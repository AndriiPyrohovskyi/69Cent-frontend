document.addEventListener('DOMContentLoaded', () => {
    // Додавання категорії
    document.querySelector('.add-category-btn').addEventListener('click', () => {
        const categoryName = prompt('Введіть назву нової категорії:');
        if (categoryName) {
            alert(`Категорія "${categoryName}" додана!`);
            // Тут можна зробити AJAX-запит для збереження на сервері
        }
    });
    // Додавання лайка
    document.querySelector('.add-like-btn').addEventListener('click', () => {
        const likeName = prompt('Введіть назву нового лайка:');
        const likeCarma = prompt('Введіть карму для лайка:');
        if (likeName && likeCarma) {
            alert(`Лайк "${likeName}" з кармою ${likeCarma} додано!`);
            // Тут можна зробити AJAX-запит для збереження на сервері
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