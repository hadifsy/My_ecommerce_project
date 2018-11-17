<?php

    session_start();
    
    if(isset($_SESSION['username'])) { //line 29

        
        $pageTitle ='items page';
        include 'init.php';
        
        $do = isset($_GET['do']) ? $_GET['do'] : 'mange';
        
        $stmt = $con -> prepare('SELECT items.*, categories.name as cat_name, users.userName from items
                                    INNER JOIN categories ON categories.id = items.cat_id
                                    INNER join users ON users.userID = items.mem_id;');
        $stmt->execute();
        $items = $stmt -> fetchAll();
        
        if($do == 'mange'){ ?>
        
        <?php
        if (!empty($items)) {
        ?>
        
        <h1 class="text-center">Mange Items</h1>
        <div class="container">

            <div class="table-responsive">
                <table class="table main-table text-center table-border">
                    <tr>
                        <td>#id</td>
                        <td>name</td>
                        <td>description</td>
                        <td>price</td>
                        <td>add_date</td>
                        <td>country</td>
                        <td>catigory</td>
                        <td>user</td>
                        <td>control</td>
                    </tr>
                        <?php
                            foreach($items as $item) {
                                echo '<tr>';
                                echo '<td>' . $item['itemID'] . '</td>';
                                echo '<td>' . $item['name'] . '</td>';
                                echo '<td>' . $item['description'] . '</td>';
                                echo '<td>' . $item['price'] . '</td>';
                                echo '<td>' . $item['add_date'] . '</td>';
                                echo '<td>' . $item['country_made'] . '</td>';
                                echo '<td>' . $item['cat_name'] . '</td>';
                                echo '<td>' . $item['userName'] . '</td>';
                                echo '<td>';
                                echo '<a href="items.php?do=Edit&itemid='. $item['itemID'] .'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a> ';
                                echo '<a href="items.php?do=delete&itemid='. $item['itemID'] .'" class="btn btn-danger confirm"><i class="fa fa-close"></i> delete</a>';
                                if($item['aprove'] == 0){
                                       echo '<a href="items.php?do=aprove&itemid=' .$item['itemID']. '" class="btn btn-info activate"><i class="fa fa-check"></i> aprove</a>';
                                        
                                }
                               echo '</td></tr>'; 
                            }
                        ?>
                </table>
                <a href="items.php?do=add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> add item</a>
            </div>
        </div>

        <?php 
        } else {
            echo '<div class="container">';
            echo '<div class="nice-msg">no exist any items</div>';
            echo '<a href="items.php?do=add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> add item</a>';
            echo '</div>';
        }
        ?>

        <?php 
            
        } elseif($do == 'add') { ?>

            <h1 class="text-center">Add Items</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=insert" method="POST">
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="name the item" required>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">decription</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="desc" class="form-control" placeholder="description this item" required>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control" placeholder="price the item" required>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class="form-control" placeholder="country made the item" required>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">image</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="file" src name="img">
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">status</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="status">
                                <option value="0">...</option>
                                <option value="1">new</option>
                                <option value="2">like new</option>
                                <option value="3">uesd</option>
                                <option value="4">very old</option>
                            </select>
                        </div>
                    </div>       
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">users</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="users">
                                <option value="0">...</option>
                                <?php 
                                    $users = getAllFrom('*', 'users', '', '', 'userID');
                                    foreach($users as $user){ 
                                        echo '<option value="'. $user['userID'] .'">'. $user['userName'] .'</option>';
                                    } 
                                    
                                ?>
                            </select>
                        </div>
                    </div>      
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">category</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="cats">
                                <option value="0">...</option>
                                <?php 
                                    $cats = getAllFrom('*', 'categories', 'where parent=0', '', 'id');
                                    foreach($cats as $cat){ 
                                        $subs = getAllFrom('*', 'categories', "where parent={$cat[id]}", '', 'id');
                                        echo '<option value="'. $cat['id'] .'">'. $cat['name'] .'</option>';
                                        foreach($subs as $sub) {
                                            echo '<option value="'. $sub['id'] .'">--- '. $sub['name'] .'</option>';
                                        }
                                    } 
                                    
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">taga</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="tags" placeholder="perate with coma (,)">
                        </div>
                    </div>
                    
                     <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="add Items" class="btn btn-primary btn-sm">
                        </div>
                    </div>
                   
                </form>
            </div>

        <?php
            
        } elseif($do == 'insert'){
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
            
                <h1 class="text-center">Insert item page</h1>
                <div class="container">
                    
                    <?php
                
                        $name     = $_POST['name'];
                        $desc     = $_POST['desc'];
                        $price    = $_POST['price'];
                        $country  = $_POST['country'];
                        $status   = $_POST['status'];
                        $user     = $_POST['users'];
                        $cats     = $_POST['cats'];
                        $tags     = $_POST['tags'];
                
                        $arrayErr = array();
                
                        if(empty($name)){
                            $arrayErr[] = 'you cant leave name empty';
                        } 
        
                        if(empty($desc)){
                            $arrayErr[] = 'you cant leave description empty';
                        } 
                
                        if(empty($price)){
                            $arrayErr[] = 'you cant leave price empty';
                        } 
                
                        if(empty($country)){
                            $arrayErr[] = 'you cant leave country empty';
                        } 
                
                        if($status == 0){
                            $arrayErr[] = 'you mest be chosse the status';
                        }  
                
                        if($user == 0){
                            $arrayErr[] = 'you mest be chosse the user';
                        }  
                
                        if($cats == 0){
                            $arrayErr[] = 'you mest be chosse the catgory';
                        }                  
                
                        if(empty($arrayErr)){
                            
                            $stmt = $con -> prepare("INSERT INTO items(name, description, price, add_date, country_made, status, mem_id, cat_id, tags) VALUES(?, ?, ?, now(), ?, ?, ?, ?, ?)");

                            $stmt -> execute(array($name, $desc, $price, $country, $status, $user, $cats, $tags)); 

                            $theMsg = '<div class="alert alert-success">insert '. $stmt->rowCount() .' success</div>';
                            redirectHome($theMsg, 'back');
                            
                        } else {
                            foreach($arrayErr as $error){
                                echo '<div class="alert alert-danger">'. $error .'</div>';
                            }
                            
                            redirectHome('','back');
                        }
                
                        
                ?>
                    
                </div>
                    
                    <?php
            } else {
                
                $theMsg = '<div class="alert alert-danger">you can\'t browes this page directly</div>';
                redirectHome($theMsg);
            }
            
            
        } elseif($do == 'Edit'){ 
            $itemid = isset($_GET['itemid']) ? $_GET['itemid'] : 0;
            $stmt = $con -> prepare('SELECT * FROM items WHERE itemID = ? ');
            $stmt -> execute(array($itemid));
            $rows = $stmt -> fetch();
            
            $count = $stmt -> rowCount();
            
            if($count > 0) {?>


        <h1 class="text-center">Edit item</h1>
        <div class="container">
            
            <form class="form-horizontal" action="?do=update" method="POST">
                
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="hidden" name="itemid" value="<?php echo $rows['itemID']; ?> ">
                        <input type="text" name="name" class="form-control" required placeholder="name item" value="<?php echo $rows['name'];?>">
                    </div>
                </div>
                
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="desc" class="form-control" required value="<?php echo $rows['description']; ?>">
                    </div>
                </div>    
                
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class="form-control" required value="<?php echo $rows['price']; ?>">
                    </div>
                </div>
                
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="country" class="form-control" required value="<?php echo $rows['country_made']; ?>">
                    </div>
                </div> 
                
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="0" <?php if($rows['status'] == 0){echo 'selected';}?> >New</option>
                            <option value="1" <?php if($rows['status'] == 1){echo 'selected';}?> >Like New</option>
                            <option value="2" <?php if($rows['status'] == 2){echo 'selected';}?> >used</option>
                            <option value="3" <?php if($rows['status'] == 3){echo 'selected';}?> >very OLd</option>
                        </select>
                    </div>
                </div>
                
                        <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">users</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="users">
                                <?php 
                                    $stmt=$con ->prepare('SELECT * FROM users');
                                    $stmt -> execute();
                                    $users = $stmt -> fetchAll();
                                    foreach($users as $user){ 
                                        echo '<option value="'. $user['userID'] .'"';
                                            if($user['userID'] == $rows['mem_id']) 
                                            {echo 'selected';} echo '>'. $user['userName'] .'</option>';
                                    } 
                                    
                                ?>
                            </select>
                        </div>
                    </div>      
                    
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">category</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="cats">
                                <?php 
                                    $stmt=$con ->prepare('SELECT * FROM categories');
                                    $stmt -> execute();
                                    $cats = $stmt -> fetchAll();
                                    foreach($cats as $cat){ 
                                        echo '<option value="'. $cat['id'] .'"';
                                        if($cat['id'] == $rows['cat_id']) {echo 'selected';}
                                        echo '>'. $cat['name'] .'</option>';
                                    } 
                                    
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- start tags  -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" value="<?php echo $rows['tags']; ?>" class="form-control" name="tags" placeholder="perate with coma (,)">
                        </div>
                    </div>
                    
                    
                     <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="save Items" class="btn btn-primary btn-sm">
                        </div>
                    </div>
                
            </form>
            
            <?php
            $stmt = $con -> prepare("SELECT comments.*, users.userName FROM comments 
            inner join users ON users.userID = comments.userid
            WHERE itemid = ?");
            $stmt->execute(array($itemid));
            $rows = $stmt->fetchAll();
        
            if(!empty($rows)){
        ?>

        <h1 class="text-center">comment mange</h1>

            <div class="table-responsive">
                <table class="table main-table text-center table-border">
                    <tr>
                        <td>comment</td>
                        <td>user</td>
                        <td>date</td>
                        <td>control</td>
                    </tr>
                    <?php
                        foreach($rows as $row){
                            echo '<tr>';
                            echo '<td>' . $row['comment'] . '</td>';
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
            <?php } ?>
            
        </div>

        <?php } else{
                    echo '<div class="container">';
                    $theMsg = '<div class="alert alert-danger">this id not found</div>';
                    redirectHome($theMsg);
                    echo '</div>';
            }
            
        } elseif($do == 'update'){
            /*
            **step the update page:
            **sure if you coming from form else echo error sg
            **save value coming from form & id coming from hidden input
            **set array error with error from form &if not empty echo error else: 
            **update db with this value & echo msg success
            */
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') { ?>

                <h1 class="text-center">Update Items</h1>
                <div class="container">

<?php
                
                $itemid = $_POST['itemid'];
                
                $name = $_POST['name'];
                $desc = $_POST['desc'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $users = $_POST['users'];
                $cats = $_POST['cats'];
                $tags = $_POST['tags'];
                
                $arrayErr = array();
                
                if(empty($name)){
                    $arrayErr[] = '<div>you must type name</div>';
                }    
                
                if(empty($desc)){
                    $arrayErr[] = '<div>you must type description</div>';
                }   
                
                if(empty($price)){
                    $arrayErr[] = '<div>you must enter price</div>';
                }  
                
                if(empty($country)){
                    $arrayErr[] = '<div>you must type country</div>';
                }
                
                if(empty($arrayErr)){
                    $stmt = $con -> prepare("UPDATE items SET name=?, description=?, price=?, country_made=?, status=?, cat_id=?, mem_id=?,tags=? WHERE itemID=?");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $cats, $users, $tags, $itemid));

                    echo '<div class="container">';
                    $theMsg = '<div class="alert alert-success">update '. $stmt->rowCount() .' success</div>';
                    redirectHome($theMsg, 'back');
                    echo '</div>';
                } else {
                    foreach($arrayErr as $error){
                        redirectHome('<div class="alert alert-danger">' . $error . '</div>', 'back');
                    }
                }
               
                
                echo '</div>';
                
                
            } else {
                    echo '<div class="container">';
                    $theMsg = '<div class="alert alert-danger">you can\'t browes this page directly</div>';
                    redirectHome($theMsg);
                    echo '</div>';
            }
            
            
        } elseif($do == 'delete'){
            
            /*set delete page:
            **sure if id is found else id=0
            **sure if item in db debind on id if not found echo msg error
            **delete from db depind on id & echo success msg
            */
            
            $id = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0; 
                
            $check = checkItem('itemID', 'items', $id);
            
            if($check > 0) {?>
                    
                <h1 class="text-center">delete item</h1>
                <div class="container">
                    
                <?php
                $stmt = $con -> prepare("DELETE FROM items WHERE itemID=?");
                $stmt -> execute(array($id));
                
                $theMsg = '<div class="alert alert-success">delete' . $stmt->rowCount() . ' success</div>';
                redirectHome($theMsg, 'back');
                
            } else {
                $theMsg = '<div class="alert alert-danger">this id not found</div>';
                redirectHome($theMsg);
            }
                
            echo '</div>';
            
        } elseif($do = 'aprove'){
            
            echo '<h1 class="text-center">Aprove Page</h1>';
            echo '<div class="container">';
            
            $id = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;
            
            $check = checkItem('itemId', 'items', $id);
            
            if($check >0) {
            

                $stmt = $con -> prepare("UPDATE items SET aprove = 1 WHERE itemID=?");
                $stmt -> execute(array($id)); 
                $theMsg = '<div class="alert alert-success">aprove success</div>';
                redirectHome($theMsg, 'back');
                
                
            } else {
                $theMsg = '<div class="alert alert-danger">this id not found</div>';
                redirectHome($theMsg);
            }
            
            
            
            echo '</div>';
            
        } else {
            
            $theMsg = 'this page not found';
            redirectHome($theMsg);
            
        }
        
        include $tpl . 'footer.php';
        
    } else {
        
        header('location:index.php');
        exit();
        
    }

?>
