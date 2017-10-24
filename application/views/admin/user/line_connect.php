
<?php include(__DIR__."/../_header.php"); ?>

<div id="container" style="text-align:center;">
  <h1>路燈管理後台 - 綁定 line 通知</h1>


    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">使用手機綁定</div>

                <div class="panel-body">
                    <p> 1. 用 line 加入好友掃描 QRCode </p>
                    <p> 2. 點擊畫面上第一個一對一聊天室</p>
                    <p> 3. 捲到最底部按同意，即可透過 line 收到最新路燈回報訊息 </p>
                    
                    <img src="data:image/png;base64,<?=h($code64)?>" alt="barcode"   />
                
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">使用電腦綁定</div>

                <div class="panel-body">
                    <p> 1. 點擊 Line 登入鈕，輸入帳號密碼登入。 </p>
                    <p> 2. 點擊畫面上第一個一對一聊天室</p>
                    <p> 3. 捲到最底部按同意，即可透過 line 收到最新路燈回報訊息</p>
                    
                    <a class="btn btn-default" href="<?=h($auth_url)?>" >Line 登入</a>
                
                </div>
            </div>
        </div>
    </div>
 


</div>

<?php function js_section(){ ?>

<?php } ?>


<?php include(__DIR__."/../_footer.php"); ?>