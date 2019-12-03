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
    tinymce.init({
        selector: '.js_tinymce_textarea',
        plugins: 'print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        height: 400,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: "mceNonEditable",
        toolbar_drawer: 'sliding',
        contextmenu: "link image imagetools table",
        images_upload_url: 'postAcceptor.php',
        images_upload_base_path: '/public/uploads/',
    });
    /* -------------- */

    /* --------- Статьи --------- */
    $('.js_add_translation').click(function(){
        var $collectionHolder = $('#article_articleTranslations');
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.find('fieldset').length;
        var newForm = prototype.replace(/__name__/g, index);

        $collectionHolder.append(newForm);
    });

    $(document).on('click', '.js_delete_translation', function(){
        var $self = $(this);

        $self.closest('fieldset').remove();
    });
    /* -------------------------- */
});