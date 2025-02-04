<?php
$page_title = 'Sale Report';
require_once('../Model/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">
        <!-- Aquí puedes añadir un título si deseas -->
      </div>
      <div class="panel-body" style="background-color:#D9D1D0;">
          <form class="clearfix" method="post" action="/RicPlast3.1/views/sale_report_process.php" onsubmit="return validateForm()">
            <div class="form-group">
              <label class="form-label">Rango de fechas</label>
                <div class="input-group">
                  <input type="text" class="datepicker form-control" name="start-date" id="start-date" placeholder="De">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <input type="text" class="datepicker form-control" name="end-date" id="end-date" placeholder="A">
                </div>
            </div>
            <div class="form-group">
                 <button type="submit" name="submit" class="btn btn-primary">Generar informe</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<script>
  function validateForm() {
    const startDate = document.getElementById("start-date").value;
    const endDate = document.getElementById("end-date").value;
    
    if (!startDate || !endDate) {
      alert("Es obligatoria las fechas");
      return false; // Evita que el formulario se envíe
    }
    return true; // Permite que el formulario se envíe si ambas fechas están seleccionadas
  }
</script>

<?php include_once('../layouts/footer.php'); ?>
