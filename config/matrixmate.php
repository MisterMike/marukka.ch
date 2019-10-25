<?php

return [
  'fields' => [
    'inhaltMatrix' => [
      'groups' => [[
        'label' => 'Inhalt',
        'types' => ['ueberschrift', 'inhalt', 'inhaltMitBild', 'bild', 'downloads', 'liste', 'zitat' ],
      ], [
        'label' => 'Erweiterte Inhalte',
        'types' => ['accordeon', 'bildergalerie',  'slider', 'youtubeVideo', 'callToAction', 'spezialelement'],
      ], [
        'label' => 'Layout',
        'types' => ['abstand', 'sektionsteiler', 'spalten'],
      ]],
      'types' => [
        'inhaltMitBild' => [
          'tabs' => [[
            'label' => 'Inhalt',
            'fields' => ['inhalt', 'bild'],
          ], [
            'label' => 'Einstellungen',
            'fields' => ['ausrichtung'],
          ], [
            'label' => 'Bildlink',
            'fields' => ['internerLink', 'externerLink', 'linkZuDatei'],
          ]],
        ],
        'bild' => [
          'tabs' => [[
            'label' => 'Bild',
            'fields' => ['bild', 'grossansichtAktivieren', 'fullwidth'],
          ], [
            'label' => 'Bildlink',
            'fields' => ['internerLink', 'externerLink', 'linkZuDatei'],
          ]],
        ],
        'Beispiel(news)' => [
          'maxLimit' => 1,
        ],
      ],
    ]
  ],
];