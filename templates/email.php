<!DOCTYPE html>
<html lang="ru">
<body>

<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= esc($data['user_name']); ?></p>
<p>Ваша ставка для лота <a
        href="http://952395-yeticave-11/lot.php?id=<?= esc($data['id']); ?>"><?= esc($data['lot_name']); ?></a>
    победила.</p>
<p>Перейдите по ссылке <a href="http://952395-yeticave-11//my-bets.php">мои ставки</a>, чтобы связаться с автором
    объявления.</p>

<small>Интернет аукцион «YetiCave»</small>

</body>
</html>
