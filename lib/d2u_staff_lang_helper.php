<?php
/**
 * Offers helper functions for language issues.
 */
class d2u_staff_lang_helper extends \D2U_Helper\ALangHelper
{
    /**
     * @var array<string,string> Array with english replacements. Key is the wildcard,
     * value the replacement.
     */
    public $replacements_english = [
        'd2u_staff_by' => 'by',
        'd2u_staff_published' => 'Last updated on',
    ];

    /**
     * @var array<string,string> Array with german replacements. Key is the wildcard,
     * value the replacement.
     */
    protected array $replacements_german = [
        'd2u_staff_by' => 'von',
        'd2u_staff_published' => 'Letztes Update am',
    ];

    /**
     * @var array<string,string> Array with french replacements. Key is the wildcard,
     * value the replacement.
     */
    protected array $replacements_french = [
        'd2u_staff_by' => 'par',
        'd2u_staff_published' => 'Dernière mise à jour le',
    ];

    /**
     * @var array<string,string> Array with spanish replacements. Key is the wildcard,
     * value the replacement.
     */
    protected array $replacements_spanish = [
        'd2u_staff_by' => 'por',
        'd2u_staff_published' => 'Última actualización el',
    ];

    /**
     * Factory method.
     * @return d2u_immo_lang_helper Object
     */
    public static function factory()
    {
        return new self();
    }

    /**
     * Installs the replacement table for this addon.
     */
    public function install(): void
    {
        foreach ($this->replacements_english as $key => $value) {
            foreach (rex_clang::getAllIds() as $clang_id) {
                $lang_replacement = rex_config::get('d2u_staff', 'lang_replacement_'. $clang_id, '');

                // Load values for input
                if ('french' === $lang_replacement && isset($this->replacements_french) && isset($this->replacements_french[$key])) {
                    $value = $this->replacements_french[$key];
                } elseif ('german' === $lang_replacement && isset($this->replacements_german) && isset($this->replacements_german[$key])) {
                    $value = $this->replacements_german[$key];
                } elseif ('spanish' === $lang_replacement && isset($this->replacements_spanish) && isset($this->replacements_spanish[$key])) {
                    $value = $this->replacements_spanish[$key];
                } else {
                    $value = $this->replacements_english[$key];
                }

                $overwrite = 'true' === rex_config::get('d2u_staff', 'lang_wildcard_overwrite', false) ? true : false;
                parent::saveValue($key, $value, $clang_id, $overwrite);
            }
        }
    }
}
