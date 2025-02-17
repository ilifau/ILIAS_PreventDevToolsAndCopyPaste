<?php

/**
 * Eine einfache Konfigurations-Klasse, die ilSetting verwendet.
 */
class ilPreventDevToolsAndCopyPasteConfig
{
    /**
     * @var ilSetting
     */
    protected $settings;

    /**
     * Konstruktor
     * @param string $namespace z. B. "prvntdvcpp"
     */
    public function __construct(string $namespace)
    {
        $this->settings = new ilSetting($namespace);
    }

    /**
     * Holt einen Wert aus dem Settings-Store (Default = "")
     */
    public function get(string $key, string $default = ""): string
    {
        return $this->settings->get($key, $default);
    }

    /**
     * Speichert einen Wert
     */
    public function set(string $key, string $value): void
    {
        $this->settings->set($key, $value);
    }
}
