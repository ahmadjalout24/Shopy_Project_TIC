<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "shopping";

// Establish database connection
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process order form submission
if(isset($_POST['order_btn'])){
    // Sanitize and validate input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $flat = mysqli_real_escape_string($conn, $_POST['flat']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $pin_code = mysqli_real_escape_string($conn, $_POST['pin_code']);

    // Get cart items
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart`");
    $price_total = 0;
    $product_name = array();
    
    if(mysqli_num_rows($cart_query) > 0){
        while($product_item = mysqli_fetch_assoc($cart_query)){
            $product_name[] = htmlspecialchars($product_item['name']) .'('. $product_item['quantity'] .')';
            $product_price = $product_item['price'] * $product_item['quantity'];
            $price_total += $product_price;
        }
    }
    
    $total_product = implode(', ', $product_name);
    
    // Insert order into database
    $detail_query = mysqli_query($conn, "INSERT INTO `order` 
        (name, number, email, method, flat, street, city, country, pin_code, total_products, total_price) 
        VALUES('$name', '$number', '$email', '$method', '$flat', '$street', '$city',  '$country', '$pin_code', '$total_product', '$price_total')") 
        or die('Query failed: ' . mysqli_error($conn));
    
    if($cart_query && $detail_query){
        // Display order confirmation
        echo "
        <div class='order-message-container'>
            <div class='message-container'>
                <h3>Thank you for shopping!</h3>
                <div class='order-detail'>
                    <span>".$total_product."</span>
                    <span class='total'>Total: $".number_format($price_total, 2)."</span>
                </div>
                <div class='customer-details'>
                    <p>Your name: <span>".htmlspecialchars($name)."</span></p>
                    <p>Your number: <span>".htmlspecialchars($number)."</span></p>
                    <p>Your email: <span>".htmlspecialchars($email)."</span></p>
                    <p>Your address: <span>".htmlspecialchars($flat).", ".htmlspecialchars($street).", ".htmlspecialchars($city).", ".htmlspecialchars($country)." , ".htmlspecialchars($pin_code)."</span></p>
                    <p>Payment method: <span>".htmlspecialchars($method)."</span></p>
                    
                </div>
                <a href='index.php' class='btn'>Continue shopping</a>
            </div>
        </div>
        ";
        
        // Clear the cart after successful order
        mysqli_query($conn, "DELETE FROM `cart`") or die('Query failed: ' . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="./style.css">
    <title>Checkout</title>
    <style>
        .checkout-form form {
            padding: 2rem;
            border-radius: .5rem;
            background-color: var(--bg-color);
        }
        .checkout-form form .flex {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .checkout-form form .flex .inputbox {
            flex: 1 1 40rem;
        }
        .checkout-form form .flex .inputbox span {
            font-size: 2rem;
            color: var(--black);
        }
        .checkout-form form .flex .inputbox input,
        .checkout-form form .flex .inputbox select {
            width: 100%;
            background-color: white;
            font-size: 1.7rem;
            color: black;
            border-radius: .5rem;
            margin: 1rem 0;
            padding: 1.2rem 1.4rem;
            text-transform: none;
              box-shadow: var(--box-shadow);
            border: 1px solid #aaa;
        }
        .heading {
            display: flex;
            font-size: 3rem;
            align-items: center;
            justify-content: space-around;
        }
        .display-order {
            max-width: 50rem;
            background-color: white;
            border-radius: .5rem;
            text-align: center;
            padding: 1.5rem;
            margin: 0 auto;
            margin-bottom: 2rem;
            box-shadow: var(--box-shadow);
            border: 1px solid #aaa;
        }
        .display-order span {
            display: inline-block;
            border-radius: .5rem;
            background-color: #fff;
            padding: .5rem 1rem;
            font-size: 1rem;
            color: black;
            margin: .5rem;
        }
        .display-order span.grand-total {
            width: 100%;
            background-color: var(--pink);
            color: white;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        .order-message-container {
            position: fixed;
            top: 0;
            left: 0;
            min-height: 100vh;
            overflow-y: scroll;
            overflow-x: hidden;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1100;
            background-color: var(--dark-bg);
            width: 100%;
        }
        .order-message-container::-webkit-scrollbar {
            width: 1rem;
        }
        .order-message-container::-webkit-scrollbar-track {
            background-color: var(--dark-bg);
        }
        .order-message-container::-webkit-scrollbar-thumb {
            background-color: blue;
        }
        .order-message-container .message-container {
             border: 1px solid black;
            width: 50rem;
            background-color: white;
            border-radius: .8rem;
            padding: 2rem;
            text-align: center;
        }
        .order-message-container .message-container h3 {
            font-size: 2.5rem;
            color: black;
        }
        .order-message-container .message-container .order-detail {
            background-color: #fff;
            border-radius: .5rem;
            padding: 1rem;
            margin: 1rem 0;
        }
        .order-message-container .message-container .order-detail span {
            background-color: white;
            border-radius: .5rem;
            color:black;
            font-size: 2rem;
            display: inline-block;
            padding: 1rem 1.5rem;
            margin: 1rem;
        }
        .order-message-container .message-container .order-detail span.total {
            display: block;
            background-color: var(--pink);
            color: white; 
        }
        .order-message-container .message-container .customer-details {
            margin: 1.5rem 0;
        }
        .order-message-container .message-container .customer-details p {
            padding: 1rem 0;
            font-size: 2rem;
            color: black;
        }
        .order-message-container .message-container .customer-details p span {
            color: black;
            padding: 0 .5rem;
            text-transform: none;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <a href="#" class="logo">Shopy<span>.</span></a>
        <nav class="navbar">
            <a class="active" href="index.php">Home</a>
            <a href="index.php#category">Categories</a>
            <a href="index.php#product1">Products</a>
            <a href="index.php#col">Contact</a>
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
           
        </div>
    </header>

    <!-- Search Form -->
    <form action="search.php" method="get" id="search-form">
        <input type="text" placeholder="Search here..." name="search" class="search-input">
        <button type="submit" name="btn_search"><i class="fas fa-search"></i></button>
        <i class="fas fa-times" id="close"></i>
    </form>

    <?php
    if (isset($_GET['btn_search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $query = "SELECT * FROM `product` WHERE `prosection` LIKE '%$search%'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            echo '<section id="product1" class="section-p1">
                <h2>Search Results</h2>
                <div class="pro-container">';
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="pro">
                    <img src="uploads/img/'.htmlspecialchars($row['proimg']).'">
                    <div class="des">
                        <h5 class="section">'.htmlspecialchars($row['prosection']).'</h5>
                        <h4>$'.htmlspecialchars($row['proprice']).'</h4><br><br>
                        <h6>For more details <a href="details.php">click here</a></h6><br>
                        <div class="qty_input">
                            <form action="cart.php?action='.htmlspecialchars($row['id']).'" method="post">
                                <button class="qty_count_mins">-</button>
                                <input type="number" name="quantity" value="1" min="1" max="10">
                                <input type="hidden" name="product_id" value="'.htmlspecialchars($row['id']).'">
                                <input type="hidden" name="h_name" value="'.htmlspecialchars($row['proname']).'">
                                <input type="hidden" name="h_price" value="'.htmlspecialchars($row['proprice']).'">
                                <input type="hidden" name="h_img" value="'.htmlspecialchars($row['proimg']).'">
                                <button class="qty_count_add">+</button>
                                <button type="submit" name="add" class="addto-cart">
                                    <i class="fas fa-shopping-cart cart"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>';
            }
            
            echo '</div></section>';
        } else {
            echo '<script>alert("No products found matching your search.");</script>';
        }
    }
    ?>

    <!-- Checkout Form -->
    <div class="container">
        <section class="checkout-form">
            <br><br><br><br><br>
            <h1 class="heading">Complete Your Order</h1>
            <form action="" method="post">
                <div class="display-order">
                    <?php
                    $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
                    $total = 0;
                    $grand_total = 0;
                    if(mysqli_num_rows($select_cart) > 0){
                        while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                            $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                            $grand_total = $total += $total_price;
                    ?>
                    <span><?= htmlspecialchars($fetch_cart['name']) ?>(<?= $fetch_cart['quantity'] ?>)</span>
                    <?php
                        }
                    } else {
                        echo "<div class='display-order'><span>Your cart is empty!</span></div>";
                    }
                    ?>
                    <span class="grand-total">Grand Total: $<?= number_format($grand_total, 2) ?></span>
                </div>
                
                <div class="flex">
                    <div class="inputbox">
                        <span>Your name</span>
                        <input type="text" placeholder="Enter your name" name="name" required>
                    </div> 
                    <div class="inputbox">
                        <span>Your number</span>
                        <input type="number" placeholder="Enter your number" name="number" required>
                    </div>
                    <div class="inputbox">
                        <span>Your email</span>
                        <input type="email" placeholder="Enter your email" name="email" required>
                    </div>
                    <div class="inputbox">
                        <span>Payment method</span>
                        <select name="method" required>
                            <option value="cash on delivery" selected>Cash on delivery</option>
                            <option value="credit card">Credit card</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <div class="inputbox">
                        <span>Address line 1</span>
                        <input type="text" placeholder="Flat/House no." name="flat" required>
                    </div>
                    <div class="inputbox">
                        <span>Address line 2</span>
                        <input type="text" placeholder="Street name" name="street" required>
                    </div>
                    <div class="inputbox">
                        <span>City</span>
                        <input type="text" placeholder="City name" name="city" required>
                    </div>
                   
                    <div class="inputbox">
                        <span>Country</span>
                        <input type="text" placeholder="Country name" name="country" required>
                    </div>
                    <div class="inputbox">
                        <span>PIN code</span>
                        <input type="text" placeholder="Postal code" name="pin_code" required>
                    </div>
                </div>
                <input type="submit" value="Order Now" name="order_btn" class="btn">
            </form>
        </section>
    </div>  

    <script src="script.js"></script> 
</body>
</html>

<?php
mysqli_close($conn);
?>