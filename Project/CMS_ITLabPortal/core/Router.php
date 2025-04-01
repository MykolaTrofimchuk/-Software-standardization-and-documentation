<?php

namespace core;

/**
 * Клас маршрутизатора для обробки URL та виклику відповідних контролерів.
 *
 * Цей клас відповідає за визначення маршруту на основі запиту, ініціалізацію контролера та виклик відповідних методів.
 */
class Router
{
    /**
     * Маршрут (URL), який потрібно обробити.
     *
     * @var string
     */
    protected $route;

    /**
     * Конструктор маршрутизатора.
     *
     * Ініціалізує об'єкт маршруту.
     *
     * @param string $route Маршрут для обробки.
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * Запускає обробку маршруту та викликає відповідний метод контролера.
     *
     * Аналізує маршрут, визначає контролер та метод, а потім викликає їх.
     * Якщо контролер або метод не знайдено, викликає функцію для обробки помилок.
     *
     * @return mixed Результат виконання методу контролера.
     */
    public function run()
    {
        // Розділяємо маршрут на частини
        $parts = explode('/', $this->route);

        // Якщо перша частина пуста, встановлюємо дефолтні значення
        if (strlen($parts[0]) == 0) {
            $parts[0] = 'Site';  // Контролер за замовчуванням
            $parts[1] = 'index'; // Метод за замовчуванням
        }

        // Якщо є тільки одна частина маршруту, встановлюємо метод за замовчуванням
        if (count($parts) == 1) {
            $parts[1] = 'index';
        }

        // Збереження назв модуля і дії в глобальних властивостях Core
        \core\Core::get()->moduleName = $parts[0];
        \core\Core::get()->actionName = $parts[1];

        // Формуємо ім'я контролера та методу
        $controller = 'Controllers\\'.ucfirst($parts[0]).'Controller';
        $method = 'action'.ucfirst($parts[1]);

        // Перевірка наявності контролера
        if(class_exists($controller)) {
            $controllerObj = new $controller();  // Створення об'єкта контролера
            Core::get()->controllerObj = $controllerObj;

            // Перевірка наявності методу в контролері
            if(method_exists($controllerObj, $method)) {
                // Видаляємо перші два елементи маршруту (модуль та дію) і передаємо їх як параметри методу
                array_splice($parts, 0, 2);
                return $controllerObj->$method($parts);
            } else {
                // Якщо метод не знайдений, викликаємо помилку 404
                $this->error(404);
            }
        } else {
            // Якщо контролер не знайдений, викликаємо помилку 404
            $this->error(404);
        }
    }

    /**
     * Завершує роботу маршрутизатора.
     * Можливо, використовується для очищення ресурсів.
     */
    public function finish()
    {
        // Закінчення роботи маршрутизатора (можливо, очищення ресурсів)
    }

    /**
     * Обробка помилок, наприклад, для випадку 404 (не знайдено).
     *
     * Встановлює код відповіді HTTP і викликає метод контролера для обробки помилки.
     *
     * @param int $errorCode Код помилки.
     */
    public function error($errorCode)
    {
        // Встановлюємо HTTP код відповіді
        http_response_code($errorCode);

        // Встановлюємо дефолтні значення для модуля та дії помилки
        Core::get()->moduleName = 'Error';
        Core::get()->actionName = 'error';

        // Формуємо контролер та метод для обробки помилок
        $controller = 'Controllers\\ErrorController';
        $method = 'actionError';

        // Створюємо об'єкт контролера для обробки помилок
        $controllerObj = new $controller();
        Core::get()->controllerObj = $controllerObj;

        // Викликаємо метод для обробки помилки
        return $controllerObj->$method($errorCode);
    }
}
