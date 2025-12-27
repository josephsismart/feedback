<?php
date_default_timezone_set('Asia/Manila');
if (!$this->session->feedback_login_id) {
  redirect(base_url('login'));
}
?>
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
  <link rel="stylesheet" href="<?= base_url() ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>plugins/toastr/toastr.min.css">
  <!-- DataTables -->
  <style>
    body {
      background: #f8f9fa;
      padding: 20px;
      font-family: Arial;
      font-family: 'Montserrat', 'Century Gothic', Arial, sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
    }

    /* body {
      background: #f8f9fa;
      padding: 20px;
      font-family: Arial;
    } */

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

    /* Dark overlay */
    #menuOverlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      display: none;
      z-index: 1040;
    }

    /* Side menu */
    #sideMenu {
      position: fixed;
      top: 0;
      right: -300px;
      width: 300px;
      height: 100%;
      background: #fff;
      box-shadow: -4px 0 10px rgba(0, 0, 0, 0.15);
      transition: right 0.3s ease;
      z-index: 1050;
    }

    #sideMenu.active {
      right: 0;
    }

    /* Header */
    .menu-header {
      padding: 15px;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    #sideMenu a {
      display: block;
      width: 100%;
      text-decoration: none;
      color: #333;
    }

    #sideMenu .list-group-item:hover {
      background: #f8f9fa;
    }
  </style>
</head>

<body>
  <nav class="navbar p-0 mb-5" style="background-color:#6f42c1;">
    <div class="w-100 d-flex justify-content-between align-items-center px-3" style="min-height:56px;">

      <!-- Left -->
      <span class="font-weight-bold text-white">
        ONLINE FEEDBACK FORM
      </span>

      <!-- Right -->
      <button class="btn btn-outline-secondary btn-sm" id="openMenu">
        <i class="fas fa-bars text-white"></i>
      </button>
      <!-- OVERLAY -->
      <div id="menuOverlay"></div>

      <!-- SIDE MENU -->
      <div id="sideMenu">
        <div class="menu-header">
          <span class="font-weight-bold">Menu</span>
          <button class="btn btn-sm btn-light" id="closeMenu">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <ul class="list-group list-group-flush">
          <li class="list-group-item bg-success">
            <a href="<?= base_url('admin/dashboard') ?>" class="text-white">
              <i class="fas fa-chart-bar me-2"></i> Dashboard
            </a>
          </li>

          <li class="list-group-item">
            <a href="<?= base_url('admin/report') ?>">
              <i class="fas fa-chart-bar me-2"></i> Report
            </a>
          </li>

          <li class="list-group-item text-danger">
            <a href="<?= base_url('logout') ?>" class="text-danger">
              <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
          </li>
        </ul>
      </div>

    </div>
  </nav>

  <div id="section_consent" class="section" style="display:block;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0 font-weight-bold">Feedback Dashboard</h4>

      <!-- <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a> -->
    </div>

    <hr>

    <!-- <form method="get" action="<?= base_url('admin/feedback') ?>" class="row g-2 mb-4"> -->
    <div class="row g-2 mb-4">
      <div class="col-md-3">
        <label class="small text-muted">From Date</label>
        <input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="<?= Date('Y-m-d') ?>" required>
      </div>

      <div class="col-md-3">
        <label class="small text-muted">To Date</label>
        <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="<?= Date('Y-m-d') ?>" required>
      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-success btn-sm w-100" id="btnSearch">
          <i class="fas fa-search"></i> Search
        </button>
      </div>

      <!-- </form> -->
    </div>
    <div id="view_data" style="display:none;">


      <div class="row mb-4 root_name">
      </div>

      <div class="row mb-4">

        <div class="col-md-3">
          <div class="card shadow-sm border-left border-success">
            <div class="card-body text-center">
              <h6 class="text-success">Positive</h6>
              <h3 class="font-weight-bold positive">0</h3>
              <h6><a href="#" onclick="getComments(1);">view data</a></h6>

            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm border-left border-warning">
            <div class="card-body text-center">
              <h6 class="text-warning">Neutral</h6>
              <h3 class="font-weight-bold neutral">0</h3>
              <h6><a href="#" onclick="getComments(3);">view data</a></h6>

            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm border-left border-danger">
            <div class="card-body text-center">
              <h6 class="text-danger">Negative</h6>
              <h3 class="font-weight-bold negative">0</h3>
              <h6><a href="#" onclick="getComments(2);">view data</a></h6>

            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <h6 class="text-muted">Total</h6>
              <h3 class="font-weight-bold total">0</h3>
              <h6><a href="#" onclick="getComments(0);">view data</a></h6>

            </div>
          </div>
        </div>


      </div>



      <div class="card mb-4 shadow-sm">
        <div class="card-header">
          <h6 class="mb-0 font-weight-bold">Top 10 Most Mentioned Words</h6>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm table-striped mb-0" id="topWordsTable">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Word</th>
                <th>Frequency</th>
              </tr>
            </thead>
            <tbody id="topWordsBody">

            </tbody>
          </table>
        </div>
      </div>
    </div>


  </div>

  <!-- <div class="modal fade show" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true" style="display: block; padding-left: 0px;"> -->
  <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="border-radius:0;">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="commentsModalLabel">Total Comments</h1>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">


          <div class="card shadow-sm">
            <div class="card-header">
              <h6 class="mb-0 font-weight-bold">Feedback Dashboard</h6>
            </div>

            <div class="card-body p-0">
              <table class="table table-sm table-striped table-hover mb-0" id="tblComments" width="100%">
                <thead class="thead-light">
                  <tr>
                    <th width="1">Date</th>
                    <th width="1">Sector</th>
                    <th width="1">Category</th>
                    <th width="100">Comment</th>
                    <th width="1">Sentiment</th>
                  </tr>
                </thead>
                <tbody>

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
  <script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.js"></script>
  <script src="<?= base_url() ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>
  <script src="<?= base_url() ?>plugins/sweetalert2/sweetalert2.min.js"></script>
  <script src="<?= base_url() ?>plugins/toastr/toastr.min.js"></script>

  <script>
    let comment_filter = 0;
    getTable("Comments", 0, 10);

    const openMenu = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    const sideMenu = document.getElementById('sideMenu');
    const overlay = document.getElementById('menuOverlay');

    openMenu.onclick = () => {
      sideMenu.classList.add('active');
      overlay.style.display = 'block';
    };

    closeMenu.onclick = closeAll;
    overlay.onclick = closeAll;

    function closeAll() {
      sideMenu.classList.remove('active');
      overlay.style.display = 'none';
    }


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

    $('#btnSearch').click(function() {
      let from_date = $("#from_date").val();
      let to_date = $("#to_date").val();
      let $tbody = $("#topWordsBody");

      $.get("<?= base_url("admin/Dashboard/getFeedbackReports") ?>", {
          from: from_date,
          to: to_date
        },
        function(data) {
          $tbody.empty();
          var d = JSON.parse(data);
          console.log(d)
          $('.total').html(d.total);
          $('.positive').html(d.positive);
          $('.negative').html(d.negative);
          $('.neutral').html(d.neutral);

          $('.root_name').empty();

          for (let i = 0; i < d.root_names.length; i++) {
            const name = d.root_names[i].name;
            const fontSize = getFontSize(name);

            $('.root_name').append(`
                <div class="col-md-3 mb-3">
                  <div class="card shadow-sm border-left border-primary">
                    <div class="card-body text-center">
                      <h6 style="font-size:${fontSize};" class="text-muted text-uppercase">
                        ${name}
                      </h6>
                      <h3 class="font-weight-bold">${d.root_names[i].count}</h3>
                    </div>
                  </div>
                </div>
            `);
          }

          if (!d.top_words.length) {
            $tbody.append(
              "<tr><td colspan='3' class='text-center text-muted'>No data found</td></tr>"
            );
            return
          }
          for (let i = 0; i < d.top_words.length; i++) {
            $tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${d.top_words[i].name}</td>
                    <td>${d.top_words[i].count}</td>
                </tr>
            `);
          }
        }).done(function() {});
      if ($("#view_data").is(':visible') == false) {
        $('#view_data').slideToggle();
      }
    });


    function getFontSize(name) {
      const len = name.length;

      if (len <= 15) return '0.9rem'; // ACADEMIC GROUP
      if (len <= 25) return '0.8rem'; // ADMINISTRATIVE GROUP
      return '0.7rem'; // ADMINISTRATIVE SUPPORT GROUP
    }


    function getTable(tableId, dtd, pl) {
      var drawCounter = 0;
      $("#tbl" + tableId).DataTable().destroy();
      var table, table_data = $("#tbl" + tableId).DataTable({
        "order": [
          [0, "asc"]
        ],
        dom: 'Bfrtip',
        buttons: [],
        // searching: tableId == 'GradesList' ? false : true,
        "info": pl == -1 ? false : true,
        "paging": pl == -1 ? false : true,
        "ordering": pl == -1 ? false : true,
        "oLanguage": {
          "sSearch": ""
        },
        "processing": true,
        "serverSide": true,
        language: {
          searchPlaceholder: "Search...",
        },
        // pageLength: pl,// Options for records per page
        // lengthMenu: [
        //     [10, 25, 50, 100],
        //     [10, 25, 50, 100]
        // ],
        ajax: {
          url: "<?= base_url("admin/Dashboard/getComments") ?>",
          type: "POST",
          data: function(d) {
            drawCounter++;
            d.length = pl;
            d.draw = drawCounter;
            d.search.value = $('#tbl' + tableId + '_filter input').val();
            d.from = $("#from_date").val();
            d.to = $("#to_date").val();
            d.comment_filter = comment_filter;
          }
        },

        "lengthMenu": [5, 10, 25, 50, 100],
        "pageLength": pl,
      });

      $("#tbl" + tableId).on('draw.dt', function() {
        $(".searchBtn").attr("disabled", false);
        $(".searchBtn").html("<span class=\"fa fa-search\"></span>");
        dtd == 1 ? $("#tbl" + tableId).DataTable().destroy() : "";
        $(".collapse" + tableId).trigger('click');
      });
      $("#tbl" + tableId + "_filter").addClass("row");
      $("#tbl" + tableId + "_filter label").css("width", "97%");
      $("#tbl" + tableId + "_filter .form-control-sm").css("width", "97%");
    }

    function getComments($a) {
      comment_filter = $a;
      getTable('Comments', 0, 10);
      $('#commentsModal').modal('show');
    }

    // function viewComments(a = null) {
    //   $.get("<?= base_url("admin/Dashboard/getComments") ?>", {
    //       filter: a
    //     },
    //     function(data) {
    //       $tbody.empty();
    //       var d = JSON.parse(data);
    //       $('.total').html(d.total);
    //       $('.positive').html(d.positive);
    //       $('.negative').html(d.negative);
    //       $('.neutral').html(d.neutral);

    //       if (!d.top_words.length) {
    //         $tbody.append(
    //           "<tr><td colspan='3' class='text-center text-muted'>No data found</td></tr>"
    //         );
    //         return
    //       }
    //       for (let i = 0; i < d.top_words.length; i++) {
    //         $tbody.append(`
    //             <tr>
    //                 <td>${i + 1}</td>
    //                 <td>${d.top_words[i].word}</td>
    //                 <td>${d.top_words[i].frequency}</td>
    //             </tr>
    //         `);
    //       }
    //     }).done(function() {});
    //   if ($("#view_data").is(':visible') == false) {
    //     $('#view_data').slideToggle();
    //   }
    // }
  </script>
</body>

</html>