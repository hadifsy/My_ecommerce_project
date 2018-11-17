<?php
    $pageTitle = 'profile';
    session_start();
    include 'init.php';
    if(isset($_SESSION['user'])){
        $getUser = $con->prepare("SELECT * FROM users WHERE userName = ?");
        $getUser->execute(array($sessionUser));
        $info = $getUser->fetch();
        //test
?>

<h1 class="text-center">My Profile</h1>
<div class="info block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                my Information
            </div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock fa-fw"></i>
                        <span>Login Name:</span> <?php echo $info['userName']; ?>
                    </li>
                    <li>
                        <i class="fa fa-envelope-o fa-fw"></i>
                        <span>email:</span><?php echo $info['email']; ?>
                    </li>
                    <li>
                        <i class="fa fa-users fa-fw"></i>
                        <span>full Name:</span> <?php echo $info['fullName'];?>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Registry date:</span><?php echo $info['date'];?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>favourite category:</span>
                    </li>
                </ul>
<!--                <a href="edit_profile.php" class="btn btn-default">edit info</a>-->
            </div>
        </div>
    </div>
</div>
<!--test-->
<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                My Ads
            </div>
            <div class="panel-body">
                <?php $items = getAllFrom('*', 'items', "where mem_id={$info['userID']}", '', 'itemID', '');

                if(!empty($items)) { 
                   echo '<div class="row">';
                       foreach($items as $item) {?>
                        <div class="col-sm-6 col-md-3">
                            <div class="thumbnail item-box">
                                <?php if($item['aprove'] == 0) {echo '<span class="aprove-sataus">whit aprove from admin</span>';}?>
                                <span class="price-tag">$<?php echo $item['price']; ?></span>
                                <img class="img-responsive" src="img.png" alt="">
                                <div class="caption">
                                    <h3>
                                        <a href="items.php?id=<?php echo $item['itemID']; ?>">
                                            <?php echo $item['name']; ?>
                                        </a>
                                    </h3>
                                    <p><?php echo $item['description'];?></p>
                                    <div class="date"><?php echo $item['add_date'];?></div>
                                </div>
                            </div>
                        </div>
          <?php }

          echo '</div>';
               } else {echo 'there\'s no ads to show, create <a href="newad.php">new ad</a>';} ?>

            </div>
        </div>
    </div>
</div>

<div class="my-comment block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                latest comments
            </div>
            <div class="panel-body">
                <?php
                    $comments = getAllFrom('comments.*', 'comments', "where userid={$info['userID']}", '', 'c_id','');

                    if(!empty($comments)) {
                         foreach($comments as $comment) {
                            echo '<div class="row">';
                                echo '<p class="col-sm-8">'.$comment['comment'] . '</p>';
                                if($comment['status'] == 0) {echo '<span class="col-sm-4 c_aprove text-center"> need aprove from admin</span>';}
                             echo '</div>';
                        }
                        
                    } else {
                        echo 'there\'s no comments to show';
                    }
                   

                ?>
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