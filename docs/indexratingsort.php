<?php
    session_start();

    include './includes/config.inc.php';
    include './includes/dbh.inc.php';
    include './includes/functions.inc.php';

    ## Recipes DB
    
    ## Default query vars
    $query_vars = [
        'per_page' => 4,
        'page' => 1
    ];

    ## Pagination
    if (isset($_GET['page'])) {
        $filtered_pages = filter_var($_GET['page'], FILTER_VALIDATE_INT);

        if ($filtered_pages) {
            $query_vars['page'] = $filtered_pages;
        }
    }

    ## search by ingredients
    if (isset($_GET['ingredients'])) {
        $prepared_string = '';
        $ingredients = [];

        foreach ($_GET['ingredients'] as $key => $value) {
            $new_key = ":id$key";
            $prepared_string .= ", $new_key";
            $ingredients[$new_key] = $value;
        }
        $ingredients_query = 'RIGHT JOIN ingredients_list ';
        $ingredients_query .= 'ON (recipes.recipe_id = ingredients_list.recipe_id) ';
        $ingredients_query .= "WHERE ingredients_list.ingredient_id IN (0$prepared_string)";
    } else {
        $ingredients_query = '';
    }

    $prepared_query_vars = [
        'limit' => $query_vars['per_page'],
        'offset' => ($query_vars['page'] - 1) * $query_vars['per_page'],
    ];

    if (isset($_GET['ingredients'])) {
        $prepared_query_vars = array_merge($prepared_query_vars, $ingredients);
    }

    ## sql constructing
    if (isset($_GET['sorting'])){
        $sortType = $_GET['sorting'];
        if ($sortType == 'nothing'){
            $sql = 'SELECT * FROM recipes ';
            $sql .= $ingredients_query;
            $sql .= 'LIMIT :limit OFFSET :offset';
        }
        elseif ($sortType == 'number of comments'){
            $sql = 'SELECT count(comments.recipe_id) As quantity, recipes.name AS name, ';
            $sql .= 'recipes.img as img, recipes.recipe_id AS recipe_id from comments ';
            $sql .= 'RIGHT JOIN recipes ON (comments.recipe_id = recipes.recipe_id) ';
            $sql .= $ingredients_query;
            $sql .= ' GROUP BY recipe_id ';
            $sql .= 'ORDER BY quantity DESC ';
            $sql .= 'LIMIT :limit OFFSET :offset';
        } 
        elseif ($sortType == 'rating low to high'){
            $sql = 'SELECT sum(comments.rating) As ratingSum, recipes.name AS name, count(comments.recipe_id) as ratingCount, ';
            $sql .= 'recipes.img as img, recipes.recipe_id AS recipe_id from comments ';
            $sql .= 'RIGHT JOIN recipes ON (comments.recipe_id = recipes.recipe_id) ';
            $sql .= $ingredients_query;
            $sql .= ' GROUP BY recipe_id ';
            $sql .= 'ORDER BY (ratingSum/ratingCount) ASC ';
            $sql .= 'LIMIT :limit OFFSET :offset';
        }

        elseif ($sortType == 'rating high to low'){
            $sql = 'SELECT sum(comments.rating) As ratingSum, recipes.name AS name, count(comments.recipe_id) as ratingCount, ';
            $sql .= 'recipes.img as img, recipes.recipe_id AS recipe_id from comments ';
            $sql .= 'RIGHT JOIN recipes ON (comments.recipe_id = recipes.recipe_id) ';
            $sql .= $ingredients_query;
            $sql .= ' GROUP BY recipe_id ';
            $sql .= 'ORDER BY (ratingSum/ratingCount) DESC ';
            $sql .= 'LIMIT :limit OFFSET :offset';
        }
        else {
            $sql = 'SELECT * FROM recipes ';
            $sql .= $ingredients_query;
            $sql .= 'LIMIT :limit OFFSET :offset';
            echo("invalid sorting!");
        }
    }
    else {
        $sql = 'SELECT * FROM recipes ';
        $sql .= $ingredients_query;
        $sql .= 'LIMIT :limit OFFSET :offset';

    }


    $stm = $db->prepare($sql);
    $stm->execute($prepared_query_vars);

    $recipes = $stm->fetchAll();
    
    ## Getting Recipes Quantity

    if(isset($ingredients)) {
        $ingredients_query = "WHERE ingredients_list.ingredient_id in (0$prepared_string)";
    } else {
        $ingredients_query = '';
    }

    
    $sql = 'SELECT count(recipes.recipe_id) AS quantity ';
    $sql .= 'FROM recipes LEFT JOIN ingredients_list ON ingredients_list.recipe_id = recipes.recipe_id ';
    $sql .= $ingredients_query;

    $stm = $db->prepare($sql);
    
    if (isset($ingredients)) {
        $stm->execute($ingredients);
    } else {
        $stm->execute();
    }

    $recipes_quantity = $stm->fetchColumn();
    $max_pages = ceil($recipes_quantity / $query_vars['per_page']);

    

    $pagination = [];

    if ($max_pages > 1) {
        if ($query_vars['page'] != 1) {
            $pagination['prev'] = [
                'href' => '/index.php?page=' . ($query_vars['page'] - 1),
                'name' => 'Prev'
            ];
        }

        if ($query_vars['page'] != $max_pages) {
            $pagination['next'] = [
                'href' => '/index.php?page=' . ($query_vars['page'] + 1),
                'name' => 'Next'
            ];
        }

        for ($i = 1; $i < $max_pages; $i++) {
            $pagination['pages'][] = [
                'href' => '/index.php?page=' . $i,
                'name' => $i,
                'active' => $query_vars['page'] == $i ? true : false
            ];
        }
    }
    

    ## Getting Ingredients
    $sql = 'SELECT * FROM ingredients';
    $stm = $db->query($sql);
    $ingredients = $stm->fetchAll();
?>
<?php include_once 'header.php'; ?>
<div id="ingredients">
    <div class="ingredients">
        <form class="form" method="GET" action="/index.php">
            <div class="list-group">
                <?php foreach($ingredients as $ingredient): ?>
                        <div class="list-group-item checkbox">
                            <input id="<?= $ingredient['name']; ?>" type="checkbox" name="ingredients[]" value="<?= $ingredient['ingredient_id']; ?>">
                            <label for="<?= $ingredient['name']; ?>"> <?= $ingredient['name']; ?></label><br>
                        </div>
                <?php endforeach; ?>
            </div>
            <div class="sortRecipes">
            <label for="sorting">Sort recipes by:</label>
            <select name="sorting" id="sortingRecipes">
                <option value="nothing">nothing</option>
                <option value="number of comments">number of comments</option>
            </select>
            </div>
            <button type="submit">Search</button>
        </form>
    </div>
</div>
<main class="mainWindow">
    <?php if (!$recipes): ?>
        <div class="emptySearchResult"><p>Oops, we didn't find anything! :(</p></div>
    <?php endif; ?>
    <div class="recipes">
        <?php foreach($recipes as $recipe): ?>
        <article class="recipe">
            <a href="<?= '/recipe.php?recipe_id=' . $recipe['recipe_id'] ;?>"><h3><?= $recipe['name']; ?></h3></a>
            <?php if ($recipe['img']): ?>
            <img src="<?= $recipe['img']; ?>" alt="<?= $recipe['name']; ?>">
            <div><?= getTheAmountOfComments($recipe['recipe_id'], $db);?> comments</div>
            <div>
                <?php $ratingCalc = calculateRating($recipe['recipe_id'], $db);
                if($ratingCalc['ratedQuantity'] == 0):?>
                rating: 0/5
                <?php else: ?>
                rating: <?= round(($ratingCalc['ratingSum'] / $ratingCalc['ratedQuantity']),1) ?>/5
                <?php endif ?>
            </div>
            <?php endif; ?>
        </article>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($pagination)): ?>
    <nav class="pagination">
        <ul>
            <?php if (isset($pagination['prev'])): ?>
            <li><a href="<?= $pagination['prev']['href']; ?>"><?= $pagination['prev']['name']; ?></a></li>
            <?php endif; ?>
            <?php foreach($pagination['pages'] as $item): ?>
            <?php if ($item['active']): ?>
            <li><span><?= $item['name']; ?></span></li>
            <?php else: ?>
            <li><a href="<?= $item['href']; ?>"><?= $item['name']; ?></a></li>
            <?php endif; endforeach; ?>
            <?php if (isset($pagination['next'])): ?>
            <li><a href="<?= $pagination['next']['href']; ?>"><?= $pagination['next']['name']; ?></a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
    <?php if (!$user): ?>
        <div class="addlink"><a href="/login.php">You need to log in to post new recipe!</a></div>
    <?php else: ?>
        <div class="addlink"><a href="/addRecipe.php">Post new recipe!</a></div>
    <?php endif; ?>
</main>
<?php include_once 'footer.php'; ?>