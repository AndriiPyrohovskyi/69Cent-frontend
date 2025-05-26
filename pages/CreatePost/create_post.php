<?php
$title = "Create Post";
ob_start();
$current_user = [
    'id' => 1,
    'username' => 'coffee_lover',
    'email' => 'coffee@example.com',
    'role' => 'admin',
    'avatar_url' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
    'created_at' => '2024-10-15 12:30:00',
    'karma' => 100
];
$categories = ['Технології', 'Кіно', 'Книги', 'Меми', 'Наука', 'Спорт'];
?>

<div class="create-post-page">
    <h1>Створити новий пост</h1>
    <form method="post" action="/api/posts/create" class="create-post-form">
        <div class="form-group">
            <label for="category">Категорія:</label>
            <select id="category" name="category" required>
                <option value="" disabled selected>Оберіть категорію</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Заголовок:</label>
            <input type="text" id="title" name="title" placeholder="Введіть заголовок" required>
        </div>
        <div class="form-group">
            <label for="content">Основний текст:</label>
            <textarea id="content" name="content" rows="6" placeholder="Введіть текст поста" required></textarea>
        </div>
        <div class="form-group">
            <label for="image_url">Посилання на зображення:</label>
            <input type="url" id="image_url" name="image_url" placeholder="Вставте посилання на зображення">
        </div>
        <button type="submit" class="submit-btn">Створити пост</button>
    </form>
</div>
<script src="create_post.js"></script>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>