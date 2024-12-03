<?php
namespace SK\CarBooking\Entity;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\SystemException;

abstract class AbstractHLBlockEntity
{
    protected static $entity;
    protected static $tableName;

    /**
     * Получение скомпилированной сущности HL-блока
     *
     * @return \Bitrix\Main\Entity\DataManager
     * @throws \Exception
     */
    public static function getEntity()
    {
        if (self::$entity === null) {
            if (empty(static::$tableName)) {
                throw new \Exception("Имя таблицы HL-блока не задано.");
            }

            $hlblock = HL\HighloadBlockTable::getList([
                'filter' => ['TABLE_NAME' => static::$tableName]
            ])->fetch();

            if ($hlblock) {
                self::$entity = HL\HighloadBlockTable::compileEntity($hlblock)->getDataClass();
            } else {
                throw new \Exception("Highload block " . static::$tableName . " не найден");
            }
        }

        return self::$entity;
    }

    /**
     * Добавление записи
     *
     * @param array $data
     * @return \Bitrix\Main\Entity\AddResult
     * @throws \Bitrix\Main\SystemException
     */
    public static function add(array $data)
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::add($data);
    }

    /**
     * Получение списка записей
     *
     * @param array $parameters
     * @return \Bitrix\Main\DB\Result
     * @throws \Bitrix\Main\SystemException
     */
    public static function getList(array $parameters = [])
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::getList($parameters);
    }

    /**
     * Получение записи по ID
     *
     * @param int $id
     * @return array|null
     * @throws \Bitrix\Main\SystemException
     */
    public static function getById($id)
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::getById($id)->fetch();
    }

    /**
     * Обновление записи
     *
     * @param int $id
     * @param array $data
     * @return \Bitrix\Main\Entity\UpdateResult
     * @throws \Bitrix\Main\SystemException
     */
    public static function update($id, array $data)
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::update($id, $data);
    }

    /**
     * Удаление записи
     *
     * @param int $id
     * @return \Bitrix\Main\Entity\DeleteResult
     * @throws \Bitrix\Main\SystemException
     */
    public static function delete($id)
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::delete($id);
    }
}