	function browser(){
		var userAgent = navigator.userAgent; 
		var isOpera = userAgent.indexOf("Opera") > -1;
		var browser_name=navigator.appName
		var b_version=navigator.appVersion
		var version=b_version.split(";");
		var trim_Version=version.length>1?version[1].replace(/[ ]/g,""):"UPON"; 
		if (isOpera) {
		return "Opera"
		}
		else if (userAgent.indexOf("Firefox") > -1) {
		return "FF";
		}
		else if (userAgent.indexOf("Chrome") > -1){
		return "Chrome";
		}
		else if (userAgent.indexOf("Safari") > -1) {
		return "Safari";
		}
		else if(browser_name=="Microsoft Internet Explorer"){
			if (trim_Version=="MSIE6.0") {
				return "IE6"; 
			} else if (trim_Version=="MSIE7.0") {
				return "IE7";
			} else if (trim_Version=="MSIE8.0") {
				return "IE8";
			} else {
				return "IE9+";
			}
		}
	}

	function PostAjax(url,postData,cfunc){
	    var xmlhttp;
	        if (window.XMLHttpRequest)
	          {// code for IE7+, Firefox, Chrome, Opera, Safari
	          xmlhttp=new XMLHttpRequest();
	          }
	        else
	          {// code for IE6, IE5
	          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	          }
	    xmlhttp.onreadystatechange=cfunc;
	    xmlhttp.open("POST",url,true);
	    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xmlhttp.setRequestHeader("X-Requested-With","XMLHttpRequest");
	    xmlhttp.send(postData);
	}
	function cp(filePath,ishtml){
		if(!ishtml){
			var myPDF = new PDFObject({url: filePath}).embed("chrom_pdf");
			if(browser()=="FF"){
				document.getElementById('firefox_pdf').style.display='block';
			}
			else if(browser()=="IE6"||browser()=="IE7"){
				alert("Please use the above version of IE8 or other browsers");
			}
			else {
				document.getElementById('chrom_pdf').style.display='block';
			}
		}
		else{
			document.getElementById('firefox_pdf').style.display='none';
			document.getElementById('chrom_pdf').style.display='none';
		}

	}