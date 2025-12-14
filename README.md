````markdown
# ScanFeast 🍔📱

Welcome to **ScanFeast**!

**ScanFeast** is a **Contactless QR Code Menu & Ordering System** designed to make dining smoother, faster, and safer.

Waiting for physical menus and flagging down waiters can be a hassle. ScanFeast solves this by letting customers control the entire dining experience from their own phones — from picking a burger to paying the bill.

> **Status:** 🚀 Ready for Deployment  
> **Demo:** [Insert Link Here]

---

## 💡 Key Features

- **⚡ Instant Access:** No app download required. Just scan the QR code and log in.
- **🛒 Dynamic Cart:** Add or remove items instantly with a smooth, interactive UI.
- **📄 No Database Headaches:** Uses a clever **JSON-based** storage system (`orders.json`) to track orders, making it lightweight and easy to host anywhere.
- **📧 Automated Receipts:** Integrated with **PHPMailer** to send digital receipts directly to the customer's email after payment.
- **💳 Payment Ready:** Includes a dedicated payment gateway interface (`payment.php`).

---

## 🛠️ The Tech Stack

- **Backend:** Core PHP (No heavy frameworks, just speed)
- **Frontend:** HTML5, CSS3 (Custom `style2.css` design)
- **Data Storage:** JSON (Flat-file architecture)
- **Email Service:** PHPMailer
- **Containerization:** Docker

---

## 🚀 How to Run It

You have two easy ways to get this running.

### Option 1: Docker (Recommended) 🐳

A `Dockerfile` is included so you can spin this up instantly without configuring XAMPP/WAMP.

1. **Clone the repository**
   ```bash
   git clone https://github.com/dhanush0254/ScanFeast.git
   cd ScanFeast
````

2. **Build & Run**

   ```bash
   docker build -t scanfeast .
   docker run -p 8080:80 scanfeast
   ```

3. Open `http://localhost:8080` and start ordering!

---

### Option 2: Manual Setup (XAMPP/WAMP)

1. Download the project and place it in your `htdocs` or `www` folder.
2. **Install Dependencies**
   Make sure Composer is installed, then run:

   ```bash
   composer install
   ```
3. Start your Apache server and navigate to:

   ```
   http://localhost/ScanFeast
   ```

---

## 📂 Project Structure

```text
/ScanFeast
│
├── index.php            # Login & Main Entry Point
├── script.php           # Form Handling Logic
├── place_order.php      # Order Processing
├── payment.php          # Payment Gateway Interface
│
├── /vendor              # Composer Dependencies (PHPMailer)
├── composer.json        # Dependency Manager
├── Dockerfile           # Docker Configuration
│
├── orders.json          # Data Storage (Simulated Database)
└── style2.css           # Custom Styling
```

---

## 👥 The Team

Built with ❤️ by:

* **Dhanush**

---

## ⚠️ Just a Heads Up (Disclaimer)

**This project is for educational and portfolio use only.**

ScanFeast demonstrates a flat-file system using `orders.json` instead of a traditional database. While this approach is fast and simple for small-scale projects, it is **not intended for large, high-traffic restaurant systems**. For production use, a proper database solution such as **MySQL** or **PostgreSQL** is recommended.

```
```
