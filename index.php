<?php
session_start();
?>
<?php
include("files/header.php");

?>
<!--product section-->


<section id="product1" class="section-p1">
    <h2>Featured Products</h2>
    <p>Summer collection with new modern design & the latest products</p>
    <div class="pro-container">
        <?php
        $query = "SELECT * FROM product";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <div class="pro">
                <a href="details.php?id=<?php echo $row['id']?>"><img src="uploads/img/<?php echo $row['proimg']; ?>" alt="<?php echo $row['proname']; ?>"></a>
                <div class="des">
                <a href="section.php?section=<?php echo $row['prosection'];?>"><h5 class="section"><?php echo $row['prosection'];?></h5></a>
                <a href="details.php?id=<?php echo $row['id']?>"><h4>$<?php echo $row['proprice']; ?></a></h4><br><br>
                    <h6>for more details <a href="details.php">clilck here </a></h6><br>
                    
                    <!--quantity-->
                    <div class="qty_input">
                        <form action="cart.php?action<?php echo $row['id'];?>" method="post">
                        <button class="qty_count_mins">-</button>
                        <input type="number" id="" name="quantity" value="1" min="0" max="10">
                        <input type="hidden" name="product_id" value="<?php echo $row['id'] ;?> ">
                        <input type="hidden" name="h_name" value="<?php echo $row['proname'];?>">
                        <input type="hidden" name="h_price" value="<?php echo $row['proprice'];?>">
                        <input type="hidden" name="h_img" value="<?php echo $row['proimg'];?>">
                        <button class="qty_count_add">+</button>
                    </div>
                </div>
                <a href="#">   <button class="addto-cart" type="submit" name="add" value="add_cart">
            <i class="fas fa-shopping-cart cart"></i>
        </button></a>
                </form> 
            </div> 
        <?php
        }
        ?>
    </div>
</section>
  <?php
include("files/footer.php");
?>