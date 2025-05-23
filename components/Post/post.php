<div class="post">
        <a class="author-link" href="/pages/ProfileView/profile_view.php?id=<?= $post_data['author_id'] ?>">
            <div class="post-author-container">
                <div class="post-author-avatar-container">
                    <img src="<?= $post_data['author_avatar'] ?>" alt="Author Avatar" class="post-author-avatar">
                </div>
                <div class="post-author-info-container">
                    <h1 class="post-author-name">Автор: <?= $post_data['author_name'] ?></h1>
                    <h2 class="post-author-date">Дата реєстрації: <?= $post_data['author_date'] ?> </h2>
                    <h3 class="post-author-role">Роль: <?= $post_data['author_role'] ?></h3>
                    <h3 class="post-author-carma">Карма: <?= $post_data['author_сarma'] ?></h3>
                </div>
            </div>
        </a>
        <div class="post-content-container">
                <h1 class="post-content-title"> <?= $post_data['post_title'] ?> </h1>
                <h2 class="post-content-text"> <?= $post_data['post_text'] ?> </h2>
                <div class="image-blur-wrapper">
                    <div class="image-blur-bg" style="background-image: url('<?= $post_data['post_image'] ?>');"></div>
                    <img src="<?= $post_data['post_image'] ?>" alt="Post Image" class="image-foreground">
                </div>
        </div>
        <div class="post-info">
            <div class="post-category-container">
                <span class="post-category"> <?= $post_data['post_category'] ?> </span>
            </div>
            <h4 class="post-created_at">Створено: <?= $post_data['post_created_at'] ?> </h4>
            <?php if ($post_data['is_modified']): ?>
                <h4 class="post-modified_at">Модифіковано: <?= $post_data['post_modified_at'] ?> </h4>
            <?php endif; ?>
        </div>
        <div class="post-actions">
            <?php if ($current_user['id'] === $post_data['author_id'] || $current_user['role'] === 'admin'): ?>
                <button class="delete-post-btn" data-post-id="<?= $post_data['id'] ?>">Видалити</button>
            <?php endif; ?>
            <?php if ($current_user['id'] === $post_data['author_id']): ?>
                <button class="edit-post-btn" data-post-id="<?= $post_data['id'] ?>">Редагувати</button>
            <?php endif; ?>
        </div>
    </div>
</div>