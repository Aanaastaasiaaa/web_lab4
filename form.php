<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анкета программиста</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background: linear-gradient(145deg, #fbbf24 0%, #f59e0b 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }
        
        .container {
            max-width: 820px;
            margin: 0 auto;
            background: #fff9e6;
            border-radius: 20px;
            border: 1px solid #fde68a;
            overflow: hidden;
        }
        
        .header {
            background: #fffbeb;
            padding: 35px 30px;
            text-align: center;
            border-bottom: 2px solid #fcd34d;
        }
        
        .header h1 {
            color: #92400e;
            font-size: 2.2em;
            margin-bottom: 8px;
        }
        
        .header p {
            color: #b45309;
            font-size: 1.1em;
        }
        
        .form-content {
            padding: 35px;
            background: white;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group.has-error label {
            color: #dc2626;
        }
        
        .form-group.has-error input,
        .form-group.has-error textarea,
        .form-group.has-error select {
            border-color: #dc2626;
            background: #fef2f2;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #92400e;
            font-weight: 600;
        }
        
        .required::after {
            content: " *";
            color: #dc2626;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 0.85em;
            margin-top: 5px;
            padding: 5px 10px;
            background: #fef2f2;
            border-radius: 6px;
            border-left: 3px solid #dc2626;
        }
        
        .global-error {
            background: #fef2f2;
            color: #991b1b;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 2px solid #fca5a5;
        }
        
        .global-error ul {
            margin-left: 20px;
            margin-top: 10px;
        }
        
        .global-error li {
            margin: 5px 0;
        }
        
        .success-message {
            background: #f0fdf4;
            color: #166534;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 2px solid #86efac;
            text-align: center;
        }
        
        .success-message a {
            color: #166534;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 2px solid #86efac;
        }
        
        input[type="text"],
        input[type="tel"],
        input[type="email"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #fde68a;
            border-radius: 12px;
            font-size: 1em;
            transition: border-color 0.2s;
            background: #fefce8;
        }
        
        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #f59e0b;
            background: white;
        }
        
        .radio-group {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            background: #fefce8;
            padding: 15px 20px;
            border-radius: 12px;
            border: 2px solid #fde68a;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
        }
        
        .radio-option input[type="radio"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            accent-color: #f59e0b;
        }
        
        .radio-option label {
            margin-bottom: 0;
            font-weight: 500;
            color: #92400e;
        }
        
        select[multiple] {
            height: 200px;
            padding: 10px;
        }
        
        select[multiple] option {
            padding: 8px 12px;
        }
        
        select[multiple] option:checked {
            background: #fbbf24;
            color: #92400e;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            background: #fefce8;
            padding: 15px 20px;
            border-radius: 12px;
            border: 2px solid #fde68a;
        }
        
        .checkbox-group input[type="checkbox"] {
            margin-right: 12px;
            width: 20px;
            height: 20px;
            accent-color: #f59e0b;
        }
        
        .checkbox-group label {
            margin-bottom: 0;
            font-weight: 500;
            color: #92400e;
            flex: 1;
        }
        
        .hint {
            font-size: 0.85em;
            color: #b45309;
            margin-top: 5px;
        }
        
        button {
            background: #f59e0b;
            color: white;
            border: none;
            padding: 16px 32px;
            font-size: 1.2em;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.2s;
        }
        
        button:hover {
            background: #d97706;
        }
        
        @media (max-width: 768px) {
            .form-content { padding: 20px; }
            .header h1 { font-size: 1.8em; }
            .radio-group { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Анкета</h1>
            <p>Заполните форму </p>
        </div>
        
        <div class="form-content">
            <?php if (isset($success_message)): ?>
                <div class="success-message">
                    <?= $success_message ?>
                    <br><br>
                    <a href="index.php">Заполнить новую анкету</a>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors) && !isset($errors['db'])): ?>
                <div class="global-error">
                    <strong>Пожалуйста, исправьте следующие ошибки:</strong>
                    <ul>
                        <?php foreach ($errors as $field => $error): ?>
                            <?php if ($field !== 'db'): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errors['db'])): ?>
                <div class="global-error">
                    <?= htmlspecialchars($errors['db']) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php">
                <!-- ФИО -->
                <div class="form-group <?= isset($errors['full_name']) ? 'has-error' : '' ?>">
                    <label for="full_name" class="required">ФИО</label>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?= htmlspecialchars($old_data['full_name'] ?? $saved_data['full_name'] ?? '') ?>" 
                           placeholder="Иванов Иван Иванович" required>
                    <div class="hint">Только буквы, пробелы и дефисы, не более 150 символов</div>
                    <?php if (isset($errors['full_name'])): ?>
                        <div class="error-message"><?= $errors['full_name'] ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Телефон -->
                <div class="form-group <?= isset($errors['phone']) ? 'has-error' : '' ?>">
                    <label for="phone" class="required">Телефон</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?= htmlspecialchars($old_data['phone'] ?? $saved_data['phone'] ?? '') ?>" 
                           placeholder="+7 (999) 123-45-67" required>
                    <div class="hint">Цифры, пробелы, дефисы, скобки, + (6-20 символов)</div>
                    <?php if (isset($errors['phone'])): ?>
                        <div class="error-message"><?= $errors['phone'] ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Email -->
                <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                    <label for="email" class="required">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($old_data['email'] ?? $saved_data['email'] ?? '') ?>" 
                           placeholder="ivan@example.com" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-message"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Дата рождения -->
                <div class="form-group <?= isset($errors['birth_date']) ? 'has-error' : '' ?>">
                    <label for="birth_date" class="required">Дата рождения</label>
                    <input type="date" id="birth_date" name="birth_date" 
                           value="<?= htmlspecialchars($old_data['birth_date'] ?? $saved_data['birth_date'] ?? '') ?>" 
                           required>
                    <?php if (isset($errors['birth_date'])): ?>
                        <div class="error-message"><?= $errors['birth_date'] ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Пол -->
                <div class="form-group <?= isset($errors['gender']) ? 'has-error' : '' ?>">
                    <label class="required">Пол</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="male" name="gender" value="male" required
                                <?= (($old_data['gender'] ?? $saved_data['gender'] ?? '') == 'male') ? 'checked' : '' ?>>
                            <label for="male">Мужской</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="female" name="gender" value="female" required
                                <?= (($old_data['gender'] ?? $saved_data['gender'] ?? '') == 'female') ? 'checked' : '' ?>>
                            <label for="female">Женский</label>
                        </div>
                    </div>
                    <?php if (isset($errors['gender'])): ?>
                        <div class="error-message"><?= $errors['gender'] ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Языки -->
                <div class="form-group <?= isset($errors['languages']) ? 'has-error' : '' ?>">
                    <label for="languages" class="required">Любимые языки программирования</label>
                    <select name="languages[]" id="languages" multiple required size="6">
                        <?php
                        $selected_langs = $old_data['languages'] ?? $saved_data['languages'] ?? [];
                        if (!is_array($selected_langs)) {
                            $selected_langs = explode(',', $selected_langs);
                        }
                        $languages = [
                            1 => 'Pascal', 2 => 'C', 3 => 'C++', 4 => 'JavaScript',
                            5 => 'PHP', 6 => 'Python', 7 => 'Java', 8 => 'Haskell',
                            9 => 'Clojure', 10 => 'Prolog', 11 => 'Scala', 12 => 'Go'
                        ];
                        foreach ($languages as $id => $name): ?>
                            <option value="<?= $id ?>" 
                                <?= in_array((string)$id, $selected_langs) ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="hint">Держите Ctrl для выбора нескольких</div>
                    <?php if (isset($errors['languages'])): ?>
                        <div class="error-message"><?= $errors['languages'] ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Биография -->
                <div class="form-group <?= isset($errors['biography']) ? 'has-error' : '' ?>">
                    <label for="biography" class="required">Биография</label>
                    <textarea id="biography" name="biography" rows="6" 
                              placeholder="Расскажите о себе..." 
                              required><?= htmlspecialchars($old_data['biography'] ?? $saved_data['biography'] ?? '') ?></textarea>
                    <div class="hint">Максимум 5000 символов</div>
                    <?php if (isset($errors['biography'])): ?>
                        <div class="error-message"><?= $errors['biography'] ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Чекбокс -->
                <div class="form-group <?= isset($errors['contract_accepted']) ? 'has-error' : '' ?>">
                    <div class="checkbox-group">
                        <input type="checkbox" id="contract" name="contract_accepted" value="1" required
                            <?= (isset($old_data['contract_accepted']) || isset($saved_data['contract_accepted'])) ? 'checked' : '' ?>>
                        <label for="contract" class="required">Я ознакомлен(а) с условиями</label>
                    </div>
                    <?php if (isset($errors['contract_accepted'])): ?>
                        <div class="error-message"><?= $errors['contract_accepted'] ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Кнопка -->
                <button type="submit">Сохранить анкету</button>
            </form>
        </div>
    </div>
</body>
</html>
