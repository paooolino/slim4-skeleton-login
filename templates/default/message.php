<?php require __DIR__ . '/partials/header.php'; ?>

<h1><?php echo $message["title"]; ?></h1>
<p><?php echo $message["body"]; ?></p>
<hr>
<a href="javascript:history.back();">Go back</a>

<?php require __DIR__ . '/partials/footer.php'; ?>