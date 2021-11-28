<?php
    include_once 'header.php';
?>

<section class="signup-form">
    <div class="container">
            <div class="header">
                <h2>Sign up</h2>
            </div>
        <form class = "form" id="signUp"  method="post"> 
            <div class="form-control">
                <label>Full name</label>
                <input  type="text" name="name" id="name" required pattern = "^(.{1,150})$" title="Name should be at least 1 characters but no more than 150">
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <div class="form-control">
                <label>Email</label>
                <input  type="text" name="email" id="email" required pattern = "[^@\s]+@[^@\s]+\.[^@\s]+"/>
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <div class="form-control">  
                <label>User name</label>
                <input  type="text" name="uid" id="uid1" required pattern = "^(.{2,40})$" title="User name should be at least 2 characters but no more than 40">
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <div class="form-control">
                <label>Password</label>
                <input  type="password" name="pwd" id="pwd1" required pattern = "^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$" title="Password must contain at least eight characters, at least one number and both lower and uppercase letters">
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <div class="form-control">
                <label>Repeat the passwod</label>
                <input  type="password" name="pwdRepeat" id="pwdRep" required>
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <button type="submit" name="submit">Registrate me!</button>
        </form>
    </div>
        
    <?php
    //Wrong or empty input errors, no redirect
    


    //Contection error and succesfull singed up, redirect
    // if (isset($_GET["error"])) {
    //     if ($_GET["error"] == "fail") {
    //         echo "<p>Something went wrong!</p>";
    //     }
    //     else if ($_GET["error"] == "none") {
    //         echo "<p>You have signed up!</p>";
    //     }
    // }
    ?>
</section>


    

<?php
    include_once 'footer.php';
?>