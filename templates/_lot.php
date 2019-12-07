<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <?= include_template('_categories-footer.php', ['category' => $category]); ?>
        <?php endforeach; ?>
    </ul>
</nav>
<?php foreach ($lots as $lot): ?>
    <section class="lot-item container">
        <h2><?= esc($lot['lot_name']); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="../uploads/<?= esc($lot['path']); ?>" width="730" height="548"
                         alt="<?= esc($lot['lot_name']); ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= esc($lot['categoryName']); ?></span></p>
                <p class="lot-item__description"><?= esc($lot['text']); ?></p>
            </div>
            <div class="lot-item__right">
                    <div class="lot-item__state">
                    <div
                        class="lot-item__timer timer <?php if (timeExp($lot['dt_end'])['часы'] < 1) echo ' timer--finishing'; ?>">
                        <?php if (timeExp($lot['dt_end'])['часы'] >= 0 && timeExp($lot['dt_end'])['минуты'] >= 0): ?>
                            <?= implode(":", timeExp($lot['dt_end'])); ?>
                        <?php else: ?>
                            продано
                        <?php endif; ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span
                                class="lot-item__cost"><?= priceFormatting($lot['current_price'] ?? $lot['st_price']); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка
                            <span><?= priceFormatting(($lot['current_price'] ?? $lot['st_price']) + $lot['bet_step']); ?></span>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $lot['autor_id'] && ((int)$lot['winner_id'] === 0) && (time() < strtotime($lot['dt_end'])) && ($_SESSION['user']['id'] !== $bets[0]['user_id'])): ?>
                        <form class="lot-item__form" action="lot.php?id=<?= (int)$lot['id']; ?> " method="post"
                              autocomplete="off" enctype="multipart/form-data">
                            <?php $classname = isset($error) ? "form__item--invalid" : ""; ?>
                            <p class="lot-item__form-item form__item <?= $classname; ?>">
                                <label for="value">Ваша ставка</label>
                                <input id="value" type="text" name="value"
                                       placeholder="<?= ($lot['current_price'] ?? $lot['st_price']) + $lot['bet_step']; ?>"
                                       value="<?= getPostVal('value'); ?>">
                                <span class="form__error"><?= $error ?? null; ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                        <?php endif; ?>
                        </div>
                        <?php if ($betsCount[0]['count'] > 0): ?>
                            <div class="history">
                                <h3>История ставок (<span><?= $betsCount[0]['count']; ?></span>)</h3>
                                <table class="history__list">
                                    <?php foreach ($bets as $bet): ?>
                                        <tr class="history__item">
                                            <td class="history__name"><?= esc($bet['name']); ?></td>
                                            <td class="history__price"><?= priceFormatting($bet['value']); ?></td>
                                            <td class="history__time"><?= timeAgo($bet['dt_add']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                    <?php endif; ?>
            </div>
        </div>
    </section>
<?php endforeach; ?>
