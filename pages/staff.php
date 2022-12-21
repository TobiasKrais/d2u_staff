<?php
$func = rex_request('func', 'string');
$entry_id = rex_request('entry_id', 'int');
$message = rex_get('message', 'string');

// Print comments
if($message != "") {
	print rex_view::success(rex_i18n::msg($message));
}

// save settings
if (intval(filter_input(INPUT_POST, "btn_save")) === 1 || intval(filter_input(INPUT_POST, "btn_apply")) === 1) {
	$form = (array) rex_post('form', 'array', []);

	// Media fields and links need special treatment
	$input_link = (array) rex_post('REX_INPUT_LINK', 'array', []);
	$input_media = (array) rex_post('REX_INPUT_MEDIA', 'array', []);

	$success = TRUE;
	$staff = FALSE;
	$staff_id = $form['staff_id'];
	foreach(rex_clang::getAll() as $rex_clang) {
		if($staff === FALSE) {
			$staff = new Staff($staff_id, $rex_clang->getId());
			$staff->staff_id = $staff_id; // Ensure correct ID in case first language has no object
			$staff->company_id = $form['company_id'];
			$staff->article_id = $input_link['article_id'];
			$staff->gender = $form['gender'];
			$staff->name = $form['name'];
			$staff->online_status = array_key_exists('online_status', $form) ? "online" : "offline";
			$staff->picture = $input_media[1];
			$staff->priority = $form['priority'];
		}
		else {
			$staff->clang_id = $rex_clang->getId();
		}
		$staff->area_of_responsibility = $form['lang'][$rex_clang->getId()]['area_of_responsibility'];
		$staff->citation = $form['lang'][$rex_clang->getId()]['citation'];
		$staff->position = $form['lang'][$rex_clang->getId()]['position'];
		$staff->lang_name = $form['lang'][$rex_clang->getId()]['lang_name'];
		$staff->knows_about = $form['lang'][$rex_clang->getId()]['knows_about'];
		$staff->translation_needs_update = $form['lang'][$rex_clang->getId()]['translation_needs_update'];
		
		if($staff->translation_needs_update == "delete") {
			$staff->delete(FALSE);
		}
		else if($staff->save() > 0){
			$success = FALSE;
		}
		else {
			// remember id, for each database lang object needs same id
			$staff_id = $staff->staff_id;
		}
	}

	// message output
	$message = 'form_save_error';
	if($success) {
		$message = 'form_saved';
	}
	
	// Redirect to make reload and thus double save impossible
	if(filter_input(INPUT_POST, "btn_apply") == 1 && $staff !== FALSE) {
		header("Location: ". rex_url::currentBackendPage(["entry_id"=>$staff->staff_id, "func"=>'edit', "message"=>$message], FALSE));
	}
	else {
		header("Location: ". rex_url::currentBackendPage(["message"=>$message], FALSE));
	}
	exit;
}
// Delete
else if(filter_input(INPUT_POST, "btn_delete") == 1 || $func == 'delete') {
	$staff_id = $entry_id;
	if($staff_id == 0) {
		$form = (array) rex_post('form', 'array', []);
		$staff_id = $form['staff_id'];
	}
	$staff = new Staff($staff_id, intval(rex_config::get("d2u_helper", "default_lang")));
	$staff->staff_id = $staff_id; // Ensure correct ID in case language has no object
	$staff->delete();
	
	$func = '';
}
// Change online status of staff
else if($func == 'changestatus') {
	$staff_id = $entry_id;
	$staff = new Staff($staff_id, intval(rex_config::get("d2u_helper", "default_lang")));
	$staff->staff_id = $staff_id; // Ensure correct ID in case language has no object
	$staff->changeStatus();
	
	header("Location: ". rex_url::currentBackendPage());
	exit;
}

// Form
if ($func == 'edit' || $func == 'add') {
?>
	<form action="<?php print rex_url::currentBackendPage(); ?>" method="post">
		<div class="panel panel-edit">
			<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_staff'); ?></div></header>
			<div class="panel-body">
				<input type="hidden" name="form[staff_id]" value="<?php echo $entry_id; ?>">
				<fieldset>
					<legend><?php echo rex_i18n::msg('d2u_helper_data_all_lang'); ?></legend>
					<div class="panel-body-wrapper slide">
						<?php
							$staff = new Staff($entry_id, intval(rex_config::get("d2u_helper", "default_lang")));
							$readonly = TRUE;
							if(rex::getUser()->isAdmin() || rex::getUser()->hasPerm('d2u_staff[edit_data]')) {
								$readonly = FALSE;
							}
							
							d2u_addon_backend_helper::form_input('d2u_helper_name', "form[name]", $staff->name, TRUE, $readonly);
							$options_gender = [
								'male' => rex_i18n::msg('d2u_staff_gender_male'),
								'female' => rex_i18n::msg('d2u_staff_gender_female'),
								'divers' => rex_i18n::msg('d2u_staff_gender_divers')
							];
							d2u_addon_backend_helper::form_select('d2u_staff_gender', 'form[gender]', $options_gender, [$staff->gender], 1, FALSE, $readonly);
							d2u_addon_backend_helper::form_mediafield('d2u_helper_picture', '1', $staff->picture, $readonly);
							d2u_addon_backend_helper::form_checkbox('d2u_helper_online_status', 'form[online_status]', 'online', $staff->online_status == "online", $readonly);
							$options = [0 => rex_i18n::msg('d2u_staff_no_link')];
							foreach(\D2U_Staff\Company::getAll() as $company) {
								$options[$company->company_id] = $company->name;
							}
							d2u_addon_backend_helper::form_select('d2u_staff_company', 'form[company_id]', $options, [$staff->company_id], 1, FALSE, $readonly);
							d2u_addon_backend_helper::form_linkfield('d2u_helper_article_id', 'article_id', $staff->article_id, intval(rex_config::get("d2u_helper", "default_lang")), $readonly);
							d2u_addon_backend_helper::form_input('header_priority', 'form[priority]', $staff->priority, TRUE, $readonly, 'number');
						?>
					</div>
				</fieldset>
				<?php
					foreach(rex_clang::getAll() as $rex_clang) {
						$staff = new Staff($entry_id, $rex_clang->getId());
						$required = $rex_clang->getId() === intval(rex_config::get("d2u_helper", "default_lang")) ? TRUE : FALSE;
						
						$readonly_lang = TRUE;
						if(rex::getUser()->isAdmin() || (rex::getUser()->hasPerm('d2u_staff[edit_lang]') && rex::getUser()->getComplexPerm('clang')->hasPerm($rex_clang->getId()))) {
							$readonly_lang = FALSE;
						}
				?>
					<fieldset>
						<legend><?php echo rex_i18n::msg('d2u_helper_text_lang') .' "'. $rex_clang->getName() .'"'; ?></legend>
						<div class="panel-body-wrapper slide">
							<?php
								if($rex_clang->getId() !== intval(rex_config::get("d2u_helper", "default_lang"))) {
									$options_translations = [];
									$options_translations["yes"] = rex_i18n::msg('d2u_helper_translation_needs_update');
									$options_translations["no"] = rex_i18n::msg('d2u_helper_translation_is_uptodate');
									$options_translations["delete"] = rex_i18n::msg('d2u_helper_translation_delete');
									d2u_addon_backend_helper::form_select('d2u_helper_translation', 'form[lang]['. $rex_clang->getId() .'][translation_needs_update]', $options_translations, [$staff->translation_needs_update], 1, FALSE, $readonly_lang);
								}
								else {
									print '<input type="hidden" name="form[lang]['. $rex_clang->getId() .'][translation_needs_update]" value="">';
								}
							?>
							<script>
								// Hide on document load
								$(document).ready(function() {
									toggleClangDetailsView(<?php print $rex_clang->getId(); ?>);
								});

								// Hide on selection change
								$("select[name='form[lang][<?php print $rex_clang->getId(); ?>][translation_needs_update]']").on('change', function(e) {
									toggleClangDetailsView(<?php print $rex_clang->getId(); ?>);
								});
							</script>
							<div id="details_clang_<?php print $rex_clang->getId(); ?>">
								<?php
									d2u_addon_backend_helper::form_input('d2u_staff_lang_name', "form[lang][". $rex_clang->getId() ."][lang_name]", $staff->lang_name, FALSE, $readonly_lang);
									d2u_addon_backend_helper::form_input('d2u_staff_area_of_responsibility', "form[lang][". $rex_clang->getId() ."][area_of_responsibility]", $staff->area_of_responsibility, FALSE, $readonly_lang);
									d2u_addon_backend_helper::form_input('d2u_staff_position', "form[lang][". $rex_clang->getId() ."][position]", $staff->position, FALSE, $readonly_lang);
									d2u_addon_backend_helper::form_textarea('d2u_staff_citation', "form[lang][". $rex_clang->getId() ."][citation]", $staff->citation, 5, FALSE, $readonly_lang, TRUE);
									d2u_addon_backend_helper::form_input('d2u_staff_knows_about', "form[lang][". $rex_clang->getId() ."][knows_about]", $staff->knows_about, FALSE, $readonly_lang);
								?>
							</div>
						</div>
					</fieldset>
				<?php
					}
				?>
			</div>
			<footer class="panel-footer">
				<div class="rex-form-panel-footer">
					<div class="btn-toolbar">
						<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="1"><?php echo rex_i18n::msg('form_save'); ?></button>
						<button class="btn btn-apply" type="submit" name="btn_apply" value="1"><?php echo rex_i18n::msg('form_apply'); ?></button>
						<button class="btn btn-abort" type="submit" name="btn_abort" formnovalidate="formnovalidate" value="1"><?php echo rex_i18n::msg('form_abort'); ?></button>
						<?php
							if(rex::getUser()->isAdmin() || rex::getUser()->hasPerm('d2u_staff[edit_data]')) {
								print '<button class="btn btn-delete" type="submit" name="btn_delete" formnovalidate="formnovalidate" data-confirm="'. rex_i18n::msg('form_delete') .'?" value="1">'. rex_i18n::msg('form_delete') .'</button>';
							}
						?>
					</div>
				</div>
			</footer>
		</div>
	</form>
	<br>
	<?php
		print d2u_addon_backend_helper::getCSS();
		print d2u_addon_backend_helper::getJS();
}

if ($func == '') {
	$query = 'SELECT staff.staff_id, name, position, citation, priority, online_status '
		. 'FROM '. rex::getTablePrefix() .'d2u_staff AS staff '
		. 'LEFT JOIN '. rex::getTablePrefix() .'d2u_staff_lang AS lang '
			. 'ON staff.staff_id = lang.staff_id AND lang.clang_id = '. rex_config::get("d2u_helper", "default_lang", rex_clang::getStartId()) .' '
		.'ORDER BY name ASC';
    $list = rex_list::factory($query, 1000);

    $list->addTableAttribute('class', 'table-striped table-hover');

    $tdIcon = '<i class="rex-icon fa-user-circle"></i>';
  	$thIcon = "";
	if(rex::getUser()->isAdmin() || rex::getUser()->hasPerm('d2u_staff[edit_data]')) {
		$thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '" title="' . rex_i18n::msg('add') . '"><i class="rex-icon rex-icon-add-module"></i></a>';
	}
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnParams($thIcon, ['func' => 'edit', 'entry_id' => '###staff_id###']);

    $list->setColumnLabel('staff_id', rex_i18n::msg('id'));
    $list->setColumnLayout('staff_id', ['<th class="rex-table-id">###VALUE###</th>', '<td class="rex-table-id">###VALUE###</td>']);

    $list->setColumnLabel('name', rex_i18n::msg('d2u_helper_name'));
    $list->setColumnParams('name', ['func' => 'edit', 'entry_id' => '###staff_id###']);

    $list->setColumnLabel('position', rex_i18n::msg('d2u_staff_position'));
   
    $list->setColumnLabel('citation', rex_i18n::msg('d2u_staff_citation'));
	$list->setColumnFormat('citation', 'custom', function ($params) {
		$list_params = $params['list'];
		$citation = stripslashes(htmlspecialchars_decode($list_params->getValue('citation')));
		if(strlen($citation) > 50) {
			$citation = substr($citation, 0, 50) . "...";
		}
		return $citation;
	});
   
    $list->setColumnLabel('priority', rex_i18n::msg('header_priority'));

    $list->addColumn(rex_i18n::msg('module_functions'), '<i class="rex-icon rex-icon-edit"></i> ' . rex_i18n::msg('edit'));
    $list->setColumnLayout(rex_i18n::msg('module_functions'), ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams(rex_i18n::msg('module_functions'), ['func' => 'edit', 'entry_id' => '###staff_id###']);

	$list->removeColumn('online_status');
	if(rex::getUser()->isAdmin() || rex::getUser()->hasPerm('d2u_staff[edit_data]')) {
		$list->addColumn(rex_i18n::msg('status_online'), '<a class="rex-###online_status###" href="' . rex_url::currentBackendPage(['func' => 'changestatus']) . '&entry_id=###staff_id###"><i class="rex-icon rex-icon-###online_status###"></i> ###online_status###</a>');
		$list->setColumnLayout(rex_i18n::msg('status_online'), ['', '<td class="rex-table-action">###VALUE###</td>']);

		$list->addColumn(rex_i18n::msg('delete_module'), '<i class="rex-icon rex-icon-delete"></i> ' . rex_i18n::msg('delete'));
		$list->setColumnLayout(rex_i18n::msg('delete_module'), ['', '<td class="rex-table-action">###VALUE###</td>']);
		$list->setColumnParams(rex_i18n::msg('delete_module'), ['func' => 'delete', 'entry_id' => '###staff_id###']);
		$list->addLinkAttribute(rex_i18n::msg('delete_module'), 'data-confirm', rex_i18n::msg('d2u_helper_confirm_delete'));
	}

    $list->setNoRowsMessage(rex_i18n::msg('d2u_staff_no_staff_found'));

    $fragment = new rex_fragment();
    $fragment->setVar('title', rex_i18n::msg('d2u_staff'), false);
    $fragment->setVar('content', $list->get(), false);
    echo $fragment->parse('core/page/section.php');
}