<?php
require __DIR__.'/includes/init.php';
header('Content-Type: application/json');

$output = [
    'success' => false , 
    'bodyData' => $_POST, 
    'code' =>0, 
    'error'=>'', 
];

$sql = "UPDATE `products` SET 
`product_code`=?,
`name`=?,
`description`=?,
`category_name`=?,
`weight`=?,
`base_price`=?,
`image_url`=?
WHERE `id`=?"; 

$weight = empty($_POST['weight']) ? null : $_POST['weight'];


$stmt = $pdo->prepare($sql); 
$stmt -> execute([
    $_POST['product_code'],
    $_POST['product_name'],
    $_POST['description'],
    $_POST['category_name'],
    $weight,
    $_POST['price'],
    $_POST['image_url'],
    $_POST['product_id']
]);
$output['success'] = !!$stmt->rowCount();


echo json_encode($output,JSON_UNESCAPED_UNICODE);