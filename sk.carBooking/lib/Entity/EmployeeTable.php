<?php

namespace SK\CarBooking\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class EmployeeTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'sk_employees';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('EMPLOYEE_ID'),
            ]),
            new Entity\IntegerField('POSITION_ID', [
                'required' => true,
                'title' => Loc::getMessage('EMPLOYEE_POSITION_ID'),
            ]),
            new Entity\IntegerField('ASSIGNED_CAR_ID', [
                'required' => false,
                'title' => Loc::getMessage('EMPLOYEE_ASSIGNED_CAR_ID'),
            ]),
            new Entity\ReferenceField('POSITION', PositionTable::class, [
                '=this.POSITION_ID' => 'ref.ID'
            ], [
                'title' => Loc::getMessage('EMPLOYEE_POSITION_REFERENCE')
            ]),
            new Entity\ReferenceField('ASSIGNED_CAR', CarTable::class, [
                '=this.ASSIGNED_CAR_ID' => 'ref.ID'
            ], [
                'title' => Loc::getMessage('EMPLOYEE_ASSIGNED_CAR_REFERENCE')
            ]),
        ];
    }
}