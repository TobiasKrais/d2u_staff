<?php

?>
<h2>Changelog</h2>
<p>1.3.0:</p>
<ul>
	<li>Neue Module 22-4 bis 22-6 als Bootstrap-5-Varianten der bestehenden Beispielmodule hinzugefügt.</li>
	<li>Module 22-1 bis 22-3 als "(BS4, deprecated)" markiert. Die BS4-Varianten werden im nächsten Major Release entfernt.</li>
	<li>Bugfix: Prioritäten werden bei Mitarbeitenden nach dem Speichern wieder stabil neu durchnummeriert, auch wenn in der Datenbank bereits doppelte Werte vorhanden sind.</li>
	<li>Backend-Listen sortierbar gemacht und Standardsortierungen von SQL-Queries auf <code>rex_list</code>-<code>defaultSort</code> umgestellt.</li>
	<li>Die Priorität von Mitarbeitenden kann in der Backend-Liste jetzt direkt per Hoch-/Runter-Buttons geändert werden.</li>
</ul>
<p>1.2.2:</p>
<ul>
	<li>Bugfix: Link in Übersetzungshilfe war fehlerhaft.</li>
	<li>Modul 22-1 "D2U Mitarbeiter - Liste": Höhe Boxen angeglichen und Abstände korrigiert.</li>
</ul>
<p>1.2.1:</p>
<ul>
	<li>Modul 22-1 "D2U Mitarbeiter - Liste": CSS auf Modul beschränkt.</li>
	<li>Modul 22-2 "D2U Mitarbeiter - Autorenbox Detailinfo": CSS auf Modul beschränkt.</li>
	<li>Modul 22-3 "D2U Mitarbeiter - Autorenbox Kurzinfo": CSS auf Modul beschränkt.</li>
</ul>
<p>1.2.0:</p>
<ul>
	<li>Vorbereitung auf R6: Folgende Klassen wurden umbenannt. Die alten Klassennamen funktionieren weiterhin, sind aber als veraltet markiert.
		<ul>
			<li><code>D2U_Staff\Company</code> wird zu <code>TobiasKrais\D2UStaff\Company</code>.</li>
			<li><code>Staff</code> wird zu <code>TobiasKrais\D2UStaff\Staff</code>.</li>
		</ul>
		Folgende interne Klassen wurden wurden ebenfalls umbenannt:
		<ul>
			<li><code>d2u_staff_lang_helper</code> wird zu <code>TobiasKrais\D2UStaff\LangHelper</code>.</li>
			<li><code>D2UStaffModules</code> wird zu <code>TobiasKrais\D2UStaff\Module</code>.</li>
		</ul>
	</li>
	<li>Modul 22-1 "D2U Mitarbeiter - Liste": auf großen Bildschirmen wird die Liste in 2 Spalten angezeigt.</li>
	<li>Modul 22-2 "D2U Mitarbeiter - Autorenbox Detailinfo": LD+JSON Ausgabe Fehlerbehebung bei Verwendung eines '.</li>
	<li>Modul 22-3 "D2U Mitarbeiter - Autorenbox Kurzinfo": Text auf 'Letztes Update am' ... 'von' geändert, damit deutlicher wird, dass das Datum ein Updatedatum ist.</li>
	<li>Bugfix: wenn ein Artikellink entfernt wurde, gab es beim Speichern einen Fehler.</li>
</ul>
<p>1.1.2:</p>
<ul>
	<li>PHP-CS-Fixer Code Verbesserungen.</li>
	<li>.github Verzeichnis aus Installer Action ausgeschlossen.</li>
	<li>rexstan Anpassungen</li>
</ul>
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