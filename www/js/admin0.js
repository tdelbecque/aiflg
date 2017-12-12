/*
 * charge la table des utilisateurs
 * 
 * 
 */

SoDAD.Admin = {};
SoDAD.Admin.loadUsers = function (d) {
    var containerId = d.users_container_id;
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
		       newElement: d.users.newElement,
		       whenUpdateRow: whenUpdateRow,
		       containerId: containerId,
		       tableId: containerId + "-table",
		       editForm: {
			   title: "Administration des utilisateurs",
			   containerId: containerId + "-edit-modal"}};
		   
		   var x = new SoDAD_HTMLTable (data, options);

		   $("#" + containerId)
		       .empty ()
		       .append (x.tableElement)
		       .append (x.editElement);
	       })
};

SoDAD.Admin.loadStructures = function (d) {
    var containerId = d.structures_container_id;
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
		       containerId: containerId,
		       tableId: containerId + "-table",
		       editForm: {
			   title: "Administration des structures",
			   containerId: containerId + "-edit-modal"}};
		   
		   var x = new SoDAD_HTMLTable (data, options);

		   $("#" + containerId)
		       .empty ()
		       .append (x.tableElement)
		       .append (x.editElement);
	       })
};

SoDAD.pageAdmin0WhenLoaded = function (uid) {
    var usersConfig = {
	newElement: function (fields) {
	    $.post ("index.php",
		    {query: "newuser"},
		    function (ne) {
			$.each (fields,
			    function (_, field) {
				if (field.inputId) {
				    if (SoDAD.isDefined (ne [field.name]))
					$("#" + field.inputId).val (ne [field.name]);
				    else
					$("#" + field.inputId).val ("");					
				}});	
		    },
		    'json');
	},
	containerId: "users-table"
    };
    
    var config = {
        uid: uid,
	users: usersConfig,
        users_container_id: "users-table",
        structures_container_id: "structures-table"
    };

    SoDAD.Admin.loadUsers (config);
    SoDAD.Admin.loadStructures (config);
}
