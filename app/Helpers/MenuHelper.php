<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Menu',
                'items' => [
                    [
                        'icon' => 'dashboard',
                        'name' => 'Dashboard',
                        'subItems' => [
                            ['name' => 'Peminjaman Perangkat', 'route' => 'admin.peminjaman'],
                            ['name' => 'Pemindahan Ruangan', 'route' => 'admin.pemindahan']
                        ],
                    ],
                    [
                        'icon' => 'tables',
                        'name' => 'Master Data',
                        'subItems' => [
                            ['name' => 'Manajemen Perangkat', 'route' => 'brg.index'],
                            ['name' => 'Manajemen Ruangan', 'route' => 'room.index'],
                            ['name' => 'Manajemen Admin', 'route' => 'admin.mgmt.index']
                        ],
                    ],
                    [
                        'icon' => 'reports',
                        'name' => 'Laporan',
                        'route'=> 'admin.laporan'
                    ],
                ],
            ],
        ];
    }

    public static function getIconSvg($icon)
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path opacity="0.4" d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z" fill="currentColor"/></svg>',
            'tables' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path opacity="0.4" d="M16.191 2H7.81C4.77 2 3 3.78 3 6.83V17.16C3 20.26 4.77 22 7.81 22H16.191C19.28 22 21 20.26 21 17.16V6.83C21 3.78 19.28 2 16.191 2Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M8.08 6.65C7.65 6.65 7.3 7 7.3 7.44C7.3 7.87 7.65 8.22 8.08 8.22H15.92C16.35 8.22 16.7 7.87 16.7 7.44C16.7 7 16.35 6.65 15.92 6.65H8.08ZM8.08 11.18C7.65 11.18 7.3 11.53 7.3 11.96C7.3 12.39 7.65 12.74 8.08 12.74H15.92C16.35 12.74 16.7 12.39 16.7 11.96C16.7 11.53 16.35 11.18 15.92 11.18H8.08ZM8.08 15.74C7.65 15.74 7.3 16.09 7.3 16.53C7.3 16.96 7.65 17.31 8.08 17.31H15.92C16.35 17.31 16.7 16.96 16.7 16.53C16.7 16.09 16.35 15.74 15.92 15.74H8.08Z" fill="currentColor"/></svg>',
            'reports' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path opacity="0.4" d="M7 2H14L19 7V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V4C5 2.9 5.9 2 7 2Z" fill="currentColor"/><path d="M14 2V7H19" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8.5 17L11 14.5L13 16.5L16 13.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>'
        ];
        return $icons[$icon] ?? '';
    }
}
