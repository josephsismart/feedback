<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $system_title ?> | <?= $page_title ?></title>

  <link rel="icon" type="image/png" href="<?= $system_svg ?>">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="<?= base_url() ?>dist/css/fonts.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url() ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>plugins/toastr/toastr.min.css">
  <!-- DataTables -->
  <style>
    body {
      background: #f8f9fa;
      padding: 20px;
      font-family: Arial;
    }

    .section {
      display: none;
      max-width: 600px;
      margin: auto;
      padding: 20px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, .1);
    }

    .section h2 {
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div id="section_consent" class="section" style="display:block;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0 font-weight-bold">Feedback Reports</h4>

      <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>

    <hr>

    <form method="get" action="<?= base_url('admin/feedback') ?>" class="row g-2 mb-4">

    <div class="col-md-3">
      <label class="small text-muted">From Date</label>
      <input type="date" name="from_date" class="form-control form-control-sm" value="<?= $this->input->get('from_date') ?>" required>
    </div>

    <div class="col-md-3">
      <label class="small text-muted">To Date</label>
      <input type="date" name="to_date" class="form-control form-control-sm" value="<?= $this->input->get('to_date') ?>" required>
    </div>

    <div class="col-md-2 d-flex align-items-end">
      <button class="btn btn-success btn-sm w-100" onclick="$('#view_data').slideToggle()">
        <i class="fas fa-search"></i> Search
      </button>
    </div>

    </form>
    <div id="view_data" style="display:none;">
      <div class="row mb-4">

        <div class="col-md-3">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <h6 class="text-muted">Total</h6>
              <h3 class="font-weight-bold">500</h3>
              <h6><a href="#" data-toggle="modal" data-target="#commentsModal">view data</a></h6>

            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm border-left border-success">
            <div class="card-body text-center">
              <h6 class="text-success">Positive</h6>
              <h3 class="font-weight-bold">250</h3>
              <h6><a href="<?= base_url('admin/feedback') ?>">view data</a></h6>

            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm border-left border-danger">
            <div class="card-body text-center">
              <h6 class="text-danger">Negative</h6>
              <h3 class="font-weight-bold">200</h3>
              <h6><a href="<?= base_url('admin/feedback') ?>">view data</a></h6>

            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm border-left border-warning">
            <div class="card-body text-center">
              <h6 class="text-warning">Neutral</h6>
              <h3 class="font-weight-bold">50</h3>
              <h6><a href="<?= base_url('admin/feedback') ?>">view data</a></h6>

            </div>
          </div>
        </div>

      </div>



      <div class="card mb-4 shadow-sm">
        <div class="card-header">
          <h6 class="mb-0 font-weight-bold">Top 10 Most Mentioned Words</h6>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm table-striped mb-0">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Word</th>
                <th>Frequency</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>teacher</td>
                <td>120</td>
              </tr>
              <tr>
                <td>2</td>
                <td>service</td>
                <td>98</td>
              </tr>
              <tr>
                <td>3</td>
                <td>slow</td>
                <td>85</td>
              </tr>
              <tr>
                <td>4</td>
                <td>good</td>
                <td>82</td>
              </tr>
              <tr>
                <td>5</td>
                <td>internet</td>
                <td>76</td>
              </tr>
              <tr>
                <td>6</td>
                <td>staff</td>
                <td>65</td>
              </tr>
              <tr>
                <td>7</td>
                <td>helpful</td>
                <td>60</td>
              </tr>
              <tr>
                <td>8</td>
                <td>process</td>
                <td>54</td>
              </tr>
              <tr>
                <td>9</td>
                <td>queue</td>
                <td>47</td>
              </tr>
              <tr>
                <td>10</td>
                <td>response</td>
                <td>39</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>


  </div>

  <!-- <div class="modal fade show" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true" style="display: block; padding-left: 0px;"> -->
  <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" style="border-radius:0;">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="commentsModalLabel">Total Comments</h1>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">


          <div class="card shadow-sm">
            <div class="card-header">
              <h6 class="mb-0 font-weight-bold">Feedback Details</h6>
            </div>

            <div class="card-body p-0">
              <table class="table table-sm table-bordered mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Date</th>
                    <th>Sector</th>
                    <th>Category</th>
                    <th>Sentiment</th>
                    <th>Comment</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>2025-01-10</td>
                    <td>Education</td>
                    <td>Enrollment</td>
                    <td class="text-success">Positive</td>
                    <td>Very helpful staff and smooth process.</td>
                  </tr>
                  <tr>
                    <td>2025-01-09</td>
                    <td>Education</td>
                    <td>IT Support</td>
                    <td class="text-danger">Negative</td>
                    <td>Internet is slow and response is delayed.</td>
                  </tr>
                  <tr>
                    <td>2025-01-08</td>
                    <td>Education</td>
                    <td>Registrar</td>
                    <td class="text-warning">Neutral</td>
                    <td>Service was okay, but waiting time was long.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
  <script src="<?= base_url() ?>plugins/jquery/jquery.form.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>
  <script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>
  <script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>

  <script>
    function validate(form_id) {
      let invalid = 0;
      $($("#" + form_id).find("input").get().reverse()).each(function() {
        if ($("#" + form_id + ' input[type="search"]')) {
          // return 0;
        }
        if ($("#" + form_id + ' input[type="text"]')) {
          var name = clean($(this).attr("name"));
          var nr = $(this).attr("nr");

          if (name == null) {} else if (nr != 1) {
            if (!$(this).val()) {
              $(this).focus().addClass("is-invalid");
              $("#" + form_id + " ." + name).addClass('border-danger');
              invalid++;
            } else {
              $(this).removeClass("is-invalid");
              $("#" + form_id + " ." + name).removeClass('border-danger');
            }
          }
        }
      });
      valid = invalid;
    }

    function saveForm(formId, tblId, tbl, dtd, pl) {
      let a = "";
      var saveData = {
        clearForm: false,
        resetForm: false,
        beforeSubmit: function(e) {
          validate("form_save_data" + formId);
          if (valid != 0) {
            fillIn();
            return false;
          }
          a = $("#form_save_data" + formId + " .submitBtnPrimary").text();
          $("#form_save_data" + formId + " .submitBtnPrimary").attr("disabled", true);
          $("#form_save_data" + formId + " .submitBtnPrimary").html("<span class=\"fa fa-spinner fa-pulse\"></span>");
        },
        success: function(data) {
          var d = JSON.parse(data);
          if (d.success == true) {
            successAlert("Survey Form Successfully Submitted!");
            clear_form(formId);
            setTimeout(function() {
              location.reload();
            }, 1000)
            // if (d.success) {
            //     window.location.href = d.redirect_to;
            // }
          } else if (d.exist == true) {
            existAlert("User already exist!");
          } else if (d.fill == true) {
            existAlert("Please fill in the required fields");
          } else {
            failAlert("Something went wrong!");
          }
          $("#form_save_data" + formId + " .submitBtnPrimary").attr("disabled", false);
          $("#form_save_data" + formId + " .submitBtnPrimary").html(a);
        }
      };
      $("#form_save_data" + formId).ajaxForm(saveData);
    }
    // saveForm("SubmitSurveyForm", [null], null);
    // Enable proceed button
    function clear_form(b) {
      let f1 = "PersonnelInfo";
      let a = "form_save_data" + b;
      $("#" + a)[0].reset();
      $("#" + a).find("input[type='hidden']").each(function() {
        $(this).val("");
      });
      $("#" + a).find("input[type='checkbox']").each(function() {
        $(this).attr("checked", false);
      });
      if (b == f1) {} else {
        $("#" + a).find("select").each(function() {
          $(this).trigger("change");
        });
      }

      $("#" + a + " .submitBtnPrimary").attr("disabled", false);
      $("#" + a + " .submitBtnPrimary").html("Save Data");
      $("#" + a + " .clearBtn").html("Clear");
      $("#" + a + " .submitBtnPrimary").removeClass("btn-info").addClass("btn-primary");
      $("#" + a + " .clearBtn").removeClass("btn-danger");

      // defaultImg('pic', 'previewPic', 'imgtargetLink', 'MALE');

    }

    // $('#btnSubmit').click(() => alert("Feedback submitted successfully!"));
    function clean(a) {
      var str = a;
      return str === undefined ? null : str.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
    }
    const Toast = Swal.mixin({
      toast: true,
      position: 'center',
      showConfirmButton: false,
      timer: 3000
    });

    function successAlert(a) {
      toastr.success(a)
    }

    function failAlert(a) {
      Toast.fire({
        icon: 'error',
        title: '  ' + a
      })
    }

    function fillIn() {
      toastr.info('Kindly fill up the required fields of the survey.')
    }

    function existAlert(a) {
      Toast.fire({
        icon: 'warning',
        title: '  ' + a
      })
    }

    function noData(a) {
      Toast.fire({
        icon: 'warning',
        title: '  ' + a,
      })
    }

    $('.toastrDefaultSuccess').click(function() {
      toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultInfo').click(function() {
      toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultError').click(function() {
      toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
    $('.toastrDefaultWarning').click(function() {
      toastr.warning('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
  </script>
</body>

</html>