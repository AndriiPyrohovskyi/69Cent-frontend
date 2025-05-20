document.addEventListener('DOMContentLoaded', async () => {
    // Завантаження категорій
    try {
        const categoriesResponse = await fetch('http://69centapi.local/api/categories');
        const categories = await categoriesResponse.json();
        const categoriesContainer = document.querySelector('.categories-container');
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
    } catch (err) {
        console.error('Помилка завантаження категорій:', err);
    }

    try {
        const likesResponse = await fetch('http://69centapi.local/api/like_types');
        const likes = await likesResponse.json();
        const likesContainer = document.querySelector('.likes-container');
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
    } catch (err) {
        console.error('Помилка завантаження лайків:', err);
    }

    document.querySelector('#add-category-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const categoryName = document.querySelector('#new-category-name').value.trim();
        if (categoryName) {
            try {
                const response = await fetch('http://69centapi.local/api/categories', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name: categoryName}),
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

    document.querySelector('#add-like-form').addEventListener('submit', async (e) => {
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

    // Редагування категорії
    document.querySelectorAll('.edit-category-btn').forEach((button) => {
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

    document.querySelectorAll('.delete-category-btn').forEach((button) => {
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

    // Редагування лайка
    document.querySelectorAll('.edit-like-btn').forEach((button) => {
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
    document.querySelectorAll('.delete-like-btn').forEach((button) => {
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
});