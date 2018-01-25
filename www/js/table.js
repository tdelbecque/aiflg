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
    updateRowView: function (dataRow, rowElt) {
	    var fields = this.data.fields;
	    var fieldsJElts;
	    
	    if (! rowElt)
	        fieldsJElts = $("#" + this.options.tableId + " tr[data-key=" + dataRow.values[this.data.key] + "] td[data-fname]")
	    else
	        fieldsJElts = $("td[data-fname]", rowElt);

	    for (var i = 0; i < fieldsJElts.length; i ++) {
	        var x = fieldsJElts [i];
	        var e = $(x);
	        var fname = e.attr ("data-fname");
	        var field;
	        for (var j = 0; j < fields.length && fields [j].name !== fname; j ++);
	        field = fields [j];
            /*
	          var field = $.grep (fields,
			  function (o, _) {
			  return o.name === fname;
			  })[0];
            */
	        var str;
	        if (field.options) {
		        for (var j = 0; j < field.options.length && field.options [j].value !== dataRow.values [fname]; j ++);
		        /*
		          var option = $.grep (field.options,
				  function (o, _) {
				  return o.value === dataRow.values [fname]
				  })[0];
		          str = option ? option.label : '?';
		        */
		        str = (j === field.options.length ? '?' : field.options [j].label);
	        } else
		        str = dataRow.values [fname];
	        e.text (str);
	    }
    },

    /*
      Refreshes the table according to the content of 'this.data'

      gets the HTML container of the table (identified by 'options.containerId'),
      then the set of HTML rows, that can be recognized because their class is 'aiflg-datarow'.

      For each of these HTML rows, it tries to find the corresponding row in the data.
      If the row is found updates the HTML cells thanks to 'updateRowView' method and flagges the datarow 
      as touched, so that it will not be inserted latter in the function. In this case the couter variable 'i'
      is incremmented.
      Otherwise the row is removed. The counter variable is not incremented, as a collection 
      returned by getElementsByClassName is a live collection.

      Finaly the data rows are browsed. When a row is not flagged as 'touched' is is inserted in the
      table, as a brand new row. The 'touched' flag is removed. 
    */
    refreshView: function () {
	    var self = this;
	    var options = this.options;
	    var data = this.data;
	    var dico = {};
	    for (var i = 0; i < data.rows.length; i ++)
	        dico [data.rows [i].values [data.key]] = data.rows [i];
	    var containerElt = document.getElementById (options.containerId); 
	    var rowElts = containerElt.getElementsByClassName ("aiflg-datarow");
	    for (var i = 0; i < rowElts.length;) {
	        var rowElt = rowElts [i];
	        var dataRow = dico [rowElt.dataset.key];
	        if (dataRow) {
		        self.updateRowView (dataRow, rowElt);
		        dataRow.touched = true;
                i++
	        } else {
		        $(rowElt).remove ();
	        }
	    }

	    for (var i = 0; i < data.rows.length; i ++) {
	        var dataRow = data.rows [i];
	        if (dataRow.touched) {
		        delete dataRow.touched;
	        } else {
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
	        .click ((function (dataRow) {  // to create a closure
		        var key = dataRow.values [self.data.key];
		        return function () {
		            for (var i = 0; 
                         i < self.data.rows.length && self.data.rows[i].values [self.data.key] !== key; 
                         i ++);
		            var dataRow = self.data.rows [i];
		            var newDataRow = {values: $.extend ({}, dataRow.values)};
		            var f = $('#' + self.options.editForm.containerId);
		            var form = $("#" + self.options.editForm.containerId + "-form");
		            form.off ("submit");
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
		             "data-key": dataRow.values [this.data.key],
		             "id": dataRow.values [this.data.key]
		            }).addClass ("aiflg-datarow");
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
