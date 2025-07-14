<?php
session_start();
if($_SERVER['REQUEST_METHOD']=='POST'){
    $coupon=$_POST['coupon']??'';
    if($coupon=='r1234'){
        $_SESSION['discount']=10;
        echo 'valid';
    }
    else{
        echo 'invalid';
    }
}
?>