<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./View/style.css">
    <title>Проекты партнера <?php echo htmlspecialchars($partnerId); ?></title>
</head>

<body>
    <div class="wrapper">
        <div class="back-button">
            <a href='?page=<?php echo htmlspecialchars($page); ?>'>Вернуться к списку партнеров</a>
        </div>
        <h1 class="title">Проекты партнера <?php echo htmlspecialchars($partnerId); ?></h1>
        <div class="cards">
            <?php if (!empty($projects)) : ?>
                <?php foreach ($projects as $project) : ?>
                    <div class='card'>
                        <a>ID: <?php echo htmlspecialchars($project['id']); ?></a>
                        <p >Сайт проекта: <?php echo htmlspecialchars($project['project_url']); ?></p>
                        <p class='version'>Редакция проекта: <?php echo htmlspecialchars($project['product_version']); ?></p>
                        <p class='text'>Описание проекта: <?php echo htmlspecialchars($project['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Проекты для этого партнера не найдены.</p>
            <?php endif; ?>
        </div>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a class='url' href='?partner_id=<?php echo htmlspecialchars($partnerId); ?>&page=<?php echo $i; ?>'><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
<script type="module" src="./../js/app.js"></script>
</html>