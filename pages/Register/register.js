document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.querySelector('form');
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('http://69centapi.local/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ username, email, password }),
            });

            if (response.ok) {
                const data = await response.json();
                console.log('Реєстрація успішна:', data.message);
                alert('Реєстрація успішна! Тепер ви можете увійти.');
                window.location.href = '/pages/Login/login.php';
            } else {
                const errorData = await response.json();
                console.error('Помилка реєстрації:', errorData.error);
                alert('Помилка реєстрації: ' + errorData.error);
            }
        } catch (error) {
            console.error('Помилка мережі:', error.message);
            alert('Помилка мережі. Спробуйте пізніше.');
        }
    });
});