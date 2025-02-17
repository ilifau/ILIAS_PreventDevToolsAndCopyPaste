<?php

/**
 * Eine einfache Konfigurations-Klasse für ILIAS 7,
 * die ilSetting verwendet.
 */
class ilPreventDevToolsAndCopyPasteConfig
{
    /**
     * @var ilSetting
     */
    protected $settings;

    /**
     * Konstruktor
     * @param string $namespace (z. B. "prvntdvcpp")
     */
    public function __construct($namespace)
    {
        $this->settings = new ilSetting($namespace);
    }

    /**
     * Gibt einen Wert zurück. Default ist ein leerer String.
     */
    public function get($key, $default = "")
    {
        return $this->settings->get($key, $default);
    }

    /**
     * Speichert einen Wert.
     */
    public function set($key, $value)
    {
        $this->settings->set($key, $value);
    }
}
