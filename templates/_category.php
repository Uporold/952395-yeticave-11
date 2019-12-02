<?php
$link = '/category.php?tab';
$value = 'tab';
?>
<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item <?php if ($category['code'] === $_GET['tab']): ?>nav__item--current<?php endif; ?>">
                <a href="/category.php?tab=<?= $category['code'] ?>"><?= $category['categoryName']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
            <h2>Все лоты в категории <span><?= $categoryName[0]['categoryName']; ?></span></h2>
            <?php if(!empty($lots)): ?>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <?= include_template('_lots.php', ['lot' => $lot]); ?>
            <?php endforeach; ?>
        </ul>
            <?php else:?>


    <p>Активных лотов в категории <?= $categoryName[0]['categoryName']; ?> не найдено</p>
    <?php endif?>

    </section>
    <?= include_template('_pagination.php', [
        'pages' => $pages,
        'pages_count' => $pages_count,
        'current_page' => $current_page,
        'link' => $link,
        'value' => $value
    ]); ?>

</div>
