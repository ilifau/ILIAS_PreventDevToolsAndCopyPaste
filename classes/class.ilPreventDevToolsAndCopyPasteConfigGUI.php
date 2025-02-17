<?php
/**
 * @ilCtrl_isCalledBy ilPreventDevToolsAndCopyPasteConfigGUI: ilObjComponentSettingsGUI
 *
 * Konfigurations-GUI für das Plugin PreventDevToolsAndCopyPaste.
 */
class ilPreventDevToolsAndCopyPasteConfigGUI extends ilPluginConfigGUI
{
    /**
     * @var ilPreventDevToolsAndCopyPastePlugin
     */
    protected $plugin;

    /**
     * Wird vom Plugin-Manager aufgerufen.
     */
    public function performCommand(string $cmd): void
    {
        global $DIC;
        $this->plugin = $this->getPluginObject();
        $ctrl = $DIC->ctrl();
        $tpl  = $DIC->ui()->mainTemplate();

        switch ($cmd) {
            case 'configure':
            case 'save':
                $this->$cmd();
                break;
            default:
                $this->configure();
                break;
        }
    }

    /**
     * Zeigt das Konfigurationsformular an.
     */
    protected function configure(): void
    {
        global $DIC;
        $tpl  = $DIC->ui()->mainTemplate();
        $form = $this->initForm();
        $tpl->setContent($form->getHTML());
    }

    /**
     * Speichert die Einstellungen.
     * (Erfolgsmeldung wurde entfernt.)
     */
    protected function save(): void
    {
        global $DIC;
        $ctrl = $DIC->ctrl();
        $tpl  = $DIC->ui()->mainTemplate();
 
        $form = $this->initForm();
        if ($form->checkInput()) {
            $global_block = $form->getInput("global_block") ? "1" : "";
            $refid_list   = $form->getInput("refid_list") ?? "";
 
            $cfg = $this->plugin->getConfig();
            $cfg->set("global_block", $global_block);
            $cfg->set("refid_list", trim($refid_list));
 
            // Keine Erfolgsmeldung – direkt umleiten
            $ctrl->redirect($this, "configure");
        } else {
            $form->setValuesByPost();
            $tpl->setContent($form->getHTML());
        }
    }

    /**
     * Baut das Formular (Checkbox + Textfeld).
     */
    protected function initForm(): ilPropertyFormGUI
    {
        global $DIC;
        $ctrl = $DIC->ctrl();
 
        $form = new ilPropertyFormGUI();
        $form->setTitle("Prevent DevTools & Copy/Paste - Einstellungen");
        $form->setFormAction($ctrl->getFormAction($this));
 
        $cfg = $this->plugin->getConfig();
        $savedGlobal = $cfg->get("global_block");
        $savedRefids = $cfg->get("refid_list");
 
        $cb = new ilCheckboxInputGUI("Global aktivieren?", "global_block");
        $cb->setInfo("Wenn aktiviert, blockiert das Plugin in ganz ILIAS Copy&Paste/DevTools.");
        $cb->setChecked($savedGlobal === "1");
        $form->addItem($cb);
 
        $ti = new ilTextInputGUI("ref_ids (Kommagetrennt)", "refid_list");
        $ti->setInfo("Blockiert nur bei diesen ref_ids, falls global nicht aktiv ist (z. B.: 24093,24100).");
        $ti->setValue($savedRefids);
        $form->addItem($ti);
 
        $form->addCommandButton("save", "Speichern");
        return $form;
    }
}
