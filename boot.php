<?php
if(rex::isBackend() && is_object(rex::getUser())) {
	rex_perm::register('d2u_staff[]', rex_i18n::msg('d2u_staff_rights'));
	rex_perm::register('d2u_staff[edit_data]', rex_i18n::msg('d2u_staff_rights_edit_data'), rex_perm::OPTIONS);
	rex_perm::register('d2u_staff[edit_lang]', rex_i18n::msg('d2u_staff_rights_edit_lang'), rex_perm::OPTIONS);
	rex_perm::register('d2u_staff[settings]', rex_i18n::msg('d2u_staff_rights_settings'), rex_perm::OPTIONS);
}

if(rex::isBackend()) {
	rex_extension::register('CLANG_DELETED', 'rex_d2u_staff_clang_deleted');
	rex_extension::register('MEDIA_IS_IN_USE', 'rex_d2u_staff_media_is_in_use');
}

/**
 * Deletes language specific configurations and objects
 * @param rex_extension_point $ep Redaxo extension point
 * @return string[] Warning message as array
 */
function rex_d2u_staff_clang_deleted(rex_extension_point $ep) {
	$warning = $ep->getSubject();
	$params = $ep->getParams();
	$clang_id = $params['id'];

	// Delete
	$staff = Staff::getAll($clang_id, FALSE);
	foreach ($staff as $cur_staff) {
		$cur_staff->delete(FALSE);
	}
	
	// Delete language settings
	if(rex_config::has('d2u_staff', 'lang_replacement_'. $clang_id)) {
		rex_config::remove('d2u_staff', 'lang_replacement_'. $clang_id);
	}
	// Delete language replacements
	d2u_staff_lang_helper::factory()->uninstall($clang_id);

	return $warning;
}

/**
 * Checks if media is used by this addon
 * @param rex_extension_point $ep Redaxo extension point
 * @return string[] Warning message as array
 */
function rex_d2u_staff_media_is_in_use(rex_extension_point $ep) {
	$warning = $ep->getSubject();
	$params = $ep->getParams();
	$filename = addslashes($params['filename']);

	// Staff
	$sql_history = rex_sql::factory();
	$sql_history->setQuery('SELECT staff_id, name FROM `' . rex::getTablePrefix() . 'd2u_staff` '
		.'WHERE picture = "'. $filename .'"');  

	// Prepare warnings
	// Staff
	for($i = 0; $i < $sql_history->getRows(); $i++) {
		$message = '<a href="javascript:openPage(\'index.php?page=d2u_staff/staff&func=edit&entry_id='.
			$sql_history->getValue('staff_id') .'\')">'. rex_i18n::msg('d2u_staff_rights') ." - ". rex_i18n::msg('d2u_staff_history') .': '. $sql_history->getValue('name') .'</a>';
		if(!in_array($message, $warning)) {
			$warning[] = $message;
		}
    }
	
	return $warning;
}