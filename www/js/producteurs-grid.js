
function GrilleProducteurs (gridElementId) {
	var self = this
	this.elementId = gridElementId
	this.columns = [
//		{id: "id_star", name: "star", field: "f_star", formatter: formatter},
		{id: "id_code", name: "Code", field: "f_code", editor: Slick.Editors.Text, validator: requiredFieldValidator,
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_code, self.dp.gridData[b].f_code)}},
		{id: "id_nom", name: "Nom", field: "f_nom", sortable:true,
			sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_nom.trim(), self.dp.gridData[b].f_nom.trim())}},
		{id: 'id_adr1', name: "Adresse 1", field: "f_adr1", 
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_adr1, self.dp.gridData[b].f_adr1)}},
		{id: 'id_adr2', name: "Adresse 2", field: "f_adr2",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_adr2, self.dp.gridData[b].f_adr2)}},
		{id: 'id_adr3', name: "Adresse 3", field: "f_adr3",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_adr3, self.dp.gridData[b].f_adr3)}},
		{id: 'id_cp', name: "Code postal", field: "f_cp",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_cp, self.dp.gridData[b].f_cp)}},
		{id: 'id_ville', name: "Ville", field: "f_ville",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_ville, self.dp.gridData[b].f_ville)}},
		{id: 'id_telephone', name: "Téléphone", field: "f_telephone",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_telephone, self.dp.gridData[b].f_telephone)}},
		{id: 'id_fax', name: "Fax", field: "f_fax",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_fax, self.dp.gridData[b].f_fax)}},
		{id: 'id_mobile', name: "Mobile", field: "f_mobile",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_mobile, self.dp.gridData[b].f_mobile)}},
		{id: 'id_no_exploitant', name: "Numéro d'exploitant", field: "f_no_exploitant",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_no_exploitant, self.dp.gridData[b].f_no_exploitant)}},
		{id: 'id_email', name: "e-mail", field: "f_email",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_email, self.dp.gridData[b].f_email)}},
		{id: 'id_code_structure', name: "Code Structure", field: "f_code_structure",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_code_structure, self.dp.gridData[b].f_code_structure)}}
	];
	
	this.options = {
		asyncEditorLoading: false,
		autoEdit: false,
		editable: true,
		enableCellNavigation: true,
		enableColumnReorder: false
	};
  
	var dataProvider = function () {
		this.gridData = []
		this.getLength = function () {
			return this.gridData.length
		}
		this.getItem = function(index) {
			if (this.orderIndex === null) return this.gridData[index];
			if (this.asc) return this.gridData [this.orderIndex[index]]
			return this.gridData [this.orderIndex[this.gridData.length - 1 - index]]
		};
		this.getItemMetadata = function(index) {
			return null;
		};
		this.newData = function () {
			for (i = 0; i < self.columns.length; i ++) {
				var v = self.columns [i]
				if (v.sortable) 
					v.sdd_index = $.map($(Array(this.gridData.length)),function(val, i) { return i; }).
				sort (v.sdd_compare)					
			}
			this.orderIndex = null
		}
		this.setOrderColId = function (id, asc) {
			this.asc = asc
			for (var i = 0; i < self.columns.length; i ++) {
				if (self.columns [i].id === id) {
					this.orderIndex = self.columns [i].sdd_index
				}
			}
		}
	}
	this.dp = new dataProvider()
	this.grid = new Slick.Grid(gridElementId, this.dp, this.columns, this.options);
	this.grid.onSort.subscribe (function (e, arg) {
		self.dp.setOrderColId (arg.sortCol.id, arg.sortAsc)
		self.grid.invalidateAllRows();
		self.grid.render();		
	})
	this.grid.onCellChange.subscribe (
		function () {alert ('changed')}
	)

	this.voidGridData = function () {
		this.dp.gridData.length = 0
	}
	this.pushDataRow = function (r) {
		this.dp.gridData.push (r)
	}
	this.refresh = function () {
		this.dp.newData ()
		this.grid.invalidate ()
		this.grid.render ()
	}
}

