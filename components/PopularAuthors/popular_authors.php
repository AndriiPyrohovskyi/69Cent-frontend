    <h3>ПОПУЛЯРНІ АВТОРИ</h3>
    <ul class="author-list">
        <?php foreach ($popular_authors as $author): ?>
            <li class="author-item">
                <img src="<?= $author['avatar'] ?>" class="author-avatar" alt="avatar">
                <div class="author-info">
                    <span class="author-name">@<?= $author['nickname'] ?></span>
                    <span class="author-karma"><?= number_format($author['karma'], 0, '.', ' ') ?> карми</span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="/authors" class="see-more">Дивитись усіх</a>
