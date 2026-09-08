<?php

if (rex::isBackend() && is_object(rex::getUser())) {
    rex_perm::register('d2u_staff[]', rex_i18n::msg('d2u_staff_rights'));
    rex_perm::register('d2u_staff[edit_data]', rex_i18n::msg('d2u_staff_rights_edit_data'), rex_perm::OPTIONS);
    rex_perm::register('d2u_staff[edit_lang]', rex_i18n::msg('d2u_staff_rights_edit_lang'), rex_perm::OPTIONS);
    rex_perm::register('d2u_staff[settings]', rex_i18n::msg('d2u_staff_rights_settings'), rex_perm::OPTIONS);
}

if (rex::isBackend()) {
    rex_extension::register('CLANG_DELETED', rex_d2u_staff_clang_deleted(...));
    rex_extension::register('D2U_HELPER_TRANSLATION_LIST', rex_d2u_staff_translation_list(...));
    rex_extension::register('D2U_HELPER_TRANSLATE_OBJECT', rex_d2u_staff_translate_object(...));
    rex_extension::register('MEDIA_IS_IN_USE', rex_d2u_staff_media_is_in_use(...));
}

/**
 * Deletes language specific configurations and objects.
 * @param rex_extension_point<array<string>> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_staff_clang_deleted(rex_extension_point $ep)
{
    $warning = $ep->getSubject();
    $params = $ep->getParams();
    $clang_id = $params['id'];

    // Delete
    $staff = TobiasKrais\D2UStaff\Staff::getAll($clang_id, false);
    foreach ($staff as $cur_staff) {
        $cur_staff->delete(false);
    }

    return $warning;
}

/**
 * Checks if media is used by this addon.
 * @param rex_extension_point<array<string>> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_staff_media_is_in_use(rex_extension_point $ep)
{
    $warning = $ep->getSubject();
    $params = $ep->getParams();
    $filename = (string) $params['filename'];

    // Staff
    $sql_staff = rex_sql::factory();
    $sql_staff->setQuery('SELECT staff_id, name FROM `' . rex::getTablePrefix() . 'd2u_staff` '
        .'WHERE picture = :filename', [':filename' => $filename]);

    // Prepare warnings
    // Staff
    for ($i = 0; $i < $sql_staff->getRows(); ++$i) {
        $message = '<a href="javascript:openPage(\'index.php?page=d2u_staff/staff&func=edit&entry_id='.
            $sql_staff->getValue('staff_id') .'\')">'. rex_i18n::msg('d2u_staff_rights') .' - '. rex_i18n::msg('d2u_staff_staff') .': '. rex_escape($sql_staff->getValue('name')) .'</a>';
        if (!in_array($message, $warning, true)) {
            $warning[] = $message;
        }
        $sql_staff->next();
    }

    return $warning;
}

/**
 * Addon translation list.
 * @param rex_extension_point<array<string>> $ep Redaxo extension point
 * @return array<array<string, array<int, array<string, string>>|string>|string> Addon translation list
 */
function rex_d2u_staff_translation_list(rex_extension_point $ep) {
    $params = $ep->getParams();
    $source_clang_id = (int) $params['source_clang_id'];
    $target_clang_id = (int) $params['target_clang_id'];
    $filter_type = (string) $params['filter_type'];

    $list = $ep->getSubject();
    $list_entry = [
        'addon_name' => rex_i18n::msg('d2u_staff'),
        'pages' => []
    ];

    $staff_members = TobiasKrais\D2UStaff\Staff::getTranslationHelperObjects($target_clang_id, $filter_type);
    if (count($staff_members) > 0) {
        $html_staff = '<ul>';
        foreach ($staff_members as $staff_member) {
            if ('' === $staff_member->name) {
                $staff_member = new TobiasKrais\D2UStaff\Staff($staff_member->staff_id, $source_clang_id);
            }
            $html_staff .= \TobiasKrais\D2UHelper\BackendHelper::getTranslationItem('d2u_staff', 'staff', $staff_member->staff_id, $staff_member->name, rex_url::backendPage('d2u_staff/staff', ['entry_id' => $staff_member->staff_id, 'func' => 'edit']));
        }
        $html_staff .= '</ul>';
        
        $list_entry['pages'][] = [
            'title' => rex_i18n::msg('d2u_staff'),
            'icon' => 'fa-user-circle',
            'html' => $html_staff
        ];
    }

    $list[] = $list_entry;

    return $list;
}

/**
 * Translate a single d2u_staff object with AI (D2U_HELPER_TRANSLATE_OBJECT).
 * @param rex_extension_point<array<string,mixed>> $ep Redaxo extension point
 * @return array<string,mixed> Result array with success, name and message
 */
function rex_d2u_staff_translate_object(rex_extension_point $ep) {
    $params = $ep->getParams();
    if ('d2u_staff' !== ($params['addon'] ?? '')) {
        return $ep->getSubject();
    }

    $type = (string) ($params['type'] ?? '');
    $id = (int) ($params['id'] ?? 0);
    $source_clang_id = (int) ($params['source_clang_id'] ?? 0);
    $target_clang_id = (int) ($params['target_clang_id'] ?? 0);

    if ('staff' !== $type) {
        return $ep->getSubject();
    }

    // The Staff constructor only returns a row when a translation for that clang
    // exists. For missing translations load the source and retarget it.
    $staff = new \TobiasKrais\D2UStaff\Staff($id, $target_clang_id);
    if ($staff->staff_id <= 0) {
        $staff = new \TobiasKrais\D2UStaff\Staff($id, $source_clang_id);
        $staff->clang_id = $target_clang_id;
    }
    if ($staff->staff_id <= 0) {
        return ['success' => false, 'name' => '', 'message' => rex_i18n::msg('d2u_helper_translations_ai_error')];
    }

    $success = $staff->translateFrom($source_clang_id);

    return [
        'success' => $success,
        'name' => $staff->name,
        'message' => $success ? '' : rex_i18n::msg('d2u_helper_translations_ai_error'),
    ];
}