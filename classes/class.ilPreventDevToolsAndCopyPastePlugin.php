<?php

/**
 * Hauptklasse des UIHook-Plugins.
 * Nutzt ilSetting (über ilPreventDevToolsAndCopyPasteConfig) zum Speichern der Plugin-Einstellungen.
 */
class ilPreventDevToolsAndCopyPastePlugin extends ilUserInterfaceHookPlugin
{
    /**
     * @var ilPreventDevToolsAndCopyPasteConfig
     */
    protected $config;

    /**
     * Konstruktor: Wird vom Plugin-Manager mit 3 Parametern aufgerufen.
     */
    public function __construct(
        ilDBInterface $db,
        ilComponentRepositoryWrite $component_repository,
        string $plugin_id
    ) {
        parent::__construct($db, $component_repository, $plugin_id);
        require_once __DIR__ . "/class.ilPreventDevToolsAndCopyPasteConfig.php";
        $this->config = new ilPreventDevToolsAndCopyPasteConfig("prvntdvcpp");
    }

    public function getPluginName(): string
    {
        return "PreventDevToolsAndCopyPaste";
    }

    /**
     * modifyGUI: Liest die Einstellungen (global blockieren oder anhand von ref_ids)
     * und bindet das prevent.js ein, wenn die Bedingungen erfüllt sind.
     */
    public function modifyGUI(string $a_comp, string $a_part, array $a_par = []): void
    {
        global $tpl;
        if (!$tpl) {
            return;
        }

        // Einstellungen aus Config-Objekt
        $global_val = $this->config->get("global_block");  // "1" oder ""
        $refid_list = $this->config->get("refid_list");       // z. B. "24093,24100"
        $refid_array = [];
        if ($refid_list !== "") {
            $refid_array = array_map('trim', explode(',', $refid_list));
        }

        if ($global_val === "1") {
            $tpl->addJavaScript($this->getJsPath());
        } else {
            $current_ref_id = (int) ($_GET['ref_id'] ?? 0);
            if ($current_ref_id > 0 && in_array($current_ref_id, $refid_array)) {
                $tpl->addJavaScript($this->getJsPath());
            }
        }
    }

    /**
     * Gibt den Pfad zu prevent.js zurück.
     */
    private function getJsPath(): string
    {
        return "./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/"
            . $this->getPluginName() . "/js/prevent.js";
    }

    /**
     * Gibt an, dass dieses Plugin Konfiguration besitzt.
     */
    public function hasConfiguration(): bool
    {
        return true;
    }

    /**
     * Liefert das Config-Objekt.
     */
    public function getConfig(): ilPreventDevToolsAndCopyPasteConfig
    {
        return $this->config;
    }
}
