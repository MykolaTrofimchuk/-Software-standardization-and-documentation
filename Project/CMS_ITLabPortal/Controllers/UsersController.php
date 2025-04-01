<?php

namespace Controllers;

use core\Controller;
use core\Core;
use http\Client\Curl\User;
use Models\Users;

/**
 * Контролер для обробки користувацьких операцій.
 *
 * Цей контролер обробляє реєстрацію, вхід, редагування профілю користувачів та інші дії,
 * пов'язані з керуванням обліковими записами користувачів.
 */
class UsersController extends Controller
{
    /**
     * Відображає сторінку профілю користувача.
     *
     * Повертає HTML-контент профілю користувача.
     *
     * @return array Масив з HTML-контентом сторінки профілю користувача.
     */
    public function actionIndex()
    {
        return $this->render('Views/users/me.php');
    }

    /**
     * Реєстрація нового користувача.
     *
     * Перевіряє введені дані користувача та реєструє нового користувача, якщо дані коректні.
     * Якщо є помилки, вони додаються в повідомлення.
     *
     * @return array Масив з HTML-контентом сторінки реєстрації.
     */
    public function actionRegister()
    {
        if ($this->isPost) {
            $user = Users::FindByLogin($this->post->login);

            if (strlen($this->post->login) === 0)
                $this->addErrorMessage('Логін не вказаний!');
            if (strlen($this->post->password) <= 8)
                $this->addErrorMessage('Пароль має містити мінімум 8 символів!');
            if (strlen($this->post->firstName) === 0)
                $this->addErrorMessage('Ім\'я не вказано!');
            if (strlen($this->post->lastName) === 0)
                $this->addErrorMessage('Прізвище не вказано!');
            if (!empty($user)) {
                $this->addErrorMessage('Користувач із таким логіном вже існує!');
            }
            if ($this->post->password != $this->post->password2) {
                $this->addErrorMessage('Паролі не збігаються!');
            }
            if (!$this->isErrorMessagesExists()) {
                Users::RegisterUser($this->post->login, $this->post->password, $this->post->firstName,
                    $this->post->lastName, $this->post->email);
                $this->redirect("/users/registersuccess");
            }
        }
        return $this->render();
    }

    /**
     * Відображає сторінку успішної реєстрації.
     *
     * Повертає HTML-контент сторінки з підтвердженням успішної реєстрації.
     *
     * @return array Масив з HTML-контентом сторінки успішної реєстрації.
     */
    public function actionRegistersuccess()
    {
        return $this->render();
    }

    /**
     * Вхід користувача.
     *
     * Перевіряє правильність введених логіна та пароля. Якщо дані правильні, користувач входить в систему.
     * Якщо дані неправильні, виводиться повідомлення про помилку.
     *
     * @return array Масив з HTML-контентом сторінки входу.
     */
    public function actionLogin()
    {
        if (Users::IsUserLogged())
            $this->redirect('/');
        if ($this->isPost) {
            $user = Users::FindLoginAndPassword($this->post->login, $this->post->password);
            if (!empty($user)) {
                Users::LoginUser($user);
                $this->redirect('/');
            } else {
                $this->addErrorMessage('Неправильний логін та/або пароль!');
            }
        }
        return $this->render();
    }

    /**
     * Вихід користувача.
     *
     * Користувач виходить з системи, після чого його перенаправляють на сторінку входу.
     *
     * @return void
     */
    public function actionLogout()
    {
        Users::LogoutUser();
        $this->redirect('/users/login');
    }

    /**
     * Відображає сторінку профілю користувача.
     *
     * Перевіряє, чи користувач увійшов в систему. Якщо не увійшов — перенаправляє на головну сторінку.
     *
     * @return array Масив з HTML-контентом сторінки профілю.
     */
    public function actionMe()
    {
        if (!Users::IsUserLogged())
            $this->redirect('/');
        return $this->render();
    }

    /**
     * Редагування даних користувача.
     *
     * Дозволяє користувачу змінювати логін, ім'я, прізвище та електронну пошту.
     * Перевіряє наявність помилок і виводить повідомлення при необхідності.
     *
     * @return array Масив з HTML-контентом сторінки редагування даних.
     */
    public function actionEdit()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        if ($this->isPost) {
            $userId = \core\Core::get()->session->get('user')['id'];
            $userData = Users::findById($userId);

            if (!$userData) {
                $this->addErrorMessage('Користувача не знайдено!');
            } else {
                if ($userData->login !== $this->post->login) {
                    $existingUser = Users::FindByLogin($this->post->login);
                    if (!empty($existingUser)) {
                        $this->addErrorMessage('Користувач із таким логіном вже існує!');
                    }
                }

                if (!$this->isErrorMessagesExists()) {
                    if (strlen($this->post->login) > 0) {
                        $userData->login = $this->post->login;
                    }
                    if (strlen($this->post->firstName) > 0) {
                        $userData->first_name = $this->post->firstName;
                    }
                    if (strlen($this->post->lastName) > 0) {
                        $userData->last_name = $this->post->lastName;
                    }
                    if (strlen($this->post->email) > 0) {
                        $userData->email = $this->post->email;
                    }

                    if (Users::EditUserInfo($userId, $userData)) {
                        $this->redirect("/users/me");
                    } else {
                        $this->addErrorMessage('Помилка при зміні даних користувача!');
                    }
                }
            }
        }

        return $this->render();
    }

    /**
     * Зміна паролю користувача.
     *
     * Перевіряє введений поточний пароль і нові паролі, а також їх відповідність.
     * Якщо умови виконуються, оновлює пароль користувача.
     *
     * @return array Масив з HTML-контентом сторінки редагування паролю.
     */
    public function actionEditpassword()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        if ($this->isPost) {
            $userId = \core\Core::get()->session->get('user')['id'];
            $userData = Users::findById($userId);

            if (!$userData) {
                $this->addErrorMessage('Користувача не знайдено!');
            } else {
                $currentPassword = $this->post->oldPassword;
                $newPassword = $this->post->newPassword;
                $newPassword2 = $this->post->newPassword2;

                if (password_verify($currentPassword, $userData->password)) {

                    if ($newPassword === $newPassword2) {
                        if (strlen($newPassword) >= 8) {
                            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                            $userData->password = $hashedPassword;
                            if (Users::EditUserInfo($userId, $userData)) {
                                $this->redirect("/users/me");
                            } else {
                                $this->addErrorMessage('Помилка при зміні даних користувача!');
                            }
                        } else {
                            $this->addErrorMessage('Новий пароль має містити мінімум 8 символів!');
                        }
                    } else {
                        $this->addErrorMessage('Нові введені паролі не збігаються!');
                    }
                } else {
                    $this->addErrorMessage('Дійсний пароль введено невірно!');
                }
            }
        }
        return $this->render();
    }

    /**
     * Зміна фото користувача.
     *
     * Дозволяє користувачу завантажити нове фото профілю.
     * Перевіряє наявність помилок при завантаженні файлу та оновлює дані в базі.
     *
     * @return array Масив з HTML-контентом сторінки редагування фото.
     */
    public function actionEditphoto()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        if ($this->isPost) {
            $userId = \core\Core::get()->session->get('user')['id'];
            $userData = Users::findById($userId);

            if (!$userData) {
                $this->addErrorMessage('Користувача не знайдено!');
            } else {
                // Перевірка чи був вибраний файл
                if (isset($_FILES['file-upload']) && $_FILES['file-upload']['error'] === UPLOAD_ERR_OK) {
                    // Переміщення завантаженого файлу у відповідну папку
                    $uploadDir = "src/database/users/user" . $userId . "/";
                    $uploadFile = $uploadDir . basename($_FILES['file-upload']['name']);

                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    if (move_uploaded_file($_FILES['file-upload']['tmp_name'], $uploadFile)) {
                        // Оновлення шляху зображення в базі даних
                        $userData->image_path = $uploadFile;
                        if (Users::EditUserInfo($userId, $userData)) {
                            $this->redirect("/users/me");
                        } else {
                            $this->addErrorMessage('Помилка при зміні даних користувача!');
                        }
                    } else {
                        $this->addErrorMessage('Помилка при завантаженні файлу!');
                    }
                } else {
                    $this->addErrorMessage('Не вдалося завантажити файл!');
                }
            }
        }

        return $this->render();
    }
}
