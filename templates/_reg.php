<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <?= include_template('_categories-footer.php', ['category' => $category]); ?>
        <?php endforeach; ?>
    </ul>
</nav>
<?php $classname = isset($errors) ? "form--invalid" : ""; ?>
<form class="form container <?= $classname; ?>" action="reg.php" method="post" autocomplete="off"
      enctype="multipart/form-data">
    <h2>Регистрация нового аккаунта</h2>
    <?php $classname = isset($errors['email']) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= getPostVal('email'); ?>">
        <span class="form__error">Введите e-mail</span>
    </div>
    <?php $classname = isset($errors['password']) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error">Введите пароль</span>
    </div>
    <?php $classname = isset($errors['name']) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= getPostVal('name'); ?>">
        <span class="form__error">Введите имя</span>
    </div>
    <?php $classname = isset($errors['contacts']) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>">
        <label for="contacts">Контактные данные <sup>*</sup></label>
        <textarea id="contacts" name="contacts"
                  placeholder="Напишите как с вами связаться"><?= getPostVal('contacts'); ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.
          <?php foreach ($errors as $val): ?>
              <li><strong><?= $val; ?></strong></li>
          <?php endforeach; ?></span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
