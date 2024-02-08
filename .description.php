<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) {
    die();
}

$arComponentDescription = [
    'NAME' => Loc::getMessage('IDEM_TEST_TREE'),
    'DESCRIPTION' => Loc::getMessage('IDEM_TEST_TREE'),
    'PATH' => [
        'ID' => 'idemtest',
        'NAME' => Loc::getMessage('IDEM_TEST_TREE_PARENT'),
    ],
    'CACHE_PATH' => 'Y',
];

