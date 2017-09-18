<section class="lot-item container">
    <h2><?=$lot['title'];?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?=$lot['img'];?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?=$lot['category'];?></span></p>
            <p class="lot-item__description"><?=$lot['description'];?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($user && !($user['id'] === $lot['author_id'])): ?>
                <?php if (!$is_done_bet): ?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer">
                            <?=time_different_calc(strtotime('now'), strtotime($lot['complete_date']));?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?=$lot['lot_price'];?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?=$lot['lot_price'] + $lot['bet_step'];?> р</span>
                            </div>
                        </div>
                        <?php if ($errors): ?>
                            <form class="lot-item__form form--invalid" action="lot.php?id=<?=$lot['lot_id'];?>" method="post">
                        <?php else: ?>
                            <form class="lot-item__form" action="lot.php?id=<?=$lot['lot_id'];?>" method="post">
                        <? endif; ?>
                            <?php if (in_array('cost', $errors)): ?>
                                <p class="lot-item__form-item form__item--invalid">
                            <?php else: ?>
                                <p class="lot-item__form-item">
                            <? endif; ?>
                                <label for="cost">Ваша ставка</label>
                                <?php if (in_array('cost', $errors)): ?>
                                    <input id="cost" style="border: 1px solid #f84646;" type="number" name="cost" placeholder="<?=$lot['lot_price'] + $lot['bet_step'];?>" value="<?=$cost;?>">
                                    <span class="form__error"><?=$errors_messages['cost'];?></span>
                                <?php else: ?>
                                    <input id="cost" type="number" name="cost" placeholder="<?=$lot['lot_price'] + $lot['bet_step'];?>" value="<?=$cost;?>">
                                <?php endif; ?>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?=$bets_count;?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bets as $bet): ?>
                        <tr class="history__item">
                            <td class="history__name"><?=$bet["user_name"];?></td>
                            <td class="history__price"><?=$bet["bet_price"];?>р</td>
                            <td class="history__time"><?=time_format(strtotime($bet["bet_date"]));?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </div>
        </div>
    </div>
</section>
