<?php
session_start();

if($_SERVER['REQUEST_METHOD']=='POST'){
   $id=$_POST['id'] ??'';
   $quantity=(int)$_POST['quantity']?? 0;
   if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as &$item){
        if($item['id']==$id){
            $item['quantity']=$quantity;
            break;
        }
    }
   }
   echo 'ok';
}

?>