<?php
$title = "Home Page";
ob_start();
?>
<h2>Welcome to the Profile Page</h2>
<p>This is the Profile</p>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>