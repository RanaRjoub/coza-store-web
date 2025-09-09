<?php
include 'db.php';
$sql="SELECT * FROM 
products_images pI ";

$result=mysqli_query($conn,$sql);
$images=[];
while($row=mysqli_fetch_assoc($result)){
    $images[]=$row;
}
mysqli_close($conn);

?>
