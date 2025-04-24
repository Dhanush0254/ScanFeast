<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Menu Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <header>
        QR Code Menu System
        <span id="cart-icon" onclick="toggleCart()">🛒</span>
    </header>
    <div id="login-popup" style="display: <?php echo isset($_SESSION['username']) ? 'none' : 'block'; ?>">
        <h3>Login to Access Menu</h3>
        <form action="script.php" method="POST">
            <input type="text" name="username" id="username" placeholder="Enter your name" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
