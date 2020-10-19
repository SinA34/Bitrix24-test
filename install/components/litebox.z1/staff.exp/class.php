<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

class LiteboxZ1StaffExpComponent extends CBitrixComponent
{
    public function __construct(CBitrixComponent $component = null)
    {
        parent::__construct($component);

    }

    public function executeComponent()
    {
        global $APPLICATION;

        $APPLICATION->SetTitle(Loc::getMessage('LITEBOX_Z1_STAFF_EXP_TITLE_DEFAULT'));

        if (!Loader::includeModule('litebox.z1')) {
            ShowError(Loc::getMessage('LITEBOX_Z1_NO_MODULE'));
            return;
        }

        $staff = Litebox\Z1\StaffTable::getList();

        if (empty($staff)) {
            ShowError(Loc::getMessage('LITEBOX_Z1_STAFF_NOT_FOUND'));
            return;
        }

        $APPLICATION->SetTitle(Loc::getMessage('LITEBOX_Z1_STAFF_EXP_TITLE'));
		$fp = fopen('litebox_z1_staff.csv', 'w');
		while ($saStaff = $staff->fetch())
   			{
       		fputcsv($fp, $saStaff);
   			}
   		fclose($fp);

        $this->includeComponentTemplate();
    }
}