<?php
/**
 * Admin Page Template
 * 
 * Main admin interface for NeuroBlock
 * 
 * @package NeuroBlock
 */

if (!defined('ABSPATH')) exit;
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
                        <?php _e('AI Generator for WordPress - Version', 'neuroblock'); ?> <?php echo NEUROBLOCK_VERSION; ?>
                    </p>
                </div>
            </div>
            <div class="neuroblock-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <?php _e('Secured', 'neuroblock'); ?>
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
                <?php _e('AI Settings', 'neuroblock'); ?>
            </button>
            <button class="neuroblock-tab" data-tab="blocks">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <?php _e('Blocks & Pages', 'neuroblock'); ?>
            </button>
            <button class="neuroblock-tab" data-tab="generator">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                <?php _e('Generator', 'neuroblock'); ?>
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
                        <strong><?php _e('Configure your AI API', 'neuroblock'); ?></strong>
                        <p><?php _e('Connect your own API to use NeuroBlock for free. Your keys are encrypted and stored securely. Each provider needs its own API key.', 'neuroblock'); ?></p>
                    </div>
                </div>
                
                <form id="neuroblock-settings-form">
                    <div class="neuroblock-form-group">
                        <label class="neuroblock-label"><?php _e('AI Provider', 'neuroblock'); ?></label>
                        <select name="provider" id="nb-provider" class="neuroblock-select">
                            <?php foreach ($providers as $key => $provider): ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php selected($current_provider, $key); ?>>
                                    <?php echo esc_html($provider['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="neuroblock-form-group">
                        <label class="neuroblock-label"><?php _e('API Key', 'neuroblock'); ?></label>
                        <input type="password" name="api_key" id="nb-api-key" class="neuroblock-input" placeholder="<?php esc_attr_e('Enter your API key...', 'neuroblock'); ?>" value="<?php echo esc_attr($masked_key); ?>" />
                        <span class="neuroblock-helper-text">
                            <?php if ($has_api_key): ?>
                                <?php _e('API key is configured. Enter a new key to update it.', 'neuroblock'); ?>
                            <?php else: ?>
                                <?php _e('Your API key is encrypted and never shared', 'neuroblock'); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <button type="submit" class="neuroblock-btn neuroblock-btn-primary neuroblock-btn-block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        <?php _e('Save Settings', 'neuroblock'); ?>
                    </button>
                </form>
            </div>
            
            <!-- Blocks Tab -->
            <div id="tab-blocks" class="neuroblock-tab-panel" style="display:none;">
                <!-- Quick Create Cards -->
                <div class="neuroblock-grid neuroblock-grid-2" style="margin-bottom: 40px;">
                    <div class="neuroblock-card">
                        <svg class="neuroblock-card-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="16 18 22 12 16 6"></polyline>
                            <polyline points="8 6 2 12 8 18"></polyline>
                        </svg>
                        <h3 class="neuroblock-card-title"><?php _e('Gutenberg Block', 'neuroblock'); ?></h3>
                        <p class="neuroblock-card-text"><?php _e('Create a new custom block with AI', 'neuroblock'); ?></p>
                    </div>
                    <div class="neuroblock-card">
                        <svg class="neuroblock-card-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <h3 class="neuroblock-card-title"><?php _e('Complete Page', 'neuroblock'); ?></h3>
                        <p class="neuroblock-card-text"><?php _e('Generate a complete WordPress page', 'neuroblock'); ?></p>
                    </div>
                </div>
                
                <!-- Recent Generated Blocks (from localStorage) -->
                <div class="neuroblock-recent-list" style="margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h3 class="neuroblock-recent-title"><?php _e('Recent Blocks & Widgets', 'neuroblock'); ?></h3>
                        <button type="button" id="clear-blocks-history" class="neuroblock-btn neuroblock-btn-secondary" style="padding: 8px 16px; font-size: 0.85rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <?php _e('Clear History', 'neuroblock'); ?>
                        </button>
                    </div>
                    <div id="recent-blocks-container">
                        <div class="neuroblock-alert neuroblock-alert-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <div>
                                <p style="margin: 0;"><?php _e('Blocks and widgets you generate will appear here. They are stored locally in your browser.', 'neuroblock'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Generator Tab -->
            <div id="tab-generator" class="neuroblock-tab-panel" style="display:none;">
                <form id="neuroblock-generator-form">
                    <div class="neuroblock-form-group">
                        <label class="neuroblock-label"><?php _e('Describe what you want to create', 'neuroblock'); ?></label>
                        <textarea name="prompt" class="neuroblock-textarea" rows="6" placeholder="<?php esc_attr_e('Ex: Create a modern hero section with a catchy title, subtitle and CTA button...', 'neuroblock'); ?>"></textarea>
                    </div>
                    
                    <div class="neuroblock-grid neuroblock-grid-2">
                        <div class="neuroblock-form-group">
                            <label class="neuroblock-label"><?php _e('AI Provider', 'neuroblock'); ?></label>
                            <select name="provider" id="nb-gen-provider" class="neuroblock-select">
                                <?php foreach ($providers as $key => $provider): ?>
                                    <option value="<?php echo esc_attr($key); ?>" <?php selected($current_provider, $key); ?>>
                                        <?php echo esc_html($provider['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="neuroblock-form-group">
                            <label class="neuroblock-label"><?php _e('Model', 'neuroblock'); ?></label>
                            <select name="model" id="nb-gen-model" class="neuroblock-select">
                                <?php 
                                $provider_models = $providers[$current_provider]['models'];
                                foreach ($provider_models as $model): 
                                ?>
                                    <option value="<?php echo esc_attr($model); ?>">
                                        <?php echo esc_html($model); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="neuroblock-grid neuroblock-grid-2">
                        <div class="neuroblock-form-group">
                            <label class="neuroblock-label"><?php _e('Content Type', 'neuroblock'); ?></label>
                            <select name="type" class="neuroblock-select">
                                <option value="block"><?php _e('Gutenberg Block', 'neuroblock'); ?></option>
                                <option value="elementor"><?php _e('Elementor Widget', 'neuroblock'); ?></option>
                                <option value="page-elementor"><?php _e('Complete Elementor Page', 'neuroblock'); ?></option>
                                <option value="page-gutenberg"><?php _e('Complete Gutenberg Page', 'neuroblock'); ?></option>
                            </select>
                        </div>
                        <div class="neuroblock-form-group">
                            <label class="neuroblock-label"><?php _e('Style', 'neuroblock'); ?></label>
                            <select name="style" class="neuroblock-select">
                                <option value="modern"><?php _e('Modern', 'neuroblock'); ?></option>
                                <option value="minimal"><?php _e('Minimalist', 'neuroblock'); ?></option>
                                <option value="professional"><?php _e('Professional', 'neuroblock'); ?></option>
                                <option value="creative"><?php _e('Creative', 'neuroblock'); ?></option>
                                <option value="elegant"><?php _e('Elegant', 'neuroblock'); ?></option>
                                <option value="classic"><?php _e('Classic', 'neuroblock'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="neuroblock-btn neuroblock-btn-primary neuroblock-btn-block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                        <?php _e('Generate with AI', 'neuroblock'); ?>
                    </button>
                </form>
                
                <div id="generated-output" style="display:none; margin-top: 24px;">
                    <div class="neuroblock-alert neuroblock-alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <div>
                            <strong><?php _e('Content generated successfully!', 'neuroblock'); ?></strong>
                        </div>
                    </div>
                    <textarea id="generated-code" class="neuroblock-textarea" rows="15" readonly></textarea>
                    <button type="button" class="neuroblock-btn neuroblock-btn-secondary neuroblock-btn-block neuroblock-copy-code" style="margin-top: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        <?php _e('Copy Code', 'neuroblock'); ?>
                    </button>
                </div>
            </div>
            
            <!-- Support Tab -->
            <div id="tab-support" class="neuroblock-tab-panel" style="display:none;">
                <div class="neuroblock-donation-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    <h2><?php _e('Support NeuroBlock', 'neuroblock'); ?></h2>
                    <p><?php _e('NeuroBlock is free and open source. Your support helps us continue development.', 'neuroblock'); ?></p>
                </div>
                
                <div class="neuroblock-crypto-box" style="border-color: #f97316; cursor: pointer;">
                    <div class="neuroblock-crypto-header">
                        <h3 class="neuroblock-crypto-name">Bitcoin (BTC)</h3>
                        <span class="neuroblock-crypto-badge"><?php _e('Recommended', 'neuroblock'); ?></span>
                    </div>
                    <code class="neuroblock-crypto-address">bc1qxlk60lzk5uxk8qlvdzv8qtdndhxkzj5z48vd9q</code>
                </div>
                
                <div class="neuroblock-crypto-box" style="cursor: pointer;">
                    <div class="neuroblock-crypto-header">
                        <h3 class="neuroblock-crypto-name">Monero (XMR)</h3>
                    </div>
                    <code class="neuroblock-crypto-address">4BGPRrrZvAi8hqKVHfM5pi2MGwTFfBqeXQe8WuUp94QRdxanQfd8FxCCsY9XqQgHwaCoGNLrSokz6KXWVZWPELVLAZ9tYG7</code>
                </div>
                
                <div class="neuroblock-crypto-box" style="cursor: pointer;">
                    <div class="neuroblock-crypto-header">
                        <h3 class="neuroblock-crypto-name">Tether (TRC20)</h3>
                    </div>
                    <code class="neuroblock-crypto-address">TTfrG2RDLuZrmbZyCQXRiXemx1QdjtKj7G</code>
                </div>
                
                <div style="text-align: center; margin-top: 24px; color: #64748b;">
                    <p><?php _e('Thank you for your support! 🙏', 'neuroblock'); ?></p>
                </div>
            </div>
            
        </div>
    </div>
    
</div>