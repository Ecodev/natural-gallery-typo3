<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Natural Gallery',
    'description' => 'A lazy load, infinite scroll and natural layout list gallery',
    'category' => 'plugin',
    'author' => 'Fabien Udriot, Sylvain Tissot, Samuel Baptista',
    'author_email' => 'fabien.udriot@ecodev.ch, sylvain.tissot@ecodev.ch, samuel.baptista@ecodev.ch',
    'author_company' => 'Ecodev',
    'state' => 'stable',
    'version' => '2.3.0-dev',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '10.0.0-10.4.99',
                    'vidi' => '5.0.0-0.0.0',
                    'vhs' => '0.0.0-0.0.0',
                ],
            'conflicts' =>
                [
                ],
            'suggests' =>
                [
                ],
        ],
];
