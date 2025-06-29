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

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>responsive e-commerce website design</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!--custom css file link-->
    <link rel="stylesheet" href="./style.css">
</head>

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
            $select_icon = "SELECT * FROM cart ";
            $result = mysqli_query($conn, $select_icon);
            if ($result) {
                $row_count = mysqli_num_rows($result);
            } else {
                $row_count = 0;
            }
            ?>
            <a href="cart.php" class="fas fa-shopping-cart"><span class="cart-count"><?php echo $row_count ?></span></a>
            <!-- end icon cart -->

        </div>
    </header>

    <!--search form-->
    <form action="search.php" method="get" id="search-form">
        <input type="text" placeholder="search here..." name="search" class="search-input">
        <button type="submit" name="btn_search"><i class="fas fa-search"></i></button>
        <i class="fas fa-times" id="close"></i>
    </form>
    <br> <br><br><br><br><br><br><br><br><br><br>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Details page</title>
    </head>
    <style>
        main {
            display: flex;
            flex-wrap: wrap;
        }

        .container {
            width: 90%;
            height: auto;
            margin: 20px auto;
            border-radius: 8px;
        }

        .product_img {
            float: left;
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;

        }

        .product_img img {
            width: 400px;
            height: 400px;
            margin-left: 40px;
            margin-bottom: 20px;
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 1.0)
        }

        .product_info {
            float: right;
            width: 400px;
            height: 400px;
            text-align: center;
            font-size: 20px;
            margin-right: 50px;
            padding: 10px 10px;
            margin-top: 30px;

        }

        .product_title {
            margin: 10px 0;
        }

        .product_price {
            color: #e84393;
            margin: 10px 0;
        }

        .product_description {
            font-size: 16px;
            line-height: 1.5;
        }

        .add_cart {

            border: 1px solid #222;
            border-radius: 30px;
            text-align: center;
            position: absolute;
            font-weight: 800;
            line-height: 50px;
            bottom: 40px;
            width: 150px;
            height: 60px;

            margin-left: 600px;
            background-color: #fff;


        }

        .add_cart:hover {
            background-color: #e84393;
            color: white;
        }

        .qty_input {
            margin-left: 30px;
            padding: 10px 100px;
        }

        .recently_added {
            float: right;
            width: 30%;
            margin-top: 30px;
            border-radius: 8px;
            padding: 10px 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 1.0);
        }

        .added_img img {
            float: right;
            margin: 10px 10px;
            width: 70px;
            height: 70px;
            margin-right: 5px;
            border-radius: 10px;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 1.0)
        }

        .comment_info {
            float: left;
            margin: 20px 10px;
            width: 100%;
            height: auto;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 1.0)
        }

        h5 {


            font-size: 20px;
            margin-top: 20;
            text-align: center;
            color: black;
        }

        textarea {
            text-align: left;
            width: 80%;
            margin-top: 20px;
            margin-left: 50px;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            height: 50px;
        }

        .add_comment {
            width: 100px;
            height: 35px;
            margin: 20px 100px;
            padding: 10px 10px;
            background-color: #fff;
            border: 1px solid #222;
            border-radius: 30px;
            text-align: center;
            font-weight: 800;


        }

        .add_comment:hover {
            background-color: #e84393;
            color: white;
        }

        .comments {
            margin-top: 10px;
        }

        .comment {
            color: black;
            font-size: larger;
            margin: 5px 5px;
            text-align: left;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
            overflow: scroll;
            text-overflow: ellipsis;

        }

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

        .username {
            padding: 4px 5px;
            text-align: left;
            color: black;
            font-size: 20px;
        }
    </style>

    <body>
        <main>
            <?php
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (isset($_GET['id'])) {
                $query = "SELECT * FROM product WHERE id='$id'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
            }
            ?>
            <!--start container -->
            <div class="container">
                <div class="product_img">
                    <img src="uploads/img/<?php echo $row['proimg']; ?>" />
                </div><!-- img -->
                <!-- start information-->
                <div class="product_info">
                    <h1 class="product_title"><?php echo $row['prosection']; ?></h1>
                    <h2 class="product_price"><?php echo $row['proprice']; ?>$ &nbsp; price</h2>
                    <a href="section.php?section=<?php echo $row['prosection']; ?>">
                        <h5 class="section"><?php echo $row['prosection']; ?></h5>
                    </a>
                    <h3><?php echo $row['prosize']; ?> size</h3><br>
                    <h4 class="product_description">details:</h4>
                    <p><?php echo $row['prodescrip']; ?></p>
                    <!--quantity-->
                    <div class="qty_input">
                        <form action="cart.php?action<?php echo $row['id']; ?>" method="post">
                            <button class="qty_count_mins">-</button>
                            <input type="number" id="" name="quantity" value="1" min="0" max="10">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?> ">
                            <input type="hidden" name="h_name" value="<?php echo $row['proname']; ?>">
                            <input type="hidden" name="h_price" value="<?php echo $row['proprice']; ?>">
                            <input type="hidden" name="h_img" value="<?php echo $row['proimg']; ?>">
                            <button class="qty_count_add">+</button>
                    </div>
                </div>
                <a href="#">
                    <button class="add_cart" type="submit" name="add">add to cart</button>
                </a>

                </form>
            </div>
            <?php

            ?>
            </div>
            <!--submit-->
            </div><!--product_info-->

            </div><!--container-->
        </main>
        <hr>
        <!--start recently added-->
        <div class="container">
            <div class="recently_added">
                <h4>Recently added products</h4>
                <?php
                $query = "SELECT * FROM product WHERE id != '$id' ORDER BY rand() LIMIT 4";
                $result = mysqli_query($conn, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="added_img">
                            <a href="details.php?id=<?php echo $row['id']; ?>">
                                <img src="uploads/img/<?php echo $row['proimg']; ?>" />
                            </a>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>No recently added products found.</p>";
                }
                ?>
            </div>
        </div>

        <div class="comment_info">
            <!-- start comment-->
            <?php
            // add comment
            @$comment = $_POST['comment'];
            @$add_comment = $_POST['add_comment'];
            if (isset($add_comment)) {
                $query = "INSERT INTO comments (comment) VALUES('$comment')";
                $result = mysqli_query($conn, $query);
            }
            //get information from db
            $query = "SELECT * FROM comments ";
            $result = mysqli_query($conn, $query);

            ?>
            <h5>Please, rate the previous product</h5>
            <form action="" method="POST">
                <textarea name="comment" placeholder="write a comment" required></textarea>
                <button class="add_comment" type="submit" name="add_comment">submit</button>
            </form>

            <h5>Comments</h5>
            <div class="comments">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "  <div class='username'> " . $row['username'] . "</div>";
                        echo "  <div class='comment'> " . $row['comment'] . "</div>";
                    }
                } else {
                    echo "There are no comments";
                }
                ?>


            </div><!--comments-->
        </div><!--comment_info-->
        </div>


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