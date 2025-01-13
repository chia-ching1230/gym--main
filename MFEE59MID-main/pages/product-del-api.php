<?php

require __DIR__.'/includes/init.php';

$product_id = empty($_GET['product_id'])? 0 : intval($_GET['product_id']);



if($product_id){
    $sql ="DELETE FROM products WHERE id = $product_id";
    $pdo->query($sql);
}
$come_from='products.php';
if(isset($_SERVER['HTTP_REFERER'])){
    $come_from=$_SERVER['HTTP_REFERER'];
}

header("Location: $come_from");