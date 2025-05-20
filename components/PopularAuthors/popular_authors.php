    <h3>ПОПУЛЯРНІ АВТОРИ</h3>
    <ul class="author-list">
        <?php foreach ($popular_authors as $author): ?>
            <li class="author-item">
                <a class="author-link" href="/pages/ProfileView/profile_view.php?id=<?= $post_data['author_id'] ?>">
                    <img src="<?= $author['avatar_url'] ?>" class="author-avatar" alt="avatar">
                    <div class="author-info">
                        <span class="author-name">@<?= $author['username'] ?></span>
                        <span class="author-karma"><?= number_format($author['karma'], 0, '.', ' ') ?> карми</span>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="/authors" class="see-more">Дивитись усіх</a>
