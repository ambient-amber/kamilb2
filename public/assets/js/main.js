$(document).ready(function(){
    // ------------------------------------------

    $(window).on('scroll', function(){
        var $pageWrapper = $('.page_wrapper');
        var $header = $('.page_header');
        var $footer = $('.footer');
        var $verticalPromo = $('.content_promo_vertical');

        var scroll_top = $(window).scrollTop();
        var headerOuterHeight = $header.outerHeight();
        var footerOffsetTop = $footer.offset().top;
        var footerOuterHeight = $footer.outerHeight();
        var windowHeight = $(window).height();
        var verticalPromoMargin = 20;

        if (scroll_top >= headerOuterHeight) {
            $header.addClass('fixed');
            $pageWrapper.css('padding-top', headerOuterHeight);
        } else {
            $header.removeClass('fixed');
            $pageWrapper.css('padding-top', '');
        }

        if ($verticalPromo.length) {
            var $verticalPromoOffsetTop = $verticalPromo.offset().top;
            var $verticalPromoInner = $('.content_promo_vertical_inner');
            var verticalPromoInnerOuterHeight = $verticalPromoInner.outerHeight();

            // Скроллить баннеры имеет смысл, если они помещаются в окно
            if ((windowHeight - headerOuterHeight) > verticalPromoInnerOuterHeight) {
                // Скролл с учетом плавающего хэдера
                if (scroll_top > $verticalPromoOffsetTop) {
                    $verticalPromoInner.addClass('fixed');

                    if ((scroll_top + verticalPromoInnerOuterHeight + verticalPromoMargin) < footerOffsetTop) {
                        // Проверка, чтобы баннеры не налезали на футер
                        $verticalPromoInner.css({
                            top: headerOuterHeight + verticalPromoMargin,
                            bottom: ''
                        });
                    } else {
                        // Меняем позиционирование с верхнего на нижнее
                        $verticalPromoInner.css({
                            top: '',
                            bottom: footerOuterHeight + verticalPromoMargin
                        });
                    }
                } else {
                    $verticalPromoInner
                        .removeClass('fixed')
                        .css({
                            top: '',
                            bottom: ''
                        });
                }
            }
        }
    });

    // ------------------------------------------

    $('.article_list_spoiler').click(function(){
         $('.article_list').toggleClass('visible');

         return false;
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

    // Добавление hash таба каждой ссылке пагинации
    $.each($('.js_tabs_content'), function(){
        let tabId = $(this).data('tab_id');

        $.each($(this).find('a.pagination_item_link'), function(){
            $(this).attr('href', $(this).attr('href') + '#' + tabId);
        });
    });

    // Переключение табов
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

    // Активация выбранного таба при загрузке страницы
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