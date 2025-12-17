
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Us - Cloth Rental</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    /* Navbar styles */
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

   .navbar {
      background-color: #1b2735;
      padding: 15px 0;
    }

    .nav-links {
      display: flex;
      justify-content: center;
      list-style: none;
      gap: 30px;
    }

    .nav-links li a {
      color: #fff;
      text-decoration: none;
      font-size: 18px;
      font-weight: bold;
    }

    .nav-links li a:hover {
      color: #00aced;
    }

    

    /* Contact form styles */
    .contact-container {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border-radius: 8px;
    }
    .contact-container h2 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
    }
    .contact-container form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .contact-container label {
      font-weight: 600;
      color: #555;
    }
    .contact-container input,
    .contact-container textarea {
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
      font-family: inherit;
      resize: vertical;
    }
    .contact-container textarea {
      min-height: 120px;
    }
    .contact-container button {
      background-color: #ff6f61;
      color: white;
      border: none;
      padding: 15px;
      font-size: 1.1rem;
      font-weight: 700;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .contact-container button:hover {
      background-color: #e55a4e;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 15px 10px;
      font-size: 0.9rem;
      color: #777;
      border-top: 1px solid #ddd;
      margin-top: 40px;
      font-family: Arial, sans-serif;
    }
  </style>
</head>
<body>

<header>
  <nav class="navbar">
    <ul class="nav-links">
      <li><a href="homepage.php">Home</a></li>
      <li><a href="men.php">Men</a></li>
      <li><a href="women.php">Women</a></li>
      <li><a href="kids.php">Kids</a></li>
      <li><a href="contact.php" class="active">Contact</a></li>
    </ul>
  </nav>
</header>



<main class="contact-container">
  <h2>Contact Us</h2>
  <form action="send_contact.php" method="POST">
    <label for="fullname">Full Name</label>
    <input type="text" id="fullname" name="fullname" placeholder="Your full name" required />

    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" placeholder="Your email" required />

    <label for="subject">Subject</label>
    <input type="text" id="subject" name="subject" placeholder="Subject of your message" required />

    <label for="message">Message</label>
    <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>

    <button type="submit">Send Message</button>
  </form>
</main>

<footer>
  &copy; <?php echo date("Y"); ?> Cloth Rental. All rights reserved.
</footer>

</body>
</html>
