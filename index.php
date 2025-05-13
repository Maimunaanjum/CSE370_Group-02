<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home Sweet Home</title>
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
      background: linear-gradient(to right, #333, #333);
      color: #333;
    }
    header {
      background-color: #35424a;
      padding: 1em 0;
      text-align: center;
    }
    nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
      gap: 2em;
    }
    nav ul li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
    }
    .hero {
      text-align: center;
      padding: 9em 1em;
      background: url('House3.jpg') center center / cover no-repeat;
      color: white;
    }
    .featured {
      padding: 1em;
      background-color: #404d55;
      text-align: center;
    }
    .card-grid {
      display: flex;
      justify-content: center;
      gap: 2em;
    }
    .card {
      background-color: #daddde;
      padding: 1em;
      border-radius: 10px;
      width: 200px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="home_search.php">Home</a></li>
        <li><a href="post_form.php">Post</a></li>
        <li><a href="wishlist.php">Wishlist</a></li>
        <li><a href="chat.php">Chat</a></li>
        <li><a href="payment.html">Payment</a></li>
        <li><a href="review.html">Review</a></li>
        <li><a href="signup.php">SignUp</a></li>
        <li><a href="login.html">Login</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section class="hero">
      <h1>Find Your Perfect Place</h1>
      <form action="search.php" method="GET">
        <input type="text" name="query" placeholder="Search by location, type...">
        <button type="submit">Search</button>
      </form>
    </section>

    <section class="featured">
      <h2>Featured Listings</h2>
      <div class="card-grid">
        <div class="card" data-category="family">🏠 Cozy Family Flat</div>
        <div class="card" data-category="seasonal">🏖️ Seasonal Rent</div>
        <div class="card" data-category="shared_living">🏢 Shared Apartment</div>
      </div>
    </section>
  </main>

  <footer style="background-color:#404d55; color:white; text-align:center; padding:1em 0;">
    <p>&copy; 2025 Sweet Home by Anan. All rights reserved.</p>
  </footer>

  <script>
    const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
    const protectedPages = ['post.html', 'chat.html', 'review.html', 'payment.html'];

    document.querySelectorAll('a').forEach(el => {
      const href = el.getAttribute('href') || '';
      if (protectedPages.some(page => href.includes(page))) {
        el.addEventListener('click', e => {
          if (!isLoggedIn) {
            e.preventDefault();
            alert("You must be logged in to access this feature.");
            window.location.href = "login.html";
          }
        });
      }
    });

    document.querySelectorAll('.card').forEach(card => {
      const category = card.getAttribute('data-category');
      if (category) {
        card.addEventListener('click', () => {
          window.location.href = `post.php?category=${category}`;
        });
      }
    });
  </script>
</body>
</html>
