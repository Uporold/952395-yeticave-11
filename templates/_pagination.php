<?php if ($pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a <?php if ($current_page > 1): ?>href="<?= $link; ?>=<?= $_GET[$value]; ?>&page=<?= $current_page - 1; ?>"<?php endif; ?>>Назад</a>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?php if ((int)$page === (int)$current_page): ?>pagination-item-active<?php endif; ?>">
                <a href="<?= $link; ?>=<?= $_GET[$value]; ?>&page=<?= $page; ?>"><?= $page; ?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a <?php if ($current_page < $page): ?>href="<?= $link; ?>=<?= $_GET[$value]; ?>&page=<?= $current_page + 1; ?>"<?php endif; ?>>Вперед</a>
        </li>
    </ul>
    </div>
<?php endif; ?>

