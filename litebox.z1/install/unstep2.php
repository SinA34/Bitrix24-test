<?php
/**
 * Синицын АВ - 2020
 */
use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid())
	return;

#работа с .settings.php
$install_count=\Bitrix\Main\Config\Configuration::getInstance()->get('litebox_module_z1');
#работа с .settings.php

if ($ex = $APPLICATION->GetException())
	echo CAdminMessage::ShowMessage(array(
		"TYPE" => "ERROR",
		"MESSAGE" => Loc::getMessage("MOD_UNINST_ERR"),
		"DETAILS" => $ex->GetString(),
		"HTML" => true,
	));
else
	echo CAdminMessage::ShowNote(Loc::getMessage("MOD_UNINST_OK"));

#работа с .settings.php
echo CAdminMessage::ShowMessage(array("MESSAGE"=>Loc::getMessage("LITEBOX_TEST_UNINSTALL_COUNT").$install_count['uninstall'],"TYPE"=>"OK"));
#работа с .settings.php
?>
<form action="<?php echo $APPLICATION->GetCurPage(); ?>">
	<input type="hidden" name="lang" value="<?php echo LANGUAGE_ID; ?>">
	<input type="submit" name="litebox_z1" value="<?php echo Loc::getMessage('MOD_BACK'); ?>">
<form>
