<?php

/** @var array $arCurrentValues */

use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock')) {
    return;
}

$iBlockList = IblockTable::query()
    ->setSelect(['ID', 'NAME'])
    ->where('ACTIVE', 'Y')
    ->setCacheTtl(86400)
    ->fetchAll();

$iBLockParamList = [];
foreach ($iBlockList as $iBlock) {
    $iBLockParamList[$iBlock['ID']] = $iBlock['NAME'];
}

$propertyParamList = [];
if ($arCurrentValues['IBLOCK_ID']) {
    $propertyList = PropertyTable::query()
        ->setSelect(['NAME', 'CODE'])
        ->where('IBLOCK_ID', $arCurrentValues['IBLOCK_ID'])
        ->setCacheTtl(86400)
        ->fetchAll();

    foreach ($propertyList as $property) {
        $propertyParamList[$property['CODE']] = $property['NAME'];
    }
}

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => [
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' =>  Loc::getMessage('IDEM_TEST_TREE_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $iBLockParamList,
            'REFRESH' => 'Y'
        ],
        'IBLOCK_TAGS_FIELD' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('IDEM_TEST_TREE_IBLOCK_TAGS_FIELD'),
            'TYPE' => 'LIST',
            'VALUES' => $propertyParamList,
        ],
        'CACHE_TIME' => ['DEFAULT' => 36000000],
    ]
];

