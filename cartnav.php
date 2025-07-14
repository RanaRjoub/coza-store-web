<?php
$cart = $_SESSION['cart'] ?? [];
$totalQuantity = 0;
$totalPrice = 0;
foreach ($cart as $item) {
    $price = isset($item['price']) ? (float)$item['price'] : 0;
    $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1; 

    $totalQuantity += $quantity;
    $totalPrice += $price * $quantity;
}

?>