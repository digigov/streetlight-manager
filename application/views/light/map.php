<?php include(__DIR__."/../_header.php"); ?>

<?php function css_section(){  ?>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
  <link rel="stylesheet" href="<?=base_url("css/MarkerCluster.css")?>" />
  <link rel="stylesheet" href="<?=base_url("css/MarkerCluster.Default.css")?>" />
  <link rel="stylesheet" href="<?=base_url("js/jquery-ui-1.12.1.custom/jquery-ui.min.css")?>">

  <style>
    hr{
      border-top:1px solid #ccc;
    }
    .ui-widget.ui-widget-content{
      z-index:1000;
    }
    .ui-autocomplete {
      max-height: 300px;
      width:400px;
      overflow-y: auto;
      /* prevent horizontal scrollbar */
      overflow-x: hidden;
    }
    /* IE 6 doesn't support max-height
     * we use height instead, but this forces the menu to always be this tall
     */
    * html .ui-autocomplete {
      height: 300px;
      width:400px;
    }
  </style>
<?php } ?>

<div id="container" class="container">
  <h1 style="text-align:center;"> 路燈清單</h1>
  <div class="alert alert-info">
    目前測試中，僅開放鹿草鄉與水上鄉資料。
  </div>
  <p id="waiting" class="alert alert-info">請稍後，資料載入中....</p>

  <p>燈號搜尋：<input type="text" name="search" /></p>
  
  <div id="mapid" style="width: 100%; height: 600px"></div>
  
  <p>最後回報與更新時間：<?=_date_format_utc($last_report_update_time)?></p>
  <hr />
  <p>Power by <a href="https://github.com/tony1223/" target="_blank">智慧城市與青年創業推動辦公室</a> </p>
  

  <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>

  <script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script> 


   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSIFJslwcgjr4ttFgt0TX3KSG6sqLkzY8"
        ></script>
  <script src='https://unpkg.com/leaflet.gridlayer.googlemutant@latest/Leaflet.GoogleMutant.js'></script>

  <script src="<?=base_url("js/leaflet.markercluster.js")?>"></script>

  <script src="<?=base_url("js/jquery-ui-1.12.1.custom/jquery-ui.js")?>"></script>


  <script>

    var getPoint = function(ary){
      return {
        id:ary[0],
        name:ary[1],
        lat:ary[2],
        lng:ary[3],
        city:ary[4],
        status:ary[5],
        reporting_count:ary[6]
      }
    };
    

    var center =  [23.413554,120.372697];
    
    var mymap = L.map('mapid',{maxZoom:18,minZoom:12}).setView(center, 12);
    var autocompletes = [];

    
    $.get("/light/json_pointers",function(res){

      if(!res.isSuccess || !res.data){
        $("#waiting").text("載入失敗");
        return true;
      }

      $("#waiting").remove();

      var markers = L.markerClusterGroup({disableClusteringAtZoom:17,spiderfyOnMaxZoom:false});

      var points = res.data;
      var point = getPoint(points[0]);
      $.each(points,function(ind,point_ary){
        var point = getPoint(point_ary);
        if(point.status == 0){

          if(point.reporting_count > 0 ){
            var redIcon = new L.Icon({
              iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
              shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
              iconSize: [25, 41],
              iconAnchor: [12, 41],
              popupAnchor: [1, -34],
              shadowSize: [41, 41]
            });

            var marker = L.marker([point.lat,point.lng],{icon:redIcon,title:point.name});
            marker.bindPopup('<h1>'+point.name+'</h1><p>所屬：'+point.city +'</p><p style="color:red;">已被回報，尚待確認。</p>'+
              '<a target="_blank" href="<?=site_url('light/report/')?>/'+point.id+'">我也要回報這個路燈</a><p></p>');
            
            // markers.addLayer(marker);
            mymap.addLayer(marker);
          }else{
            var marker = L.marker([point.lat,point.lng],{title:point.name});
            marker.bindPopup('<h1>'+point.name+'</h1><p>所屬：'+point.city+'</p>'+
              '<a target="_blank" href="<?=site_url('light/report/')?>/'+point.id+'">回報路燈問題</a><p></p>');
            markers.addLayer(marker);          
          }

        }else{
          var redIcon = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
          });
          var marker = L.marker([point.lat,point.lng],{icon:redIcon,title:point.name});
          marker.bindPopup('<h1>'+point.name+'</h1><p>所屬：'+point.city+'</p><p style="color:red;">路燈報修中</p>');
          
          // markers.addLayer(marker);
          mymap.addLayer(marker);
        }
        point.marker = marker;
        autocompletes.push({label:
          point.name + " - " + point.city
        ,value:point});      
      });

      // debugger;

      mymap.addLayer(markers);

    });

    $( "[name=search]" ).autocomplete({
        minLength: 1,
        delay: 0 ,
        source: function(req,res){
          res( $.grep( autocompletes, function( item ){
              return item.label.indexOf(req.term) != -1;
          }).slice(0,100) );
        },
        select: function( event, ui ) {
          mymap.setView(ui.item.value.marker.getLatLng(),18);
          ui.item.value.marker.openPopup();
          return false;
        }
      });

    var osm = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mymap);

    // var ggl = new L.Google();
    var roads = L.gridLayer.googleMutant({
        type: 'roadmap' // valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
    }).addTo(mymap);

    var roads2 = L.gridLayer.googleMutant({
        type: 'satellite' // valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
    }).addTo(mymap);

    mymap.addControl(new L.Control.Layers( {'開放街圖':osm,"Google 衛星":roads2,'Google':roads}, {}));
  </script>
  
</div>

<?php function js_section(){ ?>

<?php } ?>


<?php include(__DIR__."/../_footer.php"); ?>