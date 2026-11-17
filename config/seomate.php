<?php
return [
    'cacheEnabled' => false,
    'defaultProfile' => 'standard',
    'fieldProfiles' => [
        'standard' => ['title' => ['title'], 'description' => ['lead'], 'image' => ['heroImage']],
    ],
    'additionalMeta' => [
        'og:type' => 'website',
        'og:site_name' => '{{ siteName }}',
        'twitter:card' => 'summary_large_image',
    ],
    'applyRestrictions' => true,
    'truncateSuffix' => '…',
    'tagTemplateMap' => [
        'default' => '<meta name="{{ key }}" content="{{ value }}">',
        'title' => '',
        '/^og:/,/^fb:/' => '<meta property="{{ key }}" content="{{ value }}">',
    ],
    'sitemapEnabled' => true,
    'sitemapConfig' => ['elements' => [
        'home' => ['changefreq' => 'weekly', 'priority' => 1],
        'snippets' => ['changefreq' => 'weekly', 'priority' => 0.8],
        'categories' => ['changefreq' => 'weekly', 'priority' => 0.7],
    ]],
];
