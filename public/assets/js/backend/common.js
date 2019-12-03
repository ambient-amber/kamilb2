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