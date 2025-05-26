document.addEventListener('DOMContentLoaded', async () => {
    // Визначаємо активну вкладку з URL
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'profile';
    const adminSubTab = urlParams.get('subtab') || 'users';
    
    console.log('Active tab:', activeTab, 'Admin subtab:', adminSubTab);
    
    // Завантажуємо дані поточного користувача спочатку
    const authToken = localStorage.getItem('authToken');
    let currentUser = null;
    
    try {
        // Отримання даних поточного користувача
        const currentUserResponse = await fetch('http://69centapi.local/api/current_user', {
            headers: { 'Authorization': `Bearer ${authToken}` }
        });
        
        if (!currentUserResponse.ok) {
            throw new Error(`HTTP error ${currentUserResponse.status}`);
        }
        
        currentUser = await currentUserResponse.json();
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        
        // Відправляємо дані користувача на сервер для PHP-сесії
        await fetch('/pages/Profile/profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ currentUser })
        });
        
        console.log('Поточний користувач:', currentUser);
    } catch (err) {
        console.error('Помилка отримання даних користувача:', err);
        // Спробуємо використати кешовані дані
        const cachedUser = localStorage.getItem('currentUser');
        if (cachedUser) {
            currentUser = JSON.parse(cachedUser);
        }
    }
    
    // Завантажуємо дані відповідно до активної вкладки
    if (activeTab === 'profile') {
        loadProfileData(currentUser);
    } else if (activeTab === 'posts') {
        loadUserPosts(currentUser);
    } else if (activeTab === 'admin' && currentUser?.role === 'admin') {
        if (adminSubTab === 'users') {
            loadAdminUsers();
        } else if (adminSubTab === 'posts') {
            loadAdminPosts();
        } else if (adminSubTab === 'settings') {
            loadCategoriesAndLikes();
        }
    }
    
    // Глобальна змінна для зберігання всіх типів лайків
    let allLikeTypes = [];
    
    // Завантажуємо всі типи лайків при ініціалізації
    try {
        const likesResponse = await fetch('http://69centapi.local/api/like_types');
        if (likesResponse.ok) {
            allLikeTypes = await likesResponse.json();
            console.log('Доступні типи лайків:', allLikeTypes);
        }
    } catch (err) {
        console.error('Помилка завантаження типів лайків:', err);
    }
    
    // Функції для завантаження даних
    async function loadProfileData(user) {
        const container = document.querySelector('#profile-card-container');
        if (container && user) {
            renderProfileCard(user, '#profile-card-container');
        }
    }
    
    async function loadUserPosts(user) {
        if (!user) return;
        
        try {
            const userPostsResponse = await fetch(`http://69centapi.local/api/posts/user/${user.id}`, {
                headers: { 'Authorization': `Bearer ${authToken}` }
            });
            
            if (!userPostsResponse.ok) {
                throw new Error(`HTTP error ${userPostsResponse.status}`);
            }
            
            const userPosts = await userPostsResponse.json();
            console.log('Пости користувача:', userPosts);
            
            const container = document.querySelector('#posts-container');
            if (container) {
                renderPosts(userPosts, '#posts-container');
            }
        } catch (err) {
            console.error('Помилка завантаження постів користувача:', err);
        }
    }
    
    async function loadAdminUsers() {
        try {
            const usersAdminResponse = await fetch('http://69centapi.local/api/users', {
                headers: { 'Authorization': `Bearer ${authToken}` }
            });
            
            if (!usersAdminResponse.ok) {
                throw new Error(`HTTP error ${usersAdminResponse.status}`);
            }
            
            const usersAdmin = await usersAdminResponse.json();
            console.log('Користувачі:', usersAdmin);
            
            const container = document.querySelector('#profile-card-admin-container');
            if (container) {
                renderAdminUsers(usersAdmin, '#profile-card-admin-container');
            }
        } catch (err) {
            console.error('Помилка завантаження списку користувачів:', err);
        }
    }
    
    async function loadAdminPosts() {
        try {
            const adminPostsResponse = await fetch('http://69centapi.local/api/posts', {
                headers: { 'Authorization': `Bearer ${authToken}` }
            });
            
            if (!adminPostsResponse.ok) {
                throw new Error(`HTTP error ${adminPostsResponse.status}`);
            }
            
            const adminPosts = await adminPostsResponse.json();
            console.log('Адмінські пости:', adminPosts);
            
            const container = document.querySelector('#posts-card-admin-container');
            if (container) {
                renderAdminPosts(adminPosts, '#posts-card-admin-container');
            }
        } catch (err) {
            console.error('Помилка завантаження адмінських постів:', err);
        }
    }
    
    async function loadCategoriesAndLikes() {
        try {
            // Завантаження категорій
            const categoriesResponse = await fetch('http://69centapi.local/api/categories');
            if (!categoriesResponse.ok) {
                throw new Error(`HTTP error ${categoriesResponse.status}`);
            }
            
            const categories = await categoriesResponse.json();
            const categoriesContainer = document.querySelector('.categories-container');
            
            if (categoriesContainer) {
                categoriesContainer.innerHTML = '';
                categories.forEach(category => {
                    categoriesContainer.innerHTML += `
                        <div class="category-card">
                            <input id="category-input-${category.name}" type="text" value="${category.name}" />
                            <div class="category-actions">
                                <button class="edit-category-btn" data-category="${category.name}">Редагувати</button>
                                <button class="delete-category-btn" data-category="${category.name}">Видалити</button>
                            </div>
                        </div>
                    `;
                });
                
                // Додаємо обробники подій для кнопок категорій
                addCategoryEventListeners();
            }
            
            // Завантаження типів лайків
            const likesResponse = await fetch('http://69centapi.local/api/like_types');
            if (!likesResponse.ok) {
                throw new Error(`HTTP error ${likesResponse.status}`);
            }
            
            const likes = await likesResponse.json();
            const likesContainer = document.querySelector('.likes-container');
            
            if (likesContainer) {
                likesContainer.innerHTML = '';
                likes.forEach(like => {
                    likesContainer.innerHTML += `
                        <div class="like-card">
                            <input id="like-name-input-${like.name}" type="text" value="${like.name}" />
                            <input id="like-carma-input-${like.name}" type="number" value="${like.carma}" />
                            <input id="like-icon-input-${like.name}" type="text" value="${like.icon_url}" />
                            <div class="like-actions">
                                <button class="edit-like-btn" data-like="${like.name}">Редагувати</button>
                                <button class="delete-like-btn" data-like="${like.name}">Видалити</button>
                            </div>
                        </div>
                    `;
                });
                
                // Додаємо обробники подій для кнопок лайків
                addLikeEventListeners();
            }
            
            // Додаємо обробники для форм додавання
            addFormEventListeners();
            
        } catch (err) {
            console.error('Помилка завантаження категорій або лайків:', err);
        }
    }
    
    // Функції відображення
    function renderProfileCard(user, containerSelector) {
        const container = document.querySelector(containerSelector);
        const isCurrentUser = Number(currentUser.id) === Number(user.id);
        const canDelete = currentUser.role === 'admin' && user.role !== 'admin' && !isCurrentUser;
        if (!container) {
            console.error('Контейнер для відображення профілю не знайдено:', containerSelector);
            return;
        }
        
        // Placeholder для аватара, якщо він відсутній
        const avatarUrl = user.avatar_url || 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y';
        
        container.innerHTML = `
            <div class="profile-card">
                <div class="avatar">
                    <img src="${avatarUrl}" alt="Avatar">
                </div>
                <div class="info">
                    <h1 class="username-display">${user.username}</h1>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Роль:</strong> ${user.role}</p>
                    <p><strong>Зареєстровано:</strong> ${new Date(user.created_at).toLocaleString()}</p>
                    <p><strong>Карма:</strong> ${user.karma || 0}</p>
                    <div class="user-actions">
                        ${isCurrentUser ? 
                            `<button class="edit-user-btn" data-user-id="${user.id}">Редагувати</button>` : ''}
                        ${canDelete ? 
                            `<button class="delete-user-btn" data-user-id="${user.id}">Видалити</button>` : ''}
                    </div>
                </div>
            </div>
        `;
    }
    
    function renderPosts(posts, containerSelector) {
        const container = document.querySelector(containerSelector);
        if (!container) {
            console.error('Контейнер для відображення постів не знайдено:', containerSelector);
            return;
        }
        
        container.innerHTML = '';
        
        if (!posts || posts.length === 0) {
            container.innerHTML = '<p>Постів не знайдено</p>';
            return;
        }
        
        // Отримуємо поточного користувача з localStorage для перевірки прав
        const currentUser = JSON.parse(localStorage.getItem('currentUser') || '{}');
        
        posts.forEach(post => {
            // Перевіряємо права доступу
            const isAuthor = Number(currentUser.id) === Number(post.author_id);
            const isAdmin = currentUser.role === 'admin';
            
            // Підготовка кнопок редагування та видалення
            const editButton = isAuthor ? 
                `<button class="edit-post-btn" data-post-id="${post.id}">Редагувати</button>` : '';
            
            const deleteButton = (isAuthor || isAdmin) ? 
                `<button class="delete-post-btn" data-post-id="${post.id}">Видалити</button>` : '';
            
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
        
        // Додаємо обробники подій для кнопок
        addPostActionEventListeners();
    }
    
    // Додаємо нову функцію для обробки дій з постами
    function addPostActionEventListeners() {
        // Обробник для кнопок лайків
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
                    console.log('Sending like request:', {
                        postId,
                        likeTypeId,
                        userId: currentUser.id
                    });
                    
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
                    
                    console.log('Response status:', response.status);
                    console.log('Response headers:', Array.from(response.headers.entries()));
                    
                    const responseText = await response.text();
                    console.log('Raw response:', responseText);
                    
                    if (responseText.trim()) {
                        try {
                            const result = JSON.parse(responseText);
                            console.log('Parsed result:', result);
                            
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
                        console.log('Empty HTML response, trying to handle anyway');
                        
                        // Спроба оновити інтерфейс без відповіді
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
                    } else {
                        console.error('Empty response from server');
                    }
                } catch (err) {
                    console.error('Error during like operation:', err);
                }
            });
        });
        
        // Обробник для кнопки "Редагувати"
        document.querySelectorAll('.edit-post-btn').forEach(button => {
            button.addEventListener('click', () => {
                const postId = button.dataset.postId;
                window.location.href = `/pages/EditPost/edit_post.php?id=${postId}`;
            });
        });
        
        // Обробник для кнопки "Видалити"
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
                            // Видаляємо елемент з DOM без перезавантаження сторінки
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
    
    // Функції для додавання обробників подій
    function addUserActionEventListeners() {
        // Обробник для кнопки "Редагувати"
        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.dataset.userId;
                alert(`Редагування користувача з ID: ${userId}`);
                // Тут можна відкрити модальне вікно для редагування
            });
        });
        
        // Обробник для кнопки "Видалити"
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.dataset.userId;
                if (confirm(`Ви дійсно хочете видалити користувача з ID: ${userId}?`)) {
                    try {
                        const response = await fetch(`http://69centapi.local/api/users/${userId}`, {
                            method: 'DELETE',
                            headers: { 
                                'Authorization': `Bearer ${authToken}`,
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            alert('Користувача успішно видалено');
                            location.reload();
                        } else {
                            const errorData = await response.json();
                            alert(`Помилка: ${errorData.error || 'Не вдалося видалити користувача'}`);
                        }
                    } catch (err) {
                        console.error('Помилка при видаленні користувача:', err);
                        alert('Помилка мережі при видаленні користувача');
                    }
                }
            });
        });
    }
    
    function addCategoryEventListeners() {
        // Редагування категорії
        document.querySelectorAll('.edit-category-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const categoryName = button.dataset.category;
                const input = document.querySelector(`#category-input-${categoryName}`);
                const newCategoryName = input.value.trim();
                if (newCategoryName) {
                    try {
                        const response = await fetch(`http://69centapi.local/api/categories/${categoryName}`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ new_name: newCategoryName }),
                        });
                        if (response.ok) {
                            alert(`Категорія "${categoryName}" змінена на "${newCategoryName}"!`);
                            location.reload();
                        } else {
                            const error = await response.json();
                            alert(`Помилка: ${error.error}`);
                        }
                    } catch (err) {
                        alert('Помилка мережі.');
                    }
                }
            });
        });
        
        // Видалення категорії
        document.querySelectorAll('.delete-category-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const categoryName = button.dataset.category;
                if (confirm(`Ви впевнені, що хочете видалити категорію "${categoryName}"?`)) {
                    try {
                        const response = await fetch(`http://69centapi.local/api/categories/${categoryName}`, {
                            method: 'DELETE',
                        });
                        if (response.ok) {
                            alert(`Категорія "${categoryName}" видалена!`);
                            location.reload();
                        } else {
                            const error = await response.json();
                            alert(`Помилка: ${error.error}`);
                        }
                    } catch (err) {
                        alert('Помилка мережі.');
                    }
                }
            });
        });
    }
    
    function addLikeEventListeners() {
        // Редагування лайка
        document.querySelectorAll('.edit-like-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const likeName = button.dataset.like;
                const nameInput = document.querySelector(`#like-name-input-${likeName}`);
                const iconInput = document.querySelector(`#like-icon-input-${likeName}`);
                const carmaInput = document.querySelector(`#like-carma-input-${likeName}`);
                const newLikeName = nameInput.value.trim();
                const newIconUrl = iconInput.value.trim();
                const newCarmaInput = carmaInput.value.trim();
                if (newLikeName && newIconUrl) {
                    try {
                        const response = await fetch(`http://69centapi.local/api/like_types/${likeName}`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ name: newLikeName, carma: newCarmaInput, icon_url: newIconUrl }),
                        });
                        if (response.ok) {
                            alert(`Лайк "${likeName}" змінено!`);
                            location.reload();
                        } else {
                            const error = await response.json();
                            alert(`Помилка: ${error.error}`);
                        }
                    } catch (err) {
                        alert('Помилка мережі.');
                    }
                }
            });
        });
        
        // Видалення лайка
        document.querySelectorAll('.delete-like-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const likeName = button.dataset.like;
                if (confirm(`Ви впевнені, що хочете видалити лайк "${likeName}"?`)) {
                    try {
                        const response = await fetch(`http://69centapi.local/api/like_types/${likeName}`, {
                            method: 'DELETE',
                        });
                        if (response.ok) {
                            alert(`Лайк "${likeName}" видалено!`);
                            location.reload();
                        } else {
                            const error = await response.json();
                            alert(`Помилка: ${error.error}`);
                        }
                    } catch (err) {
                        alert('Помилка мережі.');
                    }
                }
            });
        });
    }
    
    function addFormEventListeners() {
        // Форма додавання категорії
        const addCategoryForm = document.querySelector('#add-category-form');
        if (addCategoryForm) {
            addCategoryForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const categoryName = document.querySelector('#new-category-name').value.trim();
                if (categoryName) {
                    try {
                        const response = await fetch('http://69centapi.local/api/categories', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ name: categoryName }),
                        });
                        if (response.ok) {
                            alert(`Категорія "${categoryName}" додана!`);
                            location.reload();
                        } else {
                            const error = await response.json();
                            alert(`Помилка: ${error.error}`);
                        }
                    } catch (err) {
                        alert('Помилка мережі.');
                    }
                }
            });
        }
        
        // Форма додавання лайка
        const addLikeForm = document.querySelector('#add-like-form');
        if (addLikeForm) {
            addLikeForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const likeName = document.querySelector('#new-like-name').value.trim();
                const likeCarma = document.querySelector('#new-like-carma').value.trim();
                const likeIconUrl = document.querySelector('#new-like-icon-url').value.trim();
                if (likeName && likeCarma && likeIconUrl) {
                    try {
                        const response = await fetch('http://69centapi.local/api/like_types', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ name: likeName, carma: likeCarma, icon_url: likeIconUrl }),
                        });
                        if (response.ok) {
                            alert(`Лайк "${likeName}" додано!`);
                            location.reload();
                        } else {
                            const error = await response.json();
                            alert(`Помилка: ${error.error}`);
                        }
                    } catch (err) {
                        alert('Помилка мережі.');
                    }
                }
            });
        }
    }
    
    function renderAdminUsers(users, containerSelector) {
        const container = document.querySelector(containerSelector);
        if (!container) {
            console.error('Контейнер для відображення користувачів не знайдено:', containerSelector);
            return;
        }
        
        // Отримуємо поточного користувача з localStorage
        const currentUser = JSON.parse(localStorage.getItem('currentUser') || '{}');
        
        container.innerHTML = ''; // Очищення контейнера
        
        if (!users || users.length === 0) {
            container.innerHTML = '<p>Користувачів не знайдено</p>';
            return;
        }
        
        users.forEach(user => {
            // Перевіряємо права доступу
            const isCurrentUser = Number(currentUser.id) === Number(user.id);
            const canDelete = currentUser.role === 'admin' && user.role !== 'admin' && !isCurrentUser;
            
            // Placeholder для аватара
            const avatarUrl = user.avatar_url || 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y';
            
            const userCard = document.createElement('div');
            userCard.className = 'profile-card';
            userCard.innerHTML = `
                <div class="avatar">
                    <img src="${avatarUrl}" alt="Avatar">
                </div>
                <div class="info">
                    <h2 class="username-display">${user.username}</h2>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Роль:</strong> ${user.role}</p>
                    <p><strong>Зареєстровано:</strong> ${new Date(user.created_at).toLocaleString()}</p>
                    <p><strong>Карма:</strong> ${user.karma || 0}</p>
                    
                    <div class="user-actions">
                        ${isCurrentUser ? 
                            `<button class="edit-user-btn" data-user-id="${user.id}">Редагувати</button>` : ''}
                        ${canDelete ? 
                            `<button class="delete-user-btn" data-user-id="${user.id}">Видалити</button>` : ''}
                    </div>
                </div>
            `;
            container.appendChild(userCard);
        });
        
        // Додавання обробників подій для кнопок
        addUserActionEventListeners();
    }

    function renderAdminPosts(posts, containerSelector) {
        // Для адмін-постів можна використовувати ту ж функцію, що й для звичайних постів
        renderPosts(posts, containerSelector);
    }
});