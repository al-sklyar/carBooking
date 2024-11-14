<?php

namespace SK\CarBooking\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class PositionComfortCategoryTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'sk_position_comfort_categories';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('POSITION_COMFORT_CATEGORY_ID'),
            ]),
            new Entity\IntegerField('POSITION_ID', [
                'required' => true,
                'title' => Loc::getMessage('POSITION_ID'),
            ]),
            new Entity\IntegerField('COMFORT_CATEGORY_ID', [
                'required' => true,
                'title' => Loc::getMessage('COMFORT_CATEGORY_ID'),
            ]),
            new Entity\ReferenceField('POSITION', PositionTable::class, [
                '=this.POSITION_ID' => 'ref.ID'
            ], [
                'title' => Loc::getMessage('POSITION_REFERENCE')
            ]),
            new Entity\ReferenceField('COMFORT_CATEGORY', ComfortCategoryTable::class, [
                '=this.COMFORT_CATEGORY_ID' => 'ref.ID'
            ], [
                'title' => Loc::getMessage('COMFORT_CATEGORY_REFERENCE')
            ]),
        ];
    }
}