<?php
/**
 * Видення всіх наявних оголошень у системі
 *
 */
$this->Title = '';

$announcement = $GLOBALS['announcement'];
$pathToImages = $GLOBALS['images'];
$countOfLikes = $GLOBALS['countLikes'];

$user = \core\Core::get()->session->get('user');
$isAdmin = (!empty($user) && isset($user['role']) && $user['role'] === 'admin');
if (!empty($user) && isset($user['id'])) {
    $userInfo = \Models\Users::GetUserInfo($user['id']);
} else {
    $userInfo = null;
}
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Оголошення</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="lightbox.css">
    <style>
        .btn-admin {
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 0.9rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .btn-edit {
            background-color: #f39c12;
            color: white;
        }
        .btn-edit:hover {
            background-color: #e67e22;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<section class="py-5" style="margin-top: -100px;">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                <div class="row">
                    <?php
                    $imageSrc = "../../../../src/resourses/no-photo.jpg";
                    $imagesPath = "./" . $pathToImages;

                    $realImagesPath = realpath($imagesPath);
                    $realImagesPath = str_replace('\\', '/', $realImagesPath);

                    $images = [];

                    if (!is_null($pathToImages) && is_dir($realImagesPath)) {
                        $scannedImages = array_values(array_diff(scandir($realImagesPath), ['.', '..']));

                        if (!empty($scannedImages)) {
                            $firstImage = reset($scannedImages);
                            $firstImageSrc = "../../../../../" . $pathToImages . "/" . $firstImage;
                            $images = array_slice($scannedImages, 1);
                        } else {
                            $firstImageSrc = '../../../../src/resourses/no-photo.jpg';
                            $images = [];
                        }
                    } else {
                        $firstImageSrc = '../../../../src/resourses/no-photo.jpg';
                        $images = [];
                    }
                    ?>
                    <div class="col-12 mb-3">
                        <img class="card-img-top big-photo" src="<?php echo($firstImageSrc) ?>" alt="Фото оголошення">
                    </div>
                    <div class="col-12">
                        <div class="row small-photos">
                            <?php foreach ($images as $image): ?>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo "../../../../../" . $pathToImages . "/" . $image ?>"
                                       data-lightbox="gallery">
                                        <img class="card-img-top" src="<?php echo "../../../../../" . $pathToImages . "/" . $image ?>"
                                             alt="Фото галереї">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="margin-top: -50px;">
                <?php if (isset($announcement)): ?>
                    <div class="announcement-header mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bolder d-inline"><?= htmlspecialchars($announcement->id) ?> ||</div>
                                <span class="text-muted"><?= htmlspecialchars($announcement->publicationDate) ?></span>
                            </div>
                            <div class="likes-count">
                                &#9829; <?= htmlspecialchars($countOfLikes[0]['count']) ?>
                            </div>
                        </div>
                    </div>
                    <h1 class="announcement-title"><?= htmlspecialchars($announcement->title) ?></h1>
                    <p class="announcement-text"><?= $announcement->text ?></p>
                <?php endif; ?>

                <?php if (\Models\Users::IsUserLogged()): ?>
                    <?php
                    $isFavorite = \Models\UserLikeAnnouncements::IsFavorite($userInfo[0]['id'], $announcement->id);
                    if (!$isFavorite): ?>
                        <div class="d-flex">
                            <a href="/announcements/addtofavorites/<?= $announcement->id ?>"
                               class="btn btn-sm btn-outline-secondary btn-favorite">Додати в обрані &#9829;</a>
                        </div>
                    <?php endif; ?>
                    <?php if ($isFavorite): ?>
                        <div class="d-flex">
                            <a href="/announcements/removefromfavorites/<?= $announcement->id ?>"
                               class="btn btn-sm btn-outline-secondary btn-favorite">Видалити з обраних</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($isAdmin): ?>
                    <div class="d-flex mt-3">
                        <a href="/announcements/edit/<?= $announcement->id ?>" class="btn btn-admin btn-edit me-2">Редагувати</a>
                        <a href="/announcements/delete/<?= $announcement->id ?>" class="btn btn-admin btn-delete">Видалити</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<script src="lightbox-plus-jquery.js"></script>
</body>
</html>
