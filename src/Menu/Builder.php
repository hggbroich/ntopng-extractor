<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

readonly class Builder {
    public function __construct(
        private FactoryInterface $factory
    ) { }

    public function mainMenu(array $options): ItemInterface {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav me-auto');

        $menu->addChild('dashboard.menu', [
            'route' => 'dashboard'
        ])
            ->setExtra('icon', 'fa fa-home');

        $menu->addChild('flows.menu', [
            'route' => 'flows'
        ])
            ->setExtra('icon', 'fa fa-list');

        $menu->addChild('hosts.menu', [
            'route' => 'hosts'
        ])
            ->setExtra('icon', 'fa fa-computer');

        return $menu;
    }
}
