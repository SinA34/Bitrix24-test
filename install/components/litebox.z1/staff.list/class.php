<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\UserField\Uri;
use Litebox\Z1\StaffTable;
use Bitrix\Main\Web\Json;

class LiteboxZ1StaffListComponent extends CBitrixComponent
{
    const GRID_ID = 'LITEBOX_Z1_STAFF_LIST_GRID';
    protected $gridId = self::GRID_ID;
    protected $filterId = "LITEBOX_Z1_STAFF_LIST_FILTER";
    protected $grid_options;
    protected $dateStart;
    protected $dateEnd;

    public function __construct($component)
    {
        parent::__construct($component);
        \Bitrix\Main\Loader::includeModule('litebox.z1');
    }

    public function executeComponent()
{
    parent::executeComponent();

    global $USER, $APPLICATION;

    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    $requestUri = new Uri('https://'.$request->getHttpHost().$request->getRequestedPage());
    $requestUri->addParams(['sessid' => bitrix_sessid()]);


        try {
// Настройки компонента            
            
            $grid_options = new Bitrix\Main\Grid\Options($this->gridId);
            $sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
            $nav_params = $grid_options->GetNavParams();

            $nav = new Bitrix\Main\UI\PageNavigation('litebox-z1-staff-list-nav-grid');
            $nav->allowAllRecords(true)
                ->setPageSize($nav_params['nPageSize'])
                ->initFromUri();

            $filterOption = new Bitrix\Main\UI\Filter\Options($this->filterId);
            $filterData = $filterOption->getFilter([]);
            $filter = [];

			$filter['FIO'] = "%" . $filterData['FIND'] . "%";

            foreach ($filterData as $k => $v) {
                if ($k === 'POSITION_NAME' && strlen($v) > 0) {//фильтр должность
                    $filter[$k] = $v;
                }
                if ($k === 'DАTE_HIRING_from' && strlen($v) > 0) {
                    $filter['>=DАTE_HIRING'] = $v;
					$dateStart=$v;
                    }
                if ($k === 'DАTE_HIRING_to' && strlen($v) > 0) {
                    $filter['<=DАTE_HIRING'] = $v;
					$dateEnd=$v;
                }
            }
            
			$staff = Litebox\Z1\StaffTable::getList([
			'select' => ['ID',
                            'FIO', 
                            'DАTE_HIRING', 
                            'POSITION_NAME'=>'POSITION.NAME', 
                            'POSITION_ID'],
			'filter' => $filter,
			'count_total' => true,
			'order' => $sort['sort'],
			'offset' => $offset = $nav->getOffset(),
			'limit' => $limit = $nav->getLimit()
		]);
            
            $ui_filter = [
                ['id' => 'FIO', 'name' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_FIO_FILTER'), 'default' => true],
                ['id' => 'POSITION_NAME', 'name' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_POSITION_FILTER'), 'default' => true],
                ['id' => 'DАTE_HIRING', 'name' =>Loc::getMessage('LITEBOX_Z1_STAFF_LIST_DАTE_HIRING_FILTER'), 'type' => 'date', 'default' => true,
                    "exclude" => array(
                        \Bitrix\Main\UI\Filter\DateType::YESTERDAY,
                        \Bitrix\Main\UI\Filter\DateType::CURRENT_DAY,
                        \Bitrix\Main\UI\Filter\DateType::TOMORROW,
                        \Bitrix\Main\UI\Filter\DateType::CURRENT_QUARTER,
                        \Bitrix\Main\UI\Filter\DateType::EXACT,
                        \Bitrix\Main\UI\Filter\DateType::LAST_WEEK,
                        \Bitrix\Main\UI\Filter\DateType::LAST_MONTH,
                        \Bitrix\Main\UI\Filter\DateType::LAST_7_DAYS,
                        \Bitrix\Main\UI\Filter\DateType::LAST_30_DAYS,
                        \Bitrix\Main\UI\Filter\DateType::LAST_60_DAYS,
                        \Bitrix\Main\UI\Filter\DateType::LAST_90_DAYS,
                        \Bitrix\Main\UI\Filter\DateType::PREV_DAYS,
                        \Bitrix\Main\UI\Filter\DateType::NEXT_DAYS,
                        \Bitrix\Main\UI\Filter\DateType::QUARTER,
                        \Bitrix\Main\UI\Filter\DateType::YEAR,
                        \Bitrix\Main\UI\Filter\DateType::NEXT_WEEK,
                        \Bitrix\Main\UI\Filter\DateType::NEXT_MONTH
                    )
                ],
            ];

            $rows = array();
            while ($saStaff = $staff->fetch()) {
                $viewUrl = CComponentEngine::makePathFromTemplate(
                    $arParams['URL_TEMPLATES']['DETAIL'], array('STAFF_ID' => $saStaff['ID']));
                $editUrl = CComponentEngine::makePathFromTemplate(
                    $arParams['URL_TEMPLATES']['EDIT'], array('STAFF_ID' => $ssaStaff['ID']));

                $deleteUrlParams = http_build_query(array(
                     'action_button_' . $this->$gridId => 'delete',
                     'ID' => array($saStaff['ID']),
                     'sessid' => bitrix_sessid()
                      ));
                $deleteUrl = $arParams['SEF_FOLDER'] . '?' . $deleteUrlParams;
                
                $rows[] = array(
                    'id' => $saStaff['ID'],
                    'actions' => [
                        ['TITLE' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_ACTION_VIEW_TITLE'),
                        'TEXT' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_ACTION_VIEW_TEXT'),
                        'ONCLICK' => 'document.location.href="/litebox/staff/'.$saStaff['ID'].'/"', 'DEFAULT' => true],
                        ['TITLE' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_ACTION_EDIT_TITLE'),
                        'TEXT' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_ACTION_EDIT_TEXT'),
                        'ONCLICK' => 'document.location.href="/litebox/staff/'.$saStaff['ID'].'/edit/"']
                        ],
                    'data' => $saStaff,
                    'columns' => array(
                        'ID' => $saStaff['ID'],                      
                        'FIO' => $saStaff['FIO'],
                        'POSITION_NAME' => $saStaff['POSITION_NAME'],
                        'DАTE_HIRING' => $saStaff['DАTE_HIRING'])
                );
            }

            $nav->setRecordCount($staff->getCount());

            $this->arResult['DATA'] = $rows;
            $this->arResult['GRID_ID'] = $this->gridId;
            $this->arResult['FILTER_ID'] = $this->filterId;
            $this->arResult['HEADERS'] = $this->getHeader();
            $this->arResult['UI_FILTER'] = $ui_filter;
            $this->arResult['NAV_OBJECT'] = $nav;
            $this->arResult['NAV_GET_COUNT'] = $staff->getCount();
            $this->arResult['SERVICE_URL'] = $requestUri->getUri();

            $this->checkAction();
            if ($request->isAjaxRequest() && !$request->isPost()) {
                $APPLICATION->RestartBuffer();
                $APPLICATION->ShowHead();
                $this->includeComponentTemplate();
                require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
                exit;
            }

            $this->includeComponentTemplate();
        }
        catch (\Throwable $exception)
        {
            print_r($exception->getMessage().$exception->getTraceAsString());
        }
}

    protected function checkAction()
    {


        global $APPLICATION;

        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $action = $request->get('ACTION');

        if (!check_bitrix_sessid()) {
            return;
        }

        if (!$request->isAjaxRequest()) {
            return;
        }

        switch ($action) {
            case 'GET_ROW_COUNT':
                $APPLICATION->RestartBuffer();
                header('Content-type: application/json');

                $count = $this->recordCount;
                echo \Bitrix\Main\Web\Json::encode([
                    'DATA' => [
                        'TEXT' => Loc::getMessage('CRM_GRID_ROW_COUNT', ['#COUNT#' => $count])
                    ]
                ]);
                die();
                break;
        }

    }

    public function getHeader()
    {
        return array(
            array('id' => 'ID', 'name' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_ID_HEADER'), 'sort' => 'ID','default' => false),
            array('id' => 'FIO', 'name' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_FIO_HEADER'), 'sort' => 'FIO', 'default' => true),
            array('id' => 'POSITION_NAME', 'name' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_POSITION_HEADER'), 'sort' => 'POSITION_NAME', 'default' => true),
            array('id' => 'DАTE_HIRING', 'name' => Loc::getMessage('LITEBOX_Z1_STAFF_LIST_DАTE_HIRING_HEADER'),'sort' => 'DАTE_HIRING', 'default' => true)
        );
    }
}