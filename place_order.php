<?php
session_start();
date_default_timezone_set('Asia/Kolkata'); // Set timezone to Kolkata

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

// Load .env only in local dev (optional)
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $user = $_SESSION['username'] ?? 'Guest';

    $orderData = [
        'user' => $user,
        'items' => $_SESSION['cart'],
        'total' => 0,
        'timestamp' => date("d M Y, h:i A"),
    ];

    // Calculate total
    $total = 0;
    foreach ($_SESSION['cart'] as $item => $details) {
        $total += $details['price'] * $details['quantity'];
    }
    $orderData['total'] = $total;
    $ttotal = $total + 100;
    $_SESSION['ttotal'] = $ttotal;

    // Store order in file
    $orderFile = 'orders.json';
    $existingOrders = file_exists($orderFile) ? json_decode(file_get_contents($orderFile), true) : [];
    $existingOrders[] = $orderData;
    file_put_contents($orderFile, json_encode($existingOrders, JSON_PRETTY_PRINT));

    // Create email message
    $message = "
    <h2 style='color:#333;'>🍽️ New Order Notification - SCANFEAST</h2>
    <p><strong>Customer:</strong> {$user}</p>
    <table style='border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;'>
        <thead>
            <tr style='background-color: #f2f2f2;'>
                <th style='border: 1px solid #ddd; padding: 8px;'>Item</th>
                <th style='border: 1px solid #ddd; padding: 8px;'>Price</th>
                <th style='border: 1px solid #ddd; padding: 8px;'>Quantity</th>
                <th style='border: 1px solid #ddd; padding: 8px;'>Subtotal</th>
            </tr>
        </thead>
        <tbody>
    ";

    foreach ($_SESSION['cart'] as $item => $details) {
        $itemName = htmlspecialchars($item);
        $price = (float)$details['price'];
        $quantity = (int)$details['quantity'];
        $subtotal = $price * $quantity;

        $message .= "
        <tr>
            <td style='border: 1px solid #ddd; padding: 8px;'>{$itemName}</td>
            <td style='border: 1px solid #ddd; padding: 8px;'>₹{$price}</td>
            <td style='border: 1px solid #ddd; padding: 8px;'>{$quantity}</td>
            <td style='border: 1px solid #ddd; padding: 8px;'>₹{$subtotal}</td>
        </tr>
        ";
    }

    $message .= "
        </tbody>
        <tfoot>
            <tr style='background-color:#f9f9f9;'>
                <td colspan='3' style='border: 1px solid #ddd; padding: 8px; text-align:right;'><strong>Tax</strong></td>
                <td style='border: 1px solid #ddd; padding: 8px;'><strong>₹100</strong></td>
            </tr>
            <tr>
                <td colspan='3' style='border: 1px solid #ddd; padding: 8px; text-align:right;'><strong>Total</strong></td>
                <td style='border: 1px solid #ddd; padding: 8px;'><strong>₹{$ttotal}</strong></td>
            </tr>
        </tfoot>
    </table>
    <p style='margin-top: 20px;'>🕒 <em>Order placed on: {$orderData['timestamp']}</em></p>
    <p style='color: #555;'>This is an automated order notification from the SCANFEAST system.</p>
    ";

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = getenv('MAIL_HOST');
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('MAIL_USERNAME');
        $mail->Password   = getenv('MAIL_PASSWORD');
        $mail->SMTPSecure = getenv('MAIL_ENCRYPTION');
        $mail->Port       = getenv('MAIL_PORT');

        $mail->setFrom(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'));
        $mail->addAddress(getenv('MAIL_TO_ADDRESS'), getenv('MAIL_TO_NAME'));

        $mail->isHTML(true);
        $mail->Subject = 'New Order from SCANFEAST';
        $mail->Body    = $message;

        $mail->send();
        $_SESSION['cart'] = [];
        echo "<script>alert('Thank you for your order! Email sent successfully.'); window.location.href='payment.php';</script>";
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
