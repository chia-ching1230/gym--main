<?php
require __DIR__.'/includes/init.php';
header('Content-Type: application/json');

$output = [
    'success' => false , 
    'bodyData' => $_POST, 
    'code' =>0, 
    'error'=>'', 
    'lastInsertId' =>0,
];
$sql = "INSERT INTO `products`
(`product_code`, `name`, `description`, `category_name`, `weight`, `base_price`, `image_url`) 
VALUES (?,?,?,?,?,?,?)"; 

$weight = empty($_POST['weight']) ? null : $_POST['weight'];


$stmt = $pdo->prepare($sql); 
$stmt -> execute([
    $_POST['product_code'],
    $_POST['product_name'],
    $_POST['description'],
    $_POST['category_name'],
    $weight,
    $_POST['price'],
    $_POST['image_url']
]);
$output['success'] = !!$stmt->rowCount();
$output['lastInsertId'] = $pdo->lastInsertId();

echo json_encode($output,JSON_UNESCAPED_UNICODE);