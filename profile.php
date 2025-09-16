<!DOCTYPE html>
<html lang="zxx">
<?php
session_start();
if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    echo "<div class='popup-message {$msg['type']}'>{$msg['text']}</div>";
    echo "<script>
    setTimeout(function(){
    const popup=document.querySelector('.popup-message');
    if(popup) popup.remove();
    },3000);
    </script>";
    unset($_SESSION['message']);
}
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("location:register.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT name ,email ,phone ,address FROM users WHERE user_id= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user['name'];
    $user_email = $user['email'];
    $user_phone = $user['phone'] ?? '';
    $user_address = $user['address'] ?? '';
} else {
    $user_name = "user not found";
    $user_email = "user not found";
}

$sql2 = "SELECT o.order_id,oi.product_id ,p.name ,p.main_image,p.price ,oi.quantity ,o.status ,
  (SELECT SUM(oi2.quantity * oi2.price) 
     FROM order_items oi2 
     WHERE oi2.order_id = o.order_id) AS total_price
 FROM orders o
 JOIN order_items oi ON o.order_id=oi.order_id 
 JOIN products p ON oi.product_id=p.product_id 
 WHERE o.user_id =? 
 ORDER BY o.order_id DESC 

 
 ";
$stmt_order = $conn->prepare($sql2);
$stmt_order->bind_param("i", $user_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();
if ($result_order->num_rows > 0) {
    $orders = [];
    while ($row = $result_order->fetch_assoc()) {
        $order_id =$row['order_id'];
        if(!isset($orders[$order_id])){
           $orders[$order_id] = [
            "order_id" => $order_id,
            "status" => $row['status'],
            "total_price"=>$row['total_price'],
            "products" => [] 
        ];
    }
    $orders[$order_id]['products'][]=[   "product_id" => $row['product_id'],
        "name" => $row['name'],
        "main_image" => $row['main_image'],
        "quantity" => $row['quantity'],
        "price" => $row['price']
    ];
}
}
if (isset($_POST['save_info'])) {
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $update = "UPDATE users SET phone= ? ,address= ? WHERE user_id= ?";
    $stmt_up = $conn->prepare($update);
    $stmt_up->bind_param("ssi", $phone, $address, $user_id);
    $stmt_up->execute();
    header("location:profile.php");
    exit();
}

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];
    $sql = "SELECT password FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $hashed_password = $user['password'];
        if (password_verify($current_password, $hashed_password)) {
            if ($new_password == $confirm_password) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password  = ? WHERE user_id=  ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $new_hashed_password, $user_id);
                if ($update_stmt->execute()) {
                    $_SESSION['message'] = ['text' => "Password Changed Successfully", 'type' => "success"];
                } else {
                    $_SESSION['message'] = ['text' => "An Error Occurred During The Update", 'type' => "error"];
                }
            } else {
                $_SESSION['message'] = ['text' => "The New Password And Confirmation Do Not Match",  'type' => "error"];
            }
        } else {
            $_SESSION['message'] = ['text' => "The Current Password Is Incorrect", 'type' => "error"];
        }
    }
    header("Location: profile.php");
    exit();
}


?>
<?php

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$totalPrice = 0;

foreach ($_SESSION['cart'] as $item) {
    $itemTotal = floatval($item['price']) * intval($item['quantity']);
    $totalPrice += $itemTotal;
}
$discount = $_SESSION['discount'] ?? 0;
$finalPrice = $totalPrice - $discount;
if ($finalPrice < 0) {
    $finalPrice = 0;
}
include 'cartnav.php';
?>

<style>

</style>

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/profile.css" type="text/css">

</head>

<body>
    <!-- Page Preloder -->


    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__option">
            <div class="offcanvas__links">
                <a href="register.php">Sign in</a>
                <a href="#">FAQs</a>
            </div>
            <div class="offcanvas__top__hover">
                <span>Usd <i class="arrow_carrot-down"></i></span>
                <ul>
                    <li>USD</li>
                    <li>EUR</li>
                    <li>USD</li>
                </ul>
            </div>
        </div>
        <div class="offcanvas__nav__option">
            <a href="#" class="search-switch"><img src="img/icon/user.png" alt=""></a>
            <a href="#"><img src="img/icon/heart.png" alt=""></a>
            <a href="#"><img src="img/icon/cart.png" alt=""> <span>0</span></a>
            <div class="price">$0.00</div>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__text">
            <p>Free shipping, 30-day return or refund guarantee.</p>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7">
                        <div class="header__top__left">
                            <p>Free shipping, 30-day return or refund guarantee.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5">
                        <div class="header__top__right">
                            <div class="header__top__links">
                                <a href="register.php">Sign in</a>
                                <a href="#">FAQs</a>
                            </div>
                            <div class="header__top__hover">
                                <span>Usd <i class="arrow_carrot-down"></i></span>
                                <ul>
                                    <li>USD</li>
                                    <li>EUR</li>
                                    <li>USD</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo">
                        <a href="./index.php"><img src="img/logo-01.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li class="active"><a href="./index.php">Home</a></li>
                            <li><a href="./shop.php">Shop</a></li>
                            <li><a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="./about.html">About Us</a></li>
                                    <!-- <li><a href="./shop-details.html">Shop Details</a></li> -->
                                    <li><a href="./shopping-cart.php">Shopping Cart</a></li>
                                    <li><a href="./checkout.html">Check Out</a></li>
                                    <li><a href="./blog-details.html">Blog Details</a></li>
                                </ul>
                            </li>
                            <li><a href="./blog.php">Blog</a></li>
                            <li><a href="./contact.php">Contacts</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="header__nav__option">
                        <div class="profile-container">
                            <a href="#" class="profile" onclick="toggleProfileMenu(event)">
                                <img src="img/icon/user.png" alt="">
                            </a>
                            <div class="profile-menu" id="profileMenu">
                                <a href="profile.php" id="profile">Profile</a>

                                <a href="logout.php" id="login">Log out</a>
                            </div>
                        </div>
                        <a href="#"><img src="img/icon/heart.png" alt=""></a>
                        <a href="shopping-cart.php"><img src="img/icon/cart.png" width="25px" height="25px" alt=""> <span id="cart-count"><?= $totalQuantity ?></span></a>
                        <div class="price" id="cart-total"><?= number_format($finalPrice, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <div class="p_container">
        <h2>Profile Info</h2>
        <div class="p_info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
            <form method="post">
                <input type="text" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($user_phone) ?>">
                <input type="text" name="address" placeholder="Address" value="<?= htmlspecialchars($user_address) ?>">
                <button class="btn-profile" type="submit" name="save_info">save changes</button>
            </form>
              <a class="btn-profile" onclick="togglePasswordForm()">change password</a>
        <div id="passwordForm" class="password-form">
            <form method="post">
                <input type="password" name="current-password" placeholder="Current Password" required>
                <input type="password" name="new-password" placeholder="New Password" required>
                <input type="password" name="confirm-password" placeholder="Confirm Password" required>
                <button type="submit" name="change_password" class="btn-profile">save</button>
            </form>
        </div>
        <br>
        <br>
            <?php
            if (!empty($orders)):
                foreach ($orders as $order): ?>
                    <div class="order-box">
                        <p><strong>order_number:</strong> <?php echo $order['order_id']; ?></p>
                        <p><strong>order_status:</strong> <?php echo $order['status']; ?></p>
                        <p><strong>total price :</strong> $<?php echo $order['total_price']; ?></p>
                         <div class="products-container">
                          <?php foreach ($order['products'] as $product): ?>
                    <div class="product-thumb" id="product-thumb">
                        <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?> id='productImage'">
                         <p><?= $product['name'] ?></p>
                        <p>Qty: <?= $product['quantity'] ?></p>
                        <p>$<?= $product['price'] ?></p> 
                    </div>
                    <?php endforeach ?>
                    </div>
                    </div>
            <?php endforeach;?>
            <?php else: ?>
    <p>No orders yet</p>
          <?php  endif;
            ?>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    <script src='js/cart.js'></script>

</body>

</html>