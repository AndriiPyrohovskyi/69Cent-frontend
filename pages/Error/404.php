<?php
$title = "Page Not Found";
ob_start();
?>
<h1>У вас виникла помилка 404</h1>
<h1>А у нас будь-яка порція кави - 69 центів.</h1>
<p>Надпис на курточці - пізда</p>
<a href="/index.php">Go back to Home</a>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>