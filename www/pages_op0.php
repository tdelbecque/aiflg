<?php // -*- mode: html; -*-
      //
      // Page pour les opérateurs de niveau 1
      //
      function pageForOperator0 ($uid) {
?>
<!doctype html>
<html>
  <head>
    <?php include 'stdheader.php' ?>
    <title>AIFLG Page Opérateur niveau 0</title>
    <script src="js/sodad.js"></script>
    <script src="js/table.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
	<div class="navbar-header">
	  <a class="navbar-brand" href="#">Administration des producteurs</a>
	</div>
	<ul class="nav navbar-nav navbar-right">
	  <li id="id_logout"><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Déconnection</a></li> 
	</ul>
      </div>
    </nav>
    <div class="container">
      <ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#users-tab">Utilisateurs</a></li>
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
    </div>
  </body>
</html>
<?php } ?>
