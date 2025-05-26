<?php
$title = "Posts Page";
ob_start();

// Початок сесії для перевірки автентифікації
session_start();
$is_authenticated = isset($_SESSION['authToken']);

// Отримуємо параметри фільтрації
$selected_category = $_GET['category'] ?? 'Усі';
$search_query = $_GET['search'] ?? '';

// Отримуємо поточного користувача, якщо доступний
$current_user = isset($_SESSION['current_user']) ? $_SESSION['current_user'] : null;
?>

<div class="filter-bar">
    <form method="GET" id="filter-form">
        <input type="text" name="search" class="search-input" placeholder="Пошук постів..." value="<?= htmlspecialchars($search_query) ?>">

        <select name="category" class="category-select">
            <!-- Категорії будуть завантажені через JavaScript -->
            <option value="Усі">Усі</option>
        </select>
    </form>
</div>

<?php if ($is_authenticated): ?>
    <a href="/pages/CreatePost/create_post.php" class="create-post-btn">+ Створити пост</a>
<?php else: ?>
    <p class="auth-warning">
        <a href="/pages/Login/login.php">Увійдіть</a>, щоб створити пост.
    </p>
<?php endif; ?>

<div class="posts">
    <div class="posts-container">
        <!-- Пости будуть завантажені через JavaScript -->
        <p class="loading">Завантаження постів...</p>
    </div>
    
    <aside class="sidebar">
        <!-- Додаємо компонент популярних авторів -->
        <?php include '../../components/PopularAuthors/popular_authors.php'; ?>
        
        <!-- Додаткові віджети можуть бути тут -->
    </aside>
</div>
<script src="/pages/Posts/posts.js"></script>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>