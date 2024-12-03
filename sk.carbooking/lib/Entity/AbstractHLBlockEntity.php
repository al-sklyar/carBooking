<?php

namespace SK\CarBooking\Entity;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\SystemException;

abstract class AbstractHLBlockEntity
{
    protected static array $entities = [];
    protected static string $tableName;

    /**
     * Получение скомпилированной сущности HL-блока
     *
     * @return string
     * @throws \Exception
     */
    public static function getEntity(): string
    {
        $class = static::class;
        if (!isset(self::$entities[$class])) {
            if (empty(static::$tableName)) {
                throw new \Exception("Имя таблицы HL-блока не задано.");
            }

            $hlblock = HL\HighloadBlockTable::getList([
                'filter' => ['TABLE_NAME' => static::$tableName]
            ])->fetch();

            if ($hlblock) {
                self::$entities[$class] = HL\HighloadBlockTable::compileEntity($hlblock)->getDataClass();
            } else {
                throw new \Exception("Highload block " . static::$tableName . " не найден");
            }
        }

        return self::$entities[$class];
    }

    /**
     * Добавление записи
     *
     * @param array $data
     *
     * @return \Bitrix\Main\Entity\AddResult
     * @throws \Bitrix\Main\SystemException
     */
    public static function add(array $data): \Bitrix\Main\Entity\AddResult
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::add($data);
    }

    /**
     * Получение списка записей
     *
     * @param array $parameters
     *
     * @return \Bitrix\Main\DB\Result
     * @throws \Bitrix\Main\SystemException
     */
    public static function getList(array $parameters = []): \Bitrix\Main\DB\Result
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::getList($parameters);
    }

    /**
     * Получение записи по ID
     *
     * @param int $id
     *
     * @return array|null
     * @throws \Bitrix\Main\SystemException
     */
    public static function getById($id): ?array
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::getById($id)->fetch();
    }

    /**
     * Обновление записи
     *
     * @param int   $id
     * @param array $data
     *
     * @return \Bitrix\Main\Entity\UpdateResult
     * @throws \Bitrix\Main\SystemException
     */
    public static function update($id, array $data): \Bitrix\Main\Entity\UpdateResult
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::update($id, $data);
    }

    /**
     * Удаление записи
     *
     * @param int $id
     *
     * @return \Bitrix\Main\Entity\DeleteResult
     * @throws \Bitrix\Main\SystemException
     */
    public static function delete($id): \Bitrix\Main\Entity\DeleteResult
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::delete($id);
    }

    /**
     * Возвращает имя таблицы HL-блока.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return static::$tableName;
    }
}