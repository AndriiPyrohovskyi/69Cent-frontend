document.addEventListener('DOMContentLoaded', async () => {
    // Отримуємо параметри фільтрації
    const urlParams = new URLSearchParams(window.location.search);
    const selectedCategory = urlParams.get('category') || 'Усі';
    const searchQuery = urlParams.get('search') || '';
    
    // Отримуємо токен авторизації
    const authToken = localStorage.getItem('authToken');
    
    // Отримуємо інформацію про поточного користувача
    let currentUser = null;
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
    
    // Завантажуємо всі типи лайків
    let allLikeTypes = [];
    try {
        const likesResponse = await fetch('http://69centapi.local/api/like_types');
        if (likesResponse.ok) {
            allLikeTypes = await likesResponse.json();
        }
    } catch (err) {
        console.error('Помилка завантаження типів лайків:', err);
    }
    
    // Завантажуємо категорії для фільтра
    try {
        const categoriesResponse = await fetch('http://69centapi.local/api/categories');
        if (categoriesResponse.ok) {
            const categories = await categoriesResponse.json();
            
            // Додаємо опцію "Усі" на початку
            const categorySelect = document.querySelector('.category-select');
            if (categorySelect) {
                categorySelect.innerHTML = '<option value="Усі">Усі</option>';
                
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.name;
                    option.textContent = category.name;
                    if (category.name === selectedCategory) {
                        option.selected = true;
                    }
                    categorySelect.appendChild(option);
                });
            }
        }
    } catch (err) {
        console.error('Помилка завантаження категорій:', err);
    }
    
    // Завантажуємо пости
    try {
        let apiUrl = 'http://69centapi.local/api/posts';
        
        // Завантажуємо пости з вибраними фільтрами
        // Тут можна додати параметри фільтрації, якщо API їх підтримує
        
        const postsResponse = await fetch(apiUrl);
        if (!postsResponse.ok) {
            throw new Error(`HTTP помилка ${postsResponse.status}`);
        }
        
        const posts = await postsResponse.json();
        
        // Фільтруємо пости на стороні клієнта, якщо API не підтримує фільтрацію
        let filteredPosts = posts;
        
        if (selectedCategory && selectedCategory !== 'Усі') {
            filteredPosts = filteredPosts.filter(post => post.post_category === selectedCategory);
        }
        
        if (searchQuery) {
            filteredPosts = filteredPosts.filter(post => 
                post.post_title.toLowerCase().includes(searchQuery.toLowerCase()) || 
                post.post_text.toLowerCase().includes(searchQuery.toLowerCase())
            );
        }
        
        renderPosts(filteredPosts);
    } catch (err) {
        console.error('Помилка завантаження постів:', err);
        document.querySelector('.posts-container').innerHTML = '<p>Помилка завантаження постів. Спробуйте пізніше.</p>';
    }
    
    // Функція для відображення постів
    function renderPosts(posts) {
        const container = document.querySelector('.posts-container');
        if (!container) return;
        
        container.innerHTML = '';
        
        if (!posts || posts.length === 0) {
            container.innerHTML = '<p>Постів не знайдено</p>';
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
                    <a class="author-link" href="/pages/ProfileView/profile_view.php?id=${post.author_id}">
                        <div class="preview-header">
                            <img src="${post.author_avatar || 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'}" alt="Author Avatar" class="preview-avatar">
                            <span class="preview-time">${new Date(post.post_created_at).toLocaleString()}</span>
                            <span class="preview-author">${post.author_name}</span>
                        </div>
                    </a>
                    <a class="post-link" href="/pages/PostView/post_view.php?id=${post.id}">
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
        
        // Додаємо обробники подій
        addPostActionEventListeners();
    }
    
    // Функція для додавання обробників подій
    function addPostActionEventListeners() {
        // Обробка натискань кнопок лайків
        document.querySelectorAll('.like-button').forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                
                const postId = button.dataset.postId;
                const likeTypeId = button.dataset.likeTypeId;
                const likeName = button.dataset.likeName;
                
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
        
        // Обробка кнопок редагування
        document.querySelectorAll('.edit-post-btn').forEach(button => {
            button.addEventListener('click', () => {
                const postId = button.dataset.postId;
                window.location.href = `/pages/EditPost/edit_post.php?id=${postId}`;
            });
        });
        
        // Обробка кнопок видалення
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
                            const postElement = document.querySelector(`.post-preview[data-post-id="${postId}"]`);
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
    
    // Обробник змін у формі фільтрів
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.querySelector('.category-select').addEventListener('change', function() {
            filterForm.submit();
        });
        
        filterForm.querySelector('.search-input').addEventListener('input', function() {
            // Додайте затримку для уникнення занадто частих запитів під час швидкого друку
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 500); // 500ms затримка
        });
    }
});