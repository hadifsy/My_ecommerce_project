<?php 

    session_start();
    if(isset($_SESSION['username'])){
        $pageTitle ='';
        include 'init.php';
        
        $do = isset($_GET["do"]) ? $_GET["do"] : 'mange';
        
        if($do == 'mange'){
            
            $sort = 'asc';
            $order = 'ordering';
            $sort_array = array('asc', 'desc');
            $order_array = array('id', 'ordering', 'name');
            if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
                $sort = $_GET['sort'];
            }
                           
            if(isset($_GET['order']) && in_array($_GET['order'], $order_array)) {
                $order = $_GET['order'];
            }

            $stmt = $con -> prepare("SELECT * FROM categories WHERE parent=0 ORDER BY $order $sort");
            $stmt -> execute();
            $cats = $stmt -> fetchAll();

        ?>

        <h1 class="text-center">Manage Categories</h1> 
        <div class='container cats'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i> Mange Categories
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i>Ordering: [
                        <a class="<?php if($sort=='asc') echo 'active'; ?>" href='?sort=asc'>Asc</a> |
                        <a class="<?php if($sort=='desc') echo 'active'; ?>" href="?sort=desc">desc</a> ]
                        <i class="fa fa-eye"></i> view:
                        <span class="active" data-view='full'>Full</span> |
                        <span data-view='clas'>Classic</span>
                    </div>
                </div>
                <div class="panel-body">
            
                    <?php
            
                        foreach($cats as $cat) {
                            
                            echo '<div class="cat">';
                                echo '<div class="hidden-btn">';
                                    echo '<a href="?do=Edit&catid='. $cat["id"] . '" 
                                    class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                                    echo '<a href="?do=delete&id=' . $cat["id"] . '" 
                                    class="confirm btn btn-danger btn-xs"><i class="fa fa-close"></i> Delete</a>';
                            
                                echo '</div>';
                                echo '<h3>' . $cat['name'] . '</h3>';
                                echo '<div class="full-view">';
                                    echo '<p>'; 
                                    if($cat['description'] == ''){echo 'this catigory no has description';} 
                                    else {echo $cat['description'];}
                                    echo '</p>';
                                    if($cat['visibilty'] == 1) { echo '<span class="visible"><i class="fa fa-eye"></i> hidden</span>';}
                                    if($cat['allow_comment'] == 1) { echo '<span class="comment"><i class="fa fa-close"></i> comment disable</span>';}
                                    if($cat['allow_ads'] == 1) { echo '<span class="ads"><i class="fa fa-close"></i> ads disaply</span>';}
                                   
                                    $subcats = getAllFrom('*', 'categories', "where parent={$cat['id']}", "", 'id', '');
                                    if(!empty($subcats)) {
                                        echo '<h4 class="sub-head">sub categories</h4>';
                                        echo '<ul class="subcats list-unstyled">';
                                        foreach($subcats as $sub) {
                                            echo '<li class="sub-link">';
                                                echo '<a href="?do=Edit&catid='. $sub["id"] . '">'. $sub['name'] .'</a>';
                                                echo '<a href="?do=delete&id=' . $sub["id"] . '" class="confirm show-delete"> Delete</a>';
                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                    }
                            
                                echo '</div>';
                            echo '</div>';
                            

                            echo '<hr>';

                            
                        }
            
                    ?>
                    
                </div>
            </div>
            <a href="categories.php?do=add" class="add_cat btn btn-primary"><i class="fa fa-plus"></i> Add new category</a>
        </div>

        <?php
            
        } elseif($do == 'add') { ?>

            <h1 class="text-center">Add new Categories</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=insert" method='POST'>
                        
                        <!-- start name -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" required placeholder="must be type name">
                            </div>
                        </div>
                        
                        <!-- start describe -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">describe</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="describe" class="form-control" placeholder="descripe the catigore">
                            </div>
                        </div>
                        
                        <!-- start parent -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">parent? </label>
                            <div class="col-sm-10 col-md-6">
                                <select name="parent">
                                    <?php 
                                        $cats = getAllFrom("*", "categories", "where parent=0", "", "id", "");
                                        foreach ($cats as $cat) { ?>
                                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- start order -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">order</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="order" class="form-control" placeholder="num to ordering">  
                            </div>
                        </div>
                        
                        <!-- start visible -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">visible</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id='v-yes' type="radio" name="visible" value="0" checked>
                                    <label for='v-yes'>yes</label>
                                </div>
                                <div>
                                    <input id='v-no' type="radio" name="visible" value="1">
                                    <label for='v-no' >no</label>
                                </div>
                            </div>
                        </div>      
                        
                        <!-- start comment -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">comment</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id='c-yes' type="radio" name="comment" value="0" checked>
                                    <label for='c-yes'>yes</label>
                                </div>
                                <div>
                                    <input id='c-no' type="radio" name="comment" value="1">
                                    <label for='c-no' >no</label>   
                                </div>
                            </div>
                        </div>      
                        
                        <!-- start ads -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">ads</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id='ads-yes' type="radio" name="ads" value="0" checked>
                                    <label for='ads-yes'>yes</label>
                                </div>
                                <div>
                                    <input id='ads-no' type="radio" name="ads" value="1">
                                    <label for='ads-no' >no</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- start submit -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value='add categore' class="btn btn-primary btn-lg">  
                            </div>
                        </div>
                        
                    </form>
                </div>


        <?php
            
        } elseif($do == 'insert') {
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
                echo '<h1 class="text-center">insert catagory to database</h1>';
                echo '<div class="container">';

                //get var fom form
                $name     = $_POST['name'];
                $desc     = $_POST['describe'];
                $parent   = $_POST['parent'];
                $order    = $_POST['order'];
                $visible  = $_POST['visible'];
                $comment  = $_POST['comment'];
                $ads      = $_POST['ads'];

                //update db with this info
                $check = checkItem('name', 'categories', $name);
                if($check == 1){
                    
                    echo '<div class="container">';
                    $theMsg = '<div class="alert alert-success">sorry this username is exists</div>';
                    redirectHome($theMsg);
                    echo '</div>';
                    
                } else{
                    
                    $stmt = $con -> prepare('INSERT INTO
                                            categories
                                            (name, description, parent, ordering, visibilty, allow_comment ,allow_ads)
                                        VALUES
                                            (:name, :desc, :parent, :order, :visible, :comment, :ads )');
                    
                    $stmt->execute(array(
                        'name'    => $name,
                        'desc'    => $desc,
                        'parent'  => $parent,
                        'order'   => $order,
                        'visible' => $visible,
                        'comment' => $comment,
                        'ads'     => $ads
                    ));
                    
                    //echo succes message
                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' row insert to database </div>';
                    redirectHome($theMsg, 'back');

                }
                
            } else {
                
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-danger">you cant browes this page directly</div>';
                redirectHome($theMsg);
                echo '</div>';
                
            }

            echo '</div>';


        } elseif($do == 'Edit') {
            
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;//CHECK AGIN
            $stmt = $con->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute(array($catid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            
            if($count > 0) { ?>      
                <h1 class="text-center">Edit Categories</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?do=update" method='POST'>
                            
                            <input type="hidden" name="catid" value="<?php echo $catid; ?>">
                            <!-- start name -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="name" class="form-control" required placeholder="must be type name" value="<?php echo $row['name'];?>">
                                </div>
                            </div>

                            <!-- start describe -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">describe</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="describe" class="form-control" placeholder="descripe the catigore" value="<?php echo $row['description'];?>">
                                </div>
                            </div>  
                            
                            <!-- start parent -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">parent?</label>
                                <div class="col-sm-10 col-md-6">
                                    <select name="parent">
                                        <option value="0">null</option>
                                        <?php 
                                            $maincats = getAllFrom('*', 'categories', 'where parent=0', '', 'id');
                                            foreach($maincats as $cat) { 
                                                echo '<option value="' . $cat['id'] . '"';
                                                if($row['parent'] == $cat['id']){echo 'selected';}  
                                                echo '>' . $cat['name'] . '</option>';
                                        }
                                         ?>
                                    </select>
                                </div>
                            </div>

                            <!-- start order -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">order</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="order" class="form-control" placeholder="num to ordering" value="<?php echo $row['ordering']; ?>">  
                                </div>
                            </div>

                            <!-- start visible -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">visible</label>
                                <div class="col-sm-10 col-md-6">
                                    <div>
                                        <input id='v-yes' type="radio" name="visible" value="0"
                                               <?php  if($row['visibilty'] == 0){ echo 'checked';} ?>>
                                        <label for='v-yes'>yes</label>
                                    </div>
                                    <div>
                                        <input id='v-no' type="radio" name="visible" value="1"
                                               <?php  if($row['visibilty'] == 1){ echo 'checked';} ?>>
                                        <label for='v-no' >no</label>
                                    </div>
                                </div>
                            </div>      

                            <!-- start comment -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">comment</label>
                                <div class="col-sm-10 col-md-6">
                                    <div>
                                        <input id='c-yes' type="radio" name="comment" value="0" <?php  if($row['allow_comment'] == 0){ echo 'checked';} ?>>
                                        <label for='c-yes'>yes</label>
                                    </div>
                                    <div>
                                        <input id='c-no' type="radio" name="comment" value="1" <?php  if($row['allow_comment'] == 1){ echo 'checked';} ?>>
                                        <label for='c-no' >no</label>   
                                    </div>
                                </div>
                            </div>      

                            <!-- start ads -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">ads</label>
                                <div class="col-sm-10 col-md-6">
                                    <div>
                                        <input id='ads-yes' type="radio" name="ads" value="0" <?php  if($row['allow_ads'] == 0){ echo 'checked';} ?>>
                                        <label for='ads-yes'>yes</label>
                                    </div>
                                    <div>
                                        <input id='ads-no' type="radio" name="ads" value="1" <?php  if($row['allow_ads'] == 1){ echo 'checked';} ?>>
                                        <label for='ads-no' >no</label>
                                    </div>
                                </div>
                            </div>

                            <!-- start submit -->
                            <div class="form-group form-group-lg">
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
            
        } elseif($do == 'update'){ ?>

            <h1 class="text-center">Update page</h1>
            <div class="container">
                
                <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    
                    $id       = $_POST['catid'];
                    $name     = $_POST['name'];
                    $desc     = $_POST['describe'];
                    $parent   = $_POST['parent'];
                    $order    = $_POST['order'];
                    $vis      = $_POST['visible'];
                    $comment  = $_POST['comment'];
                    $ads      = $_POST['ads'];
                    
                    $stmt = $con -> prepare('UPDATE categories 
                    SET 
                    name =?, description=?, parent=? , ordering=?, visibilty=?, allow_comment =?, allow_Ads=? WHERE id = ?');
                    
                    $stmt -> execute(array($name, $desc, $parent, $order, $vis, $comment, $ads, $id));
                    
                    $theMsg = '<div class="alert alert-success">update '. $stmt->rowCount() .' success</div>';
                    redirectHome($theMsg, 'back');
                    
                } else {
                    
                    $theMsg = '<div class="alert alert-danger">you can\'t browes this page directly</div>';
                    redirectHome($theMsg);
                    
                }
                
                ?>
            </div>

        <?php
            
        } elseif($do == 'delete'){ ?>
        
            <h1 class="text-center">Delete Ctegory Page</h1>
            <div class="container">
                <?php
                    
                    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0; 
                    $check = checkItem('id', 'categories', $id);        
            
                    if ($check > 0) {
                        
                        $stmt = $con -> prepare('DELETE FROM categories WHERE id = ?');
                        $stmt -> execute(array($id));

                        $theMsg = '<div class="alert alert-success">delete '. $stmt->rowCount() .' success</div>';
                        redirectHome($theMsg, 'back');
                        
                    } else {
                        
                        $theMsg = '<div class="alert alert-danger">this id not found</div>';
                        redirectHome($theMsg);
                        
                    }
                    
            
                ?>
                
            </div>

        <?php
            
        }
        
        include $tpl . 'footer.php';
        
        } else { // if not fount session username

            header('location:index.php');
            exit();

        }

?>