<?php
$title = "Create Post";
ob_start();
$post_data = [
    'id' => $post_id,
    'author_avatar' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
    'author_name' => 'Ivan Zolo',
    'author_date' => '2023-10-01',
    'author_role' => 'Admin',
    'author_сarma' => 100,
    'post_title' => 'Екатіріна Зізуліна',
    'post_text' => 'Повний текст поста...',
    'post_image' => 'https://images.next.thetruestory.news/thumb/92/c8/newsitem_3548925.webp',
    'post_category' => 'Плітки',
    'post_created_at' => '2023-10-01 12:00:00',
    'is_modified' => true,
    'post_modified_at' => '2023-10-02 12:00:00',
    'post_likes' => [
        'like' => [10, 'https://www.svgrepo.com/show/505406/like.svg'],
        'dislike' => [2, 'https://www.svgrepo.com/show/505358/dislike.svg'],
    ],
];

include '../../components/Post/post.php';
?>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>