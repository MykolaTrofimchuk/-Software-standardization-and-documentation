<?php
$latestNews = \Models\Announcements::SelectPaginated(5, 0);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->Title ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }

        .company-info {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
            font-family: Serif;
            font-size: large;
        }

        .company-info h2 {
            margin: 0 0 10px;
            color: #d35400;
        }

        .news-list {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .news-item {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .news-item:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .news-item h3 {
            margin: 0 0 12px;
            color: #d35400;
            font-size: 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #ffe88b;
            padding-bottom: 6px;
        }

        .news-item p {
            color: rgba(85, 85, 85, 0.81);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .news-item a {
            display: inline-block;
            margin-top: 15px;
            color: #773100;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 15px;
            background-color: #f39c12;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .news-item a:hover {
            text-decoration: underline;
            background-color: #e67e22;
        }

        .btn-more-news {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #fff;
            background-color: #d35400;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease-in-out, transform 0.2s;
        }

        .btn-more-news:hover {
            background-color: #e67e22;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<main>
    <div class="container">
        <div class="company-info">
            <h2><b>Ласкаво просимо до ITLabStudio</b></h2>
            <p>ITLabStudio – це інноваційна IT-компанія, що спеціалізується на розробці програмного забезпечення,
                веб-додатків та цифрових рішень для бізнесу. Ми прагнемо до досконалості, впроваджуючи сучасні
                технології та інноваційні підходи.</p>
        </div>
        <h3>Вам може сподобатись: </h3>
        <div class="news-list">
            <?php if (!empty($latestNews)): ?>
                <?php foreach ($latestNews as $news): ?>
                    <div class="news-item">
                        <h3><?= htmlspecialchars($news['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars(mb_substr($news['text'], 0, 150))) ?>...</p>
                        <a href="/announcements/index/<?= $news['id'] ?>">Читати далі</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Новин поки немає.</p>
            <?php endif; ?>
        </div>
        <a href="/announcements/view/1" class="btn-more-news">Переглянути більше новин</a>
    </div>
</main>
</body>
</html>