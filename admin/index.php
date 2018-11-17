<?php 
    session_start();

    $noNavbar = '';
    $pageTitle = 'login';

    if(isset($_SESSION['username'])){
        header("location: dashbord.php");        
    }
	include 'init.php';
	
    //check if person coming from http post request
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashpass = sha1($password);//securte the pass
        
        //check if user exists in db
        $stmt = $con->prepare("SELECT userID, userName, password 
        FROM users 
        WHERE userName=? and password=? and groupID=1 ");
        
        $stmt->execute(array($username, $hashpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        //if count > 0  the db contint recorder about this user
        if($count>0){
            $_SESSION['username']=$username; //regiter session name
            $_SESSION['ID']=$row['userID']; //regiter session id
            header("location: dashbord.php");
            exit();
            
        }
        
    }
?>
	<form class="login" action="<?php $_SERVER['PHP_SELF']?>" method="POST">
		<h4 class="text-center">login admin</h4>
		<input class="form-control" type="text" name="user" placeholder="username" autocomplete="off">
		<input class="form-control" type="password" name="pass" placeholder="password" autocomplete="off">
		<input class="btn btn-primary btn-block" type="submit" value="login">
	</form>

<?php include $tpl . "footer.php"; ?>