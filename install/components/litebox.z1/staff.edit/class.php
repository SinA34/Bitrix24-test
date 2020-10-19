<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Context;
use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

class LiteboxZ1StaffEditComponent extends CBitrixComponent
{
    const FORM_ID = 'LITEBOX_Z1_STAFF_EDIT';

    private $errors;

    public function __construct(CBitrixComponent $component = null)
    {
        parent::__construct($component);

        $this->errors = new ErrorCollection();

        CBitrixComponent::includeComponentClass('litebox.z1:staff.list');
    }

    public function executeComponent()
    {
        global $APPLICATION;

        $title = Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_SHOW_TITLE_DEFAULT');

        if (!Loader::includeModule('litebox.z1')) {
            ShowError(Loc::getMessage('CLITEBOX_Z1_STAFF_EDIT_NO_MODULE'));
            return;
        }

        $staff = array(
            'FIO' => '',
			'DАTE_HIRING' => \Bitrix\Main\Type\DateTime::createFromUserTime('01.01.2020 00:00:01'),
            'POSITION_ID' => 1
        );

        if (intval($this->arParams['STAFF_ID']) > 0) {
            $dbStaff = Litebox\Z1\StaffTable::getById($this->arParams['STAFF_ID']);
            $staff = $dbStaff->fetch();

            if (empty($staff)) {
                ShowError(Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_NOT_FOUND'));
                return;
            }
        }

        if (!empty($staff['ID'])) {
            $title = Loc::getMessage(
                'LITEBOX_Z1_STAFF_EDIT_SHOW_TITLE',
                array(
                    '#ID#' => $staff['ID'],
                    '#FIO#' => $staff['FIO']
                )
            );
        }

        $APPLICATION->SetTitle($title);

        if (self::isFormSubmitted()) {
            $savedStaffId = $this->processSave($staff);
            if ($savedStaffId > 0) {
                LocalRedirect($this->getRedirectUrl($savedStaffId));
            }

            $submittedStaff = $this->getSubmittedStaff();
            $staff = array_merge($staff, $submittedStaff);
        }

        $this->arResult =array(
            'FORM_ID' => self::FORM_ID,
			'GRID_ID' => 'LITEBOX_Z1_STAFF_LIST_GRID', // LiteboxZ1StaffListComponent::gridId,
            'IS_NEW' => empty($staff['ID']),
            'TITLE' => $title,
            'STAFF' => $staff,
            'BACK_URL' => $this->getRedirectUrl(),
            'ERRORS' => $this->errors,
        );

        $this->includeComponentTemplate();
    }

    private function processSave($initialStaff)
    {
        $submittedStaff = $this->getSubmittedStaff();

        $staff = array_merge($initialStaff, $submittedStaff);

        $this->errors = self::validate($staff);

        if (!$this->errors->isEmpty()) {
            return false;
        }

        if (!empty($staff['ID'])) {
            $result = Litebox\Z1\StaffTable::update($staff['ID'], $staff);
        } else {
			$result = Litebox\Z1\StaffTable::add($staff);
        }

        if (!$result->isSuccess()) {
            $this->errors->add($result->getErrors());
        }

        return $result->isSuccess() ? $result->getId() : false;
    }

    private function getSubmittedStaff()
    {
        $context = Context::getCurrent();
        $request = $context->getRequest();

        $submittedStaff = array(
            'FIO' => $request->get('FIO'),
            'DАTE_HIRING' => \Bitrix\Main\Type\DateTime::createFromUserTime($request->get('DАTE_HIRING')),
            'POSITION_ID' => $request->get('POSITION_ID'),
        );

        return $submittedStaff;
    }

    private static function validate($staff)
    {
        $errors = new ErrorCollection();

        if (empty($staff['FIO'])) {
            $errors->setError(new Error(Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_ERROR_EMPTY_FIO')));
        }

        if (empty($staff['POSITION_ID'])) {
            $errors->setError(new Error(Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_ERROR_EMPTY_POSITION_ID')));
        } else {
            $dbUser = Litebox\Z1\PositionsTable::getById($staff['POSITION_ID']);
            if ($dbUser->getSelectedRowsCount() <= 0) {
                $errors->setError(new Error(Loc::getMessage('LITEBOX_Z1_STAFF_EDIT_ERROR_UNKNOWN_POSITION_ID')));
            }
        }

        return $errors;
    }

    private static function isFormSubmitted()
    {
        $context = Context::getCurrent();
        $request = $context->getRequest();
        $saveAndView = $request->get('saveAndView');
        $saveAndAdd = $request->get('saveAndAdd');
        $apply = $request->get('apply');
        return !empty($saveAndView) || !empty($saveAndAdd) || !empty($apply);
    }

    private function getRedirectUrl($savedStaffId = null)
    {
        $context = Context::getCurrent();
        $request = $context->getRequest();

        if (!empty($savedStaffId) && $request->offsetExists('apply')) {
            return CComponentEngine::makePathFromTemplate(
                $this->arParams['URL_TEMPLATES']['EDIT'],
                array('STAFF_ID' => $savedStaffId)
            );
        } elseif (!empty($savedStaffId) && $request->offsetExists('saveAndAdd')) {
            return CComponentEngine::makePathFromTemplate(
                $this->arParams['URL_TEMPLATES']['EDIT'],
                array('STAFF_ID' => 0)
            );
        }

        $backUrl = $request->get('backurl');
        if (!empty($backUrl)) {
            return $backUrl;
        }

        if (!empty($savedStaffId) && $request->offsetExists('saveAndView')) {
            return CComponentEngine::makePathFromTemplate(
                $this->arParams['URL_TEMPLATES']['DETAIL'],
                array('STAFF_ID' => $savedStaffId)
            );
        } else {
            return $this->arParams['SEF_FOLDER'];
        }
    }
}