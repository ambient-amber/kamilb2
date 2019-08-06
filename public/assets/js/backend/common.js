$(document).ready(function(){
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
});