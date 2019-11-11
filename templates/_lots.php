<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?=esc($lot['path']); ?>" width="350" height="260" alt="">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?=esc($lot['cat_name']); ?></span>
        <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$lot['id']; ?>"><?=$lot['lot_name']; ?></a></h3>
            <div class="lot__state">
                <div class="lot__rate">
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?=esc(priceFormatting ($lot['st_price'])); ?></span>
                </div>
            <div class="lot__timer timer <?php if (timeExp($lot['dt_end'])['часы'] < 1) echo ' timer--finishing'; ?>">
            <?php if (timeExp($lot['dt_end'])['часы'] >=0 && timeExp($lot['dt_end'])['минуты'] >=0): ?>
            <?=implode(":", timeExp($lot['dt_end'])); ?>
            <?php else: ?>
                продано
            <?php endif; ?>
            </div>

        </div>
     </div>
</li>

