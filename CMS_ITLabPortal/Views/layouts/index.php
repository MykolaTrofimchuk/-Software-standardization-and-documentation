<?php
/** @var string $Title */
/** @var string $Content */

if (empty($Title))
    $Title = '';
if (empty($Content))
    $Content = '';

$user = \core\Core::get()->session->get('user');
if (!empty($user) && isset($user['id'])) {
    $userInfo = \Models\Users::GetUserInfo($user['id']);
} else {
    $userInfo = null;
}
$userPhoto = isset($userInfo[0]['image_path']) ? $userInfo[0]['image_path'] : '../../../src/resourses/user-default.png';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $Title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap"></use>
                </svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="/" class="nav-link px-2 link-body-emphasis">Головна</a></li>
                <li><a href="/announcements/view/1" class="nav-link px-2 link-secondary">Новини</a></li>
            </ul>
            <?php if (\Models\Users::IsUserLogged() && \Models\Users::IsAdmin($user['id'])): ?>
                <a href="/announcements/add" class="btn btn-outline-secondary me-2">&#9658;Створити оголошення</a></li>
            <?php endif; ?>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search" method="post" action="">
                <input type="search" class="form-control" name="titleLike" placeholder="Пошук..." aria-label="Search">
            </form>
            <?php if (!\Models\Users::IsUserLogged()): ?>
                <div class="col-md-3 text-end">
                    <a type="button" class="btn btn-outline-secondary me-2" href="/users/login">Авторизація</a>
                    <a type="button" class="btn btn-secondary" href="/users/register">Реєстрація</a>
                </div>
            <?php endif; ?>
            <?php if (\Models\Users::IsUserLogged()): ?>
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo "../" . htmlspecialchars($userPhoto); ?>" alt="User" width="32" height="32"
                             class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu text-small" style="">
                        <li><a class="dropdown-item" href="/users/me">Профіль</a></li>
                        <li><a class="dropdown-item" href="#">Про нас</a></li>
                        <li><a class="dropdown-item" href="#">Налаштування</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/users/logout">Вийти</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<div>
    <div class="modal-header p-5 pb-4 border-bottom-0">
        <h1 class="fw-bold mb-0 fs-2"><?= $Title ?></h1>
    </div>
    <?= $Content ?>
</div>

<footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <div class="col-md-4 d-flex align-items-center">
        <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
            <svg class="bi" width="30" height="24">
                <use xlink:href="#bootstrap"></use>
            </svg>
        </a>
        <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Company, Inc</span>
    </div>

    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
        <li class="ms-md-3"><a class="text-body-secondary" href="#"><img src="/src/resourses/instagram.png"
                                                                         alt="instagram"
                                                                         class="bi" width="24" height="24"></a></li>
        <li class="ms-md-3"><a class="text-body-secondary" href="#"><img src="/src/resourses/twitter.png" alt="twitter"
                                                                         class="bi" width="24" height="24"></a></li>
        <li class="ms-md-3"><a class="text-body-secondary" href="#"><img src="/src/resourses/facebook.png"
                                                                         alt="facebook"
                                                                         class="bi" width="24" height="24"></a></li>
    </ul>
</footer>
</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('input[name="titleLike"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    });
</script>