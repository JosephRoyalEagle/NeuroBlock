<?php
if (!defined('ABSPATH')) exit;

class NeuroBlock_API {
    
    private $api_providers = [
        'openai' => [
            'name' => 'OpenAI',
            'endpoint' => 'https://api.openai.com/v1/chat/completions',
            'models' => ['gpt-4', 'gpt-3.5-turbo', 'gpt-4-turbo']
        ],
        'deepseek' => [
            'name' => 'DeepSeek',
            'endpoint' => 'https://api.deepseek.com/v1/chat/completions',
            'models' => ['deepseek-chat', 'deepseek-coder']
        ],
        'gemini' => [
            'name' => 'Google Gemini',
            'endpoint' => 'https://generativelanguage.googleapis.com/v1/models',
            'models' => ['gemini-pro', 'gemini-ultra']
        ],
        'ollama' => [
            'name' => 'Ollama (Local)',
            'endpoint' => 'http://localhost:11434/api/chat',
            'models' => ['llama2', 'mistral', 'codellama']
        ]
    ];
    
    /**
     * Get available providers
     */
    public function get_providers() {
        return $this->api_providers;
    }
    
    /**
     * Call AI API
     */
    public function call_ai($prompt, $options = []) {
        $provider = get_option('neuroblock_api_provider', 'openai');
        $encrypted_key = get_option('neuroblock_api_key', '');
        $api_key = NeuroBlock_Security::decrypt_api_key($encrypted_key);
        $model = get_option('neuroblock_model', 'gpt-4');
        
        if (empty($api_key) && $provider !== 'ollama') {
            return new WP_Error('no_api_key', __('API key not configured', 'neuroblock'));
        }
        
        $provider_config = $this->api_providers[$provider] ?? null;
        if (!$provider_config) {
            return new WP_Error('invalid_provider', __('Invalid API provider', 'neuroblock'));
        }
        
        // Build request based on provider
        switch ($provider) {
            case 'openai':
            case 'deepseek':
                return $this->call_openai_compatible($provider_config, $api_key, $model, $prompt, $options);
                
            case 'gemini':
                return $this->call_gemini($provider_config, $api_key, $model, $prompt, $options);
                
            case 'ollama':
                return $this->call_ollama($provider_config, $model, $prompt, $options);
                
            default:
                return new WP_Error('unsupported_provider', __('Unsupported provider', 'neuroblock'));
        }
    }
    
    /**
     * Call OpenAI-compatible API
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
    
    /**
     * Call Ollama (local)
     */
    private function call_ollama($config, $model, $prompt, $options) {
        $body = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'stream' => false
        ];
        
        $response = wp_remote_post($config['endpoint'], [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($body),
            'timeout' => 60
        ]);
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        return $data['message']['content'] ?? '';
    }
}