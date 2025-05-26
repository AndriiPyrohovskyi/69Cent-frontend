<?php
session_start();

$title = "Profile Page";
ob_start();
$isEditing = isset($_POST['edit']);

$activeTab = $_GET['tab'] ?? 'profile';
$adminSubTab = $_GET['subtab'] ?? 'users';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['currentUser'])) {
        $_SESSION['current_user'] = $input['currentUser'];
        $current_user = $input['currentUser'];
    }
}
if (!isset($current_user) && isset($_SESSION['current_user'])) {
    $current_user = $_SESSION['current_user'];
}
error_log('Current User: ' . print_r($current_user, true));
?>
<div class="profile-page">
    <aside class="sidebar">
        <h2>Меню</h2>
        <ul>
            <li><a href="?tab=profile" class="<?= $activeTab === 'profile' ? 'active' : '' ?>">Профіль</a></li>
            <li><a href="?tab=posts" class="<?= $activeTab === 'posts' ? 'active' : '' ?>">Мої пости</a></li>
            <?php if (isset($current_user) && isset($current_user['role']) && $current_user['role'] === 'admin'): ?>
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
            <div id="profile-card-container"></div>
        <?php elseif ($activeTab === 'posts'): ?>
            <div id="posts-container"></div>
        <?php elseif ($activeTab === 'admin'): ?>
            <div class="admin-panel">
                <?php if ($adminSubTab === 'users'):?>
                    <div id="profile-card-admin-container"></div>
                <?php elseif ($adminSubTab === 'posts'): ?>
                    <div id="posts-card-admin-container"></div>
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