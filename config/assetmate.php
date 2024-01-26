<?php

use craft\elements\Asset;

return [
    'volumes' => [
        '*' => [
            'convertUnmanipulable' => true,
            'validation' => [
                'size' => [
                    'max' => '20M',
                ]
            ]
        ],
        'images' => [
            'validation' => [
                'extensions' => ['gif', 'jpg', 'jpeg', 'png'],
                'size' => [
                    'max' => '12M',
                    'min' => '250K',
                ],
            ],
            'resize' => [
                'maxWidth' => 2200,
                'maxHeight' => 2200,
            ]
        ],        
        'illustrations' => [
            'validation' => [
                'extensions' => ['svg'],
                'size' => [
                    'max' => '1M',
                    'min' => '50K',
                ],
            ]
        ],        
        'videos' => [
            'validation' => [
                'extensions' => ['mp4'],
                'size' => [
                    'max' => '30M',
                ],
            ],
        ],
    ]
];