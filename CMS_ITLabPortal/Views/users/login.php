<?php
/** @var string $error_message Повідомлення про помилку */

$this->Title = 'Вхід на сайт';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
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
                <input type="text" class="form-control rounded-3" id="login" placeholder="Логін" name="login">
                <label for="login">Логін</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" id="password" placeholder="Пароль"
                       name="password">
                <label for="password">Пароль</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-warning" type="submit">Вхід</button>
            <hr class="my-4">
        </form>
    </div>
</div>
</body>
</html>
