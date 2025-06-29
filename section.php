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
    <title>Responsive E-Commerce Website Design</title>

    <!-- External CSS and Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="./style.css">
</head>
<style>
    button[type="submit"][name="btn_search"] {
        background-color: rgb(208, 216, 224);
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
        background-color: rgb(240, 22, 156);
        transform: scale(1.05);
        color: white;
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
            $select_icon = "SELECT * FROM cart";
            $result = mysqli_query($conn, $select_icon);
            if ($result) {
                $row_count = mysqli_num_rows($result);
            } else {
                $row_count = 0;
            }
            ?>
            <a href="cart.php" class="fas fa-shopping-cart"><span class="cart-count"><?php echo $row_count; ?></span></a>
            <!-- end icon cart -->

        </div>
    </header>

    <!-- Search Form -->
    <form action="search.php" method="get" id="search-form">
        <input type="text" placeholder="Search here..." name="search" class="search-input">
        <button type="submit" name="btn_search"><i class="fas fa-search"></i></button>
        <i class="fas fa-times" id="close"></i>
    </form>
    <br><br><br><br>
    <!-- Categories Section -->
    <div class="category">
        <h1>Our <span>Categories</span></h1>
        <div class="category_box">
            <?php
            $query = "SELECT * FROM section";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                   <a href='section.php?section={$row['Sectionname']}'>
                       <div class='profile'>
                           <img src='uploads/img/{$row['secimg']}' alt='{$row['Sectionname']}'>
                           <div class='info'>
                               <h2 class='name'>{$row['Sectionname']}</h2>
                           </div>
                       </div>
                   </a>
                   ";
                }
            } else {
                echo "<p>No categories found.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Main Content Section -->
    <main>
        <?php
        if (isset($_GET['section'])) {
            $section = $_GET['section'];
            $query = "SELECT * FROM product WHERE prosection='$section' ORDER BY ID DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<section id="product1" class="section-p1">';
                echo '<div class="pro-container">';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                   <div class="pro">
                       <a href="details.php?id=' . $row['id'] . '"><img src="uploads/img/' . $row['proimg'] . '" alt="' . $row['proname'] . '"></a>
                       <div class="des">
                           <a href="details.php?id=' . $row['id'] . '"><h5 class="section">' . $row['prosection'] . '</h5></a>
                           <a href="details.php?id=' . $row['id'] . '"><h4>$' . $row['proprice'] . '</h4></a><br><br>
                           <h6>For more details <a href="details.php">click here</a></h6><br>
                           <!-- Quantity Input -->
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
            </div> 
                   ';
                }
                echo '</div></section>';
            } else {
                echo '<script>alert("The product is currently not available");</script>';
            }
        } else {
            echo '<script>alert("No section selected.");</script>';
        }
        ?>
    </main>
    <script>
        // JavaScript لإدارة نموذج البحث
        document.addEventListener('DOMContentLoaded', function() {
            const searchIcon = document.getElementById('search-icon');
            const closeIcon = document.getElementById('close');
            const searchForm = document.getElementById('search-form');

            // إظهار نموذج البحث عند النقر على أيقونة البحث
            if (searchIcon) {
                searchIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchForm.classList.toggle('active');
                });
            }


            if (closeIcon) {
                closeIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchForm.classList.remove('active');
                });
            }
        });
    </script>
</body>

</html>