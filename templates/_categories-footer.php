<li class="nav__item <?php if ($category['code'] == $_GET['q']): ?>nav__item--current<?php endif; ?>">
                <a href="/category.php?q=<?=$category['code']?>"><?=$category['cat_name'];?></a>
</li>
