 <?php
    session_start();
   
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"> </script>
    <script src="https://kit.fontawesome.com/33688215fa.js" crossorigin="anonymous"></script>
</head>
<body>
   
<nav>
    <div class="wrapper">
        <img id="cookbook" src="https://i.ibb.co/yVrwXdh/recipe.png" width='49,5' height='49,94' alt='Cookbook'>
        <p id="logoName">Cookbook</p>
        <p id="podLogo">A piece of cake!</p>
        <ul>
            <!-- check if user is loged in -->
            <?php
                if (isset($_SESSION["useruid"])) {
                    echo "<li><a href='profile.php'>Profile</a></li>";
                    echo "<li><a href='includes/logout.inc.php'><div class='button'>Log out</div></a></li>";
                    echo "<li><a href='favs.php' id='Heart' title='My faves!'><img src='https://i.ibb.co/8Db5h7H/heart.png' width='33'
                    height='32' alt='Heart'></a></li>";
                }
                else {
                    echo "<li><a href='login.php'>Log in</a></li>";
                    echo "<li><a href='signup.php'><div class='button'><p>Sign up</p></div></a></li>";
                    echo "<li><a href='favs.php' id='Heart' title='My faves!'><img src='https://i.ibb.co/8Db5h7H/heart.png' width='33'
                    height='32' alt='Heart'></a></li>";
                }
            ?>
        </ul>
    </div>
</nav>
