<?php
setcookie('key', 'null', time ()+3600*24*30, null, null, false, true);
header("Location: loginform.php");
?>
