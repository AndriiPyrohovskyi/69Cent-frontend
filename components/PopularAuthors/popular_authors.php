<div class="popular-authors-widget">
    <h3>Популярні автори</h3>
    <div class="popular-authors-list">
        <!-- Тут будуть відображені автори -->
        <p class="loading">Завантаження авторів...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('http://69centapi.local/api/popular_authors?limit=5');
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}`);
        }
        
        const authors = await response.json();
        displayPopularAuthors(authors);
    } catch (err) {
        console.error('Помилка завантаження популярних авторів:', err);
        document.querySelector('.popular-authors-list').innerHTML = 
            '<p>Не вдалося завантажити список авторів.</p>';
    }
    
    function displayPopularAuthors(authors) {
        const container = document.querySelector('.popular-authors-list');
        container.innerHTML = '';
        
        if (!authors || authors.length === 0) {
            container.innerHTML = '<p>Популярних авторів не знайдено</p>';
            return;
        }
        
        authors.forEach(author => {
            const avatarUrl = author.avatar_url || 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y';
            
            container.innerHTML += `
                <a href="/pages/ProfileView/profile_view.php?id=${author.id}" class="author-card">
                    <img src="${avatarUrl}" alt="${author.username}" class="author-avatar">
                    <div class="author-info">
                        <span class="author-name">${author.username}</span>
                        <span class="author-stats">
                            <span class="karma-count">${author.karma || 0} карми</span>
                            <span class="post-count">${author.post_count || 0} постів</span>
                        </span>
                    </div>
                </a>
            `;
        });
    }
});
</script>

<style>
.popular-authors-widget {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 15px;
    margin-bottom: 20px;
}

.popular-authors-widget h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #333;
    font-size: 18px;
}

.author-card {
    display: flex;
    align-items: center;
    padding: 10px 0;
    text-decoration: none;
    color: inherit;
    border-bottom: 1px solid #eee;
}

.author-card:last-child {
    border-bottom: none;
}

.author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.author-info {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 600;
    color: #333;
}

.author-stats {
    font-size: 12px;
    color: #666;
    display: flex;
    gap: 10px;
}

.karma-count {
    color: #4caf50;
}

.loading {
    text-align: center;
    color: #666;
    padding: 10px 0;
}
</style>