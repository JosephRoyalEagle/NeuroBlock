(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { RichText, InspectorControls } = wp.blockEditor;
    const { PanelBody, TextareaControl, Button } = wp.components;
    const { createElement: el, Fragment } = wp.element;
    const { __ } = wp.i18n;

    registerBlockType('neuroblock/ai-content', {
        title: __('NeuroBlock AI Content', 'neuroblock'),
        description: __('Bloc généré par intelligence artificielle', 'neuroblock'),
        icon: {
            src: el('svg', 
                { 
                    width: 24, 
                    height: 24, 
                    viewBox: '0 0 24 24',
                    xmlns: 'http://www.w3.org/2000/svg'
                },
                el('path', {
                    d: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h2v2H7v-2zm8 0h-2v2h2v-2zm0 4h-2v2h2v-2zm-4 0h-2v2h2v-2zm4-8h-2v2h2V7zM7 7h2v2H7V7z',
                    fill: 'currentColor'
                })
            ),
            foreground: '#8b5cf6'
        },
        category: 'common',
        keywords: ['ai', 'neuroblock', 'intelligence', 'artificielle'],
        attributes: {
            content: {
                type: 'string',
                default: ''
            },
            generatedBy: {
                type: 'string',
                default: 'neuroblock'
            }
        },
        
        edit: function(props) {
            const { attributes, setAttributes, className } = props;
            const { content } = attributes;

            function onChangeContent(newContent) {
                setAttributes({ content: newContent });
            }

            function generateWithAI() {
                // Open NeuroBlock admin page in new tab
                window.open(
                    ajaxurl.replace('admin-ajax.php', 'admin.php?page=neuroblock'),
                    '_blank'
                );
            }

            return el(
                Fragment,
                {},
                el(
                    InspectorControls,
                    {},
                    el(
                        PanelBody,
                        { title: __('NeuroBlock Settings', 'neuroblock') },
                        el(
                            'div',
                            { className: 'neuroblock-sidebar' },
                            el('p', {}, __('Générez du contenu IA depuis le panneau NeuroBlock', 'neuroblock')),
                            el(
                                Button,
                                {
                                    isPrimary: true,
                                    onClick: generateWithAI,
                                    className: 'neuroblock-generate-btn'
                                },
                                __('Ouvrir NeuroBlock', 'neuroblock')
                            )
                        )
                    )
                ),
                el(
                    'div',
                    { className: className + ' neuroblock-block-editor' },
                    content ? 
                        el(RichText, {
                            tagName: 'div',
                            value: content,
                            onChange: onChangeContent,
                            placeholder: __('Contenu généré par IA...', 'neuroblock'),
                            className: 'neuroblock-content-editable'
                        })
                    :
                        el(
                            'div',
                            { className: 'neuroblock-placeholder' },
                            el(
                                'div',
                                { className: 'neuroblock-placeholder-icon' },
                                el('svg', 
                                    { 
                                        width: 48, 
                                        height: 48, 
                                        viewBox: '0 0 24 24',
                                        fill: 'none',
                                        stroke: 'currentColor',
                                        strokeWidth: 2
                                    },
                                    el('path', {
                                        d: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h2v2H7v-2zm8 0h-2v2h2v-2zm0 4h-2v2h2v-2zm-4 0h-2v2h2v-2zm4-8h-2v2h2V7zM7 7h2v2H7V7z'
                                    })
                                )
                            ),
                            el('h3', {}, __('Bloc NeuroBlock AI', 'neuroblock')),
                            el('p', {}, __('Générez du contenu avec l\'intelligence artificielle', 'neuroblock')),
                            el(
                                Button,
                                {
                                    isPrimary: true,
                                    onClick: generateWithAI
                                },
                                __('Générer avec IA', 'neuroblock')
                            )
                        )
                )
            );
        },

        save: function(props) {
            const { attributes } = props;
            const { content } = attributes;

            return el(
                'div',
                { className: 'neuroblock-ai-content' },
                el(RichText.Content, {
                    tagName: 'div',
                    value: content
                })
            );
        }
    });

})(window.wp);