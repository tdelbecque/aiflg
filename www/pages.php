<?php // Hi emacs ! -*- mode: html -*-

require_once 'utils.php';
require_once 'pages_admin0.php';
require_once 'pages_op0.php';
require_once 'pages_op1.php';
?>

<?php // Page d'erreur 
function pageError ($error) {
?>
<html>
  <head>
    <?php
      include 'stdheader.php'
    ?>
    <title>AIFLG Page Erreur</title>
  </head>
  <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
	<div class="navbar-header">
	  <a class="navbar-brand" href="#">Erreur</a>
	</div>
	<ul class="nav navbar-nav navbar-right">
	  <li id="id_logout"><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Déconnection</a></li> 
	</ul>
      </div>
    </nav>
    <div class="container">
    <?php echo $error; ?>
    </div>
  </body>
</html>

<?php } ?>

<?php // page pour un utilisateur.
      //
      // l'utilisateur est identifié par $uid. Selon son role la page adaptée est proposée
      //
function pageForUID ($uid) {
      global $AIFLG_ROLE_ADMIN0;
      global $AIFLG_ROLE_ADMIN1;
      global $AIFLG_ROLE_OP0;
      global $AIFLG_ROLE_OP1;
      
      switch (AIFLG_getRoleForUID ($uid)) {
      case $AIFLG_ROLE_ADMIN0:
      case $AIFLG_ROLE_ADMIN1:
        pageForAdmin0 ($uid);
        break;
      case $AIFLG_ROLE_OP0:
        pageForOperator0 ($uid);
        break;
      case $AIFLG_ROLE_OP1:
        pageForOperator1 ($uid);
        break;
      default:
        pageError ("UNKNOWN ROLE for $uid: $role");
      }
}
?>
