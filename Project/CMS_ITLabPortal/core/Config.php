<?php

namespace core;

/**
 * Клас для керування конфігураційними параметрами.
 *
 * Цей клас використовує патерн Singleton для забезпечення доступу до конфігураційних даних.
 * Всі конфігураційні файли в директорії `config` підключаються та зберігаються в об'єкті цього класу.
 */
class Config
{
    /**
     * Масив, що зберігає параметри конфігурації.
     *
     * @var array
     */
    protected $params;

    /**
     * Єдиний екземпляр класу Config (патерн Singleton).
     *
     * @var Config|null
     */
    protected static $instance;

    /**
     * Приватний конструктор для ініціалізації параметрів з конфігураційних файлів.
     *
     * Цей метод автоматично підключає всі PHP файли в директорії `config` та зберігає їх у властивості класу.
     */
    private function __construct()
    {
        /** @var array $Config Містить конфігураційні параметри, що були підключені з файлів */

        $directory = 'config';
        $config_files = scandir($directory);

        // Підключаємо всі конфігураційні файли
        foreach ($config_files as $config_file) {
            if (substr($config_file, -4) === '.php') {
                $path = $directory . '/' . $config_file;
                include($path);
            }
        }

        $this->params = [];

        // Зберігаємо конфігурації в масив
        foreach ($Config as $config) {
            foreach ($config as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Отримує єдиний екземпляр класу Config.
     *
     * Якщо екземпляр ще не створений, він буде ініціалізований.
     *
     * @return Config
     */
    public static function get()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Магічний метод для встановлення значень конфігураційних параметрів.
     *
     * Використовується для динамічного додавання значень до властивості `$params`.
     *
     * @param string $name Назва параметра
     * @param mixed $value Значення параметра
     */
    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * Магічний метод для отримання значень конфігураційних параметрів.
     *
     * Повертає значення, збережене за вказаним параметром.
     *
     * @param string $name Назва параметра
     * @return mixed Значення конфігураційного параметра
     */
    public function __get($name)
    {
        return $this->params[$name];
    }
}
