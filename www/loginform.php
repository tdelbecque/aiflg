<!doctype html>
<html>
<header>
	<?php
	include 'stdheader.php';
	?>
	<title>AIFLG Login Page</title>
</header>
<body>
<div class="container">
     <div class="modal-dialog">
     	  <div class="modal-content">
	       <div class="modal-header">
	       	    <span><img src="images/logo.jpg"/></span><legend class="modal-title"> Entrez votre clé pour acceder à votre espace</legend>
	  	</div>
		<div class="modal-body">
		<form action="login.php">
		<div class="form-group">
		<label for="key" class="control-label">Clé :</label>
            	<input type="text" name="key" class="form-control" id="key">
		</div>
		<button type="submit" class="btn-primary">Envoyer</button>
		</form>
		</div>
		<div class="modal-footer">
		<a href="#">Demander une clé</a>
		</div>
	  </div>
     </div>
</div>

</body>
</html>
