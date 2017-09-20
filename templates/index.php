<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $index => $category): ?>
            <li class="promo__item promo__item--<?=$categories_classes[$index]?>">
                <a class="promo__link" href="all-lots.php?category_id=<?=$categories_id[$index];?>&page=1"><?=$category;?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
        <select class="lots__select">
            <option>Все категории</option>
            <?php foreach ($categories as $category): ?>
                <option><?=$category;?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $index => $lot): ?>
            <?=render_template('templates/lot_item.php', ['lot' => $lot]);?>
        <?php endforeach; ?>
    </ul>

    <?=render_template('templates/paginator.php',
        [
            'page_link' => 'index.php?',
            'pages_count' => $pages_count,
            'page_number' => $page_number
        ]
    );?>

</section>
