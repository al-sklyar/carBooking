<?php
$testData = [

    // 'sk_comfort_categories'
    'SK\CarBooking\Entity\ComfortCategoryTable' => [
        ['UF_ID' => 1, 'UF_NAME' => 'Бизнес'],
        ['UF_ID' => 2, 'UF_NAME' => 'Комфорт'],
        ['UF_ID' => 3, 'UF_NAME' => 'Стандарт'],
    ],

    // 'sk_positions'
    'SK\CarBooking\Entity\PositionTable' => [
        ['UF_ID' => 1, 'UF_POSITION_NAME' => 'Директор'],
        ['UF_ID' => 2, 'UF_POSITION_NAME' => 'Заместитель директора'],
        ['UF_ID' => 3, 'UF_POSITION_NAME' => 'Менеджер'],
        ['UF_ID' => 4, 'UF_POSITION_NAME' => 'Старший специалист'],
        ['UF_ID' => 5, 'UF_POSITION_NAME' => 'Младший специалист'],
        ['UF_ID' => 6, 'UF_POSITION_NAME' => 'Консультант'],
    ],

    // 'sk_position_comfort_categories'
    'SK\CarBooking\Entity\PositionComfortCategoryTable' => [
        ['UF_POSITION_ID' => 1, 'UF_COMFORT_CATEGORY_ID' => 1], // Директор — 1 категория (Бизнес)
        ['UF_POSITION_ID' => 2, 'UF_COMFORT_CATEGORY_ID' => 1], // Заместитель директора — 1 категория (Бизнес)
        ['UF_POSITION_ID' => 3, 'UF_COMFORT_CATEGORY_ID' => 2], // Менеджер — 2 категория (Комфорт)
        ['UF_POSITION_ID' => 4, 'UF_COMFORT_CATEGORY_ID' => 2], // Старший специалист — 2 категория (Комфорт)
        ['UF_POSITION_ID' => 5, 'UF_COMFORT_CATEGORY_ID' => 3], // Младший специалист — 3 категория (Стандарт)
        ['UF_POSITION_ID' => 6, 'UF_COMFORT_CATEGORY_ID' => 3], // Консультант — 3 категория (Стандарт)
    ],

    // 'sk_employees'
    'SK\CarBooking\Entity\EmployeeTable' => [
        ['UF_NAME' => 'Иван Иванов', 'UF_POSITION_ID' => 1], // Директор
        ['UF_NAME' => 'Петр Петров', 'UF_POSITION_ID' => 2], // Заместитель директора
        ['UF_NAME' => 'Мария Сидорова', 'UF_POSITION_ID' => 3], // Менеджер
        ['UF_NAME' => 'Ольга Смирнова', 'UF_POSITION_ID' => 3], // Менеджер
        ['UF_NAME' => 'Анна Васильева', 'UF_POSITION_ID' => 4], // Старший специалист
        ['UF_NAME' => 'Алексей Михайлов', 'UF_POSITION_ID' => 4], // Старший специалист
        ['UF_NAME' => 'Владимир Попов', 'UF_POSITION_ID' => 5], // Младший специалист
        ['UF_NAME' => 'Светлана Козлова', 'UF_POSITION_ID' => 5], // Младший специалист
        ['UF_NAME' => 'Дмитрий Орлов', 'UF_POSITION_ID' => 6], // Консультант
        ['UF_NAME' => 'Екатерина Павлова', 'UF_POSITION_ID' => 6], // Консультант
    ],

    // 'sk_cars'
    'SK\CarBooking\Entity\CarTable' => [
        ['UF_MODEL' => 'BMW 7 Series', 'UF_COMFORT_CATEGORY_ID' => 1, 'UF_DRIVER' => 'Сергей Сергеев', 'UF_AVAILABILITY' => true],
        ['UF_MODEL' => 'Mercedes S-Class', 'UF_COMFORT_CATEGORY_ID' => 1, 'UF_DRIVER' => 'Александр Куликов', 'UF_AVAILABILITY' => true],
        ['UF_MODEL' => 'Toyota Camry', 'UF_COMFORT_CATEGORY_ID' => 2, 'UF_DRIVER' => 'Василий Федоров', 'UF_AVAILABILITY' => true],
        ['UF_MODEL' => 'Honda Accord', 'UF_COMFORT_CATEGORY_ID' => 2, 'UF_DRIVER' => 'Геннадий Петров', 'UF_AVAILABILITY' => true],
        ['UF_MODEL' => 'Ford Focus', 'UF_COMFORT_CATEGORY_ID' => 3, 'UF_DRIVER' => 'Михаил Волков', 'UF_AVAILABILITY' => true],
        ['UF_MODEL' => 'Hyundai Elantra', 'UF_COMFORT_CATEGORY_ID' => 3, 'UF_DRIVER' => 'Игорь Соколов', 'UF_AVAILABILITY' => true],
    ],

    // 'sk_booking'
    'SK\CarBooking\Entity\BookingTable' => [
        ['UF_EMPLOYEE_ID' => 1, 'UF_CAR_ID' => 1, 'UF_START_TIME' => '2024-11-22 10:00:00', 'UF_END_TIME' => '2024-11-22 12:00:00'], // Иван Иванов бронирует BMW
        ['UF_EMPLOYEE_ID' => 2, 'UF_CAR_ID' => 2, 'UF_START_TIME' => '2024-11-22 13:00:00', 'UF_END_TIME' => '2024-11-22 15:00:00'], // Петр Петров бронирует Mercedes
        ['UF_EMPLOYEE_ID' => 3, 'UF_CAR_ID' => 3, 'UF_START_TIME' => '2024-11-22 11:00:00', 'UF_END_TIME' => '2024-11-22 14:00:00'], // Мария Сидорова бронирует Toyota
    ]
];

return $testData;