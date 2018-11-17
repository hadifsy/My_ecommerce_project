<?php 
session_start();
$pageTitle = 'login';
include 'init.php'; 

if(isset($_SESSION['user'])){
    header('location:index.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['login'])){

        $user = $_POST['userName'];
        $pass = $_POST['password'];
        $hashpass = sha1($_POST['password']);

        $stmt = $con -> prepare("SELECT userName,userID FROM users WHERE userName = ? And password=?");
        $stmt->execute(array($user, $hashpass));
        $count=$stmt->rowCount();

        if($count > 0) {
            $_SESSION['user']  = $user;
            $_SESSION['id']  = $stmt->fetch()['userID'];
            header('location:index.php');
            exit();
        }

    } else { //you come fromsignup page

    $arrayErr = array();

    //filter user name
    if(isset($_POST['userName'])) {

        $user = filter_var($_POST['userName'], FILTER_SANITIZE_STRING);

        if(strlen($user) < 3) {
            $arrayErr[] = 'user name cant be less then 3 letters';
        } elseif(strlen($user) >= 15) {
            $arrayErr[] = 'user name cant be more then 15 letters';
        }

    }


    if(isset($_POST['password']) && isset($_POST['password2'])) {

        if(empty($_POST['password'])) {
            $arrayErr[] = 'sorry you cant leave password empty';
        }

        $pass1 = sha1($_POST['password']);
        $pass2 = sha1($_POST['password2']);

        if($pass1 !== $pass2) {
            $arrayErr[] = 'password no matched';
        }

    }

    if(isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        if(filter_var($email, FILTER_VALIDATE_EMAIL) != true) {
             $arrayErr[] = 'sorry not valid email';
        }

    }

    if (empty($arrayErr)) {

        $check = check('userName', 'users', "where userName=$user", '');

        if($check > 0) {
            $arrayErr[] = 'sorry user is exists';
        } else {
            $stmt = $con ->prepare("INSERT INTO users(userName, password, email, `date`) VALUES(?, ?, ?, now())");
            $stmt->execute(array($user, $pass1, $email));
            $msg_success = '<div class="success-msg">you is now registry</div>';
        }

    }

} //end else

} //end method post

?>

<div class="container login-page">
    <h1 class="text-center">
        <span data-class="login" class="selected">login</span> | <span data-class="signUp">sign up</span>
    </h1>
    <form class="login" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="input-container">
                <input type="text" class="form-control" name="userName" placeholder="type user name" required>
            </div>
            <div class="input-container">
                <input type="password" class="form-control" name="password" placeholder="type password" required>
            </div>
            <input type="submit" class="btn btn-primary btn-block" name="login" value="login">
    </form>

    <form class="signUp" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="input-container">
                <input pattern=".{3,15}"
                       title="user name must be between 3-15 chars" 
                       type="text" 
                       class="form-control" 
                       name="userName" 
                       placeholder="type user name" 
                       autocomplete="off" required>
            </div>
            <div class="input-container">
                <input minlength="4" type="password" class="form-control" name="password" placeholder="type a complex password" required>
            </div>
            <div class="input-container">
                <input minlength="4" type="password" class="form-control" name="password2" placeholder="type password agin" required>
            </div>
            <div class="input-container">
                <input type="email" class="form-control" name="email" placeholder="type valid email" required>
            </div>
            <input type="submit" class="btn btn-success btn-block" name="signUp" value="Sign Up">
    </form>
</div>

<div class="the-error text-center">
    <?php
    if(!empty($arrayErr)){ 
        foreach($arrayErr as $error){
            echo '<div class="nice-msg">'. $error .'</div>';
        }

    } else { 
        if(isset($msg_success)){
            echo '<div class="success-msg">you is now registry</div>';
        }
    }
    ?>
</div>

<?php include $tpl . 'footer.php'; ?>