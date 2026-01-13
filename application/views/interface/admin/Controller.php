<?php
date_default_timezone_set('Asia/Manila');
if (!$this->session->feedback_login_id) {
	redirect(base_url('login'));
}
$uri = $this->session->feedback_login_uri;
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
			<h4 class="mb-0 font-weight-bold">Feedback <?= $page_title ?></h4>
		</div>

		<hr>
		
		<div class="card card-purple">
			<div class="card-header">
				<h3 class="card-title">Sentiment Words</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse">
						<i class="fas fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12">
						<!-- text input -->
						<?= form_open(base_url($uri . '/Controller/saveSentimentWord'), 'id="form_save_dataSentimentWord"'); ?>
						<input type="hidden" name="id" nr="1">
						<div class="form-group">
							<label>Sentiment Words</label>
							<div class="input-group">
								<input type="text" style="width:30%" class="form-control text-uppercase" placeholder="Enter Sentiment Words" name="word" autocomplete="off">
								<select class="form-control text-uppercase" name="type_int">
									<option value="1">Positive</option>
									<option value="0">Negative</option>
								</select>
								<span class="input-group-append action-buttons">
									<button type="submit" class="btn btn-success btn-save">Save</button>
								</span>
							</div>
						</div>
						<?= form_close() ?>
						<div class="card-body p-2" style="overflow: auto;">
							<table id="tblSentimentWord" class="table table-bordered table-sm table-striped table-hover" width="100%">
								<thead>
									<tr>
										<th width="1">No.</th>
										<th width="80%">Sentiment Words</th>
										<th width="1">Type</th>
										<!-- <th width="1">Active</th> -->
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.card-body -->
		</div>

		<hr>

		<div class="card card-success collapsed-card">
			<div class="card-header">
				<h3 class="card-title">Category</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse">
						<i class="fas fa-plus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12">
						<!-- text input -->
						<?= form_open(base_url($uri . '/Controller/saveCategory'), 'id="form_save_dataCategory"'); ?>
						<input type="hidden" name="id" nr="1">
						<div class="text-center mb-2">
							<img name="previewPic" src="<?= $system_svg ?>" class="rounded border shadow-sm" onclick="$('[name=picCategory]').trigger('click')" width="70" height="70"><br />
							<label for="picSecotr" class="mt-2">Upload Image</label>
							<input name="picCategory" type="file" accept="image/*" onchange="imageView('picCategory','previewPic','imgtargetLink')" nr="1" hidden="">
							<input name="img_path" type="text" nr="1" hidden="">
						</div>

						<div class="form-group">
							<label>Category Name</label>
							<input type="text" class="form-control text-uppercase" placeholder="Enter Category name" name="name" autocomplete="off">

							<div class="input-group mt-2">
								<select style="width:30%" name="parent_id" class="form-control text-uppercase" nr="1">
									<option value="">No Parent Category</option>

									<?php
									// 1) get all categories once
									$categories = $this->db
										->order_by('parent_id ASC, order_by ASC')
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

										foreach ($tree[$parent_id] as $cat) {

											// indent (3 spaces per level)
											$indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);

											echo '<option value="' . $cat->id . '">';
											echo $indent . $cat->name;
											echo '</option>';

											// children
											$renderOptions($cat->id, $level + 1);
										}
									};

									// 4) render tree
									$renderOptions();
									?>
								</select>
								<select class="form-control text-uppercase" name="is_active">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select>
								<span class="input-group-append action-buttons">
									<button type="submit" class="btn btn-success btn-save">Save</button>
								</span>
							</div>
						</div>
						<?= form_close() ?>
						<div class="card-body p-2" style="overflow: auto;">
							<table id="tblCategory" class="table table-bordered table-sm table-striped table-hover" width="100%">
								<thead>
									<tr>
										<th width="1">Photo</th>
										<th width="1">Category</th>
										<th width="1">Parent Category</th>
										<th width="1">Active</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.card-body -->
		</div>

		<hr>

		<div class="card card-primary collapsed-card">
			<div class="card-header">
				<h3 class="card-title">Sectors</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse">
						<i class="fas fa-plus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12">
						<!-- text input -->
						<?= form_open(base_url($uri . '/Controller/saveSector'), 'id="form_save_dataSector"'); ?>
						<input type="hidden" name="id" nr="1">
						<div class="text-center mb-2">
							<img name="previewPic" src="<?= $system_svg ?>" class="rounded border shadow-sm" onclick="$('[name=picSector]').trigger('click')" width="70" height="70"><br />
							<label for="picSecotr" class="mt-2">Upload Image</label>
							<input name="picSector" type="file" accept="image/*" onchange="imageView('picSector','previewPic','imgtargetLink')" nr="1" hidden="">
							<input name="img_path" type="text" nr="1" hidden="">
						</div>



						<div class="form-group">
							<label>Sector Name</label>
							<div class="input-group">
								<input style="width:30%" type="text" class="form-control text-uppercase" placeholder="Enter sector name" name="name" autocomplete="off">
								<select class="form-control text-uppercase" name="is_active">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select>
								<span class="input-group-append action-buttons">
									<button type="submit" class="btn btn-success btn-save">Save</button>
								</span>
							</div>
						</div>
						<?= form_close() ?>
						<div class="card-body p-2" style="overflow: auto;">
							<table id="tblSector" class="table table-bordered table-sm table-striped table-hover" width="100%">
								<thead>
									<tr>
										<th width="1">Photo</th>
										<th width="50">Sector</th>
										<th width="1">Active</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.card-body -->
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
		$(function() {
			let f1 = "Sector";
			let f2 = "Category";
			let f3 = "SentimentWord";
			getTable(f1, 0, 5);
			saveForm(f1, [f1], null, 0, 5);

			getTable(f2, 0, 5);
			saveForm(f2, [f2], null, 0, 5);

			getTable(f3, 0, 10);
			saveForm(f3, [f3], null, 0, 10);
		});

		function existAlert(a) {
			toastr.warning(a)
		}

		function successAlert(a) {
			toastr.success(a)
		}

		function failAlert(a) {
			toastr.error(a)
		}

		function fillIn() {
			toastr.error('Please fill in all the required fields.');
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
					url: "<?= base_url($uri . '/' . $current_location . '/get') ?>" + tableId,
					type: "POST",
					data: function(d) {
						drawCounter++;
						d.length = pl;
						d.draw = drawCounter;
						d.search.value = $('#tbl' + tableId + '_filter input').val();
						d.search.farm_id = $('#farmList').val();
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

		function saveForm(formId, tblId, tbl, dtd, pl) {
			let a = "";
			var saveData = {
				clearForm: false,
				resetForm: false,
				beforeSubmit: function(e) {
					if (formId != 'SbjctAssPrsnnl') {
						validate("form_save_data" + formId);
					}
					if (valid != 0) {
						fillIn();
						return false;
					}
					a = $("#form_save_data" + formId + " .submitBtnPrimary").text();
					// $("#form_save_data" + formId + " .submitBtnPrimary").attr("disabled", true);
					$("#form_save_data" + formId + " .submitBtnPrimary").html("<span class=\"fa fa-spinner fa-pulse\"></span>");
				},
				success: function(data) {
					var d = JSON.parse(data);
					if (d.success == true) {
						successAlert(d.message);
						clear_form(formId);
						$("#modal" + formId).modal('hide');
						for (var i = 0; i < tblId.length; i++) {
							getTable(tblId[i], dtd, pl);
						}
						tbl ? removeAllItemList("tbl" + tbl) : null;
						tbl ? $("#btn" + tbl).trigger("click") : null;
						if (formId == "FarmInfo") {
							location.reload();
						}
					} else if (d.success == false && d.exist == true) {
						existAlert(d.message);
					} else {
						failAlert("Something went wrong!");
					}
					resetForm('form_save_data' + formId);
					$("#form_save_data" + formId + " .submitBtnPrimary").attr("disabled", false);
					$("#form_save_data" + formId + " .submitBtnPrimary").html(a);
				},
				error: function() {
					failAlert("Something went wrong!");
					$("#form_save_data" + formId + " .submitBtnPrimary").attr("disabled", false);
					$("#form_save_data" + formId + " .submitBtnPrimary").html(a);
				}
			};
			$("#form_save_data" + formId).ajaxForm(saveData);
		}

		function validate(form_id) {
			let invalid = 0;
			$($("#" + form_id).find("select").get().reverse()).each(function() {
				var name = $(this).attr("name");
				var j = clean($(this).attr("name"));
				var nr = $(this).attr("nr");
				var multiple = $(this).attr("multiple");


				if (nr != 1) {
					if (!$(this).val() || $(this).val() == 'null') {
						$(this).focus().addClass("is-invalid");
						$("#" + form_id + " select[name='" + name + "']").focus().next().find('.select2-selection').addClass('has-error');
						$("#" + form_id + " ." + j).addClass('border-danger');
						invalid++;
					} else {
						$(this).removeClass("is-invalid");
						$("#" + form_id + " select[name='" + name + "']").focus().next().find('.select2-selection').removeClass('has-error');
						$("#" + form_id + " ." + j).removeClass('border-danger');
					}
				}
			});

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


		function clean(a) {
			var str = a;
			return str === undefined ? null : str.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
		}


		function imageView(a, b, c) {
			var fileInput = $("[name=" + a + "]")[0]; // Get the file input element
			var file = fileInput.files[0]; // Get the selected file

			if (file.size > 25 * 1024 * 1024) {
				// Picture size is above 2MB
				alert("Picture must be less than 2MB");
				return; // You can handle this case according to your requirements
			}

			var reader = new FileReader();

			reader.onload = function(e) {
				$("[name=" + b + "]").attr('src', e.target.result); // Set the source of the image element
			};

			reader.readAsDataURL(file);
		}

		function defaultImg(a, b, c, d) {
			var reader = new FileReader();
			$("[name=" + a + "]").val("");
			// img = (d == 'FEMALE' ? 'defaultf.png' : 'defaultm.png');
			$("[name=" + b + "]").attr("src", "<?= $system_svg ?>");
			reader.onload = function(e) {
				document.getElementById(c).src = e.target.result;
			};
		}


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

			defaultImg('pic', 'previewPic', 'imgtargetLink', 'MALE');

		}

		function edit(formSelector, data) {
			let $form = $(formSelector);

			// Safety check
			if (!$form.length) {
				console.error('Form not found:', formSelector);
				return;
			}

			// Loop through all object keys
			$.each(data, function(key, value) {

				let $field = $form.find('[name="' + key + '"]');

				if ($field.length) {

					if ($field.is(':checkbox')) {
						$field.prop('checked', value == 1);

					} else if ($field.is(':radio')) {
						$field
							.filter('[value="' + value + '"]')
							.prop('checked', true);

					} else {
						$field.val(value);
					}
				}

				// Auto image preview
				if (key === 'img_path') {
					let img = value ?
						"<?= base_url(); ?>" + value :
						"<?= base_url('dist/img/SMCCnewlogo_5x6.png'); ?>";

					$form.find('img[name="previewPic"]').attr('src', img);
				}
			});


			// 🔥 EDIT MODE UI
			let $btnGroup = $form.find('.action-buttons');
			let $saveBtn = $btnGroup.find('.btn-save');

			// change save → update (yellow)
			$saveBtn
				.removeClass('btn-success')
				.addClass('btn-warning')
				.text('Update');

			// append cancel button ONLY ONCE
			if (!$btnGroup.find('.btn-cancel').length) {
				$btnGroup.append(`
					<button type="button"
							class="btn btn-danger btn-cancel ml-1"
							onclick="resetForm('form_save_dataSector')">
						✖
					</button>
				`);
			}

			// scroll + focus
			$('html, body').animate({
				scrollTop: $form.offset().top - 80
			}, 300);

			$form.find('[name="sector"], [name="name"]').first().focus();
		}


		function resetForm(formId) {
			let $form = $('#' + formId);
			let $btnGroup = $form.find('.action-buttons');

			// reset form
			$form[0].reset();
			$form.find('[name="id"]').val('');

			// reset image
			$form.find('img[name="previewPic"]').attr(
				'src',
				"<?= base_url('dist/img/SMCCnewlogo_5x6.png'); ?>"
			);

			// restore save button
			let $saveBtn = $btnGroup.find('.btn-save');
			$saveBtn
				.removeClass('btn-warning')
				.addClass('btn-success')
				.text('Save');

			// 🔥 REMOVE cancel button
			$btnGroup.find('.btn-cancel').remove();
		}
	</script>
</body>

</html>