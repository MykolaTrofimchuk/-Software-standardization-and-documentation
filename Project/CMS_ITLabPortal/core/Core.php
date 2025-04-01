<?php

namespace core;

/**
 * Клас для керування основною логікою роботи додатку.
 *
 * Цей клас реалізує основні функції для ініціалізації шаблону, бази даних, сесій,
 * а також відповідає за запуск програми та обробку маршрутизації.
 */
class Core
{
    /**
     * Назва модуля, який обробляється.
     *
     * @var string
     */
    public $moduleName;

    /**
     * Назва дії, яка виконується за замовчуванням.
     *
     * @var string
     */
    public $actionName = 'actionIndex';

    /**
     * Об'єкт для маршрутизації запитів.
     *
     * @var Router
     */
    public $router;

    /**
     * Об'єкт шаблону для відображення сторінки.
     *
     * @var Template
     */
    public $template;

    /**
     * Шлях до шаблону за замовчуванням.
     *
     * @var string
     */
    public $defaultLayoutPath = 'Views/layouts/index.php';

    /**
     * Об'єкт для роботи з базою даних.
     *
     * @var DB
     */
    public $db;

    /**
     * Об'єкт контролера, який керує бізнес-логікою.
     *
     * @var Controller|null
     */
    public ?Controller $controllerObj = null;

    /**
     * Об'єкт для роботи з сесією.
     *
     * @var Session
     */
    public $session;

    /**
     * Єдиний екземпляр класу Core (патерн Singleton).
     *
     * @var Core|null
     */
    private static $instance;

    /**
     * Приватний конструктор для ініціалізації компонента Core.
     *
     * Ініціалізує шаблон, підключає базу даних та сесію.
     */
    private function __construct()
    {
        $this->template = new Template($this->defaultLayoutPath);
        $host = Config::get()->dbHost;
        $name = Config::get()->dbName;
        $login = Config::get()->dbLogin;
        $password = Config::get()->dbPass;
        $this->db = new DB($host, $name, $login, $password);
        $this->session = new Session();
        session_start();
    }

    /**
     * Запускає додаток, ініціалізуючи маршрутизатор.
     *
     * Розбирає маршрут та передає параметри шаблону.
     *
     * @param string $route Маршрут для обробки
     * @return void
     */
    public function run($route)
    {
        $this->router = new \core\Router($route);
        $params = $this->router->run();
        if (!empty($params)) {
            $this->template->setParams($params);
        }
    }

    /**
     * Завершує обробку запиту та відображає шаблон.
     *
     * Викликає метод відображення шаблону та завершує роботу маршрутизатора.
     *
     * @return void
     */
    public function finish()
    {
        $this->template->display();
        $this->router->finish();
    }

    /**
     * Отримує єдиний екземпляр класу Core.
     *
     * Якщо екземпляр ще не створений, він буде ініціалізований.
     *
     * @return Core
     */
    public static function get()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
