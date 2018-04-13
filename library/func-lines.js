function isImg (line){
	var isAnImg = false;
	var id= line.indexOf (".");
	var extention = line.substring (id);
	var lengthExtention = extention.length;
	if (lengthExtention >=2 && lengthExtention <5) isAnImg = true;
	return isAnImg;
}
function isItemList (line){
	var isAnItem = false;
	if (line.substring (0,2) ==='- ') isAnItem = line.substring(2);
	return isAnItem;
}
function isLink (line){
	var isALink = false;
	if (line.substring (0,7) ==='http://' || line.substring (0,8) ==='https://'){
		var listLink = line.split ('/');
		var posLast = listLink.length -1;
		if (! listLink [posLast]) posLast = posLast -1;
		isALink = listLink [posLast];
	}
	return isALink;
}
function isTableLine (line){
	var isATableLine = false;
	if (line.indexOf (' - ') >0) isATableLine = line.split (' - ');
	return isATableLine;
}
function isSubTitle (line){
	var isASubTitle = false;
	if (line.indexOf ('____ ') >=0){
		var posStart = line.indexOf (' ') +1;
		var posEnd = line.indexOf (' ____'),
		isASubTitle = line.substring (posStart, posEnd);
	}
	return isASubTitle;
}
function isParagraph (line){
	var isAnItem = isItemList (line);
	var isAnImg = isImg (line);
	var isATableLine = isTableLine (line);
	var isASubTitle = isSubTitle (line);
	var isALink = isLink (line);
	var isAParagraph = false;
	if (! isAnItem && ! isAnImg && ! isATableLine && ! isASubTitle && ! isALink) isAParagraph = true;
	return isAParagraph;
}
function tsvToTable (tsvText){
	var table =[];
	var tsvList = tsvText.split ('\n')
	tsvList.forEach (function (line){
		table.push (line.split ('\t'));
	});
	return table;
}