<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Signup Page</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #78909F;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .signup-container {
      background: #D2DADF;
      padding: 2rem 3rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      max-width: 420px;
      width: 100%;
    }

    h2 {
      text-align: center;
      margin-bottom: 1rem;
      color: #333;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin-bottom: 1rem;
    }

    .error {
      color: red;
    }

    .success {
      color: green;
    }

    .form-group {
      margin-bottom: 0.8rem;
    }

    label {
      display: block;
      margin-bottom: 0.3rem;
      color: #555;
    }

    input, select {
      width: 100%;
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    button {
      width: 100%;
      padding: 0.7rem;
      background-color: #35424a;
      color: white;
      font-size: 1rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 1rem;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #949ba0;
    }
  </style>
</head>
<body>
  <div class="signup-container">
    <h2>Signup Form</h2>

    

    <?php
      if (isset($_GET['error']) && $_GET['error'] == 'User+already+exists') {
          echo "<script>alert('User already exists with this National ID.');</script>";
      }

      if (isset($_GET['success']) && $_GET['success'] == '1') {
          echo '<p class="message success">Signup successful! Redirecting to login...</p>';
      }
    ?>

    <form action="signup_process.php" method="POST">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required />
      </div>
      <div class="form-group">
        <label for="phone">Phone No</label>
        <input type="tel" id="phone" name="phone" required />
      </div>
      <div class="form-group">
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
          <option value="" disabled selected>Select gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div class="form-group">
        <label for="age">Age</label>
        <input type="number" id="age" name="age" min="0" required />
      </div>
      <div class="form-group">
        <label for="nationality">Nationality</label>
        <input type="text" id="nationality" name="nationality" required />
      </div>
      <div class="form-group">
        <label for="nid">NID No</label>
        <input type="text" id="nid" name="nid" required />
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
      </div>
      <button type="submit">Signup</button>
    </form>
  </div>
</body>
</html>

