<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      padding: 40px;
    }
    .login-container {
      width: 400px;
      margin: auto;
      background: #fff;
      padding: 20px 25px 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 8px;
    }
    .header {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }
    .header img {
      width: 60px;
      height: 60px;
      margin-right: 15px;
    }
    .header-text {
      font-size: 14px;
    }
    .header-text strong {
      font-size: 16px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 8px;
      box-sizing: border-box;
    }
    .login-btn {
      width: 100%;
      background-color: #4d90fe;
      color: white;
      padding: 10px;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }
    .login-btn:hover {
      background-color: #357ae8;
    }
    .register-link {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    }
    .register-link a {
      color: #4d90fe;
      text-decoration: none;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="header">
     <img src="/aplikasikasir_alkestore/assets/images/Logo.png" alt="Logo">
      <div class="header-text">
        Selamat datang di<br><strong>Toko Alat Kesehatan</strong>
      </div>
    </div>

<form method="POST" action="/aplikasikasir_alkestore/controllers/login_controller.php">
      <div class="form-group">
        <label>User ID:</label>
        <input type="text" name="username" required>
      </div>
      <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password" required>
      </div>
      <button class="login-btn" type="submit">LOGIN</button>
    </form>

    <div class="register-link">
      Belum punya akun? <a href="/aplikasikasir_alkestore/app/views/auth/register_form.php">Registrasi di sini</a>
    </div>
  </div>

</body>
</html>
