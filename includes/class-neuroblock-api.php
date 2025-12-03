<?php
/**
 * NeuroBlock API Handler
 * 
 * Manages API calls to various AI providers
 * 
 * @package NeuroBlock
 */

if (!defined('ABSPATH')) exit;

class NeuroBlock_API {
    
    /**
     * Available API providers configuration
     * 
     * @var array
     */
    private $api_providers = [
        'openai' => [
            'name' => 'OpenAI',
            'endpoint' => 'https://api.openai.com/v1/chat/completions',
            'models' => ['gpt-4o-mini']
        ],
        'deepseek' => [
            'name' => 'DeepSeek',
            'endpoint' => 'https://api.deepseek.com/v1/chat/completions',
            'models' => ['deepseek-chat', 'deepseek-coder']
        ],
        'gemini' => [
            'name' => 'Google Gemini',
            'endpoint' => 'https://generativelanguage.googleapis.com/v1/models',
            'models' => ['gemini-2.5-flash', 'gemini-2.5-flash-lite']
        ],
        'mistral' => [
            'name' => 'Mistral AI',
            'endpoint' => 'https://api.mistral.ai/v1/chat/completions',
            'models' => ['mistral-small']
        ]
    ];
    
    /**
     * Get available providers
     * 
     * @return array List of available API providers
     */
    public function get_providers() {
        return $this->api_providers;
    }
    
    /**
     * Call AI API based on configured provider
     * 
     * @param string $prompt User prompt to send to AI
     * @param array $options Optional parameters (provider, model, max_tokens, temperature, etc.)
     * @return string|WP_Error Generated content or error
     */
    public function call_ai($prompt, $options = []) {
        $provider = $options['provider'] ?? get_option('neuroblock_api_provider', 'openai');
        $model = $options['model'] ?? '';
        
        $encrypted_key = get_option('neuroblock_api_key_' . $provider, '');
        $api_key = NeuroBlock_Security::decrypt_api_key($encrypted_key);
        
        if (empty($api_key)) {
            return new WP_Error('no_api_key', __('API key not configured', 'neuroblock'));
        }
        
        $provider_config = $this->api_providers[$provider] ?? null;
        if (!$provider_config) {
            return new WP_Error('invalid_provider', __('Invalid API provider', 'neuroblock'));
        }
        
        // Use first model if not specified
        if (empty($model)) {
            $model = $provider_config['models'][0];
        }
        
        // Build request based on provider
        switch ($provider) {
            case 'openai':
            case 'deepseek':
            case 'mistral':
                return $this->call_openai_compatible($provider_config, $api_key, $model, $prompt, $options);
                
            case 'gemini':
                return $this->call_gemini($provider_config, $api_key, $model, $prompt, $options);
                
            default:
                return new WP_Error('unsupported_provider', __('Unsupported provider', 'neuroblock'));
        }
    }
    
    /**
     * Call OpenAI-compatible API (OpenAI, DeepSeek, Mistral)
     * 
     * @param array $config Provider configuration
     * @param string $api_key API key
     * @param string $model Model name
     * @param string $prompt User prompt
     * @param array $options Additional options
     * @return string|WP_Error Generated content or error
     */
    private function call_openai_compatible($config, $api_key, $model, $prompt, $options) {
        $max_tokens = $options['max_tokens'] ?? get_option('neuroblock_max_tokens', 2000);
        $temperature = $options['temperature'] ?? get_option('neuroblock_temperature', 0.7);
        
        $body = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant that generates WordPress content, HTML, and CSS.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => intval($max_tokens),
            'temperature' => floatval($temperature)
        ];
        
        $response = wp_remote_post($config['endpoint'], [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($body),
            'timeout' => 30
        ]);
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['error'])) {
            return new WP_Error('api_error', $data['error']['message'] ?? __('API error', 'neuroblock'));
        }
        
        return $data['choices'][0]['message']['content'] ?? '';
    }
    
    /**
     * Call Gemini API
     * 
     * @param array $config Provider configuration
     * @param string $api_key API key
     * @param string $model Model name
     * @param string $prompt User prompt
     * @param array $options Additional options
     * @return string|WP_Error Generated content or error
     */
    private function call_gemini($config, $api_key, $model, $prompt, $options) {
        $endpoint = $config['endpoint'] . '/' . $model . ':generateContent?key=' . $api_key;
        
        $body = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];
        
        $response = wp_remote_post($endpoint, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($body),
            'timeout' => 30
        ]);
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }
}