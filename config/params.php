<?php

return [
    'adminEmail' => 'iconicinterior2026@gmail.com',
    'senderEmail' => 'noreply@iconicinteriors.in',
    'senderName' => 'ICONIC INTERIORS',

    // Upload constraints (used by models & admin forms)
    'upload.maxSize' => 8 * 1024 * 1024,
    'upload.extensions' => 'png, jpg, jpeg, webp',

    // Fixed vocabularies used across the frontend & admin
    'heroRooms' => ['Living Lounge', 'Master Bedroom', 'Modular Kitchen', 'Executive Office'],
    'lightingModes' => [
        'warm' => 'Warm Evening 2700K',
        'daylight' => 'Natural Daylight',
        'noir' => 'Noir Lounge Mood',
    ],
    'propertyTypes' => [
        '1BHK' => ['label' => '1 BHK Apartment', 'area' => 550, 'factor' => 1.00],
        '2BHK' => ['label' => '2 BHK Apartment', 'area' => 950, 'factor' => 1.00],
        '3BHK' => ['label' => '3 BHK Apartment', 'area' => 1400, 'factor' => 1.04],
        '4BHK/Villa' => ['label' => '4 BHK / Villa', 'area' => 2400, 'factor' => 1.10],
        'Commercial Office' => ['label' => 'Commercial Office', 'area' => 1800, 'factor' => 0.92],
    ],
    'projectCategories' => ['Residential', 'Commercial', 'Kitchens', 'Bedrooms', 'Furniture', 'Ceilings', 'CNC & Jali'],
];
