<?php

namespace Controllers;

use core\Controller;
use core\Core;
use http\Client\Curl\User;
use Models\Users;

class UsersController extends Controller
{
    public function actionIndex()
    {
        return $this->render('Views/users/me.php');
    }

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

    public function actionRegistersuccess()
    {
        return $this->render();
    }

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

    public function actionLogout()
    {
        Users::LogoutUser();
        $this->redirect('/users/login');
    }

    public function actionMe()
    {
        if (!Users::IsUserLogged())
            $this->redirect('/');
        return $this->render();
    }

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