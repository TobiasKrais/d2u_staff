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
            'D2U Mitarbeiter - Liste (BS4, deprecated)',
            6);
        $modules[] = new \TobiasKrais\D2UHelper\Module('22-2',
            'D2U Mitarbeiter - Autorenbox Detailinfo (BS4, deprecated)',
            3);
        $modules[] = new \TobiasKrais\D2UHelper\Module('22-3',
            'D2U Mitarbeiter - Autorenbox Kurzinfo (BS4, deprecated)',
            3);
        $modules[] = new \TobiasKrais\D2UHelper\Module('22-4',
            'D2U Mitarbeiter - Liste (BS5)',
            1);
        $modules[] = new \TobiasKrais\D2UHelper\Module('22-5',
            'D2U Mitarbeiter - Autorenbox Detailinfo (BS5)',
            1);
        $modules[] = new \TobiasKrais\D2UHelper\Module('22-6',
            'D2U Mitarbeiter - Autorenbox Kurzinfo (BS5)',
            1);
        return $modules;
    }
}
