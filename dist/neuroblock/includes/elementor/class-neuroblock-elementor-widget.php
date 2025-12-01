<?php
/**
 * NeuroBlock Elementor Widget
 * 
 * Custom Elementor widget for AI-generated content
 * 
 * @package NeuroBlock
 */

if (!defined('ABSPATH')) exit;

class NeuroBlock_Elementor_Widget extends \Elementor\Widget_Base {
    
    /**
     * Get widget name
     * 
     * @return string Widget name
     */
    public function get_name() {
        return 'neuroblock_ai_content';
    }
    
    /**
     * Get widget title
     * 
     * @return string Widget title
     */
    public function get_title() {
        return __('NeuroBlock AI Content', 'neuroblock');
    }
    
    /**
     * Get widget icon
     * 
     * @return string Widget icon
     */
    public function get_icon() {
        return 'eicon-code';
    }
    
    /**
     * Get widget categories
     * 
     * @return array Widget categories
     */
    public function get_categories() {
        return ['general'];
    }
    
    /**
     * Get widget keywords
     * 
     * @return array Widget keywords
     */
    public function get_keywords() {
        return ['ai', 'neuroblock', 'generated', 'artificial intelligence'];
    }
    
    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'neuroblock'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'ai_content',
            [
                'label' => __('AI Generated Content', 'neuroblock'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __('Use NeuroBlock generator to create AI content, then paste it here.', 'neuroblock'),
                'description' => __('Go to NeuroBlock → Generator to create AI-powered content', 'neuroblock'),
            ]
        );
        
        $this->add_control(
            'open_generator',
            [
                'label' => __('Open NeuroBlock Generator', 'neuroblock'),
                'type' => \Elementor\Controls_Manager::BUTTON,
                'text' => __('Open Generator', 'neuroblock'),
                'event' => 'neuroblock:open_generator',
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_section();
        
        // Style section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'neuroblock'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Padding', 'neuroblock'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .neuroblock-ai-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'content_margin',
            [
                'label' => __('Margin', 'neuroblock'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .neuroblock-ai-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        echo '<div class="neuroblock-ai-content">';
        echo wp_kses_post($settings['ai_content']);
        echo '</div>';
    }
    
    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <div class="neuroblock-ai-content">
            {{{ settings.ai_content }}}
        </div>
        <?php
    }
}