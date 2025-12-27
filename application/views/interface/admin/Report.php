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
          <li class="list-group-item">
            <a href="<?= base_url('admin/dashboard') ?>">
              <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
          </li>

          <li class="list-group-item bg-success">
            <a href="<?= base_url('admin/report') ?>" class="text-white">
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
      <h4 class="mb-0 font-weight-bold">Feedback Reports</h4>

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

    <div class="col-12">
      <label class="small text-muted">Category</label>
      <div id="categoryWrapper">
      </div>
    </div>


    <div id="view_data" class="mt-4" style="display:none">


      <div class="row mb-4">
        <div class="col-md-6 col-12">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-3">Job Factor Summary</h6>
              <canvas id="jobFactorChart" height="320"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-12">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-3">Sentiment Summary</h6>
              <canvas id="sentimentChart" height="320"></canvas>
            </div>
          </div>
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
              <h6 class="mb-0 font-weight-bold">Feedback Reports</h6>
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

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    let selected_category_id = null;
    const openMenu = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    const sideMenu = document.getElementById('sideMenu');
    const overlay = document.getElementById('menuOverlay');

    openMenu.onclick = () => {
      sideMenu.classList.add('active');
      overlay.style.display = 'block';
    };
    $(document).ready(function() {

      // Load root categories
      loadCategories(null, 0);

      function loadCategories(parentId, level) {

        const url = parentId === null ?
          "<?= base_url('get-categories/') ?>0" :
          "<?= base_url('get-subcategories/') ?>" + parentId;

        $.getJSON(url, function(data) {

          // remove deeper selects
          $('.category-select').each(function() {
            if ($(this).data('level') > level) {
              $(this).remove();
            }
          });

          if (!data || data.length === 0) return;

          let select = $('<select/>', {
            class: 'form-control form-control-sm mt-2 category-select',
            'data-level': level + 1
          });

          select.append('<option value="">Select Category</option>');

          $.each(data, function(i, cat) {
            select.append(
              $('<option/>', {
                value: cat.id,
                text: cat.name
              })
            );
          });

          $('#categoryWrapper').append(select);
        });
      }

      // On change, load children
      $(document).on('change', '.category-select', function() {

        const selectedValue = $(this).val(); // ✅ SELECTED ID
        const level = $(this).data('level');

        console.log('Selected category ID:', selectedValue);

        if (selectedValue) {
          loadCategories(selectedValue, level);
        }
      });

    });

    function getFinalCategory() {
      const selects = $('.category-select');

      if (selects.length === 0) {
        return {
          value: null,
          valid: false,
          reason: 'no_select'
        };
      }

      const lastSelect = selects.last();
      const value = lastSelect.val();

      if (!value) {
        return {
          value: null,
          valid: false,
          reason: 'not_selected'
        };
      }

      return {
        value: value,
        valid: true,
        reason: 'ok'
      };
    }

    function existAlert(a) {
      toastr.warning(a)
    }

    $('#btnSearch').on('click', function() {

      const from_date = $('#from_date').val();
      const to_date = $('#to_date').val();
      const category_id = getFinalCategory();

      if (from_date === '' || to_date === '' || !category_id.valid) {
        existAlert('Please select a date range and category.');
        return;
      }

      $("#view_data").slideDown();

      $.getJSON("<?= base_url('admin/Report/get_job_factor_report') ?>", {
        from_date: from_date,
        to_date: to_date,
        category_id: category_id
      }, function(d) {

        console.log(d);

        if (!d || !d.job_factors || !d.sentiment) {
          alert('No data found');
          return;
        }

        /* =========================
         * JOB FACTORS
         * ========================= */
        let jf_labels = [];
        let jf_values = [];
        let jf_meanings = [];

        $.each(d.job_factors, function(key, row) {
          jf_labels.push(row.rating_txt);
          jf_values.push(row.count);
          jf_meanings.push(row.rating_meaning);
        });

        renderJobFactorChart(jf_labels, jf_values, jf_meanings);

        /* =========================
         * SENTIMENT
         * ========================= */
        let s_labels = [];
        let s_values = [];
        let s_meanings = [];

        $.each(d.sentiment, function(key, row) {
          s_labels.push(row.rating_txt);
          s_values.push(row.count);
          s_meanings.push(row.rating_meaning);
        });

        renderSentimentChart(s_labels, s_values, s_meanings);
      });
    });

    let jobFactorChart = null;

    function renderJobFactorChart(labels, values, meanings) {

      const ctx = document.getElementById('jobFactorChart').getContext('2d');

      if (jobFactorChart) {
        jobFactorChart.destroy();
      }

      jobFactorChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Job Factor Count',
            data: values,
            backgroundColor: [
              '#28a745', // VS
              '#17a2b8', // S
              '#ffc107', // D
              '#dc3545' // VD
            ]
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const i = context.dataIndex;
                  return `${labels[i]} - ${meanings[i]} : ${context.raw}`;
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });
    }

    let sentimentChart = null;

    function renderSentimentChart(labels, values, meanings) {

      const ctx = document.getElementById('sentimentChart').getContext('2d');

      if (sentimentChart) {
        sentimentChart.destroy();
      }

      sentimentChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: labels,
          datasets: [{
            data: values,
            backgroundColor: [
              '#28a745', // Positive
              '#dc3545', // Negative
              '#6c757d' // Neutral
            ]
          }]
        },
        options: {
          responsive: true,
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  const i = context.dataIndex;
                  return `${meanings[i]} : ${context.raw}`;
                }
              }
            }
          }
        }
      });
    }
  </script>
</body>

</html>