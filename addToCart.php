
    <?php
        session_start();
    include 'cartnav.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
$product=[
    'id'=>$_POST['id'],
    'name'=>$_POST['name'],
    'price'=>$_POST['price'],
    'image'=>$_POST['image'],
    'quantity'=>1
];
   
}
if(!isset($_SESSION['cart'])){
    $_SESSION['cart']=[];
}
$found=false;
foreach($_SESSION['cart'] as $key=>$item){
    if($item['id']==$product['id']){
        $_SESSION['cart'][$key]['quantity']++;
        $found=true;
        break;
    }
    
}
if(!$found){
    $_SESSION['cart'][]=$product;
}
echo "the item added succesfully";

  exit;



    ?>