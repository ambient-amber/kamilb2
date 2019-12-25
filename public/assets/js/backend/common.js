$(document).ready(function(){
    /* --------- Главное меню --------- */
    var $mainMenu = $('.main_menu');

    $.each($mainMenu.find('.nav-item.dropdown'), function(){
        if ($(this).hasClass('current_ancestor') || $(this).hasClass('current')) {
            $(this).addClass('open');
        }
    });

    $('.dropdown-toggle').click(function(e){
        e.preventDefault();
        return false;
    });
    /* -------------------------------- */

    /* --- Включение/выключение публикации --- */
    $('.pub_toggle_link').click(function(){
        var $link = $(this);

        $.ajax({
            url: $link.attr('href'),
            type: 'post',
            dataType: 'json',
            data: {},
            success: function (result) {
                if (result.success) {
                    $link.toggleClass('active');
                }
            }
        });

        return false;
    });
    /* -------------------------------------- */

    /* --- TinyMCE --- */
    var tinymce_settings = {
        language: 'ru',
        theme: 'modern',
        relative_urls: false,
        convert_urls: false,
        valid_elements: '*[*]',
        valid_children : '+body[style]',
        paste_as_text: true,
        plugins: [
            'advlist autolink link image lists charmap print preview hr anchor pagebreak',
            'searchreplace visualblocks visualchars codemirror fullscreen insertdatetime media nonbreaking',
            'save table contextmenu directionality template paste textcolor responsivefilemanager'
        ],
        image_advtab: true,
        plugin_preview_width: 800,
        content_css : '/assets/css/backend/tiny_mce_front_styles.css',
        advlist_bullet_styles: 'default',
        advlist_number_styles: 'default',
        style_formats: [
            { title: 'Абзац', block: 'p' },
            { title: 'H2 title', block: 'h2' },
            { title: 'H3 title', block: 'h3' },
            { title: 'H4 title', block: 'h4' },
            { title: 'Важное примечание', block: 'div', classes: 'highlighted_block'}
        ],
        templates : [
            {
                title: "Фото с подписью",
                description: "Фото с подписью",
                url: "/assets/cosmo/libs/tinymce/templates/photo_with_description.htm",
            }
        ],
        external_filemanager_path: '/assets/cosmo/libs/tinymce/plugins/filemanager/',
        filemanager_title: 'Responsive Filemanager',
        external_plugins: { filemanager: '/assets/cosmo/libs/tinymce/plugins/filemanager/plugin.min.js' },
        codemirror: {
            indentOnInit: true,
            fullscreen: false,
            path: 'codemirror',
            config: {
                mode: 'application/x-httpd-php',
                lineNumbers: false
            },
            width: 940,
            height: 480,
            jsFiles: [
                'mode/clike/clike.js',
                'mode/php/php.js'
            ]
        },
    };

    function enable_editor(selector) {
        var tinymce_settings_full = $.extend(true, {}, tinymce_settings);

        tinymce_settings_full.selector = selector;
        tinymce_settings_full.height = 400;
        tinymce_settings_full.menu = {
            edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
            insert: {title: 'Insert', items: 'link image | nonbreaking hr charmap | hidden_text image_comparison image_slider'},
            view: {title: 'View', items: 'visualblocks visualaid'},
            format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | content_layout_left | removeformat'},
            table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
            tools: {title: 'Tools', items: 'typograf code'}
        };

        tinymce_settings_full.toolbar = 'undo redo | styleselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image responsivefilemanager media | fullscreen preview code template';

        tinymce.init(tinymce_settings_full);
    }

    enable_editor('.js_tinymce_textarea');

    // Заполнение настоящей textarea перед отправкой формы добавления/редактирования для избежания ошибки валидации
    $('.js_save_with_tiny_mce').click(function(e){
        e.preventDefault();

        tinymce.triggerSave();

        $(this).closest('form').submit();

        return false;
    });
    /* -------------- */

    /* --------- Статьи --------- */
    $('.js_add_translation').click(function(){
        var $collectionHolder = $('#article_articleTranslations');
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.find('fieldset').length;
        var newForm = prototype.replace(/__name__/g, index);

        $collectionHolder.append(newForm);

        enable_editor('#article_articleTranslations_' + index + '_content');
    });

    $(document).on('click', '.js_delete_translation', function(){
        var $self = $(this);
        var $parent = $self.closest('fieldset');

        tinymce.remove('#' + $parent.find('.js_tinymce_textarea').attr('id'));

        $parent.remove();

        return false;
    });
    /* -------------------------- */

    /* --- Блок со скроллом --- */
    $.each($('.js_scrollable_block'), function(){
        var $self = $(this);
        var height = $self.attr('height') ? $self.attr('height') : '150px';
        var width = $self.attr('width') ? $self.attr('width') : false;
        var styles = {
            'overflow-y': 'scroll',
            'height': height,
            'border': '1px solid #dee0e1'
        };

        if (width) {
            styles['overflow-x'] = 'scroll';
            styles['width'] = width;
        }

        $self.css(styles);
    });
    /* ----------------------- */
});