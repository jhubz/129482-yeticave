<section class="rates container">

    <h2>Мои ставки</h2>

    <table class="rates__list">

        <?php foreach ($bets as $bet): ?>
            <tr class="rates__item">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?=htmlspecialchars($bet['img']);?>" width="54" height="40" alt="Сноуборд">
                    </div>
                    <h3 class="rates__title"><a href="lot.php?id=<?=$bet['lot_id'];?>"><?=htmlspecialchars($bet['title']);?></a></h3>
                </td>
                <td class="rates__category">
                    <?=$bet['category']?>
                </td>
                <td class="rates__timer">
                    <div class="timer timer--finishing">
                        <?=time_different_calc(strtotime('now'), strtotime($bet['lot_complete_date']));?>
                    </div>
                </td>
                <td class="rates__price">
                    <?=$bet['bet_price']?>
                </td>
                <td class="rates__time">
                    <?=time_format(strtotime($bet["bet_date"]));?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

</section>
