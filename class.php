<?php

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use CBitrixComponent;


class IdemTestTree extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['CACHE_TIME'] = $arParams['CACHE_TIME'] ?: 36000000;

        return $arParams;
    }

    public function executeComponent(): void
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception(Loc::getMessage('IDEM_TEST_TREE_REQUIRE_MODULE_ERROR'));
        }

        if (!$this->arParams['IBLOCK_ID'] || !$this->arParams['IBLOCK_TAGS_FIELD']) {
            throw new Exception(Loc::getMessage('IDEM_TEST_TREE_PARAMS_ERROR'));
        }

        if ($this->startResultCache()) {
            $cacheManager = Application::getInstance()->getTaggedCache();
            $cacheManager->registerTag('iblock_id_' . $this->arParams['IBLOCK_ID']);

            $entity = \Bitrix\Iblock\Iblock::wakeUp($this->arParams['IBLOCK_ID'])->getEntityDataClass();
            $elementCollection = $entity::query()
                ->setSelect([
                    'ID',
                    'NAME',
                    $this->arParams['IBLOCK_TAGS_FIELD'],
                    'SECTION_NAME' => 'SECTION.NAME',
                    'SECTION_ID' => 'SECTION.ID',
                ])
                ->registerRuntimeField(
                    'SECTION',
                    new ReferenceField(
                        'SECTION',
                        SectionTable::getEntity(),
                        ['=ref.ID' => 'this.IBLOCK_SECTION_ID']
                    )
                )
                ->fetchCollection();

            foreach ($elementCollection as $element) {
                $sectionId = $element->get('SECTION')?->getId() ?: null;

                $this->arResult['ITEMS'][$sectionId]['name'] = $element->get('SECTION')?->getName() ?: '';
                $this->arResult['ITEMS'][$sectionId]['elements'][] = $this->getElementInfo($element);
            }

            $this->includeComponentTemplate();
        }
    }

    private function getElementInfo(object $element): array
    {
        $res['name'] = $element->getName();

        foreach ($element->get($this->arParams['IBLOCK_TAGS_FIELD'])->getAll() as $customTag) {
            $res['tags'][] = $customTag->getValue();
        }

        $res['tags'] = !empty($res['tags'])
            ? '(' . implode(',', $res['tags']) . ')'
            : '';

        return $res;
    }
}
