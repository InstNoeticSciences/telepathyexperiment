function getdata(map) {
	var request = GXmlHttp.create();
	request.open("GET", 'xml_map.php?last='+lastpost, true);
      	request.onreadystatechange = function() {
		if (request.readyState == 4) {
			if(request.status==200) {
				var xmlDoc = GXml.parse(request.responseText);
        			// obtain the array of markers and loop through it
				var markers = xmlDoc.documentElement.getElementsByTagName("post");
	        		//for (var i = 0; i < markers.length; i++) {
				var i =1;
				var retint = window.setInterval(function() {
					if(i>=markers.length) {
						clearInterval(retint);
						getdata(map);
						lastpost = 0;
					}else{
						var ptd = markers.length - i;
						document.getElementById('new_posts_count').innerHTML = 'New posts: '+ptd;
						map.clearOverlays();
						// obtain the attribues of each marker
       			   			var lat = parseFloat(markers[i].getAttribute("y"));
    						var lng = parseFloat(markers[i].getAttribute("x"));
						var point = new GLatLng(lat, lng);
      			    			var html = markers[i].getAttribute("post");
						var label = markers[i].getAttribute("user");
						var avatar ='<a href="../'+label+'"><img src="'+markers[i].getAttribute("avatar25link")+'" alt="'+label+'" style="float: left"/></a>';
        					var when = markers[i].getAttribute("when");
						var from = markers[i].getAttribute("location");
						lastpost = markers[i].getAttribute("pid");
						// create the marker
        					var marker = new GMarker(point);
						map.addOverlay(marker);
						map.openInfoWindow(point, avatar+'<span style="font-family: arial; font-size: 12px;"><u>'+label+'</u><br/>'+html+'<br /><span style="font-size:0.8em;">'+when+' from: <b>'+from+'</b></span></span>');
						map.panTo(point);

						i++;
					}

        			}, 4000);

			}
		}else{
			document.getElementById('new_posts_count').innerHTML = ' please wait while loading...';
		}
	}
    	request.send(null);

}
window.onload = function() {
	if (GBrowserIsCompatible()) {

		//initializing map
		var map = new GMap2(document.getElementById("map"));
		map.setCenter(new GLatLng(0, 0), 4);
		map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());
		map.openInfoWindow(new GLatLng(0, 0), '<img src="../logo/map_logo.png" alt="twittr clne" style="border: 0; height: 43px;" />');
  		//adding point of post after 4 sec.
		setTimeout(function () {getdata(map) }, 4000);


	}else{
		document.getElementById('new_posts_count').innerHTML = "sorry but your browser sux hard. update it and come again!";
	}
}
