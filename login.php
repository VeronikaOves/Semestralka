<?php
    include_once 'header.php';
?>

    <section class="signup-form">
        <div class="container">
            <div class="header"><h2>Log In</h2></div>
                <div class="signup-form">
                    <form class="form" id="logIn" method="post">
                        <div class="form-control">
                            <label>User name</label>
                            <input type="text" name="uid" id="uid2" required pattern = "^(.{2,40})$" title="User name should be at least 2 characters but no more than 40">
                            <i class="fas fa-check-circle"></i>
                            <i class ="fas fa-exclamation-circle"></i>
                            <small>Error message</small>
                        </div>
                        
                        <div class="form-control">
                            <label>Password</label>
                            <input type="password" name="pwd" id="pwd2" required pattern = "^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$" title="Password must contain at least eight characters, at least one number and both lower and uppercase letters">
                            <i class="fas fa-check-circle"></i>
                            <i class ="fas fa-exclamation-circle"></i>
                            <small>Error message</small>
                        </div>
                    
                        <button type="submit" name="submit">Submit!</button>
                    </form>
                </div>
        </div>
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "fail") {
                echo "<p>Something went wrong!</p>";
            } 
    }
    ?>
    </section>


<?php
    include_once 'footer.php';
?>