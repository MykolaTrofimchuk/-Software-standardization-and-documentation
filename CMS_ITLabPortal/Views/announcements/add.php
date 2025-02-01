<?php
/** @var string $error_message Повідомлення про помилку */
$userId = \core\Core::get()->session->get('user')['id'];
if (!\Models\Users::IsAdmin($userId)) {
    header("Location: /");
    exit;
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
                <h3>Додайте фото для статті: </h3>
                <div class="form-group col">
                    <input type="file" class="form-control file-input" name="files[]" id="fileInput">
                    <button type="button" id="addPhoto" class="btn btn-secondary mt-2">Додати фото</button>
                </div>
                <div id="preview" class="preview"></div>
            </div>
            <br><br><br>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="titleAnnouncement"
                       placeholder="Заголовок оголошення" name="title" value="<?= $this->controller->post->title ?>">
                <label for="titleAnnouncement">Заголовок</label>
            </div>
            <div class="form-group">
                <label for="text">Опис</label>
                <textarea class="form-control rounded-3" id="text" rows="5" name="text"
                          placeholder="Основний опис до статті..."><?= $this->controller->post->text ?></textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Створити новину</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var previewContainer = document.getElementById('preview');
        document.getElementById('addPhoto').addEventListener('click', function() {
            var newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.classList.add('form-control', 'file-input');
            newInput.name = 'files[]';
            newInput.style.display = 'none';
            document.querySelector('.form-group').appendChild(newInput);

            newInput.addEventListener('change', handleFileSelect);
            newInput.click();
        });

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
                    previewContainer.appendChild(img);
                };

                reader.readAsDataURL(file);
            }
        }

        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', handleFileSelect);
        });
    });
</script>

</body>
</html>