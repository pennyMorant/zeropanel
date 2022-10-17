<?php 

# 浅色主题
$_style['primary'] = [
    'global' => [
        'title'             => 'text-primary',
        'text'              => 'text-dark-75',
        'svg'               => 'svg-icon-primary',
        'btn_subheader'     => 'btn-outline-primary',
    ],
    'modal' => [
        'text_title' => 'text-dark',
        'btn_close'  => 'btn-light-primary',
    ],
    'alert' => 'alert-white',
    'index'  => [
        'dash1' => [
            'bg'  => '',
            'svg' => 'svg-icon-white',
        ],
        'tn' => [
            'bg'  => '',
            'svg' => 'svg-icon-success',
            'btn' => 'label-success',
            'text' => 'text-dark-50',
        ],
        'dash3' => [
            'bg'  => '',
            'svg' => 'svg-icon-warning',
        ],
        'dash4' => [
            'bg'  => '',
            'svg' => 'svg-icon-danger',
        ],
        'text' => 'text-dark',
    ],
    'shop' => [
        'card_head' => '',
        'card_bg'   => '',
        'card_text' => '',
        'card_btn'  => '',
    ],
    'shared' => [
        'item'   => 'bg-white text-dark',
    ],
    'help' => [
        'search_bg' => 'bg-white',
    ],
    'toolbar' => [
        'bg' => 'btn-bg-light'
    ],
    'JavaScript' => json_encode([
        'theme' => 'primary',
        'global' => [
            'gray' => [
                '100' => '#F3F6F9',
                '200' => '#ECF0F3',
                '300' => '#E5EAEE',
                '400' => '#D6D6E0',
                '500' => '#B5B5C3',
                '600' => '#80808F',
                '700' => '#464E5F',
                '800' => '#1B283F',
                '900' => '#212121',
            ],
        ],
        'index' => [
            'flowTiaoChart' => [
                'strip' => '#3B5998',
                'light' => '#E1E9FF',
                'text'  => '#464E5F',
            ],
            'index-NodeTrafficChart-card' => [
                'strip' => '#3B5998',
                'light' => '#E1E9FF',
                'text'  => '464E5F',
            ],
        ],
    ]),
];
# 深色主题
$_style['dark'] = [
    'global' => [
        'title'             => 'text-primary',
        'text'              => 'text-white',
        'svg'               => 'svg-icon-white',
        'btn_subheader'     => 'btn-gray',
    ],
    'modal' => [
        'text_title' => 'text-white',
        'btn_close'  => 'btn-gray',
    ],
    'alert' => 'alert-gray',
    'index'  => [
        'dash1' => [
            'bg'  => 'bg-card-dark',
            'svg' => 'svg-icon-primary',
        ],
        'tn' => [
            'bg'  => 'bg-card-dark',
            'svg' => 'svg-icon-success',
            'btn' => 'label-success',
            'text' => 'text-muted',
        ],
        'dash3' => [
            'bg'  => 'bg-card-dark',
            'svg' => 'svg-icon-warning',
        ],
        'dash4' => [
            'bg'  => 'bg-card-dark',
            'svg' => 'svg-icon-danger',
        ],
        'text' => 'text-white',
    ],
    'shop' => [
        'card_head' => 'bg-radial-gradient-dark-head',
        'card_bg'   => 'bg-card-dark',
        'card_text' => 'text-white',
        'card_btn'  => 'btn-gray',
    ],
    'shared' => [
        'item'   => 'bg-card-dark text-white',
    ],
    'help' => [
        'search_bg' => 'bg-input-dark',
    ],
    'toolbar' => [
        'bg' => 'btn-bg-dark-75'
    ],
    'JavaScript' => json_encode([
        'theme' => 'dark',
        'global' => [
            'gray' => [
                '100' => '#F3F6F9',
                '200' => '#ECF0F3',
                '300' => '#E5EAEE',
                '400' => '#D6D6E0',
                '500' => '#B5B5C3',
                '600' => '#80808F',
                '700' => '#464E5F',
                '800' => '#1B283F',
                '900' => '#212121',
            ],
        ],
        'index' => [
            'flowTiaoChart' => [
                'strip'     => '#6993FF',
                'light'     => '#3a3b3c',
                'text'      => '#FFFFFF',
            ],
        ],
    ]),
];
