<?php

/**
 * Hauptklasse des UIHook-Plugins für ILIAS 7.
 * Speichert die Plugin-Einstellungen über ein eigenes Config‑Objekt,
 * das auf ilSetting basiert.
 */
class ilPreventDevToolsAndCopyPastePlugin extends ilUserInterfaceHookPlugin
{
    /**
     * @var ilPreventDevToolsAndCopyPasteConfig
     */
    protected $config;

    /**
     * Konstruktor
     * Ermöglicht auch eine parameterlose Instanziierung.
     *
     * Falls nicht alle Parameter übergeben werden, wird einfach der parameterlose Eltern-Konstruktor aufgerufen.
     */
    public function __construct($db = null, $component_repository = null, $plugin_id = null)
    {
        if ($db === null || $component_repository === null || $plugin_id === null) {
            parent::__construct();
        } else {
            parent::__construct($db, $component_repository, $plugin_id);
        }
        // Stelle sicher, dass das Config-Objekt auch existiert:
        if (!$this->config) {
            require_once __DIR__ . "/class.ilPreventDevToolsAndCopyPasteConfig.php";
            $this->config = new ilPreventDevToolsAndCopyPasteConfig("prvntdvcpp");
        }
    }

    public function getPluginName()
    {
        return "PreventDevToolsAndCopyPaste";
    }

 

/**
 * modifyGUI: Liest die Einstellungen (global blockieren oder anhand von ref_ids)
 * und bindet prevent.js ein, wenn entweder global aktiviert ist
 * oder wenn die aktuelle ref_id in der Liste steht **und** ein active_id vorhanden ist.
 */
public function modifyGUI($a_comp, $a_part, $a_par = array())
{
    global $tpl;
    if (!$tpl) {
        return;
    }

    // Einstellungen aus dem Config‑Objekt holen
    $global_val = $this->config->get("global_block");  // "1" oder ""
    $refid_list = $this->config->get("refid_list");       // z. B. "24093,24100"
    $refid_array = array();
    if (trim($refid_list) != "") {
        $refid_array = array_map("trim", explode(",", $refid_list));
    }

    // Prüfe zusätzlich den active_id-Parameter
    $active_id = (int)(isset($_GET["active_id"]) ? $_GET["active_id"] : 0);

    // Wenn global aktiviert, immer laden
    if ($global_val === "1") {
        $tpl->addJavaScript($this->getJsPath());
    } else {
        // Sonst: Nur laden, wenn active_id vorhanden und ref_id in der Liste ist
        $current_ref_id = (int)(isset($_GET["ref_id"]) ? $_GET["ref_id"] : 0);
        if ($active_id > 0 && $current_ref_id > 0 && in_array($current_ref_id, $refid_array)) {
            $tpl->addJavaScript($this->getJsPath());
        }
    }
}

    /**
     * Liefert den Pfad zu prevent.js.
     */
    private function getJsPath()
    {
        return "./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/"
             . $this->getPluginName() . "/js/prevent.js";
    }

    /**
     * Gibt an, dass dieses Plugin Konfiguration besitzt.
     */
    public function hasConfiguration()
    {
        return true;
    }

    /**
     * Liefert das Config‑Objekt.
     */
    public function getConfig()
    {
        if (!$this->config) {
            require_once __DIR__ . "/class.ilPreventDevToolsAndCopyPasteConfig.php";
            $this->config = new ilPreventDevToolsAndCopyPasteConfig("prvntdvcpp");
        }
        return $this->config;
    }
}
