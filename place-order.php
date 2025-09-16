<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) session_start();
include 'db.php';
include 'cartnav.php';
header('Content-Type: application/json; charset=utf-8');
$user_id=$_SESSION['user_id']??null;
if(!$user_id){
     echo json_encode(["success" => false, "message" => "you should sign in firstly"]);
    exit();
}

$fname=$_POST['fname']??'';
$lname=$_POST['lname']??'';
$country=$_POST['country']??'';
$address1=$_POST['address1']??'';
$address2=$_POST['address2']??'';
$city=$_POST['city']??'';
$zip=$_POST['zip']??'';
$phone=$_POST['phone']??'';
$email=$_POST['email']??'';
$notes=$_POST['notes']??'';
$cart=[];
$totalPrice = 0;
    $stmt=$conn->prepare("SELECT c.product_id, p.name, p.price, c.quantity
     FROM cart c 
     JOIN products p ON c.product_id=p.product_id
     WHERE c.user_id= ?");
     $stmt->bind_param("i",$user_id);
     $stmt->execute();
     $result=$stmt->get_result();
     while($row=$result->fetch_assoc()){
        $cart[]=$row;
         $totalPrice+=$row['price']* $row['quantity'];
     }

if(empty($cart)){
    echo json_encode(["success"=>false,"message"=>"cart empty"]);
    exit();
}

$response=["success" => false, "message" => "error❌"];
$sql="INSERT INTO orders (user_id ,fname,lname,country,address1,address2,city,zip,phone,email,notes,total_price,status,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,'pending',NOW())";
$stmt=$conn->prepare($sql);
$stmt->bind_param("issssssssssd",$user_id,$fname,$lname,$country,$address1,$address2,$city,$zip,$phone,$email,$notes,$totalPrice);
if($stmt->execute()){
    $order_id=$stmt->insert_id;
    $sql2="INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)";
    $stmt_item=$conn->prepare($sql2);
foreach($cart as $item){
    $stmt_item->bind_param("iiid",$order_id,$item['product_id'] ,$item['quantity'] ,$item['price']);
    $stmt_item->execute();
}
$stmt_clear=$conn->prepare("DELETE FROM cart WHERE user_id= ?");
$stmt_clear->bind_param("i",$user_id);
$stmt_clear->execute();
$response=["success" => true, "message" => "Order Created Succesfully"];

}



echo json_encode($response);

?>