<?php include(__DIR__."/../_header.php"); ?>

<div class="container" id="container" style="text-align:center;">
  <h1>路燈最近三十天的報修記錄</h1>
  
  
  <p>&nbsp;</p>
  <h2>報修情形</h2>

  <table class="table table-bordered">
    <tr>
      <td>報修時間</td>    
      <td>地區</td>
      <td>處理情形</td>
      <td>報修路燈編號</td>
      <td>最後更新時間</td>
    </tr>
    <?php 
    $report_status = ["0" => "回報確認中" , "1" => "已確認送修" , "2" => "無法確認問題或重複回報","3" => "已修復"];
    foreach($reports as $report){ ?>
    <tr>
      <td><?=_date_format_utc($report->created_at)?></td>    
      <td><?=h($report->city)?></td>
      <td><?=h($report_status[$report->status])?></td>
      <td><?=h($report->light_name)?></td>
      <td><?=_date_format_utc($report->updated_at)?></td>   
    </tr>
    <?php } ?>
  </table>
  
  <p>最後回報與更新時間：<?=_date_format_utc($last_report_update_time)?></p>

</div>

<?php function js_section(){ ?>

<?php } ?>


<?php include(__DIR__."/../_footer.php"); ?>