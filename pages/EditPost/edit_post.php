<?php
$title = "Редагування поста";
ob_start();

// Перевірка авторизації
session_start();
if (!isset($_SESSION['authToken'])) {
    header('Location: /pages/Login/login.php');
    exit;
}

// Отримання ID поста з URL
$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    echo '<p>ID поста не вказано.</p>';
    $content = ob_get_clean();
    include '../../layout.php';
    exit;
}
?>

<div class="edit-post-container">
    <h1>Редагування поста</h1>
    
    <div class="alert" id="statusMessage"></div>
    
    <form id="editPostForm" class="edit-post-form">
        <div class="form-group">
            <label for="title">Заголовок:</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="category">Категорія:</label>
            <select id="category" name="category" required>
                <option value="" disabled selected>Оберіть категорію</option>
                <!-- Категорії будуть завантажені через JavaScript -->
            </select>
        </div>
        
        <div class="form-group">
            <label for="content">Текст поста:</label>
            <textarea id="content" name="content" rows="10" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="image_url">Посилання на зображення:</label>
            <input type="url" id="image_url" name="image_url">
            <small>Залиште порожнім, якщо не бажаєте додавати зображення</small>
        </div>
        
        <div class="image-preview" id="imagePreview">
            <img src="" alt="Preview" id="previewImg" style="display: none;">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="save-btn">Зберегти зміни</button>
            <a href="/pages/Profile/profile.php?tab=posts" class="cancel-btn">Скасувати</a>
        </div>
    </form>
</div>

<script src="/pages/EditPost/edit_post.js"></script>

<style>
.edit-post-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.edit-post-form {
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
input[type="url"],
textarea,
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

textarea {
    resize: vertical;
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

.image-preview {
    margin: 20px 0;
    text-align: center;
}

#previewImg {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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