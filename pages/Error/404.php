<?php
$title = "Page Not Found";
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
?>
<h1>У вас виникла помилка 404</h1>
<h1>А у нас будь-яка порція кави - 69 центів.</h1>
<p>Надпис на курточці - пізда</p>
<a href="/index.php">Go back to Home</a>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>