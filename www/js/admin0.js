/*
 * charge la table des utilisateurs
 * 
 * 
 */

SoDAD.Admin = {};

SoDAD.Admin.whenUpdateRowGen = function (queryUpdate, queryRefresh) {
    return function (dataRow, callback) {
	var options = $.extend ({query: queryUpdate}, dataRow.values);
	$.post ("index.php",
		options,
		function (updateRetData) {
		    if (SoDAD.isDefined (callback) && $.isFunction (callback)) {
			$.post ("index.php",
				{query: queryRefresh},
				function (data) {
				    if (SoDAD.isDefined (data.error))
					alert ("error");
				    else 
					callback (data, dataRow);
				},
				'json')
		    }
		},
		'json');
    }
};

SoDAD.Admin.newElementGen = function (queryNew) {
    return function (fields) {
	$.post ("index.php",
		{query: queryNew},
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
    }
};

SoDAD.Admin.loadUsers = function (d) {
    var containerId = d.users_container_id;
    var whenUpdateRow = SoDAD.Admin.whenUpdateRowGen ("updateusers", "allusers");
    var whenAddElement = function (dataRow, callback) {
	var options = $.extend ({query: "adduser"}, dataRow.values);
	$post ("index.php",
	       options,
	       function (data) {
		   if (SoDAD.isDefined (callback) && $.isFunction (callback))
		       callback (data, dataRow);
	       },
	       'json');
    };
    
    $.post ("index.php",
	    {query: "allusers"},
	    function (data) {
		var options = {
		    newElement: d.users.newElement,   // new elements create a blank data
		    whenUpdateRow: whenUpdateRow,
		    whenAddElement: whenAddElement,
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
	    },
	   'json')
};

SoDAD.Admin.loadStructures = function (d) {
    var containerId = d.structures_container_id;
    var whenUpdateRow = SoDAD.Admin.whenUpdateRowGen ("updatestructure", "allstructures");
    /*
    var whenUpdateRow = function (dataRow) {
	var options = $.extend ({query: "updatestructure"}, dataRow.values);
	$.post ("index.php",
		options,
		function (data) {
		    console.log (JSON.stringify (data))
		},
	       'json');
    };
    */
    var whenAddElement = function (dataRow) {
	var options = $.extend ({query: "addstructures"}, dataRow.values);
	$post ("index.php",
	       options,
	       function (data) {
	       },
	       'json');
    };
    $.post ("index.php",
	    {query: "allstructures"},
	    function (data) {
		var options = {
		    whenUpdateRow: whenUpdateRow,
		    whenAddElement: whenAddElement,
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
	    },
	   'json')
};

SoDAD.Admin.load = function (what, uid, formTitle) {
    var containerId = what + "-table";
    var whenUpdateRow = SoDAD.Admin.whenUpdateRowGen ("update" + what, "all" + what);
    var whenAddElement = function (dataRow, callback) {
	var options = $.extend ({query: "add" + what}, dataRow.values);
	$post ("index.php",
	       options,
	       function (data) {
		   if (SoDAD.isDefined (callback) && $.isFunction (callback))
		       callback (data, dataRow);
	       },
	       'json');
    };
    
    $.post ("index.php",
	    {query: "all" + what},
	    function (data) {
		if (SoDAD.isDefined (data.error)) {
		    alert (data.error);
		} else {
		    var options = {
			newElement: SoDAD.Admin.newElementGen ("new" + what),
			whenUpdateRow: whenUpdateRow,
			whenAddElement: whenAddElement,
			containerId: containerId,
			tableId: containerId + "-table",
			editForm: {
			    title: formTitle,
			    containerId: containerId + "-edit-modal"}};
		    
		    var x = new SoDAD_HTMLTable (data, options);
		    
		    $("#" + containerId)
			.empty ()
			.append (x.tableElement)
			.append (x.editElement);
		}
	    },
	   'json')
}

SoDAD.pageAdmin0WhenLoaded = function (uid) {
    var usersConfig = {
	newElement: SoDAD.Admin.newElementGen ("newusers"),
	containerId: "users-table"
    };
    
    var config = {
        uid: uid,
	users: usersConfig,
        users_container_id: "users-table",
        structures_container_id: "structures-table"
    };

    //    SoDAD.Admin.loadUsers (config);
    SoDAD.Admin.load ("users", uid, "Administration des utilisateurs");
    //SoDAD.Admin.loadStructures (config);
    SoDAD.Admin.load ("structures", uid, "Administration des structures");
}
