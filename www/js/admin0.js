/*
 * charge la table des utilisateurs
 * 
 * 
 */

function loadPageAdmin0 (d) {
    var whenUpdateRow = function (dataRow) {
	alert (JSON.stringify (dataRow.values));
	var options = $.extend ({query: "updateuser"}, dataRow.values);
	$.post ("index.php",
		options,
		function (data) {
		    alert (JSON.stringify (data))},
	       'json');
    };
    
    $.getJSON ("index.php",
	       {query: "allusers"},
	       function (data) {
		   var options = {
		       whenUpdateRow: whenUpdateRow,
		       containerId: d.users_container_id,
		       tableId: d.users_container_id + "-table",
		       editForm: {
			   title: "Administration des utilisateurs",
			   containerId: d.users_container_id + "-edit-modal"}};
		   
		   var x = new SoDAD_HTMLTable (data, options);

		   $("#" + d.users_container_id)
		       .empty ()
		       .append (x.tableElement)
		       .append (x.editElement);
	       }
	      )
}
