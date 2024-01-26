<?php

return [
    'defaultProfile' => 'standard',
    
    'fieldProfiles' => [
        'standard' => [
            'title' => ['seoTitle', 'title'],
            'description' => ['seoDescription', 'lead'],
            'image' => ['seoImage', 'hero']
        ]
    ],
    'sitemapEnabled' => true,
    'sitemapLimit' => 100,
    'sitemapConfig' => [
        'elements' => [
            'website' => ['changefreq' => 'weekly', 'priority' => 1],
            'projekte' => ['changefreq' => 'weekly', 'priority' => 1],
            'home' => ['changefreq' => 'daily', 'priority' => 1],
            'news' => ['changefreq' => 'daily', 'priority' => 1],
            'team' => ['changefreq' => 'weekly', 'priority' => 1],
            'bilder' => ['changefreq' => 'weekly', 'priority' => 1],
            'weitereSeiten' => ['changefreq' => 'monthly', 'priority' => 1],
        ],
    ]
];