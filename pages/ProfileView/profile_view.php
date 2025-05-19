<?php
$title = "Profile";
ob_start();
$current_user = [
    'id' => 1,
    'username' => 'coffee_lover',
    'email' => 'coffee@example.com',
    'role' => 'admin',
    'avatar_url' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
    'created_at' => '2024-10-15 12:30:00',
    'karma' => 100
];
$profile_user = [
    'id' => 2,
    'username' => 'coffee_lover',
    'email' => 'coffee@example.com',
    'role' => 'admin',
    'avatar_url' => 'https://uznayvse.ru/images/catalog/2022/3/ivan-zolo_0.jpg',
    'created_at' => '2024-10-15 12:30:00',
    'karma' => 100,
];

include '../../components/ProfileCard/profile_card.php';
?>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>