<?php 

    session_start();
    if(isset($_SESSION[username])){
        
        $pageTitle ='';
        include 'init.php';
        

        $do = isset($_GET["do"]) ? $_GET["do"] : 'mange';
        if($do == 'mange'){
            
        } elseif($do == 'add') {
            
        } elseif($do == 'insert') {
            
        } elseif($do == 'Edit') {
            
        } elseif($do == 'update'){
            
        } elseif($do == 'delete'){
            
        } elseif($do == 'active'){
            
        }
        
        include 'footer.php';
        
        } else {

            header('location:index.php');
            exit();

        }

// add -> insert
// edit -> update
// delete

?>