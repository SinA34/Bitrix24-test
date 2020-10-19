<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->IncludeComponent(
	'litebox.test:test', 
	'', 
	array(
		'SEF_MODE' => 'Y',
		'SEF_FOLDER' => '/litebox/test/',
		'SEF_URL_TEMPLATES' => array(
			'details' => '#STAFF_ID#/',
			'edit' => '#STAFF_ID#/edit/',
		)
	),
	false
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
