<?php

use craft\elements\Entry;
use craft\helpers\UrlHelper;

return [
    'endpoints' => [
        'news.json' => function() {
            // Allow other sites to access this endpoint. Use with caution...
            // \Craft::$app->response->headers->set('Access-Control-Allow-Origin', '*');

            $howMany = \Craft::$app->request->getParam('count');

            if ($howMany) {
                $count = filter_var( $howMany, FILTER_SANITIZE_NUMBER_INT );
            } else {
                $count = 5;
            }
            

            return [
                'elementType' => Entry::class,
                'criteria' => ['section' => 'news'],
                'elementsPerPage' => $count,
                'cache' => false,
                'transformer' => function(Entry $entry) {

                    $image = $entry->hero->one();

                    $categories = [];
                    foreach ($entry->kategorie as $cat)
                        $categories[] = $cat->title;

                    return [
                        'title' => $entry->title,
                        'url' => $entry->url,
                        'date'=> $entry->postDate,
                        'teaser' => $entry->lead ? $entry->lead : null,
                        'category' => $categories,
                        'image' => $image ? $image->getUrl('newsThumb') : null
                    ];
                },
            ];
        },
        'news/<entryId:\d+>.json' => function($entryId) {
            return [
                'elementType' => Entry::class,
                'criteria' => ['id' => $entryId],
                'one' => true,
                'transformer' => function(Entry $entry) {
                    return [
                        'title' => $entry->title,
                        'url' => $entry->url,
                        'teaser' => $entry->teaser ? $entry->teaser : null,
                        'body' => $entry->imhalt,
                    ];
                },
            ];
        },
    ]
];