$(document).ready(function(){
    // ------------------------------------------

    // Определения функций

    // Высота блоков баннеров в каждом отдельном списке (мобильная, планшетная версии)
    function calculateContentItemsPromoHeight() {
        $.each($('.content_items'), function(){
            // Условия вывода шаблонов в зависимости от устройства в шаблонах.
            // Элементов content_promo_list_item в десктопной версии нет.
            let $contentItemsBlocks = $(this);
            let $contentPromoListItems = $contentItemsBlocks.find('.content_promo_list_item');
            let contentItemsHeights = [];
            let contentItemsAverageHeight = 0;

            if ($contentPromoListItems.length) {
                $.each($contentItemsBlocks.find('.content_item_inner'), function(){
                    contentItemsHeights.push($(this).height());
                });

                let contentItemsHeightsSum = contentItemsHeights.reduce(function(a, b){
                    return a + b;
                }, 0);

                contentItemsAverageHeight = Math.round(contentItemsHeightsSum / contentItemsHeights.length);

                $.each($contentPromoListItems, function(){
                    $(this).css({
                        height: contentItemsAverageHeight,
                        overflow: 'hidden'
                    });
                });
            }
        });
    }

    // ------------------------------------------

    // События при скроле
    $(window).on('scroll', function(){
        var $pageWrapper = $('.page_wrapper');
        var $header = $('.page_header');
        var $verticalPromo = $('.content_promo_vertical');

        var scroll_top = $(window).scrollTop();
        var headerOuterHeight = $header.outerHeight();
        var windowHeight = $(window).height();

        // Плавающий header
        if (scroll_top >= headerOuterHeight) {
            $header.addClass('fixed');
            $pageWrapper.css('padding-top', headerOuterHeight);
        } else {
            $header.removeClass('fixed');
            $pageWrapper.css('padding-top', '');
        }

        // Плавающие баннеры
        if ($verticalPromo.length) {
            var verticalPromoMargin = 20;
            var $verticalPromoOffsetTop = $verticalPromo.offset().top;
            var $verticalPromoInner = $('.content_promo_vertical_inner');
            var verticalPromoInnerOuterHeight = $verticalPromoInner.outerHeight();

            // Скроллить баннеры имеет смысл, если они помещаются в окно
            if ((windowHeight - headerOuterHeight) > verticalPromoInnerOuterHeight) {
                // Скролл с учетом плавающего хэдера
                if ((scroll_top + headerOuterHeight) > $verticalPromoOffsetTop) {
                    $verticalPromoInner
                        .addClass('sticky')
                        .css('top', headerOuterHeight + verticalPromoMargin);
                } else {
                    $verticalPromoInner
                        .removeClass('sticky')
                        .css('top', '');
                }
            }
        }
    });

    // ------------------------------------------

    // События при изменении размеров окна
    $(window).resize(function(){
        calculateContentItemsPromoHeight();
    });

    // ------------------------------------------

    calculateContentItemsPromoHeight();

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