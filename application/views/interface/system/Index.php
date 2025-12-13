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

        <div class="mb-3 align-items-center" style="text-align: center;">
            <img src="<?= $system_logo ?>" width="90" height="90" alt="logo" class="rounded-circle shadow-sm">
        </div>
        <h2 class="text-center">Select Sector & Category</h2>
        <div class="mb-3">
            <select id="sectorSelect" name="select_sector" class="form-select border-primary" onchange="$('[name=sector_id]').val($(this).val());">
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
            <select class="form-select root-category border-primary" name="select_category" onchange="$(`[name=category_id]`).val($(this).val());">
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
                    <?php $query = $this->db->query("SELECT * FROM public.service");
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
            <button class="btn btn-success w-100" id="btnSubmit">Submit Feedback</button>
            </form>
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
        let categoryPath = [];

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