<div class="like-card">
    <img src="<?= htmlspecialchars($like['icon_url']) ?>" alt="<?= htmlspecialchars($like['name']) ?>" class="like-icon">
    <span class="like-name"><?= htmlspecialchars($like['name']) ?></span>
    <span class="like-carma">Карма: <?= htmlspecialchars($like['carma']) ?></span>
    <div class="like-actions">
        <button class="edit-like-btn" data-like="<?= htmlspecialchars($like['name']) ?>">Редагувати</button>
        <button class="delete-like-btn" data-like="<?= htmlspecialchars($like['name']) ?>">Видалити</button>
    </div>
</div>