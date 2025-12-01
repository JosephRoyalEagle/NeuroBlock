<?php
if (!defined('ABSPATH')) exit;

class NeuroBlock_Blocks {
    
    public function init() {
        add_action('init', [$this, 'register_blocks']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);
    }
    
    /**
     * Register Gutenberg blocks
     */
    public function register_blocks() {
        if (!function_exists('register_block_type')) {
            return;
        }
        
        // Register AI-generated block
        register_block_type('neuroblock/ai-content', [
            'api_version' => 2,
            'title' => __('NeuroBlock AI Content', 'neuroblock'),
            'description' => __('Bloc généré par IA', 'neuroblock'),
            'category' => 'common',
            'icon' => 'admin-generic',
            'keywords' => ['ai', 'neuroblock', 'generated'],
            'attributes' => [
                'content' => [
                    'type' => 'string',
                    'default' => ''
                ],
                'generatedBy' => [
                    'type' => 'string',
                    'default' => 'neuroblock'
                ]
            ],
            'render_callback' => [$this, 'render_ai_block'],
            'editor_script' => 'neuroblock-blocks-js',
            'editor_style' => 'neuroblock-blocks-editor-css',
            'style' => 'neuroblock-blocks-css'
        ]);
    }
    
    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'neuroblock-blocks-js',
            NEUROBLOCK_PLUGIN_URL . 'assets/js/neuroblock-blocks.js',
            ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components'],
            NEUROBLOCK_VERSION,
            true
        );
        
        wp_enqueue_style(
            'neuroblock-blocks-editor-css',
            NEUROBLOCK_PLUGIN_URL . 'assets/css/neuroblock-blocks-editor.css',
            ['wp-edit-blocks'],
            NEUROBLOCK_VERSION
        );
        
        wp_enqueue_style(
            'neuroblock-blocks-css',
            NEUROBLOCK_PLUGIN_URL . 'assets/css/neuroblock-blocks.css',
            [],
            NEUROBLOCK_VERSION
        );
    }
    
    /**
     * Render AI block
     */
    public function render_ai_block($attributes) {
        $content = isset($attributes['content']) ? $attributes['content'] : '';
        
        if (empty($content)) {
            return '<div class="neuroblock-placeholder">' . 
                   __('Utilisez NeuroBlock pour générer du contenu IA', 'neuroblock') . 
                   '</div>';
        }
        
        // Sanitize and return content
        return '<div class="neuroblock-ai-content">' . wp_kses_post($content) . '</div>';
    }
    
    /**
     * Create custom Elementor widget
     */
    public function register_elementor_widgets() {
        // Check if Elementor is active
        if (!did_action('elementor/loaded')) {
            return;
        }
        
        require_once NEUROBLOCK_PLUGIN_DIR . 'includes/elementor/class-neuroblock-elementor-widget.php';
        
        add_action('elementor/widgets/widgets_registered', function() {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(
                new \NeuroBlock_Elementor_Widget()
            );
        });
    }
}