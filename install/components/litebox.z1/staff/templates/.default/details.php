<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Uri;


/** @var CBitrixComponentTemplate $this */

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
);

$editUrl = CComponentEngine::makePathFromTemplate(
    $urlTemplates['EDIT'],
    array('STAFF_ID' => $arResult['VARIABLES']['STAFF_ID'])
);

$viewUrl = CComponentEngine::makePathFromTemplate(
    $urlTemplates['DETAIL'],
    array('STAFF_ID' => $arResult['VARIABLES']['STAFF_ID'])
);

$editUrl = new Uri($editUrl);
$editUrl->addParams(array('backurl' => $viewUrl));
/*
$APPLICATION->IncludeComponent(
    'bitrix:crm.interface.toolbar',
    'type2',
    array(
        'TOOLBAR_ID' => 'CRMSTORES_TOOLBAR',
        'BUTTONS' => array(
            array(
                'TEXT' => Loc::getMessage('CRMSTORES_EDIT'),
                'TITLE' => Loc::getMessage('CRMSTORES_EDIT'),
                'LINK' => $editUrl->getUri(),
                'ICON' => 'btn-edit',
            ),
        )
    ),
    $this->getComponent(),
    array('HIDE_ICONS' => 'Y')
);
*/

$APPLICATION->IncludeComponent(
    'litebox.z1:staff.show',
    '',
    array(
        'STAFF_ID' => $arResult['VARIABLES']['STAFF_ID']
    ),
    $this->getComponent(),
    array('HIDE_ICONS' => 'Y',)
);
