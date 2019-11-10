
<nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $category): ?>
        <li class="nav__item">
            <a href="pages/all-lots.html"><?=$category['cat_name'];?></a>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <?php foreach ($lots as $lot): ?>
    <section class="lot-item container">
      <h2><?=$lot['lot_name']; ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=esc($lot['path']); ?>" width="730" height="548" alt="<?=$lot['lot_name']; ?><">
          </div>
          <p class="lot-item__category">Категория: <span><?=esc($lot['cat_name']); ?></span></p>
          <p class="lot-item__description"></p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
            <div class="lot-item__timer timer <?php if (timeExp($lot['dt_end'])['часы'] < 1) echo ' timer--finishing'; ?>">
                <?php if (timeExp($lot['dt_end'])['часы'] >=0 && timeExp($lot['dt_end'])['минуты'] >=0): ?>
                <?=implode(":", timeExp($lot['dt_end'])); ?>
                <?php else: ?>
                    продано
                <?php endif; ?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=$lot['st_price'];?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=$lot['st_price'] + $lot['bet_step'];?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php endforeach; ?>
