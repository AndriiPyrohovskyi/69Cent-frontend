<div class="profile-card">
    <div class="avatar">
        <?php if ($isEditing): ?>
            <input type="text" name="avatar_url" form="profile-form" value="<?= htmlspecialchars($user['avatar_url']) ?>" class="avatar-input">
        <?php else: ?>
            <img src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar">
        <?php endif; ?>
    </div>
    <div class="info">
        <form method="post" id="profile-form" class="info-form">
            <?php if ($isEditing): ?>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="username-input">
            <?php else: ?>
                <h1 class="username-display"><?= htmlspecialchars($user['username']) ?></h1>
            <?php endif; ?>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Роль:</strong> <?= htmlspecialchars($user['role']) ?></p>
            <p><strong>Зареєстровано:</strong> <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></p>
            <div class="form-actions">
                <button type="submit" name="<?= $isEditing ? 'save' : 'edit' ?>" class="edit-button">
                    <?= $isEditing ? 'Зберегти' : 'Редагувати' ?>
                </button>
            </div>
        </form>
    </div>
</div>