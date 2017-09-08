<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
    <?php foreach ($bets_data as $bet_data): ?>
      <tr class="rates__item">
        <td class="rates__info">
          <div class="rates__img">
            <img src="<?=$lots[$bet_data['id']]['img']?>" width="54" height="40" alt="Сноуборд">
          </div>
          <h3 class="rates__title"><a href="lot.php?id=<?=$bet_data['id'];?>"><?=$lots[$bet_data['id']]['title']?></a></h3>
        </td>
        <td class="rates__category">
          <?=$lots[$bet_data['id']]['category']?>
        </td>
        <td class="rates__timer">
          <div class="timer timer--finishing">07:13:34</div>
        </td>
        <td class="rates__price">
          <?=$bet_data['cost']?>
        </td>
        <td class="rates__time">
          <?=time_format($bet_data['date'])?>
        </td>
      </tr>
    <?php endforeach; ?>

  </table>
</section>