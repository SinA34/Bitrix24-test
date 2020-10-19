<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/** @var CBitrixComponentTemplate $this */

if (!Loader::includeModule('crm')) {
    ShowError(Loc::getMessage('CRMSTORES_NO_CRM_MODULE'));
    return;
}

$APPLICATION->IncludeComponent(
    'bitrix:crm.interface.form',
    'show',
    array(
        'GRID_ID' => $arResult['GRID_ID'],
        'FORM_ID' => $arResult['FORM_ID'],
        'TACTILE_FORM_ID' => $arResult['TACTILE_FORM_ID'],
        'ENABLE_TACTILE_INTERFACE' => 'Y',
        'SHOW_SETTINGS' => 'Y',
        'DATA' => $arResult['STAFF'],
        'TABS' => array(
            array(
                'id' => 'tab_1',
                'name' => Loc::getMessage('LITEBOX_Z1_STAFF_SHOW_TAB_NAME'),
                'title' => Loc::getMessage('LITEBOX_Z1_STAFF_SHOW_TAB_TITLE'),
                'display' => false,
                'fields' => array(
                    array(
                        'id' => 'section_store',
                        'name' => Loc::getMessage('LITEBOX_Z1_STAFF_SHOW__FIELD_SECTION'),
                        'type' => 'section',
                        'isTactile' => true,
                    ),
                    array(
                        'id' => 'ID',
                        'name' => Loc::getMessage('LITEBOX_Z1_STAFF_SHOW__FIELD_ID'),
                        'type' => 'label',
                        'value' => $arResult['STAFF']['ID'],
                        'isTactile' => true,
                    ),
                    array(
                        'id' => 'FIO',
                        'name' => Loc::getMessage('LITEBOX_Z1_STAFF_SHOW__FIELD_FIO'),
                        'type' => 'label',
                        'value' => $arResult['STAFF']['FIO'],
                        'isTactile' => true,
                    ),
                    array(
                        'id' => 'DАTE_HIRING',
                        'name' => Loc::getMessage('LITEBOX_Z1_STAFF_SHOW_FIELD_DАTE_HIRING'),
                        'type' => 'label',
                        'value' => $arResult['STORE']['DАTE_HIRING'],
                        'isTactile' => true,
                    ),
                    array(
                        'id' => 'POSITION_ID',
                        'name' => Loc::getMessage('LITEBOX_Z1_STAFF_SHOW_FIELD_POSITION_ID'),
                        'type' => 'label',
                        'isTactile' => true,
                    )
                )
            )
        ),
    ),
    $this->getComponent(),
    array('HIDE_ICONS' => 'Y')
);