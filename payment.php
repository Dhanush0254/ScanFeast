<?php
session_start();
$kou = $_SESSION['username'] ?? 'Guest';
$shik = ucwords($kou);

// Load the orders from the JSON file
$orderFile = 'orders.json';
$cart = [];
$total_price = 0;

if (file_exists($orderFile)) {
    $orders = json_decode(file_get_contents($orderFile), true);

    // Find the latest order for the current user
    for ($i = count($orders) - 1; $i >= 0; $i--) {
        if ($orders[$i]['user'] === $kou) {
            $cart = $orders[$i]['items'];
            $total_price = $orders[$i]['total'];
            break;
        }
    }
}

// Retrieve ttotal from session
$total_price_with_tax = $_SESSION['ttotal'] ?? ($total_price + 100);

// If payment is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Clear the cart session
    $_SESSION['cart'] = [];

    // Optionally, remove order from orders.json (clean up after payment)
    if (!empty($orders)) {
        foreach ($orders as $index => $order) {
            if ($order['user'] === $kou && $order['total'] == $total_price) {
                unset($orders[$index]);
                break;
            }
        }
        file_put_contents($orderFile, json_encode(array_values($orders), JSON_PRETTY_PRINT));
    }

    echo "<script>alert('Payment successful! Thank you for your order. \\nMr./Mrs. {$shik}'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>
<body>

<header>
    Payment Page
</header>

<div class="container">
    <h3>Order Summary</h3>
    <ul>
        <?php foreach ($cart as $item => $details): ?>
            <li>
                <?php echo htmlspecialchars($item); ?> x<?php echo $details['quantity']; ?> - ₹<?php echo $details['price'] * $details['quantity']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <h4>Tax: ₹100</h4>
    <h4>Total: ₹<?php echo $total_price_with_tax; ?></h4>
    <form action="" method="POST">
        <button type="submit">Pay</button>
    </form>
</div>

</body>
</html>
