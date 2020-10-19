<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/** @var CBitrixComponentTemplate $this */

/** @var ErrorCollection $errors */
$errors = $arResult['ERRORS'];

foreach ($errors as $error) {
    /** @var Error $error */
    ShowError($error->getMessage());
}

$APPLICATION->IncludeComponent(
    'bitrix:crm.interface.form',
    'edit',
    array(
        'GRID_ID' => $arResult['GRID_ID'],
        'FORM_ID' => $arResult['FORM_ID'],
        'ENABLE_TACTILE_INTERFACE' => 'Y',
        'SHOW_SETTINGS' => 'Y',
        'TITLE' => $arResult['TITLE'],
        'IS_NEW' => $arResult['IS_NEW'],
        'DATA' => $arResult['STAFF'],
        'TABS' => array(
            array(
                'id' => 'tab_1',
                'name' => Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_NAME'),
                'title' => Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_TITLE'),
                'display' => false,
                'fields' => array(
                    array(
                        'id' => 'section_staff',
                        'name' => Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_FIELD_SECTION_STAFF'),
                        'type' => 'section',
                        'isTactile' => true,
                    ),
                    array(
                        'id' => 'FIO',
                        'name' => Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_FIELD_FIO'),
                        'type' => 'text',
                        'value' => $arResult['STAFF']['FIO'],
                        'isTactile' => true,
                    ),
                    array(
                        'id' => 'DАTE_HIRING',
                        'name' => Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_FIELD_DАTE_HIRING'),
                        'type' => 'DateTime',
                        'value' => $arResult['STAFF']['DАTE_HIRING'],
                        'isTactile' => true,
                    ),
                    array(
                        'id' => 'POSITION_ID',
                        'name' => Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_FIELD_POSITION_ID'),
                        'type' => 'int',
                        'value' => $arResult['STAFF']['POSITION_ID'],
                        'isTactile' => true,
                    )
                )
            ),
        ),
        'BUTTONS' => array(
            'back_url' => $arResult['BACK_URL'],
            'standard_buttons' => true,
        ),
    ),
    $this->getComponent(),
    array('HIDE_ICONS' => 'Y')
);