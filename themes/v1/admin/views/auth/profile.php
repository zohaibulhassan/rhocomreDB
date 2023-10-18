<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">Edit User </h3>
            </div>
            <div class="md-card-content">
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addFrom');
                echo admin_form_open_multipart("#", $attrib);
                ?>
                <div class="uk-grid">
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>First Name <span class="red">*</span></label>
                            <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                            <input type="text" name="first_name" class="md-input md-input-success label-fixed" required
                                value="<?php echo $user->first_name; ?>">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Last Name <span class="red">*</span></label>
                            <input type="text" name="last_name" class="md-input md-input-success label-fixed" required
                                value="<?php echo $user->last_name; ?>">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Gender <span class="red">*</span></label>
                            <select name="gender" class="uk-width-1-1 select2" required>
                                <option value="male" <?php if ($user->gender == "male") {
                                    echo 'selected';
                                } ?>>Male</option>
                                <option value="female" <?php if ($user->gender == "female") {
                                    echo 'selected';
                                } ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Company Name <span class="red">*</span></label>
                            <input type="text" name="company" class="md-input md-input-success label-fixed" required
                                value="<?php echo $user->company; ?>">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Phone <span class="red">*</span></label>
                            <input type="text" name="phone" class="md-input md-input-success label-fixed" required
                                value="<?php echo $user->phone; ?>">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Email <span class="red">*</span></label>
                            <input type="email" name="email" class="md-input md-input-success label-fixed" required
                                value="<?php echo $user->email; ?>">
                        </div>
                    </div>
                    <?php if ($Owner || $Admin) { ?>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Permission Group <span class="red">*</span></label>
                                <select name="group" class="uk-width-1-1 select2" required>
                                    <?php
                                    foreach ($groups as $group) {
                                        ?>
                                        <option value="<?php echo $group['id']; ?>" <?php if ($group['id'] == $user->group_id) {
                                               echo 'selected';
                                           } ?>><?php echo $group['name']; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("supplier1", "supplier"); ?>
                                <?php
                                $bl[""] = lang('select') . ' ' . lang('supplier');
                                foreach ($billers as $suppliers) {
                                    $bl[$suppliers->id] = $suppliers->name != '-' ? $suppliers->name : $suppliers->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ''), 'id="biller" class="form-control select" style="width:100%;"');
                                ?>
                            </div>
                        </div>

                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <?= lang("supplier2", "supplier2"); ?>
                                <?php
                                $bl[""] = lang('select') . ' ' . lang('supplier');
                                foreach ($billers as $suppliers) {
                                    $bl[$suppliers->id] = $suppliers->name != '-' ? $suppliers->name : $suppliers->name;
                                }
                                echo form_dropdown('biller2', $bl, (isset($_POST['biller2']) ? $_POST['biller2'] : ''), 'id="biller2" class="form-control select" style="width:100%;"');
                                ?>
                            </div>
                        </div>



                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label for="addMoreSupplier">Add More Supplier</label>
                                <input type="checkbox" id="addMoreSupplier" name="addMoreSupplier" value="1">
                            </div>
                        </div>


                        <div class="uk-width-large-1-2">
                        <div id="additionalSuppliers" style="display: none;">
                            <!-- Additional supplier fields will be added here using JavaScript -->
                        </div>
                        </div>

                        <div class="uk-width-large-1-1">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Warehouse <span class="red">*</span></label>
                                <select name="warehouse" class="uk-width-1-1 select2" required>
                                    <option value="0">All Warehouses</option>
                                    <?php
                                    foreach ($warehouses as $warehouse) {
                                        ?>
                                        <option value="<?php echo $warehouse->id; ?>" <?php if ($warehouse->id == $user->warehouse_id) {
                                               echo 'selected';
                                           } ?>><?php echo $warehouse->name; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Report Person <span class="red">*</span></label>
                                <select name="reportperson" class="uk-width-1-1 select2" required>
                                    <option value="0">No Reprot Person</option>
                                    <?php
                                    foreach ($userslist as $userlist) {
                                        ?>
                                        <option value="<?php echo $userlist->id; ?>" <?php if ($userlist->id == $user->report_person) {
                                               echo 'selected';
                                           } ?>><?php echo $userlist->first_name . ' ' . $userlist->last_name; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-large-1-2">
                            <div class="md-input-wrapper md-input-filled">
                                <label>Status <span class="red">*</span></label>
                                <select name="status" class="uk-width-1-1 select2" required>
                                    <option value="1" <?php if ($user->active == "1") {
                                        echo 'selected';
                                    } ?>>Active</option>
                                    <option value="0" <?php if ($user->active == "0") {
                                        echo 'selected';
                                    } ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    <?php }
                    ?>




                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Username <span class="red">*</span></label>
                            <input type="text" name="username" class="md-input md-input-success label-fixed" readonly
                                required value="<?php echo $user->username; ?>">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Password</label>
                            <input type="password" name="password" class="md-input md-input-success label-fixed">
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="md-input-wrapper md-input-filled">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password"
                                class="md-input md-input-success label-fixed">
                        </div>
                    </div>


                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-1" style="padding-top: 20px;">
                        <button type="submit"
                            class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light"
                            id="submitbtn">Submit</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<!-- CK Editor 5 -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/ckeditor5/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
                shouldNotGroupWhenFull: true
            }
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error(error);
        });
</script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('#addFrom').submit(function (e) {
            e.preventDefault();
            $('#submitbtn').prop('disabled', true);
            $.ajax({
                url: '<?php echo base_url('admin/auth/edit_user/' . $user->id); ?>',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    var obj = jQuery.parseJSON(data);
                    console.log(obj);
                    if (obj.status) {
                        toastr.success(obj.message);
                        window.location.href = "<?php echo base_url('admin/users'); ?>";
                    } else {
                        toastr.error(obj.message);
                    }
                    $('#submitbtn').prop('disabled', false);
                }
            });
        });
    });
</script>
<script>
    document.getElementById('addMoreSupplier').addEventListener('change', function () {
        var additionalSuppliersDiv = document.getElementById('additionalSuppliers');
        if (this.checked) {
            // Show the additional supplier fields
            additionalSuppliersDiv.style.display = 'block';
            // Add a new supplier dropdown field
            additionalSuppliersDiv.innerHTML = `
                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <?= lang("supplier3", "supplier3"); ?>
                        <?php
                        $bl[""] = lang('select') . ' ' . lang('supplier');
                        foreach ($billers as $suppliers) {
                            $bl[$suppliers->id] = $suppliers->name != '-' ? $suppliers->name : $suppliers->name;
                        }
                        echo form_dropdown('biller3', $bl, (isset($_POST['biller3']) ? $_POST['biller3'] : ''), 'id="biller3" class="form-control select" style="width:100%;"');
                        ?>
                    </div>
                </div>

                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <?= lang("supplier4", "supplier4"); ?>
                        <?php
                        $bl[""] = lang('select') . ' ' . lang('supplier');
                        foreach ($billers as $suppliers) {
                            $bl[$suppliers->id] = $suppliers->name != '-' ? $suppliers->name : $suppliers->name;
                        }
                        echo form_dropdown('biller4', $bl, (isset($_POST['biller4']) ? $_POST['biller4'] : ''), 'id="biller4" class="form-control select" style="width:100%;"');
                        ?>
                    </div>
                </div>

                <div class="uk-width-large-1-1">
                    <div class="md-input-wrapper md-input-filled">
                        <?= lang("supplier5", "supplier5"); ?>
                        <?php
                        $bl[""] = lang('select') . ' ' . lang('supplier');
                        foreach ($billers as $suppliers) {
                            $bl[$suppliers->id] = $suppliers->name != '-' ? $suppliers->name : $suppliers->name;
                        }
                        echo form_dropdown('biller5', $bl, (isset($_POST['biller5']) ? $_POST['biller5'] : ''), 'id="biller5" class="form-control select" style="width:100%;"');
                        ?>
                    </div>
                </div>


            `;
        } else {
            // Hide the additional supplier fields
            additionalSuppliersDiv.style.display = 'none';
            // Clear any added supplier fields
            additionalSuppliersDiv.innerHTML = '';
        }
    });
</script>