<?php
defined('B_PROLOG_INCLUDED') || die;

/** @var CBitrixComponentTemplate $this */
/*
$APPLICATION->IncludeComponent(
    'bitrix:crm.control_panel',
    '',
    array(
        'ID' => 'STAFF',
        'ACTIVE_ITEM_ID' => 'STAFF',
    ),
    $component
);
*/
$urlTemplates = array(
    'DETAIL' => $arResult['SEF_FOLDER'] . $arResult['SEF_URL_TEMPLATES']['details'],
    'EDIT' => $arResult['SEF_FOLDER'] . $arResult['SEF_URL_TEMPLATES']['edit'],
);

$APPLICATION->IncludeComponent(
    'litebox.z1:staff.edit',
    '',
    array(
        'STAFF_ID' => $arResult['VARIABLES']['STAFF_ID'],
        'URL_TEMPLATES' => $urlTemplates,
        'SEF_FOLDER' => $arResult['SEF_FOLDER'],
    ),
    $this->getComponent(),
    array('HIDE_ICONS' => 'Y')
);