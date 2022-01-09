<?php


if (isset($_COOKIE['colorTheme'])) {
    setcookie ("colorTheme", "", time() - 3600); 
}
else {
    setcookie('colorTheme', 'darkMode');
}
    
?>