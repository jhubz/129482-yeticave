<?php if ($categories): ?>

    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $index => $category): ?>
                <?php if ($categories_id[$index] === $current_category_id): ?>
                    <li class="nav__item nav__item--current">
                <?php else: ?>
                    <li class="nav__item">
                <?php endif; ?>
                    <a href="all-lots.php?category_id=<?=$categories_id[$index];?>&page=1"><?=$category;?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

<? endif; ?>
