<?php include_once __DIR__ . '/../../helpers.php'; ?>

<div class="post-preview">
    <a class="author-link" href="/pages/Profile/profile.php?id=<?= $post_data['author_id'] ?>">
        <div class="preview-header">
            <img src="<?= $post_data['author_avatar'] ?>" alt="Author Avatar" class="preview-avatar">
            <span class="preview-time"><?= htmlspecialchars(getRelativeTime($post_data['post_created_at'])) ?></span>
            <span class="preview-author"><?= $post_data['author_name'] ?></span>
        </div>
    </a>
    <a class="post-link" href="/pages/PostView/post_view.php?id=<?= $post_data['post_id'] ?>" class="preview-read-more">
        <h2 class="preview-title"><?= htmlspecialchars($post_data['post_title']) ?></h2>
        <p class="preview-text">
            <?= implode(' ', array_slice(explode(' ', strip_tags($post_data['post_text'])), 0, 10)) ?>...
        </p>

        <div class="image-blur-wrapper">
            <div class="image-blur-bg" style="background-image: url('<?= $post_data['post_image'] ?>');"></div>
            <img src="<?= $post_data['post_image'] ?>" alt="Post Image" class="image-foreground">
        </div>
    </a>

    <div class="preview-bottom">
        <span class="post-category"><?= $post_data['post_category'] ?></span>
        <div class="post-likes">
            <?php foreach ($post_data['post_likes'] as $like_type => $like_count): ?>
                <button class="like-button">
                    <img class="like_icon" src="<?= $like_count[1] ?>"></img> <?= $like_count[0] ?>
                </button>
            <?php endforeach; ?>
        </div>
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
