<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'icon' => 'home',
                'route' => 'dashboard',
                'order' => 1,
                'is_active' => true,
                'children' => [],
            ],
            [
                'name' => 'Master Data',
                'slug' => 'master-data',
                'icon' => 'circle-stack',
                'route' => null,
                'order' => 2,
                'is_active' => true,
                'children' => [
                    [
                        'name' => 'Users',
                        'slug' => 'users',
                        'icon' => 'users',
                        'route' => 'users.index',
                        'order' => 1,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Roles',
                        'slug' => 'roles',
                        'icon' => 'shield-check',
                        'route' => 'roles.index',
                        'order' => 2,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Menus',
                        'slug' => 'menus',
                        'icon' => 'list-bullet',
                        'route' => 'menus.index',
                        'order' => 3,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Menu Access',
                        'slug' => 'menu-access',
                        'icon' => 'key',
                        'route' => 'menu-access.index',
                        'order' => 4,
                        'is_active' => true,
                    ],
                ],
            ],
        ];

        foreach ($menus as $menuData) {
            $children = $menuData['children'] ?? [];
            unset($menuData['children']);

            $menu = Menu::updateOrCreate(
                ['slug' => $menuData['slug']],
                $menuData
            );

            foreach ($children as $childData) {
                $childData['parent_id'] = $menu->id;
                Menu::updateOrCreate(
                    ['slug' => $childData['slug']],
                    $childData
                );
            }
        }
    }
}
