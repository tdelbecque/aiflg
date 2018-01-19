<?php // -*- mode: html; -*-
      //
      // Page pour les opérateurs de niveau 0
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
    <script src="js/admin0.js"></script>
    <script> $(function () {SoDAD.pageOp0WhenLoaded ('<?php echo $uid; ?>')}) </script>
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
	    <li class="active"><a data-toggle="tab" href="#users-tab">Utilisateurs</a></li>
	    <li><a data-toggle="tab" href="#producers-tab">Producteurs</a></li>
	    <li><a data-toggle="tab" href="#parcels-tab">Parcelles</a></li>
      </ul>
      <div class="tab-content">
        <?php 
          entitiesSimpleTable ("users", "fade in active");
          entitiesSimpleTable ("producers");
          entitiesSimpleTable ("parcels");
          ?>
      </div>
    </div>
  </body>
</html>
<?php } ?>
