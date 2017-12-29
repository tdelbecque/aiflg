<?php // -*- mode: html; -*-
      //
      // Page pour les opérateurs de niveau 1
      //
      function pageForOperator1 ($uid) {
?>
<!doctype html>
<html>
  <head>
    <?php include 'stdheader.php' ?>
    <title>AIFLG Page Opérateur niveau 1</title>
    <script src="js/sodad.js"></script>
    <script src="js/table.js"></script>
    <script src="js/admin0.js"></script>
    <script> $(function () {SoDAD.pageOp1WhenLoaded ('<?php echo $uid; ?>')}) </script>
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
    <div class="container-fluid">
      <ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#producers-tab">Producteurs</a></li>
	<li><a data-toggle="tab" href="#parcels-tab">Parcelles</a></li>
      </ul>
      <div class="tab-content">
	<?php
	  //
	  // Producteurs
	  //
	  ?>	
	<div id="producers-tab" class="tab-pane fade in active">
	  <div id="producers-table" class="well">
	    PRODUCTEURS
	  </div>
	  <div>
	    <button id="producers-table-addbtn", type="button" class="btn pull-right" data-toggle="modal" data-target="#structures-table-edit-modal">
	      <span class="glyphicon glyphicon-plus"></span>
	    </button>
	  </div>
	</div>
	<?php
	  //
	  // PARCELLES
	  //
	  ?>	
	<div id="parcels-tab" class="tab-pane">
	  <div id="parcels-table" class="well">
	    PARCELLES
	  </div>
	  <div>
	    <button id="parcels-table-addbtn", type="button" class="btn pull-right" data-toggle="modal" data-target="#structures-table-edit-modal">
	      <span class="glyphicon glyphicon-plus"></span>
	    </button>
	  </div>
	</div>		
      </div>
    </div>
  </body>
</html>
<?php } ?>
