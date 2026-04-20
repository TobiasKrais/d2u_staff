<?php

/*
 * Modules
 */
$d2u_module_manager = new \TobiasKrais\D2UHelper\ModuleManager(TobiasKrais\D2UStaff\Module::getModules(), 'modules/', 'd2u_staff');

// \TobiasKrais\D2UHelper\ModuleManager actions
$d2u_module_id = rex_request('d2u_module_id', 'string');
$paired_module = rex_request('pair_'. $d2u_module_id, 'int');
$function = rex_request('function', 'string');
if ('' !== $d2u_module_id) {
    $d2u_module_manager->doActions($d2u_module_id, $function, $paired_module);
}

// \TobiasKrais\D2UHelper\ModuleManager show list
$d2u_module_manager->showManagerList();

?>
<h2>Beispielseiten</h2>
<ul>
	<li>D2U Mitarbeiter Addon: <a href="https://www.kaltenbach.com/de/unternehmen/kaltenbach-gruppe/" target="_blank">
		www.kaltenbach.com</a>.</li>
</ul>
<h2>Support</h2>
<p>Fehlermeldungen bitte über
	<a href="https://github.com/TobiasKrais/d2u_staff/issues" target="_blank">GitHub</a> melden.</p>