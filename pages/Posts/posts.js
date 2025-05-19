document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.getElementById('filter-form');
    const postsContainer = document.querySelector('.posts-container');

    // Функція для оновлення постів
    const updatePosts = async () => {
        const formData = new FormData(filterForm);
        formData.append('ajax', '1'); // Додаємо параметр для AJAX-запиту

        const queryString = new URLSearchParams(formData).toString();
        const response = await fetch(`/pages/Posts/posts.php?${queryString}`);
        const html = await response.text();

        postsContainer.innerHTML = html;
    };

    // Слухач для зміни категорії
    filterForm.addEventListener('change', (event) => {
        if (event.target.name === 'category') {
            updatePosts();
        }
    });

    // Слухач для введення тексту в поле пошуку
    filterForm.querySelector('.search-input').addEventListener('input', () => {
        updatePosts();
    });
});