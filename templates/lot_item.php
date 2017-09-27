<li class="lots__item lot">

    <div class="lot__image">
        <img src="<?=$lot['img'];?>" width="350" height="260" alt="Сноуборд">
    </div>

    <div class="lot__info">
        <span class="lot__category"><?=$lot['category'];?></span>
        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot['lot_id'];?>"><?=htmlspecialchars($lot['title']);?></a></h3>
        <div class="lot__state">
            <div class="lot__rate">
                <?php if ($lot['bets_count'] > 0): ?>
                    <span class="lot__amount"><?=$lot['bets_count'];?> ставок</span>
                <?php else: ?>
                    <span class="lot__amount">Стартовая цена</span>
                <?php endif; ?>
                <span class="lot__cost"><?=$lot['lot_price'];?><b class="rub">р</b></span>
            </div>
            <div class="lot__timer timer">
                <?=time_different_calc(strtotime('now'), strtotime($lot['complete_date']));?>
            </div>
        </div>
    </div>
</li>
