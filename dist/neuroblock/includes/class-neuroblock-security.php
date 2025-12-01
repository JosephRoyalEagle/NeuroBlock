<?php
if (!defined('ABSPATH')) exit;

class NeuroBlock_Security {
    
    /**
     * Encrypt API key
     */
    public static function encrypt_api_key($api_key) {
        if (empty($api_key)) return '';
        
        // Use WordPress salts for encryption
        $key = wp_salt('auth');
        $encrypted = base64_encode(openssl_encrypt($api_key, 'AES-256-CBC', $key, 0, substr($key, 0, 16)));
        
        return $encrypted;
    }
    
    /**
     * Decrypt API key
     */
    public static function decrypt_api_key($encrypted_key) {
        if (empty($encrypted_key)) return '';
        
        $key = wp_salt('auth');
        $decrypted = openssl_decrypt(base64_decode($encrypted_key), 'AES-256-CBC', $key, 0, substr($key, 0, 16));
        
        return $decrypted;
    }
    
    /**
     * Sanitize prompt
     */
    public static function sanitize_prompt($prompt) {
        return wp_kses_post($prompt);
    }
    
    /**
     * Verify nonce
     */
    public static function verify_nonce($nonce, $action = 'neuroblock_action') {
        return wp_verify_nonce($nonce, $action);
    }
}