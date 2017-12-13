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
					alert ("error : " + JSON.stringify (data));
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


SoDAD.Admin.load = function (what, uid, formTitle) {
    var containerId = what + "-table";
    var whenUpdateRow = SoDAD.Admin.whenUpdateRowGen ("update" + what, "all" + what);
    var whenAddElement = SoDAD.Admin.whenUpdateRowGen ("add" + what, "all" + what);

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
			whenDeleteRow: SoDAD.Admin.whenUpdateRowGen ("delete" + what, "all" + what),
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
    SoDAD.Admin.load ("users", uid, "Administration des utilisateurs");
    SoDAD.Admin.load ("structures", uid, "Administration des structures");
}
