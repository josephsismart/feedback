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
  <link rel="stylesheet" href="<?= base_url() ?>dist/css/adminlte.min.css?v=3.2.0">

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

      <?php $this->load->view('interface/admin/_menu') ?>

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

      <div class="col-md-3">
        <label class="small text-muted">Category</label>
        <select name="category_id" id="category_id" class="form-control text-uppercase form-control-sm" nr="1">
          <?php
          // 1) get all categories once

          $logid = $this->session->feedback_login_id;
          if ($logid == 1) {
            echo '<option value="">NO FILTER</option>';
          }

          $categories = $this->db
            ->order_by('parent_id ASC, order_by ASC')
            ->where('is_active', 1)
            ->get('category')
            ->result();

          // 2) group by parent_id
          $tree = [];
          foreach ($categories as $cat) {
            $tree[$cat->parent_id][] = $cat;
          }

          // 3) recursive printer (DEFINED HERE)
          $renderOptions = function ($parent_id = null, $level = 0) use (&$renderOptions, $tree) {

            if (!isset($tree[$parent_id])) return;
            $c_id = [$this->session->feedback_login_category_id_list];
            $l_id = $this->session->feedback_login_id;

            foreach ($tree[$parent_id] as $cat) {

              $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
              if ($l_id != 1) {
                if (in_array($cat->id, $c_id)) {
                  echo '<option value="' . $cat->id . '" selected>';
                  echo $indent . $cat->name;
                  echo '</option>';
                }
              } else {
                echo '<option value="' . $cat->id . '">';
                echo $indent . $cat->name;
                echo '</option>';
              }
              // children
              $renderOptions($cat->id, $level + 1);
            }
          };

          // 4) render tree
          $renderOptions();
          ?>
        </select>

      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-success btn-sm w-100" id="btnSearch">
          <i class="fas fa-search"></i> Search
        </button>
      </div>

      <!-- </form> -->
    </div>

    <div class="col-12" hidden>
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
        <div class="col-12" id="sentimentWord">
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

        selected_category_id = selectedValue;
        if (selectedValue == '') {
          loadCategories(null, 0);
        }
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

    function err(a) {
      toastr.error(a)
    }

    $('#btnSearch').on('click', function() {

      const from_date = $('#from_date').val();
      const to_date = $('#to_date').val();
      // const category_id = selected_category_id; //getFinalCategory();
      const category_id = $('#category_id').val();

      if (from_date === '' || to_date === '' || category_id === '') {
        existAlert('Please select a date range and category.');
        return;
      }

      $.getJSON("<?= base_url('admin/Report/get_job_factor_report') ?>", {
        from_date: from_date,
        to_date: to_date,
        category_id: category_id
      }, function(d) {

        if (d.empty_data == true) {
          err('No data found');
          $("#view_data").slideUp();
          return;
        }

        $("#view_data").slideDown();
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
        let s_percent = [];

        $.each(d.sentiment, function(key, row) {
          s_labels.push(row.rating_txt);
          s_values.push(row.count);
          s_meanings.push(row.rating_meaning);
          s_percent.push(row.percent);
        });

        $("#sentimentWord").html("");
        renderSentimentChart(s_labels, s_values, s_meanings, s_percent);
        if (d.negative_word) {
          $("#sentimentWord").html('<b>SENTIMENT WORD:</b> <br><i>' + d.negative_word + "</i> - <badge class='badge badge-danger'>NEGATIVE</badge>");
          $("#sentimentWord").append('<br><input style="width: 100%;" class="form-control form-control-sm" type="text" id="negativeSentimentWordInput" value="suggestion for ' + d.negative_word + ' about the school and make it short, more positive, concise and professional, anwer directly to the user, dont let the user to ask you, dont put \'Here\'s a suggestion:\'" hidden/>');
          $("#sentimentWord").append('<button onclick="askAI(\'negative\')" class="btn btn-primary btn-sm">Generate Suggestion</button>');
          $("#sentimentWord").append('<br><div id="negativeResult" style="font-style: italic; background-color: #efefefff; padding: 10px; border-radius: 5px;"></div>');
        }
        if (d.positive_word) {
          $("#sentimentWord").append('<br><b>SENTIMENT WORD:</b> <br><i>' + d.positive_word + "</i> - <badge class='badge badge-success'>POSITIVE</badge>");
          $("#sentimentWord").append('<br><input style="width: 100%;" class="form-control form-control-sm" type="text" id="positiveSentimentWordInput" value="suggestion for ' + d.positive_word + ' about the school and make it short, more positive, concise and professional, anwer directly to the user, dont let the user to ask you, dont put \'Here\'s a suggestion:\'" hidden/>');
          $("#sentimentWord").append('<button onclick="askAI(\'positive\')" class="btn btn-primary btn-sm">Generate Suggestion</button>');
          $("#sentimentWord").append('<br><div id="positiveResult" style="font-style: italic; background-color: #efefefff; padding: 10px; border-radius: 5px;"></div>');
        }
      });
    });

    async function askAI(type) {
      const word = $("#" + type + "Result").val();
      $("#" + type + "Result").text("Generating Suggestion...");

      try {
        const response = await $.getJSON(
          "<?= base_url('admin/Report/get_sentiment_word_ai') ?>", {
            word: $("#" + type + "SentimentWordInput").val()
          }
        );

        // 👇 waits here until AI responds
        $("#" + type + "Result").text(response.answer);

      } catch (e) {
        $("#" + type + "Result").text("AI error");
      }
    }

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

    function renderSentimentChart(labels, values, meanings, percent) {

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
                  return `${meanings[i]} : ${context.raw} (${percent[i]}%)`;
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