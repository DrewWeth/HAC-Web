<?php 
require_once 'engine/init.php';
// To direct users here, add: protect_page(); Here before loading header.
include 'layout/overall/header.php'; 
if (user_logged_in() === true) {
?>

<h1>STOP!</h1>
<p>Ummh... Why are you sniffing around here?</p>

<?php
} else {
?>

<h2>Sorry, you need to be logged in to do that!</h2>
<p>Please register or log in.</p>

<?php 
}
include 'layout/overall/footer.php'; ?>