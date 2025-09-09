<?php
include 'db.php';
$sql="SELECT p.* , c.category_name ,p.count
 FROM products p
 JOIN categories c ON p.category_id=c.category_id

";
$result=mysqli_query($conn,$sql);

$products=[];
while($row=mysqli_fetch_assoc($result)){
    $products[]=$row;

}


$sql2="SELECT p.* , c.category_name ,p.count
 FROM products p
 JOIN categories c ON p.category_id=c.category_id
 ORDER BY count desc LIMIT 4
";
$result=mysqli_query($conn,$sql2);

$products2=[];
while($row=mysqli_fetch_assoc($result)){
    $products2[]=$row;

}

$sql3="SELECT p.*  ,c.category_name
From products p 
JOIN product_filters pf ON p.product_id=pf.product_id
JOIN categories c ON p.category_id = c.category_id ";
$result=mysqli_query($conn,$sql3);

$products3=[];
while($row=mysqli_fetch_assoc($result)){
    $products3[]=$row;
}
mysqli_close($conn);
?>