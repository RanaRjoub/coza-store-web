<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include 'db.php';
$user_id = $_SESSION['user_id'] ?? null;
 $totalPrice = 0;
$totalQuantity = 0;
$cart = [];
if ($user_id) {
    $stmt = $conn->prepare("SELECT p.product_id, p.name, p.main_image  , p.price, c.quantity 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.product_id 
                            WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
     while($row=$result->fetch_assoc()){
        $cart[] = $row;
        $quantity = isset($row['quantity']) ? (int)$row['quantity'] : 1;
        $price = isset($row['price']) ? (float)$row['price'] : 0;
        $totalQuantity += $quantity;
        $totalPrice += $price * $quantity;
     }
     
} else {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $cart = $_SESSION['cart'];
    
$totalPrice = 0;
foreach ($cart as $item) {
    $price = isset($item['price']) ? (float)$item['price'] : 0;
    $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
    $totalPrice += $price * $quantity;
    $totalQuantity += $quantity;
    
}
     

}
$discount = $_SESSION['discount'] ?? 0;
$finalPrice = $totalPrice - $discount;
if ($finalPrice < 0) {
    $finalPrice = 0;
}
?>