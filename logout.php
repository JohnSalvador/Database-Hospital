<?php
setcookie('access', 'nurse', time()-1);
setcookie('access', 'doctor', time()-1);
setcookie('access', 'admin', time()-1);
header("Location: index.php");
?>