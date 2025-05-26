<?php
$title = "Редагування профілю";
ob_start();

// Перевірка авторизації
session_start();
if (!isset($_SESSION['authToken'])) {
    header('Location: /pages/Login/login.php');
    exit;
}

// Отримання ID профілю з URL (якщо передано)
$user_id = $_GET['id'] ?? null;
?>

<div class="edit-profile-container">
    <h1>Редагування профілю</h1>
    
    <div class="alert" id="statusMessage"></div>
    
    <form id="editProfileForm" class="edit-profile-form">
        <div class="form-group">
            <label for="username">Ім'я користувача:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="avatar_url">URL аватара:</label>
            <input type="url" id="avatar_url" name="avatar_url">
            <small>Залиште порожнім, щоб використовувати аватар за замовчуванням</small>
        </div>
        
        <h2>Зміна пароля (необов'язково)</h2>
        
        <div class="form-group">
            <label for="current_password">Поточний пароль:</label>
            <input type="password" id="current_password" name="current_password">
        </div>
        
        <div class="form-group">
            <label for="new_password">Новий пароль:</label>
            <input type="password" id="new_password" name="new_password">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Підтвердіть новий пароль:</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="save-btn">Зберегти зміни</button>
            <a href="/pages/Profile/profile.php?tab=profile" class="cancel-btn">Скасувати</a>
        </div>
    </form>
</div>

<script src="edit_profile.js"></script>

<style>
.edit-profile-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.edit-profile-form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="url"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}

.save-btn, .cancel-btn {
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
}

.save-btn {
    background-color: #4CAF50;
    color: white;
    border: none;
}

.cancel-btn {
    background-color: #f0f0f0;
    color: #333;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: none;
}

.alert.success {
    background-color: #dff0d8;
    color: #3c763d;
    border: 1px solid #d6e9c6;
    display: block;
}

.alert.error {
    background-color: #f2dede;
    color: #a94442;
    border: 1px solid #ebccd1;
    display: block;
}

h2 {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

small {
    display: block;
    margin-top: 5px;
    color: #666;
}
</style>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>