<?php include(__DIR__."/../_header.php"); ?>

<?php function css_section(){  ?>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
  <link rel="stylesheet" href="<?=base_url("css/MarkerCluster.css")?>" />
  <link rel="stylesheet" href="<?=base_url("css/MarkerCluster.Default.css")?>" />

  <style>
    hr{
      border-top:1px solid #ccc;
    }
  </style>
<?php } ?>

<script>
  window.points = <?=json_encode($points)?>;
  if(window.points.push == null){
    window.points = [];
  }
</script>
<div id="container" class="container">
  <h1 style="text-align:center;"> 路燈修復回報（廠商版）</h1>
  <div class="alert alert-info">
  </div>

  <h2>所屬區域：<?=h($points[0]->city)?></h2>

  <h3>路燈位置</h3>
  <div id="mapid" style=" height: 300px;width:100%;max-width:400px;"></div>
  <p>&nbsp;</p>

  <div class="row col-md-8 col-xs-12">
    <a class="btn btn-primary" href="<?=site_url("/light/fixed/".$points[0]->id)?>" >已經修復</a>
    <a class="btn btn-default"  href="<?=site_url("/")?>" >取消</a>
  </div>
  <p class="clearfix"></p>

  
  <hr />
  <p>Power by <a href="https://github.com/tony1223/" target="_blank">智慧城市與青年創業推動辦公室</a> </p>

  <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>

  <script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script> 


   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSIFJslwcgjr4ttFgt0TX3KSG6sqLkzY8"
        ></script>
  <script src='https://unpkg.com/leaflet.gridlayer.googlemutant@latest/Leaflet.GoogleMutant.js'></script>

  <script src="<?=base_url("js/leaflet.markercluster.js")?>"></script>

  <script>


    // var center = [25.043325,121.5195076];
    var point = window.points[0];
    var center = [point.lat,point.lng];

    var mymap = L.map('mapid',{maxZoom:18,minZoom:10}).setView(center, 14);
    

    var markers = L.markerClusterGroup({disableClusteringAtZoom:17,spiderfyOnMaxZoom:false});

    $.each(window.points,function(ind,point){
        var marker = L.marker([point.lat,point.lng],{title:point.name});
        marker.bindPopup('<h1>'+point.name+'</h1><p>所屬：'+point.city+point.town_name+'</p>');

        markers.addLayer(marker);
        // mymap.addLayer(marker);
    });

    mymap.addLayer(markers);

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
