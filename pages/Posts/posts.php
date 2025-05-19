<?php
$title = "Posts Page";
ob_start();
?>
<?php
$current_user = [
    'id' => 1,
    'username' => 'coffee_lover',
    'email' => 'coffee@example.com',
    'role' => 'admin',
    'avatar_url' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
    'created_at' => '2024-10-15 12:30:00',
    'karma' => 100
];
$posts = [
    [
        'author_avatar' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'author_id' => 1,
        'author_name' => 'Ivan Zolo',
        'author_date' => '2023-10-01',
        'author_role' => 'Admin',
        'author_сarma' => 100,
        'post_id' => 1,
        'post_title' => 'Екатіріна Зізуліна',
        'post_text' => 'Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна',
        'post_image' => 'https://images.next.thetruestory.news/thumb/92/c8/newsitem_3548925.webp',
        'post_category' => 'IT',
        'post_created_at' => '2023-10-01 12:00:00',
        'is_modified' => true,
        'post_modified_at' => '2023-10-02 12:00:00',
        'post_likes' => [
            'like' => [10, 'https://www.svgrepo.com/show/505406/like.svg'],
            'dislike' => [2, 'https://www.svgrepo.com/show/505358/dislike.svg'],
            'love' => [2, 'https://www.svgrepo.com/show/13666/heart.svg'],
            'funny' => [2, 'https://www.svgrepo.com/show/352802/laugh-squint.svg'],
            'angry' => [2, 'https://www.svgrepo.com/show/458500/angry.svg'],
        ],
    ],
    [
        'author_avatar' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'author_id' => 2,
        'author_name' => 'Ivan Zolo',
        'author_date' => '2023-10-01',
        'author_role' => 'Admin',
        'author_сarma' => 100,
        'post_id' => 2,
        'post_title' => 'Екатіріна Зізуліна',
        'post_text' => 'Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна',
        'post_image' => 'https://images.next.thetruestory.news/thumb/92/c8/newsitem_3548925.webp',
        'post_category' => 'Кіно',
        'post_created_at' => '2023-10-01 12:00:00',
        'is_modified' => true,
        'post_modified_at' => '2023-10-02 12:00:00',
        'post_likes' => [
            'like' => [10, 'https://www.svgrepo.com/show/505406/like.svg'],
            'dislike' => [2, 'https://www.svgrepo.com/show/505358/dislike.svg'],
            'love' => [2, 'https://www.svgrepo.com/show/13666/heart.svg'],
            'funny' => [2, 'https://www.svgrepo.com/show/352802/laugh-squint.svg'],
            'angry' => [2, 'https://www.svgrepo.com/show/458500/angry.svg'],
        ],
    ],
];
$popular_authors = [
    [
        'id' => 1,
        'username' => 'coffee_lover',
        'email' => 'coffee@example.com',
        'role' => 'admin',
        'avatar_url' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'created_at' => '2024-10-15 12:30:00'
    ],
    [
        'id' => 1,
        'username' => 'coffee_lover',
        'email' => 'coffee@example.com',
        'role' => 'admin',
        'avatar_url' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'created_at' => '2024-10-15 12:30:00',
        'karma' => 100
    ],
];
$categories = ['Усі', 'ІТ', 'Кіно', 'Книги', 'Меми', 'Наука'];

$selected_category = $_GET['category'] ?? 'Усі';
if ($selected_category !== 'Усі') {
    $posts = array_filter($posts, function ($post) use ($selected_category) {
        return $post['post_category'] === $selected_category;
    });
}

$search_query = $_GET['search'] ?? '';
if (!empty($search_query)) {
    $posts = array_filter($posts, function ($post) use ($search_query) {
        $title_match = stripos($post['post_title'], $search_query) !== false;
        $text_match = stripos($post['post_text'], $search_query) !== false;
        return $title_match || $text_match;
    });
}

if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    ob_start();
    foreach ($posts as $post_data) {
        include '../../components/PostPreview/post_preview.php';
    }
    $html = ob_get_clean();
    echo $html ?: '<p>Нічого не знайдено.</p>';
    exit;
}
?>
<div class="filter-bar">
    <form method="GET" id="filter-form">
        <input type="text" name="search" class="search-input" placeholder="Пошук постів..." value="<?= htmlspecialchars($search_query) ?>">

        <select name="category" class="category-select">
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category) ?>" <?= $selected_category === $category ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
<a href="/pages/CreatePost/create_post.php" class="create-post-btn">+ Створити пост</a>
<div class="posts">
    <div class="posts-container">
        <?php
        foreach ($posts as $post_data) {
            include '../../components/PostPreview/post_preview.php';
        }
        ?>
    </div>
    <div class="authors-container">
        <?php
            include '../../components/PopularAuthors/popular_authors.php';
        ?>
    </div>
</div>
<script src="/pages/Posts/posts.js"></script>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>