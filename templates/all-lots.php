<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span>«<?=$category_name;?>»</span></h2>
        <ul class="lots__list">
            <?php foreach ($lots as $index => $lot): ?>
                <?=render_template('templates/lot_item.php', ['lot' => $lot]);?>
            <?php endforeach; ?>
        </ul>
    </section>

    <?=render_template('templates/paginator.php',
        [
            'page_link' => "all-lots.php?category_id=$current_category_id&",
            'pages_count' => $pages_count,
            'page_number' => $page_number
        ]
    );?>

</div>
