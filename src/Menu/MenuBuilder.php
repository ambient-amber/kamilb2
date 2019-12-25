<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainBackendMenu(array $options)
    {
        $menu = $this->factory->createItem(
            'root',
            [
                'childrenAttributes' => [
                    'class' => 'nav nav-pills nav-stacked main_menu'
                ]
            ]
        );

        // Пользователи
        $menu->addChild('user', ['route' => 'user_index'])
             ->setAttribute('dropdown', true)
             ->setAttribute('icon_class', 'la la-users')
             ->setAttribute('class', 'nav-item dropdown')
             ->setLinkAttributes(['class' => 'nav-link dropdown-toggle']);

        $menu['user']->addChild('List', ['route' => 'user_index']);
        $menu['user']->addChild('Add', ['route' => 'user_new']);

        // Популярные магазины
        $menu->addChild('popular_shop', ['route' => 'popular_shop_index'])
            ->setAttribute('dropdown', true)
            ->setAttribute('icon_class', 'la la-external-link')
            ->setAttribute('class', 'nav-item dropdown')
            ->setLinkAttributes(['class' => 'nav-link dropdown-toggle']);

        $menu['popular_shop']->addChild('List', ['route' => 'popular_shop_index']);
        $menu['popular_shop']->addChild('Add', ['route' => 'popular_shop_new']);

        // Языки
        $menu->addChild('language', ['route' => 'language_index'])
            ->setAttribute('class', 'nav-item')
            ->setAttribute('icon_class', 'la la-language')
            ->setLinkAttributes(['class' => 'nav-link']);

        // Статьи
        $menu->addChild('article', ['route' => 'article_index'])
            ->setAttribute('dropdown', true)
            ->setAttribute('icon_class', 'la la-newspaper-o')
            ->setAttribute('class', 'nav-item dropdown')
            ->setLinkAttributes(['class' => 'nav-link dropdown-toggle']);

        $menu['article']->addChild('List', ['route' => 'article_index']);
        $menu['article']->addChild('Add', ['route' => 'article_new']);

        // Контентные блоки
        $menu->addChild('content_block', ['route' => 'content_block_index'])
            ->setAttribute('dropdown', true)
            ->setAttribute('icon_class', 'la la-puzzle-piece')
            ->setAttribute('class', 'nav-item dropdown')
            ->setLinkAttributes(['class' => 'nav-link dropdown-toggle']);

        $menu['content_block']->addChild('List', ['route' => 'content_block_index']);
        $menu['content_block']->addChild('Add', ['route' => 'content_block_new']);

        return $menu;
    }
}
