<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name; //создаёт путь к файлу $name
    $result = '';

    if (!is_readable($name)) { //если файл не существует или не доступен для чтения
        return $result; // возвращает пустую строку
    }

    ob_start(); //включение буферизации вывода
    extract($data); //импортирует переменные из массива
    require $name; //выполняет файл $name

    $result = ob_get_clean(); //получает содержимое текущего буфера и удаляет его

    return $result; //вовзращает полученные данные
}

/**
 * Подсчитывает количества задач у проекта
 * 
 * @param array $projectsId Массив id проектов из таблицы задач
 * @param int $id id проекта
 * 
 * @return int количество задач для проекта
 */
function countTasks(array $projectsId, int $id)
{
    $count = 0;
    foreach ($projectsId as $projectId) {
        if ($projectId == $id) {
            $count++;
        }
    }
    return $count;
}

/**
 * Проверяет осталось ли 24 часа или меньше от текущей даты, до переданной
 * 
 * @param string $date Дата в виде строки
 * 
 * @return bool true - если осталось 24 чаcа или меньше, иначе false
 */
function dateCheck(string $date)
{
    if ($date) {
        $date = strtotime($date);
        $curDate = strtotime(date('Y/m/d'));
        if (($date - $curDate) / 3600 <= 24) {
            return true;
        }
    }
    return false;
}

/**
 * Возвращает массив с данными из $link
 * 
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array Массив с данными из БД
 */
function dbGetResult($link, $sql, $data = [])
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $stmt->execute();
    $result = $stmt->get_result();
    return (!is_bool($result)) ? $result->fetch_all(MYSQLI_ASSOC) : '';
}

/**
 * Возвращает готовые к использованию данные от пользователя
 * 
 * @param string $data Данные для обработки
 *
 * @return string Готовые для использования данные, если данные переданные данные были пусты возвращает NULL
 */
function checkInput($data)
{
    $data = trim($data);
    if (empty($data)) {
        return NULL;
    }
    return $data;
}

/**
 * Возвращает готовые для вывода в html данные
 * 
 * @param array $data Данные для обработки
 *
 * @return array Готовые для вывода в html данные
 */
function preparingDataOutput($data)
{
    if (isset($data)) {
        foreach ($data as $key => $array) {
            foreach ($array as $innerKey => $value) {
                $data[$key][$innerKey] = htmlspecialchars($value);
            }
        }
    }
    return $data;
}
