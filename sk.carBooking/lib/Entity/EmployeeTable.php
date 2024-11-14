<?php

namespace SK\CarBooking\Entity;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class EmployeeTable
{
    protected static $entity;

    /**
     * @return \Bitrix\Main\Entity\DataManager
     * @throws \Bitrix\Main\SystemException
     */
    public static function getEntity()
    {
        if (self::$entity === null) {
            $hlblock = HL\HighloadBlockTable::getList([
                'filter' => ['TABLE_NAME' => 'sk_employees']
            ])->fetch();

            if ($hlblock) {
                self::$entity = HL\HighloadBlockTable::compileEntity($hlblock)->getDataClass();
            } else {
                throw new \Exception('Highload block sk_employees не найден');
            }
        }

        return self::$entity;
    }

    /**
     * @param array $data
     *
     * @return \Bitrix\Main\Entity\AddResult
     * @throws \Bitrix\Main\SystemException
     */
    public static function add(array $data)
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::add($data);
    }

    /**
     * @param array $parameters
     *
     * @return \Bitrix\Main\DB\Result
     * @throws \Bitrix\Main\SystemException
     */
    public static function getList(array $parameters = [])
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::getList($parameters);
    }

    /**
     * @param int $id
     *
     * @return array|null
     * @throws \Bitrix\Main\SystemException
     */
    public static function getById($id)
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::getById($id)->fetch();
    }

    /**
     * @param int   $id
     * @param array $data
     *
     * @return \Bitrix\Main\Entity\UpdateResult
     * @throws \Bitrix\Main\SystemException
     */
    public static function update($id, array $data)
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::update($id, $data);
    }

    /**
     * @param int $id
     *
     * @return \Bitrix\Main\Entity\DeleteResult
     * @throws \Bitrix\Main\SystemException
     */
    public static function delete($id)
    {
        $entityDataClass = self::getEntity();
        return $entityDataClass::delete($id);
    }
}