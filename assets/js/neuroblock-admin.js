jQuery(document).ready(function($) {
    'use strict';

    // Initialize on page load
    checkProviderApiKeys();
    
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
        openai: [
            'gpt-4o-mini'
        ],
    
        deepseek: [
            'deepseek-chat',
            'deepseek-coder'
        ],
    
        gemini: [
            'gemini-2.5-flash',
            'gemini-2.5-flash-lite'
        ],
    
        mistral: [
            'mistral-small',
        ]
    };

    // Provider API key status (will be populated on page load)
    const providerApiKeys = {};

    // Check API key status for each provider
    function checkProviderApiKeys() {
        const providers = ['openai', 'deepseek', 'gemini', 'mistral'];
        providers.forEach(function(provider) {
            $.post(neuroblock.ajax_url, {
                action: 'neuroblock_check_api_key',
                nonce: neuroblock.nonce,
                provider: provider
            }, function(response) {
                if (response.success) {
                    providerApiKeys[provider] = response.data.has_key;
                }
            });
        });
    }

    // Update models when provider changes in settings
    $('#nb-provider').on('change', function() {
        const provider = $(this).val();
        updateApiKeyField(provider);
    });

    // Update API key field to show masked key if exists
    function updateApiKeyField(provider) {
        $.post(neuroblock.ajax_url, {
            action: 'neuroblock_get_masked_key',
            nonce: neuroblock.nonce,
            provider: provider
        }, function(response) {
            if (response.success && response.data.masked_key) {
                $('#nb-api-key').val(response.data.masked_key);
                $('#nb-api-key').next('.neuroblock-helper-text').text(neuroblock.strings.apiKeyConfigured);
            } else {
                $('#nb-api-key').val('');
                $('#nb-api-key').next('.neuroblock-helper-text').text(neuroblock.strings.apiKeyEncrypted);
            }
        });
    }

    // Update models when provider changes in generator
    $('#nb-gen-provider').on('change', function() {
        const provider = $(this).val();
        const models = providerModels[provider] || [];
        const $modelSelect = $('#nb-gen-model');
        
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
        
        const apiKey = $form.find('input[name="api_key"]').val();
        
        // Don't send API key if it's the masked value
        const formData = {
            action: 'neuroblock_save_settings',
            nonce: neuroblock.nonce,
            provider: $form.find('select[name="provider"]').val(),
            api_key: apiKey === '••••••••••••••••••••' ? '' : apiKey
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
                // Update to show masked key
                if (apiKey && apiKey !== '••••••••••••••••••••') {
                    $form.find('input[name="api_key"]').val('••••••••••••••••••••');
                    $form.find('input[name="api_key"]').next('.neuroblock-helper-text').text(neuroblock.strings.apiKeyConfigured);
                }
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
        const provider = $form.find('select[name="provider"]').val();
        const model = $form.find('select[name="model"]').val();
        
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
            style: $form.find('select[name="style"]').val(),
            provider: provider,
            model: model
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

    // ============================================
    // BLOCKS HISTORY MANAGEMENT (localStorage)
    // ============================================
    
    /**
     * Get blocks history from localStorage
     */
    function getBlocksHistory() {
        try {
            const history = localStorage.getItem('neuroblock_blocks_history');
            return history ? JSON.parse(history) : [];
        } catch (e) {
            console.error('Error reading blocks history:', e);
            return [];
        }
    }
    
    /**
     * Save block to history
     */
    function saveBlockToHistory(blockData) {
        try {
            let history = getBlocksHistory();
            
            // Add new block at the beginning
            history.unshift({
                id: Date.now(),
                type: blockData.type,
                prompt: blockData.prompt.substring(0, 100) + (blockData.prompt.length > 100 ? '...' : ''),
                style: blockData.style,
                content: blockData.content,
                timestamp: Date.now()
            });
            
            // Keep only last 20 blocks
            history = history.slice(0, 20);
            
            localStorage.setItem('neuroblock_blocks_history', JSON.stringify(history));
            renderBlocksHistory();
        } catch (e) {
            console.error('Error saving block to history:', e);
        }
    }
    
    /**
     * Clear blocks history
     */
    function clearBlocksHistory() {
        Swal.fire({
            icon: 'warning',
            title: neuroblock.strings.warning,
            text: neuroblock.strings.clearHistoryConfirm || 'Are you sure you want to clear all blocks history?',
            showCancelButton: true,
            confirmButtonText: neuroblock.strings.yes || 'Yes',
            cancelButtonText: neuroblock.strings.cancel || 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('neuroblock_blocks_history');
                renderBlocksHistory();
                Swal.fire({
                    icon: 'success',
                    title: neuroblock.strings.success,
                    text: neuroblock.strings.historyCleared || 'History cleared successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }
    
    /**
     * Delete single block from history
     */
    function deleteBlockFromHistory(blockId) {
        try {
            let history = getBlocksHistory();
            history = history.filter(block => block.id !== blockId);
            localStorage.setItem('neuroblock_blocks_history', JSON.stringify(history));
            renderBlocksHistory();
            
            Swal.fire({
                icon: 'success',
                title: neuroblock.strings.deleted || 'Deleted',
                timer: 1500,
                showConfirmButton: false
            });
        } catch (e) {
            console.error('Error deleting block:', e);
        }
    }
    
    /**
     * View block content
     */
    function viewBlockContent(blockId) {
        const history = getBlocksHistory();
        const block = history.find(b => b.id === blockId);
        
        if (block) {
            Swal.fire({
                title: getBlockTypeLabel(block.type),
                html: `
                    <div style="text-align: left;">
                        <p style="margin-bottom: 10px; color: #64748b;">
                            <strong>${neuroblock.strings.prompt || 'Prompt'}:</strong><br>
                            ${block.prompt}
                        </p>
                        <p style="margin-bottom: 10px; color: #64748b;">
                            <strong>${neuroblock.strings.style || 'Style'}:</strong> ${block.style}
                        </p>
                        <textarea readonly style="width: 100%; height: 300px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: monospace; font-size: 0.85rem;">${block.content}</textarea>
                    </div>
                `,
                width: '800px',
                showCancelButton: true,
                confirmButtonText: neuroblock.strings.copyCode || 'Copy Code',
                cancelButtonText: neuroblock.strings.close || 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    copyToClipboard(block.content, block.type);
                }
            });
        }
    }
    
    /**
     * Copy content to clipboard
     */
    function copyToClipboard(content, type) {
        const $temp = $('<textarea>');
        $('body').append($temp);
        $temp.val(content).select();
        document.execCommand('copy');
        $temp.remove();
        
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
    }
    
    /**
     * Get block type label
     */
    function getBlockTypeLabel(type) {
        const labels = {
            'block': neuroblock.strings.gutenbergBlock || 'Gutenberg Block',
            'elementor': neuroblock.strings.elementorWidget || 'Elementor Widget'
        };
        return labels[type] || type;
    }
    
    /**
     * Get block type icon
     */
    function getBlockTypeIcon(type) {
        const icons = {
            'block': `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="16 18 22 12 16 6"></polyline>
                <polyline points="8 6 2 12 8 18"></polyline>
            </svg>`,
            'elementor': `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>`
        };
        return icons[type] || icons['block'];
    }
    
    /**
     * Format timestamp
     */
    function formatTimestamp(timestamp) {
        const now = Date.now();
        const diff = now - timestamp;
        
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (minutes < 1) return neuroblock.strings.justNow || 'Just now';
        if (minutes < 60) return minutes + ' ' + (neuroblock.strings.minutesAgo || 'minutes ago');
        if (hours < 24) return hours + ' ' + (neuroblock.strings.hoursAgo || 'hours ago');
        return days + ' ' + (neuroblock.strings.daysAgo || 'days ago');
    }
    
    /**
     * Render blocks history
     */
    function renderBlocksHistory() {
        const $container = $('#recent-blocks-container');
        const history = getBlocksHistory();
        
        if (history.length === 0) {
            $container.html(`
                <div class="neuroblock-alert neuroblock-alert-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <div>
                        <p style="margin: 0;">${neuroblock.strings.noBlocksYet || 'No blocks generated yet. Go to Generator tab to create your first block!'}</p>
                    </div>
                </div>
            `);
            return;
        }
        
        let html = '';
        history.forEach(block => {
            html += `
                <div class="neuroblock-recent-item">
                    <div class="neuroblock-recent-info">
                        ${getBlockTypeIcon(block.type)}
                        <div>
                            <span class="neuroblock-recent-name">${block.prompt}</span>
                            <span style="display: block; font-size: 0.8rem; color: #94a3b8; margin-top: 2px;">
                                ${getBlockTypeLabel(block.type)} • ${block.style}
                            </span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="neuroblock-recent-time">${formatTimestamp(block.timestamp)}</span>
                        <button type="button" class="neuroblock-btn neuroblock-btn-secondary view-block-btn" data-block-id="${block.id}" style="padding: 6px 12px; font-size: 0.8rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            ${neuroblock.strings.view || 'View'}
                        </button>
                        <button type="button" class="neuroblock-btn neuroblock-btn-secondary delete-block-btn" data-block-id="${block.id}" style="padding: 6px 12px; font-size: 0.8rem; background: #fee2e2; color: #dc2626; border-color: #fecaca;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            ${neuroblock.strings.delete || 'Delete'}
                        </button>
                    </div>
                </div>
            `;
        });
        
        $container.html(html);
    }
    
    // ============================================
    // EVENT HANDLERS
    // ============================================
    
    // Clear blocks history button
    $(document).on('click', '#clear-blocks-history', function() {
        clearBlocksHistory();
    });
    
    // View block button
    $(document).on('click', '.view-block-btn', function() {
        const blockId = parseInt($(this).data('block-id'));
        viewBlockContent(blockId);
    });
    
    // Delete block button
    $(document).on('click', '.delete-block-btn', function() {
        const blockId = parseInt($(this).data('block-id'));
        deleteBlockFromHistory(blockId);
    });
    
    // Modify the generator form submission to save blocks to history
    const originalGeneratorSubmit = $('#neuroblock-generator-form').data('events')?.submit;
    
    $('#neuroblock-generator-form').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const $output = $('#generated-output');
        const $code = $('#generated-code');
        
        const prompt = $form.find('textarea[name="prompt"]').val().trim();
        const type = $form.find('select[name="type"]').val();
        const style = $form.find('select[name="style"]').val();
        
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
        
        // Show loading
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
            style: style
        };
        
        $.post(neuroblock.ajax_url, formData, function(response) {
            Swal.close();
            
            if (response.success) {
                $code.val(response.data.content);
                $output.slideDown();
                
                // Save block/widget to history (NOT complete pages)
                if (type === 'block' || type === 'elementor') {
                    saveBlockToHistory({
                        type: type,
                        prompt: prompt,
                        style: style,
                        content: response.data.content
                    });
                }
                
                // Show success message
                if (type === 'block' || type === 'elementor') {
                    Swal.fire({
                        icon: 'success',
                        title: neuroblock.strings.success,
                        text: neuroblock.strings.blockGenerated,
                        confirmButtonText: neuroblock.strings.ok
                    });
                } else {
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
    
    // Initialize blocks history on page load
    if ($('#tab-blocks').length) {
        renderBlocksHistory();
    }
    
    // Refresh blocks history when switching to Blocks tab
    $('.neuroblock-tab[data-tab="blocks"]').on('click', function() {
        setTimeout(renderBlocksHistory, 100);
    });
});