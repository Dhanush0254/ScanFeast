<?php
session_start();

$menu = [
    'healthy' => [
        ['name' => 'Salad', 'price' => 60],
        ['name' => 'Fresh Juice', 'price' => 40],
        ['name' => 'Veg Meals', 'price' => 80]
    ],
    'chinese' => [
        ['name' => 'Noodles', 'price' => 70],
        ['name' => 'Fried Rice', 'price' => 70],
        ['name' => 'Chicken Manchurian Rice', 'price' => 90]
    ],
    'Biryani' => [
        ['name' => 'Chicken Dum Biryani', 'price' => 150],
        ['name' => 'Mutton Biryani', 'price' => 250],
        ['name' => 'Fish Biryani', 'price' => 200]
    ],
    'starters' => [
        ['name' => 'Chicken Lollipop', 'price' => 100],
        ['name' => 'Paneer Tikka', 'price' => 80],
        ['name' => 'Gobi Manchurian', 'price' => 70]
    ],
    'desserts' => [
        ['name' => 'GulabJamun(2 pcs)', 'price' => 60],
        ['name' => 'Double ka Meta', 'price' => 50],
        ['name' => 'Ice Cream', 'price' => 5]
    ],
    'beverages' => [
        ['name'=> 'Thumbsup', 'price'=> 20],
        ['name' => 'Coffee', 'price' => 20],
        ['name' => 'Green Tea', 'price' => 20],
        ['name' => 'Smoothie', 'price' => 30]
    ]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'])) {
        $_SESSION['username'] = htmlspecialchars($_POST['username']);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $item = $_POST['item'];
        $price = (float)$_POST['price'];
        $quantity = (int)$_POST['quantity'];

        if (!isset($_SESSION['cart'][$item])) {
            $_SESSION['cart'][$item] = ['price' => $price, 'quantity' => $quantity];
        } else {
            $_SESSION['cart'][$item]['quantity'] += $quantity;
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
        $item = $_POST['item'];
        unset($_SESSION['cart'][$item]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR Code Menu System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>
<body>

<header>
    QR Code Menu System
    <span id="cart-icon" onclick="toggleCart()">🛒</span>
</header>

<!-- Login -->
<div id="login-popup" style="display: <?php echo isset($_SESSION['username']) ? 'none' : 'block'; ?>">
    <h3>Login to Access Menu</h3>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Enter your name" required>
        <button type="submit">Login</button>
    </form>
</div>

<!-- Menu -->
<div class="container" style="display: <?php echo isset($_SESSION['username']) ? 'flex' : 'none'; ?>">
    <div id="categories">
        <h3>Categories</h3>
        <ul>
            <?php foreach ($menu as $category => $items): ?>
                <li onclick="showProducts('<?php echo $category; ?>')"><?php echo ucfirst($category); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="products"></div>
</div>

<!-- Cart -->
<div id="cart-section" style="display: <?php echo isset($_SESSION['username']) ? 'block' : 'none'; ?>">
    <h3>Cart 🛒</h3>
    <ul id="cart-items">
        <?php if (isset($_SESSION['cart'])): ?>
            <?php foreach ($_SESSION['cart'] as $item => $details): ?>
                <li>
                    <?php echo htmlspecialchars($item); ?> x<?php echo $details['quantity']; ?> - ₹<?php echo $details['price'] * $details['quantity']; ?>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="remove_from_cart">
                        <input type="hidden" name="item" value="<?php echo htmlspecialchars($item); ?>">
                        <button type="submit" class="remove-button">❌</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    <?php
    $total_price = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item => $details) {
            $total_price += $details['price'] * $details['quantity'];
        }
    }
    ?>
    <h4>Total: ₹<span id="total-price"><?php echo $total_price; ?></span></h4>

    <form action="place_order.php" method="POST">
        <button class="cart-button" type="submit">Place Order</button>
    </form>
</div>

<!-- JavaScript -->
<script>
function showProducts(category) {
    const productsSection = document.getElementById("products");
    productsSection.innerHTML = "";

    const menu = <?php echo json_encode($menu); ?>;

    menu[category].forEach(product => {
        const name = product.name;
        const price = product.price;
        const productCard = document.createElement("div");
        productCard.className = "product-card";
        productCard.innerHTML = `
            <h4>${name} - ₹${price}</h4>
            <div class="quantity-control">
                <button type="button" onclick="changeQuantity('${name}', -1)">-</button>
                <span id="qty-${name}">1</span>
                <button type="button" onclick="changeQuantity('${name}', 1)">+</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_to_cart">
                <input type="hidden" name="item" value="${name}">
                <input type="hidden" name="price" value="${price}">
                <input type="hidden" name="quantity" id="hidden-qty-${name}" value="1">
                <button type="submit">Add to Cart</button>
            </form>
        `;
        productsSection.appendChild(productCard);
    });
}

let quantities = {};

function changeQuantity(item, delta) {
    if (!quantities[item]) quantities[item] = 1;
    quantities[item] = Math.max(1, quantities[item] + delta);
    document.getElementById(`qty-${item}`).textContent = quantities[item];
    document.getElementById(`hidden-qty-${item}`).value = quantities[item];
}

function toggleCart() {
    const cartSection = document.getElementById("cart-section");
    cartSection.style.display = cartSection.style.display === "none" ? "block" : "none";
}
</script>

</body>
</html>
