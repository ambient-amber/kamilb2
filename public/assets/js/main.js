$(document).ready(function(){
    var _window = $(window);

    _window.on('scroll', function(){
        var scroll_top = _window.scrollTop();
        var header = $('.page_header');

        if (scroll_top > 0) {
            header.addClass('fixed');
        } else {
            header.removeClass('fixed');
        }
    });

    $('.article_list_spoiler').click(function(){
         $('.article_list').toggleClass('visible');

         return false;
    });

    // ------------------------------------------

    var $track_input = $('.track_input');
    var track_number;

    $('.track_submit').click(function(){
        track_number = $track_input.val();

        if (track_number) {
            YQV5.trackSingle({
                //Обязательно, укажите id контейнера.
                YQ_ContainerId: "track_result",
                //Не обязательно, укажите высоту результата отслеживания, максимальная высота 800px, Значение по умолчанию - 560 пикселей.
                YQ_Height: 800,
                //Не обязательно, выберите перевозчика, по умолчанию - автоопределение.
                YQ_Fc: "0",
                //Не обязательно, укажите язык пользовательского интерфейса, по умолчанию язык будет определен по настройкам браузера.
                YQ_Lang: "ru",
                //Обязательно, укажите номер, который необходимо отслеживать.
                YQ_Num: track_number
            });
        }
    });

    // ------------------------------------------

    $('.track_number').click(function(){
        var trackNumber = $(this).text();

        $('.track_input').val(trackNumber);
        $('.track_submit').click();

        $('.your_track_instruction .your_tracks').hide();
        $('.your_track_instruction .instruction').hide();
        $('.your_track_instruction .your_other_tracks').show();
    });

    // ------------------------------------------

    $('.page_map_link').click(function(e){
        e.preventDefault();

        var self = $(this);
        var contentBlock = $(self.attr('href'));
        var contentBlockHeight = contentBlock.outerHeight();

        $.scrollTo(contentBlock, {duration: 1000, offset: -contentBlockHeight});

        return false;
    });

    // ------------------------------------------

    $('.js_tabs_title').click(function(){
        var $tabs = $(this).closest('.js_tabs');
        var selectedTabId = $(this).data('tab_id');

        $tabs.find('.js_tabs_title').removeClass('selected');
        $(this).addClass('selected');

        $tabs
            .find('.js_tabs_content').removeClass('selected')
            .filter('[data-tab_id="' + selectedTabId + '"]').addClass('selected');

        window.location.hash = selectedTabId;
    });

    if (location.hash) {
        var cleanHash = location.hash.replace('#','');
        var $hashTabTitle = $('.js_tabs_title[data-tab_id="' + cleanHash + '"]');
        var $hashTabContent = $('.js_tabs_content[data-tab_id="' + cleanHash + '"]');

        if ($hashTabTitle.length && $hashTabContent.length) {
            $hashTabTitle.siblings().removeClass('selected').end().addClass('selected');
            $hashTabContent.siblings().removeClass('selected').end().addClass('selected');
        }
    }

    // ------------------------------------------
});