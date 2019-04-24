<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Natural Gallery',
    'description' => 'A lazy load, infinite scroll and natural layout list gallery',
    'category' => 'plugin',
    'author' => 'Fabien Udriot, Sylvain Tissot, Samuel Baptista',
    'author_email' => 'fabien.udriot@ecodev.ch, sylvain.tissot@ecodev.ch, samuel.baptista@ecodev.ch',
    'author_company' => 'Ecodev',
    'state' => 'beta',
    'version' => '1.0.10',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '7.6.0-9.5.99',
                    'vidi' => '0.0.0-0.0.0',
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
