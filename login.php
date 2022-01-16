<?php 
session_start(); 
include "./includes/dbh.inc.php";
include_once './includes/functions.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login-form'])) {
        # form validation

        $form_data = [];
        $form_data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
        $form_data['password'] = isset($_POST['password']) ? $_POST['password'] : '';

        $form_error = (function(& $form_data, $db) {
            $text_error = 'Enter the correct password and email';

            if (empty($form_data['email'])) {
                return $text_error;
            }

            if (empty($form_data['password'])) {
                return $text_error;
            }
            
            $result = getUserByEmail($form_data['email'], $db);
            if (!$result) return $text_error;

            if (password_verify($form_data['password'],$result['password']))  {
                $_SESSION['uid'] = $result['user_id'];
                redirect('./index.php');
            }

            return $text_error;
        })($form_data, $db);

        if ($form_error) {
            $form_data['error'] = $form_error;
            $_SESSION['form_data'] = $form_data;
            redirect('/login.php');
        }
    }
}


?>

<?php include_once 'header.php'; ?>
    <section class="signup-form">
        <div class="container">
            <div class="header"><h2>Log In</h2></div>
            <div class="signup-form">
                <form class="form" id="logIn" method="post" action="/login.php">
                    <?php if (isset($_SESSION['form_data']['error'])) { ?>
                    <p class="error"><?= $_SESSION['form_data']['error']; ?></p>
                    <?php } ?>
                    <?php if (isset($_SESSION['form_data']['registration_success'])) { ?>
                    <p class="success"><?= $_SESSION['form_data']['registration_success']; ?></p>
                    <?php } ?>
                    <div class="form-control">
                        <label class ="isRequiered">Email</label>
                        <input type="text" name="email" id="email2" value="<?= isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : ''; ?>" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" />
                        <i class ="fas fa-check-circle"></i>
                        <i class ="fas fa-exclamation-circle"></i>
                        <small>Error message</small>
                    </div>
                    <div class="form-control">
                        <label class ="isRequiered">Password</label>
                        <input type="password" name="password" id="password2" reqired pattern="^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{8,}$" />
                        <i class="fas fa-check-circle"></i>
                        <i class ="fas fa-exclamation-circle"></i>
                        <small>Error message</small>
                    </div>
                    <input type="text" name="login-form" hidden>
                    <button type="submit">Submit!</button>
                </form>
            </div>
        </div>
    </section>
<?php include_once 'footer.php'; ?>
<?php

unset($_SESSION['form_data']);

?>