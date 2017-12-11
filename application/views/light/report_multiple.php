<?php include(__DIR__."/../_header.php"); ?>

<?php function css_section(){  ?>
  <style>
    hr{
      border-top:1px solid #ccc;
    }
  </style>
<?php } ?>

<div id="container" class="container">
  <div class="page-header">
    <h1>快速路燈報修暨道路維修通報平台</h1>
  </div>
  <div class='row'>
    <div class="caption col-md-12">
        <h3>請選擇回報類型與輸入資料</h3>
    </div>
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">路燈報修</div>
        <div class="panel-body">
          <p>報修流程：輸入燈號 &gt; 填寫回報人資料 &gt; 完成修復</p>
          <p>請輸入路燈號碼：<input type="text" name="light" /></p>
          <br />
          (路燈電線杆上會有噴漆的路燈號碼，目前僅支援水上鄉、鹿草鎮範圍，若您找不到路燈號碼，可電洽公所回報。)
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">道路坑洞報修</div>
        <div class="panel-body">

          <form action="<?=site_url("road/upload")?>"  enctype="multipart/form-data" method="POST">
            <p>報修流程：上傳坑洞照片 &gt; 確認位置 &gt; 填寫回報人資料 &gt; 完成回報</p>
            <p>
              <label>上傳坑洞照片<input type="file" name="file"  /></label>
            </p>
            <br />
            <button>送出</button>
            (目前僅支援水上鄉範圍)
          </form>
        </div>
      </div>
    </div>
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
