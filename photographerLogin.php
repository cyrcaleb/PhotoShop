<?php include 'includes/header-member.php'; ?>
<h1>Login</h1>
<form method="POST" action="login.php">
  Username: <input type="email" name="email"><br>
  Password: <input type="password" name="password"><br>
  <input type="submit" value="Log In">
</form>
<?php include 'includes/footer.php'; ?>