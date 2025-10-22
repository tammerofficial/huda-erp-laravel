<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Huda ERP Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all the main configuration settings for the Huda ERP
    | system including currency, date formats, production settings, and more.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */
    'company_name' => env('HUDA_COMPANY_NAME', 'Huda Industries'),
    'company_logo' => env('HUDA_COMPANY_LOGO', '/images/logo.png'),
    'timezone' => env('HUDA_TIMEZONE', 'Asia/Kuwait'),
    'language' => env('HUDA_LANGUAGE', 'en'),
    'date_format' => env('HUDA_DATE_FORMAT', 'Y-m-d'),
    'time_format' => env('HUDA_TIME_FORMAT', 'H:i:s'),
    'datetime_format' => env('HUDA_DATETIME_FORMAT', 'Y-m-d H:i:s'),

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    */
    'currency' => [
        'code' => env('HUDA_CURRENCY_CODE', 'KWD'),
        'symbol' => env('HUDA_CURRENCY_SYMBOL', 'KWD'),
        'position' => env('HUDA_CURRENCY_POSITION', 'after'), // before or after
        'decimal_places' => env('HUDA_CURRENCY_DECIMAL_PLACES', 2),
        'thousands_separator' => env('HUDA_THOUSANDS_SEPARATOR', ','),
        'decimal_separator' => env('HUDA_DECIMAL_SEPARATOR', '.'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Production Settings
    |--------------------------------------------------------------------------
    */
    'production' => [
        'default_cycle_time' => env('HUDA_DEFAULT_CYCLE_TIME', 24), // hours
        'quality_check_required' => env('HUDA_QUALITY_CHECK_REQUIRED', true),
        'auto_assign_employees' => env('HUDA_AUTO_ASSIGN_EMPLOYEES', false),
        'default_warehouse' => env('HUDA_DEFAULT_WAREHOUSE', 1),
        'production_stages' => [
            'planning' => 'Planning',
            'preparation' => 'Preparation',
            'production' => 'Production',
            'quality_control' => 'Quality Control',
            'packaging' => 'Packaging',
            'completed' => 'Completed',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Inventory Settings
    |--------------------------------------------------------------------------
    */
    'inventory' => [
        'low_stock_threshold' => env('HUDA_LOW_STOCK_THRESHOLD', 10),
        'auto_reorder' => env('HUDA_AUTO_REORDER', false),
        'track_expiry' => env('HUDA_TRACK_EXPIRY', false),
        'default_unit' => env('HUDA_DEFAULT_UNIT', 'pcs'),
        'barcode_enabled' => env('HUDA_BARCODE_ENABLED', true),
        'qr_code_enabled' => env('HUDA_QR_CODE_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Order Settings
    |--------------------------------------------------------------------------
    */
    'orders' => [
        'auto_generate_invoice' => env('HUDA_AUTO_GENERATE_INVOICE', true),
        'default_payment_terms' => env('HUDA_DEFAULT_PAYMENT_TERMS', 'Net 30'),
        'require_customer_approval' => env('HUDA_REQUIRE_CUSTOMER_APPROVAL', false),
        'default_status' => env('HUDA_DEFAULT_ORDER_STATUS', 'pending'),
        'statuses' => [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'in_production' => 'In Production',
            'completed' => 'Completed',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice Settings
    |--------------------------------------------------------------------------
    */
    'invoices' => [
        'prefix' => env('HUDA_INVOICE_PREFIX', 'INV'),
        'number_padding' => env('HUDA_INVOICE_NUMBER_PADDING', 6),
        'auto_number' => env('HUDA_INVOICE_AUTO_NUMBER', true),
        'default_due_days' => env('HUDA_INVOICE_DEFAULT_DUE_DAYS', 30),
        'tax_rate' => env('HUDA_TAX_RATE', 0), // percentage
        'tax_inclusive' => env('HUDA_TAX_INCLUSIVE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Employee Settings
    |--------------------------------------------------------------------------
    */
    'employees' => [
        'qr_code_enabled' => env('HUDA_EMPLOYEE_QR_ENABLED', true),
        'qr_code_prefix' => env('HUDA_EMPLOYEE_QR_PREFIX', 'EMP'),
        'default_department' => env('HUDA_DEFAULT_DEPARTMENT', 'Production'),
        'departments' => [
            'Production' => 'Production',
            'Quality Control' => 'Quality Control',
            'Maintenance' => 'Maintenance',
            'Administration' => 'Administration',
            'Sales' => 'Sales',
            'Finance' => 'Finance',
            'HR' => 'Human Resources',
        ],
        'employment_statuses' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'terminated' => 'Terminated',
            'on_leave' => 'On Leave',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Customer Settings
    |--------------------------------------------------------------------------
    */
    'customers' => [
        'default_credit_limit' => env('HUDA_DEFAULT_CREDIT_LIMIT', 1000),
        'require_approval_for_credit' => env('HUDA_REQUIRE_CREDIT_APPROVAL', true),
        'customer_types' => [
            'individual' => 'Individual',
            'business' => 'Business',
            'government' => 'Government',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Supplier Settings
    |--------------------------------------------------------------------------
    */
    'suppliers' => [
        'default_payment_terms' => env('HUDA_SUPPLIER_DEFAULT_PAYMENT_TERMS', 'Net 30'),
        'supplier_types' => [
            'material' => 'Material Supplier',
            'service' => 'Service Provider',
            'both' => 'Both Material & Service',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Warehouse Settings
    |--------------------------------------------------------------------------
    */
    'warehouses' => [
        'default_location' => env('HUDA_DEFAULT_WAREHOUSE_LOCATION', 'Kuwait City'),
        'track_locations' => env('HUDA_TRACK_WAREHOUSE_LOCATIONS', true),
        'require_manager_approval' => env('HUDA_REQUIRE_WAREHOUSE_MANAGER_APPROVAL', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Accounting Settings
    |--------------------------------------------------------------------------
    */
    'accounting' => [
        'fiscal_year_start' => env('HUDA_FISCAL_YEAR_START', '01-01'),
        'chart_of_accounts_enabled' => env('HUDA_CHART_OF_ACCOUNTS_ENABLED', true),
        'double_entry_enabled' => env('HUDA_DOUBLE_ENTRY_ENABLED', true),
        'auto_journal_entries' => env('HUDA_AUTO_JOURNAL_ENTRIES', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'email_enabled' => env('HUDA_EMAIL_NOTIFICATIONS', true),
        'sms_enabled' => env('HUDA_SMS_NOTIFICATIONS', false),
        'low_stock_alerts' => env('HUDA_LOW_STOCK_ALERTS', true),
        'order_status_updates' => env('HUDA_ORDER_STATUS_UPDATES', true),
        'production_completion_alerts' => env('HUDA_PRODUCTION_COMPLETION_ALERTS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        'session_timeout' => env('HUDA_SESSION_TIMEOUT', 120), // minutes
        'password_min_length' => env('HUDA_PASSWORD_MIN_LENGTH', 8),
        'require_strong_passwords' => env('HUDA_REQUIRE_STRONG_PASSWORDS', true),
        'two_factor_auth' => env('HUDA_TWO_FACTOR_AUTH', false),
        'login_attempts_limit' => env('HUDA_LOGIN_ATTEMPTS_LIMIT', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Settings
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'auto_backup' => env('HUDA_AUTO_BACKUP', true),
        'backup_frequency' => env('HUDA_BACKUP_FREQUENCY', 'daily'), // daily, weekly, monthly
        'backup_retention_days' => env('HUDA_BACKUP_RETENTION_DAYS', 30),
        'backup_location' => env('HUDA_BACKUP_LOCATION', 'local'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Settings
    |--------------------------------------------------------------------------
    */
    'integrations' => [
        'woocommerce_enabled' => env('HUDA_WOOCOMMERCE_ENABLED', false),
        'woocommerce_url' => env('HUDA_WOOCOMMERCE_URL', ''),
        'woocommerce_key' => env('HUDA_WOOCOMMERCE_KEY', ''),
        'woocommerce_secret' => env('HUDA_WOOCOMMERCE_SECRET', ''),
        'woocommerce_consumer_key' => env('HUDA_WOOCOMMERCE_CONSUMER_KEY', ''),
        'woocommerce_consumer_secret' => env('HUDA_WOOCOMMERCE_CONSUMER_SECRET', ''),
        'woocommerce_sync_orders' => env('HUDA_WOOCOMMERCE_SYNC_ORDERS', true),
        'woocommerce_sync_customers' => env('HUDA_WOOCOMMERCE_SYNC_CUSTOMERS', true),
        'woocommerce_sync_products' => env('HUDA_WOOCOMMERCE_SYNC_PRODUCTS', true),
        'woocommerce_sync_interval' => env('HUDA_WOOCOMMERCE_SYNC_INTERVAL', 300), // seconds
        'woocommerce_order_statuses' => [
            'on-hold' => 'On Hold',
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
            'failed' => 'Failed',
        ],
        'woocommerce_sync_order_statuses' => env('HUDA_WOOCOMMERCE_SYNC_ORDER_STATUSES', 'on-hold,pending,processing'),
        'woocommerce_auto_create_customers' => env('HUDA_WOOCOMMERCE_AUTO_CREATE_CUSTOMERS', true),
        'woocommerce_auto_create_products' => env('HUDA_WOOCOMMERCE_AUTO_CREATE_PRODUCTS', true),
        'woocommerce_webhook_secret' => env('HUDA_WOOCOMMERCE_WEBHOOK_SECRET', ''),
        'woocommerce_webhook_url' => env('HUDA_WOOCOMMERCE_WEBHOOK_URL', ''),
        'api_enabled' => env('HUDA_API_ENABLED', true),
        'api_rate_limit' => env('HUDA_API_RATE_LIMIT', 100), // requests per minute
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Settings
    |--------------------------------------------------------------------------
    */
    'theme' => [
        'primary_color' => env('HUDA_PRIMARY_COLOR', '#3B82F6'),
        'secondary_color' => env('HUDA_SECONDARY_COLOR', '#6B7280'),
        'dark_mode' => env('HUDA_DARK_MODE', false),
        'sidebar_collapsed' => env('HUDA_SIDEBAR_COLLAPSED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'cache_enabled' => env('HUDA_CACHE_ENABLED', true),
        'cache_ttl' => env('HUDA_CACHE_TTL', 3600), // seconds
        'pagination_limit' => env('HUDA_PAGINATION_LIMIT', 15),
        'search_results_limit' => env('HUDA_SEARCH_RESULTS_LIMIT', 50),
        'auto_optimize_images' => env('HUDA_AUTO_OPTIMIZE_IMAGES', true),
    ],
];
