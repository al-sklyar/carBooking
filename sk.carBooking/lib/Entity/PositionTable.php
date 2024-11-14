<?php

namespace SK\CarBooking\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class PositionTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'sk_positions';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('POSITION_ID'),
            ]),
            new Entity\StringField('POSITION_NAME', [
                'required' => true,
                'title' => Loc::getMessage('POSITION_NAME'),
            ]),
        ];
    }
}