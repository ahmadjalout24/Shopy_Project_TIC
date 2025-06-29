<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "shopping";

$conn = mysqli_connect($host, $username, $password, $dbname);
if ($conn) {
    echo "connect";
} else {
    echo "not connect";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>responsive e-commerce website design</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!--custom css file link-->
    <link rel="stylesheet" href="./style.css">
</head>
<style>
    button[type="submit"][name="btn_search"] {
    background-color:rgb(208, 216, 224); 
    color: black; 
    border: none; 
    padding: 10px 20px;
    border-radius: 25px; 
    cursor: pointer; 
    font-size: 16px; 
    transition: background-color 0.3s ease, transform 0.2s ease; 
    display: inline-flex; 
    align-items: center; 
    gap: 8px; 
}

button[type="submit"][name="btn_search"]:hover {
    background-color:rgb(240, 22, 156); 
    transform: scale(1.05);
    color:white;
}

button[type="submit"][name="btn_search"]:active {
    background-color: #004080; 
    transform: scale(0.95);
}

button[type="submit"][name="btn_search"] i {
    font-size: 18px; 
}

</style>
<body>
   <!--header section-->
   <header>
       <a href="#" class="logo">Shopy<span>.</span></a>
       <nav class="navbar">
       <a class="active" href="index.php">Home</a>
       
           <a href="index.php #category">Categories</a>
           <a href="index.php #product1">Products</a>
           <a href="index.php #col">Contact</a>
       </nav>
       <div class="icons">
           <i class="fas fa-bars" id="menu-bars"></i>
           <i class="fas fa-search" id="search-icon"></i>
           <!-- start icon cart -->
           <?php
            $select_icon ="SELECT * FROM cart ";
            $result=mysqli_query($conn,$select_icon);
            if($result){
                $row_count =mysqli_num_rows($result);
            }else{
                $row_count = 0;
            }
            ?>
           <a href="cart.php" class="fas fa-shopping-cart"><span class="cart-count"><?php echo $row_count ?></span></a>
           <!-- end icon cart -->
           <a href="signup.php" class="fas fa-user"></a>
       </div>
   </header>

   <!--search form-->
   <form action="search.php" method="get" id="search-form">
       <input type="text" placeholder="search here..." name="search" class="search-input">
       <button type="submit" name="btn_search"><i class="fas fa-search"></i></button>
       <i class="fas fa-times" id="close"></i>
   </form>

<?php
if (isset($_GET['btn_search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM product WHERE prosection LIKE '%$search%' ";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo '<br><br><br><br>
        <section id="product1" class="section-p1">
            <h2>Featured Products</h2>
            <div class="pro-container">';
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
                <div class="pro">
                    <img src="uploads/img/' . $row['proimg'] . '">
                    <div class="des">
                        <h5 class="section">' . $row['prosection'] . '</h5>
                        <h4>$' . $row['proprice'] . '</h4><br><br>
                        <h6>for more details <a href="details.php">click here</a></h6><br>
                        
                        <!--quantity-->
                       <div class="qty_input">
                        <form action="cart.php?action=' . $row['id'] . '" method="post">
                        <button class="qty_count_mins">-</button>
                        <input type="number" id="" name="quantity" value="1" min="0" max="10">
                        <input type="hidden" name="product_id" value="' . $row['id'] . '">
                        <input type="hidden" name="h_name" value="' . $row['proname'] . '">
                        <input type="hidden" name="h_price" value="' . $row['proprice'] . '">
                        <input type="hidden" name="h_img" value="' . $row['proimg'] . '">
                        <button class="qty_count_add">+</button>
                    </div>
                </div>
                <a href="#">   <button class="addto-cart" type="submit" name="add" value="add_cart">
            <i class="fas fa-shopping-cart cart"></i>
        </button></a>
                </form> 
            </div> ';
        }
        
        echo '
            </div>
        </section>';
    } else {
        echo '<script>alert("The product you are looking for is currently not available");</script>';
    }
}
?>

<?php
include("files/footer.php");
?>
