<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;


/** @var CBitrixComponentTemplate $this */

$APPLICATION->SetTitle(Loc::getMessage('LITEBOX_Z1_LIST_TITLE'));

/*
$APPLICATION->IncludeComponent(
    'bitrix:crm.control_panel',
    '',
    array(
        'ID' => 'STORES',
        'ACTIVE_ITEM_ID' => 'STORES',
    ),
    $component
);
*/

$urlTemplates = array(
    'DETAIL' => $arResult['SEF_FOLDER'] . $arResult['SEF_URL_TEMPLATES']['details'],
    'EDIT' => $arResult['SEF_FOLDER'] . $arResult['SEF_URL_TEMPLATES']['edit'],
	'EXP' => $arResult['SEF_FOLDER'] . $arResult['SEF_URL_TEMPLATES']['exp'],
);

$APPLICATION->IncludeComponent(
    'bitrix:crm.interface.toolbar',
    'title',
    array(
        'TOOLBAR_ID' => 'LITEBOX_Z1_STAFF_LIST_TOOLBAR',
        'BUTTONS' => array(
            array(
                'TEXT' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_TEXT_ADD'),
                'TITLE' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_TITLE_ADD'),
                'LINK' => CComponentEngine::makePathFromTemplate($urlTemplates['EDIT'], array('STAFF_ID' => 0)),
                'ICON' => 'btn-add',
            ),
            array(
                'TEXT' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_TEXT_EXP'),
                'TITLE' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_TITLE_EXP'),
                'LINK' => CComponentEngine::makePathFromTemplate($urlTemplates['EXP']),
                'ICON' => 'btn-add',
            ),
        )
    ),
    $this->getComponent(),
    array('HIDE_ICONS' => 'Y')
);

$APPLICATION->IncludeComponent(
    'litebox.z1:staff.list',
    '',
    array(
        'URL_TEMPLATES' => $urlTemplates,
        'SEF_FOLDER' => $arResult['SEF_FOLDER'],
    ),
    $this->getComponent(),
    array('HIDE_ICONS' => 'Y',)
);