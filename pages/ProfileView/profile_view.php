<?php
$title = "Перегляд профілю";
ob_start();

// Отримуємо ID користувача з URL
$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    echo '<p>ID користувача не вказано.</p>';
    $content = ob_get_clean();
    include '../../layout.php';
    exit;
}

// Зберігаємо сесію для реєстрації активності користувача
session_start();
?>

<div class="profile-view-container">
    <div class="profile-content">
        <!-- Сюди буде завантажено інформацію про користувача -->
        <div class="profile-card-container"></div>
        
        <h2 class="posts-title">Пости користувача</h2>
        <div class="user-posts-container">
            <p class="loading">Завантаження постів...</p>
        </div>
    </div>
    
    <aside class="sidebar">
        <div class="popular-authors-widget">
            <?php include '../../components/PopularAuthors/popular_authors.php'; ?>
        </div>
    </aside>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const userId = <?= json_encode($user_id) ?>;
    const authToken = localStorage.getItem('authToken');
    let currentUser = null;
    let allLikeTypes = [];
    
    // Отримання даних поточного користувача
    try {
        const currentUserResponse = await fetch('http://69centapi.local/api/current_user', {
            headers: { 'Authorization': `Bearer ${authToken}` }
        });
        
        if (currentUserResponse.ok) {
            currentUser = await currentUserResponse.json();
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
        }
    } catch (err) {
        console.error('Помилка отримання даних користувача:', err);
        const cachedUser = localStorage.getItem('currentUser');
        if (cachedUser) {
            currentUser = JSON.parse(cachedUser);
        }
    }
    
    // Завантажуємо інформацію про користувача за ID
    try {
        const response = await fetch(`http://69centapi.local/api/users/${userId}`);
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}`);
        }
        
        const user = await response.json();
        displayUser(user);
    } catch (err) {
        console.error('Помилка завантаження даних користувача:', err);
        document.querySelector('.profile-card-container').innerHTML = 
            '<p>Не вдалося завантажити інформацію про користувача.</p>';
    }
    
    // Завантажуємо пости користувача
    try {
        const postsResponse = await fetch(`http://69centapi.local/api/posts/user/${userId}`);
        if (!postsResponse.ok) {
            throw new Error(`HTTP error ${postsResponse.status}`);
        }
        
        const posts = await postsResponse.json();
        
        // Завантажуємо типи лайків для відображення постів
        const likesResponse = await fetch('http://69centapi.local/api/like_types');
        if (likesResponse.ok) {
            allLikeTypes = await likesResponse.json();
        }
        
        displayPosts(posts);
    } catch (err) {
        console.error('Помилка завантаження постів користувача:', err);
        document.querySelector('.user-posts-container').innerHTML = 
            '<p>Не вдалося завантажити пости.</p>';
    }
    
    // Функція відображення інформації про користувача
    function displayUser(user) {
        const container = document.querySelector('.profile-card-container');
        const avatarUrl = user.avatar_url || 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y';
        
        container.innerHTML = `
            <div class="profile-card">
                <div class="avatar">
                    <img src="${avatarUrl}" alt="${user.username}" class="user-avatar">
                </div>
                <div class="info">
                    <h1 class="username">${user.username}</h1>
                    <p class="role">Роль: ${user.role}</p>
                    <p class="registered">Зареєстровано: ${new Date(user.created_at).toLocaleString()}</p>
                    <p class="karma">Карма: ${user.karma || 0}</p>
                </div>
            </div>
        `;
    }
    
    // Функція відображення постів користувача
    function displayPosts(posts) {
        const container = document.querySelector('.user-posts-container');
        container.innerHTML = '';
        
        if (!posts || posts.length === 0) {
            container.innerHTML = '<p>Цей користувач ще не опублікував жодного поста.</p>';
            return;
        }
        
        posts.forEach(post => {
            // Перевіряємо права доступу
            const isAuthor = currentUser && Number(currentUser.id) === Number(post.author_id);
            const isAdmin = currentUser && currentUser.role === 'admin';
            
            // Кнопки редагування та видалення
            const editButton = isAuthor ? 
                `<button class="edit-post-btn" data-post-id="${post.id}">Редагувати</button>` : '';
            
            const deleteButton = (isAuthor || isAdmin) ? 
                `<button class="delete-post-btn" data-post-id="${post.id}">Видалити</button>` : '';
            
            // Блок лайків
            let likesHTML = '';
            
            if (allLikeTypes.length > 0) {
                likesHTML = '<div class="all-likes-container">';
                
                allLikeTypes.forEach(likeType => {
                    const likeCount = post.post_likes && post.post_likes[likeType.name] 
                        ? post.post_likes[likeType.name][0] 
                        : 0;
                    
                    likesHTML += `
                        <button class="like-button ${post.user_likes && post.user_likes.includes(likeType.id) ? 'liked' : ''}" 
                                data-post-id="${post.id}" 
                                data-like-type-id="${likeType.id}" 
                                data-like-name="${likeType.name}">
                            <img class="like-icon" src="${likeType.icon_url}" alt="${likeType.name}">
                            <span class="like-count">${likeCount}</span>
                        </button>
                    `;
                });
                
                likesHTML += '</div>';
            }
            
            container.innerHTML += `
                <div class="post-preview" data-post-id="${post.id}">
                    <div class="preview-header">
                        <span class="preview-time">${new Date(post.post_created_at).toLocaleString()}</span>
                    </div>
                    <a href="/pages/PostView/post_view.php?id=${post.id}" class="post-link">
                        <h2 class="preview-title">${post.post_title}</h2>
                        <p class="preview-text">${post.post_text ? post.post_text.slice(0, 100) + '...' : ''}</p>
                        ${post.post_image ? `
                        <div class="image-blur-wrapper">
                            <div class="image-blur-bg" style="background-image: url('${post.post_image}');"></div>
                            <img src="${post.post_image}" alt="Post Image" class="image-foreground">
                        </div>
                        ` : ''}
                    </a>
                    <div class="preview-bottom">
                        <span class="post-category">${post.post_category}</span>
                        <div class="post-likes">
                            ${likesHTML}
                        </div>
                        <div class="post-actions">
                            ${editButton}
                            ${deleteButton}
                        </div>
                    </div>
                </div>
            `;
        });
        
        // Додаємо обробники подій для лайків та кнопок
        addPostEventListeners();
    }
    
    // Функція додавання обробників подій
    function addPostEventListeners() {
        // Обробники для лайків
        document.querySelectorAll('.like-button').forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                
                const postId = button.dataset.postId;
                const likeTypeId = button.dataset.likeTypeId;
                
                if (!currentUser || !currentUser.id) {
                    alert('Ви повинні бути авторизовані для того, щоб ставити лайки');
                    return;
                }
                
                try {
                    const response = await fetch(`http://69centapi.local/api/posts/${postId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${authToken}`
                        },
                        body: JSON.stringify({
                            user_id: currentUser.id,
                            like_type_id: likeTypeId
                        })
                    });
                    
                    const responseText = await response.text();
                    
                    if (responseText.trim()) {
                        try {
                            const result = JSON.parse(responseText);
                            
                            const countElement = button.querySelector('.like-count');
                            
                            if (result.action === 'liked') {
                                button.classList.add('liked');
                                if (countElement) {
                                    countElement.textContent = parseInt(countElement.textContent) + 1;
                                }
                            } else if (result.action === 'unliked') {
                                button.classList.remove('liked');
                                if (countElement) {
                                    countElement.textContent = parseInt(countElement.textContent) - 1;
                                }
                            }
                        } catch (jsonError) {
                            console.error('JSON parse error:', jsonError);
                        }
                    } else if (response.headers.get('Content-Type') === 'text/html; charset=UTF-8' && !responseText) {
                        // Резервний варіант, якщо відповідь порожня
                        const countElement = button.querySelector('.like-count');
                        
                        if (button.classList.contains('liked')) {
                            button.classList.remove('liked');
                            if (countElement) {
                                countElement.textContent = Math.max(0, parseInt(countElement.textContent) - 1);
                            }
                        } else {
                            button.classList.add('liked');
                            if (countElement) {
                                countElement.textContent = parseInt(countElement.textContent) + 1;
                            }
                        }
                    }
                } catch (err) {
                    console.error('Error during like operation:', err);
                }
            });
        });
        
        // Обробники для кнопки редагування
        document.querySelectorAll('.edit-post-btn').forEach(button => {
            button.addEventListener('click', () => {
                const postId = button.dataset.postId;
                window.location.href = `/pages/EditPost/edit_post.php?id=${postId}`;
            });
        });
        
        // Обробники для кнопки видалення
        document.querySelectorAll('.delete-post-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const postId = button.dataset.postId;
                
                if (confirm('Ви впевнені, що хочете видалити цей пост?')) {
                    try {
                        const response = await fetch(`http://69centapi.local/api/posts/${postId}`, {
                            method: 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${authToken}`
                            }
                        });
                        
                        if (response.ok) {
                            alert('Пост успішно видалено');
                            // Видаляємо пост з DOM
                            const postElement = button.closest('.post-preview');
                            if (postElement) {
                                postElement.remove();
                            }
                        } else {
                            const errorData = await response.json();
                            alert(`Помилка: ${errorData.error || 'Не вдалося видалити пост'}`);
                        }
                    } catch (err) {
                        console.error('Помилка при видаленні посту:', err);
                        alert('Помилка мережі при видаленні посту');
                    }
                }
            });
        });
    }
});
</script>

<style>
.profile-view-container {
    display: grid;
    grid-template-columns: 3fr 1fr;
    gap: 20px;
    padding: 20px;
}

.profile-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.sidebar {
    position: sticky;
    top: 20px;
}

.profile-card {
    display: flex;
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.avatar {
    margin-right: 20px;
}

.user-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
}

.info h1 {
    margin-top: 0;
    color: #333;
}

.info p {
    margin: 5px 0;
    color: #666;
}

.karma {
    font-weight: bold;
    color: #4caf50;
}

.posts-title {
    margin: 20px 0 10px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

.post-preview {
    background: white;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.preview-time {
    color: #999;
    font-size: 14px;
}

.preview-title {
    margin: 10px 0;
    color: #333;
}

.preview-text {
    color: #555;
    margin-bottom: 15px;
}

.post-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.image-blur-wrapper {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 15px;
}

.image-blur-bg {
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    background-position: center;
    background-size: cover;
    filter: blur(10px);
    opacity: 0.5;
}

.image-foreground {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.preview-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.post-category {
    background-color: #f0f0f0;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.all-likes-container {
    display: flex;
    gap: 8px;
}

.like-button {
    display: flex;
    align-items: center;
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 20px;
    background-color: #f8f8f8;
    cursor: pointer;
}

.like-button.liked {
    background-color: #e3f2fd;
    border-color: #2196f3;
}

.like-icon {
    width: 16px;
    height: 16px;
    margin-right: 5px;
}

.post-actions {
    display: flex;
    gap: 10px;
}

.edit-post-btn, .delete-post-btn {
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

.edit-post-btn {
    background-color: #4CAF50;
    color: white;
    border: none;
}

.delete-post-btn {
    background-color: #f44336;
    color: white;
    border: none;
}

.loading {
    text-align: center;
    color: #999;
    padding: 20px;
}

@media (max-width: 768px) {
    .profile-view-container {
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        display: none;
    }
    
    .profile-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .avatar {
        margin-right: 0;
        margin-bottom: 20px;
    }
}
</style>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>