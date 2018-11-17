<?php

session_start();
$pageTitle = 'Show Items';
include 'init.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

$check = check('itemID', 'items', "where itemID=$id", '');

if($check > 0) {

	$getItems = $con -> prepare("SELECT items.*, users.userName, categories.name as cat_name
									FROM items 
									INNER JOIN users ON users.userID = items.mem_id
									INNER JOIN categories ON categories.id = items.cat_id
									WHERE itemID=? AND aprove=1");
	$getItems->execute(array($id));
	$item = $getItems->fetch();

	if($getItems->rowCount() > 0) { ?>


		<h1 class="text-center"><?php echo $pageTitle; ?></h1>
        <div class="container">
        	<div class="row">
        		<div class="col-md-3">
        			<img src="img.png" class="img-thumbnail center-block img-responsive" alt="">
        		</div>
        		<div class="col-md-9 item-info">
        			<h2><?php echo $item['name']; ?></h2>
        			<p><?php echo $item['description']; ?></p>
        			<ul class="list-unstyled">
        				<li>
        					<i class="fa fa-calendar fa-fw"></i>
        					<span>added date: </span><?php echo $item['add_date']; ?>
        				</li>
	        			<li>
	        				<i class="fa fa-money fa-fw"></i>
	        				<span>price: </span>$<?php echo $item['price']; ?>
	        			</li>
	        			<li>
	        				<i class="fa fa-building fa-fw"></i>
	        				<span>made in: </span><?php echo $item['country_made']; ?>
	        			</li>
	        			<li>
	        				<i class="fa fa-tag fa-fw"></i>
	        				<span>user</span> : <?php echo $item['userName']; ?>
	        			</li>
	        			<li>
	        				<i class="fa fa-user fa-fw"></i>
	        				<span>category</span> :
	        				<a href="categories.php?id=<?php echo $item['cat_id']; ?>">
	        				<?php echo $item['cat_name']; ?></a>
	        			</li>
                        <li class="tags-item">
	        				<i class="fa fa-user fa-fw"></i>
	        				<span>tags</span> :
                            <?php 
                                   $tags = explode(',' ,$item['tags']);
                                   foreach($tags as $tag){
                                       $tag = str_replace(' ', '', $tag);
                                       $tag = strtolower($tag);
                                       if(!empty($tag)) {
                             ?>
                                        <a href="tags.php?name=<?php echo $tag; ?>">
                                        <?php echo $tag ?></a>
                                        <?php }}?>
	        			</li>
        			</ul>
        		</div>
        	</div>
            
        	<hr class="custom-hr">

        	<?php if(isset($_SESSION['user'])) { ?>
        	<div class="row">
	        	<div class="col-md-offset-3">
	        			<div class="add-comment">
	        			<h3>add comment</h3>
	        			<form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $item['itemID'] ?>" method="POST">
	        				<textarea minlength="5" name="comment" required></textarea>
	        				<input type="submit" class="btn btn-primary" name="" value="add comment">
	        			</form>
	        			<?php 
	        			if($_SERVER['REQUEST_METHOD'] == 'POST') {
	        				$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $userid  = $_SESSION['id'];
                            $itemid  = $item['itemID'];
                            
                            if(! empty($comment)) {
                            
                                $stmt = $con -> prepare("INSERT INTO comments(comment, userid, itemid, c_date)
                                                        VALUES(?, ?, ?, NOW())");
                                
                                $stmt -> execute(array($comment, $userid, $itemid));
                                
                                if($stmt) {
                                    echo '<div class="container">';
                                        echo '<div class="alert alert-success">success add comment but need aprove from admin</div>';
                                    echo '</div>';
                                }
                                
                            } else {
                                echo '<div class="container">';
                                    echo '<div class="alert alert-danger">you must type commnt</div>';
                                echo '</div>';
                            }
                            
                            
	        			}
	        			?>
	        		</div>
	        	</div>
	        </div>
	        <?php } else {

	        	echo '<a href="login.php">login </a>or <a href="login.php">rigstery</a> to add comment';

	        } ?>

	        <hr class="custom-hr">
            <?php
                $stmt = $con -> prepare("
                SELECT comments.*, users.userName 
                FROM   comments 
                INNER JOIN users 
                ON users.userID = comments.userid
                WHERE status=1 And itemid=?");
                                   
                $stmt -> execute(array($item['itemID']));
                $comments = $stmt->fetchAll();
                                   
                foreach($comments as $comment) {
                    echo '<div class="comment-box">';
                        echo '<div class="row">';
                            echo '<div class="col-sm-2 text-center">';
                                echo '<img class="img-circle img-thumbnail img-responsive center-block" src="img.png">';
                                echo $comment['userName'];
                            echo '</div>';
                            echo '<div class="col-sm-10">';
                                echo '<p class="lead">' . $comment['comment'] . '</p>';
                            echo '</div>';
                        echo '</div>';
                        echo '<hr class="custom-hr">';
                    echo '</div>';
                }
                ?>


<?php	} else {
            echo '<div class="container">';
                echo '<div class="alert alert-danger">this item wating aprove from admin</div>';
            echo '</div>';
}

} else {
	echo '<div class="container">';
		echo '<div class="alert alert-danger">this id not found</div>';
	echo '</div>';
}

include $tpl . 'footer.php';

?>