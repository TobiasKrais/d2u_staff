<?php

namespace TobiasKrais\D2UStaff;

use rex;
use rex_sql;

/**
 * Staff class.
 */
class Staff implements \TobiasKrais\D2UHelper\ITranslationHelper
{
    /** @var int Staff database ID */
    public int $staff_id = 0;

    /** @var int Redaxo Language ID */
    public int $clang_id = 0;

    /** @var int Company ID */
    public int $company_id = 0;

    /** @var int Redaxo article ID with detailed information */
    public int $article_id = 0;

    /** @var string Citation */
    public string $citation = '';

    /** @var string Name of countries staff is responsible for */
    public string $area_of_responsibility = '';

    /** @var string name */
    public string $name = '';

    /** @var string gender */
    public string $gender = '';

    /** @var string Language specific name */
    public string $lang_name = '';

    /** @var string special knowledge */
    public string $knows_about = '';

    /** @var string online status, either "online" or "offline" */
    public string $online_status = 'offline';

    /** @var string Picture */
    public string $picture = '';

    /** @var string Postion in company */
    public string $position = '';

    /** @var int priority */
    public int $priority = 0;

    /** @var string "yes" if translation needs update */
    public string $translation_needs_update = 'delete';

    /**
     * Constructor.
     * @param int $staff_id staff ID
     * @param int $clang_id Redaxo language ID
     */
    public function __construct($staff_id, $clang_id)
    {
        $this->clang_id = $clang_id;
        $query = 'SELECT * FROM '. rex::getTablePrefix() .'d2u_staff AS staff '
                .'LEFT JOIN '. rex::getTablePrefix() .'d2u_staff_lang AS lang '
                    .'ON staff.staff_id = lang.staff_id '
                .'WHERE staff.staff_id = '. $staff_id .' '
                    .'AND clang_id = '. $clang_id .' ';
        $result = rex_sql::factory();
        $result->setQuery($query);
        $num_rows = $result->getRows();

        if ($num_rows > 0) {
            $this->staff_id = $result->getValue('staff_id');
            $this->company_id = $result->getValue('company_id');
            $this->article_id = $result->getValue('article_id');
            $this->citation = stripslashes(htmlspecialchars_decode($result->getValue('citation')));
            $this->area_of_responsibility = $result->getValue('area_of_responsibility');
            $this->name = stripslashes($result->getValue('name'));
            $this->lang_name = stripslashes($result->getValue('lang_name'));
            $this->gender = stripslashes($result->getValue('gender'));
            $this->knows_about = stripslashes($result->getValue('knows_about'));
            $this->online_status = $result->getValue('online_status');
            if ('' != $result->getValue('picture')) {
                $this->picture = $result->getValue('picture');
            }
            $this->position = $result->getValue('position');
            $this->priority = $result->getValue('priority');
            $this->translation_needs_update = $result->getValue('translation_needs_update');
        }
    }

    /**
     * Changes the online status of this object.
     */
    public function changeStatus(): void
    {
        if ('online' === $this->online_status) {
            if ($this->staff_id > 0) {
                $query = 'UPDATE '. rex::getTablePrefix() .'d2u_staff '
                    ."SET online_status = 'offline' "
                    .'WHERE staff_id = '. $this->staff_id;
                $result = rex_sql::factory();
                $result->setQuery($query);
            }
            $this->online_status = 'offline';
        } else {
            if ($this->staff_id > 0) {
                $query = 'UPDATE '. rex::getTablePrefix() .'d2u_staff '
                    ."SET online_status = 'online' "
                    .'WHERE staff_id = '. $this->staff_id;
                $result = rex_sql::factory();
                $result->setQuery($query);
            }
            $this->online_status = 'online';
        }
    }

    /**
     * Deletes the object in all languages.
     * @param bool $delete_all If true, all translations and main object are deleted. If
     * false, only this translation will be deleted.
     */
    public function delete($delete_all = true): void
    {
        $query_lang = 'DELETE FROM '. rex::getTablePrefix() .'d2u_staff_lang '
            .'WHERE staff_id = '. $this->staff_id
            . ($delete_all ? '' : ' AND clang_id = '. $this->clang_id);
        $result_lang = rex_sql::factory();
        $result_lang->setQuery($query_lang);

        // If no more lang objects are available, delete
        $query_main = 'SELECT * FROM '. rex::getTablePrefix() .'d2u_staff_lang '
            .'WHERE staff_id = '. $this->staff_id;
        $result_main = rex_sql::factory();
        $result_main->setQuery($query_main);
        if (0 === $result_main->getRows()) {
            $query = 'DELETE FROM '. rex::getTablePrefix() .'d2u_staff '
                .'WHERE staff_id = '. $this->staff_id;
            $result = rex_sql::factory();
            $result->setQuery($query);

            // reset priorities
            $this->setPriority(true);
        }
    }

    /**
     * Gets all staffs.
     * @param int $clang_id Redaxo language ID
     * @param bool $online_only if true, only online objects are returned
     * @return Staff[] array with Staff objects
     */
    public static function getAll($clang_id, $online_only = true)
    {
        $query = 'SELECT lang.staff_id FROM '. rex::getTablePrefix() .'d2u_staff_lang AS lang '
            .'LEFT JOIN '. rex::getTablePrefix() .'d2u_staff AS staff '
                .'ON lang.staff_id = staff.staff_id '
            .'WHERE clang_id = '. $clang_id .' ';
        if ($online_only) {
            $query .= 'AND online_status = "online" ';
        }
        $query .= 'ORDER BY priority ASC';
        $result = rex_sql::factory();
        $result->setQuery($query);
        $num_rows = $result->getRows();

        $staffs = [];
        for ($i = 0; $i < $num_rows; ++$i) {
            $staff = new self($result->getValue('staff_id'), $clang_id);
            $staffs[$staff->staff_id] = $staff;
            $result->next();
        }

        return $staffs;
    }

    /**
     * Gets all staffs with citation.
     * @param int $clang_id Redaxo language ID
     * @param bool $online_only if true, only online objects are returned
     * @return Staff[] array with Staff objects
     */
    public static function getAllWithCitation($clang_id, $online_only = true)
    {
        $query = 'SELECT lang.staff_id FROM '. rex::getTablePrefix() .'d2u_staff_lang AS lang '
            .'LEFT JOIN '. rex::getTablePrefix() .'d2u_staff AS staff '
                .'ON lang.staff_id = staff.staff_id '
            .'WHERE clang_id = '. $clang_id .' AND citation != "" ';
        if ($online_only) {
            $query .= 'AND online_status = "online" ';
        }
        $query .= 'ORDER BY priority ASC';
        $result = rex_sql::factory();
        $result->setQuery($query);
        $num_rows = $result->getRows();

        $staffs = [];
        for ($i = 0; $i < $num_rows; ++$i) {
            $staff = new self($result->getValue('staff_id'), $clang_id);
            $staffs[$staff->staff_id] = $staff;
            $result->next();
        }

        return $staffs;
    }

    /**
     * Get objects concerning translation updates.
     * @param int $clang_id Redaxo language ID
     * @param string $type 'update' or 'missing'
     * @return Staff[] array with Staff objects
     */
    public static function getTranslationHelperObjects($clang_id, $type)
    {
        $query = 'SELECT lang.staff_id FROM '. \rex::getTablePrefix() .'d2u_staff_lang AS lang '
                .'LEFT JOIN '. \rex::getTablePrefix() .'d2u_staff AS main '
                    .'ON lang.staff_id = main.staff_id '
                .'WHERE clang_id = '. $clang_id ." AND translation_needs_update = 'yes' "
                .'ORDER BY name';
        if ('missing' === $type) {
            $query = 'SELECT main.staff_id FROM '. \rex::getTablePrefix() .'d2u_staff AS main '
                    .'LEFT JOIN '. \rex::getTablePrefix() .'d2u_staff_lang AS target_lang '
                        .'ON main.staff_id = target_lang.staff_id AND target_lang.clang_id = '. $clang_id .' '
                    .'LEFT JOIN '. \rex::getTablePrefix() .'d2u_staff_lang AS default_lang '
                        .'ON main.staff_id = default_lang.staff_id AND default_lang.clang_id = '. \rex_config::get('d2u_helper', 'default_lang') .' '
                    .'WHERE target_lang.staff_id IS NULL '
                    .'ORDER BY name';
            $clang_id = \rex_config::get('d2u_helper', 'default_lang');
        }
        $result = \rex_sql::factory();
        $result->setQuery($query);

        $objects = [];
        for ($i = 0; $i < $result->getRows(); ++$i) {
            $objects[] = new self($result->getValue('staff_id'), $clang_id);
            $result->next();
        }

        return $objects;
    }

    /**
     * Updates or inserts the object into database.
     * @return bool true if successful
     */
    public function save()
    {
        $error = false;

        // Save the not language specific part
        $pre_save_staff = new self($this->staff_id, $this->clang_id);

        // save priority, but only if new or changed
        if ($this->priority !== $pre_save_staff->priority || 0 === $this->staff_id) {
            $this->setPriority();
        }

        if (0 === $this->staff_id || $pre_save_staff != $this) {
            $query = rex::getTablePrefix() .'d2u_staff SET '
                    .'article_id = '. ($this->article_id ?: 0) .', '
                    ."online_status = '". $this->online_status ."', "
                    ."picture = '". $this->picture ."', "
                    ."priority = ". $this->priority .", "
                    ."name = '". addslashes($this->name) ."', "
                    ."gender = '". addslashes($this->gender) ."', "
                    .'company_id = '. $this->company_id;

            if (0 === $this->staff_id) {
                $query = 'INSERT INTO '. $query;
            } else {
                $query = 'UPDATE '. $query .' WHERE staff_id = '. $this->staff_id;
            }

            $result = rex_sql::factory();
            $result->setQuery($query);
            if (0 === $this->staff_id) {
                $this->staff_id = (int) $result->getLastId();
                $error = $result->hasError();
            }
        }

        if (!$error) {
            // Save the language specific part
            $pre_save_staff = new self($this->staff_id, $this->clang_id);
            if ($pre_save_staff != $this) {
                $query = 'REPLACE INTO '. rex::getTablePrefix() .'d2u_staff_lang SET '
                        .'staff_id = '. $this->staff_id .', '
                        .'clang_id = '. $this->clang_id .', '
                        ."lang_name = '". addslashes($this->lang_name) ."', "
                        ."knows_about = '". addslashes($this->knows_about) ."', "
                        ."area_of_responsibility = '". $this->area_of_responsibility ."', "
                        ."citation = '". addslashes(htmlspecialchars($this->citation)) ."', "
                        ."position = '". $this->position ."', "
                        ."translation_needs_update = '". $this->translation_needs_update ."' ";

                $result = rex_sql::factory();
                $result->setQuery($query);
                $error = $result->hasError();
            }
        }

        return $error;
    }

    /**
     * Reassigns priorities in database.
     * @param bool $delete Reorder priority after deletion
     */
    private function setPriority($delete = false): void
    {
        // Pull prios from database
        $query = 'SELECT staff_id, priority FROM '. rex::getTablePrefix() .'d2u_staff '
            .'WHERE staff_id <> '. $this->staff_id .' ORDER BY priority';
        $result = rex_sql::factory();
        $result->setQuery($query);

        // When priority is too small, set at beginning
        if ($this->priority <= 0) {
            $this->priority = 1;
        }

        // When prio is too high or was deleted, simply add at end
        if ($this->priority > $result->getRows() || $delete) {
            $this->priority = $result->getRows() + 1;
        }

        $staffs = [];
        for ($i = 0; $i < $result->getRows(); ++$i) {
            $staffs[$result->getValue('priority')] = $result->getValue('staff_id');
            $result->next();
        }
        array_splice($staffs, $this->priority - 1, 0, [$this->staff_id]);

        // Save all prios
        foreach ($staffs as $prio => $staff_id) {
            $query = 'UPDATE '. rex::getTablePrefix() .'d2u_staff '
                    .'SET priority = '. ((int) $prio + 1) .' ' // +1 because array_splice recounts at zero
                    .'WHERE staff_id = '. $staff_id;
            $result = rex_sql::factory();
            $result->setQuery($query);
        }
    }
}
