<nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $category): ?>
        <li class="nav__item <?php if ($category['code'] == $_GET['q']): ?>nav__item--current<?php endif; ?>">
            <a href="/category.php?q=<?=$category['code']?>"><?=$category['cat_name'];?></a>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
      <?php foreach ($lots as $lot): ?>
        <h2>Все лоты в категории <span><?=$lot['cat_name'];?></span></h2>
        <?php endforeach; ?>
        <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
                <?=include_template('_lots.php', ['lot' => $lot]); ?>
            <?php endforeach; ?>
        </ul>
      </section>
      <?=include_template('_pagination.php', [
            'pages' => $pages,
            'pages_count' => $pages_count,
            'cur_page' => $cur_page,
            'link' => $link
    ]); ?>

    </div>
