<?php
/**
 * @ilCtrl_isCalledBy ilPreventDevToolsAndCopyPasteConfigGUI: ilObjComponentSettingsGUI
 *
 * Konfigurations-GUI für das Plugin PreventDevToolsAndCopyPaste in ILIAS 7.
 */
class ilPreventDevToolsAndCopyPasteConfigGUI extends ilPluginConfigGUI
{
    /**
     * @var ilPreventDevToolsAndCopyPastePlugin
     */
    protected $plugin;

    public function performCommand($cmd)
    {
        global $ilCtrl, $tpl;
        $this->plugin = $this->getPluginObject();

        switch ($cmd) {
            case "configure":
            case "save":
                $this->$cmd();
                break;
            default:
                $this->configure();
                break;
        }
    }

    protected function configure()
    {
        global $tpl;
        $form = $this->initForm();
        $tpl->setContent($form->getHTML());
    }

    protected function save()
    {
        global $ilCtrl, $tpl;
        $form = $this->initForm();
        if ($form->checkInput()) {
            $global_block = $form->getInput("global_block") ? "1" : "";
            $refid_list   = $form->getInput("refid_list") ? $form->getInput("refid_list") : "";

            // Speichern der Einstellungen über unser Config-Objekt
            $this->plugin->getConfig()->set("global_block", $global_block);
            $this->plugin->getConfig()->set("refid_list", trim($refid_list));

            // Kein Erfolgstext – direkte Weiterleitung
            $ilCtrl->redirect($this, "configure");
        } else {
            $form->setValuesByPost();
            $tpl->setContent($form->getHTML());
        }
    }

    protected function initForm()
    {
        global $ilCtrl;
        $form = new ilPropertyFormGUI();
        $form->setTitle("Prevent DevTools & Copy/Paste - Einstellungen");
        $form->setFormAction($ilCtrl->getFormAction($this));

        $saved_global = $this->plugin->getConfig()->get("global_block");
        $saved_refids = $this->plugin->getConfig()->get("refid_list");

        $cb = new ilCheckboxInputGUI("Global aktivieren?", "global_block");
        $cb->setInfo("Wenn aktiviert, wird Copy&Paste in ganz ILIAS blockiert.");
        $cb->setChecked($saved_global === "1");
        $form->addItem($cb);

        $ti = new ilTextInputGUI("ref_ids (Kommagetrennt)", "refid_list");
        $ti->setInfo("Blockiert nur bei diesen ref_ids, falls global nicht aktiv ist (z. B.: 24093,24100).");
        $ti->setValue($saved_refids);
        $form->addItem($ti);

        $form->addCommandButton("save", "Speichern");
        return $form;
    }
}
