<?php

namespace core;

class Core
{
    public $moduleName;
    public $actionName = 'actionIndex';
    public $router;
    public $template;
    public $defaultLayoutPath = 'Views/layouts/index.php';
    public $db;
    public ?Controller $controllerObj = null; // Ініціалізуємо як null
    public $session;
    private static $instance;

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

    public function run($route)
    {
        $this->router = new \core\Router($route);
        $params = $this->router->run();
        if (!empty($params))
            $this->template->setParams($params);
    }

    public function finish()
    {
        $this->template->display();
        $this->router->finish();
    }

    public static function get()
    {
        if (empty(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }
}