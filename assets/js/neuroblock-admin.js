jQuery(document).ready(function($) {
    'use strict';
    
    // Tab navigation
    $('.neuroblock-tab').on('click', function() {
        const tabId = $(this).data('tab');
        
        // Update active tab
        $('.neuroblock-tab').removeClass('active');
        $(this).addClass('active');
        
        // Show corresponding panel
        $('.neuroblock-tab-panel').hide();
        $('#tab-' + tabId).show();
    });
    
    // Provider models mapping
    const providerModels = {
        openai: ['gpt-4', 'gpt-3.5-turbo', 'gpt-4-turbo'],
        deepseek: ['deepseek-chat', 'deepseek-coder'],
        gemini: ['gemini-pro', 'gemini-ultra'],
        ollama: ['llama2', 'mistral', 'codellama']
    };
    
    // Update models when provider changes
    $('#nb-provider').on('change', function() {
        const provider = $(this).val();
        const models = providerModels[provider] || [];
        const $modelSelect = $('#nb-model');
        
        $modelSelect.empty();
        models.forEach(function(model) {
            $modelSelect.append(
                $('<option></option>').val(model).text(model)
            );
        });
    });
    
    // Settings form submission
    $('#neuroblock-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const buttonText = $button.html();
        
        // Show loading state
        $button.prop('disabled', true).html(
            '<span class="neuroblock-spinner"></span> ' + neuroblock.strings.generating
        );
        
        const formData = {
            action: 'neuroblock_save_settings',
            nonce: neuroblock.nonce,
            provider: $form.find('select[name="provider"]').val(),
            api_key: $form.find('input[name="api_key"]').val(),
            model: $form.find('select[name="model"]').val()
        };
        
        $.post(neuroblock.ajax_url, formData, function(response) {
            if (response.success) {
                showNotification('success', neuroblock.strings.saved);
                // Clear API key field for security
                $form.find('input[name="api_key"]').val('');
            } else {
                showNotification('error', response.data.message || neuroblock.strings.error);
            }
        }).fail(function() {
            showNotification('error', neuroblock.strings.error);
        }).always(function() {
            $button.prop('disabled', false).html(buttonText);
        });
    });
    
    // Generator form submission
    $('#neuroblock-generator-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const buttonText = $button.html();
        const $output = $('#generated-output');
        const $code = $('#generated-code');
        
        const prompt = $form.find('textarea[name="prompt"]').val().trim();
        
        if (!prompt) {
            showNotification('error', 'Veuillez entrer une description');
            return;
        }
        
        // Show loading state
        $button.prop('disabled', true).html(
            '<span class="neuroblock-spinner"></span> ' + neuroblock.strings.generating
        );
        $output.hide();
        
        const formData = {
            action: 'neuroblock_generate',
            nonce: neuroblock.nonce,
            prompt: prompt,
            type: $form.find('select[name="type"]').val(),
            style: $form.find('select[name="style"]').val()
        };
        
        $.post(neuroblock.ajax_url, formData, function(response) {
            if (response.success) {
                $code.val(response.data.content);
                $output.slideDown();
                showNotification('success', neuroblock.strings.success);
                
                // Scroll to output
                $('html, body').animate({
                    scrollTop: $output.offset().top - 100
                }, 500);
            } else {
                showNotification('error', response.data.message || neuroblock.strings.error);
            }
        }).fail(function() {
            showNotification('error', neuroblock.strings.error);
        }).always(function() {
            $button.prop('disabled', false).html(buttonText);
        });
    });
    
    // Copy code to clipboard
    $(document).on('click', '#generated-code', function() {
        $(this).select();
        document.execCommand('copy');
        showNotification('success', 'Code copié dans le presse-papier !');
    });
    
    /**
     * Show notification
     */
    function showNotification(type, message) {
        // Remove existing notifications
        $('.neuroblock-notification').remove();
        
        const iconSvg = type === 'success' 
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>'
            : '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
        
        const alertClass = type === 'success' ? 'neuroblock-alert-success' : 'neuroblock-alert-warning';
        
        const $notification = $('<div class="neuroblock-alert ' + alertClass + ' neuroblock-notification" style="position: fixed; top: 32px; right: 32px; z-index: 99999; min-width: 300px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); animation: slideInRight 0.3s ease;">' +
            iconSvg +
            '<div><strong>' + message + '</strong></div>' +
        '</div>');
        
        $('body').append($notification);
        
        // Auto remove after 3 seconds
        setTimeout(function() {
            $notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // Add slide animation CSS
    if (!$('#neuroblock-animations').length) {
        $('<style id="neuroblock-animations">@keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }</style>').appendTo('head');
    }
    
    console.log('NeuroBlock admin loaded successfully');
});