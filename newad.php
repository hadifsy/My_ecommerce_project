<?php
    $pageTitle = 'create new items';
    session_start();
    include 'init.php';    
    if(isset($_SESSION['user'])){

       if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $arrayErr = array();

            $name     = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc     = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
            $price    = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country  = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags     = filter_var($_POST['category'], FILTER_SANITIZE_STRING);

            if(strlen($name) < 3 || strlen($name) > 15) {
                $arrayErr[] = 'name must be between 3 & 15 letter';
            } 
            
            if(strlen($desc) < 5) {
                $arrayErr[] = 'description must be large then 5 letter';
            }
            
            if(empty($price)) {
                $arrayErr[] = 'sorry price is empty';
            }

            if(strlen($country) < 3) {
                $arrayErr[] = 'country must be large then 2 letter';
            }

            if(empty($status)) {
                $arrayErr[] = 'must be choose status';
            }

            if(empty($category)) {
                $arrayErr[] = 'must be choose category';
            }

            if(empty($arrayErr)) {
                $stmt = $con -> prepare("INSERT INTO
                                                    items(name, description, price, country_made, add_date, status, cat_id, mem_id, tags)
                                         VALUES
                                                   (?, ?, ?, ?, now(), ?, ?, ?, ?)");

                $stmt->execute(array($name, $desc, $price, $country, $status, $category, $_SESSION['id'], $tags) );
                $msg = '<div class="alert alert-success">item has been added</div>';
            }

        }

             


?>

<h1 class="text-center"><?php echo $pageTitle; ?></h1>
<div class="new-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php echo $pageTitle; ?>
            </div>
            <div class="panel-body">
                <div class="row">
                <div class="col-md-8">
                <form class="form-horizontal main-form" action="<?php $_SERVER['PHP_SELF']; ?>" method='POST'>
                    <!-- start name item -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">Name: </label>
                        <div class="col-sm-10 col-md-9">
                            <input minlength="4" class="form-control live" data-class=".live-name" type="name" name="name" placeholder="name of item" required>
                        </div>
                    </div>
                    <!-- start description item -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">description: </label>
                        <div class="col-sm-10 col-md-9">
                            <input minlength="5" class="form-control live" data-class=".live-desc" type="text" name="desc" placeholder="description of item" required>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">price: </label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control live" data-class=".live-price" type="text" name="price" placeholder="price of item" required>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">country: </label>
                        <div class="col-sm-10 col-md-9">
                            <input class="form-control" type="text" name="country" placeholder="country of item" required>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">status: </label>
                        <div class="col-sm-10 col-md-9">
                            <select name="status" required>
                                <option value="">...</option>
                                <option value="1">new</option>
                                <option value="2">like new</option>
                                <option value="3">used</option>
                                <option value="4">very old</option>
                            </select>   
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">category: </label>
                        <div class="col-sm-10 col-md-9">
                            <select name="category" required>
                                <option value="">...</option>
                                <?php 
                                    $cats = getAllFrom('*', 'categories', '', '', 'id', 'asc');
                                    foreach ($cats as $cat) {
                                        echo '<option value="'. $cat['id'] .'">' . $cat['name'] . '</option>';
                                    }
                                ?>
                            </select>   
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-3 control-label">tags: </label>
                        <div class="col-sm-10 col-md-9">
                            <input type="text" class="form-control" name="tags" placeholder="perate with coma (,)">
                        </div>
                    </div>
                    
                    
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-3 col-sm-9">
                            <input type="submit" class="btn btn-primary" value="add">
                        </div>
                    </div>

                </form>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail item-box live">
                        <span class="price-tag">
                            $<span class="live-price">0</span>
                        </span>
                        <img class="img-responsive" src="img.png" alt="">
                        <div class="caption">
                            <h3 class="live-name">test</h3>
                            <p class="live-desc">test</p>
                        </div>
                    </div>
                </div> 
            </div>

             <div class="the-error">
            <?php
                if(!empty($arrayErr)) {
                    foreach($arrayErr as $error) {
                        echo '<div class="alert alert-danger">' . $error . '</div>';
                    }
                } else {
                    if(isset($msg))  {
                        echo $msg;
                    }
                }
            ?>
            </div>

        </div>
        </div>
     </div>
</div>

       

<?php 
} else {
    header('location: login.php');
    exit();
}

include $tpl . 'footer.php'; ?>