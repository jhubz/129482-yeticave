<?php if ($pages_count > 1): ?>

    <ul class="pagination-list">

        <?php if ($page_number === 1): ?>
            <li class="pagination-item pagination-item-prev">Назад</li>
        <? else: ?>
            <li class="pagination-item pagination-item-prev">
                <a href="<?=$page_link;?>page=<?=$page_number - 1;?>">
                    Назад
                </a>
            </li>
        <? endif; ?>

        <?php for ($i = 1; $i <= $pages_count; $i++) { ?>

            <?php if ($i === $page_number): ?>
                <li class="pagination-item pagination-item-active"><a><?=$i;?></a></li>
            <?php else: ?>
                <li class="pagination-item">
                    <a href="<?=$page_link;?>page=<?=$i;?>">
                        <?=$i;?>
                    </a>
                </li>
            <?php endif; ?>

        <?php } ?>

        <?php if ($page_number === $pages_count): ?>
            <li class="pagination-item pagination-item-prev">Вперед</li>
        <?php else: ?>
            <li class="pagination-item pagination-item-next">
                <a href="<?=$page_link;?>page=<?=$page_number + 1;?>">
                    Вперед
                </a>
            </li>
        <?php endif; ?>

    </ul>

<?php endif; ?>
