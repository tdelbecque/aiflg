function requiredFieldValidator(value) {
	if (value == null || value == undefined || !value.length) {
		return {valid: false, msg: "This is a required field"};
	} else {
		return {valid: true, msg: null};
	}
}

function strCompare (a, b) {
	return (''+a).localeCompare (''+b)
}

function attachGrid (grid, panelId, otherPanelIds, menuId, action) {
	var e = document.getElementById (menuId)
	e.onclick = function () {
		var request = new XMLHttpRequest()
		request.open ("GET", action)
		request.onreadystatechange = function () {
			if (request.readyState === 4 && request.status === 200) {
				var type = request.getResponseHeader ('Content-type')
				if (type === "application/json") {
					var data = JSON.parse (request.responseText)
					grid.voidGridData ()
					for (var i = 0; i < data.data.length; i ++)
						grid.pushDataRow (data.data [i])
					grid.refresh ()
				} 
				else {
					alert ("bad Content-type header : " + type + request.responseText)
				}
			}
			else if (request.readyState == 4) {
				alert ("arg : " + request.statusText)
			}
		}
		request.send (null)
                $("#" + panelId).show()
		//document.getElementById (panelId).style.display = "initial"
		for (var i = 0; i < otherPanelIds.length; i ++) {
			//$document.getElementById (otherPanelIds [i]).style.display = "none"
                        $("#" + otherPanelIds [i]).hide()
		}
	}
}
