<?php
$title = "Home Page";
ob_start();
?>
<h2>Welcome to the Home Page</h2>
<p>This is the main content of the page.</p>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>