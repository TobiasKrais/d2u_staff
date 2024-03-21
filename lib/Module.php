<?php
namespace TobiasKrais\D2UStaff;

/**
 * Class managing modules published by www.design-to-use.de.
 *
 * @author Tobias Krais
 */
class Module
{
    /**
     * Get modules offered by this addon.
     * @return \TobiasKrais\D2UHelper\Module[] Modules offered by this addon
     */
    public static function getModules()
    {
        $modules = [];
        $modules[] = new \TobiasKrais\D2UHelper\Module('22-1',
            'D2U Mitarbeiter - Liste',
            4);
        $modules[] = new \TobiasKrais\D2UHelper\Module('22-2',
            'D2U Mitarbeiter - Autorenbox Detailinfo',
            2);
        $modules[] = new \TobiasKrais\D2UHelper\Module('22-3',
            'D2U Mitarbeiter - Autorenbox Kurzinfo',
            2);
        return $modules;
    }
}
