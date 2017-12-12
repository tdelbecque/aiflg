/*
 * charge la table des utilisateurs
 * 
 * 
 */

SoDAD.Admin = {};
SoDAD.Admin.loadUsers = function (d) {
    var whenUpdateRow = function (dataRow) {
	var options = $.extend ({query: "updateuser"}, dataRow.values);
	$.post ("index.php",
		options,
		function (data) {
		    console.log (JSON.stringify (data))
		},
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
	       })
};

SoDAD.Admin.loadStructures = function (d) {
    var whenUpdateRow = function (dataRow) {
	var options = $.extend ({query: "updatestructure"}, dataRow.values);
	$.post ("index.php",
		options,
		function (data) {
		    console.log (JSON.stringify (data))
		},
	       'json');
    };
    
    $.getJSON ("index.php",
	       {query: "allstructures"},
	       function (data) {
		   var options = {
		       whenUpdateRow: whenUpdateRow,
		       containerId: d.users_container_id,
		       tableId: d.users_container_id + "-table",
		       editForm: {
			   title: "Administration des structures",
			   containerId: d.users_container_id + "-edit-modal"}};
		   
		   var x = new SoDAD_HTMLTable (data, options);

		   $("#" + d.users_container_id)
		       .empty ()
		       .append (x.tableElement)
		       .append (x.editElement);
	       })
};

function loadPageAdmin0 (d) {
    SoDAD.Admin.loadUsers (d);
    SoDAD.Admin.loadStructures (d);
}
