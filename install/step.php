<?php
/**

 * Синицын АВ - 2020
 */
use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid())
	return;

#работа с .settings.php
$install_count=\Bitrix\Main\Config\Configuration::getInstance()->get('litebox_module_z1');

$cache_type=\Bitrix\Main\Config\Configuration::getInstance()->get('cache');
#работа с .settings.php

if ($ex = $APPLICATION->GetException())
	echo CAdminMessage::ShowMessage(array(
		"TYPE" => "ERROR",
		"MESSAGE" => Loc::getMessage("MOD_INST_ERR"),
		"DETAILS" => $ex->GetString(),
		"HTML" => true,
	));
else 
	echo CAdminMessage::ShowNote(Loc::getMessage("MOD_INST_OK"));

#работа с .settings.php
echo CAdminMessage::ShowMessage(array("MESSAGE"=>Loc::getMessage("LITEBOX_TEST_INSTALL_COUNT").$install_count['install'],"TYPE"=>"OK"));

if(!$cache_type['type'] || $cache_type['type']=='none')
	echo CAdminMessage::ShowMessage(array("MESSAGE"=>Loc::getMessage("LITEBOX_TEST_NO_CACHE"),"TYPE"=>"ERROR"));
#работа с .settings.php
?>
<form action="<?php echo $APPLICATION->GetCurPage(); ?>">
	<input type="hidden" name="lang" value="<?php echo LANGUAGE_ID ?>">
	<input type="submit" name="litebox_z1" value="<?php echo Loc::getMessage('MOD_BACK') ?>">
<form>
