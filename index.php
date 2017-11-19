<?php include "header.php"; ?>

<?php
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
