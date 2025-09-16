<?php
session_start();
include 'db.php';
$user_id = $_SESSION['user_id'] ?? null;
$product_id = $_POST['id'] ?? null;
$quantity = $_POST['quantity'] ?? null;
if ($product_id !== null && $user_id !== null && $quantity !== null) {
    $sql = "UPDATE cart SET quantity= ? WHERE user_id= ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    if ($stmt->execute()) {
        echo "the item updated successfully";
    } else {
        echo "error updating";
    }
    $stmt->close();
} else {
    if ($product_id !== null && $quantity !== null) {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ((string)$product_id == (string)$item['product_id']) {
                    $_SESSION['cart'][$key]['quantity'] = (int)$quantity;
                    echo "quantity updated successfully";
                    exit;
                }
            }
        }
    }
}
