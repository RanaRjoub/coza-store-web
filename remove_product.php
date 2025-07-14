<?php
session_start();
if(isset($_POST['id'])){
    $id=$_POST['id'];
    if(isset($_SESSION['cart'])&&!empty($_SESSION['cart'])){
        foreach($_SESSION['cart'] as$key=>$product){
            if((string)$id ==(string)$product['id']){
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart']=array_values($_SESSION['cart']);
                echo 'item deleted succesfully';
                exit;
            }
        }
    }
}


?>