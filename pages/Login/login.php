<?php
$title = "Login Page";
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $api_url = 'http://69cent.local/api/login';
    $data = [
        'username' => $username,
        'password' => $password
    ];

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $response_data = json_decode($response, true);
        session_start();
        $_SESSION['authToken'] = $response_data['token'];
        header('Location: /');
        exit;
    } else {
        $error_message = 'Помилка входу. Перевірте дані та спробуйте ще раз.';
        if (!empty($response)) {
            $error_data = json_decode($response, true);
            $error_message = $error_data['message'] ?? $error_message;
        }
    }
}
?>
<div class="auth-container">
    <div class="auth-form">
        <h1 class="auth-title">Вхід</h1>
        <form action="/pages/Login/login.php" method="POST">
            <div class="input-group">
                <input type="text" name="username" id="username" required>
                <label for="username">Ім'я користувача</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" required>
                <label for="password">Пароль</label>
            </div>
            <button type="submit" class="auth-btn">Увійти</button>
        </form>
        <p class="auth-switch">
            Немає акаунта? <a href="/pages/Register/register.php">Зареєструватися</a>
        </p>
    </div>
</div>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>