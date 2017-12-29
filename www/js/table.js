function isUndefined (x) {
    var undef;
    return x === undef;
}

function createGlyph (glyph, options, attr) {
    if (isUndefined (attr)) attr = {};
    var e =  $("<span/>", attr).
	addClass ("glyphicon").
	addClass ("glyphicon-" + glyph).
	css ("paddingLeft", "2px").
	css ("paddingRight", "2px");
    
    if (! options.visible) e.css ("visibility", "hidden");
    return e;
}

function sortIndexes (fields, criteria, sortFun) {
    if (isUndefined (sortFun))
	sortFun = function (a, b) {
	    if (isUndefined (a))
		return isUndefined (b) ? 0 : -1;
	    if (isUndefined (b) || b < a) return 1;
	    return a < b ? -1 : 0;
	}
    return $
	.map (fields, function (_, i) {return i})
	.sort (function (a, b) { return sortFun (fields [a][criteria], fields [b][criteria]) });
}

function SoDAD_HTMLTable (data, options) {
    var self = this;
    this.data = data;
    this.options = options;
    this.maxRowId = 0;
    for (var i = 0; i < data.rows.length; i ++)
	if (! isUndefined (data.rows [i].id))
	    this.maxRowId = Math.max (this.maxRowId, data.rows [i].id);
	
    for (var i = 0; i < data.rows.length; i ++)
	if (isUndefined (data.rows [i].id))
	    data.rows [i].id = ++ this.maxRowId;
    
    this.tableElement = this.toHTMLTable (data, options);
    this.editElement = this.createFormForFields (data.fields, options);

    var handleUpdate = function (fun, dataRow) {
	if (fun && $.isFunction (fun))
	    fun (dataRow,
		 function (data, dataRow) {
		     self.data.rows = data.rows;
		     self.refreshView ();
		 });
    };
    
    this.updateRow = function (dataRow) {
	if (options.whenUpdateRow)
	    options.whenUpdateRow (dataRow,
				   function (data, dataRow) {
				       self.data.rows = data.rows;
				       self.refreshView ();
				   });
    };

    this.addRow = function (dataRow) {
	if (options.whenAddElement)
	    options.whenAddElement (dataRow,
				    function (data, dataRow) {
					self.data.rows = data.rows;
					self.refreshView ();
				    });
    };

    this.deleteRow = function (dataRow) {
	handleUpdate (options.whenDeleteRow, dataRow);
    };
}


$.extend (SoDAD_HTMLTable.prototype, {
    updateRowView: function (dataRow) {
	var fields = this.data.fields;
	$("#" + this.options.tableId + " tr[data-key=" + dataRow.values[this.data.key] + "] td[data-fname]").each (
	    function (_, x) {
		var e = $(x);
		var fname = e.attr ("data-fname");
		var field = $.grep (fields,
				    function (o, _) {
					return o.name === fname;
				    })[0];
		var str;
		if (field.options) {
		    var option = $.grep (field.options,
			    function (o, _) {
				return o.value === dataRow.values [fname]
			    })[0];
		    str = option ? option.label : '?';
		} else
		    str = dataRow.values [fname];
		e.text (str);
	    }
	);
    },

    refreshView: function () {
	var self = this;
	var undef;
	var forKey = function (data, key) {
	    var r = $.grep (data.rows,
			    function (r, _) {
				return r.values [data.key] === key;
			    });
	    return r.length > 0 ? r [0] : undef;
	};
    
	var options = this.options;
	var data = this.data;
	$.each ($("#" + options.containerId + " tr[data-key]"),
		function (_, rowElt) {
		    var dataRow = forKey (data, rowElt.dataset.key);
		    if (dataRow) {
			self.updateRowView (dataRow);
			dataRow.touched = true;
		    } else {
			$(rowElt).remove ();
		    }
		});
	$.each (data.rows,
		function (_, dataRow) {
		    if (dataRow.touched) {
			delete dataRow.touched;
		    } else {
			self.createRowElement (dataRow)
			    .appendTo ($("#" + options.tableId + "-tbody"));
		    }});
    },

 /*
  *  Creates the edit button for a row
  */   
    createRowEditBtnElement: function (dataRow) {
	var self = this;
	return createGlyph ("edit",
			    {
				visible: dataRow.editable
			    },
			    {
				"data-toggle": "modal",
				"data-target": '#' + this.options.editForm.containerId
			    })
	    .click ((function (dataRow) {  // to create a closure
		return function () {
		    var newDataRow = {values: $.extend ({}, dataRow.values)};
		    var f = $('#' + self.options.editForm.containerId);
		    var form = $("#" + self.options.editForm.containerId + "-form");
		    form.off ("submit");
		    form.submit (function (e) {
			$.each (self.data.fields,
				function (_, field) {
				    if (field.inputId && ! field.noneditable && ! self.options.noneditable) {
					newDataRow.values [field.name] = $("#" + field.inputId).val ();
				    }
				    //alert ("#" + field.inputId + " : " + JSON.stringify (newDataRow.values [field.name]));
				});
			self.updateRow (newDataRow);
			e.preventDefault ();
			$("#" + self.options.editForm.containerId)
			    .modal ('hide');
			return false;
		    });
		    $.each (self.data.fields,
			    function (_, field) {
				if (field.inputId) {
				    var value = dataRow.values [field.name];
				    var options = dataRow.options [field.name];
				    var nonEditable = field.noneditable || dataRow.options [field.name].noneditable;
				    var e = $("#" + field.inputId);
				    e[0].disabled = nonEditable;
				    e.val (value);
				}})}})(dataRow))
    },

    createRowRemoveBtnElement: function (dataRow) {
	var self = this;
	return createGlyph ("remove",
			    {
				visible: dataRow.deletable
			    })
	    .click (function () {
		self.deleteRow (dataRow);
	    });
    },
    
    createRowElement: function (dataRow) {
	var self = this;
	var row = $("<tr/>",
		    {"data-row": dataRow.id,
		     "data-key": dataRow.values [this.data.key]
		    });
	var c = $ ("<td/>").appendTo (row);

	this.createRowEditBtnElement (dataRow).appendTo (c);
	this.createRowRemoveBtnElement (dataRow).appendTo (c);
	/*
	createGlyph ("remove",
		     {
			 visible: dataRow.deletable
		     })
	    .appendTo (c);
	*/
	var I = this.data.I;
	for (i = 0; i < I.length; i ++) {
	    var field = this.data.fields [I[i]];
	    var fname = field.name;
	    var label;
	    if (field.type.indexOf ('select') === 0) {
		var o = $.grep (field.options,
				function (o,_) {
				    return o.value === dataRow.values [fname];
				})[0];
		label = o ? o.label : '?';
	    } else {
		label = dataRow.values [fname];
	    }
	    
	    $("<td/>", {"data-fname": fname}).appendTo (row).text (label);
	}
	return row;
    },
    
    toHTMLTable: function () {
	var self = this;
	var x = this.data;
	var options = this.options;
	
/// ADD BUTTON
	$("#" + options.containerId + "-addbtn").click (
	    function () {
		var f = $('#' + options.editForm.containerId);
		var form = $("#" + options.editForm.containerId + "-form");
		form.off ("submit");
		form.submit (function (e) {
		    var dataRow = {
			id: 1000,
			editable: true,
			deletable: true,
			values: {}
		    };
		    $.each (x.fields,
			    function (_, field) {
				if (field.inputId) {
				    dataRow.values [field.name] = $("#" + field.inputId).val ();
				}
			    });
		    self.addRow (dataRow);
		    e.preventDefault ();
		    $("#" + options.editForm.containerId)
			.modal ('hide');
//		    self.refreshView ();
		    return false;
		});
		options.newElement (x.fields);
	    });
	
	var I = sortIndexes (x.fields, "crank");
	x.I = I;

	var table = $('<table/>', {id: options.containerId + "-table"}).addClass ('table');

/// CREATES HEADER
	var thead = $('<thead/>').appendTo (table);
	var row = $('<tr/>').appendTo (thead);
	var c, i, j, g;
	c = $('<th/>').appendTo (row);

	for (i = 0; i < I.length; i ++) {
	    c = $("<th/>").appendTo (row).text (x.fields [I [i]].label);
	}
	
/// CREATES BODY AND DATA ROWS
	var tbody = $('<tbody/>', {id: options.tableId + "-tbody"}).appendTo (table);;
	for (j = 0; j < x.rows.length; j ++) 
	    this.createRowElement (x.rows [j]).appendTo (tbody);

	return table;
    },
    
    createFormForFields: function (fields, options) {
	var formOptions = options.editForm;
	var modalEltId = formOptions.containerId;
	
	var createCloseButtonElt = function () {
	    
	    return $("<button/>", {
		type: "button",
		"data-dismiss": "modal",
		"class" : "close"}).
		html ("&times;");
	};

	var createTitleElt = function () {
	    return $("<h4/>").
		addClass ("modal-title").
		text (options.editForm.title)
	};
	
	var modalElt =
	    $('<div/>',
	      {
		  id: modalEltId,
		  role: "dialog"
	      }).
	    data ("backdrop", "static").
	    data ("keyboard", false).
	    addClass ("modal fade");

	var modalDialogElt =
	    $('<div/>').
	    addClass ("modal-dialog").
	    appendTo (modalElt);

	var modalContentElt =
	    $('<div/>').
	    addClass ("modal-content").
	    appendTo (modalDialogElt);

	var modalHeaderElt =
	    $('<div/>').
	    addClass ("modal-header").
	    appendTo (modalContentElt).
	    append (createCloseButtonElt ()).
	    append (createTitleElt ());

	var modalBodyElt =
	    $('<div/>').
	    addClass ("modal-body").
	    appendTo (modalContentElt);

	var formElt = $("<form/>", {id: modalEltId + "-form", action: '#'})
	    .appendTo (modalBodyElt);

	$.each (sortIndexes (fields, "frank"),
		function (i) {
		    var field = fields [i];
		    var fname = field.name;
		    var flabel = field.label;
		    var inputId = modalEltId + "-form-" + fname;
		    field.inputId = inputId;
		    var formGroupElt = $("<div/>", {"class": "form-group"}).appendTo (formElt);
		    $("<label/>", {
			id: modalEltId + "-form-label-" + fname,
			"for": inputId
		    })
			.text (flabel)
			.appendTo (formGroupElt);
		    createInputForField (field).appendTo (formGroupElt);
		});

	var modalFooterElt = $("<div/>", {"class": "modal-footer"}).appendTo (formElt);
	$("<button/>", {"class": "btn btn-default", type:"submit"})
	    .text ("Valider")
	    .appendTo (modalFooterElt);

	return modalElt;
	
    }
});

function createInputForField (field) {
    var e;
    switch (field.type) {
    case "text":
	e = $("<input/>", {type: "text", "class": "form-control"});
	break;

    case "select":
	e = $("<select/>", {"class": "form-control"});
	$.each (field.options,
		function (_, opt) {
		    $("<option/>", {value: opt.value})
			.text (opt.label)
			.appendTo (e)
		});
	break;

    case "text multiple":
	e = $("<textarea/>", {rows: 4, "class": "form-control"});
	break;
	
    default:
	return $("<div/>", {id: field.inputId}).html ("<br>Unexpected field type: " + field.type +"</br>");
    }

    if (field.noneditable)
	e[0].disabled = true;
    
    return e
	.attr ("name", field.name)
	.attr ("id", field.inputId);
}

function fakeData () {
    var data = {
	fields: [
	    {name: "name1", label: "label 1", crank: 3, frank: 1, type: "text"},
	    {name: "name2", label: "label 2", crank: 2, frank: 2, type: "text"},
	    {name: "name3", label: "label 3", type: "select",
	     options: [
		 {label: "op1", value: "val1"},
		 {label: "op2", value: "val2"},
		 {label: "op3", value: "val3"}
	     ]}
	],
	rows: [
	    {id: 1, options: {name1: {noneditable: true}}, values: {name1: 'value1'}, editable: true, deletable: true},
	    {id: 2, values: {name2: 'value2'}, editable: false, deletable: true},
	    {id: 3, values: {name1: 'value1', name2: 'value2', name3: 'val2'}, editable: true, deletable: true}
	]
    };
    return data;
}
