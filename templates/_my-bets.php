<nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $category): ?>
        <?=include_template('_categories-footer.php', ['category' => $category]); ?>
        <?php endforeach; ?>
      </ul>
    </nav>

    <section class="rates container">
    <?php if(!empty($bets)): ?>
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach($bets as $bet): ?>
        <tr class="rates__item <?php if ($bet['winner_id'] == $_SESSION['user']['id']): ?>
                               <?= 'rates__item--win'; ?>
                               <?php elseif (($bet['winner_id'] > 0) && (time() > strtotime($bet['dt_end']))): ?>
                               <?= 'rates__item--end'; ?>
                               <?php endif; ?>">
            <td class="rates__info">
                <div class="rates__img">
                    <a href="/lot.php?id=<?= $bet['lot_id']; ?>"><img src="/uploads/<?= $bet['path']; ?>" alt=""></a>
                </div>
                <div><h3 class="rates__title"><a href="/lot.php?id=<?= $bet['lot_id']; ?>"><?= esc($bet['lot_name']); ?></a></h3>
                <?php if($bet['winner_id'] == $_SESSION['user']['id']): ?>
                    <p><?= esc($bet['contacts']); ?></p>
                <?php endif; ?>
                </div>
            </td>
            <td class="rates__category">
                <?= $bet['cat_name']; ?>
            </td>
            <td class="rates__timer">
                <?php if ($bet['winner_id'] == $_SESSION['user']['id']): ?>
                <div class="timer timer--win">Ставка выиграла</div>
                <?php elseif (($bet['winner_id'] > 0) && (time() > strtotime($bet['dt_end']))): ?>
                <div class="timer timer--end">Торги окончены</div>
                <?php else: ?>
                <div class="timer <?php if (timeExp($bet['dt_end'])) echo 'timer--finishing';?>">
                <?=implode(":", timeExp($bet['dt_end'])); ?>
                </div>
                <?php endif; ?>
            </td>
            <td class="rates__price">
                <?= priceFormatting($bet['value']); ?></b>
            </td>
            <td class="rates__time">
                <?= timeAgo($bet['dt_add']); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
        <?php else: ?>
        <h2>Список ставок пуст.</h2>
        <?php endif; ?>
</section>
