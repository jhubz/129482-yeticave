<section class="lot-item container">
    <h2><?=$lots[$id]['title'];?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?=$lots[$id]['img'];?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?=$lots[$id]['category'];?></span></p>
            <p class="lot-item__description"><?=$lots[$id]['description'];?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($user): ?>
              <div class="lot-item__state">
                  <div class="lot-item__timer timer">
                      10:54:12
                  </div>
                  <div class="lot-item__cost-state">
                      <div class="lot-item__rate">
                          <span class="lot-item__amount">Текущая цена</span>
                          <span class="lot-item__cost"><?=$lots[$id]['price'];?></span>
                      </div>
                      <div class="lot-item__min-cost">
                          Мин. ставка <span>12 000 р</span>
                      </div>
                  </div>
                  <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
                      <p class="lot-item__form-item">
                          <label for="cost">Ваша ставка</label>
                          <input id="cost" type="number" name="cost" placeholder="12 000">
                      </p>
                      <button type="submit" class="button">Сделать ставку</button>
                  </form>
              </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span>4</span>)</h3>
                <!-- заполните эту таблицу данными из массива $bets-->
                <table class="history__list">
                    <?php foreach ($bets as $bet): ?>
                        <tr class="history__item">
                            <td class="history__name"><?=$bet["name"];?></td>
                            <td class="history__price"><?=$bet["price"];?>р</td>
                            <td class="history__time"><?=time_format($bet["ts"]);?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </div>
        </div>
    </div>
</section>
