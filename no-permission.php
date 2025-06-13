<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #5b73e8, #5b73e8);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #fff;
        }

        h1 {
            font-size: 48px;
            margin: 0 0 10px;
        }

        p {
            font-size: 20px;
            margin: 10px 0 30px;
        }

        .btn-home {
            background: #fff;
            color: #ff416c;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 30px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-home:hover {
            background: #ffe0e9;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon">🚫</div>
        <h1>Access Denied</h1>
        <p>You do not have permission to access this page.</p>
        <a href="index?page_id=20?message=5">
            <button class="btn-home">Go to Homepage</button>
        </a>
    </div>
</body>

</html>