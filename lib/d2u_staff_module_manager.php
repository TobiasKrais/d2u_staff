<?php
/**
 * Class managing modules published by www.design-to-use.de.
 *
 * @author Tobias Krais
 */
class D2UStaffModules
{
    /**
     * Get modules offered by this addon.
     * @return D2UModule[] Modules offered by this addon
     */
    public static function getModules()
    {
        $modules = [];
        $modules[] = new D2UModule('22-1',
            'D2U Mitarbeiter - Liste',
            3);
        $modules[] = new D2UModule('22-2',
            'D2U Mitarbeiter - Autorenbox Detailinfo',
            1);
        $modules[] = new D2UModule('22-3',
            'D2U Mitarbeiter - Autorenbox Kurzinfo',
            1);
        return $modules;
    }
}
