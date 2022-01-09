<?php 
    $user = null;

    if (isset($_SESSION['uid'])) {
        $result = getUserById($_SESSION['uid'], $db);
        if (!empty($result)) {
            $user = $result;
        }
    }
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php if (isset($_COOKIE['colorTheme'])): ?> 
        <link rel="stylesheet" href="darkMode.css"> 
    <?php else: ?> 
        <link rel="stylesheet" href="style.css">
    <?php endif; ?>
    <link rel="stylesheet" href="print.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer src="script.js"> </script>
    <script src="https://kit.fontawesome.com/33688215fa.js" crossorigin="anonymous"></script>
</head>
<body>
<nav>
    <div class="wrapper">
        <a href="index.php"><img id="cookbook" src="https://i.ibb.co/yVrwXdh/recipe.png" width='49' height='50' alt='Cookbook'></a>
        <div id="logoName">Cookbook</div>
        <div id="podLogo">A piece of cake!</div>
        <ul>
            <!-- check if user is loged in -->
            <?php if ($user): ?>
            <li><a href='includes/logout.inc.php'><div class='button'><div class='logOut'>Log out</div></div></a></li>
            <li><a href='favs.php' id='Heart' title='My faves!'><img src='https://i.ibb.co/8Db5h7H/heart.png' width='33' height='32' alt='Heart'></a></li>
        </ul>
            <div class="darkModeButton"><i class="fa fa-moon-o" aria-hidden="true" onclick="darkMode('<?= getCurrentUrl()?>')"></i></div>
            <?php else: ?>
            <li><a href='login.php'>Log in</a></li>
            <li><a href='signup.php'><div class='button'><p>Sign up</p></div></a></li>
            <li><a href='favs.php' id='Heart' title='My faves!'><img src='https://i.ibb.co/8Db5h7H/heart.png' width='33' height='32' alt='Heart'></a></li>
        </ul>
        <div class="darkModeButton"><i class="fa fa-moon-o" aria-hidden="true" onclick="darkMode('<?= getCurrentUrl()?>')"></i></div>
            <?php endif; ?>

        
    </div>
</nav>