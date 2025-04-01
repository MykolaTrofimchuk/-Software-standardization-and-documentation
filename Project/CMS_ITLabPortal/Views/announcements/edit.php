<?php
/**
 * Представлення для редагування оголошення
 *
 * @var string $error_message Повідомлення про помилку
 */

$this->Title = 'Редагування оголошення';

$announcementInfo = $GLOBALS['announcementInfo'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .preview img {
            max-width: 100px;
            margin: 5px;
            transition: filter 0.3s ease;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="modal-content rounded-4 shadow">
    <div class="modal-body p-5 pt-0">
        <form method="post" action="" enctype="multipart/form-data">
            <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>
            <div class="form-row">
                <h3>Додайте фото до публікації: </h3>
                <div class="form-group col">
                    <button type="button" id="addPhoto" class="btn btn-secondary mt-2">Додати фото</button>
                </div>
                <div id="preview" class="preview">
                    <?php
                    $imageSrc = "../../../../src/resourses/no-photo.jpg";
                    $imagesPath = "./" . $announcementInfo[0]['pathToImages'];
                    $firstImageSrc = '../../../src/resourses/no-photo.jpg';
                    $realImagesPath = realpath($imagesPath);
                    $realImagesPath = str_replace('\\', '/', $realImagesPath);

                    if (!is_null($announcementInfo[0]['pathToImages']) && is_dir($realImagesPath)) {
                        $images = scandir($realImagesPath);
                        $images = array_diff($images, array('.', '..'));

                        foreach ($images as $image) {
                            $imageSrc = "../../../../../" . $announcementInfo[0]['pathToImages'] . "/" . $image;
                            echo "<img src='$imageSrc' alt='$image' data-filename='$image'>";
                        }
                    }
                    ?>
                </div>
            </div>
            <p class="text-muted" style="font-style: italic;">(Порядок фотографій відповідає їх відображенню в оголошенні. Для видалення - клікніть по картинці)</p>
            <input type="hidden" name="deletedImages" id="deletedImages" value="">
            <br><br><br>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="titleAnnouncement"
                       placeholder="Заголовок" name="title" value="<?= $announcementInfo[0]['title'] ?>">
                <label for="titleAnnouncement">Заголовок оголошення</label>
            </div>
            <div class="form-group">
                <label for="text">Опис</label>
                <textarea class="form-control rounded-3" id="text" rows="5" name="text"
                          placeholder="Основний опис новини..."><?= $announcementInfo[0]['text'] ?></textarea>
            </div>
            <br>

            <button type="submit" class="btn btn-primary">Зберегти зміни</button>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var previewContainer = document.getElementById('preview');

        document.getElementById('addPhoto').addEventListener('click', function() {
            var newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.accept = 'image/jpeg, image/png, image/gif, image/jpg';
            newInput.classList.add('form-control', 'file-input');
            newInput.name = 'files[]';
            newInput.style.display = 'none';
            document.querySelector('.form-group').appendChild(newInput);

            newInput.addEventListener('change', handleFileSelect);
            newInput.click();
        });

        var imageCounter = 1;

        function handleFileSelect(event) {
            var files = event.target.files;

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.addEventListener('click', function() {
                        this.remove();
                    });
                    img.addEventListener('mouseover', function() {
                        this.style.filter = 'blur(3px)';
                    });
                    img.addEventListener('mouseout', function() {
                        this.style.filter = 'none';
                    });

                    var extension = file.name.split('.').pop().toLowerCase();
                    var fileName = imageCounter + '.' + extension;
                    imageCounter++;
                    img.setAttribute('data-filename', fileName);

                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }

        document.querySelectorAll('.preview img').forEach(img => {
            img.addEventListener('click', function() {
                var confirmed = confirm('Ви впевнені, що хочете видалити цю фотографію?');
                if (confirmed) {
                    var deletedImagesInput = document.getElementById('deletedImages');
                    var filename = this.getAttribute('data-filename');
                    this.remove();
                    if (deletedImagesInput.value === '') {
                        deletedImagesInput.value = filename;
                    } else {
                        deletedImagesInput.value += ', ' + filename;
                    }
                }
            });
        });
    });
</script>
</body>
</html>