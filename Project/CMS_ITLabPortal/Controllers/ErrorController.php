<?php

namespace Controllers;

use core\Controller;

/**
 * Контролер для обробки помилок
 *
 * Цей контролер відповідає за відображення сторінки помилки на основі коду помилки.
 */
class ErrorController extends Controller
{
    /**
     * Дія для обробки помилок
     *
     * Встановлює код помилки та викликає відповідний шаблон для відображення сторінки помилки.
     *
     * @param int $errorCode Код помилки, який необхідно відобразити на сторінці.
     * @return array Масив, що містить HTML-контент сторінки помилки.
     */
    public function actionError($errorCode)
    {
        // Встановлення параметру для шаблону з кодом помилки
        $this->template->setParam('errorCode', $errorCode);

        // Встановлення шляху до шаблону для сторінки помилки
        $this->template->setTemplateFilePath('Views/error/error.php');

        // Повернення HTML-контенту сторінки помилки
        return [
            'Content' => $this->template->getHTML()
        ];
    }
}
