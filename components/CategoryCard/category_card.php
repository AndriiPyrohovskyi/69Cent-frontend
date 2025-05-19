<div class="category-card">
    <span class="category-name"><?= htmlspecialchars($category) ?></span>
    <div class="category-actions">
        <button class="edit-category-btn" data-category="<?= htmlspecialchars($category) ?>">Редагувати</button>
        <button class="delete-category-btn" data-category="<?= htmlspecialchars($category) ?>">Видалити</button>
    </div>
</div>