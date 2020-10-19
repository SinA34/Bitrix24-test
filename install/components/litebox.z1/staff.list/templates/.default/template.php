<?php
/**
* @var array $arResult
* @var array $arParams
*/
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

 use Bitrix\Main\Page\Asset;
 use Bitrix\Main\Localization\Loc;

$APPLICATION->SetTitle(Loc::getMessage('LITEBOX_Z1_STAFF_LIST_TITLE_PAGE'));

$asset = Asset::getInstance();
$asset->addJs('/bitrix/js/crm/interface_grid.js');
$asset->addJs('/bitrix/js/crm/crm.js');
$asset->addCss('/bitrix/js/crm/css/crm.css');
?>

<?php
$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            'FILTER_ID' => $arResult['FILTER_ID'],
            'GRID_ID' => $arResult['GRID_ID'],
            'FILTER' => $arResult['UI_FILTER'],
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true
        ]);
$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    array(
        'CACHE_TYPE' => 'A',
        "GRID_ID" => $arResult['GRID_ID'],
        "HEADERS" => $arResult['HEADERS'],
        "ROWS" => $arResult['DATA'],
        'FILTER' => $arResult['FILTER'],
        'SORT' => $arResult['SORT'],
        'NAV_OBJECT' => $arResult['NAV_OBJECT'],
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_HISTORY' => 'N',
        'PAGE_SIZES' => [
            ['NAME' => "5", 'VALUE' => '5'],
            ['NAME' => '10', 'VALUE' => '10'],
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
            ['NAME' => '100', 'VALUE' => '100']
        ],
        'SHOW_CHECK_ALL_CHECKBOXES' => false,
        'SHOW_ROW_CHECKBOXES'       => false,
        'SHOW_ROW_ACTIONS_MENU'     => true,
        'SHOW_GRID_SETTINGS_MENU'   => true,
        'SHOW_NAVIGATION_PANEL'     => true,
        'SHOW_PAGINATION'           => true,
        'SHOW_SELECTED_COUNTER'     => false,
        'SHOW_TOTAL_COUNTER'        => false,
        'SHOW_PAGESIZE'             => true,
		'SHOW_ACTION_PANEL'         => true,
    ),
    $this->getComponent(),
    array("HIDE_ICONS" => "Y")
);
?>

