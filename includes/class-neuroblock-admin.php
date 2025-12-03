<?php
/**
 * NeuroBlock Admin Interface
 * 
 * Handles the WordPress admin dashboard interface and AJAX requests
 * 
 * @package NeuroBlock
 */

if (!defined('ABSPATH')) exit;

class NeuroBlock_Admin {
    
    /**
     * @var NeuroBlock_API API handler instance
     */
    private $api;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->api = new NeuroBlock_API();
    }
    
    /**
     * Initialize admin hooks
     */
    public function init() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_ajax_neuroblock_generate', [$this, 'ajax_generate_content']);
        add_action('wp_ajax_neuroblock_save_settings', [$this, 'ajax_save_settings']);
        add_action('wp_ajax_neuroblock_check_api_key', [$this, 'ajax_check_api_key']);
        add_action('wp_ajax_neuroblock_get_masked_key', [$this, 'ajax_get_masked_key']);
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_menu_page(
            __('NeuroBlock', 'neuroblock'),
            __('NeuroBlock', 'neuroblock'),
            'manage_options',
            'neuroblock',
            [$this, 'admin_page'],
            $this->get_icon_svg(),
            26
        );
    }
    
    /**
     * Get menu icon SVG (brain icon)
     * 
     * @return string Base64 encoded SVG
     */
    private function get_icon_svg() {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h2v2H7v-2zm8 0h-2v2h2v-2zm0 4h-2v2h2v-2zm-4 0h-2v2h2v-2zm4-8h-2v2h2V7zM7 7h2v2H7V7z"/>
            </svg>
        ');
    }
    
    /**
     * Enqueue admin assets (CSS, JS, SweetAlert2)
     * 
     * @param string $hook Current admin page hook
     */
    public function enqueue_assets($hook) {
        if ($hook !== 'toplevel_page_neuroblock') {
            return;
        }
        
        // Enqueue SweetAlert2
        wp_enqueue_style(
            'sweetalert2-css',
            'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
            [],
            '11.0.0'
        );
        
        wp_enqueue_script(
            'sweetalert2-js',
            'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js',
            [],
            '11.0.0',
            true
        );
        
        // Enqueue plugin styles
        wp_enqueue_style(
            'neuroblock-admin-css',
            NEUROBLOCK_PLUGIN_URL . 'assets/css/neuroblock-admin.css',
            [],
            NEUROBLOCK_VERSION
        );
        
        // Enqueue plugin scripts
        wp_enqueue_script(
            'neuroblock-admin-js',
            NEUROBLOCK_PLUGIN_URL . 'assets/js/neuroblock-admin.js',
            ['jquery', 'sweetalert2-js'],
            NEUROBLOCK_VERSION,
            true
        );
        
        // Check if Gutenberg and Elementor are active
        $has_gutenberg = function_exists('register_block_type');
        $has_elementor = did_action('elementor/loaded');
        
        // Localize script with translations and config
        wp_localize_script('neuroblock-admin-js', 'neuroblock', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('neuroblock_nonce'),
            'hasGutenberg' => $has_gutenberg,
            'hasElementor' => $has_elementor,
            'strings' => [
                'generating' => __('Generating...', 'neuroblock'),
                'pleaseWait' => __('Please wait while AI generates your content...', 'neuroblock'),
                'success' => __('Success!', 'neuroblock'),
                'error' => __('Error', 'neuroblock'),
                'warning' => __('Warning', 'neuroblock'),
                'saved' => __('Settings saved successfully', 'neuroblock'),
                'saving' => __('Saving...', 'neuroblock'),
                'copied' => __('Copied!', 'neuroblock'),
                'addressCopied' => __('Address copied to clipboard', 'neuroblock'),
                'promptRequired' => __('Please enter a description', 'neuroblock'),
                'gutenbergRequired' => __('Gutenberg is required but not installed. Please install the Gutenberg plugin.', 'neuroblock'),
                'elementorRequired' => __('Elementor is required but not installed. Please install the Elementor plugin.', 'neuroblock'),
                'blockGenerated' => __('Block generated successfully! Click the copy button below to copy the code.', 'neuroblock'),
                'pageCreated' => __('Page created successfully!', 'neuroblock'),
                'contentGenerated' => __('Content generated successfully!', 'neuroblock'),
                'viewPage' => __('View Page', 'neuroblock'),
                'ok' => __('OK', 'neuroblock'),
                'apiKeyConfigured' => __('API key is configured. Enter a new key to update it.', 'neuroblock'),
                'apiKeyEncrypted' => __('Your API key is encrypted and never shared', 'neuroblock'),
                'gutenbergInstructions' => __('1. Go to Posts/Pages → Add New<br>2. Click the "+" button<br>3. Search for "HTML"<br>4. Paste the code in the Custom HTML block', 'neuroblock'),
                'elementorInstructions' => __('1. Go to Pages → Add New<br>2. Click "Edit with Elementor"<br>3. Add an HTML widget<br>4. Paste the code in the widget', 'neuroblock'),
                
                'clearHistory' => __('Clear History', 'neuroblock'),
                'clearHistoryConfirm' => __('Are you sure you want to clear all blocks history? This cannot be undone.', 'neuroblock'),
                'historyCleared' => __('History cleared successfully', 'neuroblock'),
                'noBlocksYet' => __('No blocks generated yet. Go to Generator tab to create your first block!', 'neuroblock'),
                'recentBlocksWidgets' => __('Recent Blocks & Widgets', 'neuroblock'),
                'recentCompletePages' => __('Recent Complete Pages', 'neuroblock'),
                'pagesGenerated' => __('%d pages generated', 'neuroblock'),
                'gutenbergBlock' => __('Gutenberg Block', 'neuroblock'),
                'elementorWidget' => __('Elementor Widget', 'neuroblock'),
                'gutenbergPage' => __('Gutenberg Page', 'neuroblock'),
                'elementorPage' => __('Elementor Page', 'neuroblock'),
                'view' => __('View', 'neuroblock'),
                'edit' => __('Edit', 'neuroblock'),
                'delete' => __('Delete', 'neuroblock'),
                'deleted' => __('Deleted!', 'neuroblock'),
                'prompt' => __('Prompt', 'neuroblock'),
                'style' => __('Style', 'neuroblock'),
                'copyCode' => __('Copy Code', 'neuroblock'),
                'close' => __('Close', 'neuroblock'),
                'justNow' => __('Just now', 'neuroblock'),
                'minutesAgo' => __('minutes ago', 'neuroblock'),
                'hoursAgo' => __('hours ago', 'neuroblock'),
                'daysAgo' => __('days ago', 'neuroblock'),
                'yes' => __('Yes', 'neuroblock'),
                'cancel' => __('Cancel', 'neuroblock')
            ]
        ]);
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('neuroblock_options', 'neuroblock_api_provider');
        register_setting('neuroblock_options', 'neuroblock_api_key');
        register_setting('neuroblock_options', 'neuroblock_model');
        register_setting('neuroblock_options', 'neuroblock_max_tokens');
        register_setting('neuroblock_options', 'neuroblock_temperature');
    }

    /**
     * AJAX: Save settings
     */
    public function ajax_save_settings() {
        check_ajax_referer('neuroblock_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied', 'neuroblock')]);
        }
        
        $provider = sanitize_text_field($_POST['provider'] ?? '');
        $api_key = sanitize_text_field($_POST['api_key'] ?? '');
        
        // Only update API key if not masked (means user entered new key)
        if (!empty($api_key) && $api_key !== '••••••••••••••••••••') {
            // Encrypt API key for security
            $encrypted_key = NeuroBlock_Security::encrypt_api_key($api_key);
            update_option('neuroblock_api_key_' . $provider, $encrypted_key);
        }
        
        update_option('neuroblock_api_provider', $provider);
        
        wp_send_json_success(['message' => __('Settings saved successfully', 'neuroblock')]);
    }

    /**
     * AJAX: Check if API key exists for provider
     */
    public function ajax_check_api_key() {
        check_ajax_referer('neuroblock_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied', 'neuroblock')]);
        }
        
        $provider = sanitize_text_field($_POST['provider'] ?? '');
        $encrypted_key = get_option('neuroblock_api_key_' . $provider, '');
        
        wp_send_json_success(['has_key' => !empty($encrypted_key)]);
    }

    /**
     * AJAX: Get masked API key for provider
     */
    public function ajax_get_masked_key() {
        check_ajax_referer('neuroblock_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied', 'neuroblock')]);
        }
        
        $provider = sanitize_text_field($_POST['provider'] ?? '');
        $encrypted_key = get_option('neuroblock_api_key_' . $provider, '');
        
        $masked_key = !empty($encrypted_key) ? '••••••••••••••••••••' : '';
        
        wp_send_json_success(['masked_key' => $masked_key]);
    }
    
    /**
     * AJAX: Generate content with AI
     */
    public function ajax_generate_content() {
        check_ajax_referer('neuroblock_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'neuroblock')]);
        }
        
        $prompt = sanitize_textarea_field($_POST['prompt'] ?? '');
        $type = sanitize_text_field($_POST['type'] ?? 'block');
        $style = sanitize_text_field($_POST['style'] ?? 'modern');
        $provider = sanitize_text_field($_POST['provider'] ?? get_option('neuroblock_api_provider', 'openai'));
        $model = sanitize_text_field($_POST['model'] ?? '');
        
        if (empty($prompt)) {
            wp_send_json_error(['message' => __('Prompt is required', 'neuroblock')]);
        }
        
        // Check if API key exists for selected provider
        $encrypted_key = get_option('neuroblock_api_key_' . $provider, '');
        if (empty($encrypted_key)) {
            wp_send_json_error(['message' => sprintf(__('No API key configured for %s. Please configure it in AI Settings.', 'neuroblock'), $provider)]);
        }
        
        // Build enhanced prompt based on type
        $enhanced_prompt = $this->build_prompt($prompt, $type, $style);
        
        // Call AI API with specific provider and model
        $result = $this->api->call_ai($enhanced_prompt, [
            'provider' => $provider,
            'model' => $model
        ]);
        
        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }
        
        // For complete pages, create actual WordPress page
        $page_url = null;
        if ($type === 'page-gutenberg' || $type === 'page-elementor') {
            $page_id = $this->create_page($result, $type, $prompt);
            if (!is_wp_error($page_id)) {
                $page_url = get_permalink($page_id);
            }
        }
        
        wp_send_json_success([
            'content' => $result,
            'type' => $type,
            'pageUrl' => $page_url
        ]);
    }
    
    /**
     * Create WordPress page from generated content
     * 
     * @param string $content Generated HTML/CSS content
     * @param string $type Page type (page-gutenberg or page-elementor)
     * @param string $original_prompt Original user prompt
     * @return int|WP_Error Page ID or error
     */
    private function create_page($content, $type, $original_prompt) {
        // Extract title from prompt or use default
        $title = $this->extract_title($original_prompt);
        
        $page_data = [
            'post_title' => $title,
            'post_status' => 'draft',
            'post_type' => 'page',
            'post_author' => get_current_user_id()
        ];
        
        if ($type === 'page-gutenberg') {
            // Wrap content in HTML block for Gutenberg
            $page_data['post_content'] = '<!-- wp:html -->' . $content . '<!-- /wp:html -->';
        } else {
            // For Elementor, store as meta and set flag
            $page_data['post_content'] = $content;
            $page_id = wp_insert_post($page_data);
            
            if (!is_wp_error($page_id)) {
                // Mark as Elementor page
                update_post_meta($page_id, '_elementor_edit_mode', 'builder');
                update_post_meta($page_id, '_elementor_template_type', 'wp-page');
            }
            
            return $page_id;
        }
        
        return wp_insert_post($page_data);
    }
    
    /**
     * Extract title from prompt
     * 
     * @param string $prompt User prompt
     * @return string Extracted or default title
     */
    private function extract_title($prompt) {
        // Try to extract meaningful title
        $words = explode(' ', $prompt);
        $title_words = array_slice($words, 0, 5);
        $title = implode(' ', $title_words);
        
        if (strlen($title) > 50) {
            $title = substr($title, 0, 47) . '...';
        }
        
        return ucfirst($title) . ' - ' . __('Generated by NeuroBlock', 'neuroblock');
    }
    
    /**
     * Build enhanced AI prompt based on content type
     * 
     * @param string $user_prompt User's original prompt
     * @param string $type Content type
     * @param string $style Design style
     * @return string Enhanced prompt for AI
     */
    private function build_prompt($user_prompt, $type, $style) {
        $base_prompt = "You are a WordPress expert. Generate clean, modern, and responsive code.\n\n";
        
        switch ($type) {
            case 'block':
                $base_prompt .= "Create a Gutenberg block with the following requirements:\n";
                $base_prompt .= "- Use modern HTML5 and semantic tags\n";
                $base_prompt .= "- Include inline CSS for styling\n";
                $base_prompt .= "- Make it responsive and mobile-friendly\n";
                $base_prompt .= "- Style: {$style}\n";
                $base_prompt .= "- Provide ONLY the HTML and CSS code, no explanations\n\n";
                break;
                
            case 'page-gutenberg':
                $base_prompt .= "Create a complete WordPress Gutenberg page with:\n";
                $base_prompt .= "- Full HTML structure with semantic sections\n";
                $base_prompt .= "- Multiple sections (hero, features, content, CTA, etc.)\n";
                $base_prompt .= "- Responsive design with mobile-first approach\n";
                $base_prompt .= "- Inline CSS styling\n";
                $base_prompt .= "- Style: {$style}\n";
                $base_prompt .= "- Provide ONLY the HTML and CSS code, no explanations\n\n";
                break;
                
            case 'page-elementor':
                $base_prompt .= "Create a complete Elementor-compatible page with:\n";
                $base_prompt .= "- Full HTML structure optimized for Elementor\n";
                $base_prompt .= "- Multiple sections using Elementor-friendly markup\n";
                $base_prompt .= "- Responsive design\n";
                $base_prompt .= "- CSS classes compatible with Elementor\n";
                $base_prompt .= "- Style: {$style}\n";
                $base_prompt .= "- Provide ONLY the HTML and CSS code, no explanations\n\n";
                break;
                
            case 'elementor':
                $base_prompt .= "Create an Elementor-compatible widget with:\n";
                $base_prompt .= "- Clean HTML structure\n";
                $base_prompt .= "- CSS classes for Elementor\n";
                $base_prompt .= "- Responsive design\n";
                $base_prompt .= "- Style: {$style}\n";
                $base_prompt .= "- Provide ONLY the HTML and CSS code, no explanations\n\n";
                break;
        }
        
        $base_prompt .= "User request: {$user_prompt}\n\n";
        $base_prompt .= "Remember: Provide ONLY the code without any markdown formatting, explanations, or comments.";
        
        return $base_prompt;
    }
    
    /**
     * Render admin page HTML
     */
    public function admin_page() {
        $providers = $this->api->get_providers();
        $current_provider = get_option('neuroblock_api_provider', 'openai');
        
        // Check if API key exists and show masked version
        $encrypted_key = get_option('neuroblock_api_key_' . $current_provider, '');
        $has_api_key = !empty($encrypted_key);
        $masked_key = $has_api_key ? '••••••••••••••••••••' : '';
        
        // Get recent generated blocks/pages
        $recent_pages = get_posts([
            'post_type' => 'page',
            'posts_per_page' => 5,
            'meta_query' => [
                [
                    'key' => '_neuroblock_generated',
                    'value' => '1',
                    'compare' => '='
                ]
            ]
        ]);
        
        include NEUROBLOCK_PLUGIN_DIR . 'includes/admin-page-template.php';
    }
}