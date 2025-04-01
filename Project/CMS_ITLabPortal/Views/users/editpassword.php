<?php
/**
 * Сторінка для зміни паролю користувача
 *
 * Цей файл відповідає за отримання інформації про користувача та відображення форми для зміни його паролю.
 * @var string $error_message Повідомлення про помилку, яке може бути виведене на сторінці
 * @var string $this->Title Заголовок сторінки
 * @var array $userInfo Інформація про користувача, отримана з бази даних
 */
$this->Title = 'Змінити пароль користувача';

$userInfo = \Models\Users::GetUserInfo(\core\Core::get()->session->get('user')['id']);
?>
<!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
    <div class="modal-content rounded-4 shadow">

        <div class="modal-body p-5 pt-0">
            <form class="" method="post" action="">
                <?php if (!empty($error_message)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error_message ?>
                    </div>
                <?php endif; ?>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3" placeholder="Поточний Пароль"
                           id="oldPassword" name="oldPassword">
                    <label for="oldPassword">Старий пароль*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3" placeholder="Пароль"
                           id="newPassword" name="newPassword">
                    <label for="newPassword">Новий пароль*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3" placeholder="Пароль (повтор)"
                           id="newPassword2" name="newPassword2">
                    <label for="newPassword2">Новий пароль (повторити)*</label>
                </div>
                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Зберегти</button>
                <small class="text-body-secondary">Уважно перевірте введені дані для зміни.</small>
                <hr class="my-4">

            </form>
        </div>
    </div>
    </body>
    </html>
