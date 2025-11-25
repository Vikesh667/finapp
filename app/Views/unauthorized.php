<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access | FinApp</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            font-family: "Poppins", sans-serif;
        }

        .error-box {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 420px;
            width: 100%;
        }

        .error-icon {
            font-size: 64px;
            color: #dc3545;
            margin-bottom: 20px;
        }

        h1 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .btn-main {
            background-color: #007bff;
            color: white;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 500;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-main:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <div class="error-box">
        <ion-icon name="lock-closed-outline" class="error-icon"></ion-icon>
        <h1>Access Denied</h1>
        <p>You don’t have permission to view this page.<br>
            Please contact the administrator or return to the dashboard.</p>
        <a href="<?= base_url('/') ?>" class="btn btn-main">
            <ion-icon name="home-outline"></ion-icon> Go Home
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
