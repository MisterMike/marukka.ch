<?php

return [
  'defaultProfile' => 'standard',

  'fieldProfiles' => [
    'standard' => [
      'title' => ['seoTitel', 'title'],
      'description' => ['seoBeschreibung'],
      'image' => ['seitenbild']
    ]
  ],
  
  'defaultMeta' => [
    'image' => ['standardSeitenbild.seitenbild']
  ],

  'sitemapEnabled' => true,
  'sitemapLimit' => 100,
  'sitemapConfig' => [
    'elements' => [
      'website' => ['changefreq' => 'weekly', 'priority' => 1],
      'team' => ['changefreq' => 'monthly', 'priority' => 1],
      'news' => ['changefreq' => 'weekly', 'priority' => 1],
      'faq' => ['changefreq' => 'weekly', 'priority' => 1],
      'events' => ['changefreq' => 'weekly', 'priority' => 1],
      'weitereSeiten' => ['changefreq' => 'monthly', 'priority' => 1],
    ],
  ],
  
];