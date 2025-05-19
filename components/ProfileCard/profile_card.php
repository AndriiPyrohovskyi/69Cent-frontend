<div class="profile-card">
    <div class="avatar">
        <?php if ($isEditing && $current_user['id'] === $profile_user['id']): ?>
            <input type="text" name="avatar_url" form="profile-form" value="<?= htmlspecialchars($profile_user['avatar_url']) ?>" class="avatar-input">
        <?php else: ?>
            <img src="<?= htmlspecialchars($profile_user['avatar_url']) ?>" alt="Avatar">
        <?php endif; ?>
    </div>
    <div class="info">
        <form method="post" id="profile-form" class="info-form">
            <?php if ($isEditing && $current_user['id'] === $profile_user['id']): ?>
                <input type="text" name="username" value="<?= htmlspecialchars($profile_user['username']) ?>" class="username-input">
            <?php else: ?>
                <h1 class="username-display"><?= htmlspecialchars($profile_user['username']) ?></h1>
            <?php endif; ?>
            <p><strong>Email:</strong> <?= htmlspecialchars($profile_user['email']) ?></p>
            <p><strong>Роль:</strong> <?= htmlspecialchars($profile_user['role']) ?></p>
            <p><strong>Зареєстровано:</strong> <?= date('d.m.Y H:i', strtotime($profile_user['created_at'])) ?></p>
            <p><strong>Карма:</strong> <?= number_format($profile_user['karma'], 0, '.', ' ') ?> карми</p>
            <div class="form-actions">
                <?php if ($current_user['id'] === $profile_user['id']): ?>
                    <button type="submit" name="<?= $isEditing ? 'save' : 'edit' ?>" class="edit-button">
                        <?= $isEditing ? 'Зберегти' : 'Редагувати' ?>
                    </button>
                <?php elseif ($current_user['role'] === 'admin'): ?>
                    <button class="delete-button" data-user-id="<?= $profile_user['id'] ?>">Видалити користувача</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>