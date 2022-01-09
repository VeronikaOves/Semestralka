<?php
    session_start();
    include './includes/dbh.inc.php';
    include './includes/functions.inc.php';
    

    if ($_SERVER["REQUEST_METHOD"] == 'GET') {
        ## query vars
        if (!isset($_GET['recipe_id'])) {
            echo "Doesn't exist";
            die();
        }

        ## filtering vars
        $filtered_id = filter_var($_GET['recipe_id'], FILTER_VALIDATE_INT);

        if (!$filtered_id) {
            echo "Doesn't exist";
            die();
        }

        $sql = 'SELECT * from recipes WHERE recipe_id = ?';
        $stm = $db->prepare($sql);
        $stm->execute([$filtered_id]);

        $recipe = $stm->fetch();

        if (!$recipe) echo "Doesn't exist";

        ## get the comments
        $sorting = $_GET['sorting'];
        
        if ($sorting == 'nothing'){
            $comments = getTheComments($recipe['recipe_id'], $db);
        }
        elseif ($sorting == 'rating low to high'){
            $comments = getTheCommentsSortByRatingLowToHigh($recipe['recipe_id'], $db);
        }
        elseif ($sorting == 'rating high to low'){
            $comments = getTheCommentsSortByRatingHighToLow($recipe['recipe_id'], $db);
        }
        else{
            $comments = getTheComments($recipe['recipe_id'], $db);
        }
            

        ## check if this recipe is user's favoriet
        $isFavorite = checkIfRecipeIsUsersFavorite($_SESSION['uid'], $recipe['recipe_id'], $db);
    
        ## Session
        if (isset($_SESSION['form_data'])) {
            $form_data = $_SESSION['form_data'];
            unset($_SESSION['form_data']);
        }

    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['commentForm'])){
            $form_data = [];
            $recipeId = $_GET['recipe_id'];
            $form_data['comment'] = isset($_POST['comment']) ? htmlspecialchars(trim($_POST['comment'])) : '';
            $form_data['validation'] = [];
            if (isset($_POST['rating'])) {
                $form_data['rating'] = $_POST['rating'];
            }
            else {
                $form_data['rating'] = 0;
            }

            $form_data['validation']['comment'] = (function($form_data) {
                if (strlen($form_data['comment']) == 0) {
                    return 'Review is required';
                }

                if (strlen($form_data['comment']) < 2 || strlen($form_data['comment']) > 500) {
                    return 'Comment must be at least 2 symbols but no more then 500';
                }

            })($form_data);

            $form_data['validation']['rating'] = (function($form_data) {
                $arr = [0,1,2,3,4,5];
                if (!in_array($form_data['rating'], $arr)) {
                    return 'Raiting is not valid';
                }
            })($form_data);

            // Delete epmpty errors
            $form_data['validation'] = array_filter($form_data['validation'], function($value) {
                return !empty($value);
            });

            // Create comment
            if (empty($form_data['validation'])) {
                $form_data['validation'] = (function($form_data, $db, $recipeId) {
                    if (!createComment($_SESSION['uid'], $recipeId, $form_data['comment'], $form_data['rating'], $db)) {
                        return 'Something went wrong!';}
                redirect(getCurrentUrl());
            })($form_data, $db, $recipeId);

            }
            $_SESSION['form_data'] = $form_data;
            redirect(getCurrentUrl());

    }

    }
    
?>

<?php include_once 'header.php'; ?>
<article class="mainWindowOneRecipe">
    <div id="recipeButtons">
        <div id="printButton"><p title="Print" 
        onclick="window.print();"><i class="fas fa-print"></i></p></div>
        <?php if (!$user): ?> 
            <a href="/favs.php"><div id="favoriteButton"><i class="far fa-heart"></i></div><a>
        <?php else: ?>
        <div id="favoriteButton" class="<?= $isFavorite ? 'is-favorite' : ''; ?>">
            <div id="favoriteButtonAdd"><a title="Add to favorites" style="cursor:pointer;" onclick="addToFavs(<?=$_SESSION['uid']?>, <?=$recipe['recipe_id']?>)"><i class="far fa-heart"></i></a></div> 
            <div id="favoriteButtonDelete"><a title="Delete from favorites" style="cursor:pointer;" onclick="deleteFromFavs(<?=$_SESSION['uid']?>, <?=$recipe['recipe_id']?>)"><i class="fas fa-heart"></i></a></div>
        </div> 
        <?php endif ?>
    </div>
    <div id="recipeInfomation">
        <div class="recipeHeading"> <h1><?= $recipe['name']; ?></h1></div>
        <div id="recipeImage"><img src="<?= $recipe['img']; ?>" alt="<?= $recipe['name']; ?>"></div>
        <div class="recipeHeading"> <h1>Description</h1></div>
        <div id="recipeDescription"><p><?= $recipe['description']; ?></p></div>
    </div>
    <section id="comments">
    <div class="recipeHeading"><h2>Write a comment</h2></div>
    <?php if ($user): ?>
        <form class="add-review-form" action="<?= getCurrentUrl(); ?>" method="post" id="add-review">
        <div id="commentSection"> 
            <label for="comment">Write your comment here!</label>
                <textarea name="comment" id="comment"><?= isset($form_data['comment']) ? $form_data['comment'] : ''; ?></textarea>
                <?php if (isset($form_data['validation']['comment'])): ?>
                <p><?= $form_data['validation']['comment']; ?></p>
                <?php endif; ?>
                <div class ="hidden"><small>Error message</small></div>
        </div>
            <div class="rating">
                    <div>
                        <p class="ratingHeading">Rate the recipe</p>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" value="<?= $i; ?>" name="rating" id="<?= $i; ?>">
                            <label for="<?= $i; ?>"> <?= $i; ?></label>
                        <?php endfor; ?>
                    </div>
                    <?php if (isset($form_data['validation']['rating'])): ?>
                    <p><?= $form_data['validation']['rating']; ?></p>
                    <?php endif; ?>
            </div>
            
            <div class="photo">
                <label for="photo" class="photoText">Photo of your dish</label>
                <input type="file" id="photo" name="photo" accept="image/png, image/jpeg">
            </div>
            <input type="hidden" name="commentForm">
            <button type="submit">Submit </button>
        </form>
    <?php else: ?>
        <div>
            <div class="recipeHeading"><h3>You need to <a href="/signup.php">registrate</a> to write a comment!</h3></div>
        </div>
    <?php endif ?>
    <div class="comments">
        <div class="commentsHeading"><h2>Comments</h2></div>
        <?php if (!$comments): ?>
            <div>There is no comments yet! Yout could be the first</div>
        <?php endif; ?>
        <form action="recipe.php" method="GET" id="sort">
            <input type="hidden" value="<?= $_GET['recipe_id']?>" name="recipe_id">
            <label for="sorting">Sort comments by:</label>
            <select name="sorting" id="sorting">
                <option value="nothing">nothing</option>
                <option value="rating low to high">rating low to high</option>
                <option value="rating high to low">rating high to low</option>
            </select>
            <small class="hidden">Error message</small>
            <button type="submit">Sort</button>
         </form>
        <div class="commentsList">
        <?php foreach($comments as $comment): ?>
        <div class="wrap">
            <div><?= $comment['name']?></div>
            <div><?= $comment['date']?></div>
            <div>
                <?= str_repeat('<i class="fa fa-star" aria-hidden="true"></i>', $comment['rating'])?>
            </div>
            <div><?= $comment['text']?></div>
        </div>
        <?php endforeach ?>
        </div>
    </div>
</section>
</article>

<?php include_once 'footer.php'; ?>