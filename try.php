<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'] ?? null;

// جلب السلة
if ($user_id) {
    $stmt = $conn->prepare("SELECT p.product_id, p.name, p.price, p.main_image, c.quantity 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.product_id 
                            WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart = [];
    while ($row = $result->fetch_assoc()) {
        $cart[] = $row;
    }
} else {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $cart = $_SESSION['cart'];
}

// حساب الأسعار لكل منتج
$totalPrice = 0;
foreach ($cart as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>
<table border="1">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cart as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>$<?= number_format($item['price'],2) ?></td>
            <td>$<?= number_format($item['price']*$item['quantity'],2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Total: $<?= number_format($totalPrice,2) ?></h2>

</body>
</html>
