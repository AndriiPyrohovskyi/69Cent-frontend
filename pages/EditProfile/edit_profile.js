document.addEventListener('DOMContentLoaded', async () => {
    const editProfileForm = document.getElementById('editProfileForm');
    const statusMessage = document.getElementById('statusMessage');
    
    // Отримуємо токен авторизації
    const authToken = localStorage.getItem('authToken');
    if (!authToken) {
        window.location.href = '/pages/Login/login.php';
        return;
    }
    
    // Отримуємо ID користувача з URL
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');
    
    // Отримуємо поточного користувача
    let currentUser;
    try {
        // Спочатку перевіряємо, чи є у нас достатні права доступу
        const currentUserResponse = await fetch('http://69centapi.local/api/current_user', {
            headers: { 'Authorization': `Bearer ${authToken}` }
        });
        
        if (currentUserResponse.ok) {
            currentUser = await currentUserResponse.json();
            
            // Перевіряємо права доступу: користувач може редагувати тільки свій профіль,
            // або адміністратор може редагувати будь-який профіль
            if (currentUser.id != userId && currentUser.role !== 'admin') {
                showMessage('У вас немає прав на редагування цього профілю', 'error');
                setTimeout(() => {
                    window.location.href = '/pages/Profile/profile.php?tab=profile';
                }, 2000);
                return;
            }
            
            // Завантажуємо дані користувача для редагування
            const userResponse = await fetch(`http://69centapi.local/api/users/${userId}`, {
                headers: { 'Authorization': `Bearer ${authToken}` }
            });
            
            if (userResponse.ok) {
                const userData = await userResponse.json();
                
                // Заповнюємо форму даними користувача
                document.getElementById('username').value = userData.username || '';
                document.getElementById('email').value = userData.email || '';
                document.getElementById('avatar_url').value = userData.avatar_url || '';
            } else {
                throw new Error('Failed to get user data');
            }
        } else {
            throw new Error('Failed to get current user');
        }
    } catch (err) {
        console.error('Error loading user data:', err);
        showMessage('Не вдалося завантажити дані профілю. Спробуйте оновити сторінку.', 'error');
    }
    
    // Обробка відправки форми
    editProfileForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Отримуємо значення полів форми
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const avatarUrl = document.getElementById('avatar_url').value;
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        // Базова валідація
        if (!username || !email) {
            showMessage('Ім\'я користувача та email є обов\'язковими полями.', 'error');
            return;
        }
        
        // Перевірка паролів, якщо вони заповнені
        if (newPassword) {
            if (!currentPassword) {
                showMessage('Введіть поточний пароль для його зміни.', 'error');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showMessage('Новий пароль та підтвердження не співпадають.', 'error');
                return;
            }
            
            if (newPassword.length < 6) {
                showMessage('Новий пароль повинен містити не менше 6 символів.', 'error');
                return;
            }
        }
        
        // Підготовка даних для відправки
        const userData = {
            username,
            email,
            avatar_url: avatarUrl
        };
        
        // Додаємо паролі, якщо вони заповнені
        if (newPassword) {
            userData.current_password = currentPassword;
            userData.new_password = newPassword;
        }
        
        try {
            // Відправка запиту на оновлення даних користувача
            const response = await fetch(`http://69centapi.local/api/users/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${authToken}`
                },
                body: JSON.stringify(userData)
            });
            
            if (response.ok) {
                // Отримуємо оновлені дані користувача
                const updatedUser = await response.json();
                
                // Оновлюємо дані користувача в localStorage
                if (userId == currentUser.id) {
                    localStorage.setItem('currentUser', JSON.stringify(updatedUser));
                }
                
                showMessage('Профіль успішно оновлено!', 'success');
                
                // Перенаправлення на сторінку профілю після паузи
                setTimeout(() => {
                    window.location.href = '/pages/Profile/profile.php?tab=profile';
                }, 1500);
            } else {
                const errorData = await response.json();
                showMessage(`Помилка: ${errorData.error || 'Не вдалося оновити профіль'}`, 'error');
            }
        } catch (err) {
            console.error('Error updating profile:', err);
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