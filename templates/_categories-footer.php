<li class="nav__item <?php if ($category['code'] == $_GET['tab']): ?>nav__item--current<?php endif; ?>">
    <a href="/category.php?tab=<?= $category['code'] ?>"><?= $category['cat_name']; ?></a>
</li>
