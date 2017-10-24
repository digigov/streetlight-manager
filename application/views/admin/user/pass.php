<?php include(__DIR__."/../_header.php"); ?>

<div id="container" style="text-align:center;">
  <h1>路燈管理後台 - 修改密碼</h1>
  
  <?php if($status == 1) { ?>
  <div class="alert alert-info">
    密碼更新成功
  </div>
  <?php } ?>

  <?php if($status == 2) { ?>
  <div class="alert alert-warning">
    密碼更新錯誤
  </div>
  <?php } ?>
  <br />
  <br />
  <div class="container">
    <div class="col-md-6 col-md-offset-3">
      <form action="<?=site_url("admin/user/passing")?>" method="POST">
        <table class="table table-bordered">
          <tr>
            <td>密碼</td>
            <td><input type="password" name="pwd" /></td>
          </tr>
          <tr>
            <td>確認密碼</td>
            <td><input type="password" name="pwd2" /></td>
          </tr>
          <tr>
            <td colspan="2">
              <button>送出</button>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>

</div>

<?php function js_section(){ ?>

<?php } ?>


<?php include(__DIR__."/../_footer.php"); ?>