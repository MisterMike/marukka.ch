<?php

return [
    'defaultProfile' => 'standard',
    
    'fieldProfiles' => [
        'standard' => [
            'title' => ['seoTitle', 'title'],
            'description' => ['seoDescription', 'lead'],
            'image' => ['seoImage', 'hero'],
            'og:image' => ['seoImage'],
            'twitter:image' => ['seoImage']
        ]
    ],
    'sitemapEnabled' => true,
    'sitemapLimit' => 100,
    'sitemapConfig' => [
        'elements' => [
            'website' => ['changefreq' => 'weekly', 'priority' => 1],
            'craftsnippets' => ['changefreq' => 'weekly', 'priority' => 1],
            'home' => ['changefreq' => 'monthly', 'priority' => 1],
            'mac' => ['changefreq' => 'weekly', 'priority' => 1],
            'css' => ['changefreq' => 'weekly', 'priority' => 1],
            'jssnippets' => ['changefreq' => 'weekly', 'priority' => 1],
            'laravel' => ['changefreq' => 'weekly', 'priority' => 1],
        ],
    ],

    'defaultMeta' => [
        'description' => ['defaultSeo.metaDescription'],
        'keywords' => ['defaultSeo.keywords'],
        'image' => ['defaultSeo.seoImage'],
        'og:image' => ['defaultSeo.seoImage'],
        'twitter:image' => ['defaultSeo.seoImage'],
    ],

    'cacheEnabled' => false,
];