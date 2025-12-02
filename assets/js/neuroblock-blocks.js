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
        mistral: ['mistral-small', 'mistral-medium', 'mistral-large']
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
            '<span class="neuroblock-spinner"></span> ' + neuroblock.strings.saving
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
                Swal.fire({
                    icon: 'success',
                    title: neuroblock.strings.success,
                    text: neuroblock.strings.saved,
                    timer: 2000,
                    showConfirmButton: false
                });
                // Clear API key field for security
                $form.find('input[name="api_key"]').val('');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: neuroblock.strings.error,
                    text: response.data.message || neuroblock.strings.error
                });
            }
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: neuroblock.strings.error,
                text: neuroblock.strings.error
            });
        }).always(function() {
            $button.prop('disabled', false).html(buttonText);
        });
    });
    
    // Generator form submission
    $('#neuroblock-generator-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const $output = $('#generated-output');
        const $code = $('#generated-code');
        
        const prompt = $form.find('textarea[name="prompt"]').val().trim();
        const type = $form.find('select[name="type"]').val();
        
        if (!prompt) {
            Swal.fire({
                icon: 'warning',
                title: neuroblock.strings.warning,
                text: neuroblock.strings.promptRequired
            });
            return;
        }
        
        // Check if Gutenberg or Elementor is required and available
        if ((type === 'page-gutenberg' || type === 'block') && !neuroblock.hasGutenberg) {
            Swal.fire({
                icon: 'error',
                title: neuroblock.strings.error,
                text: neuroblock.strings.gutenbergRequired
            });
            return;
        }
        
        if ((type === 'page-elementor' || type === 'elementor') && !neuroblock.hasElementor) {
            Swal.fire({
                icon: 'error',
                title: neuroblock.strings.error,
                text: neuroblock.strings.elementorRequired
            });
            return;
        }
        
        // Show loading with SweetAlert
        Swal.fire({
            title: neuroblock.strings.generating,
            html: neuroblock.strings.pleaseWait,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $output.hide();
        
        const formData = {
            action: 'neuroblock_generate',
            nonce: neuroblock.nonce,
            prompt: prompt,
            type: type,
            style: $form.find('select[name="style"]').val()
        };
        
        $.post(neuroblock.ajax_url, formData, function(response) {
            Swal.close();
            
            if (response.success) {
                $code.val(response.data.content);
                $output.slideDown();
                
                // Show success with copy button info for blocks
                if (type === 'block' || type === 'elementor') {
                    Swal.fire({
                        icon: 'success',
                        title: neuroblock.strings.success,
                        text: neuroblock.strings.blockGenerated,
                        confirmButtonText: neuroblock.strings.ok
                    });
                } else {
                    // For complete pages, show page created message
                    Swal.fire({
                        icon: 'success',
                        title: neuroblock.strings.success,
                        html: response.data.pageUrl 
                            ? neuroblock.strings.pageCreated + '<br><a href="' + response.data.pageUrl + '" target="_blank">' + neuroblock.strings.viewPage + '</a>'
                            : neuroblock.strings.contentGenerated,
                        confirmButtonText: neuroblock.strings.ok
                    });
                }
                
                // Scroll to output
                $('html, body').animate({
                    scrollTop: $output.offset().top - 100
                }, 500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: neuroblock.strings.error,
                    text: response.data.message || neuroblock.strings.error
                });
            }
        }).fail(function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: neuroblock.strings.error,
                text: neuroblock.strings.error
            });
        });
    });
    
    // Copy code to clipboard with instructions
    $(document).on('click', '.neuroblock-copy-code', function() {
        const $code = $('#generated-code');
        const type = $('#neuroblock-generator-form select[name="type"]').val();
        
        $code.select();
        document.execCommand('copy');
        
        let instructions = '';
        
        if (type === 'block') {
            instructions = neuroblock.strings.gutenbergInstructions;
        } else if (type === 'elementor') {
            instructions = neuroblock.strings.elementorInstructions;
        }
        
        Swal.fire({
            icon: 'success',
            title: neuroblock.strings.copied,
            html: instructions,
            confirmButtonText: neuroblock.strings.ok
        });
    });
    
    // Copy crypto address
    $(document).on('click', '.neuroblock-crypto-address', function() {
        const address = $(this).text();
        
        // Create temporary input
        const $temp = $('<input>');
        $('body').append($temp);
        $temp.val(address).select();
        document.execCommand('copy');
        $temp.remove();
        
        // Show SweetAlert with timer
        Swal.fire({
            icon: 'success',
            title: neuroblock.strings.copied,
            text: neuroblock.strings.addressCopied,
            timer: 2000,
            showConfirmButton: false
        });
    });
});