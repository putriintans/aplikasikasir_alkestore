<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Registrasi</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background-color: #f4f4f4;
    }

    .form-box {
      width: 500px;
      margin: 0 auto;
      background: #fff;
      padding: 25px;
      border: 1px solid #ccc;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: inline-block;
      width: 120px;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="date"],
    select {
      width: 60%;
      padding: 6px;
    }

    .gender {
      display: inline-block;
      width: auto;
    }

    .form-buttons {
      text-align: center;
      margin-top: 20px;
    }

    button {
      padding: 8px 15px;
      margin: 0 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="form-box">
    <h2>FORM REGISTRASI</h2>
<form method="POST" action="/aplikasikasir_alkestore/controllers/register_controller.php">
      <div class="form-group">
        <label>Username:</label>
        <input type="text" name="username" required>
      </div>

      <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password" required>
      </div>

      <div class="form-group">
        <label>Retype Password:</label>
        <input type="password" name="retype_password" required>
      </div>

      <div class="form-group">
        <label>E-mail:</label>
        <input type="email" name="email">
      </div>

      <div class="form-group">
        <label>Date of Birth:</label>
        <input type="date" name="dob">
      </div>

      <div class="form-group">
        <label>Gender:</label>
        <label class="gender"><input type="radio" name="gender" value="Male" required> Male</label>
        <label class="gender"><input type="radio" name="gender" value="Female"> Female</label>
      </div>

      <div class="form-group">
        <label>Address:</label>
        <input type="text" name="address">
      </div>

      <div class="form-group">
        <label>City:</label>
        <select name="city">
          <option value="">-- Pilih Kota --</option>
          <option value="Jakarta">Jakarta</option>
          <option value="Surabaya">Surabaya</option>
          <option value="Bandung">Bandung</option>
          <option value="Yogyakarta">Yogyakarta</option>
        </select>
      </div>

      <div class="form-group">
        <label>Contact No:</label>
        <input type="text" name="contact">
      </div>

      <div class="form-group">
        <label>PayPal ID:</label>
        <input type="text" name="paypal_id">
      </div>

      <div class="form-buttons">
        <button type="submit">Submit</button>
        <button type="reset">Clear</button>
      </div>
    </form>
  </div>

</body>
</html>
