<?php
header('Content-Type: text/html; charset=UTF-8');

// Подключение к БД
$user = 'u82277';
$pass = '1452026';
$db = new PDO('mysql:host=localhost;dbname=u82277', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Функция для сохранения данных в Cookies на год
function saveToCookies($data) {
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        setcookie("saved_$key", $value, time() + 365*24*60*60, '/');
    }
}

// Функция для загрузки сохраненных данных из Cookies
function loadFromCookies() {
    $data = [];
    foreach ($_COOKIE as $key => $value) {
        if (strpos($key, 'saved_') === 0) {
            $field = substr($key, 6);
            if ($field === 'languages' && strpos($value, ',') !== false) {
                $data[$field] = explode(',', $value);
            } else {
                $data[$field] = $value;
            }
        }
    }
    return $data;
}

// Функция валидации с регулярными выражениями
function validateForm($data) {
    $errors = [];
    
    // 1. ФИО - только буквы, пробелы и дефисы
    if (empty($data['full_name'])) {
        $errors['full_name'] = 'ФИО обязательно для заполнения';
    } elseif (!preg_match('/^[а-яА-ЯёЁa-zA-Z\s-]+$/u', $data['full_name'])) {
        $errors['full_name'] = 'ФИО может содержать только буквы, пробелы и дефисы';
    } elseif (strlen($data['full_name']) > 150) {
        $errors['full_name'] = 'ФИО не должно превышать 150 символов';
    }
    
    // 2. Телефон - цифры, пробелы, дефисы, скобки, плюс
    if (empty($data['phone'])) {
        $errors['phone'] = 'Телефон обязателен для заполнения';
    } elseif (!preg_match('/^[\+\d\s\-\(\)]{6,20}$/', $data['phone'])) {
        $errors['phone'] = 'Телефон может содержать только цифры, пробелы, дефисы, скобки и + (6-20 символов)';
    }
    
    // 3. Email - стандартный формат
    if (empty($data['email'])) {
        $errors['email'] = 'Email обязателен для заполнения';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный формат email (пример: name@domain.com)';
    }
    
    // 4. Дата рождения - формат ГГГГ-ММ-ДД
    if (empty($data['birth_date'])) {
        $errors['birth_date'] = 'Дата рождения обязательна для заполнения';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['birth_date'])) {
        $errors['birth_date'] = 'Дата должна быть в формате ГГГГ-ММ-ДД';
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $data['birth_date']);
        if (!$date) {
            $errors['birth_date'] = 'Некорректная дата';
        } elseif ($date > new DateTime()) {
            $errors['birth_date'] = 'Дата рождения не может быть в будущем';
        }
    }
    
    // 5. Пол - только male/female
    if (empty($data['gender'])) {
        $errors['gender'] = 'Выберите пол';
    } elseif (!in_array($data['gender'], ['male', 'female'])) {
        $errors['gender'] = 'Некорректное значение пола';
    }
    
    // 6. Языки - только ID от 1 до 12
    if (empty($data['languages']) || !is_array($data['languages'])) {
        $errors['languages'] = 'Выберите хотя бы один язык программирования';
    } else {
        foreach ($data['languages'] as $lang_id) {
            if (!preg_match('/^[1-9]$|^1[0-2]$/', $lang_id)) {
                $errors['languages'] = 'Выбран недопустимый язык программирования';
                break;
            }
        }
    }
    
    // 7. Биография - любой текст, но не больше 5000
    if (empty($data['biography'])) {
        $errors['biography'] = 'Биография обязательна для заполнения';
    } elseif (strlen($data['biography']) > 5000) {
        $errors['biography'] = 'Биография не должна превышать 5000 символов';
    }
    
    // 8. Чекбокс - должен быть отмечен
    if (!isset($data['contract_accepted'])) {
        $errors['contract_accepted'] = 'Необходимо подтвердить ознакомление с контрактом';
    }
    
    return $errors;
}

// GET запрос - показываем форму
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Если есть параметр save - успех
    if (!empty($_GET['save'])) {
        $success_message = 'Спасибо, результаты сохранены.';
    }
    
    // Загружаем сохраненные данные из Cookies
    $saved_data = loadFromCookies();
    
    // Загружаем ошибки из Cookies (если есть)
    $errors = [];
    if (isset($_COOKIE['form_errors'])) {
        $errors = json_decode($_COOKIE['form_errors'], true);
        // Удаляем Cookies с ошибками после использования
        setcookie('form_errors', '', time() - 3600, '/');
    }
    
    // Загружаем старые данные из Cookies (если есть ошибки)
    $old_data = [];
    if (isset($_COOKIE['old_data'])) {
        $old_data = json_decode($_COOKIE['old_data'], true);
        setcookie('old_data', '', time() - 3600, '/');
    }
    
    include 'form.php';
    exit();
}

// POST запрос - проверяем и сохраняем
$errors = validateForm($_POST);

if (!empty($errors)) {
    // Сохраняем ошибки в Cookies (на время сессии)
    setcookie('form_errors', json_encode($errors), 0, '/');
    
    // Сохраняем введенные данные в Cookies (на время сессии)
    setcookie('old_data', json_encode($_POST), 0, '/');
    
    // Перенаправляем обратно GET-запросом
    header('Location: index.php');
    exit();
}

// Если ошибок нет - сохраняем в БД
try {
    $db->beginTransaction();
    
    // Вставляем основную информацию
    $stmt = $db->prepare("
        INSERT INTO applications 
        (full_name, phone, email, birth_date, gender, biography, contract_accepted) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $contract = isset($_POST['contract_accepted']) ? 1 : 0;
    
    $stmt->execute([
        $_POST['full_name'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['birth_date'],
        $_POST['gender'],
        $_POST['biography'],
        $contract
    ]);
    
    $application_id = $db->lastInsertId();
    
    // Вставляем языки
    if (!empty($_POST['languages'])) {
        $stmt = $db->prepare("
            INSERT INTO application_languages (application_id, language_id) 
            VALUES (?, ?)
        ");
        
        foreach ($_POST['languages'] as $lang_id) {
            $stmt->execute([$application_id, $lang_id]);
        }
    }
    
    $db->commit();
    
    // Сохраняем данные в Cookies на год
    saveToCookies($_POST);
    
    // Успех - редирект с параметром save
    header('Location: index.php?save=1');
    exit();
    
} catch(PDOException $e) {
    $db->rollBack();
    
    // Сохраняем ошибку БД
    setcookie('form_errors', json_encode(['db' => 'Ошибка базы данных: ' . $e->getMessage()]), 0, '/');
    setcookie('old_data', json_encode($_POST), 0, '/');
    
    header('Location: index.php');
    exit();
}
?>
