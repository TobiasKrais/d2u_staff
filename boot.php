<?php

if (rex::isBackend() && is_object(rex::getUser())) {
    rex_perm::register('d2u_staff[]', rex_i18n::msg('d2u_staff_rights'));
    rex_perm::register('d2u_staff[edit_data]', rex_i18n::msg('d2u_staff_rights_edit_data'), rex_perm::OPTIONS);
    rex_perm::register('d2u_staff[edit_lang]', rex_i18n::msg('d2u_staff_rights_edit_lang'), rex_perm::OPTIONS);
    rex_perm::register('d2u_staff[settings]', rex_i18n::msg('d2u_staff_rights_settings'), rex_perm::OPTIONS);
}

if (rex::isBackend()) {
    rex_extension::register('CLANG_DELETED', 'rex_d2u_staff_clang_deleted');
    rex_extension::register('MEDIA_IS_IN_USE', 'rex_d2u_staff_media_is_in_use');
}

/**
 * Deletes language specific configurations and objects.
 * @param rex_extension_point<string> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_staff_clang_deleted(rex_extension_point $ep)
{
    /** @var array<string> $warning */
    $warning = $ep->getSubject();
    $params = $ep->getParams();
    $clang_id = $params['id'];

    // Delete
    $staff = Staff::getAll($clang_id, false);
    foreach ($staff as $cur_staff) {
        $cur_staff->delete(false);
    }

    return $warning;
}

/**
 * Checks if media is used by this addon.
 * @param rex_extension_point<string> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_staff_media_is_in_use(rex_extension_point $ep)
{
    /** @var array<string> $warning */
    $warning = $ep->getSubject();
    $params = $ep->getParams();
    $filename = addslashes($params['filename']);

    // Staff
    $sql_staff = rex_sql::factory();
    $sql_staff->setQuery('SELECT staff_id, name FROM `' . rex::getTablePrefix() . 'd2u_staff` '
        .'WHERE picture = "'. $filename .'"');

    // Prepare warnings
    // Staff
    for ($i = 0; $i < $sql_staff->getRows(); ++$i) {
        $message = '<a href="javascript:openPage(\'index.php?page=d2u_staff/staff&func=edit&entry_id='.
            $sql_staff->getValue('staff_id') .'\')">'. rex_i18n::msg('d2u_staff_rights') .' - '. rex_i18n::msg('d2u_staff_staff') .': '. $sql_staff->getValue('name') .'</a>';
        if (!in_array($message, $warning, true)) {
            $warning[] = $message;
        }
        $sql_staff->next();
    }

    return $warning;
}
