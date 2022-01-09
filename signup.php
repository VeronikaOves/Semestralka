<?php
    session_start();
    include "./includes/dbh.inc.php";
    include_once './includes/functions.inc.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['signup-form'])) {
            $form_data = [];
            $form_data['name'] = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
            $form_data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
            $form_data['password'] = isset($_POST['password']) ? $_POST['password'] : '';
            $form_data['passwordRepeat'] = isset($_POST['passwordRepeat']) ? $_POST['passwordRepeat'] : '';
            $form_data['validation'] = [];

            $form_data['validation']['name'] = (function($form_data) {
                if (strlen($form_data['name']) > 150) {
                    return 'Name should be no more than 150 symbols';
                }
            })($form_data);

            $form_data['validation']['name'] = (function($form_data) {
                if (strlen($form_data['name']) == 0) {
                    return 'Name is required';
                }
            })($form_data);

            $form_data['validation']['email'] = (function($form_data) {
                if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
                    return 'Invalid email';
                }
            })($form_data);

            $form_data['validation']['password'] = (function($form_data) {
                $pattern = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';
                if (!preg_match($pattern, $form_data['password'])) {
                    return 'Password must contain at least eight characters at least one number and both lower and uppercase letters';
                }
                if ($form_data['password'] != $form_data['passwordRepeat']) {
                    return 'Passwords do not match!';
                }
            })($form_data);

            $form_data['validation'] = array_filter($form_data['validation'], function($value) {
                return !empty($value);
            });

            if (empty($form_data['validation'])) {
                $form_data['error'] = (function($form_data, $db) {
                    if(!empty(getUserByEmail($form_data['email'], $db))) {
                        return 'Email is already taken!';
                    }
                    if (!createUser($form_data['name'], $form_data['email'], $form_data['password'], $db)) {
                        return 'Something went wrong';
                    }
                    $_SESSION['form_data']['registration_success'] = 'Yay! You are registred now!';
                    redirect('/login.php');
                })($form_data, $db);
            }

            $_SESSION['form_data'] = $form_data;
            redirect('/signup.php');
        }
     }

?>
<?php include_once 'header.php'; ?>

<section class="signup-form">
    <div class="container">
        <div class="header">
            <h2>Sign up</h2>
        </div>
        <form class = "form" id="signUp" action="/signup.php" method="post"> 
            <?php if (isset($_SESSION['form_data']['error'])): ?>
            <?= $_SESSION['form_data']['error']; ?></p>
            <?php endif; ?>
            <div class="form-control">
                <label>Full name</label>
                <input type="text" name="name" id="name" value="<?= isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : '' ?>">
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
                <?php if (isset($_SESSION['form_data']['validation']['name'])): ?>
                <p><?= $_SESSION['form_data']['validation']['name']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-control">
                <label>Email</label>
                <input  type="text" name="email" id="email" value="<?= isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : '' ?>" >
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
                <?php if (isset($_SESSION['form_data']['validation']['email'])): ?>
                <p><?= $_SESSION['form_data']['validation']['email']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-control">
                <label>Password</label>
                <input  type="password" name="password" id="password1">
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
                <?php if (isset($_SESSION['form_data']['validation']['password'])): ?>
                <p><?= $_SESSION['form_data']['validation']['password']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-control">
                <label>Repeat the password</label>
                <input  type="password" name="passwordRepeat" id="passwordRepeat" >
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <input type="text" name="signup-form" hidden>
            <button type="submit">Registrate me!</button>
        </form>
    </div>
        
</section>

<?php include_once 'footer.php'; ?>
<?php
    unset($_SESSION['form_data']);
?>