<?php

namespace SK\CarBooking\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ComfortCategoryTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'sk_comfort_categories';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('COMFORT_CATEGORY_ID'),
            ]),
            new Entity\StringField('NAME', [
                'required' => true,
                'title' => Loc::getMessage('COMFORT_CATEGORY_NAME'),
            ]),
        ];
    }
}