<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Application;
use SK\CarBooking\Entity\ComfortCategoryTable;
use SK\CarBooking\Entity\PositionTable;
use SK\CarBooking\Entity\PositionComfortCategoryTable;
use SK\CarBooking\Entity\EmployeeTable;
use SK\CarBooking\Entity\CarTable;

Loc::loadMessages(__FILE__);

class sk_carBooking extends CModule
{
    private $entities = [
        ComfortCategoryTable::class,
        PositionTable::class,
        PositionComfortCategoryTable::class,
        EmployeeTable::class,
        CarTable::class,
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

        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = "Y";
    }

    // Определение пути
    public function GetPath($notDocumentRoot = false)
    {
        return $notDocumentRoot
            ? str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__))
            : dirname(__DIR__);
    }

    // Проверка поддержки D7
    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    // Создание таблиц
    function InstallDB($withTestData = false)
    {
        Loader::includeModule($this->MODULE_ID);

        foreach ($this->entities as $entity) {
            if (class_exists($entity)) {
                $entityInstance = Base::getInstance($entity);
                $connection = $entityInstance->getConnection();
                $tableName = $entityInstance->getDBTableName();

                if (!$connection->isTableExists($tableName)) {
                    $entityInstance->createDbTable();
                }
            }
        }

        // Опциональное заполнение тестовыми данными
        if ($withTestData) {
            $this->populateTestData();
        }
    }

    // Удаление таблиц
    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        foreach ($this->entities as $entity) {
            if (class_exists($entity)) {
                $entityInstance = Base::getInstance($entity);
                $connection = $entityInstance->getConnection();
                $tableName = $entityInstance->getDBTableName();

                if ($connection->isTableExists($tableName)) {
                    $connection->queryExecute('DROP TABLE IF EXISTS ' . $tableName);
                }
            }
        }

        Option::delete($this->MODULE_ID);
    }

    // Заполнение тестовыми данными
    private function populateTestData()
    {
        $testData = include __DIR__ . '/testData.php';

        foreach ($testData as $entityClass => $records) {
            if (class_exists($entityClass)) {
                foreach ($records as $record) {
                    $entityClass::add($record);
                }
            }
        }
    }

    public function doInstall()
    {
        global $APPLICATION;

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["install_test_data"])) {
        $withTestData = $_POST["install_test_data"] === 'Y';
    } else {
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("CAR_BOOKING_INSTALL_TITLE"),
            $this->GetPath() . "/install/step1.php"
        );
        return;
    }

        if ($this->isVersionD7()) {
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
            $this->InstallDB($withTestData);
        } else {
            $APPLICATION->ThrowException(Loc::getMessage("CAR_BOOKING_INSTALL_ERROR_VERSION"));
        }
    }

    public function doUninstall()
    {
        global $APPLICATION;

        // Удаляем таблицы
        $this->UnInstallDB();

        // Удаляем модуль
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}