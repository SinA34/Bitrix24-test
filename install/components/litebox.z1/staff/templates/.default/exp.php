<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Uri;



$APPLICATION->IncludeComponent(
    'litebox.z1:staff.exp','','',
    $this->getComponent(),
    array('HIDE_ICONS' => 'Y',)
);
