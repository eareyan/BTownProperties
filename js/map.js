  var map, infoWindow;

$(document).ready(function () {
    $('#propertyMap').each(function () {
        var lat = $('span.lat').html();
        var lng = $('span.lng').html();
        var name = $('span.name').html();
        var address = $('span.address').html();
                
        makeMap(lat, lng);
        addPoint(lat, lng, name, address);
        
        addLandmarks();
    });
    return;
  });

  function makeMap(lat, lng) {
    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
      zoom: 13,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
  }

  function addPoint(lat, lng, name, address) {
       var latlng = new google.maps.LatLng(lat,lng);
       var title = "(" + lat + "," + lng + ")";
       var m = new google.maps.Marker({
           position: latlng,
           map: map,
           icon: "http://www.cs.indiana.edu/cgi-pub/mercerjd/images/loc.png",
           title: title,
           content: name + "<br/>" + address
       });   
       addPopup(m);
  }


  function addLandmarks() {
      var url = "http://www.cs.indiana.edu/cgi-pub/mercerjd/ajax/latlng.php";
      var icon = "http://www.cs.indiana.edu/cgi-pub/mercerjd/images/lib.png";

      $.getJSON(url, function (locs) {
        $.each(locs, function() {
           var latlng = new google.maps.LatLng(this.lat,this.lng);
	   var title = this.name + "(" + this.lat + "," + this.lng + ")\r\n" + "(" + this.distance + " miles)" +  "($" + this.price + ")";
           var m = new google.maps.Marker({
               position: latlng,
	       map: map,
	       icon: icon,
               content: this.name
	   });   
	   addPopup(m);
        });
    });
  }
  
  function addPopup(m) {
     google.maps.event.addListener(m, 'click', function () { 
         if(infoWindow) infoWindow.close();
         infoWindow = new google.maps.InfoWindow( { content: m.content } );
         infoWindow.open(map, m);
     });
  }
