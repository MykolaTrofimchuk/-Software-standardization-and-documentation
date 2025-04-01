<?php

namespace core;

/**
 * Клас для керування контролером, що обробляє запити, рендерить шаблони та керує помилками.
 *
 * Цей клас визначає базові методи для обробки HTTP запитів, рендерингу шаблонів та керування повідомленнями про помилки.
 */
class Controller
{
    /**
     * Об'єкт шаблону, що відповідає за рендеринг контенту.
     *
     * @var Template
     */
    protected $template;

    /**
     * Масив повідомлень про помилки.
     *
     * @var array
     */
    protected $errorMessages;

    /**
     * Індикатор того, чи був запит методом POST.
     *
     * @var bool
     */
    public $isPost = false;

    /**
     * Індикатор того, чи був запит методом GET.
     *
     * @var bool
     */
    public $isGet = false;

    /**
     * Об'єкт для обробки даних з POST-запиту.
     *
     * @var Post
     */
    public $post;

    /**
     * Об'єкт для обробки даних з GET-запиту.
     *
     * @var Get
     */
    public $get;

    /**
     * Конструктор класу Controller.
     *
     * Визначає необхідний шлях до шаблону, створює об'єкти для обробки POST та GET запитів,
     * а також ініціалізує масив для зберігання повідомлень про помилки.
     */
    public function __construct()
    {
        $action = Core::get()->actionName;
        $module = Core::get()->moduleName;
        $path = "Views/{$module}/{$action}.php";
        $this->template = new Template($path);

        switch ($_SERVER['REQUEST_METHOD']){
            case 'POST' :
                $this->isPost = true;
                break;
            case 'GET':
                $this->isGet = true;
                break;
        }

        // Створюємо об'єкти для обробки GET та POST запитів
        $this->post = new Post();
        $this->get = new Get();

        // Ініціалізуємо масив для повідомлень про помилки
        $this->errorMessages = [];
    }

    /**
     * Рендерить шаблон з даними.
     *
     * Якщо шлях до виду надано, використовується він, інакше використовуються дані,
     * які були ініціалізовані в конструкторі.
     *
     * @param string|null $pathToView Шлях до файлу виду (якщо вказано)
     * @return array Масив з HTML контентом
     */
    public function render($pathToView = null): array
    {
        if (!empty($pathToView)) {
            $this->template->setTemplateFilePath($pathToView);
        }
        return [
            'Content' => $this->template->getHTML()
        ];
    }

    /**
     * Перенаправляє користувача на інший шлях.
     *
     * @param string $path Шлях для перенаправлення
     * @return void
     */
    public function redirect($path): void
    {
        header("Location: {$path}");
        die;
    }

    /**
     * Додає повідомлення про помилку до масиву.
     *
     * Якщо повідомлення не надано, додається порожнє повідомлення.
     * Повідомлення обробляються та відображаються в шаблоні.
     *
     * @param string|null $message Повідомлення про помилку
     * @return void
     */
    public function addErrorMessage($message = null): void
    {
        $this->errorMessages[] = $message;
        $this->template->setParam('error_message', implode('<br>', $this->errorMessages));
    }

    /**
     * Очищає всі повідомлення про помилки.
     *
     * Видаляє всі повідомлення про помилки з масиву та з шаблону.
     *
     * @return void
     */
    public function clearErrorMessage(): void
    {
        $this->errorMessages = [];
        $this->template->setParam('error_message', null);
    }

    /**
     * Перевіряє, чи є повідомлення про помилки.
     *
     * @return bool Повертає true, якщо є повідомлення про помилки, в іншому випадку false
     */
    public function isErrorMessagesExists(): bool
    {
        return count($this->errorMessages) > 0;
    }
}
