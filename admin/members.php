<?php

    session_start(); //to acces to index.php

    if(isset($_SESSION['username'])){
        
        $pageTitle = 'memmbers';
        
        include 'init.php';
        
        $do = isset($_GET['do']) ? $_GET['do'] : 'mange';

        if($do == 'mange'){ //mange member page 

           $query = '';
           if(isset($_GET['page'])  && $_GET['page'] == 'pending'){
               $query = ' And regStatus = 0';
           }
            
           $stmt = $con->prepare('SELECT * FROM users WHERE groupID != 1' . $query);
           $stmt->execute();
           $rows = $stmt->fetchAll();

           if(!empty($rows)) {

?>

                    <h1 class="text-center">mange memmbres</h1>;
                    <div class="container">
                        <div class="table-responsive">
                            <table class="main-table text-center mange-mem table table-border">
                                <tr>
                                    <td>#id</td>
                                    <td>avatar</td>
                                    <td>user name</td>
                                    <td>email</td>
                                    <td>full name</td>
                                    <td>register data</td>
                                    <td>control</td>
                                </tr>
                                <?php 
                                    foreach($rows as $row){
                                        echo '<tr>';
                                        echo '<td>' . $row['userID'] . '</td>';
                                        echo '<td>';
                                        if(!empty($row['avatar'])) {
                                        echo '<img src="upload/avatar/' . $row['avatar'] . '">';
                                        } else {
                                            echo 'no image';
                                        }
                                        echo '</td>';
                                        echo '<td>' . $row['userName'] . '</td>';
                                        echo '<td>' . $row['email'] . '</td>';
                                        echo '<td>' . $row['fullName'] . '</td>';
                                        echo '<td>' . $row['date'] . '</td>';
                                        echo '<td>
                                            <a href="members.php?do=Edit&userid='. $row['userID'] .'" class="btn btn-success"><i class="fa fa-edit"></i>Edit</a>
                                            <a href="members.php?do=delete&userid='. $row['userID'] .'" class="confirm btn btn-danger"><i class="fa fa-close"></i>Delete</a>';
                                            
                                            if($row['regStatus'] == 0) {
                                                echo '<a href="members.php?do=active&userid='. $row['userID'] .'" class="btn btn-info activate"><i class="fa fa-edit"></i>Activate</a>';
                                            }
                                        
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </table>
                        </div>
                        <a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> new memmbre</a>
                    </div>
                    <?php } else {
                        echo '<div class="container">';
                        echo '<div class="nice-msg">no exist any member</div>';
                        echo '<a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> new memmbre</a>';
                        echo '</div>';
            } ?>
             
            <?php } elseif($do == 'add'){//add page ?>
                    <h1 class="text-center">Add new memmbres</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?do=insert" method='POST' enctype="multipart/form-data">
                            <!-- start username -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">userName</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="userName" class="form-control" autocomplete="off" required placeholder="must be type name">
                                </div>
                            </div>
                            <!-- start password -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">password</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="password" name="password" class="password form-control" required placeholder="password must be complex">
                                    <i class="show-pass fa fa-eye fa-2x"></i>
                                </div>
                            </div>
                            <!-- start email -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">email</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="email" name="email" class="form-control" required placeholder="email must be valid">  
                                </div>
                            </div>
                            <!-- start full name -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">fullName</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="fullName"  class="form-control" required placeholder="full name shw in profile">  
                                </div>
                            </div>
                            <!-- start avatar -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">avatar</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="file" name="avatar"  class="form-control" required >  
                                </div>
                            </div>
                            <!-- start submit -->
                            <div -lgclass="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value='add member' class="btn btn-primary btn-lg">  
                                </div>
                            </div>
                        </form>
                    </div>

            <?php } elseif($do == 'insert'){ // insert page

                        if($_SERVER['REQUEST_METHOD'] == 'POST'){
                            echo '<h1 class="text-center">insert member to database</h1>';
                            echo '<div class="container">';
                            
                            //get var fom form
                            $user = $_POST['userName'];
                            $pass = $_POST['password'];
                            $email = $_POST['email'];
                            $fullName = $_POST['fullName'];
                            
                            $hashpass = sha1($_POST['password']);
                            
                            
                            //upload file 
                            $avatar_name = $_FILES['avatar']['name'];
                            $avatar_size = $_FILES['avatar']['size'];
                            $avatar_tmp = $_FILES['avatar']['tmp_name'];
                            $avatar_type = $_FILES['avatar']['type'];
                            
                            $avatarExeAllow = array('jpg', 'png');
                            
//                            $fullName = explode('\\', $avatar_tmp);
//                            array_pop($fullName);
//                            $fullName = implode('\\', $fullName);
//                            $fullName = $fullName .'\\' . $avatar_name;
//                            print_r($fullName);
                            
                            //validate the form
                            $arrayError = array();
                            
                            //check exteson
                            $avatarExt = strtolower(end(explode('.', $avatar_name)));
    
                            if(empty($user)){
                                $arrayError[] = 'uaer name can\'t be <strong>empty</strong>';
                            } elseif(strlen($user) < 4){
                                $arrayError[] = 'uaer name can\'t be less then <strong>4 letters</strong>';
                            } 

                            if(strlen($user) > 15){
                                $arrayError[] = 'uaer name can\'t be large then <strong>15 letters</strong>';
                            }
                            
                            if(empty($pass)){
                                $arrayError[] = 'email can\'t be <strong>empty</strong>';
                            }

                            if(empty($email)){
                                $arrayError[] = 'email can\'t be <strong>empty</strong>';
                            }

                            if(empty($fullName)){
                                $arrayError[] = 'full name can\'t be <strong>empty</strong>';
                            }
                            
                            if(!empty($avatar_name) && !in_array($avatarExt, $avatarExeAllow)) {
                                $arrayError[] = 'you must choose another picture from type <strong>jpg,png</strong>';
                            }       
                            
                            if(empty($avatar_name)) {
                                $arrayError[] = 'you must choose  <strong>image</strong>';
                            } 

                            if($avatar_size > 4*1024*1024) {
                                $arrayError[] = 'size is image is vey <strong>big</strong>';
                            } 

                            //loop into error array and eho it 
                            if(!empty($arrayError)){
                                foreach($arrayError as $error){
                                    echo '<div class="alert alert-danger">' . $error . '</div>' ;
                                }
                            }
                            
                            //update db with this info
                            if(empty($arrayError)){                 
                                $check = checkItem('userName', 'users', $user);
                                if($check == 1){
                                    echo 'sorry this username is exists';
                                } else{
                                      $avatar = rand(1000, 10000) . '_' . $avatar_name;
                                      move_uploaded_file($avatar_tmp, 'upload\avatar\\' . $avatar);
                                        echo $avatar;
                                    
                                    $stmt = $con -> prepare('INSERT INTO 
                                                            users(userName, password, email, fullName,regStatus ,date, avatar)
                                                        VALUES(:user,:pass,:email,:name, 1, now(), :avatar)');
                                    $stmt->execute(array(
                                        'user' => $user,
                                        'pass' => $hashpass,
                                        'email' => $email,
                                        'name' => $fullName,
                                        'avatar' => $avatar
                                 
                                    ));
                                    //echo succes message
                                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' row insert to database </div>';
                                    redirectHome($theMsg, 'back');

                                }
                                                       }
                            
                        }else {
                            echo '<div class="container">';
                            $theMsg = '<div class="alert alert-danger">you cant browes this page directly</div>';
                            redirectHome($theMsg);
                            echo '</div>';
                        }

                        echo '</div>';
            } elseif($do == 'Edit'){//edit page 

                    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;//CHECK AGIN
                    $stmt = $con->prepare("SELECT * FROM users WHERE userID = ?");
                    $stmt->execute(array($userid));
                    $row = $stmt->fetch();
                    $count = $stmt->rowCount();
                if($count > 0) { ?>
                    <h1 class="text-center">Edit Profile</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?do=update" method='POST'>
                            <input type="hidden" name="userid" value="<?php echo $userid ?>">
                            <!-- start username -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">userName</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="userName" class="form-control"  value='<?php echo $row['userName']?>' autocomplete="off" required='required'>
                                </div>
                            </div>
                            <!-- start password -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">password</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>">  
                                    <input type="password" name="newpassword" class="form-control" value="" autocomplete="new-psword" placeholder="leave blank if you no want to chang it">  
                                </div>
                            </div>
                            <!-- start email -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">email</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="email" name="email" value='<?php echo $row['email']?>' class="form-control" required>  
                                </div>
                            </div>
                            <!-- start full name -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">fullName</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="fullName" value='<?php echo $row['fullName']?>' class="form-control" required>  
                                </div>
                            </div>
                            <!-- start submit -->
                            <div -lgclass="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value='save' class="btn btn-primary btn-lg">  
                                </div>
                            </div>
                        </form>
                    </div>
                        
            <?php 
                } else {
                    echo '<div clss="container">';
                    $theMsg = '<div class="alert alert-danger">no found this id</div>';
                    redirectHome($theMsg);
                    echo '</div>';
                }
                
            } elseif($do == 'update'){ //update page

                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    
                    echo '<h1 class="text-center">Update Profile</h1>';
                    echo '<div class="container">';
                    
                        $user = $_POST['userName'];
                        $id = $_POST['userid'];
                        $email = $_POST['email'];
                        $fullName = $_POST['fullName'];
                        //password trick 
                        $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                        //validate the form
                        $arrayError = array();

                        if(empty($user)){
                            $arrayError[] = 'uaer name can\'t be <strong>empty</strong>';
                        } elseif(strlen($user) < 4){
                            $arrayError[] = 'uaer name can\'t be less then <strong>4 letters</strong>';
                        } 

                        if(strlen($user) > 15){
                            $arrayError[] = 'uaer name can\'t be large then <strong>15 letters</strong>';
                        }

                        if(empty($email)){
                            $arrayError[] = 'email can\'t be <strong>empty</strong>';
                        }

                        if(empty($fullName)){
                            $arrayError[] = 'full name can\'t be <strong>empty</strong>';
                        }

                        //loop into error array and eho it 
                        if(!empty($arrayError)){
                            foreach($arrayError as $error){
                                echo '<div class="alert alert-danger">' . $error . '</div>' ;
                            }
                        }

                        //update db with this info
                        if(empty($arrayError)){
                            
                            $stmt2 =  $con -> prepare('SELECT * FROM users WHERE userName=? AND userID !=?');
                            $stmt2->execute(array($user, $id));
                            $count = $stmt2->rowCount();
                            
                            if($count > 0) {
                                $theMsg = '<div class="alert alert-danger">this name is found</div>';
                                redirectHome($theMsg, 'back');
                            } else {
                                $stmt = $con -> prepare('UPDATE users SET userName=?, email=?, fullName=?,password=? WHERE userID=?');
                                $stmt->execute(array($user, $email, $fullName, $pass, $id));                        

                                //echo succes message
                                $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' row updates </div>';
                                redirectHome($theMsg, 'back');
                            }
                            
                        }
                    
                    } else {
                    
                        echo '<div class="container">';
                        $theMsg = 'you can\'t browes this page directly';
                        redirectHome($theMsg);
                        echo '</div>';
                    
                    }
                
                echo '</div>';
                
            } elseif($do == 'delete'){ //delete memmber page
                
                echo '<h1 class="text-center">Delete member</h1>';
                echo '<div class="container">';
                //to delete member must 1)get info th member 2)delete member from db 3)echo message to succes delete
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;//CHECK AGIN
                //select all data depand in id
                
                $check = checkItem('userID', 'users', $userid);

                //if id exist $count mest be large then 0
                if($check > 0) {
                    $stmt = $con->prepare("DELETE FROM users WHERE userID = :zuser");
                    $stmt->bindparam(":zuser", $userid);
                    $stmt->execute();

                    $theMsg = '<div class="alert alert-success">delete ' . $stmt -> rowCount() . ' success</div>';
                    redirectHome($theMsg, 'back');
                        
            } else{

                    $theMsg = '<div class="alert alert-danger">this id is not exist</div>';
                    redirectHome($theMsg);
                }
                echo '</div>';
            
            } elseif($do == 'active'){//activate page

                        echo '<h1 class="text-center">Activate member</h1>';
                        echo '<div class="container">';
                        //to delete member must 1)get info th member 2)delete member from db 3)echo message to succes delete
                        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;//CHECK AGIN
                        //select all data depand in id

                        $check = checkItem('userID', 'users', $userid);

                        //if id exist $count mest be large then 0
                        if($check > 0) {
                            $stmt = $con -> prepare("UPDATE users SET regStatus = 1 WHERE userID = ?");
                            $stmt->execute(array($userid));

                            $theMsg = '<div class="alert alert-success">activate ' . $stmt -> rowCount() . ' success</div>';
                            redirectHome($theMsg, 'back');

                    } else{

                            $theMsg = '<div class="alert alert-danger">this id is not exist</div>';
                            redirectHome($theMsg);
                            
                        }

                        echo '</div>';
        } 
            
            
            include $tpl . 'footer.php';
        }

        else {
            
            header('location: index.php');
            exit();
            
        }