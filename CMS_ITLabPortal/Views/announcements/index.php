<?php
$this->Title = '';

$announcement = $GLOBALS['announcement'];
$pathToImages = $GLOBALS['images'];
$countOfLikes = $GLOBALS['countLikes'];

$user = \core\Core::get()->session->get('user');
if (!empty($user) && isset($user['id'])) {
    $userInfo = \Models\Users::GetUserInfo($user['id']);
} else {
    $userInfo = null;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="lightbox.js"></script>
    <style>
        .big-photo {
            width: 100%;
            height: auto;
        }

        .small-photos {
            max-height: 300px;
            overflow-y: auto;
        }

        .small-photos {
            flex: 0 0 calc(33.33% - 10px);
            margin-bottom: 10px;
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
                        <img class="card-img-top big-photo" src="<?php echo($firstImageSrc) ?>" alt="...">
                    </div>
                    <div class="col-12">
                        <div class="row small-photos">
                            <?php foreach ($images as $image): ?>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo "../../../../../" . $pathToImages . "/" . $image ?>"
                                       data-lightbox="gallery">
                                        <img class="card-img-top"
                                             src="<?php echo "../../../../../" . $pathToImages . "/" . $image ?>"
                                             alt="...">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="margin-top: -50px;">
                <?php if (isset($announcement)): ?>
                    <div class="small mb-1">
                        <div class="fw-bolder d-inline"><?= htmlspecialchars($announcement->id) ?> || </div>
                        <?= htmlspecialchars($announcement->publicationDate) ?>
                        <div class="float-end">
                            <div class="fw-bolder d-inline text-danger">&#9829; <?= htmlspecialchars($countOfLikes[0]['count']) ?></div>
                        </div>
                    </div>
                    <h1 class="display-5 fw-bolder"><?= htmlspecialchars($announcement->title) ?></h1>
                    <p class="lead"><?= $announcement->text ?></p>
                <?php endif; ?>
                <?php
                $isFavorite = \Models\UserLikeAnnouncements::IsFavorite($userInfo[0]['id'], $announcement->id);
                if (!$isFavorite) : ?>
                <div class="d-flex">
                    <a href="/announcements/addtofavorites/<?= $announcement->id ?>" class="btn btn-sm btn-outline-secondary">Додати в обрані</a>
                </div>
                <?php endif;?>
                <?php if ($isFavorite) : ?>
                    <div class="d-flex">
                        <a href="/announcements/removefromfavorites/<?= $announcement->id ?>" class="btn btn-sm btn-outline-secondary">Видалити з обраних</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

</body>
</html>
