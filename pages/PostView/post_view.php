<?php
$title = "Перегляд поста";
ob_start();

// Отримуємо ID поста з URL
$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    echo '<p>ID поста не вказано.</p>';
    $content = ob_get_clean();
    include '../../layout.php';
    exit;
}

// Зберігаємо сесію для реєстрації активності користувача
session_start();
?>

<div class="post-view-container">
    <!-- Пост буде завантажено через JavaScript -->
    <p class="loading">Завантаження поста...</p>
    
    <div class="post-content">
        <!-- Вміст поста з'явиться тут -->
    </div>
    
    <div class="post-actions">
        <!-- Кнопки лайків та інші дії -->
    </div>
    
    <a href="/pages/Posts/posts.php" class="back-button">Повернутися до списку постів</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    // Виправлені змінні - додаємо їх на початку файлу
    const postId = <?= json_encode($post_id) ?>;
    const authToken = localStorage.getItem('authToken');
    let currentUser = null;
    let allLikeTypes = [];
    
    // Отримуємо поточного користувача
    if (authToken) {
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
    }
    
    // Завантажуємо типи лайків
    try {
        const likesResponse = await fetch('http://69centapi.local/api/like_types');
        if (likesResponse.ok) {
            allLikeTypes = await likesResponse.json();
        }
    } catch (err) {
        console.error('Помилка завантаження типів лайків:', err);
    }
    
    // Завантажуємо пост
    try {
        const response = await fetch(`http://69centapi.local/api/posts/${postId}`);
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}`);
        }
        
        const post = await response.json();
        displayPost(post);
    } catch (err) {
        console.error('Помилка завантаження поста:', err);
        document.querySelector('.post-content').innerHTML = '<p>Помилка завантаження поста. Спробуйте пізніше.</p>';
        document.querySelector('.loading').style.display = 'none';
    }
    
    // Функція для відображення поста
    function displayPost(post) {
        const container = document.querySelector('.post-content');
        const loadingElement = document.querySelector('.loading');
        const actionsContainer = document.querySelector('.post-actions');
        
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        
        // Перевіряємо права доступу
        const isAuthor = currentUser && Number(currentUser.id) === Number(post.author_id);
        const isAdmin = currentUser && currentUser.role === 'admin';
        
        // Кнопки редагування та видалення
        const editButton = isAuthor ? 
            `<a href="/pages/EditPost/edit_post.php?id=${postId}" class="edit-post-btn">Редагувати</a>` : '';
        
        const deleteButton = (isAuthor || isAdmin) ? 
            `<button class="delete-post-btn" data-post-id="${postId}">Видалити</button>` : '';
        
        // Відображаємо пост
        container.innerHTML = `
            <article class="post">
                <header class="post-header">
                    <h1 class="post-title">${post.post_title}</h1>
                    <div class="post-meta">
                        <a href="/pages/ProfileView/profile_view.php?id=${post.author_id}" class="author-info">
                            <img src="${post.author_avatar || 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'}" alt="${post.author_name}" class="author-avatar">
                            <span class="author-name">${post.author_name}</span>
                        </a>
                        <time datetime="${post.post_created_at}" class="post-time">
                            ${new Date(post.post_created_at).toLocaleString()}
                        </time>
                        <span class="post-category">${post.post_category}</span>
                    </div>
                </header>
                
                ${post.post_image ? `
                <div class="post-image">
                    <img src="${post.post_image}" alt="${post.post_title}" class="full-image">
                </div>
                ` : ''}
                
                <div class="post-text-content">
                    ${post.post_text}
                </div>
            </article>
        `;
        
        // Підготовка блоку лайків
        let likesHTML = '';
        
        if (allLikeTypes.length > 0) {
            likesHTML = '<div class="all-likes-container">';
            
            allLikeTypes.forEach(likeType => {
                // Перевіряємо, чи є такий тип лайку в пості
                const likeCount = post.post_likes && post.post_likes[likeType.name] 
                    ? post.post_likes[likeType.name][0] 
                    : 0;
                
                // При побудові блоку лайків додайте атрибут user-liked
                likesHTML += `
                    <button class="like-button ${post.user_likes && post.user_likes.includes(likeType.id) ? 'liked' : ''}" 
                            data-post-id="${postId}" 
                            data-like-type-id="${likeType.id}" 
                            data-like-name="${likeType.name}">
                        <img class="like-icon" src="${likeType.icon_url}" alt="${likeType.name}">
                        <span class="like-count">${likeCount}</span>
                    </button>
                `;
            });
            
            likesHTML += '</div>';
        }
        
        // Додаємо дії з постом (лайки, редагування, видалення)
        actionsContainer.innerHTML = `
            <div class="likes-section">
                ${likesHTML}
            </div>
            <div class="admin-buttons">
                ${editButton}
                ${deleteButton}
            </div>
        `;
        
        // Додаємо обробник подій для кнопок лайків
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
        
        // Додаємо обробник для кнопки "Видалити"
        const deleteBtn = document.querySelector('.delete-post-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', async () => {
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
                            window.location.href = '/pages/Posts/posts.php';
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
        }
    }
});
</script>

<style>
.post-view-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.loading {
    text-align: center;
    font-size: 18px;
    margin: 40px 0;
}

.post-header {
    margin-bottom: 20px;
}

.post-title {
    font-size: 28px;
    margin-bottom: 10px;
}

.post-meta {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    color: #666;
    flex-wrap: wrap;
    gap: 15px;
}

.author-info {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: inherit;
}

.author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.post-image {
    margin: 20px 0;
    text-align: center;
}

.full-image {
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.post-text-content {
    line-height: 1.6;
    font-size: 16px;
    margin-bottom: 30px;
}

.post-actions {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.all-likes-container {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.like-button {
    display: flex;
    align-items: center;
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 20px;
    background-color: #f8f8f8;
    cursor: pointer;
    transition: all 0.2s;
}

.like-button.liked {
    background-color: #e3f2fd;
    border-color: #2196f3;
    box-shadow: 0 0 3px rgba(33, 150, 243, 0.5);
}

.like-icon {
    width: 16px;
    height: 16px;
    margin-right: 5px;
}

.admin-buttons {
    display: flex;
    gap: 10px;
}

.edit-post-btn, .delete-post-btn {
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.edit-post-btn {
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
}

.delete-post-btn {
    background-color: #f44336;
    color: white;
    border: none;
}

.back-button {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 15px;
    background-color: #f0f0f0;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
}
</style>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>