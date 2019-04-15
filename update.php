<?php
// Update modules
if(class_exists('D2UModuleManager')) {
	$modules = [];
	$modules[] = new D2UModule("22-1",
		"D2U Personen - Karusell",
		1);
	$d2u_module_manager = new D2UModuleManager($modules, "", "d2u_staff");
	$d2u_module_manager->autoupdate();
}

$sql = rex_sql::factory();
// Update database to 1.0.2
$sql->setQuery("ALTER TABLE `". rex::getTablePrefix() ."d2u_staff` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
$sql->setQuery("ALTER TABLE `". rex::getTablePrefix() ."d2u_staff_lang` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

if (rex_string::versionCompare($this->getVersion(), '1.0.2', '<')) {
	$sql->setQuery("ALTER TABLE ". \rex::getTablePrefix() ."d2u_staff DROP updatedate;");
	$sql->setQuery("ALTER TABLE ". \rex::getTablePrefix() ."d2u_staff_lang DROP updatedate;");
}
$sql->setQuery("SHOW COLUMNS FROM ". \rex::getTablePrefix() ."d2u_staff_lang LIKE 'lang_name';");
if($sql->getRows() == 0) {
	$sql->setQuery("ALTER TABLE ". \rex::getTablePrefix() ."d2u_staff_lang "
		. "ADD lang_name varchar(255) collate utf8mb4_unicode_ci default NULL AFTER clang_id;");
}