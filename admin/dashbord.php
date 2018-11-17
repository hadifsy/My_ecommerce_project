<?php

    session_start();

    if(isset($_SESSION['username'])){
        
        $pageTitle = 'dashBord';
        
        include 'init.php';
        
        $latestUsers = 4;   
        $theLatest = getLatest("*", 'users', 'userID', $latestUsers);
        
        $latestitem = 4;
        $thelatestitem = getLatest("*", 'items', 'itemID', $latestitem);
        
        $latestcomment =4;
        
        /* start dashbord page */
        ?>
        
        <div class="container home-stats text-center">
            <h1>DashBord</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                             totle members
                            <span><a href="members.php"><?php echo countItems('userID', 'users'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            pending members
                             <span><a href="members.php?do=mange&page=pending"><?php echo checkItem('regStatus','users', 0); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            totle items
                             <span><a href="items.php"><?php echo countItems('itemID', 'items'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            totle comments
                            <span>
                                <a href="comments.php"><?php echo countItems('c_id', 'comments'); ?></a>
                            </span>
                        </div>
                    </div>
                </div>            
            </div>
        </div>

        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>
                            latest <?php echo $latestUsers;?> members register
                            <span class="pull-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                            <?php 
                                if(!empty($theLatest)){
                                    foreach($theLatest as $user) {
                                        echo '<li>';
                                            echo  $user['userName'];
                                            echo '<a href="members.php?do=Edit&userid= ' . $user['userID'] . ' ">';
                                                echo '<span class="btn btn-success pull-right">';
                                                    echo '<i class="fa fa-edit"></i>Edit';
                                                    if($user['regStatus'] == 0) {
                                                        echo '<a href="members.php?do=active&userid='. $user['userID'] .'" class="btn btn-info activate pull-right"><i class="fa fa-edit"></i>Activate</a>';
                                                    }
                                                echo '</span>';
                                            echo '</a>';
                                        echo '</li>';
                                    }
                                } else {
                                    echo 'no exist any users';
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i>
                            latest <?php echo $latestitem;?> items
                             <span class="pull-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php 
                                    if(!empty($thelatestitem)){
                                        foreach($thelatestitem as $item) {
                                            echo '<li>';
                                            echo $item['name'];
                                            echo '<a class="" href="items.php?do=Edit&itemid=' . $item['itemID'] . '">
                                            <span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</span>
                                            </a>';
                                            if($item['aprove'] == 0){
                                                echo '<a class="btn btn-info pull-right" href="items.php?do=aprove&itemid=' .$item['itemID'] . '"><i class="fa fa-check"></i> aprove</a>';
                                            }
                                            echo '</li>';
                                        }
                                    } else {
                                        echo 'no exist any items';
                                    }
        
                                ?>
                                
                            </ul>
                        </div>
                    </div>
                </div> 
                
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comment"></i>
                            latest <?php echo $latestcomment; ?> comments
                             <span class="pull-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php 
                                    $stmt= $con -> prepare("SELECT comments.*, users.userName from comments 
                                    inner join users on users.userID= comments.userid ORDER BY c_id DESC LIMIT ". $latestcomment);
        
                                    $stmt -> execute();
                                    $rows = $stmt -> fetchAll();
                                    if(!empty($rows)){
                                         foreach($rows as $row){
                                            echo '<div class="comment-box">';
                                                echo '<span class="mem-n">' . $row['userName']. '</span>';
                                                echo '<p class="mem-c">' . $row['comment'] . '</p>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo 'no exist any items';
                                    }
        
                                ?>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            

        <?php
        /* end dashbord page */
        
        include $tpl . 'footer.php';
        
    } else{
        
        header('location: index.php');
        exit();
        
    }