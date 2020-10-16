<?php
/**
 * Синицын АВ - 2020
 */
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config as Conf;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Application;

use Bitrix\Main\Diag\Debug;

Loc::loadMessages(__FILE__);
Class litebox_z1 extends CModule
{

	function __construct()
	{
		$arModuleVersion = array();
		include(__DIR__."/version.php");

                $this->MODULE_ID = 'litebox.z1';
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("LITEBOX_TEST_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("LITEBOX_TEST_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("LITEBOX_TEST_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("LITEBOX_TEST_PARTNER_URI");

        $this->MODULE_SORT = 1;
	}

    //Определяем место размещения модуля
    public function GetPath($notDocumentRoot=false)
    {
        if($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(),'',dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    //Проверяем что система поддерживает D7
    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);
        $ss = \Litebox\Z1\PositionsTable::getTableName();
        
        if(!Application::getConnection(\Litebox\Z1\PositionsTable::getConnectionName())->isTableExists(
            \Litebox\Z1\PositionsTable::getTableName())
        )
        {
            Base::getInstance('\Litebox\Z1\PositionsTable')->createDbTable();
        }
 
        if(!Application::getConnection(\Litebox\Z1\StaffTable::getConnectionName())->isTableExists(
            Base::getInstance('\Litebox\Z1\StaffTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Litebox\Z1\StaffTable')->createDbTable();
        }
     }

    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);
       
        Application::getConnection(\Litebox\Z1\StaffTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('\Litebox\Z1\StaffTable')->getDBTableName());

        Application::getConnection(\Litebox\Z1\PositionsTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('\Litebox\Z1\PositionsTable')->getDBTableName());

        Option::delete($this->MODULE_ID);
    }

	function InstallFiles($arParams = array())
	{
        $path=$this->GetPath()."/install/components";

        if(\Bitrix\Main\IO\Directory::isDirectoryExists($path))
            CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"]."/local/components", true, true);
        else
            throw new \Bitrix\Main\IO\InvalidPathException($path);

        return true;
	}

	function UnInstallFiles()
	{
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/local/components/litebox.z1/');
 		return true;
 
	}

	function DoInstall()
	{
	global $APPLICATION;
        if($this->isVersionD7())
        {
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallFiles();

            #работа с .settings.php
            $configuration = Conf\Configuration::getInstance();
            $litebox_module=$configuration->get('litebox_module_z1');
            $litebox_module['install']=$litebox_module['install']+1;
            $configuration->add('litebox_module_z1', $litebox_module);
            $configuration->saveConfiguration();
            #работа с .settings.php
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("LITEBOX_TEST_INSTALL_ERROR_VERSION"));
        }

//        $APPLICATION->IncludeAdminFile(Loc::getMessage("LITEBOX_TEST_INSTALL_TITLE"), $this->GetPath()."/install/step.php");
	}

	function DoUninstall()
	{
        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if($request["step"]<2)
        {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("LITEBOX_TEST_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep1.php");
        }
        elseif($request["step"]==2)
        {
            $this->UnInstallFiles();

            if($request["savedata"] != "Y")
                $this->UnInstallDB();

            \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

            #работа с .settings.php
            $configuration = Conf\Configuration::getInstance();
            $litebox_module=$configuration->get('litebox_module_z1');
            $litebox_module['uninstall']=$litebox_module['uninstall']+1;
            $configuration->add('litebox_module_z1', $litebox_module);
            $configuration->saveConfiguration();
            #работа с .settings.php

            $APPLICATION->IncludeAdminFile(Loc::getMessage("LITEBOX_TEST_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep2.php");
        }
	}

}
?>
