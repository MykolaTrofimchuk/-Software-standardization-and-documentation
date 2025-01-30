<?php
/** @var string $error_message Повідомлення про помилку */

$this->Title = 'Реєстрація користувача';
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
                <input type="text" class="form-control rounded-3" placeholder="логін"
                       id="login" name="login" value="<?= $this->controller->post->login ?>">
                <label for="login">Логін*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" placeholder="пароль"
                       id="password" name="password">
                <label for="password">Пароль*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" placeholder="пароль (повтор)"
                       id="password2" name="password2">
                <label for="password2">Пароль (повторити)*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" placeholder="прізвище"
                       id="lastName" name="lastName" value="<?= $this->controller->post->lastName ?>">
                <label for="lastName">Прізвище*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" placeholder="ім'я"
                       id="firstName" name="firstName" value="<?= $this->controller->post->firstName ?>">
                <label for="firstName">Ім'я*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control rounded-3" placeholder="e-mail (необов'язково)"
                       id="email" name="email" value="<?= $this->controller->post->email ?>">
                <label for="email">E-mail</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Реєстрація</button>
            <small class="text-body-secondary">Натискаючи «Зареєструватися», ви погоджуєтеся з умовами
                використання.</small>
            <hr class="my-4">

        </form>
    </div>
</div>
</body>
</html>
