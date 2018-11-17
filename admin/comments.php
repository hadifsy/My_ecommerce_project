<?php

    session_start();

    if(isset($_SESSION['username'])) {
        
        $pageTitle = 'comments';
        
        include 'init.php';
        
        $do = isset($_GET['do']) ? $_GET['do'] : 'mange';
        
        if($do == 'mange'){
            
            $stmt = $con -> prepare("SELECT comments.*, items.name, users.userName FROM comments 
            inner join items ON comments.itemid = items.itemID
            inner join users ON users.userID = comments.userid");
            $stmt->execute();
            $rows = $stmt->fetchAll();
        
        ?>

        <h1 class="text-center">comment page</h1>
        <div class="container">

            <div class="table-responsive">
                <table class="table main-table text-center table-border">
                    <tr>
                        <td>#id</td>
                        <td>comment</td>
                        <td>item</td>
                        <td>user</td>
                        <td>date</td>
                        <td>control</td>
                    </tr>
                    <?php
                        foreach($rows as $row){
                            echo '<tr>';
                            echo '<td>' . $row['c_id'] . '</td>';
                            echo '<td>' . $row['comment'] . '</td>';
                            echo '<td>' . $row['name'] . '</td>';
                            echo '<td>' . $row['userName'] . '</td>';
                            echo '<td>' . $row['c_date'] . '</td>';
                            echo '<td>';
                             echo '<a href="comments.php?do=Edit&id='. $row['c_id'] .'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                             <a href="comments.php?do=delete&id='. $row['c_id'] .'" class="btn btn-danger confirm"><i class="fa fa-close"></i> delete</a>';
                            if($row['status'] == 0){
                                echo '<a class="btn btn-info activate" href="comments.php?do=aprove&id=' . $row['c_id'] . '"><i class="fa fa-check"></i> aprove<a>';
                            }
                            
                            echo '</td></tr>';
                        }
                        
            
            ?>
                </table>
            </div>
        </div>

<?php
        } elseif($do == 'Edit') {
            
            /*setp edit:
            **tack id from link
            **fetch data from db depand on id if not found id echo msg error else
            **create form with value from db & with hidden input id
            */
            
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
            
            $stmt = $con -> prepare('SELECT comments.* FROM comments WHERE c_id = ?');
            $stmt -> execute(array($id));
            $row = $stmt->fetch();
            
            if($stmt -> rowCount() > 0) {
            
            ?>
            
            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                
                <form class="form-horizontal" action="?do=update" method="POST">
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">comment</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="id" value="<?php echo $row['c_id'] ;?>" >
                            <textarea class="form-control" name="comment"><?php echo $row['comment'] ;?></textarea>
                        </div>
                    </div>      
                        
                    
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" type="submit" value="update">
                        </div>
                    </div>
                    
                </form>
                
            </div>

<?php
            } else {
                
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-danger">no found this id</div>';
                redirectHome($theMsg);
                echo '</div>';
                
            }
            
        } elseif($do == 'update') {
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                $id = $_POST['id'];
                $comment = $_POST['comment'];
                
                $stmt = $con -> prepare('UPDATE comments SET comment=? WHERE c_id=?');
                $stmt->execute(array($comment, $id));
                
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-success">update '. $stmt->rowCount() .' success</div>';
                redirectHome($theMsg, 'back');
                echo '</div>';
                
            } else {
                
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-danger">you can\'t brows this page directly</div>';
                redirectHome($theMsg);
                echo '</div>';
                
            }
            
        } elseif($do == 'delete'){
            
            /*set delete page:
            **sure if id is found else id=0
            **sure if item in db debind on id if not found echo msg error
            **delete from db depind on id & echo success msg
            */
            
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0; 
                
            $check = checkItem('c_id', 'comments', $id);
            
            if($check > 0) {?>
                    
                <h1 class="text-center">delete item</h1>
                <div class="container">
                    
                <?php
                $stmt = $con -> prepare("DELETE FROM comments WHERE c_id=?");
                $stmt -> execute(array($id));
                
                $theMsg = '<div class="alert alert-success">delete' . $stmt->rowCount() . ' success</div>';
                redirectHome($theMsg, 'back');
                
            } else {
                $theMsg = '<div class="alert alert-danger">this id not found</div>';
                redirectHome($theMsg);
            } 
                
            echo '</div>';
            
        } elseif ($do == 'aprove') {
            
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
            $check = checkItem('c_id', 'comments', $id);
            
            if($check >0) {
                
                ?>
                    <h1 class="text-center">aprove item</h1>
                    <div class="container">
                
                        
                <?php

                    $stmt = $con -> prepare('UPDATE comments SET status = 1 WHERE c_id =?');
                    $stmt->execute(array($id));
                    $theMsg = '<div class="alert alert-success">aprove' . $stmt->rowCount() . ' success</div>';
                    redirectHome($theMsg, 'back');
                    echo '</div>';
                
            } else {
                
                $theMsg = '<div class="alert alert-danger">this id not found</div>';
                redirectHome($theMsg);
            }
            
        }
       
        include $tpl . 'footer.php';
        
    } else {
        header('location:index.php');
        exit();
    }
?>
