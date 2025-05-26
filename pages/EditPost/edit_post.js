document.addEventListener('DOMContentLoaded', async () => {
    const editPostForm = document.getElementById('editPostForm');
    const statusMessage = document.getElementById('statusMessage');
    const postId = new URLSearchParams(window.location.search).get('id');
    const imageUrlInput = document.getElementById('image_url');
    const previewImg = document.getElementById('previewImg');
    
    if (!postId) {
        showMessage('ID поста не вказано.', 'error');
        return;
    }
    
    // Отримуємо токен авторизації
    const authToken = localStorage.getItem('authToken');
    if (!authToken) {
        window.location.href = '/pages/Login/login.php';
        return;
    }
    
    // Отримуємо поточного користувача
    let currentUser;
    try {
        const userResponse = await fetch('http://69centapi.local/api/current_user', {
            headers: { 'Authorization': `Bearer ${authToken}` }
        });
        
        if (userResponse.ok) {
            currentUser = await userResponse.json();
        } else {
            throw new Error('Failed to get current user');
        }
    } catch (err) {
        console.error('Error loading user data:', err);
        showMessage('Не вдалося завантажити дані користувача. Спробуйте оновити сторінку.', 'error');
    }
    
    // Завантажуємо категорії
    try {
        const categoriesResponse = await fetch('http://69centapi.local/api/categories');
        if (categoriesResponse.ok) {
            const categories = await categoriesResponse.json();
            const categorySelect = document.getElementById('category');
            
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
        }
    } catch (err) {
        console.error('Error loading categories:', err);
        showMessage('Не вдалося завантажити категорії.', 'error');
    }
    
    // Завантажуємо дані поста
    try {
        const postResponse = await fetch(`http://69centapi.local/api/posts/${postId}`);
        if (!postResponse.ok) {
            throw new Error(`HTTP error ${postResponse.status}`);
        }
        
        const post = await postResponse.json();
        
        // Перевіряємо права доступу
        const isAuthor = Number(currentUser?.id) === Number(post.author_id);
        const isAdmin = currentUser?.role === 'admin';
        
        if (!isAuthor && !isAdmin) {
            showMessage('У вас немає прав на редагування цього поста.', 'error');
            setTimeout(() => {
                window.location.href = '/pages/Profile/profile.php?tab=posts';
            }, 2000);
            return;
        }
        
        // Заповнюємо форму даними поста
        document.getElementById('title').value = post.post_title || '';
        document.getElementById('content').value = post.post_text || '';
        document.getElementById('image_url').value = post.post_image || '';
        
        // Вибираємо категорію
        const categorySelect = document.getElementById('category');
        const categoryOptions = Array.from(categorySelect.options);
        const categoryOption = categoryOptions.find(option => option.textContent === post.post_category);
        if (categoryOption) {
            categoryOption.selected = true;
        }
        
        // Відображаємо превʼю зображення, якщо воно є
        if (post.post_image) {
            previewImg.src = post.post_image;
            previewImg.style.display = 'block';
        }
    } catch (err) {
        console.error('Error loading post data:', err);
        showMessage('Не вдалося завантажити дані поста. Спробуйте оновити сторінку.', 'error');
    }
    
    // Оновлення превʼю зображення при зміні URL
    imageUrlInput.addEventListener('input', () => {
        const imageUrl = imageUrlInput.value.trim();
        if (imageUrl) {
            previewImg.src = imageUrl;
            previewImg.style.display = 'block';
            
            // Обробка помилки завантаження зображення
            previewImg.onerror = () => {
                previewImg.style.display = 'none';
                showMessage('Не вдалося завантажити зображення за вказаним URL.', 'error');
            };
        } else {
            previewImg.style.display = 'none';
        }
    });
    
    // Обробка відправки форми
    editPostForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Отримуємо значення полів форми
        const title = document.getElementById('title').value;
        const content = document.getElementById('content').value;
        const categoryId = document.getElementById('category').value;
        const imageUrl = document.getElementById('image_url').value;
        
        // Базова валідація
        if (!title || !content || !categoryId) {
            showMessage('Заголовок, текст та категорія є обов\'язковими полями.', 'error');
            return;
        }
        
        // Підготовка даних для відправки
        const postData = {
            title,
            description: content,
            category_id: categoryId,
            image: imageUrl,
            user_id: currentUser.id,
            status_id: 1 // Припускаємо, що 1 = опублікований пост
        };
        
        try {
            // Відправка запиту на оновлення поста
            const response = await fetch(`http://69centapi.local/api/posts/${postId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${authToken}`
                },
                body: JSON.stringify(postData)
            });
            
            if (response.ok) {
                showMessage('Пост успішно оновлено!', 'success');
                
                // Перенаправлення на сторінку перегляду поста після паузи
                setTimeout(() => {
                    window.location.href = `/pages/PostView/post_view.php?id=${postId}`;
                }, 1500);
            } else {
                const errorData = await response.json();
                showMessage(`Помилка: ${errorData.error || 'Не вдалося оновити пост'}`, 'error');
            }
        } catch (err) {
            console.error('Error updating post:', err);
            showMessage('Помилка мережі. Спробуйте пізніше.', 'error');
        }
    });
    
    // Функція для відображення повідомлень
    function showMessage(message, type = 'info') {
        statusMessage.textContent = message;
        statusMessage.className = 'alert';
        statusMessage.classList.add(type);
    }
});