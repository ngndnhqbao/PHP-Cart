<?php
session_start();
include 'config.php';

if (empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];
    $customer_address = $_POST['customer_address'];
    
    // Tính tổng tiền
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT price FROM products WHERE id = $product_id";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
        $total += $product['price'] * $quantity;
    }

    // Lưu đơn hàng
    $sql = "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount) 
            VALUES ('$customer_name', '$customer_email', '$customer_phone', '$customer_address', $total)";
    
    if ($conn->query($sql)) {
        // Xóa giỏ hàng
        unset($_SESSION['cart']);
        $_SESSION['order_success'] = true;
        header('Location: order_success.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Giỏ hàng của Lê Khôi</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="checkout.php">Thanh toán</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-7 mx-auto">
                <div class="card shadow-lg rounded-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h2 class="mb-0">Thông tin thanh toán</h2>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" class="checkout-form">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-floating">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Họ tên" required>
                                        <label for="customer_name"><i class="fas fa-user me-2"></i>Họ tên</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="email" name="customer_email" id="customer_email" class="form-control" placeholder="Email" required>
                                        <label for="customer_email"><i class="fas fa-envelope me-2"></i>Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="tel" name="customer_phone" id="customer_phone" class="form-control" placeholder="Số điện thoại" required>
                                        <label for="customer_phone"><i class="fas fa-phone me-2"></i>Số điện thoại</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <div class="form-floating">
                                        <textarea name="customer_address" id="customer_address" class="form-control" placeholder="Địa chỉ" style="height: 100px" required></textarea>
                                        <label for="customer_address"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-shopping-cart me-2"></i>Hoàn tất đặt hàng
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-white text-center py-3 mt-5">
        <p>&copy; 2025 Giỏ hàng của Lê Khôi. All rights reserved.</p>
    </footer>
</body>
</html>