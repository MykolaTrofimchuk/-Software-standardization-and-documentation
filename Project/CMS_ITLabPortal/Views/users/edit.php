<?php
/** @var string $error_message Повідомлення про помилку */

$this->Title = 'Змінити дані користувача';

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
                <input type="text" class="form-control rounded-3" placeholder="Логін"
                       id="login" name="login" value="<?= $userInfo[0]['login'] ?>">
                <label for="login">Новий логін*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" placeholder="Прізвище"
                       id="lastName" name="lastName" value="<?= $userInfo[0]['last_name'] ?>">
                <label for="lastName">Змінити прізвище*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" placeholder="Ім'я"
                       id="firstName" name="firstName" value="<?= $userInfo[0]['first_name'] ?>">
                <label for="firstName">Змінити ім'я*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control rounded-3" placeholder="name@mail.com"
                       id="email" name="email" value="<?= $userInfo[0]['email'] ?>">
                <label for="email">Змінити e-mail</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Зберегти</button>
            <small class="text-body-secondary">Уважно перевірте введені дані для зміни.</small>
            <hr class="my-4">

        </form>
    </div>
</div>
</body>
</html>
