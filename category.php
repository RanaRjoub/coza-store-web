<?php
$filter = $_POST['filter'] ?? '';
include 'db.php';
$sql = "SELECT  DISTINCT p.* FROM products p 
        JOIN product_filters pf ON p.product_id=pf.product_id
        JOIN filters f ON pf.filter_id = f.filter_id
        WHERE f.name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $filter); 

$stmt->execute();
$result = $stmt->get_result();

   
while ($product=$result->fetch_assoc()):
?>
  
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="product__item">
            
                <div class="product__item__pic set-bg" data-setbg="<?= htmlspecialchars($product['main_image'])?>" style="background-image: url('<?=htmlspecialchars($product['main_image'])?>');">

                <ul class="product__hover">
                    <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                    <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                    <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                </ul>
            </div>
            <div class="product__item__text">
                <h6><?= htmlspecialchars($product['name']) ?></h6>
                <form class="add-to-cart-form" method="post" action="addToCart.php">
                    <input type="hidden" name="id" value="<?= $product['product_id'] ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']) ?>">
                    <input type="hidden" name="price" value="<?= $product['price'] ?>">
                    <input type="hidden" name="image" value="<?= $product['main_image'] ?>">
                    <a class="add-cart"> <button type="submit" style="margin-right:20px ; border:0px ; width:120px ; height:50px" >Add to Cart</button></a>
                </form>

                <div class="rating">
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>    
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                </div>
                <h5><?= '$'. htmlspecialchars($product['price']) ?></h5>
                <div class="product__color__select">
                    <label for="pc-4"><input type="radio" id="pc-4"></label>
                    <label class="active black" for="pc-5"><input type="radio" id="pc-5"></label>
                    <label class="grey" for="pc-6"><input type="radio" id="pc-6"></label>
                </div>
            </div>
        </div>
    </div>
<?php endwhile;
 ?>
 <script src='js/cart.js'></script>