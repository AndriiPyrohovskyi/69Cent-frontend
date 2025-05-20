<?php
$title = "Profile Page";
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
$users_admin = [
    [
        'id' => 2,
        'username' => 'coffee_lover',
        'email' => 'coffee@example.com',
        'role' => 'user',
        'avatar_url' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'created_at' => '2024-10-15 12:30:00',
        'karma' => 100
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
$posts = [
    [
        'author_avatar' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'author_id' => 1,
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
        'author_id' => 1,
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
$admin_posts = [
    [
        'author_avatar' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'author_id' => 1,
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
        'author_id' => 2,
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
        'author_id' => 2,
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
$categories_admin = ['Плітки', 'Спорт', 'Політика', 'Технології', 'Наука', 'Мистецтво'];
$likes_admin = [
    [
        'name' => 'Like',
        'carma' => 1,
        'icon_url' => 'https://www.svgrepo.com/show/505406/like.svg',
    ],
    [
        'name' => 'Dislike',
        'carma' => -1,
        'icon_url' => 'https://www.svgrepo.com/show/505358/dislike.svg',
    ],
    [
        'name' => 'Love',
        'carma' => 2,
        'icon_url' => 'https://www.svgrepo.com/show/13666/heart.svg',
    ],
    [
        'name' => 'Funny',
        'carma' => 2,
        'icon_url' => 'https://www.svgrepo.com/show/352802/laugh-squint.svg',
    ],
    [
        'name' => 'Angry',
        'carma' => -2,
        'icon_url' => 'https://www.svgrepo.com/show/458500/angry.svg',
    ],
];
$isEditing = isset($_POST['edit']);
if (isset($_POST['save'])) {
    $current_user['username'] = htmlspecialchars($_POST['username']);
    $current_user['avatar_url'] = htmlspecialchars($_POST['avatar_url']);
    $isEditing = false;
}
$activeTab = $_GET['tab'] ?? 'profile';
$adminSubTab = $_GET['subtab'] ?? 'users';
?>
<div class="profile-page">
    <aside class="sidebar">
        <h2>Меню</h2>
        <ul>
            <li><a href="?tab=profile" class="<?= $activeTab === 'profile' ? 'active' : '' ?>">Профіль</a></li>
            <li><a href="?tab=posts" class="<?= $activeTab === 'posts' ? 'active' : '' ?>">Мої пости</a></li>
            <?php if ($current_user['role'] === 'admin'): ?>
                <li>
                    <a href="?tab=admin" class="<?= $activeTab === 'admin' ? 'active' : '' ?>">Адмін-панель</a>
                    <?php if ($activeTab === 'admin'): ?>
                        <ul class="sub-menu">
                            <li><a href="?tab=admin&subtab=users" class="<?= $adminSubTab === 'users' ? 'active' : '' ?>">Користувачі</a></li>
                            <li><a href="?tab=admin&subtab=posts" class="<?= $adminSubTab === 'posts' ? 'active' : '' ?>">Пости</a></li>
                            <li><a href="?tab=admin&subtab=settings" class="<?= $adminSubTab === 'settings' ? 'active' : '' ?>">Налаштування</a></li>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        </ul>
    </aside>
    <div class="profile-content">
        <?php if ($activeTab === 'profile'): ?>
            <?php
                $profile_user = $current_user;
                $context = 'profile';
                include '../../components/ProfileCard/profile_card.php'; 
            ?>
        <?php elseif ($activeTab === 'posts'): ?>
            <div class="posts-section">
                <div class="posts-container">
                    <?php
                    foreach ($posts as $post_data) {
                        include '../../components/PostPreview/post_preview.php';
                    }
                    ?>
                </div>
            </div>
        <?php elseif ($activeTab === 'admin'): ?>
            <div class="admin-panel">
                <?php if ($adminSubTab === 'users'):
                    foreach ($users_admin as $profile_user) {
                        $context = 'admin';
                        include '../../components/ProfileCard/profile_card.php';
                    }
                ?>
                <?php elseif ($adminSubTab === 'posts'): 
                    foreach ($admin_posts as $post_data) {
                        include '../../components/PostPreview/post_preview.php';
                    }
                ?>
                <?php elseif ($adminSubTab === 'settings'): ?>
                    <div class="settings-section">
                        <h2>Категорії</h2>
                        <div class="categories-container">
                        </div>
                        <form id="add-category-form" class="add-form">
                            <input type="text" id="new-category-name" placeholder="Назва нової категорії" required>
                            <button type="submit" class="add-category-btn">+ Додати категорію</button>
                        </form>

                        <h2>Лайки</h2>
                        <div class="likes-container">
                        </div>
                        <form id="add-like-form" class="add-form">
                            <input type="text" id="new-like-name" placeholder="Назва нового лайка" required>
                            <input type="number" id="new-like-carma" placeholder="Карма" required>
                            <input type="text" id="new-like-icon-url" placeholder="Посилання на іконку" required>
                            <button type="submit" class="add-like-btn">+ Додати лайк</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<script src="profile.js"></script>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>