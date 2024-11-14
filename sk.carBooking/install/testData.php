<?php
$testData = [

    // 'sk_comfort_categories'
    'SK\CarBooking\Entity\ComfortCategoryTable' => [
        ['id' => 1, 'name' => 'Бизнес'],
        ['id' => 2, 'name' => 'Комфорт'],
        ['id' => 3, 'name' => 'Стандарт'],
    ],

    // 'sk_positions'
    'SK\CarBooking\Entity\PositionTable' => [
        ['id' => 1, 'position_name' => 'Директор'],
        ['id' => 2, 'position_name' => 'Заместитель директора'],
        ['id' => 3, 'position_name' => 'Менеджер'],
        ['id' => 4, 'position_name' => 'Старший специалист'],
        ['id' => 5, 'position_name' => 'Младший специалист'],
        ['id' => 6, 'position_name' => 'Консультант'],
    ],

    // 'sk_position_comfort_categories'
    'SK\CarBooking\Entity\PositionComfortCategoryTable' => [
        ['position_id' => 1, 'comfort_category_id' => 1], // Директор — 1 категория (Бизнес)
        ['position_id' => 2, 'comfort_category_id' => 1], // Заместитель директора — 1 категория (Бизнес)
        ['position_id' => 3, 'comfort_category_id' => 2], // Менеджер — 2 категория (Комфорт)
        ['position_id' => 4, 'comfort_category_id' => 2], // Старший специалист — 2 категория (Комфорт)
        ['position_id' => 5, 'comfort_category_id' => 3], // Младший специалист — 3 категория (Стандарт)
        ['position_id' => 6, 'comfort_category_id' => 3], // Консультант — 3 категория (Стандарт)
    ],

    // 'sk_employees'
    'SK\CarBooking\Entity\EmployeeTable' => [
        ['name' => 'Иван Иванов', 'position_id' => 1], // Директор
        ['name' => 'Петр Петров', 'position_id' => 2], // Заместитель директора
        ['name' => 'Мария Сидорова', 'position_id' => 3], // Менеджер
        ['name' => 'Ольга Смирнова', 'position_id' => 3], // Менеджер
        ['name' => 'Анна Васильева', 'position_id' => 4], // Старший специалист
        ['name' => 'Алексей Михайлов', 'position_id' => 4], // Старший специалист
        ['name' => 'Владимир Попов', 'position_id' => 5], // Младший специалист
        ['name' => 'Светлана Козлова', 'position_id' => 5], // Младший специалист
        ['name' => 'Дмитрий Орлов', 'position_id' => 6], // Консультант
        ['name' => 'Екатерина Павлова', 'position_id' => 6], // Консультант
    ],

    // 'sk_cars'
    'SK\CarBooking\Entity\CarTable' => [
        ['model' => 'BMW 7 Series', 'comfort_category_id' => 1, 'driver' => 'Сергей Сергеев', 'availability' => true],
        ['model' => 'Mercedes S-Class', 'comfort_category_id' => 1, 'driver' => 'Александр Куликов', 'availability' => true],
        ['model' => 'Toyota Camry', 'comfort_category_id' => 2, 'driver' => 'Василий Федоров', 'availability' => true],
        ['model' => 'Honda Accord', 'comfort_category_id' => 2, 'driver' => 'Геннадий Петров', 'availability' => true],
        ['model' => 'Ford Focus', 'comfort_category_id' => 3, 'driver' => 'Михаил Волков', 'availability' => true],
        ['model' => 'Hyundai Elantra', 'comfort_category_id' => 3, 'driver' => 'Игорь Соколов', 'availability' => true],
    ],
];