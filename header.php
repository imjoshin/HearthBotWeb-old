<?php session_start(); ?>

<html>
<head>
  <title>Hearthstone Cards</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
  <link rel="icon" href="img/icon.png">
  <link href="dist/css/app.css" rel="stylesheet" media="screen" />
  <script src="assets/js/jquery-3.2.1.min.js"></script>
  <script src="dist/js/app.js"></script>

</head>

<body>
<div id="background-image"></div>
<div id="header"></div>
<div id="wrapper" <?php echo isset($_SESSION['id']) ? "" : "class='wrapper-login'"; ?>>
