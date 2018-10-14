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