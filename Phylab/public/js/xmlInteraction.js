function SetXMLDoc_lab(labnum){
	var str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
	var tmp = $('.xmlInteraction').text();
	eval('str += ' + tmp);
	return str;
}
