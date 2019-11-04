INSERT INTO categories (cat_name, code)
VALUES ('Доски и лыжи', 'boards'),
       ('Крепления', 'attachment'),
       ('Ботинки', 'boots'),
       ('Одежда', 'clothing'),
       ('Инструменты', 'toold'),
       ('Разное', 'other');

INSERT INTO users (email, name, password, contacts, lot_id, bet_id)
VALUES ('test1@gmail.com', 'Кольтира', '123456', '+79111234567', '1', '1'),
       ('test2@yandex.ru', 'Тассариан', '654321', '+79117654321', '2','2');

INSERT INTO lots (lot_name, cat_id, st_price, path, dt_end, text, bet_step, autor_id)
VALUES        ('2014 Rossignol District Snowboard', '1', '10999', 'img/lot-1.jpg', '2019-11-06', 'Описание сноуборда', '100', '1'),
              ('DC Ply Mens 2016/2017 Snowboard', '1', '159999', 'img/lot-2.jpg', '2019-12-01', 'Описание сноуборда 2', '1000', '1'),
              ('Крепления Union Contact Pro 2015 года размер L/XL', '2', '8000', 'img/lot-3.jpg', '2019-12-01', 'Описание крепления', '75', '1'),
              ('Ботинки для сноуборда DC Mutiny Charocal', '3', '10999', 'img/lot-4.jpg', '2019-12-03', 'Описание ботинок', '90', '2'),
              ('Куртка для сноуборда DC Mutiny Charocal', '4', '7500', 'img/lot-5.jpg', '2019-12-04', 'Описание куртки', '50', '2'),
              ('Маска Oakley Canopy', '6', '5400', 'img/lot-6.jpg', '2019-12-05', 'Описание маски', '40', '2');

INSERT INTO bets (value, user_id, lot_id)
VALUES        ('5440', '1', '6'),
              ('5480', '1', '6');

/*получить все категории;*/
SELECT id, cat_name FROM categories;

 /*получить самые новые, открытые лоты. Каждый лот должен включать название,
 стартовую цену, ссылку на изображение, цену, название категории;*/
SELECT  lot_name, st_price, path, c.cat_name, value, l.dt_add, l.winner_id FROM lots l
JOIN categories c ON c.id = l.cat_id
JOIN bets b ON b.lot_id = l.id
WHERE l.winner_id = 0
ORDER BY l.dt_add

 /*показать лот по его id.
 Получить также название категории, к которой принадлежит лот;*/
SELECT l.id, lot_name, cat_name FROM lots l /*3*/
JOIN categories c
ON l.id = c.id;
WHERE l.id = 1;

/*обновить название лота по его идентификатору;*/
UPDATE lots SET lot_name = 'Измененное название' WHERE id = 1;

/*получить список ставок для лота по его идентификатору с сортировкой по дате.*/
SELECT id, value, dt_add FROM bets WHERE lot_id = 6 ORDER BY dt_add;

