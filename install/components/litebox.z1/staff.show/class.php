<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

class LiteboxZ1StaffShowComponent extends CBitrixComponent
{
    const FORM_ID = 'LITEBOX_Z1_STAFF_SHOW';

    public function __construct(CBitrixComponent $component = null)
    {
        parent::__construct($component);

        CBitrixComponent::includeComponentClass('litebox.z1:staff.list');
        CBitrixComponent::includeComponentClass('litebox.z1:staff.edit');
    }

    public function executeComponent()
    {
        global $APPLICATION;

        $APPLICATION->SetTitle(Loc::getMessage('LITEBOX_Z1_STAFF_SHOW_TITLE_DEFAULT'));

        if (!Loader::includeModule('litebox.z1')) {
            ShowError(Loc::getMessage('LITEBOX_Z1_NO_MODULE'));
            return;
        }

        $dbStaff = Litebox\Z1\StaffTable::getById($this->arParams['STAFF_ID']);
        $staff = $dbStaff->fetch();

        if (empty($staff)) {
            ShowError(Loc::getMessage('LITEBOX_Z1_STAFF_NOT_FOUND'));
            return;
        }

        $APPLICATION->SetTitle(Loc::getMessage(
            'LITEBOX_Z1_STAFF_SHOW_TITLE',
            array(
                '#ID#' => $staff['ID'],
                '#FIO#' => $staff['FIO']
            )
        ));

        $this->arResult =array(
            'FORM_ID' => self::FORM_ID,
            'TACTILE_FORM_ID' => LiteboxZ1StaffEditComponent::FORM_ID,
            'GRID_ID' => LiteboxZ1StaffListComponent::GRID_ID,
            'STAFF' => $staff
        );

        $this->includeComponentTemplate();
    }
}