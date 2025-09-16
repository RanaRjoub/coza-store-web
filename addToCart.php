
    <?php
    session_start();
    include 'db.php';
 $user_id=$_SESSION['user_id']??null;
 $product_id=$_POST['id'];
 $quantity=$_SESSION['quantity']??1;
 if($user_id){
 $sql="SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
 $stmt=$conn->prepare($sql);
 $stmt->bind_param("ii",$user_id ,$product_id);
 $stmt->execute();
 $result=$stmt->get_result();
 if($result->num_rows>0){
    $sql="UPDATE cart SET quantity=quantity+? WHERE user_id= ? AND product_id= ?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("iii",$quantity,$user_id,$product_id);
    $stmt->execute();

 }
 else{
    $sql="INSERT INTO cart (user_id,product_id,quantity) VALUES (?,?,?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("iii",$user_id,$product_id,$quantity);
    $stmt->execute();
 }
}

else{
if(!isset($_SESSION['cart'])){
    $_SESSION['cart']=[];}
    $found=false;
    foreach($_SESSION['cart'] as $item){
        if($item['product_id']==$product_id){
         $item['quantity']+=$quantity;
         $found=true;
         break;
        }
    }
    unset($item);
    if(!$found){
    $stmt=$conn->prepare("SELECT product_id ,name ,main_image ,price FROM products WHERE product_id= ?");
    $stmt->bind_param("i",$product_id);
    $stmt->execute();
    $res=$stmt->get_result();
    while($product=$res->fetch_assoc()){
        $product['quantity']=$quantity;
        $_SESSION['cart'][]=$product;
    }
    }

}
header("Location: shop.php");
exit;




    ?>