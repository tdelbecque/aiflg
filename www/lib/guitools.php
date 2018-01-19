<?php // Hi emacs ! -*- mode: html; -*-
function tablesToolBars ($type) {
?>
<nav class="navbar">
  <div class="container-fluid">
    <?php /*
    <!-- How to use drop down menu ? 
    <ul class="nav navbar-nav"> 
      <li>
        <div class="dropdown">
          <button class="btn navbar-btn dropdown-toggle" 
                  type="button" data-toggle="dropdown">Dropdown Example
            <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a href="#">HTML</a></li>
            <li><a href="#">CSS</a></li>
            <li><a href="#">JavaScript</a></li>
          </ul>
        </div> 
      </li>
    </ul>
    -->
    */ ?>
	<ul class="nav navbar-nav navbar-right"> 
	  <li>	        
        <button id="<?php echo $type;?>-table-addbtn" 
                type="button" 
                class="btn navbar-btn pull-right" 
                data-toggle="modal" 
                data-target="#<?php echo $type;?>-table-edit-modal">
	      <span class="glyphicon glyphicon-plus"></span>
	    </button>
      </li> 
	</ul>
  </div>
</nav> 
<?php
}

function entitiesSimpleTable ($type, $classes='') {
?>
<div id="<?php echo $type;?>-tab" 
     class="tab-pane<?php echo $classes != '' ? ' ' . $classes : '';?>">
  <?php tablesToolBars ($type); ?>
  <div id="<?php echo $type;?>-table" class="well">
  </div>
</div>
<?php
}
?>
