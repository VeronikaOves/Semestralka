<?php
    session_start();
    include './includes/dbh.inc.php';
    include_once './includes/functions.inc.php';

    // getting favorite recipes 
    $sql = 'SELECT recipes.recipe_id, recipes.name, recipes.description, recipes.img FROM favorites LEFT JOIN recipes ON favorites.recipe_id = recipes.recipe_id WHERE favorites.user_id = ?';
    $stm = $db->prepare($sql);
    $stm->execute([$_SESSION['uid']]);
    $favRecipes = $stm->fetchAll();

?>

<?php include_once 'header.php'; ?>
    <article class="mainWindow">
        <h1>Bookmarks</h1>
        <div class="recipes">
            <?php if(!$user): ?>
                <div class="warning">
                    <div><i class="fas fa-exclamation-triangle"></i></div>
                    <div><p>If you want to bookmark recipes you need to <a href="/signup.php"> registrate</a></p></div>
                </div>
            <?php elseif($favRecipes): ?>
            <?php foreach($favRecipes as $favRecipe): ?>
            <article class="recipe">
                <a href="<?= '/recipe.php?recipe_id=' . $favRecipe['recipe_id'] ;?>"><h3><?= $favRecipe['name']; ?></h3></a>
                <?php if ($favRecipe['img']): ?>
                <img src="<?= $favRecipe['img']; ?>" alt="<?= $favRecipe['name']; ?>">
                <?php endif; ?>
            </article>
            <?php endforeach ?>
            <?php else: ?>
                <div><p>Your bookmark list is empty!</p></div>
            <?php endif ?>
        </div>
    </article>

<?php include_once 'footer.php'; ?>