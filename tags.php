<?php 

session_start();
$name = isset($_GET['name']) ? $_GET['name'] : 'category';
$name = filter_var($name, FILTER_SANITIZE_STRING);
$pageTitle = $name;
include 'init.php'; ?>

<div class="container">
    <h1 class="text-center"><?php echo $pageTitle; ?></h1>
    <div class="row">
        <?php 
            $items = getAllFrom('*', 'items', "where tags like '%{$name}%'", 'and aprove=1', 'itemID');
            foreach($items as $item) {
                echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class="thumbnail item-box">';
                        echo '<span class="price-tag">'. $item['price'] .'</span>';
                        echo '<img class="img-responsive" src="img.png" alt="" />';
                        echo '<div class="caption">';
                            echo '<h3>
                                    <a href="items.php?id=' . $item['itemID'] .'">' . $item['name'] . '</a>
                                  </h3>';
                            echo '<p> ' . $item['description'] . ' </p>';
                            echo '<p class="date"> ' . $item['add_date'] . ' </p>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        ?>
    </div>
</div>

<?php include $tpl . 'footer.php'; ?>

