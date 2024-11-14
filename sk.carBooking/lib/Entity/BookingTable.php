<?php

namespace SK\CarBooking\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BookingTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'sk_booking';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('BOOKING_ID'),
            ]),
            new Entity\IntegerField('EMPLOYEE_ID', [
                'required' => true,
                'title' => Loc::getMessage('BOOKING_EMPLOYEE_ID'),
            ]),
            new Entity\IntegerField('CAR_ID', [
                'required' => true,
                'title' => Loc::getMessage('BOOKING_CAR_ID'),
            ]),
            new Entity\DatetimeField('START_TIME', [
                'required' => true,
                'title' => Loc::getMessage('BOOKING_START_TIME'),
            ]),
            new Entity\DatetimeField('END_TIME', [
                'required' => true,
                'title' => Loc::getMessage('BOOKING_END_TIME'),
            ]),
            new Entity\ReferenceField('EMPLOYEE', EmployeeTable::class, [
                '=this.EMPLOYEE_ID' => 'ref.ID'
            ], [
                'title' => Loc::getMessage('BOOKING_EMPLOYEE_REFERENCE')
            ]),
            new Entity\ReferenceField('CAR', CarTable::class, [
                '=this.CAR_ID' => 'ref.ID'
            ], [
                'title' => Loc::getMessage('BOOKING_CAR_REFERENCE')
            ]),
        ];
    }
}