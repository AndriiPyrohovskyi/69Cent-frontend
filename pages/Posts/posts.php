<?php
$title = "Posts Page";
ob_start();
?>
<?php
$posts = [
    [
        'author_avatar' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
        'author_name' => 'Ivan Zolo',
        'author_date' => '2023-10-01',
        'author_role' => 'Admin',
        'post_title' => 'Екатіріна Зізуліна',
        'post_text' => 'Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна Екатіріна Зізуліна',
        'post_image' => 'https://images.next.thetruestory.news/thumb/92/c8/newsitem_3548925.webp',
        'post_category' => 'Плітки',
        'post_created_at' => '2023-10-01 12:00:00',
        'post_modified_at' => '2023-10-02 12:00:00'
    ],
];
foreach ($posts as $post_data) {
    include '../../components/Post/post.php';
}
?>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>