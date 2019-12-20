<?php require __DIR__ . '/partials/header.php'; ?>

<form method="post" action="<?php echo $router->urlFor("LOGIN_POST"); ?>">
  <div>Username</div>
  <div><input name="U"></div>
  <div>Password</div>
  <div><input type="password" name="P"></div>
  <div><button type="submit">Login</button></div>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>