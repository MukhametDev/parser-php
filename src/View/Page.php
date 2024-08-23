<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./View/style.css">
    <title>Партнеры</title>
</head>
<body>
    <div class="wrapper">
    <a href="/index.php?action=parsePartners" class="btn btn-primary">Распарсить партнеров</a>
        <div class="cards">
            <?php foreach ($partners as $partner) : ?>
                <div class='card'>
                    <a class='url' href='#'> ID: <span><?php echo htmlspecialchars($partner['id']); ?></span></a>
                    <h2 class='name'><?php echo htmlspecialchars($partner['name']); ?></h2>
                    <p class='detaul-url'>URL партнера: <span><?php echo htmlspecialchars($partner['details_url']); ?></span></p>
                    <p class='website'>Сайт партнера: <span><?php echo htmlspecialchars($partner['website']); ?></span></p>
                    <a class='url' href='?partner_id=<?php echo htmlspecialchars($partner['id']); ?>'>Проекты партнера</a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a class='url' href='?page=<?php echo $i; ?>'><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <a class='top-button' href='#'>Наверх</a>
    </div>
</body>
<script type="module" src="./../js/app.js"></script>
</html>
