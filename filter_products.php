<?php
include 'products.php'; 
$filter = $_POST['filter'] ?? '';
$filterProducts = $products;

if ($filter == 'low_high') {
    usort($filterProducts, function ($a, $b) {
        return $a['price'] - $b['price'];
    });
} elseif ($filter == 'high_low') {
    usort($filterProducts, function ($a, $b) {
        return $b['price'] - $a['price'];
    });
}


foreach ($filterProducts as $product):
?>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="product__item">
            
                <div class="product__item__pic set-bg" data-setbg="<?= htmlspecialchars($product['image'])?>" style="background-image: url('<?=htmlspecialchars($product['image'])?>');">

                <ul class="product__hover">
                    <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                    <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>Compare</span></a></li>
                    <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                </ul>
            </div>
            <div class="product__item__text">
                <h6><?= htmlspecialchars($product['name']) ?></h6>
                <form class="add-to-cart-form" method="post" action="addToCart.php">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']) ?>">
                    <input type="hidden" name="price" value="<?= $product['price'] ?>">
                    <input type="hidden" name="image" value="<?= $product['image'] ?>">
                    <a class="add-cart"> <button type="submit">Add to Cart</button></a>
                </form>

                <div class="rating">
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>    
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                </div>
                <h5><?= htmlspecialchars($product['price']) ?></h5>
                <div class="product__color__select">
                    <label for="pc-4"><input type="radio" id="pc-4"></label>
                    <label class="active black" for="pc-5"><input type="radio" id="pc-5"></label>
                    <label class="grey" for="pc-6"><input type="radio" id="pc-6"></label>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
