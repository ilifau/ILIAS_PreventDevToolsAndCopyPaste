<?php

/**
 * UIHook-Klasse, die ILIAS beim Rendern aufruft.
 */
class ilPreventDevToolsAndCopyPasteUIHookGUI extends ilUIHookPluginGUI
{
    /**
     * ILIAS 9-Signatur: public function modifyGUI(string $a_comp, string $a_part, array $a_par = []): void
     */
    public function modifyGUI(string $a_comp, string $a_part, array $a_par = []): void
    {
        /** @var ilPreventDevToolsAndCopyPastePlugin $plugin */
        $plugin = $this->getPluginObject();
        if (method_exists($plugin, 'modifyGUI')) {
            $plugin->modifyGUI($a_comp, $a_part, $a_par);
        }
    }
}
