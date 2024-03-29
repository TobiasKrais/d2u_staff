<?php

\rex_sql_table::get(\rex::getTable('d2u_staff'))
    ->ensureColumn(new rex_sql_column('staff_id', 'INT(11) unsigned', false, null, 'auto_increment'))
    ->setPrimaryKey('staff_id')
    ->ensureColumn(new \rex_sql_column('name', 'VARCHAR(191)', true))
    ->ensureColumn(new \rex_sql_column('gender', 'VARCHAR(10)', true))
    ->ensureColumn(new \rex_sql_column('picture', 'VARCHAR(191)', true))
    ->ensureColumn(new \rex_sql_column('online_status', 'VARCHAR(10)', true))
    ->ensureColumn(new \rex_sql_column('company_id', 'INT(11)', false, 0))
    ->ensureColumn(new \rex_sql_column('article_id', 'INT(11)', true))
    ->ensureColumn(new \rex_sql_column('priority', 'INT(11)', true))
    ->ensureColumn(new \rex_sql_column('updatedate', 'DATETIME'))
    ->ensure();
\rex_sql_table::get(\rex::getTable('d2u_staff_lang'))
    ->ensureColumn(new rex_sql_column('staff_id', 'INT(11) unsigned', false, null, 'auto_increment'))
    ->ensureColumn(new \rex_sql_column('clang_id', 'INT(11)', false, 1))
    ->setPrimaryKey(['staff_id', 'clang_id'])
    ->ensureColumn(new \rex_sql_column('lang_name', 'VARCHAR(191)'))
    ->ensureColumn(new \rex_sql_column('knows_about', 'VARCHAR(191)'))
    ->ensureColumn(new \rex_sql_column('area_of_responsibility', 'VARCHAR(191)'))
    ->ensureColumn(new \rex_sql_column('position', 'VARCHAR(191)', true))
    ->ensureColumn(new \rex_sql_column('citation', 'TEXT', true))
    ->ensureColumn(new \rex_sql_column('translation_needs_update', 'VARCHAR(7)'))
    ->ensure();

\rex_sql_table::get(\rex::getTable('d2u_staff_company'))
    ->ensureColumn(new rex_sql_column('company_id', 'INT(11) unsigned', false, null, 'auto_increment'))
    ->setPrimaryKey('company_id')
    ->ensureColumn(new \rex_sql_column('name', 'VARCHAR(191)', true))
    ->ensureColumn(new \rex_sql_column('url', 'VARCHAR(191)', true))
    ->ensureColumn(new \rex_sql_column('logo', 'VARCHAR(191)', true))
    ->ensure();

// Remove old column
\rex_sql_table::get(
    \rex::getTable('d2u_staff'))
    ->removeColumn('address_id')
    ->ensure();

// Update modules
include __DIR__ . DIRECTORY_SEPARATOR .'lib'. DIRECTORY_SEPARATOR .'Module.php';
$d2u_module_manager = new \TobiasKrais\D2UHelper\ModuleManager(\TobiasKrais\D2UStaff\Module::getModules(), '', 'd2u_staff');
$d2u_module_manager->autoupdate();

// Update language replacements
if (!class_exists(TobiasKrais\D2UStaff\LangHelper::class)) {
    // Load class in case addon is deactivated
    require_once 'lib/LangHelper.php';
}
TobiasKrais\D2UStaff\LangHelper::factory()->install();
