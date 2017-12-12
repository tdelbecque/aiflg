SoDAD = {};

SoDAD.isUndefined = function (x) {
    var undef;
    return x === undef;
};

SoDAD.createGlyph = function (glyph, options, attr) {
    if (isUndefined (attr)) attr = {};
    var e =  $("<span/>", attr).
	addClass ("glyphicon").
	addClass ("glyphicon-" + glyph).
	css ("paddingLeft", "2px").
	css ("paddingRight", "2px");
    
    if (! options.visible) e.css ("visibility", "hidden");
    return e;
};

SoDAD.sortIndexes = function (fields, criteria, sortFun) {
    if (isUndefined (sortFun))
	sortFun = function (a, b) {
	    if (isUndefined (a))
		return isUndefined (b) ? 0 : -1;
	    if (isUndefined (b) || b < a) return 1;
	    return a < b ? -1 : 0;
	}
    return $
	.map (fields, function (_, i) {return i})
	.sort (function (a, b) {
	    return sortFun (fields [a][criteria], fields [b][criteria]) });
};

