<?php
if (!defined('ABSPATH')) exit;

class NeuroBlock_Admin {
    
    private $api;
    
    public function __construct() {
        $this->api = new NeuroBlock_API();
    }
    
    public function init() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_ajax_neuroblock_generate', [$this, 'ajax_generate_content']);
        add_action('wp_ajax_neuroblock_save_settings', [$this, 'ajax_save_settings']);
    }
    
    /**
     * Add admin menu
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
     * Get menu icon SVG
     */
    private function get_icon_svg() {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h2v2H7v-2zm8 0h-2v2h2v-2zm0 4h-2v2h2v-2zm-4 0h-2v2h2v-2zm4-8h-2v2h2V7zM7 7h2v2H7V7z"/>
            </svg>
        ');
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_assets($hook) {
        if ($hook !== 'toplevel_page_neuroblock') {
            return;
        }
        
        wp_enqueue_style(
            'neuroblock-admin-css',
            NEUROBLOCK_PLUGIN_URL . 'assets/css/neuroblock-admin.css',
            [],
            NEUROBLOCK_VERSION
        );
        
        wp_enqueue_script(
            'neuroblock-admin-js',
            NEUROBLOCK_PLUGIN_URL . 'assets/js/neuroblock-admin.js',
            ['jquery'],
            NEUROBLOCK_VERSION,
            true
        );
        
        wp_localize_script('neuroblock-admin-js', 'neuroblock', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('neuroblock_nonce'),
            'strings' => [
                'generating' => __('Génération en cours...', 'neuroblock'),
                'success' => __('Contenu généré avec succès !', 'neuroblock'),
                'error' => __('Erreur lors de la génération', 'neuroblock'),
                'saved' => __('Paramètres sauvegardés', 'neuroblock')
            ]
        ]);
    }
    
    /**
     * Register settings
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
        $model = sanitize_text_field($_POST['model'] ?? '');
        
        // Encrypt API key
        $encrypted_key = NeuroBlock_Security::encrypt_api_key($api_key);
        
        update_option('neuroblock_api_provider', $provider);
        update_option('neuroblock_api_key', $encrypted_key);
        update_option('neuroblock_model', $model);
        
        wp_send_json_success(['message' => __('Settings saved successfully', 'neuroblock')]);
    }
    
    /**
     * AJAX: Generate content
     */
    public function ajax_generate_content() {
        check_ajax_referer('neuroblock_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'neuroblock')]);
        }
        
        $prompt = sanitize_textarea_field($_POST['prompt'] ?? '');
        $type = sanitize_text_field($_POST['type'] ?? 'block');
        $style = sanitize_text_field($_POST['style'] ?? 'modern');
        
        if (empty($prompt)) {
            wp_send_json_error(['message' => __('Prompt is required', 'neuroblock')]);
        }
        
        // Build enhanced prompt
        $enhanced_prompt = $this->build_prompt($prompt, $type, $style);
        
        // Call AI
        $result = $this->api->call_ai($enhanced_prompt);
        
        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }
        
        wp_send_json_success([
            'content' => $result,
            'type' => $type
        ]);
    }
    
    /**
     * Build enhanced prompt
     */
    private function build_prompt($user_prompt, $type, $style) {
        $base_prompt = "You are a WordPress expert. Generate clean, modern, and responsive code.\n\n";
        
        switch ($type) {
            case 'block':
                $base_prompt .= "Create a Gutenberg block with the following requirements:\n";
                $base_prompt .= "- Use modern HTML5 and semantic tags\n";
                $base_prompt .= "- Include inline CSS for styling\n";
                $base_prompt .= "- Make it responsive and mobile-friendly\n";
                $base_prompt .= "- Style: {$style}\n\n";
                break;
                
            case 'page':
                $base_prompt .= "Create a complete WordPress page with:\n";
                $base_prompt .= "- Full HTML structure\n";
                $base_prompt .= "- Multiple sections\n";
                $base_prompt .= "- Responsive design\n";
                $base_prompt .= "- Style: {$style}\n\n";
                break;
                
            case 'elementor':
                $base_prompt .= "Create an Elementor-compatible widget with:\n";
                $base_prompt .= "- Clean HTML structure\n";
                $base_prompt .= "- CSS classes for Elementor\n";
                $base_prompt .= "- Style: {$style}\n\n";
                break;
        }
        
        $base_prompt .= "User request: {$user_prompt}\n\n";
        $base_prompt .= "Provide only the HTML and CSS code without explanations.";
        
        return $base_prompt;
    }
    
    /**
     * Render admin page
     */
    public function admin_page() {
        $providers = $this->api->get_providers();
        $current_provider = get_option('neuroblock_api_provider', 'openai');
        $current_model = get_option('neuroblock_model', 'gpt-4');
        ?>
        <div class="neuroblock-admin-wrap">
            
            <!-- Header -->
            <div class="neuroblock-header">
                <div class="neuroblock-header-content">
                    <div class="neuroblock-logo-section">
                        <div class="neuroblock-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h2v2H7v-2zm8 0h-2v2h2v-2zm0 4h-2v2h2v-2zm-4 0h-2v2h2v-2zm4-8h-2v2h2V7zM7 7h2v2H7V7z"/>
                            </svg>
                        </div>
                        <div class="neuroblock-title-section">
                            <h1><?php _e('NeuroBlock', 'neuroblock'); ?></h1>
                            <p class="neuroblock-subtitle">
                                <?php _e('Générateur IA pour WordPress - Version', 'neuroblock'); ?> <?php echo NEUROBLOCK_VERSION; ?>
                            </p>
                        </div>
                    </div>
                    <div class="neuroblock-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <?php _e('Sécurisé', 'neuroblock'); ?>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="neuroblock-nav">
                <div class="neuroblock-tabs">
                    <button class="neuroblock-tab active" data-tab="settings">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 1v6m0 6v6m5.2-13.2l-4.2 4.2m0 6l4.2 4.2M23 12h-6m-6 0H1m18.8-5.2l-4.2 4.2m0 6l4.2 4.2"></path>
                        </svg>
                        <?php _e('Paramètres IA', 'neuroblock'); ?>
                    </button>
                    <button class="neuroblock-tab" data-tab="blocks">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <?php _e('Blocs & Pages', 'neuroblock'); ?>
                    </button>
                    <button class="neuroblock-tab" data-tab="generator">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                        <?php _e('Générateur', 'neuroblock'); ?>
                    </button>
                    <button class="neuroblock-tab" data-tab="support">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <?php _e('Support', 'neuroblock'); ?>
                    </button>
                </div>
                
                <!-- Tab Content -->
                <div class="neuroblock-tab-content">
                    
                    <!-- Settings Tab -->
                    <div id="tab-settings" class="neuroblock-tab-panel active">
                        <div class="neuroblock-alert neuroblock-alert-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <div>
                                <strong><?php _e('Configuration de votre API IA', 'neuroblock'); ?></strong>
                                <p><?php _e('Connectez votre propre API pour utiliser NeuroBlock gratuitement. Vos clés sont chiffrées et stockées de manière sécurisée.', 'neuroblock'); ?></p>
                            </div>
                        </div>
                        
                        <form id="neuroblock-settings-form">
                            <div class="neuroblock-form-group">
                                <label class="neuroblock-label"><?php _e('Fournisseur IA', 'neuroblock'); ?></label>
                                <select name="provider" id="nb-provider" class="neuroblock-select">
                                    <?php foreach ($providers as $key => $provider): ?>
                                        <option value="<?php echo esc_attr($key); ?>" <?php selected($current_provider, $key); ?>>
                                            <?php echo esc_html($provider['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="neuroblock-form-group">
                                <label class="neuroblock-label"><?php _e('Clé API', 'neuroblock'); ?></label>
                                <input type="password" name="api_key" class="neuroblock-input" placeholder="sk-..." />
                                <span class="neuroblock-helper-text">
                                    <?php _e('Votre clé API est chiffrée et jamais partagée', 'neuroblock'); ?>
                                </span>
                            </div>
                            
                            <div class="neuroblock-form-group">
                                <label class="neuroblock-label"><?php _e('Modèle', 'neuroblock'); ?></label>
                                <select name="model" id="nb-model" class="neuroblock-select">
                                    <?php 
                                    $provider_models = $providers[$current_provider]['models'];
                                    foreach ($provider_models as $model): 
                                    ?>
                                        <option value="<?php echo esc_attr($model); ?>" <?php selected($current_model, $model); ?>>
                                            <?php echo esc_html($model); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="neuroblock-btn neuroblock-btn-primary neuroblock-btn-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                <?php _e('Enregistrer les paramètres', 'neuroblock'); ?>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Blocks Tab -->
                    <div id="tab-blocks" class="neuroblock-tab-panel" style="display:none;">
                        <div class="neuroblock-grid neuroblock-grid-2">
                            <div class="neuroblock-card">
                                <svg class="neuroblock-card-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="16 18 22 12 16 6"></polyline>
                                    <polyline points="8 6 2 12 8 18"></polyline>
                                </svg>
                                <h3 class="neuroblock-card-title"><?php _e('Bloc Gutenberg', 'neuroblock'); ?></h3>
                                <p class="neuroblock-card-text"><?php _e('Créer un nouveau bloc personnalisé avec IA', 'neuroblock'); ?></p>
                            </div>
                            <div class="neuroblock-card">
                                <svg class="neuroblock-card-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <h3 class="neuroblock-card-title"><?php _e('Page complète', 'neuroblock'); ?></h3>
                                <p class="neuroblock-card-text"><?php _e('Générer une page WordPress complète', 'neuroblock'); ?></p>
                            </div>
                        </div>
                        
                        <div class="neuroblock-recent-list" style="margin-top: 24px;">
                            <h3 class="neuroblock-recent-title"><?php _e('Blocs récents', 'neuroblock'); ?></h3>
                            <div class="neuroblock-recent-item">
                                <div class="neuroblock-recent-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                    </svg>
                                    <span class="neuroblock-recent-name">Hero Section</span>
                                </div>
                                <span class="neuroblock-recent-time">Il y a 1h</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Generator Tab -->
                    <div id="tab-generator" class="neuroblock-tab-panel" style="display:none;">
                        <form id="neuroblock-generator-form">
                            <div class="neuroblock-form-group">
                                <label class="neuroblock-label"><?php _e('Décrivez ce que vous voulez créer', 'neuroblock'); ?></label>
                                <textarea name="prompt" class="neuroblock-textarea" rows="6" placeholder="Ex: Créer une section hero moderne avec un titre accrocheur, un sous-titre et un bouton CTA..."></textarea>
                            </div>
                            
                            <div class="neuroblock-grid neuroblock-grid-2">
                                <div class="neuroblock-form-group">
                                    <label class="neuroblock-label"><?php _e('Type de contenu', 'neuroblock'); ?></label>
                                    <select name="type" class="neuroblock-select">
                                        <option value="block">Bloc Gutenberg</option>
                                        <option value="elementor">Widget Elementor</option>
                                        <option value="page-elementor">Page complète Elementor</option>
                                        <option value="page-gutenberg">Page complète Gutenberg</option>
                                    </select>
                                </div>
                                <div class="neuroblock-form-group">
                                    <label class="neuroblock-label"><?php _e('Style', 'neuroblock'); ?></label>
                                    <select name="style" class="neuroblock-select">
                                        <option value="modern">Moderne</option>
                                        <option value="minimal">Minimaliste</option>
                                        <option value="professional">Professionnel</option>
                                        <option value="creative">Créatif</option>
                                        <option value="elegant">Élégant</option>
                                        <option value="classic">Classique</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="submit" class="neuroblock-btn neuroblock-btn-primary neuroblock-btn-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                                <?php _e('Générer avec IA', 'neuroblock'); ?>
                            </button>
                        </form>
                        
                        <div id="generated-output" style="display:none; margin-top: 24px;">
                            <div class="neuroblock-alert neuroblock-alert-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <div>
                                    <strong><?php _e('Contenu généré avec succès !', 'neuroblock'); ?></strong>
                                </div>
                            </div>
                            <div class="neuroblock-form-group">
                                <label class="neuroblock-label"><?php _e('Code généré', 'neuroblock'); ?></label>
                                <textarea id="generated-code" class="neuroblock-textarea" rows="15" readonly></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Support Tab -->
                    <div id="tab-support" class="neuroblock-tab-panel" style="display:none;">
                        <div class="neuroblock-donation-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                            <h2><?php _e('Soutenez NeuroBlock', 'neuroblock'); ?></h2>
                            <p><?php _e('NeuroBlock est gratuit et open source. Votre soutien nous aide à continuer le développement.', 'neuroblock'); ?></p>
                        </div>
                        
                        <div class="neuroblock-crypto-box" style="border-color: #f97316;">
                            <div class="neuroblock-crypto-header">
                                <h3 class="neuroblock-crypto-name">Bitcoin (BTC)</h3>
                                <span class="neuroblock-crypto-badge"><?php _e('Recommandé', 'neuroblock'); ?></span>
                            </div>
                            <code class="neuroblock-crypto-address">bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh</code>
                        </div>
                        
                        <div class="neuroblock-crypto-box">
                            <div class="neuroblock-crypto-header">
                                <h3 class="neuroblock-crypto-name">Monero (XMR)</h3>
                            </div>
                            <code class="neuroblock-crypto-address">4AdUndXHHZ6cfufTMvppY6JwXNouMBzSkbLYfpAV5Usx3skxNgYeYTRj5UzqtReoS44qo9mtmXCqY45DJ852K5Jv2684Rge</code>
                        </div>
                        
                        <div class="neuroblock-crypto-box">
                            <div class="neuroblock-crypto-header">
                                <h3 class="neuroblock-crypto-name">Tether (USDT)</h3>
                            </div>
                            <code class="neuroblock-crypto-address">0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb5</code>
                        </div>
                        
                        <div style="text-align: center; margin-top: 24px; color: #64748b;">
                            <p><?php _e('Merci pour votre soutien ! 🙏', 'neuroblock'); ?></p>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
        <?php
    }
}