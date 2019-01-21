<?php
/*
 * Modules
 */
$d2u_module_manager = new D2UModuleManager(D2UStaffModules::getModules(), "modules/", "d2u_staff");

// D2UModuleManager actions
$d2u_module_id = rex_request('d2u_module_id', 'string');
$paired_module = rex_request('pair_'. $d2u_module_id, 'int');
$function = rex_request('function', 'string');
if($d2u_module_id != "") {
	$d2u_module_manager->doActions($d2u_module_id, $function, $paired_module);
}

// D2UModuleManager show list
$d2u_module_manager->showManagerList();

/*
 * Templates
 */
?>
<h2>Beispielseiten</h2>
<ul>
	<li>D2U Mitarbeiter Addon: <a href="https://www.kaltenbach.com/de/unternehmen/kaltenbach-gruppe/" target="_blank">
		www.kaltenbach.com</a>.</li>
</ul>
<h2>Support</h2>
<p>Fehlermeldungen bitte über
	<a href="https://github.com/TobiasKrais/d2u_staff/issues" target="_blank">GitHub</a> melden.</p>
<h2>Changelog</h2>
<p>1.0.2-DEV:</p>
<ul>
	<li>Bugfix: Prioritäten wurden beim Löschen nicht reorganisiert.</li>
</ul>
<p>1.0.1:</p>
<ul>
	<li>Bugfix: Fehler beim Speichern von Namen mit einfachem Anführungszeichen behoben.</li>
	<li>Übernimmt Lieblingseditor aus D2U Helper Addon.</li>
	<li>Beispielmodul jetzt mit Bootstrap 4.</li>
	<li>Im Setup jetzt GitHub Link für Fehlermeldungen.</li>
	<li>Englisches Backend hinzugefügt.</li>
</ul>
<p>1.0.0:</p>
<ul>
	<li>Initiale Version.</li>
</ul>