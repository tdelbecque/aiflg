
function GrilleParcelles (gridElementId) {
	var self = this
	this.elementId = gridElementId
	this.columns = [
		{id: "id_parcelle", name: "Parcelle", field: "f_parcelle", editor: Slick.Editors.Text, validator: requiredFieldValidator,
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_parcelle, self.dp.gridData[b].f_parcelle)}},
		{id: "id_nom_parcelle", name: "Nom parcelle", field: "f_nom_parcelle", sortable:true,
			sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_nom_parcelle.trim(), self.dp.gridData[b].f_nom_parcelle.trim())}},
		{id: 'id_surface', name: "Surface", field: "f_surface",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_surface, self.dp.gridData[b].f_surface)}},
		{id: 'id_no_exploitant', name: "No Exploitant", field: "f_no_exploitant",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_no_exploitant, self.dp.gridData[b].f_no_exploitant)}},
		{id: 'id_date_plantation', name: "Date Plantation", field: "f_date_plantation",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_date_plantation, self.dp.gridData[b].f_date_plantation)}},
		{id: 'id_ref_cadaste', name: "Ref Cadastre", field: "f_ref_cadaste",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_ref_cadaste, self.dp.gridData[b].f_ref_cadaste)}},
		{id: 'id_code_parcelle', name: "Code Parcelle", field: "f_code_parcelle",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_code_parcelle, self.dp.gridData[b].f_code_parcelle)}},
		{id: 'id_code_variete', name: "Code Variété", field: "f_code_variete",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_code_variete, self.dp.gridData[b].f_code_variete)}},
		{id: 'id_code_producteur', name: "Code Producteur", field: "f_code_producteur",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_code_producteur, self.dp.gridData[b].f_code_producteur)}},
		{id: 'id_fiche_bloquee', name: "Fiche Bloquée", field: "f_fiche_bloquee",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_fiche_bloquee, self.dp.gridData[b].f_fiche_bloquee)}},
		{id: 'id_annee', name: "Année", field: "f_annee",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_annee, self.dp.gridData[b].f_annee)}},
		{id: 'id_type_plant', name: "Type Plant", field: "f_type_plant",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_type_plant, self.dp.gridData[b].f_type_plant)}},
		{id: 'id_nb_plant', name: "Nb Plant", field: "f_nb_plant",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_nb_plant, self.dp.gridData[b].f_nb_plant)}},
		{id: 'id_densite', name: "Densité", field: "f_densite",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_densite, self.dp.gridData[b].f_densite)}},
		{id: 'id_type_abri', name: "Type Abri", field: "f_type_abri",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_type_abri, self.dp.gridData[b].f_type_abri)}},
		{id: 'id_type_chauffage', name: "Type Chauffage", field: "f_type_chauffage",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_type_chauffage, self.dp.gridData[b].f_type_chauffage)}},
		{id: 'id_itineraire', name: "Itinéraire", field: "f_itineraire",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_itineraire, self.dp.gridData[b].f_itineraire)}},
		{id: 'id_departement', name: "Département", field: "f_departement",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_departement, self.dp.gridData[b].f_departement)}},
		{id: 'id_volume_pm', name: "Volume pm", field: "f_volume_pm",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_volume_pm, self.dp.gridData[b].f_volume_pm)}},
		{id: 'id_precocite_pm', name: "Précocité pm", field: "f_precocite_pm",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_precocite_pm, self.dp.gridData[b].f_precocite_pm)}},
		{id: 'id_region', name: "Région", field: "f_region",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_region, self.dp.gridData[b].f_region)}},
		{id: 'id_fin_plantation', name: "Fin plantation", field: "f_fin_plantation",
			sortable:true,
				sdd_compare: function (a, b) {return strCompare (self.dp.gridData[a].f_fin_plantation, self.dp.gridData[b].f_fin_plantation)}}
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

