<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo getTitle(); ?></title>
	<!--	<link rel="stylesheet" href="<?php echo $css ?>front.css" />-->
		<link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" />
		<link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
		<link rel="stylesheet" href="<?php echo $css ?>front.css" />

	</head>
	<body>
    <div class="upper-bar">
        <div class="container">
          <?php if(isset($_SESSION['user'])){ 
            $users = getAllFrom('avatar', 'users', "where userID={$_SESSION['id']}", '', 'userID', 'asc');
            $avatar = $users['0']['avatar'];
            if(!empty($avatar)) {
                 echo '<img class="my-img  img-circle" src="admin/upload/avatar/' . $users['0']['avatar'] . '">';
            } else {
                echo '<img class="my-img img-thumbnail img-circle" src="img.png">';
            }
            ?>
        
            <div class="btn-group my-info">
                <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?php echo $_SESSION['user']; ?>
                    <span class="caret"></span>
                </span>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">my profile</a></li>
                    <li><a href="newad.php">new item</a></li>
                    <li><a href="profile.php#my-ads">my items</a></li>
                    <li><a href="Logout.php">Logout</a></li>
                </ul>
            </div>
            
            <?php
            $userStat = check('regStatus', 'users', "where userID={$_SESSION['id']}" , 'and regStatus=0');
            if($userStat == 1) {
              echo ' <span class="alert alert-danger alert-msg" title="need active from admin">you is not active</span>';
            }

          } else {?>
            <a href="login.php">
                <span class="pull-right">Login/signUp</span>
            </a>
          <?php } ?>
        </div>
    </div> 
    <nav class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">HomePage</a>
        </div>
        <div class="collapse navbar-collapse" id="app-nav">
          <ul class="nav navbar-nav navbar-right">
            <?php 
                $cats = getAllFrom('*', 'categories', 'where parent=0', '', 'id', 'asc');
                foreach($cats as $cat) {
                    echo '<li><a href="categories.php?id= ' . $cat['id'] . '&name=' . $cat['name'] .  ' ">'. $cat['name'] . '</a></li>';
                }
            ?>
          </ul>

        </div>
      </div>
</nav>