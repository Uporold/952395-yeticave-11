<nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $category): ?>
        <?=include_template('_categories-footer.php', ['category' => $category]); ?>
      <?php endforeach; ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span>Union</span>»</h2>
        <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
                <?=include_template('_lots.php', ['lot' => $lot]); ?>
        <?php endforeach; ?>
        </ul>
      </section>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
      </ul>
    </div>
