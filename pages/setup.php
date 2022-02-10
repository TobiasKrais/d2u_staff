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
<p>1.1.1:</p>
<ul>
	<li>Anpassungen an Publish-Release-to-Redaxo.</li>
</ul>
<p>1.1.0:</p>
<ul>
	<li>Bugfix: Beim Löschen von Medien die vom Addon verlinkt werden wurde der Name der verlinkenden Quelle in der Warnmeldung nicht immer korrekt angegeben.</li>
	<li>Verknüpfung zum D2U Adressen Addon entfernt und statt dessen eigene Tabelle für Unternehmen hinzugefügt.</li>
	<li>Ausschnitt der Zitate werden jetzt in der Übersichtsliste angezeigt.</li>
	<li>Benötigt Redaxo >= 5.10, da die neue Klasse rex_version verwendet wird.</li>
	<li>Modul 22-1 "D2U Mitarbeiter - Liste". Eingabefelder nun im Redaxo Stil.</li>
	<li>Neues Modul 22-2 "D2U Mitarbeiter - Autorenbox Detailinfo". Für einen Redaxo Artikel kann eine Autorenbox ausgegeben werden. Dabei werden die Autoreninformationen auch im Google kompatiblen LD+JSON Format ausgegeben.</li>
	<li>Neues Modul 22-3 "D2U Mitarbeiter - Autorenbox Kurzinfo". Kurzinfo des Artikelautors.</li>
</ul>
<p>1.0.3:</p>
<ul>
	<li>Backend: Einstellungen und Setup Tabs rechts eingeordnet um sie vom Inhalt besser zu unterscheiden.</li>
	<li>Feld "Sprachspezifischer Name" hinzugefügt (ermöglicht die Eingabe eines Namens in kyrillischen, arabischen, ... Buchstaben).</li>
	<li>Nicht benötigte "updatedate" Felder in Datenbank entfernt.</li>
	<li>Listen im Backend werden jetzt nicht mehr in Seiten unterteilt.</li>
	<li>Konvertierung der Datenbanktabellen zu utf8mb4.</li>
	<li>Bugfix beim Löschen einer Sprache.</li>
	<li>Sprachdetails werden ausgeblendet, wenn Speicherung der Sprache nicht vorgesehen ist.</li>
	<li>Bugfix: Prioritäten wurden beim Löschen nicht reorganisiert.</li>
</ul>
<p>1.0.2:</p>
<ul>
	<li>Feld "Sprachspezifischer Name" hinzugefügt (ermöglicht die Eingabe eines Namens in kyrillischen, arabischen, ... Buchstaben).</li>
	<li>Nicht benötigte "updatedate" Felder in Datenbank entfernt.</li>
	<li>Listen im Backend werden jetzt nicht mehr in Seiten unterteilt.</li>
	<li>Konvertierung der Datenbanktabellen zu utf8mb4.</li>
	<li>Bugfix beim Löschen einer Sprache.</li>
	<li>Sprachdetails werden ausgeblendet, wenn Speicherung der Sprache nicht vorgesehen ist.</li>
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