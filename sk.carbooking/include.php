<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('sk.carbooking', [
    'SK\CarBooking\Entity\AbstractHLBlockEntity' => 'lib/Entity/AbstractHLBlockEntity.php',
    'SK\CarBooking\Entity\ComfortCategoryTable' => 'lib/Entity/ComfortCategoryTable.php',
    'SK\CarBooking\Entity\PositionTable' => 'lib/Entity/PositionTable.php',
    'SK\CarBooking\Entity\PositionComfortCategoryTable' => 'lib/Entity/PositionComfortCategoryTable.php',
    'SK\CarBooking\Entity\EmployeeTable' => 'lib/Entity/EmployeeTable.php',
    'SK\CarBooking\Entity\CarTable' => 'lib/Entity/CarTable.php',
    'SK\CarBooking\Entity\BookingTable' => 'lib/Entity/BookingTable.php',
]);