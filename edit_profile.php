<?php 

include 'init.php';
session_start();
$pageTitle = 'edit info';


$users = getAllFrom('*', 'users', "where userID={$_SESSION['id']}", '', 'userID', '');
?>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
    $user   = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $pass   = $_POST['pass'];
    $email  = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $name   = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);

    // avatar
    $avatar = $_FILES['avatar'];

    $avatarName = $avatar['name'];
    $avatartmp = $avatar['tmp_name'];
    $avatarSize = $avatar['size'];
    $avatarType = $avatar['type'];

    $avatarExtAllow = array('jpg', 'png');

    $avatarExt = end(explode('.', $avatarName));

    $arrayErr = array();
    if(strlen($user) > 3) {
        $arrayErr[] = 'user name must be large then 3 letter';
    } 

    if(empty($pass)) {
        $arrayErr[] = 'user name must be large then 3 letter';
    } else {
        $hashpass = sha1($pass);
    } 

    if(empty($email)) { // ==> check if email valid [must check]
        $arrayErr[] = 'you must enter email';
    }

    if(!empty($avatarName) && in_array($avatarExt, $avatarExtAllow)) {
        $arrayErr[] = 'must type vatar jpg png';
    }

    foreach($arrayErr as $error) {
        echo $error;
    }
        
    }



foreach($users as $user) {
?>

<h1 class="text-center"><?php echo $pageTitle; ?></h1>
<div class="container">

    <form class="form-horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group form-group-lg form-horizontal">
            <label class="col-sm-2 control-label">name </label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="name" class="form-control" value="<?php echo $user['userName']; ?>" placeholder="user name must be large then 3 letter">
            </div>
        </div>

        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">password </label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="pass" class="form-control" placeholder="leave blank if you wwant changed ">
            </div>
        </div>   

        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">email </label>
            <div class="col-sm-10 col-md-6">
                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>"  placeholder="">
            </div>
        </div>
        
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">full name </label>
            <div class="col-sm-10 col-md-6">
                <input type="text" name="fullname" class="form-control" value="<?php echo $user['fullName']; ?>" placeholder="">
            </div>
        </div>       
        
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">avatar </label>
            <div class="col-sm-10 col-md-6">
                <input type="file" class="form-control" value="" placeholder="" placeholder="">
            </div>
        </div>
        
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" class="btn btn-primary" value="update">
            </div>
        </div>
        
    </form>
    
    
</div>

<?php
    
}
include $tpl . 'footer.php';

?>