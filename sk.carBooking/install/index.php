<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class sk_carBooking extends CModule
{
    private $hlBlocks = [
        'ComfortCategory' => 'sk_comfort_categories',
        'Position' => 'sk_positions',
        'PositionComfortCategory' => 'sk_position_comfort_categories',
        'Employee' => 'sk_employees',
        'Car' => 'sk_cars',
        'Booking' => 'sk_booking',
    ];

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_ID = 'sk.carBooking';
        $this->MODULE_NAME = Loc::getMessage("CAR_BOOKING_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("CAR_BOOKING_MODULE_DESCRIPTION");

        $this->PARTNER_NAME = Loc::getMessage("CAR_BOOKING_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("CAR_BOOKING_PARTNER_URI");
    }

    public function doInstall()
    {
        global $APPLICATION;

        // Проверяем, был ли выполнен step.php
        if (!isset($_REQUEST["step"])) {
            // Переход на step.php
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("CAR_BOOKING_INSTALL_TITLE"),
                $this->GetPath() . "/install/step.php"
            );
            return;
        }

        $withTestData = isset($_POST["install_test_data"]) && $_POST["install_test_data"] === 'Y';

        if ($this->isVersionD7()) {
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
            Loader::includeModule("highloadblock");
            $this->createHLBlocks($withTestData);
        } else {
            $APPLICATION->ThrowException(Loc::getMessage("CAR_BOOKING_INSTALL_ERROR_VERSION"));
        }
    }


    public function doUninstall()
    {
        $this->deleteHLBlocks();
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    protected function createHLBlocks($withTestData = false)
    {
        foreach ($this->hlBlocks as $name => $table) {
            $result = HL\HighloadBlockTable::add([
                'NAME' => $name,
                'TABLE_NAME' => $table,
            ]);

            if ($result->isSuccess()) {
                $hlblockId = $result->getId();
                $this->addHLBlockFields($name, $hlblockId);
            }
        }

        if ($withTestData) {
            $this->populateTestData();
        }
    }

    protected function deleteHLBlocks()
    {
        foreach ($this->hlBlocks as $name => $table) {
            $hlblock = HL\HighloadBlockTable::getList([
                'filter' => ['TABLE_NAME' => $table]
            ])->fetch();

            if ($hlblock) {
                HL\HighloadBlockTable::delete($hlblock['ID']);
            }
        }
    }

    protected function addHLBlockFields($name, $hlblockId)
    {
        $userTypeEntity = new \CUserTypeEntity();

        $fields = [];
        switch ($name) {
            case 'ComfortCategory':
                $fields = [
                    ['FIELD_NAME' => 'UF_NAME', 'USER_TYPE_ID' => 'string', 'MANDATORY' => 'Y']
                ];
                break;
            case 'Position':
                $fields = [
                    ['FIELD_NAME' => 'UF_POSITION_NAME', 'USER_TYPE_ID' => 'string', 'MANDATORY' => 'Y']
                ];
                break;
            case 'PositionComfortCategory':
                $fields = [
                    ['FIELD_NAME' => 'UF_POSITION_ID', 'USER_TYPE_ID' => 'integer', 'MANDATORY' => 'Y'],
                    ['FIELD_NAME' => 'UF_COMFORT_CATEGORY_ID', 'USER_TYPE_ID' => 'integer', 'MANDATORY' => 'Y']
                ];
                break;
            case 'Employee':
                $fields = [
                    ['FIELD_NAME' => 'UF_POSITION_ID', 'USER_TYPE_ID' => 'integer', 'MANDATORY' => 'Y'],
                    ['FIELD_NAME' => 'UF_ASSIGNED_CAR_ID', 'USER_TYPE_ID' => 'integer', 'MANDATORY' => 'N']
                ];
                break;
            case 'Car':
                $fields = [
                    ['FIELD_NAME' => 'UF_MODEL', 'USER_TYPE_ID' => 'string', 'MANDATORY' => 'Y'],
                    ['FIELD_NAME' => 'UF_COMFORT_CATEGORY_ID', 'USER_TYPE_ID' => 'integer', 'MANDATORY' => 'Y'],
                    ['FIELD_NAME' => 'UF_DRIVER', 'USER_TYPE_ID' => 'string', 'MANDATORY' => 'Y'],
                    ['FIELD_NAME' => 'UF_AVAILABILITY', 'USER_TYPE_ID' => 'boolean', 'MANDATORY' => 'Y']
                ];
                break;
            case 'Booking':
                $fields = [
                    ['FIELD_NAME' => 'UF_EMPLOYEE_ID', 'USER_TYPE_ID' => 'integer', 'MANDATORY' => 'Y'],
                    ['FIELD_NAME' => 'UF_CAR_ID', 'USER_TYPE_ID' => 'integer', 'MANDATORY' => 'Y'],
                    ['FIELD_NAME' => 'UF_START_TIME', 'USER_TYPE_ID' => 'datetime', 'MANDATORY' => 'Y'],
                    ['FIELD_NAME' => 'UF_END_TIME', 'USER_TYPE_ID' => 'datetime', 'MANDATORY' => 'Y']
                ];
                break;
        }

        foreach ($fields as $field) {
            $field['ENTITY_ID'] = 'HLBLOCK_' . $hlblockId;
            $userTypeEntity->Add($field);
        }
    }

    protected function populateTestData()
    {
        $testData = include __DIR__ . '/testData.php';

        //модуль использует явное подключение классов для совместимости
        Loader::registerAutoLoadClasses(null, [
            'SK\CarBooking\Entity\ComfortCategoryTable' => '/local/modules/sk.carBooking/lib/Entity/ComfortCategoryTable.php',
            'SK\CarBooking\Entity\PositionTable' => '/local/modules/sk.carBooking/lib/Entity/PositionTable.php',
            'SK\CarBooking\Entity\PositionComfortCategoryTable' => '/local/modules/sk.carBooking/lib/Entity/PositionComfortCategoryTable.php',
            'SK\CarBooking\Entity\EmployeeTable' => '/local/modules/sk.carBooking/lib/Entity/EmployeeTable.php',
            'SK\CarBooking\Entity\CarTable' => '/local/modules/sk.carBooking/lib/Entity/CarTable.php',
        ]);

        foreach ($testData as $entityClass => $records) {
            \Bitrix\Main\Diag\Debug::dumpToFile(class_exists($entityClass),date("Y-m-d H:i:s") . ' class_exists', '/logs/log1.txt');
            if (class_exists($entityClass)) {
                foreach ($records as $record) {
                $result = $entityClass::add($record);
                \Bitrix\Main\Diag\Debug::dumpToFile($result->isSuccess(), date("Y-m-d H:i:s") . ' add result success', '/logs/log1.txt');
                \Bitrix\Main\Diag\Debug::dumpToFile($result->getErrorMessages(), date("Y-m-d H:i:s") . ' add result errors', '/logs/log1.txt');
                }
            }
        }
    }

    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    public function GetPath($notDocumentRoot = false)
    {
        return $notDocumentRoot
            ? str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__))
            : dirname(__DIR__);
    }
}