<?php
$tab = filter_input(INPUT_GET, 'tab');
?>
<li class="nav__item <?php if ($category['code'] === $tab): ?>nav__item--current<?php endif; ?>">
    <a href="/category.php?tab=<?= $category['code'] ?>"><?= $category['categoryName']; ?></a>
</li>
