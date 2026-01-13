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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>plugins/fontawesome-free-6.4.2-web/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>plugins/fontawesome-free/css/all.min.css">

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


        .select-card {
            cursor: pointer;
            transition: all 0.25s ease;
            border: 2px solid #dee2e6;
        }

        .select-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
        }

        .select-card.active {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        .select-card i {
            color: #0d6efd;
        }

        td input[type="radio"] {
            transform: scale(1.7);
            display: block;
            margin: auto;
            cursor: pointer;
        }

        .table-danger {
            background-color: #f8d7da !important;
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
            <a href="<?= base_url('login'); ?>" class="d-flex align-items-center text-white font-weight-bold" style="text-decoration:none;">
                <i class="fas fa-sign-in-alt mr-2"></i> LOGIN
            </a>

        </div>
    </nav>

    <!-- SECTION 1: CONSENT -->
    <div id="section_consent" class="section" style="display:block;">
        <div class="mb-3 align-items-center" style="text-align: center;">
            <img src="<?= $system_logo ?>" width="90" height="90" alt="logo" class="rounded-circle shadow-sm">
        </div>
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

        <div class="mb-3 text-center">
            <img src="<?= $system_logo ?>" width="90" class="rounded-circle shadow-sm">
        </div>

        <!-- hidden -->

        <!-- SECTOR -->
        <div id="sectorSection">
            <h2 class="text-center mb-4">Select Sector</h2>
            <div class="row g-3" id="sectorCards">
                <?php foreach ($this->db->where('is_active', 1)->get('sector')->result() as $s) : ?>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card select-card sector-card text-center h-100" data-id="<?= $s->id ?>" data-name="<?= $s->name ?>">

                            <div class="sector-img-wrapper mx-auto">
                                <img src="<?= base_url($s->img_path) ?>" width="150" height="150" alt="<?= $s->name ?>" class="sector-img">
                            </div>

                            <strong class="mt-2"><?= $s->name ?></strong>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- NESTED CATEGORY -->
        <div id="categorySection" class="mt-4 d-none">

            <button class="btn btn-outline-secondary mb-3" id="btnBack">
                ← Back
            </button>

            <h2 class="text-center mb-4" id="categoryTitle">
                Select Category
            </h2>

            <div class="row g-3" id="categoryCards"></div>
        </div>
    </div>

    <!-- Hidden inputs -->

    <!-- NESTED CATEGORY -->
    <div id="nestedCategorySection" class="mt-4 d-none">

        <button class="btn btn-outline-secondary mb-3" id="btnBackLevel">
            ← Back
        </button>

        <h2 class="text-center mb-4" id="categoryTitle">Select Subcategory</h2>

        <div class="row g-3" id="nestedCategoryCards"></div>
        <input type="hidden" name="final_category_id">
    </div>

    <!-- SUBCATEGORY -->
    <div id="subcategorySection" class="mt-4 d-none">

        <button class="btn btn-outline-secondary mb-3" id="btnBackToCategory">
            ← Back to Category
        </button>

        <h2 class="text-center mb-4">Select Subcategory</h2>

        <div class="row g-3" id="subcategoryCards"></div>
        <input type="hidden" name="subcategory_id">
    </div>

    <!-- SECTION 3: FEEDBACK FORM -->
    <!-- <div id="section_feedback" class="section"> -->
    <div id="section_feedback" class="section">

        <div class="mb-3 align-items-center" style="text-align: center;">
            <img src="<?= $system_logo ?>" width="90" height="90" alt="logo" class="rounded-circle shadow-sm">
        </div>
        <h2 class="text-center">Client Feedback Form</h2>

        <div>
            <?= form_open(base_url('submit_survey'), 'id=form_save_dataSubmitSurveyForm'); ?>

            <div class="mb-3">
                <label>Sector</label>
                <input type="text" id="formSector" class="form-control border-primary" disabled>
                <input name="sector_id" hidden>
            </div>
            <div class="mb-3">
                <label>Category</label>
                <input type="text" id="formCategory" class="form-control border-primary" disabled>
                <input name="category_id" hidden>
            </div>

            <h5>Instructions</h5>
            <p class="text-muted small">Read each statement and select your rating.</p>

            <h5>Scale</h5>
            <p class="text-muted small">VS – Very Satisfied | S – Satisfied | D – Dissatisfied | VD – Very Dissatisfied</p>

            <h5>Optional Information</h5>
            <input type="text" placeholder="Name (optional)" class="form-control text-uppercase border-primary mb-2" name="name" nr="1" autocomplete="off">
            <input type="text" placeholder="Address (optional)" class="form-control text-uppercase border-primary mb-2" name="address" nr="1" autocomplete="off">
            <select class="form-control text-uppercase border-primary mb-2" name="sex" nr="1" autocomplete="off">
                <option value="">Sex (optional)</option>
                <option>Male</option>
                <option>Female</option>
            </select>
            <input type="text" placeholder="Contact (optional)" class="form-control text-upp border-primary mb-3" name="contact_number" nr="1" autocomplete="off">

            <h5>Rate Our Services <span class="text-danger">*</span></h5>
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
                    <?php $query = $this->db->query("SELECT * FROM service");
                    foreach ($query->result() as $row) {
                        $rowId = $row->id;
                        echo "
                            <tr align='left'>
                                <td>
                                    <input type='hidden' id='service_id_$rowId' name='service_id_$rowId' value=''>
                                    $row->name
                                </td>
                                <td><input type='radio' class='rate-radio' data-target='service_id_$rowId' name='rate_" . md5($rowId) . "' value='VS' data-int='4'></td>
                                <td><input type='radio' class='rate-radio' data-target='service_id_$rowId' name='rate_" . md5($rowId) . "' value='S' data-int='3'></td>
                                <td><input type='radio' class='rate-radio' data-target='service_id_$rowId' name='rate_" . md5($rowId) . "' value='D' data-int='2'></td>
                                <td><input type='radio' class='rate-radio' data-target='service_id_$rowId' name='rate_" . md5($rowId) . "' value='VD' data-int='1'></td>
                            </tr>";
                    }
                    ?>
                </tbody>
                <input name="getmyid" hidden>
            </table>

            <textarea placeholder="Comments/Suggestions..." class="form-control border-primary mb-3" rows="4" name="comment" nr="1" autocomplete="off"></textarea>
            <button class="btn btn-success w-100 submitBtnPrimary" id="btnSubmit">Submit Feedback</button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="consentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header" style="background-color: #6f42c1;color: #fff;">
                    <h5 class="modal-title">Consent and Terms of Feedback</h5>
                    <button type="button" class="btn btn-default close" data-dismiss="modal"><i class="fa fa-"></i></button>
                </div>

                <div class="modal-body">
                    <div style="max-height: 350px; overflow-y: auto; padding-right: 10px;">

                        <h4>PLEASE READ CAREFULLY</h4>
                        <p>
                            By participating in this feedback form, you voluntarily agree to provide
                            information that may include personal details such as your name, sex,
                            address, and contact number. These details are optional, and you may choose
                            not to provide them if you prefer anonymous feedback.
                        </p>

                        <p>
                            The information collected will only be used to improve the quality of
                            public service offered by our office. Your responses will be kept
                            confidential and accessible only to authorized personnel.
                        </p>

                        <p>By clicking the "I Agree" button below, you confirm that:</p>

                        <ul>
                            <li>You understand the purpose of this feedback.</li>
                            <li>You voluntarily provide your answers.</li>
                            <li>You can refuse to answer optional personal questions.</li>
                            <li>Your data may be processed under the Data Privacy Act.</li>
                        </ul>

                        <p>If you do not agree, simply close this window.</p>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button class="btn btn-success" id="agreeBtn" data-dismiss="modal">
                        I Agree
                    </button>
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
        let categoryStack = [];
        let currentSectorId = null;

        function showSection(sectionId) {
            $('#sectorSection, #categorySection, #nestedCategorySection, #subcategorySection, #section_feedback')
                .addClass('d-none');

            $(sectionId).removeClass('d-none');
        }
        $('#btnBackToSector').on('click', function() {
            $('#categorySection').fadeOut(300, function() {
                $('#sectorSection').fadeIn(300);
            });
            $('[name=category_id]').val('');
            $('#btnSectorCategoryNext').prop('disabled', true);
        });
        $('#btnBackToSector').removeClass('d-none');

        $(document).on('click', '.sector-card', function() {

            $('.sector-card').removeClass('active');
            $(this).addClass('active');

            currentSectorId = $(this).data('id');
            const sectorName = $(this).data('name');

            $('[name=sector_id]').val(currentSectorId);
            $('#formSector').val(sectorName);

            categoryStack = [];

            showCategorySection(); // 🔥 IMPORTANT
            loadCategoryLevel(null, currentSectorId, 'Select Category');
        });

        $(document).on('click', '.category-card', function() {

            $('.category-card').removeClass('active');
            $(this).addClass('active');

            const id = $(this).data('id');
            const name = $(this).data('name');

            categoryStack.push({
                id,
                name
            });

            $('[name=category_id]').val(id);

            loadCategoryLevel(id, null, name);
        });

        $(document).on('click', '.subcategory-card', function() {

            $('.subcategory-card').removeClass('active');
            $(this).addClass('active');

            $('[name=subcategory_id]').val($(this).data('id'));

            // Proceed to feedback
            $('#section_sector_category').hide();
            $('#section_feedback').show();
        });

        $(document).on('click', '.nested-category-card', function() {

            $('.nested-category-card').removeClass('active');
            $(this).addClass('active');

            const id = $(this).data('id');
            const name = $(this).data('name');

            categoryStack.push({
                id,
                name
            });

            loadCategoryLevel(id, name);
        });

        $('#btnBack, #btnBackLevel').off().on('click', function() {

            categoryStack.pop();

            // Back to sector
            if (categoryStack.length === 0) {
                showSection('#sectorSection');
                return;
            }

            // Back one level
            const prev = categoryStack[categoryStack.length - 1];

            $('#formCategory').val(
                categoryStack.map(c => c.name).join(' → ')
            );

            loadCategoryLevel(prev.id, null, prev.name);
        });

        $('#btnBackToCategory').on('click', function() {
            showSection('#categorySection');
            $('[name=subcategory_id]').val('');
        });

        $('#btnBack').off().on('click', function() {

            categoryStack = []; // reset stack

            $('#categorySection').hide();
            $('#sectorSection').show();
            // 🔙 BACK ONE CATEGORY LEVEL
            const prev = categoryStack[categoryStack.length - 1];

            $('#formCategory').val(
                categoryStack.map(c => c.name).join(' → ')
            );

            loadCategoryLevel(prev.id, null, prev.name);
        });

        function showCategorySection() {
            $('#sectorSection').hide();
            $('#categorySection').removeClass('d-none').show();
        }
        // $('#btnBack').click(function() {

        //     categoryStack.pop();

        //     // Back to sector
        //     if (categoryStack.length === 0) {
        //         $('#categorySection').hide();
        //         $('#sectorSection').show();
        //         return;
        //     }

        //     // Back one level
        //     const prev = categoryStack[categoryStack.length - 1];
        //     loadCategoryLevel(prev.id, null, prev.name);
        // });

        $('#btnBackLevel').click(function() {

            categoryStack.pop();

            // Back to CATEGORY list
            if (categoryStack.length === 0) {
                $('#nestedCategorySection').hide();
                $('#categorySection').show();
                return;
            }

            // Load previous level
            const prev = categoryStack[categoryStack.length - 1];
            loadCategoryLevel(prev.id, prev.name);
        });

        $('#formCategory').val(
            categoryStack.map(c => c.name).join(' → ')
        );

        $('#btnBackToCategory').click(function() {
            $('#subcategorySection').fadeOut(300, function() {
                $('#categorySection').fadeIn(300);
            });
            $('[name=subcategory_id]').val('');
        });

        $('#btnBackToSector').click(function() {
            $('#categorySection').fadeOut(300, function() {
                $('#sectorSection').fadeIn(300);
            });
            $('[name=category_id]').val('');
        });

        // Back button
        $('#btnBackToSector').on('click', function() {
            $('#categorySection').fadeOut(300, function() {
                $('#sectorSection').fadeIn(300);
            });

            $('[name=category_id]').val('');
            $('#btnSectorCategoryNext').prop('disabled', true);
        });
        document.addEventListener("DOMContentLoaded", function() {

            function generateUUID() {
                if (window.crypto && crypto.randomUUID) {
                    return crypto.randomUUID();
                }
                return 'xxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    let r = Math.random() * 16 | 0;
                    let v = c === 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }

            function getDeviceId() {
                let id = localStorage.getItem("device_id");

                if (!id) {
                    id = generateUUID();
                    try {
                        localStorage.setItem("device_id", id);
                    } catch (_) {
                        document.cookie = "device_id=" + id + "; max-age=31536000; path=/";
                    }
                }

                return id;
            }
            $("[name=getmyid]").val(getDeviceId());
        });

        $(document).on('change', '.rate-radio', function() {
            let intValue = $(this).data('int'); // the rating number (1–4)
            let targetId = $(this).data('target'); // hidden input ID

            $('#' + targetId).val(intValue); // put value inside hidden input
        });
        // $('#section_feedback').show();
        // $('#section_sector_category').show();

        function validate(form_id) {
            let invalid = 0;
            let checkedRadios = [];

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
            $("#" + form_id + " input[type='radio']").each(function() {

                let radioName = $(this).attr("name");

                // prevent checking same group multiple times
                if (checkedRadios.includes(radioName)) return;

                checkedRadios.push(radioName);

                // check if at least one radio in group is selected
                if (!$("input[name='" + radioName + "']:checked").length) {

                    // highlight entire table row
                    $(this).closest("tr").addClass("table-danger");

                    invalid++;
                } else {
                    $(this).closest("tr").removeClass("table-danger");
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
                    console.log(data)
                    // var d = JSON.parse(data);
                    if (data.success == true) {
                        successAlert("Survey Form Successfully Submitted!");
                        // clear_form(formId);
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
                    // $("#form_save_data" + formId + " .submitBtnPrimary").attr("disabled", false);
                    // $("#form_save_data" + formId + " .submitBtnPrimary").html(a);
                }
            };
            $("#form_save_data" + formId).ajaxForm(saveData);
        }
        saveForm("SubmitSurveyForm", [null], null);
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

        $('#agreeCheck').on('change', function() {
            $('#btnProceed').prop('disabled', !$(this).is(':checked'));
        });

        $("#agreeBtn").click(function() {
            $('#section_consent').hide();
            $('#section_sector_category').show();
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

            $('#formCategory').val(finalCategory);

            $('#section_sector_category').hide();
            $('#section_feedback').show();
        });

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

        function loadCategoryLevel(parentId, sectorId, title) {

            $('#categoryTitle').text(title);
            $('#categoryCards').html('<div class="text-center">Loading...</div>');
            $('#categorySection').removeClass('d-none');

            let url = parentId === null ?
                "<?= base_url('get-categories/') ?>" + sectorId :
                "<?= base_url('get-subcategories/') ?>" + parentId;

            $.get(url, function(data) {

                let res = JSON.parse(data);

                // 🔥 NO CHILD → GO FEEDBACK
                if (res.length === 0) {

                    const final = categoryStack[categoryStack.length - 1];
                    if (!final) return; // safety

                    $('[name=final_category_id]').val(final.id);

                    $('#section_sector_category').hide();
                    $('#section_feedback').show();

                    $('#formCategory').val(
                        categoryStack.map(c => c.name).join(' → ')
                    );
                    return;
                }

                let html = '';
                res.forEach(r => {
                    html += `
            <div class="col-12">
                <div class="card text-center p-3 select-card category-card"
                     data-id="${r.id}"
                     data-name="${r.name}">
                    <center>
                        <img src="${r.img_path || "<?= base_url('dist/img/SMCCnewlogo_5x6.png') ?>"}"
                             width="65" height="65"
                             class="rounded border shadow-sm mb-2">
                    </center>
                    <strong>${r.name}</strong>
                </div>
            </div>`;
                });

                $('#categoryCards').html(html);
            });
        }

        function loadChildCategories(parentId, container) {
            $.getJSON("<?= base_url() ?>get_subcategories", {
                parent_id: parentId
            }, function(data) {
                // Remove selects after current level
                $(container).find('select').slice(categoryPath.length).remove();

                if (data.length > 0) {
                    let select = $('<select class="form-select mt-2 border-primary" onchange="$(`[name=category_id]`).val($(this).val());"></select>');
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