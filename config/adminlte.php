<?php

return [

    'title' => 'Es Teh Kita',

    'logo' => '<b>EsTeh</b>Kita',

    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',

    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar'  => true,

    /*
    |--------------------------------------------------------------------------
    | Menu
    |--------------------------------------------------------------------------
    */

    'menu' => [

        [
            'text'  => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon'  => 'fas fa-home',
        ],

        ['header' => 'DATA MASTER'],

        [
            'text'  => 'Data Outlet',
            'route' => 'admin.outlet.index',
            'icon'  => 'fas fa-store',
        ],

        [
            'text'  => 'Data Bahan',
            'route' => 'admin.bahan.index',
            'icon'  => 'fas fa-box',
        ],

        [
            'text'  => 'Stok Masuk',
            'route' => 'admin.stok-masuk.index',
            'icon'  => 'fas fa-arrow-down',
        ],

        [
            'text'  => 'Distribusi',
            'route' => 'admin.distribusi.index',
            'icon'  => 'fas fa-truck',
        ],

        [
            'text'  => 'Laporan',
            'url'   => '#',
            'icon'  => 'fas fa-file',
        ],

        ['header' => 'AKUN'],

        [
            'text'   => 'Logout',
            'route'  => 'logout',
            'method' => 'post',
            'icon'   => 'fas fa-sign-out-alt',
        ],
    ],
];
