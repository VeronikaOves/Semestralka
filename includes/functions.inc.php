
<?php

/**
 * Web site with recipes
 * 
 * @autor Veronika Ovsyannikova
 * @package samples
 */




/**
 * Check if name is too long
 *
 * @param string   $name  full user name
 * 
 * @return boolean 
 */ 
function tooLongName($name) {
    if (strlen($name) > 150) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

/**
 * Check if email is valid
 *
 * @param string   $email user's email
 * 
 * @return boolean 
 */ 
function invalidEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

/**
 * Try to get user from database by user's id
 *
 * @param int  $id user's id
 * @param PDO $db database connection
 * 
 * @return array with information about user 
 * */
function getUserById($id, $db) {
    $sql = 'SELECT * FROM users WHERE user_id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}

/**
 * Try to get user from database by email, check if email is already taken
 *
 * @param string  $email user's email
 * @param PDO $db database connection
 * 
 * @return array with information about user
 */ 
function getUserByEmail($email, $db) {
    $sql = 'SELECT * from users WHERE email = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$email]);

    return $stmt->fetch();
}

/**
 * Registrate new user
 *
 * @param string   $name  full user name
 * @param string  $email user's email
 * @param string $password 
 * @param PDO $db database connection
 * 
 * @return bool returns true on success or false on failure
 */ 
function createUser($name, $email, $password, $db) { 
    $password = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([$name, $email, $password]);

    return $result;

}

/**
 * Redirect
 * 
 * @param string $url adress where we want to redirect
 * 
 */
function redirect($url) {
    header("Location: $url");
    die();
}

/**
 * Get current Url
 * 
 * @return string
 */
function getCurrentUrl() {
    return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}


/**
 * Check if user bookmarked this recipe
 * 
 * @param int  $usersid user's id
 * @param int $recipeid recipe's id
 * @param PDO $db database connection
 * 
 * @return bool
 * 
 */
function checkIfRecipeIsUsersFavorite($userid, $recipeid, $db) {
    $sql = 'SELECT * from favorites WHERE user_id = ? AND recipe_id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$userid, $recipeid]);

    return !!($stmt->fetchColumn());
}

/**
 * Create new comment
 * 
 * @param int  $usersid user's id
 * @param int $recipeid recipe's id
 * @param string $text comment
 * @param int $rating rating from 1 to 5, if user didn't rate recipe, rating will be 0
 * @param PDO $db database connection
 * 
 * @return bool
 * 
 */
function createComment($userid, $recipeid, $text, $rating, $db){
    $sql = 'INSERT INTO comments (user_id, recipe_id, text, rating) VALUES(?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([$userid, $recipeid, $text, $rating]);

    return $result;
}

/**
 * Retrive comments to display on recipe's page
 * 
 * @param int $recipeid recipe's id
 * @param PDO $db database connection
 * 
 * @return array 
 */
function getTheComments($recipeid, $db){
    $sql = 'SELECT users.full_name AS name, comments.text As text, comments.date As date, comments.rating As rating FROM comments RIGHT JOIN users ON (users.user_id = comments.user_id) WHERE comments.recipe_id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$recipeid]);

    return $stmt->fetchall();
}


/**
 * Sort comments by rating from low to high
 * 
 * @param int $recipeid recipe's id
 * @param PDO $db database connection
 * 
 * @return array 
 */
function getTheCommentsSortByRatingLowToHigh($recipeid, $db){
    $sql = 'SELECT users.full_name AS name, comments.text As text, comments.date As date, comments.rating As rating FROM comments RIGHT JOIN users ON (users.user_id = comments.user_id) WHERE comments.recipe_id = ? ORDER BY comments.rating ASC';
    $stmt = $db->prepare($sql);
    $stmt->execute([$recipeid]);

    return $stmt->fetchall();
}


/**
 * Sort comments by rating from high to low
 * 
 * @param int $recipeid recipe's id
 * @param PDO $db database connection
 * 
 * @return array 
 */
function getTheCommentsSortByRatingHighToLow($recipeid, $db){
    $sql = 'SELECT users.full_name AS name, comments.text As text, comments.date As date, comments.rating As rating FROM comments RIGHT JOIN users ON (users.user_id = comments.user_id) WHERE comments.recipe_id = ? ORDER BY comments.rating DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute([$recipeid]);

    return $stmt->fetchall();
}

/**
 * Get amount of comments to display near recipe's thumbnail on index page
 * 
 * @param int $recipeid recipe's id
 * @param PDO $db database connection
 * 
 * @return int
 */
function getTheAmountOfComments($recipeid, $db) {
    $sql = 'SELECT count(recipe_id) As quantity from comments WHERE recipe_id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$recipeid]);
    $result = $stmt->fetchColumn();

    return $result;
}

/**
 * Get information to calculate recipe's rating
 * 
 * @param int $recipeid recipe's id
 * @param PDO $db database connection
 * 
 * @return array
 */
function calculateRating($recipeid, $db) {
    $sql = "SELECT count(recipe_id) As ratedQuantity, sum(rating) as ratingSum from comments WHERE recipe_id = $recipeid AND rating != 0 ORDER BY recipe_id";
    $stmt = $db->query($sql);
    $result = $stmt->fetch();

    return $result;
}

?>