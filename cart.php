<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "shopping";

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    echo "not connect";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة السلة</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="./style.css">

    <style>
        /*  تنسيقات CSS للدردشة  الذكية */
        .chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            z-index: 1000;
        }

        .chat-box {
            background: #fff0f6;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            transition: all 0.3s;
        }

        .chat-header {
            background: #ff6f91;
            color: white;
            padding: 15px;
            border-radius: 15px 15px 0 0;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #ffeef8;
            border-radius: 0 0 15px 15px;
        }

        .message {
            margin: 10px 0;
            padding: 10px 15px;
            border-radius: 20px;
            max-width: 80%;
        }

        .user-message {
            background: #ff6f91;
            color: white;
            margin-left: auto;
        }

        .bot-message {
            background: #f1f1f1;
            color: #333;
        }

        .chat-input {
            padding: 15px;
            background: #fff;
            border-top: 1px solid #eee;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
        }

        .chat-btn {
            background: #ff6f91;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: absolute;
            bottom: 0;
            right: 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }

        .chat-btn:hover {
            transform: scale(1.1);
        }

        /*  التنسيقات الخاصة ب السلة */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h3 {
            font-family: arial, sans-serif;
            color: black;
        }

        body {
            font-family: arial, sans-serif;
            background-color: #fff;
            color: #333;
        }

        .cart_container {
            direction: ltr;
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: rgba(0, 0, 0, 0.2);
        }

        .cont_head {
            padding: 5px;
            width: 100%;
            height: 100px;
            box-shadow: rgba(168, 168, 236);
        }

        .cont_head h1 {
            float: left;
            margin: 20px;
        }

        .cart_table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .cart_table th,
        td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .cart_table th {
            background-color: #d3d8e4;
        }

        .cart_table img {
            width: 70px;
            height: 70px;
        }

        .cart_table td input {
            width: 50px;
            padding: 5px;
            text-align: center;
            font-size: 20px;
        }

        .remove {
            background-color: #d3d8e4;
            color: black;
            border: none;
            padding: 10px 10px;
            cursor: pointer;
        }

        .remove:hover {
            background-color: #e84393;
            color: white;
        }

        .cart_total h6 {
            color: black;
            font-size: large;
        }

        .cart_total button {
            padding: 10px 40px;
            transition: transform 0.3s ease;
        }

        .cart_total button a {
            text-decoration: none;
            color: black;
        }

        .cart_total button:hover {
            transform: scale(1.2);
        }
    </style>
</head>

<body>

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

            <?php
            $select_icon = "SELECT * FROM cart";
            $result = mysqli_query($conn, $select_icon);
            $row_count = $result ? mysqli_num_rows($result) : 0;
            ?>
            <a href="cart.php" class="fas fa-shopping-cart"><span class="cart-count"><?php echo $row_count ?></span></a>
        </div>
    </header>

    <form action="search.php" method="get" id="search-form">
        <input type="text" placeholder="search here..." name="search" class="search-input">
        <button type="submit" name="btn_search"><i class="fas fa-search"></i></button>
        <i class="fas fa-times" id="close"></i>
    </form>

    <!-- كود الدردشة الذكية  -->
    <div class="chat-container">
        <div class="chat-btn" onclick="toggleChat()">🤖</div>
        <div class="chat-box" id="chatBox">
            <div class="chat-header">
                <h3>مساعد المتجر الذكي</h3>
            </div>
            <div class="chat-messages" id="chatMessages"></div>
            <div class="chat-input">
                <input type="text" id="userInput" placeholder="اكتب رسالتك هنا..." onkeypress="handleKeyPress(event)">
            </div>
        </div>
    </div>

    <script>
        let isChatOpen = false;

        const botResponses = {
            'مرحبا': 'مرحبًا! كيف يمكنني مساعدتك اليوم؟ 😊',
            'مساعدة': 'يمكنني مساعدتك في:<br>1. عرض أحدث الموديلات<br>2. مساعدة في اختيار المقاس<br>3. نصائح بالموضة<br>4. معلومات عن التخفيضات<br>اختر ما يناسبك!',
            'تخفيضات': 'لدينا حالياً تخفيضات على:<br>- مجموعة الصيف 2024 (حتى 50%)<br>- الملابس الرياضية (30% خصم)<br>- تشكيلة الأحذية الجديدة',
            'مقاس': 'لاختيار المقاس المناسب:<br>1. قم بقياس محيط الصدر<br>2. قارن مع جدول المقاسات لدينا<br>3. اختر مقاسًا أكبر إذا كنت تفضل الملابس الفضفاضة',
            'موضة': 'أهم صيحات هذا الموسم:<br>1. ألوان الباستيل الناعمة<br>2. الجينز الواسع<br>3. البلazers الرياضية<br>4. الأحذية ذات الكعب المربع',
            'أقسام المتجر': 'لدينا أقسام متنوعة تشمل الملابس النسائية، الأطفال، إكسسوارات الهواتف، الأبواب الرياضية، والشنط.',
            'طرق الدفع': 'نقبل الدفع عبر البطاقات الائتمانية، PayPal، والتحويل البنكي.',
            'سياسة الشحن': 'نوفر شحنًا مجانيًا للطلبات فوق مبلغ معين، والشحن السريع لجميع الطلبات.',
            'تفاعل': 'أنا هنا لمساعدتك! يمكنك سؤالي عن أي شيء يتعلق بالمتجر أو المنتجات.',
            'ذكاء': 'أنا مصمم لأكون مساعدك الذكي، أستطيع فهم احتياجاتك ومساعدتك في اتخاذ القرار الصحيح!',
            'سؤال عام': 'نحن هنا لتقديم أفضل تجربة تسوق لك، لا تتردد في طرح أي سؤال.',
            'أسئلة شائعة': 'يمكنك العثور على إجابات لأسئلتك الشائعة في قسم الأسئلة الشائعة في موقعنا.',
            'استرجاع المنتجات': 'يمكنك استرجاع المنتجات خلال 30 يومًا من الشراء، بشرط أن تكون بحالتها الأصلية.',
            'default': 'عذرًا، لم أفهم سؤالك. يمكنك طرح أسئلة عن:<br>- التخفيضات<br>- المقاسات<br>- صيحات الموضة<br>- طرق الدفع'
        };

        function toggleChat() {
            const chatBox = document.getElementById('chatBox');
            isChatOpen = !isChatOpen;
            chatBox.style.display = isChatOpen ? 'flex' : 'none';
        }

        function handleKeyPress(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        }

        async function sendMessage() {
            const input = document.getElementById('userInput');
            const message = input.value.trim();
            if (!message) return;

            // إضافة رسالة المستخدم
            addMessage(message, 'user');

            // معالجة الرسالة وإضافة الرد
            setTimeout(async () => {
                const response = await getBotResponse(message);
                addMessage(response, 'bot');
            }, 1000);

            input.value = '';
        }

        async function getBotResponse(message) {
            message = message.toLowerCase();

            // تحقق من الردود المبرمجة مسبقًا
            for (const key in botResponses) {
                if (message.includes(key)) {
                    return botResponses[key];
                }
            }

            // إذا لم يكن هناك رد مبرمج، استخدم واجهة برمجة التطبيقات
            return await fetchBotResponseFromAPI(message);
        }

        async function fetchBotResponseFromAPI(message) {
            const response = await fetch("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=GEMINI_API_KEY", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    "contents": [{
                        "parts": [{
                            "text": message
                        }]
                    }]
                })
            });

            if (!response.ok) {
                return 'عذرًا، حدث خطأ أثناء معالجة طلبك.';
            }

            const data = await response.json();
            return data.contents[0].parts[0].text || 'عذرًا، لم أستطع فهم سؤالك.';
        }

        function addMessage(text, sender) {
            const messagesDiv = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}-message`;
            messageDiv.innerHTML = text;
            messagesDiv.appendChild(messageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
    </script>

    <div class="cart_container">
        <div class="cont_head">
            <h1>Shopping Cart</h1>
        </div>
        <table class="cart_table">
            <tr>
                <th>صورة المنتج</th>
                <th>معرف المنتج</th>
                <th>اسم المنتج</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>الإجمالي</th>
                <th>تحديث</th>
                <th>حذف</th>
            </tr>
            <?php
            $query = "SELECT * FROM cart";
            $result = mysqli_query($conn, $query);
            $total = 0;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <tr>
                        <td><img src="uploads/img/<?php echo $row['img']; ?>"></td>
                        <td>
                            <h3><?php echo $row['product_id']; ?></h3>
                        </td>
                        <td>
                            <h3><?php echo $row['name']; ?></h3>
                        </td>
                        <td><input type="number" value="<?php echo $row['quantity']; ?>" min="1"></td>
                        <td>
                            <h3>$<?php echo $row['price']; ?></h3>
                        </td>
                        <td>
                            <h3>$<?php echo number_format($row['quantity'] * $row['price'], 2); ?></h3>
                        </td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button class="remove" type="submit" name="delete_c">حذف<i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" required min="1">
                                <button class="remove" type="submit" name="update_quantity">تحديث<i class="fa-solid fa-pen-to-square"></i></button>
                            </form>
                        </td>
                <?php
                    $total += $row['quantity'] * $row['price'];
                }
            }
                ?>
                    </tr>
        </table>
        <div class="cart_total">
            <h6><span id="total">الإجمالي:</span>$<?php echo number_format($total, 2); ?></h6><br>
            <button type="submit" class="remove"><a href="login.php">
                    <h2>طلب</h2>
                </a></button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>