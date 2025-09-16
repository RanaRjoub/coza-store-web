<?php
session_start();
include 'db.php';
$product_id = $_POST['id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
if ($product_id !== null) {
    if ($user_id) {
        $sql = "DELETE FROM cart WHERE user_id= ? AND product_id= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) {
            echo "item deleted successfully";
        } else {
            echo "error deleting";
        }
        $stmt->close();
    } else {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $product) {
                if ((string)$product_id === (string)$product['product_id']) {
                    unset($_SESSION['cart'][$key]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                    echo "Item deleted successfully from session";
                    exit;
                }
            }
        }
    }
}
