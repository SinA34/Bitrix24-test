<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;

$arComponentParameters = array(
    'PARAMETERS' => array(
        'SEF_MODE' => array(
            'details' => array(
                'NAME' => Loc::getMessage('LITEBOX_Z1_DETAILS_URL_TEMPLATE'),
                'DEFAULT' => '#STAFF_ID#/',
                'VARIABLES' => array('STAFF_ID')
            ),
            'edit' => array(
                'NAME' => Loc::getMessage('LITEBOX_Z1_EDIT_URL_TEMPLATE'),
                'DEFAULT' => '#STAFF_ID#/edit/',
                'VARIABLES' => array('STAFF_ID')
            ),
			'exp' => array(
                'NAME' => Loc::getMessage('LITEBOX_Z1_EDIT_URL_TEMPLATE'),
                'DEFAULT' => 'exp/'
            )
        )
    )
);
