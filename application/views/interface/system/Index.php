<?php
$sectors = [
    ["id" => 1, "name" => "STUDENT"],
    ["id" => 2, "name" => "PARENT / GUARDIAN"],
    ["id" => 3, "name" => "VISITOR"],
];

$categories = [
    ["id" => 1, "name" => "ENROLLMENT"],
    ["id" => 2, "name" => "DOCUMENT REQUEST"],
    ["id" => 3, "name" => "GENERAL CONCERNS"],
];

$services = [
    "STAFF APPEARANCE",
    "STAFF HELPFULNESS",
    "SPEED/ EFFICIENCY",
    "JOB KNOWLEDGE",
    "QUALITY OF SERVICE"
];
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
    <link rel="stylesheet" href="<?= base_url() ?>plugins/bootstrap/css/bootstrap.min.css">
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

    <!-- SECTION 1: CONSENT -->
    <div id="section_consent" class="section" style="display:block;">
        <h2 class="text-center">Client Feedback Consent</h2>
        <p>Please <a href="#" data-toggle="modal" data-target="#consentModal">read and accept to continue.</a></p>
        <label class="form-check-label" style="cursor: pointer;">
            <input type="checkbox" id="agreeCheck" class="form-check-input"> I agree to provide feedback
        </label>
        <br><br>
        <button class="btn btn-success" id="btnProceed" disabled>Proceed</button>
    </div>

    <!-- SECTION 2: SECTOR & CATEGORY -->
    <div id="section_sector_category" class="section">
        <h2>Select Sector & Category</h2>
        <div class="mb-3">
            <select id="sectorSelect" class="form-select border-primary">
                <option value="">-- Select Sector --</option>
                <?php
                $query = $this->db->query("SELECT * FROM public.sector");
                foreach ($query->result() as $row) {
                    echo "<option value=\"" . $row->id . "\">" . $row->name . "</option>";
                }
                ?>
            </select>
        </div>
        <!-- Category Container -->
        <div class="mb-3" id="categoryContainer">
            <label>Category</label>
            <select class="form-select root-category border-primary">
                <option value="">-- Select Category --</option>
                <?php
                // Load root categories (parent_id IS NULL)
                $root_categories = $this->db->where('parent_id', NULL)
                    ->where('is_active', TRUE)
                    ->order_by('order_by', 'ASC')
                    ->get('category')
                    ->result();
                foreach ($root_categories as $c) {
                    echo "<option value=\"{$c->id}\">{$c->name}</option>";
                }
                ?>
            </select>
        </div>
        <button class="btn btn-primary" id="btnSectorCategoryNext">Next</button>
    </div>

    <!-- SECTION 3: FEEDBACK FORM -->
    <!-- <div id="section_feedback" class="section"> -->
    <div id="section_feedback" class="section">
        <h2>Client Feedback Form</h2>

        <div class="mb-3">
            <label>Sector</label>
            <input type="text" id="formSector" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <input type="text" id="formCategory" class="form-control" readonly>
        </div>

        <h5>Instructions</h5>
        <p class="text-muted small">Read each statement and select your rating.</p>

        <h5>Scale</h5>
        <p class="text-muted small">VS – Very Satisfied | S – Satisfied | D – Dissatisfied | VD – Very Dissatisfied</p>

        <h5>Optional Information</h5>
        <input type="text" placeholder="Name (optional)" class="form-control mb-2">
        <input type="text" placeholder="Address (optional)" class="form-control mb-2">
        <select class="form-select mb-2">
            <option value="">Sex (optional)</option>
            <option>Male</option>
            <option>Female</option>
        </select>
        <input type="text" placeholder="Contact (optional)" class="form-control mb-3">

        <h5>Rate Our Services</h5>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>VS</th>
                    <th>S</th>
                    <th>D</th>
                    <th>VD</th>
                </tr>
            </thead>
            <tbody>
                <?php $query = $this->db->query("SELECT * FROM public.service");
                foreach ($query->result() as $row) {
                    echo "<tr><td>" . $row->name . "</td>
                    <td><input type=\"radio\" name=\"rate_" . md5($row->id) . "\" value=\"VS\"></td>
                    <td><input type=\"radio\" name=\"rate_" . md5($row->id) . "\" value=\"S\"></td>
                    <td><input type=\"radio\" name=\"rate_" . md5($row->id) . "\" value=\"D\"></td>
                    <td><input type=\"radio\" name=\"rate_" . md5($row->id) . "\" value=\"VD\"></td></tr>";
                }
                ?>
            </tbody>
        </table>

        <textarea placeholder="Comments/Suggestions..." class="form-control mb-3" rows="4"></textarea>
        <button class="btn btn-success w-100" id="btnSubmit">Submit Feedback</button>
    </div>

    <?php $this->load->view('interface/system/layout/consent_modal'); ?>
    <script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>
    <script>
        // Enable proceed button
        $('#section_feedback').show();
        
        $('#agreeCheck').on('change', function() {
            $('#btnProceed').prop('disabled', !$(this).is(':checked'));
        });

        $('#btnProceed').click(() => {
            $('#section_consent').hide();
            $('#section_sector_category').show();
        });

        $('#btnSectorCategoryNext').click(function() {
            const sector = $('#sectorSelect').val();
            if (!sector) {
                alert("Please select a sector.");
                return;
            }

            // Check all category selects
            let allCategoriesSelected = true;
            $('#categoryContainer select').each(function() {
                if (!$(this).val()) allCategoriesSelected = false;
            });

            if (!allCategoriesSelected) {
                alert("Please select all category levels.");
                return;
            }

            // All validations passed, proceed
            // You can now populate the feedback form
            const finalCategory = $('#categoryContainer select:last').find(':selected').text();
            $('#formSector').val($('#sectorSelect option:selected').text());
            $('#formCategory').val(finalCategory);

            $('#section_sector_category').hide();
            $('#section_feedback').show();
        });

        $('#btnSubmit').click(() => alert("Feedback submitted successfully!"));

        let categoryPath = [];

        function loadChildCategories(parentId, container) {
            $.getJSON("<?= base_url() ?>get_subcategories", {
                parent_id: parentId
            }, function(data) {
                // Remove selects after current level
                $(container).find('select').slice(categoryPath.length).remove();

                if (data.length > 0) {
                    let select = $('<select class="form-select mt-2 border-primary"></select>');
                    select.append('<option value="">-- Select Subcategory --</option>');

                    data.forEach(cat => {
                        select.append(`<option value="${cat.id}">${cat.name}</option>`);
                    });

                    $(container).append(select);

                    select.change(function() {
                        const val = $(this).val();
                        categoryPath[categoryPath.length] = val ? parseInt(val) : null;
                        // Slice array to remove any lower-level selections
                        categoryPath = categoryPath.slice(0, categoryPath.length);
                        if (val) loadChildCategories(val, container);
                    });
                }
            });
        }

        // Attach change to root category
        $('#categoryContainer').on('change', 'select.root-category', function() {
            const val = $(this).val();
            categoryPath[0] = val ? parseInt(val) : null;
            categoryPath = categoryPath.slice(0, 1);
            if (val) loadChildCategories(val, '#categoryContainer');
        });
    </script>
</body>

</html>