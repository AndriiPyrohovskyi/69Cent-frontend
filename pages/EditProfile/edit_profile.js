document.addEventListener('DOMContentLoaded', async () => {
    const editProfileForm = document.getElementById('editProfileForm');
    const statusMessage = document.getElementById('statusMessage');
    
    // Отримуємо ID користувача з URL
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');
    
    // Отримуємо токен авторизації
    const authToken = localStorage.getItem('authToken');
    if (!authToken) {
        window.location.href = '/pages/Login/login.php';
        return;
    }
    
    // Отримуємо поточного користувача
    let currentUser;
    try {
        const currentUserResponse = await fetch('http://69centapi.local/api/current_user', {
            headers: { 'Authorization': `Bearer ${authToken}` }
        });
        
        if (currentUserResponse.ok) {
            currentUser = await currentUserResponse.json();
            console.log('Current user data:', currentUser);
            
            // Перевіряємо права доступу
            if (currentUser.id != userId && currentUser.role !== 'admin') {
                showMessage('У вас немає прав на редагування цього профілю', 'error');
                setTimeout(() => {
                    window.location.href = '/pages/Profile/profile.php?tab=profile';
                }, 2000);
                return;
            }
            
            // Завантажуємо дані користувача для редагування
            const response = await fetch(`http://69centapi.local/api/users/${userId}`, {
                headers: { 'Authorization': `Bearer ${authToken}` }
            });
            
            if (response.ok) {
                const user = await response.json();
                console.log('User data to edit:', user);
                
                // Заповнюємо форму даними користувача
                document.getElementById('username').value = user.username || '';
                document.getElementById('email').value = user.email || '';
                document.getElementById('avatar_url').value = user.avatar_url || '';
            } else {
                throw new Error('Failed to get user data');
            }
        } else {
            throw new Error('Failed to authenticate');
        }
    } catch (err) {
        console.error('Error:', err);
        showMessage('Не вдалося завантажити дані профілю', 'error');
    }
    
    // Обробка відправки форми
    editProfileForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log('Form submitted');
        
        // Отримуємо значення полів форми
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const avatarUrl = document.getElementById('avatar_url').value;
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        // Базова валідація
        if (!username || !email) {
            showMessage('Ім\'я користувача та email є обов\'язковими полями', 'error');
            return;
        }
        
        // Перевірка паролів
        if (newPassword) {
            if (!currentPassword) {
                showMessage('Введіть поточний пароль для його зміни', 'error');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showMessage('Новий пароль та підтвердження не співпадають', 'error');
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
        
        console.log('Sending data:', userData);
        
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
            
            console.log('Response status:', response.status);
            
            if (response.ok) {
                const updatedUser = await response.json();
                console.log('Updated user:', updatedUser);
                
                // Оновлюємо дані в localStorage, якщо це поточний користувач
                if (userId == currentUser.id) {
                    localStorage.setItem('currentUser', JSON.stringify(updatedUser));
                }
                
                showMessage('Профіль успішно оновлено!', 'success');
                
                // Перенаправлення на сторінку профілю
                setTimeout(() => {
                    window.location.href = '/pages/Profile/profile.php?tab=profile';
                }, 1500);
            } else {
                const errorData = await response.json();
                showMessage(`Помилка: ${errorData.error || 'Не вдалося оновити профіль'}`, 'error');
            }
        } catch (err) {
            console.error('Error during profile update:', err);
            showMessage('Помилка мережі при оновленні профілю', 'error');
        }
    });
    
    // Функція для відображення повідомлень
    function showMessage(message, type = 'info') {
        statusMessage.textContent = message;
        statusMessage.className = 'alert';
        statusMessage.classList.add(type);
    }
});