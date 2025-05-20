<?php
$title = "Register Page";
ob_start();
?>
<div class="auth-container">
    <div class="auth-form">
        <h1 class="auth-title">Реєстрація</h1>
        <form action="/auth/register_handler.php" method="POST">
            <div class="input-group">
                <input type="text" name="username" id="username" required>
                <label for="username">Ім'я користувача</label>
            </div>
            <div class="input-group">
                <input type="email" name="email" id="email" required>
                <label for="email">Електронна пошта</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" required>
                <label for="password">Пароль</label>
            </div>
            <button type="submit" class="auth-btn">Зареєструватися</button>
        </form>
        <p class="auth-switch">
            Вже маєте акаунт? <a href="/pages/Login/login.php">Увійти</a>
        </p>
    </div>
</div>
<script src="/pages/Register/register.js"></script>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>