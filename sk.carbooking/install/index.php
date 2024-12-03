<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class sk_carbooking extends CModule
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
        $this->MODULE_ID = 'sk.carbooking';
        $this->MODULE_NAME = Loc::getMessage("CAR_BOOKING_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("CAR_BOOKING_MODULE_DESCRIPTION");

        $this->PARTNER_NAME = Loc::getMessage("CAR_BOOKING_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("CAR_BOOKING_PARTNER_URI");
    }

    public function doInstall()
    {
        global $APPLICATION;

        if (!isset($_REQUEST["step"])) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("CAR_BOOKING_INSTALL_TITLE"),
                $this->GetPath() . "/install/step.php"
            );
            return;
        }

        $withTestData = isset($_POST["install_test_data"]) && $_POST["install_test_data"] === 'Y';
        if ($this->isVersionD7()) {
            ModuleManager::registerModule($this->MODULE_ID);

            if (!Loader::includeModule("highloadblock")) {
                $APPLICATION->ThrowException(Loc::getMessage("CAR_BOOKING_INSTALL_ERROR_HLBLOCK"));
                return;
            }

            if (!Loader::includeModule($this->MODULE_ID)) {
                $APPLICATION->ThrowException(Loc::getMessage("CAR_BOOKING_INSTALL_ERROR_MODULE", ["#MODULE_ID#" => $this->MODULE_ID]));
                return;
            }

            $this->createHLBlocks($withTestData);
            $this->InstallFiles();
        } else {
            $APPLICATION->ThrowException(Loc::getMessage("CAR_BOOKING_INSTALL_ERROR_VERSION"));
        }
    }

    public function doUninstall()
    {
        global $APPLICATION;
        if (!Loader::includeModule("highloadblock")) {
            $APPLICATION->ThrowException(Loc::getMessage("CAR_BOOKING_UNINSTALL_ERROR_HLBLOCK"));
            return;
        }

        $this->deleteHLBlocks();
        $this->UnInstallFiles();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallFiles($arParams = [])
    {
        $sourceDir = $this->GetPath() . '/tools';
        $targetDir = Application::getDocumentRoot() . '/local/tools';

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($sourceDir)) {
            \Bitrix\Main\IO\Directory::createDirectory($targetDir);
            CopyDirFiles($sourceDir, $targetDir, true, true);
        }

        return true;
    }

    public function UnInstallFiles()
    {
        $targetDir = Application::getDocumentRoot() . '/local/tools/freeCars.php';

        if (\Bitrix\Main\IO\File::isFileExists($targetDir)) {
            \Bitrix\Main\IO\File::deleteFile($targetDir);
        }

        return true;
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
                    ['FIELD_NAME' => 'UF_NAME', 'USER_TYPE_ID' => 'string', 'MANDATORY' => 'Y'],
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

        foreach ($testData as $entityClass => $records) {
            if (class_exists($entityClass)) {
                foreach ($records as $record) {
                    // Преобразуем формат даты/времени для hl-блока Booking
                    if (isset($record['UF_START_TIME'])) {
                        $record['UF_START_TIME'] = new \Bitrix\Main\Type\DateTime($record['UF_START_TIME'], 'Y-m-d H:i:s');
                    }
                    if (isset($record['UF_END_TIME'])) {
                        $record['UF_END_TIME'] = new \Bitrix\Main\Type\DateTime($record['UF_END_TIME'], 'Y-m-d H:i:s');
                    }

                    $result = $entityClass::add($record);
                    if (!$result->isSuccess()) {
                        throw new \Exception(
                            Loc::getMessage("CAR_BOOKING_ERROR_ADD_RECORD") . implode(", ", $result->getErrorMessages())
                        );
                    }
                }
            } else {
                throw new \Exception(
                    Loc::getMessage("CAR_BOOKING_ERROR_CLASS_NOT_FOUND", ["#CLASS_NAME#" => $entityClass])
                );
            }
        }
    }


    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
    }

    public function GetPath($notDocumentRoot = false)
    {
        return $notDocumentRoot
            ? str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__))
            : dirname(__DIR__);
    }
}