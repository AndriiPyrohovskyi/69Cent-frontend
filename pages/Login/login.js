document.addEventListener('DOMContentLoaded', () => {
    const phpTokenElement = document.getElementById('php-auth-token');
    if (phpTokenElement) {
        const phpToken = phpTokenElement.dataset.token;
        if (phpToken && !localStorage.getItem('authToken')) { // Перевіряємо, чи токен вже є в localStorage
            localStorage.setItem('authToken', phpToken);
            console.log('Токен з PHP збережено:', phpToken);
        }
    }

    getCurrentUser();
    maybeRefreshToken();
    setInterval(maybeRefreshToken, 60 * 1000); // перевіряти щохвилини

    const logoutLink = document.getElementById('logout-link');
    const loginLink = document.getElementById('login-link');
    const registerLink = document.getElementById('register-link');

    // Перевіряємо, чи є токен
    const authToken = localStorage.getItem('authToken');
    if (authToken) {
        // Показуємо кнопку "Logout" і ховаємо "Login" та "Register"
        logoutLink.style.display = 'inline';
        loginLink.style.display = 'none';
        registerLink.style.display = 'none';
    }

    // Обробка кліку на "Logout"
    logoutLink.addEventListener('click', (e) => {
        e.preventDefault();
        handleLogout();
    });
});

function isTokenExpiringSoon(token) {
    try {
        const payload = JSON.parse(atob(token.split('.')[1]));
        const expiry = payload.exp * 1000;
        const now = Date.now();
        return (expiry - now) < 5 * 60 * 1000;
    } catch (e) {
        return true;
    }
}

function maybeRefreshToken() {
    const authToken = localStorage.getItem('authToken');
    if (!authToken) return;
    if (isTokenExpiringSoon(authToken)) {
        refreshToken();
    }
}

async function getCurrentUser() {
    const authToken = localStorage.getItem('authToken');
    if (!authToken) {
        console.log('Користувач не авторизований');
        return;
    }
    if (authToken.split('.').length !== 3) {
        console.log('Невалідний токен');
        localStorage.removeItem('authToken');
        return;
    }

    try {
        const response = await fetch('http://69centapi.local/api/current_user', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        if (!response.ok) throw new Error('Не вдалося отримати дані користувача');

        const currentUser = await response.json();
        console.log('Поточний користувач:', currentUser);

        updateHeaderWithUser(currentUser.username);
    } catch (error) {
        console.error('Помилка отримання користувача:', error.message);
        localStorage.removeItem('authToken');
    }
}


async function refreshToken() {
    const authToken = localStorage.getItem('authToken');
    if (!authToken) return;

    try {
        const response = await fetch('http://69centapi.local/api/refresh_token', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });

        if (!response.ok) throw new Error('Не вдалося оновити токен');

        const data = await response.json();
        localStorage.setItem('authToken', data.token);
        console.log('Токен оновлено');
    } catch (error) {
        console.error('Помилка оновлення токена:', error.message);
        localStorage.removeItem('authToken');
    }
}

function updateHeaderWithUser(username) {
    const secondNav = document.querySelectorAll('header nav')[1];
    if (!secondNav) return;

    if (username) {
        secondNav.innerHTML = `
            <ul>
                <li><a href="/pages/Profile/profile.php"><span>👤 ${username}</span></a></li>
                <li><a href="#" id="logout-link">Logout</a></li>
            </ul>
        `;
    } else {
        secondNav.innerHTML = `
            <ul>
                <li><a href="/pages/Login/login.php" id="login-link">Login</a></li>
                <li><a href="/pages/Register/register.php" id="register-link">Register</a></li>
            </ul>
        `;
    }

    const logoutLink = document.getElementById('logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', (e) => {
            e.preventDefault();
            handleLogout();
        });
    }
}

async function handleLogout() {
    const authToken = localStorage.getItem('authToken');
    localStorage.removeItem('authToken');
    const phpTokenElement = document.getElementById('php-auth-token');
    if (phpTokenElement) {
        phpTokenElement.remove();
    }

    try {
        await fetch('http://69centapi.local/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
            },
        });
        console.log('Вихід виконано успішно');
    } catch (error) {
        console.error('Помилка під час виходу:', error.message);
    }
    document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    updateHeaderWithUser(null);
    window.location.href = '/pages/Login/login.php';
}

async function handleLoginSuccess(token) {
    localStorage.setItem('authToken', token);
    console.log('Токен збережено:', token);

    // Викликаємо getCurrentUser для оновлення хедера
    await getCurrentUser();

    // Перенаправляємо на головну сторінку
    window.location.href = '/index.php';
}
