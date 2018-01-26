window.onerror = function (msg, url, lineNo, columnNo, error) {
  // ... handle error ...
    alert ("arg " + msg + " " + lineNo + " " + url);
  return false;
}

function createGlyph (glyph, options, attr) {
    if (SoDAD.isUndefined (attr)) attr = {};
    var e =  $("<span/>", attr).
	    addClass ("glyphicon").
	    addClass ("glyphicon-" + glyph).
	    css ("paddingLeft", "2px").
	    css ("paddingRight", "2px");
    
    if (! options.visible) e.css ("visibility", "hidden");
    return e;
}

function sortIndexes (fields, criteria, sortFun) {
    if (SoDAD.isUndefined (sortFun))
	    sortFun = function (a, b) {
	        if (SoDAD.isUndefined (a))
		        return SoDAD.isUndefined (b) ? 0 : -1;
	        if (SoDAD.isUndefined (b) || b < a) return 1;
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
    SoDAD.setCommons (data);
    for (var i = 0; i < data.rows.length; i ++)
	    if (! SoDAD.isUndefined (data.rows [i].id))
	        this.maxRowId = Math.max (this.maxRowId, data.rows [i].id);
	
    for (var i = 0; i < data.rows.length; i ++)
	    if (SoDAD.isUndefined (data.rows [i].id))
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

    /*
      Called when a new row is added. 
      Update the commons (1), and replace the rows set with the new one (2), and 
      refresh the view of the table (3)
    */
    this.addRow = function (dataRow) {
	    if (options.whenAddElement)
	        options.whenAddElement (dataRow,
				                    function (data, dataRow) {
                                        SoDAD.setCommons (data);    // (1)
					                    self.data.rows = data.rows; // (2)
					                    self.refreshView ();        // (3)
				                    });
    };

    this.deleteRow = function (dataRow) {
	    handleUpdate (options.whenDeleteRow, dataRow);
    };
}

/*
  This extension display the data in a regular HTML table
*/
$.extend (SoDAD_HTMLTable.prototype, {
    /*
      Update a row in a the table.
      'rowElt' is the HTML row element. If it is not providd it is retrieves with a jquery selector
      that uses the table id and the key of this dataRow. 
      In both cases the HTML cells to be filled are retrieved in 'fieldJElts' (1)
      For each cell we find the piece of data in the dataRow (2).
      If this is a select, we specifically set the option value, may be by looking
      to the commons (3)
    */
    updateRowView: function (dataRow, rowElt) {
	    var fields = this.data.fields;
	    var fieldsJElts;
	    
	    if (! rowElt) // (1)
	        fieldsJElts = $("#" + this.options.tableId + 
                            " tr[data-key=" + dataRow.values[this.data.key] + "] td[data-fname]")
	    else
	        fieldsJElts = $("td[data-fname]", rowElt);

	    for (var i = 0; i < fieldsJElts.length; i ++) {
	        var x = fieldsJElts [i];
	        var e = $(x);
	        var fname = e.attr ("data-fname");
	        var field;
	        for (var j = 0; j < fields.length && fields [j].name !== fname; j ++);
	        field = fields [j]; // (2)
	        var str;
	        if (field.options) { // (3)
                var options;
                switch ($.type (field.options)) {
                case 'array':
                    options = field.options
                    break;
                case 'string':
                    options = SoDAD.commons [field.options];
                    break;
                default:
                    options = [];
                }
                if (SoDAD.isUndefined (options)) {
                    console.log ("option Undefined");
                    console.log (JSON.strinfify (field.options), null, 2);
                    console.log (JSON.strinfify (SoDAD.commons), null, 2);
                }
		        for (var j = 0; 
                     j < options.length && options [j].value !== dataRow.values [fname]; 
                     j ++);
		        str = (j === options.length ? '?' : options [j].label);
	        } else
		        str = dataRow.values [fname];
	        e.text (str);
	    }
    },

    /*
      Refreshes the table according to the content of 'this.data'

      gets the HTML container of the table (identified by 'options.containerId'),
      then the set of HTML rows, that can be recognized because their class is 'aiflg-datarow'. (1)

      For each of these HTML rows, it tries to find the corresponding row in the data. (2)
      If the row is found (3) updates the HTML cells thanks to 'updateRowView' method and flagges the datarow 
      as touched, so that it will not be inserted latter in the function. In this case the couter variable 'i'
      is incremmented.
      Otherwise the row is removed. The counter variable is not incremented, as a collection 
      returned by getElementsByClassName is a live collection.

      Finaly the data rows are browsed (4). When a row is not flagged as 'touched' is is inserted in the
      table, as a brand new row (5). Othewise the 'touched' flag is removed. 
    */
    refreshView: function () {
	    var self = this;
	    var options = this.options;
	    var data = this.data;
	    var dico = {};
	    for (var i = 0; i < data.rows.length; i ++)
	        dico [data.rows [i].values [data.key]] = data.rows [i];
	    var containerElt = document.getElementById (options.containerId); 
	    var rowElts = containerElt.getElementsByClassName ("aiflg-datarow"); // (1)
	    for (var i = 0; i < rowElts.length;) {
	        var rowElt = rowElts [i];
	        var dataRow = dico [rowElt.dataset.key]; // (2)
	        if (dataRow) { // (3)
		        self.updateRowView (dataRow, rowElt);
		        dataRow.touched = true;
                i++
	        } else {
		        $(rowElt).remove ();
	        }
	    }

	    for (var i = 0; i < data.rows.length; i ++) { // (4)
	        var dataRow = data.rows [i];
	        if (dataRow.touched) {
		        delete dataRow.touched;
	        } else { // (5)
		        self.createRowElement (dataRow)
		            .appendTo ($("#" + options.tableId + "-tbody"));
	        }
	    }
    },

    /*
      key: the id of the currently edited/created item;
      field: the field currently checked;
      value: the given value of the field
    */
    checkUniqueWith: function (key, field, value) {
        var auxFieldValue;
        var auxFieldName = field.uniquewith;
        var fields = this.data.fields;
        // Get the auxiliary value
        for (var j = 0; j < fields.length; j ++) {
            var auxField = fields [j];
            if (auxField.name === auxFieldName) {
                auxFieldValue = $("#" + auxField.inputId).val ().trim ();
                break; } }
        // Checks if the auxilary value is found and not blank
        if (SoDAD.isUndefined (auxFieldValue) || (auxFieldValue.length === 0)) return;
        // loops in the table to find duplicates
        var rows = this.data.rows;
        for (var r = 0; r < rows.length; r ++) {
            var values = rows [r].values;
            var thisAuxFieldValue = values[auxFieldName];
            if (thisAuxFieldValue !== null) {
                thisAuxFieldValue = thisAuxFieldValue.trim ();
                var thisValue = values[field.name].trim ();
                var auxKey = values[this.data.key]; 
                // it is ok to be equal to oneself
                if ((thisAuxFieldValue === auxFieldValue) && (thisValue === value) && (key !== auxKey))
                    return field.name + " must be unique"; } } },

    checkFields: function (key) {
        var errMsg;
        var fields = this.data.fields;
        for (var i = 0; i < fields.length; i ++) {
            var field = fields [i];
            if (field.inputId && ! field.noneditable && ! this.options.noneditable) {
                var value = $("#" + field.inputId).val ();
                if (value === null) return;
                value = value.trim ();
                if (value.length > 0 && SoDAD.isDefined (field.uniquewith)) {
                    errMsg = this.checkUniqueWith (key, field, value);
                    if (SoDAD.isDefined (errMsg)) return errMsg; } } } },

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
	        .click ((function (key) {  // to create a closure
		        return function () {
		            for (var i = 0; 
                         i < self.data.rows.length && self.data.rows[i].values [self.data.key] !== key; 
                         i ++);
		            var dataRow = self.data.rows [i];
		            var newDataRow = {values: $.extend ({}, dataRow.values)};
		            var f = $('#' + self.options.editForm.containerId);
		            var form = $("#" + self.options.editForm.containerId + "-form");
                    SoDAD.resetFormMutableSelectOptions (form);
		            form.off ("submit");
                    /*
                      behavior when the data is submitted
                    */
		            form.submit (function (e) {
                        // Validation
                        var errMsg = self.checkFields (key);
                        if (SoDAD.isDefined (errMsg)) {
                            alert (errMsg);
                            return false;
                        }
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
				                    var nonEditable = field.noneditable || dataRow.options [field.name].noneditable;
				                    var e = $("#" + field.inputId);
				                    e[0].disabled = nonEditable;
				                    e.val (value);
				                }})}})(dataRow.values [self.data.key]))
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
    
    /*
      Creates a brand new row element.
      The row HTML element is linked to the data by its id that is set to the value 
      of the key of the dataRow (1). Note that this may be a problem, and a prefix may be needed.
      A empty cell is provided to welcome buttons that are created just after (2), (3).
      Then for each visible field an HTML cell is created. The link wirth the specific piece of data is
      achieved with the 'data-fname' attribute (4).
      At least we call 'updateRowView' to fill in the data (5).
    */
    createRowElement: function (dataRow) {
	    var self = this;
	    var row = $("<tr/>",
		            {"data-row": dataRow.id,
		             "data-key": dataRow.values [this.data.key],
		             "id": dataRow.values [this.data.key] // (1)
		            }).addClass ("aiflg-datarow");
	    var c = $ ("<td/>").appendTo (row); // (2)

	    this.createRowEditBtnElement (dataRow).appendTo (c);
	    this.createRowRemoveBtnElement (dataRow).appendTo (c); // (3)
	    var I = this.data.I;
/*
	    for (i = 0; i < I.length; i ++) {
	        var field = this.data.fields [I[i]];
            if (field.invisible) continue;
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
*/
	    for (i = 0; i < I.length; i ++) {
	        var field = this.data.fields [I[i]];
            if (! field.invisible)
	            $("<td/>", {"data-fname": field.name}).appendTo (row); // (4)
	    }
        this.updateRowView (dataRow, row); // (5)
	    return row;
    },
    
    toHTMLTable: function () {
	    var self = this;
	    var x = this.data;
	    var options = this.options;
	    
        /*
          Adds the 'New' button behavior.
          When the user clicks on this button, this hendler reset the 'submit' 
          method of the form (2).
          Then it calls the 'newElement' method to ask the server for initial data
          to feed in the new record: typically an id (1).
          Note that the 'newElement' method takes care of populating the form fields,
          and provides the initial data, as returned by the server, in 'initData.received'
         */
	    $("#" + options.containerId + "-addbtn").click (
	        function () {
		        var f = $('#' + options.editForm.containerId);
		        var form = $("#" + options.editForm.containerId + "-form");
                var initData = {};
                SoDAD.resetFormMutableSelectOptions (form);
		        form.off ("submit"); // (2)
                /*
                  The 'submit' handlerr begin by checking if the filds are ok. (6). 
                  Then it browses the fields of the form (that have been 
                  provided by the user), and populate the dataRow entries with these data (3).
                  Then it fills the remeining entries with the data provided by the server (4).
                  At the end it just hide the modal element (5).
                 */
		        form.submit (function (e) { 
                    // Validation
                    var errMsg = self.checkFields (''); // (6)
                    if (SoDAD.isDefined (errMsg)) {
                        alert (errMsg);
                        return false;
                    }
                   
		            var dataRow = {
			            id: 1000,
			            editable: true,
			            deletable: true,
			            values: {}
		            };
		            $.each (x.fields,
			                function (_, field) {
				                if (field.inputId) {
				                    dataRow.values [field.name] = $("#" + field.inputId).val (); // (3)
				                }
			                });
                    if (SoDAD.isDefined (initData.received)) 
                        for (initField in initData.received) 
                            dataRow.values [initField] = initData.received [initField]; // (4)
                    
		            self.addRow (dataRow);
		            e.preventDefault ();
		            $("#" + options.editForm.containerId)
			            .modal ('hide'); // (5)
                    //		    self.refreshView ();
		            return false;
		        });
		        options.newElement (x.fields, initData); // (1)
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
	        var field = this.data.fields [I[i]];
            if (field.invisible) continue;
	        c = $("<th/>").appendTo (row).text (field.label);
//	        c = $("<th/>").appendTo (row).text (x.fields [I [i]].label);
	    }
	    
        /// CREATES BODY AND DATA ROWS
	    var tbody = $('<tbody/>', {id: options.tableId + "-tbody"}).appendTo (table);;
	    for (j = 0; j < x.rows.length; j ++) 
	        this.createRowElement (x.rows [j]).appendTo (tbody);

	    return table;
    },
    
    /*
      Given the set of fields, creates the form elements. 
      The interesting part is (1), that calls iteratively 'createInputForField'
      to create each element.
    */
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

	    $.each (sortIndexes (fields, "frank"), // (1)
		        function (i) { 
		            var field = fields [i];
                    if (field.invisible) return;
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

/*
  Creates the input form element specific for this field.
  Type of element is decided upon the value of 'field.type', that can take the following values:
  "text", "text multiple", or "select".
  When the type is "select" the field must come with a, 'options' attribute. 
  According to the type of the 'options' attribute, the '<option> may be defined by:
  - either the items of 'options' itself, if this is an array (1)
  - or the items of a common array which name is the value of 'options', if this is a string (2)
  In the latter case the class of the '<select>' element is set to "aiflg-form-select-mutable"
  so that it will be retrieved when the form will be displayed, and its childs refreshed from the common.
  Also, a data ("source") is set with the name of the common. 
*/
function createInputForField (field) {
    var e;
    switch (field.type) {
    case "text":
	    e = $("<input/>", {type: "text", "class": "form-control"});
	    break;
    case "select":
	    e = $("<select/>", {"class": "form-control"});
        switch ($.type (field.options)) {
        case "array": // (1)
	        $.each (field.options,
		            function (_, opt) {
		                $("<option/>", {value: opt.value})
			                .text (opt.label)
			                .appendTo (e)
		            });
            e.addClass ("aiflg-form-select-static");
            break;
        case "string": // (2)
            e.addClass ("aiflg-form-select-mutable");
            e.data ("source", field.options);
            break;
        }
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

/*
  Find the mutable <select> elements for the form: 
  these elements have class "aiflg-form-select-mutable" (1)
  For each of these elements find the name of the common (2)
  and if everything is ok clean the content of the <select> element
  and add a new <option> for each line of the common (3)
*/
SoDAD.resetFormMutableSelectOptions  = function (form) {
    var mutableSelectElements = $(".aiflg-form-select-mutable", form); // (1)
    $.each (mutableSelectElements, function (_, e) {
        e = $(e);
        var source = e.data ("source"); // (2)
        if (SoDAD.isDefined (source)) {
            var options = SoDAD.commons [source];
            if (SoDAD.isDefined (options)) {
                e.empty ();
                for (var i = 0; i < options.length; i ++) { // (3)
                    var opt = options [i];
                    $("<option/>", {value: opt.value})
                        .text (opt.label)
                        .appendTo (e);
                }
            }
        }
    });
}
