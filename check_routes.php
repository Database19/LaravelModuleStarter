<?php

// Script untuk cek dan perbaiki route yang tidak terdefinisi dalam menu seeder

$routeReplacements = [
    // Sales Module
    'sales.invoices.index' => 'sales.orders.index',
    'sales.payments.index' => 'sales.orders.index',
    'sales.reports.index' => 'sales.orders.index',

    // Purchasing Module
    'purchasing.invoices.index' => 'purchasing.purchase-orders.index',
    'purchasing.payments.index' => 'purchasing.purchase-orders.index',
    'purchasing.reports.index' => 'purchasing.purchase-orders.index',

    // Inventory Module
    'inventory.reports.index' => 'inventory.products.index',

    // Manufacturing Module
    'manufacturing.reports.index' => 'manufacturing.boms.index',

    // POS Module (sudah diperbaiki)
    'pos.terminal.index' => 'pointofsales.index',
    'pos.transactions.index' => 'pointofsales.create',
    'pos.settings.index' => 'pointofsales.index',

    // Quality Control
    'quality.reports.index' => 'qualitycontrol.index',
    'quality.standards.index' => 'qualitycontrol.create',

    // Accounting Module
    'accounting.reports.index' => 'accounting.settings.index',
    'accounting.budget.index' => 'accounting.settings.index',
    'accounting.fixed-assets.index' => 'accounting.settings.index',

    // HR Module
    'hr.reports.index' => 'humanresource.employees.index',
    'hr.recruitment.index' => 'humanresource.employees.create',
    'hr.performance.index' => 'humanresource.employees.index',

    // Project Management
    'projects.reports.index' => 'project.management.index',
    'projects.time-tracking.index' => 'project.management.create',
    'projects.tasks.index' => 'project.management.index',

    // Settings routes yang mungkin tidak ada
    'settings.users.index' => 'admin.users.index',
    'settings.roles.index' => 'admin.roles.index',
    'settings.menus.index' => 'admin.menu.index',
    'settings.company.index' => 'home',
    'settings.accounting.index' => 'accounting.settings.index',
    'settings.email.index' => 'home',
    'settings.backup.index' => 'home',
    'settings.logs.index' => 'home',

    // Reports module
    'reports.executive.index' => 'home',
    'reports.sales.index' => 'sales.orders.index',
    'reports.financial.index' => 'accounting.settings.index',
    'reports.inventory.index' => 'inventory.products.index',
    'reports.hr.index' => 'humanresource.employees.index',
    'reports.custom.index' => 'home',
];

echo "Route replacements needed:\n";
foreach ($routeReplacements as $old => $new) {
    echo "- $old => $new\n";
}

echo "\nRoute replacements ready to apply to MenuItemSeeder.php\n";
