<?php

namespace SK\CarBooking\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CarTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'sk_cars';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('CAR_ID'),
            ]),
            new Entity\StringField('MODEL', [
                'required' => true,
                'title' => Loc::getMessage('CAR_MODEL'),
            ]),
            new Entity\IntegerField('COMFORT_CATEGORY_ID', [
                'required' => true,
                'title' => Loc::getMessage('CAR_COMFORT_CATEGORY_ID'),
            ]),
            new Entity\StringField('DRIVER', [
                'required' => true,
                'title' => Loc::getMessage('CAR_DRIVER'),
            ]),
            new Entity\BooleanField('AVAILABILITY', [
                'required' => true,
                'values' => ['N', 'Y'],
                'title' => Loc::getMessage('CAR_AVAILABILITY'),
            ]),
            new Entity\ReferenceField('COMFORT_CATEGORY', ComfortCategoryTable::class, [
                '=this.COMFORT_CATEGORY_ID' => 'ref.ID'
            ], [
                'title' => Loc::getMessage('CAR_COMFORT_CATEGORY_REFERENCE')
            ]),
        ];
    }
}