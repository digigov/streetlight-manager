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
  window.points = <?=json_encode([$img->lat != null ? ["lat"=>$img->lat,"lng"=>$img->lng]:[]])?>;
  if(window.points.push == null){
    window.points = [];
  }
</script>
<div id="container" class="container">
  <h1 style="text-align:center;"> 道路坑洞報修</h1>
  <div class="alert alert-info">
    目前測試中，僅開放水上鄉回報。
  </div>


  <h2>所屬區域：水上鄉 </h2>

  <h3>報修單</h3>
  <div class="row col-md-8 col-xs-12">


    <?php if($img->lat != null){ ?>
      自動從照片中分析位置
      <h4>坑洞位置</h3>
      
    <div id="mapid" style=" height: 300px;width:100%;max-width:400px;"></div>
    <?php } ?>
    <form action="<?=site_url("road/reporting")?>" method="POST">
      <input type="hidden" name="img_id" value="<?=h($img->id)?>" />
      <table class="table table-bordered">
        <tr>
          <td>回報人</td>
          <td><input autofocus tabindex="1" style='width:100%' type="text" name="name" /></td>
        </tr>
        <tr>
          <td>聯絡電話</td>
          <td>
            <input tabindex="2" style='width:100%' type="text" name="contact" />
            <br />
            <span style="color:gray">若有需要時，我們將會與您確認具體的問題現況。</span>
          </td>
        </tr>
        <!-- <tr>
          <td>email (選填) </td>
          <td><input tabindex="3" style='width:100%' type="text" name="email" />
          <span style="color:gray">有新的進展時，系統將自動 email 通知您</span>
          </td>
        </tr> -->
        <tr>
          <td>位置</td>
          <td><p>請簡要描述坑洞所在的位置（如 oo路與yy路路口，附近地標等）</p>
            <textarea tabindex="4"  name="location"  style='width:100%' rows="5" ></textarea>
          </td>
        </tr> 
        <tr>
          <td>問題回報與附註</td>
          <td><textarea tabindex="5" style='width:100%' rows="5" name="comment" ></textarea>
            <span style="color:gray">
              狀況描述、照片說明及其他與路燈相關的訊息
            </span>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <a href="<?=site_url("/")?>" class="btn btn-default" >取消</a>
            <input type="submit" value="送出" class="btn btn-primary" />
          </td>
        </tr>
      </table>
    </form>
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
