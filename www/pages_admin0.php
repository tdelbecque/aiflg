<?php // Hi emacs ! -*- mode: html; -*-
      //
      // Cette page est pour les administrateurs (roles ADMIN/X).
      // Elle permet d'administrer les utilisateurs et les structures.
      //
      // - $uid identifie l'utilisateur qui accède à cette page.
      //
  function pageForAdmin0 ($uid) {
?>
<!doctype html>
<html>
  <head>
    <?php include 'stdheader.php' ?>
    <title>AIFLG Page Principale</title>
    <script src="js/sodad.js"></script>
    <script src="js/table.js"></script>
    <script src="js/admin0.js"></script>
    <script> $(function () {SoDAD.pageAdmin0WhenLoaded ('<?php echo $uid; ?>')}) </script>
  </head>
  <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
	<div class="navbar-header">
	  <a class="navbar-brand" href="#">Administration</a>
	</div>
	<ul class="nav navbar-nav navbar-right">
	  <li id="id_logout"><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Déconnection</a></li> 
	</ul>
      </div>
    </nav>
    <div class="container">
      <ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#users-tab">Utilisateurs</a></li>
	<li><a data-toggle="tab" href="#structures-tab">Structures</a></li>
	<li><a data-toggle="tab" href="#producteurs-tab">Producteurs</a></li>
	<li><a data-toggle="tab" href="#parcelles-tab">Parcelles</a></li>
      </ul>
      <div class="tab-content">
	<?php
	  //
	  // Utilisateurs
	  //
	  ?>
	<div id="users-tab" class="tab-pane fade in active">
	  <div id="users-table" class="well">
	    USERS
	  </div>
	  <div>
	    <button id="users-table-addbtn", type="button" class="btn pull-right" data-toggle="modal" data-target="#users-table-edit-modal">
	      <span class="glyphicon glyphicon-plus"></span>
	    </button>
	  </div>
	</div>
	<?php
	  //
	  // Structures
	  //
	  ?>	
	<div id="structures-tab" class="tab-pane">
	  <div id="structures-table" class="well">
	    STRUCTURES
	  </div>
	  <div>
	    <button id="structures-table-addbtn", type="button" class="btn pull-right" data-toggle="modal" data-target="#structures-table-edit-modal">
	      <span class="glyphicon glyphicon-plus"></span>
	    </button>
	  </div>
	</div>
	<?php
	  //
	  // Producteurs
	  //
	  ?>	
	<div id="producteurs-tab" class="tab-pane">
	  <div id="producteurs-table" class="well">
	    PRODUCTEURS
	  </div>
	  <div>
	    <button id="producteurs-table-addbtn", type="button" class="btn pull-right" data-toggle="modal" data-target="#structures-table-edit-modal">
	      <span class="glyphicon glyphicon-plus"></span>
	    </button>
	  </div>
	</div>
	<?php
	  //
	  // PARCELLES
	  //
	  ?>	
	<div id="parcelles-tab" class="tab-pane">
	  <div id="parcelles-table" class="well">
	    PARCELLES
	  </div>
	  <div>
	    <button id="parcelles-table-addbtn", type="button" class="btn pull-right" data-toggle="modal" data-target="#structures-table-edit-modal">
	      <span class="glyphicon glyphicon-plus"></span>
	    </button>
	  </div>
	</div>	
      </div>
  </body>
</html>
<?php
}
?>
