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

        $menu->addChild('User', ['route' => 'user_index'])
             ->setAttribute('dropdown', true)
             ->setAttribute('class', 'nav-item dropdown')
             ->setLinkAttributes(['class' => 'nav-link dropdown-toggle']);

        /*if($this->container->get('security.context')->isGranted(['ROLE_ADMIN'])) {

        }*/

        $menu['User']->addChild('List', ['route' => 'user_index']);
        $menu['User']->addChild('Add', ['route' => 'user_new']);

        $menu->addChild('Popular shop', ['route' => 'popular_shop_index'])
            ->setAttribute('dropdown', true)
            ->setAttribute('class', 'nav-item dropdown')
            ->setLinkAttributes(['class' => 'nav-link dropdown-toggle']);

        $menu['Popular shop']->addChild('List', ['route' => 'popular_shop_index']);
        $menu['Popular shop']->addChild('Add', ['route' => 'popular_shop_new']);

        return $menu;
    }
}
