<?php

use Bitrix\Main\Loader;
use SK\CarBooking\Entity\CarTable;
use SK\CarBooking\Entity\EmployeeTable;
use SK\CarBooking\Entity\BookingTable;
use SK\CarBooking\Entity\PositionComfortCategoryTable;

header('Content-Type: application/json');

require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

Loader::includeModule("highloadblock");

// Явное подключение классов для совместимости
Loader::registerAutoLoadClasses(null, [
    'SK\CarBooking\Entity\ComfortCategoryTable' => '/local/modules/sk.carBooking/lib/Entity/ComfortCategoryTable.php',
    'SK\CarBooking\Entity\PositionTable' => '/local/modules/sk.carBooking/lib/Entity/PositionTable.php',
    'SK\CarBooking\Entity\PositionComfortCategoryTable' => '/local/modules/sk.carBooking/lib/Entity/PositionComfortCategoryTable.php',
    'SK\CarBooking\Entity\EmployeeTable' => '/local/modules/sk.carBooking/lib/Entity/EmployeeTable.php',
    'SK\CarBooking\Entity\CarTable' => '/local/modules/sk.carBooking/lib/Entity/CarTable.php',
    'SK\CarBooking\Entity\BookingTable' => '/local/modules/sk.carBooking/lib/Entity/BookingTable.php',
]);

function convertToDateTime($dateString)
{
    if (!empty($dateString)) {
        try {
            $date = strtotime($dateString);
            if ($date === false) {
                throw new \Exception("Ошибка преобразования даты: $dateString");
            }

            return \Bitrix\Main\Type\DateTime::createFromTimestamp($date);

        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
    return null;
}

$response = [];
$employeeName = $_GET['employee_name'] ?? null;
$startTime = $_GET['start_time'] ?? null;
$endTime = $_GET['end_time'] ?? null;

// Проверяем наличие всех параметров
if (!$employeeName || !$startTime || !$endTime) {
    $response['error'] = 'Необходимо указать все параметры: employee_name, start_time, end_time.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Получаем сотрудника
$employee = EmployeeTable::getList([
    'filter' => ['UF_NAME' => $employeeName],
    'select' => ['ID', 'UF_POSITION_ID', 'UF_NAME']
])->fetch();

if (!$employee) {
    $response['error'] = "Сотрудник с именем \"$employeeName\" не найден.";
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Проверяем доступные категории комфорта для должности
$accessibleCategories = PositionComfortCategoryTable::getList([
    'filter' => ['UF_POSITION_ID' => $employee['UF_POSITION_ID']],
    'select' => ['UF_COMFORT_CATEGORY_ID']
])->fetchAll();

$accessibleCategories = array_column($accessibleCategories, 'UF_COMFORT_CATEGORY_ID');

if (empty($accessibleCategories)) {
    $response['error'] = "Для должности сотрудника \"$employeeName\" не заданы доступные категории автомобилей.";
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Преобразование дат
$startDateTime = convertToDateTime($startTime);
$endDateTime = convertToDateTime($endTime);

if (!$startDateTime || !$endDateTime) {
    $response['error'] = "Неверный формат даты/времени: $startTime или $endTime";
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Получаем автомобили, соответствующие категории комфорта сотрудника
$cars = CarTable::getList([
    'filter' => ['UF_COMFORT_CATEGORY_ID' => $accessibleCategories],
    'select' => ['ID', 'UF_MODEL', 'UF_DRIVER', 'UF_AVAILABILITY', 'UF_COMFORT_CATEGORY_ID']
])->fetchAll();

if (!$cars) {
    $response['error'] = "Для сотрудника \"$employeeName\" нет доступных автомобилей.";
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Ищем занятые автомобили в указанный период
$bookedCars = BookingTable::getList([
    'filter' => [
        [
            'LOGIC' => 'OR',
            [
                '<=UF_START_TIME' => $endDateTime,
                '>=UF_START_TIME' => $startDateTime,
            ],
            [
                '<=UF_END_TIME' => $endDateTime,
                '>=UF_END_TIME' => $startDateTime,
            ]
        ]
    ],
    'select' => ['UF_CAR_ID']
])->fetchAll();

$bookedCarIds = array_column($bookedCars, 'UF_CAR_ID');

// Фильтруем свободные машины
$availableCars = array_filter($cars, function ($car) use ($bookedCarIds) {
    return $car['UF_AVAILABILITY'] === "1" && !in_array($car['ID'], $bookedCarIds);
});

// Формируем ответ
if (!$availableCars) {
    $response['error'] = "На указанное время нет свободных автомобилей для сотрудника \"$employeeName\".";
} else {
    $response['success'] = true;
    $response['cars'] = array_map(function ($car) {
        return [
            'model' => $car['UF_MODEL'],
            'driver' => $car['UF_DRIVER'],
            'comfort_category_id' => $car['UF_COMFORT_CATEGORY_ID'],
        ];
    }, $availableCars);
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);