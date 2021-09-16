<?php
// if page is login type  
if ($login_page){  
?>    
<html>
    <head>
      <title><?php echo $pageTitle; ?></title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="../css/normalize.css">
      <link rel="stylesheet" href="../css/main.css">
      <link rel="stylesheet" href="../css/login.css">
      <link rel="shortcut icon" href="../img/favicon/game_pad.ico">
    </head>
    <body>
        <header>
            <a href="../index.php">
                <img src="../img/logo/logo.jpg" alt="Game Center Logo">
            </a>
<?php
} // end if
// else page is not a login page
else {
?>
<html>
    <head>
      <title><?php echo $pageTitle; ?></title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="css/normalize.css">
      <link rel="stylesheet" href="css/main.css">
      <link rel="shortcut icon" href="img/favicon/game_pad.ico">
    </head>
    <body>
        <header>
            <a href="index.php">
                <img src="img/logo/logo.jpg" alt="Game Center Logo" >
            </a>    
        </header>
<?php
} // end else
?>