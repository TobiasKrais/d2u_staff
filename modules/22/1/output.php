<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
	
	$position_container_classes = "col-12 col-lg-". $cols . $offset_lg;
	
	$type = "REX_VALUE[1]";

	$stafflist = Staff::getAll(rex_clang::getCurrentId(), TRUE);
	
	// Output
	foreach ($stafflist as $staff) {
		print '<div class="'. $position_container_classes .'">';
		print '<div class="module-box">';
		print '<div class="row">';
		print '<div class="col-12 col-sm-6 col-md-4"><img src="';
			if($staff->picture == "") {
				print rex_addon::get('d2u_staff')->getAssetsUrl("noavatar.jpg");
			}
		else {
			print 'index.php?rex_media_type='. $type .'&rex_media_file='. $staff->picture;
		}
		print '" alt="'. $staff->name .'"><br><br></div>';
		print '<div class="col-12 col-sm-6 col-md-8">';
		print '<strong>'. $staff->name.'</strong> '. $staff->position .'<br><br>';
		if($staff->area_of_responsibility != "") {
			print $staff->area_of_responsibility .'<br>';
		}
		if($staff->citation != "") {
			print $staff->citation;
		}
		print '</div>';
		print '</div>';
		print '</div>';
		print '</div>';
	}