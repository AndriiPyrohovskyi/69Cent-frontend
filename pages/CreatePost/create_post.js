document.addEventListener('DOMContentLoaded', async () => {
    const createPostForm = document.querySelector('.create-post-form');
    const categorySelect = document.getElementById('category');
    
    // Завантажуємо категорії з API
    try {
        const categoryResponse = await fetch('http://69centapi.local/api/categories');
        if (categoryResponse.ok) {
            const categories = await categoryResponse.json();
            
            // Очищаємо селект від існуючих опцій, крім першої (placeholder)
            while (categorySelect.options.length > 1) {
                categorySelect.remove(1);
            }
            
            // Додаємо отримані категорії
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.name;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
        } else {
            console.error('Не вдалося завантажити категорії з API');
        }
    } catch (error) {
        console.error('Помилка при завантаженні категорій:', error);
    }
    
    if (createPostForm) {
        createPostForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Запобігаємо стандартній відправці форми
            
            // Отримуємо дані з форми
            const categoryName = document.getElementById('category').value;
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            const imageUrl = document.getElementById('image_url').value;
            
            // Отримуємо токен авторизації
            const authToken = localStorage.getItem('authToken');
            
            // Отримуємо дані поточного користувача
            const currentUser = JSON.parse(localStorage.getItem('currentUser') || '{}');
            
            if (!authToken || !currentUser.id) {
                alert('Ви повинні бути авторизовані для створення постів');
                window.location.href = '/pages/Auth/login.php';
                return;
            }
            
            try {
                // Спочатку отримуємо ID категорії за назвою
                const categoryResponse = await fetch(`http://69centapi.local/api/categories`);
                if (!categoryResponse.ok) {
                    throw new Error(`Помилка отримання категорій: ${categoryResponse.status}`);
                }
                
                const categories = await categoryResponse.json();
                const selectedCategory = categories.find(cat => cat.name === categoryName);
                
                if (!selectedCategory) {
                    throw new Error(`Категорія "${categoryName}" не знайдена`);
                }
                
                // Отримуємо ID статусу (припускаємо, що "published" = 1)
                const statusId = 1; // За замовчуванням опублікований
                
                // Формуємо дані для відправки
                const postData = {
                    user_id: currentUser.id,      // ID автора
                    category_id: selectedCategory.id, // ID категорії
                    status_id: statusId,          // ID статусу
                    title: title,                 // Заголовок
                    description: content,         // Опис/текст посту
                    image: imageUrl || null       // Посилання на зображення
                };
                
                // Відправляємо запит на створення посту
                const response = await fetch('http://69centapi.local/api/create_post', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: JSON.stringify(postData)
                });
                
                if (!response.ok) {
                    // Перевіряємо, чи є відповідь у форматі JSON
                    let errorMessage = 'Помилка створення посту';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.error || errorMessage;
                    } catch (jsonError) {
                        // Якщо не вдалося розпарсити JSON, використовуємо текст статусу
                        errorMessage = `Помилка створення посту: ${response.statusText}`;
                    }
                    throw new Error(errorMessage);
                }
                
                // Перевіряємо, чи є вміст у відповіді
                const responseText = await response.text();
                
                if (responseText.trim()) {
                    try {
                        // Спроба розпарсити JSON лише якщо є вміст
                        const result = JSON.parse(responseText);
                        console.log('Результат створення посту:', result);
                    } catch (jsonError) {
                        console.warn('Відповідь сервера не у форматі JSON:', responseText);
                    }
                }
                
                alert('Пост успішно створено!');
                
                // Переадресація на сторінку з постами
                window.location.href = '/pages/Profile/profile.php?tab=posts';
                
            } catch (error) {
                console.error('Помилка:', error);
                alert(`Помилка: ${error.message}`);
            }
        });
    }
});