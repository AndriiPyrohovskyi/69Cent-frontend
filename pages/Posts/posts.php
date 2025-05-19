<?php
$title = "Posts Page";
ob_start();
?>
<?php
$posts = [
    [
        'author_avatar' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'author_name' => 'Ivan Zolo',
        'author_date' => '2023-10-01',
        'author_role' => 'Admin',
        'author_сarma' => 100,
        'post_title' => 'Екатіріна Зізуліна',
        'post_text' => 'Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна',
        'post_image' => 'https://images.next.thetruestory.news/thumb/92/c8/newsitem_3548925.webp',
        'post_category' => 'Плітки',
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
        'author_name' => 'Ivan Zolo',
        'author_date' => '2023-10-01',
        'author_role' => 'Admin',
        'author_сarma' => 100,
        'post_title' => 'Екатіріна Зізуліна',
        'post_text' => 'Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна',
        'post_image' => 'https://images.next.thetruestory.news/thumb/92/c8/newsitem_3548925.webp',
        'post_category' => 'Плітки',
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
        'avatar' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'nickname' => 'ivan_zolo',
        'karma' => 100,
    ],
    [
        'avatar' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'nickname' => 'ivan_zolo',
        'karma' => 100,
    ],
];
$categories = ['Усі', 'ІТ', 'Кіно', 'Книги', 'Меми', 'Наука'];
?>
<div class="filter-bar">
    <input type="text" class="search-input" placeholder="Пошук постів...">

    <select class="category-select">
        <?php foreach ($categories as $category): ?>
            <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<a href="/pages/create_post.php" class="create-post-btn">+ Створити пост</a>
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

<?php
$content = ob_get_clean();
include '../../layout.php';
?>