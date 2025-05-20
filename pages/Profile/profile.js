document.addEventListener('DOMContentLoaded', () => {
    // Додавання категорії через форму
    document.querySelector('#add-category-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const categoryName = document.querySelector('#new-category-name').value.trim();
        if (categoryName) {
            alert(`Категорія "${categoryName}" додана!`);
            // Тут можна зробити AJAX-запит для збереження на сервері
            document.querySelector('#new-category-name').value = ''; // Очистити поле
        }
    });

    // Додавання лайка через форму
    document.querySelector('#add-like-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const likeName = document.querySelector('#new-like-name').value.trim();
        const likeCarma = document.querySelector('#new-like-carma').value.trim();
        if (likeName && likeCarma) {
            alert(`Лайк "${likeName}" з кармою ${likeCarma} додано!`);
            // Тут можна зробити AJAX-запит для збереження на сервері
            document.querySelector('#new-like-name').value = ''; // Очистити поле
            document.querySelector('#new-like-carma').value = ''; // Очистити поле
        }
    });

    // Редагування категорії
    document.querySelectorAll('.edit-category-btn').forEach(button => {
        button.addEventListener('click', () => {
            const categoryName = button.dataset.category;
            const newCategoryName = prompt('Редагувати категорію:', categoryName);
            if (newCategoryName) {
                alert(`Категорія "${categoryName}" змінена на "${newCategoryName}"!`);
                // Тут можна зробити AJAX-запит для оновлення на сервері
            }
        });
    });
    // Видалення категорії
    document.querySelectorAll('.delete-category-btn').forEach(button => {
        button.addEventListener('click', () => {
            const categoryName = button.dataset.category;
            if (confirm(`Ви впевнені, що хочете видалити категорію "${categoryName}"?`)) {
                alert(`Категорія "${categoryName}" видалена!`);
            }
        });
    });
    // Редагування лайка
    document.querySelectorAll('.edit-like-btn').forEach(button => {
        button.addEventListener('click', () => {
            const likeName = button.dataset.like;
            const newLikeName = prompt('Редагувати лайк:', likeName);
            if (newLikeName) {
                alert(`Лайк "${likeName}" змінено на "${newLikeName}"!`);
                // Тут можна зробити AJAX-запит для оновлення на сервері
            }
        });
    });
    // Видалення лайка
    document.querySelectorAll('.delete-like-btn').forEach(button => {
        button.addEventListener('click', () => {
            const likeName = button.dataset.like;
            if (confirm(`Ви впевнені, що хочете видалити лайк "${likeName}"?`)) {
                alert(`Лайк "${likeName}" видалено!`);
                // Тут можна зробити AJAX-запит для видалення на сервері
            }
        });
    });
});