<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?=esc($lot['url']); ?>" width="350" height="260" alt="">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?=esc($lot['category']); ?></span>
        <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=esc($lot['name']); ?></a></h3>
            <div class="lot__state">
                <div class="lot__rate">
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?=esc(priceFormatting ($lot['price'])); ?></span>
                </div>
            <div class="lot__timer timer <?php if (timeExp($lot['expire'])['часы'] < 1) echo ' timer--finishing'; ?>">
            <?=implode(":", timeExp($lot['expire'])); ?>
            </div>

        </div>
     </div>
</li>
