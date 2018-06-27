<?php // line added to turn on color syntax highlight
session_start();
unset($_SESSION['name']);
unset($_SESSION['user_id']);
session_destroy();
header('Location: index.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Khaled Badawy</title>
</head>
<body>

</body>
</html>