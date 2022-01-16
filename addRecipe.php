<?php
    session_start();
    include './includes/dbh.inc.php';
    include './includes/functions.inc.php';

    ## Getting Ingredients
    $sql = 'SELECT * FROM ingredients';
    $stm = $db->query($sql);
    $ingredients = $stm->fetchAll();

    $sql ='SELECT ingredient_id FROM ingredients';
    $stm = $db->query($sql);
    $ingredientsId = $stm->fetchAll();
    $idArray = [];

    foreach($ingredientsId as $array):
        array_push($idArray, $array['ingredient_id']);
    endforeach;



    // post new recipe
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['addRecipe-form'])) {
            $form_data = [];
            $form_data['recipeName'] = isset($_POST['recipeName']) ? htmlspecialchars(trim($_POST['recipeName'])) : '';

            if(isset($_POST['ingredients'])){
                $form_data['ingredients'] = [];
                foreach($_POST['ingredients'] as $value):
                    array_push($form_data['ingredients'], htmlspecialchars($value));
                endforeach;
            }
           
            $form_data['recipeDescription'] = isset($_POST['recipeDescription']) ? htmlspecialchars(trim($_POST['recipeDescription'])) : '';
            $form_data['validation'] = [];

            $form_data['validation']['recipeName'] = (function($form_data) {
                if (strlen($form_data['recipeName']) > 250) {
                    return 'Name should be no more then 250 symbols';
                }
            })($form_data);

            $form_data['validation']['recipeName'] = (function($form_data) {
                if (strlen($form_data['recipeName']) < 2) {
                    return 'Name should be at least 2 symbols';
                }
            })($form_data);

            $form_data['validation']['recipeDescription'] = (function($form_data) {
                if (strlen($form_data['recipeDescription']) > 2000) {
                    return 'Description should be no more then 2000 symbols';
                }
            })($form_data);

            $form_data['validation']['recipeDescription'] = (function($form_data) {
                if (strlen($form_data['recipeDescription']) < 50) {
                    return 'Description should be more then 50 symbols';
                }
            })($form_data);

            if (!empty($form_data['ingredients'])){
            $form_data['validation']['ingredients'] = (function($form_data, $idArray) {
                print_r($form_data['ingredients']);
                foreach  ($form_data['ingredients'] as $ingredient):
                    if (!in_array($ingredient, $idArray)){
                        return "Invalid ingredient";
                    }
                endforeach;
            })($form_data, $idArray);
            }

            if(empty($_FILES['img']) || $_FILES['img']['error'] == 4) {
                $form_data['img'] = '';
            }
            else {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["img"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                $form_data['validation']['img'] = (function($imageFileType) {
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
                        return 'Invalid file type'; 
                    } else if($_FILES['img']['size'] > 10485760) {
                        return "Sorry, your file is too large.";
                    }
                })($imageFileType);                
        
                $form_data['validation'] = array_filter($form_data['validation'], function($value) {
                    return !empty($value);
                });

                if(empty($form_data['validation'])) {
                    $form_data['img'] = $target_file;
                    move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
                }
            }



            $form_data['validation'] = array_filter($form_data['validation'], function($value) {
                return !empty($value);
            });

          
            if (empty($form_data['validation'])) {
                $form_data['error'] = (function($form_data, $db) {
                    $id=postNewRecipe($form_data['recipeName'], $form_data['ingredients'], $form_data['recipeDescription'], $form_data['img'], $db);
                
                    redirect("/recipe.php?recipe_id=$id");
                })($form_data, $db);
            }

            $_SESSION['form_data'] = $form_data;
            redirect('addRecipe.php');

    }
}

?>
<?php include_once 'header.php'; 
?>

<section class="signup-form">
    <div class="container addRecipeContainer">
    <h1 class="header">Add new recipe</h1>
        <form class="form" id="addRecipe" action="addRecipe.php" method="POST" enctype="multipart/form-data">
        <?php if (isset($_SESSION['form_data']['error'])): ?>
            <p class="notHiddenError"><?= $_SESSION['form_data']['error']; ?></p>
            <?php endif; ?>
            <div class="form-control">
                <label class ="isRequiered" for="recipeName" > Recipe name</label>
                <input type="text" name="recipeName" id="recipeName" value="<?= isset($_SESSION['form_data']['recipeName']) ? $_SESSION['form_data']['recipeName'] : '' ?>">
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
                <?php if (isset($_SESSION['form_data']['validation']['recipeName'])): ?>
                <p class="notHiddenError"><?= $_SESSION['form_data']['validation']['recipeName']; ?></p>
                <?php endif; ?>
            </div>
            <div class="list-group">
                <h5>Select ingredients</h5>
                <?php foreach($ingredients as $ingredient): ?>
                        <div class="list-group-item checkbox">
                            <input id="<?= $ingredient['name']; ?>" type="checkbox" name="ingredients[]" value="<?= $ingredient['ingredient_id']; ?>"
                            <?php  if (isset($_SESSION['form_data']['ingredients'])){
                                        if(in_array($ingredient['ingredient_id'], $_SESSION['form_data']['ingredients'])){
                                            echo "checked='checked'"; }}?>>
                            <label for="<?= $ingredient['name']; ?>"> <?= $ingredient['name']; ?></label><br>
                        </div>
                <?php endforeach; ?>
                <?php if (isset($_SESSION['form_data']['validation']['ingredients'])): ?>
                <p class="notHiddenError"><?= $_SESSION['form_data']['validation']['ingredients']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-control recipedesc">
                <label for="recipeDescriptionNewRecipe" class ="isRequiered"> Recipe description</label>
                <textarea name="recipeDescription" id="recipeDescriptionNewRecipe"><?= isset($_SESSION['form_data']['recipeDescription']) ? $_SESSION['form_data']['recipeDescription'] : '' ?></textarea>
                <i class="fas fa-check-circle"></i>
                <i class ="fas fa-exclamation-circle"></i>
                <small>Error message</small>
                <?php if (isset($_SESSION['form_data']['validation']['recipeDescription'])): ?>
                <p class="notHiddenError"><?= $_SESSION['form_data']['validation']['recipeDescription']; ?></p>
                <?php endif; ?>
            </div>
            <div class="form-control">
                <label for="img">Photo of the dish</label>
                <input type="file" id="img" name="img" accept="image/png, image/jpeg">
                <?php if (isset($_SESSION['form_data']['validation']['img'])): ?>
                <p class="notHiddenError"><?= $_SESSION['form_data']['validation']['img']; ?></p>
                <?php endif; ?>
            </div>
            <input type="text" name="addRecipe-form" hidden>
            <button type="submit">Post recipe!</button>
        </form>
    </div>
    </section>

<?php unset($_SESSION['form_data']);?>
<?php include_once 'footer.php'; ?>