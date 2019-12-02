<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <?= include_template('_categories-footer.php', ['category' => $category]); ?>
        <?php endforeach; ?>
    </ul>
</nav>
<?php $classname = isset($errors) ? "form--invalid" : ""; ?>
<form class="form form--add-lot container <?= $classname; ?>" action="add.php" method="post"
      enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $classname = isset($errors['lot_name']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot_name" placeholder="Введите наименование лота"
                   value="<?= getPostVal('lot_name'); ?>">
            <span class="form__error">Введите наименование лота</span>
        </div>
        <?php $classname = isset($errors['categoryId']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select name="categoryId" id="category">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"
                            <?php if ($category['id'] === getPostVal('categoryId')): ?>selected<?php endif; ?>><?= $category['categoryName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="form__error">Выберите категорию</span>
        </div>
    </div>
    <?php $classname = isset($errors['text']) ? "form__item--invalid" : ""; ?>
    <div class="form__item form__item--wide <?= $classname; ?>">
        <label for="text">Описание <sup>*</sup></label>
        <textarea id="text" name="text" placeholder="Напишите описание лота"><?= getPostVal('text'); ?></textarea>
        <span class="form__error">Напишите описание лота</span>
    </div>
    <div class="form__item form__item--file">
        <label for="lot_img">Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="lot_img" id="lot_img" value="">
            <label for="lot_img">
                Добавить
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <?php $classname = isset($errors['st_price']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--small <?= $classname; ?>">
            <label for="st_price">Начальная цена <sup>*</sup></label>
            <input id="st_price" type="text" name="st_price" placeholder="0" value="<?= getPostVal('st_price'); ?>">
            <span class="form__error">Введите начальную цену</span>
        </div>
        <?php $classname = isset($errors['bet_step']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--small <?= $classname; ?>">
            <label for="bet_step">Шаг ставки <sup>*</sup></label>
            <input id="bet_step" type="text" name="bet_step" placeholder="0" value="<?= getPostVal('bet_step'); ?>">
            <span class="form__error">Введите шаг ставки</span>
        </div>
        <?php $classname = isset($errors['dt_end']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="dt_end">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="dt_end" type="date" name="dt_end"
                   placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= getPostVal('dt_end'); ?>">
            <span class="form__error">Введите дату завершения торгов</span>
        </div>
    </div>
    <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте следующие ошибки:
          <?php foreach ($errors as $val): ?>
              <li><strong><?= $val; ?></strong></li>
          <?php endforeach; ?></span>
    <?php endif; ?>
    <button type="submit" class="button">Добавить лот</button>
</form>
