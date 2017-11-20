<?php include "header.php"; ?>

<?php
// session_start();
// session_unset();
// session_destroy();
	if (isset($_SESSION['id']))
	{
		include 'views/cards.php';
	}
	else
	{
		include 'views/login.php';
	}
?>

<?php include "footer.php" ?>
