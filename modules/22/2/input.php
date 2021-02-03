<div class="row">
	<div class="col-xs-4">
		Breite des Blocks:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[20]" class="form-control">
		<?php
		$values = [12=>"12 von 12 Spalten (ganze Breite)", 8=>"8 von 12 Spalten", 6=>"6 von 12 Spalten", 4=>"4 von 12 Spalten", 3=>"3 von 12 Spalten"];
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if ("REX_VALUE[20]" == $key) {
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Offset (Seitenabstand) auf größeren Geräten:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[17]" class="form-control">
		<?php
		$values = [0=>"Nicht zentrieren.", 1=>"Zentrieren, wenn freie Breite von anderem Inhalt nicht genutzt wird"];
		foreach($values as $key => $value) {
			echo '<option value="'. $key .'" ';
	
			if ("REX_VALUE[17]" == $key) {
				echo 'selected="selected" ';
			}
			echo '>'. $value .'</option>';
		}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-4">
		Autor:
	</div>
	<div class="col-xs-8">
		<select name="REX_INPUT_VALUE[1]" class="form-control">
		<?php
			$sql_staff = rex_sql::factory();
			$result_staff = $sql_staff->setQuery('SELECT lang.staff_id, IF(lang_name = "", name, lang_name) AS all_name FROM ' . rex::getTablePrefix() . 'd2u_staff_lang AS lang '
				. 'LEFT JOIN ' . rex::getTablePrefix() . 'd2u_staff AS staff ON lang.staff_id = staff.staff_id AND lang.clang_id = '. rex_clang::getCurrentId() .' '
				.'WHERE online_status = "online" ORDER BY all_name, priority');
			for($i = 0; $i < $result_staff->getRows(); $i++) {
				echo '<option value="'. $result_staff->getValue("staff_id") .'" '
					.("REX_VALUE[1]" == $result_staff->getValue("staff_id") ? 'selected="selected" ' : '')
					.'>'. $result_staff->getValue("all_name") .'</option>';
				$result_staff->next();
			}
		?>
		</select>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">&nbsp;</div>
</div>
<div class="row">
	<div class="col-xs-12"><p>Alle Daten werden im <a href="index.php?page=d2u_staff">D2U Mitarbeiter Addon</a> verwaltet.</p></div>
</div>