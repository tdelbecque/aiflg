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
    <?php
      include 'stdheader.php'
    ?>
    <title>AIFLG Page Principale</title>
    <script src="js/dev/table.js"></script>
    <script src="js/admin0.js"></script>
    <script>
      $(function () {
        loadPageAdmin0 ({
          uid: '<?php echo $uid; ?>',
          users_container_id: "users-table",
          structures_container_id: "structures-table"
        })})
    </script>
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
      </ul>
      <div class="tab-content">
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
	<div id="structures-tab" class="tab-pane">
	  <div id="structures-table" class="well">
	    STRUCTURES
	  </div>
	  <div>
	    <button id="structure-table-addbtn", type="button" class="btn pull-right" data-toggle="modal" data-target="#structures-edit-modal">
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
