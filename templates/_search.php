<?php
$link = '/search.php?q';
$value = 'q';
?>
<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <?= include_template('_categories-footer.php', ['category' => $category]); ?>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= esc($_GET['q']); ?></span>»</h2>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <?= include_template('_lots.php', ['lot' => $lot]); ?>
            <?php endforeach; ?>
        </ul>
    </section>
    <?= include_template('_pagination.php', [
        'pages' => $pages,
        'pages_count' => $pages_count,
        'current_page' => $current_page,
        'link' => $link,
        'value' => $value
    ]); ?>
