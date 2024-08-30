<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style_login.css">
    <style>
        .notification {
            padding: 15px;
            background-color: #f44336; /* Red */
            color: white;
            margin-bottom: 15px;
            opacity: 0;
            transition: opacity 0.6s; /* 600ms to fade in/out */
            position: fixed;
            top: 20px;
            right: 20px;
            left: 20px;
            z-index: 1;
        }

        .notification.show {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-content">
            <div class="logo">
                <img src="./gambar/Logo.png" alt="Logo">
            </div>
            <h2>LOGIN</h2>
            <form action="Flogin.php" method="post">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="role">Status:</label>
                    <select id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="kepala">Kepala</option>
                    </select>
                </div>
                <button type="submit">Login</button>
            </form>
            <?php
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                echo "<div class='notification' id='notification'>$error</div>";
            }
            ?>

            <script>
                window.onload = function() {
                    var notification = document.getElementById('notification');
                    if (notification) {
                        notification.className += " show";
                        setTimeout(function() {
                            notification.className = notification.className.replace(" show", "");
                        }, 3000); // The notification will disappear after 3 seconds
                    }
                }
            </script>
            <button class="back-button" onclick="window.location.href='index.php'">Kembali</button>
        </div>
    </div>
</body>
</html>