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

        $menuItems = [
            [
                'key' => 'user',
                'title' => 'Пользователи',
                'route' => 'user_index',
                'icon' => 'la la-users',
                'children' => [
                    ['title' => 'Список', 'route' => 'user_index'],
                    ['title' => 'Добавить', 'route' => 'user_new']
                ]
            ],
            [
                'key' => 'popular_shop',
                'title' => 'Популярные магазины',
                'route' => 'popular_shop_index',
                'icon' => 'la la-external-link',
                'children' => [
                    ['title' => 'Список', 'route' => 'popular_shop_index'],
                    ['title' => 'Добавить', 'route' => 'popular_shop_new']
                ]
            ],
            [
                'key' => 'language',
                'title' => 'Языки',
                'route' => 'language_index',
                'icon' => 'la la-language',
            ],
            [
                'key' => 'article',
                'title' => 'Статьи',
                'route' => 'article_index',
                'icon' => 'la la-newspaper-o',
                'children' => [
                    ['title' => 'Список', 'route' => 'article_index'],
                    ['title' => 'Добавить', 'route' => 'article_new']
                ]
            ],
            [
                'key' => 'content_block',
                'title' => 'Контентные блоки',
                'route' => 'content_block_index',
                'icon' => 'la la-puzzle-piece',
                'children' => [
                    ['title' => 'Список', 'route' => 'content_block_index'],
                    ['title' => 'Добавить', 'route' => 'content_block_new']
                ]
            ],
            [
                'key' => 'page',
                'title' => 'Статические страницы',
                'route' => 'page_index',
                'icon' => 'la la-file',
                'children' => [
                    ['title' => 'Список', 'route' => 'page_index'],
                    ['title' => 'Добавить', 'route' => 'page_new']
                ]
            ],
            [
                'key' => 'meta_data',
                'title' => 'Мета теги',
                'route' => 'meta_data_index',
                'icon' => 'la la-tags',
                'children' => [
                    ['title' => 'Список', 'route' => 'meta_data_index'],
                    ['title' => 'Добавить', 'route' => 'meta_data_new']
                ]
            ],
            [
                'key' => 'parser',
                'title' => 'Парсер',
                'route' => 'parser_index',
                'icon' => 'la la-magnet',
                'children' => [
                    ['title' => 'Парсер страницы', 'route' => 'parse_page'],
                ]
            ],
        ];

        foreach ($menuItems as $menuItem) {
            $menu->addChild($menuItem['key'], ['route' => $menuItem['route']])
                ->setAttribute('class', 'nav-item dropdown')
                ->setLinkAttributes(['class' => 'nav-link'])
            ;

            $addedMenuItem = $menu->getChild($menuItem['key']);

            if (!empty($menuItem['icon'])) {
                $addedMenuItem->setAttribute('icon_class', $menuItem['icon']);
            }

            if (!empty($menuItem['children'])) {
                $addedMenuItem
                    ->setLinkAttributes(['class' => 'nav-link dropdown-toggle'])
                    ->setAttribute('dropdown', true);

                foreach ($menuItem['children'] as $child) {
                    $addedMenuItem->addChild($child['title'], ['route' => $child['route']]);
                }
            }
        }

        return $menu;
    }
}
