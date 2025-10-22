<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Units of Measurement
    |--------------------------------------------------------------------------
    |
    | This file contains all available units of measurement used throughout
    | the Huda ERP system, particularly for materials and BOM management.
    |
    */

    'units' => [
        'piece' => [
            'name' => 'Piece',
            'name_ar' => 'قطعة',
            'abbreviation' => 'pcs',
            'type' => 'count',
        ],
        'meter' => [
            'name' => 'Meter',
            'name_ar' => 'متر',
            'abbreviation' => 'm',
            'type' => 'length',
        ],
        'cm' => [
            'name' => 'Centimeter',
            'name_ar' => 'سنتيمتر',
            'abbreviation' => 'cm',
            'type' => 'length',
        ],
        'kg' => [
            'name' => 'Kilogram',
            'name_ar' => 'كيلوجرام',
            'abbreviation' => 'kg',
            'type' => 'weight',
        ],
        'gram' => [
            'name' => 'Gram',
            'name_ar' => 'جرام',
            'abbreviation' => 'g',
            'type' => 'weight',
        ],
        'box' => [
            'name' => 'Box',
            'name_ar' => 'علبة',
            'abbreviation' => 'box',
            'type' => 'packaging',
        ],
        'carton' => [
            'name' => 'Carton',
            'name_ar' => 'كرتون',
            'abbreviation' => 'ctn',
            'type' => 'packaging',
        ],
        'roll' => [
            'name' => 'Roll',
            'name_ar' => 'لفة',
            'abbreviation' => 'roll',
            'type' => 'packaging',
        ],
        'yard' => [
            'name' => 'Yard',
            'name_ar' => 'ياردة',
            'abbreviation' => 'yd',
            'type' => 'length',
        ],
        'dozen' => [
            'name' => 'Dozen',
            'name_ar' => 'دستة',
            'abbreviation' => 'dz',
            'type' => 'count',
        ],
        'pack' => [
            'name' => 'Pack',
            'name_ar' => 'حزمة',
            'abbreviation' => 'pack',
            'type' => 'packaging',
        ],
        'bottle' => [
            'name' => 'Bottle',
            'name_ar' => 'قارورة',
            'abbreviation' => 'btl',
            'type' => 'packaging',
        ],
        'can' => [
            'name' => 'Can',
            'name_ar' => 'علبة معدنية',
            'abbreviation' => 'can',
            'type' => 'packaging',
        ],
        'set' => [
            'name' => 'Set',
            'name_ar' => 'طقم',
            'abbreviation' => 'set',
            'type' => 'count',
        ],
        'pair' => [
            'name' => 'Pair',
            'name_ar' => 'زوج',
            'abbreviation' => 'pr',
            'type' => 'count',
        ],
        'bundle' => [
            'name' => 'Bundle',
            'name_ar' => 'رزمة',
            'abbreviation' => 'bndl',
            'type' => 'packaging',
        ],
        'spool' => [
            'name' => 'Spool',
            'name_ar' => 'بكرة خيوط',
            'abbreviation' => 'spl',
            'type' => 'textile',
            'description' => 'For threads, yarns, and similar materials',
        ],
        'strand' => [
            'name' => 'Strand',
            'name_ar' => 'خصلة/خيط',
            'abbreviation' => 'str',
            'type' => 'textile',
            'description' => 'For individual threads or strings (e.g., pearl strings)',
        ],
        'card' => [
            'name' => 'Card',
            'name_ar' => 'كرت/بطاقة',
            'abbreviation' => 'crd',
            'type' => 'packaging',
            'description' => 'For items mounted on cards (e.g., buttons, beads)',
        ],
        'sheet' => [
            'name' => 'Sheet',
            'name_ar' => 'ورقة/قطعة',
            'abbreviation' => 'sht',
            'type' => 'count',
            'description' => 'For flat materials like paper, fabric sheets',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Unit Types
    |--------------------------------------------------------------------------
    |
    | Categories for grouping units by type
    |
    */

    'types' => [
        'count' => 'Count/Quantity',
        'length' => 'Length/Distance',
        'weight' => 'Weight/Mass',
        'packaging' => 'Packaging',
        'textile' => 'Textile/Fashion',
    ],

    /*
    |--------------------------------------------------------------------------
    | Standard Sizes
    |--------------------------------------------------------------------------
    |
    | Common sizes used in fashion and textile industry
    |
    */

    'sizes' => [
        // Garment Sizes
        'garment' => [
            'XXS' => 'XX-Small',
            'XS' => 'X-Small',
            'S' => 'Small',
            'M' => 'Medium',
            'L' => 'Large',
            'XL' => 'X-Large',
            'XXL' => 'XX-Large',
            'XXXL' => 'XXX-Large',
        ],

        // Fabric Widths (in cm)
        'fabric_width' => [
            '90cm' => '90 cm width',
            '110cm' => '110 cm width',
            '115cm' => '115 cm width',
            '120cm' => '120 cm width',
            '140cm' => '140 cm width',
            '150cm' => '150 cm width',
            '160cm' => '160 cm width',
            '180cm' => '180 cm width',
            '220cm' => '220 cm width',
            '280cm' => '280 cm width',
        ],

        // Button Sizes (in mm or ligne)
        'button' => [
            '10mm' => '10mm (16L)',
            '12mm' => '12mm (18L)',
            '15mm' => '15mm (24L)',
            '18mm' => '18mm (28L)',
            '20mm' => '20mm (32L)',
            '23mm' => '23mm (36L)',
            '25mm' => '25mm (40L)',
            '28mm' => '28mm (44L)',
            '30mm' => '30mm (48L)',
        ],

        // Zipper Lengths (in cm)
        'zipper' => [
            '10cm' => '10 cm',
            '12cm' => '12 cm',
            '15cm' => '15 cm',
            '18cm' => '18 cm',
            '20cm' => '20 cm',
            '25cm' => '25 cm',
            '30cm' => '30 cm',
            '35cm' => '35 cm',
            '40cm' => '40 cm',
            '45cm' => '45 cm',
            '50cm' => '50 cm',
            '55cm' => '55 cm',
            '60cm' => '60 cm',
            '70cm' => '70 cm',
            '80cm' => '80 cm',
        ],

        // Thread Sizes
        'thread' => [
            '20' => 'Size 20',
            '30' => 'Size 30',
            '40' => 'Size 40',
            '50' => 'Size 50',
            '60' => 'Size 60',
            '80' => 'Size 80',
            '100' => 'Size 100',
        ],

        // Pearl/Bead Sizes (in mm)
        'pearl_bead' => [
            '2mm' => '2mm',
            '3mm' => '3mm',
            '4mm' => '4mm',
            '5mm' => '5mm',
            '6mm' => '6mm',
            '8mm' => '8mm',
            '10mm' => '10mm',
            '12mm' => '12mm',
            '14mm' => '14mm',
            '16mm' => '16mm',
        ],

        // Ribbon/Trim Widths (in cm)
        'ribbon_trim' => [
            '0.5cm' => '0.5 cm',
            '1cm' => '1 cm',
            '1.5cm' => '1.5 cm',
            '2cm' => '2 cm',
            '2.5cm' => '2.5 cm',
            '3cm' => '3 cm',
            '4cm' => '4 cm',
            '5cm' => '5 cm',
            '7cm' => '7 cm',
            '10cm' => '10 cm',
        ],

        // Generic Sizes
        'generic' => [
            'mini' => 'Mini',
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
            'xlarge' => 'Extra Large',
            'standard' => 'Standard',
            'custom' => 'Custom',
            'various' => 'Various Sizes',
        ],
    ],
];

