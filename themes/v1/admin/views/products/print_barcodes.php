<style>
    .uk-open>.uk-dropdown,
    .uk-open>.uk-dropdown-blank {}
    .introtext.no-print {
        white-space: nowrap;
        /* overflow: hidden;     */
        text-overflow: ellipsis;
    }
</style>
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <i class="md-icon material-icons md-card-toggle" style="opacity: 1; transform: scale(1);"></i>
                </div>
                <h3 class="md-card-toolbar-heading-text">Print Barcode/Label </h3>
            </div>
            <div class="md-card-content">
                <input type="hidden" name="show_type" value="2">
                <div class="uk-grid">
                    <div class="uk-width-large-1-2">
                        <p class="introtext no-print">
                            <?php echo sprintf(
                                lang('print_barcode_heading'),
                                anchor('admin/system_settings/categories', lang('categories')),
                                anchor('admin/system_settings/subcategories', lang('subcategories')),
                                anchor('admin/purchases', lang('purchases')),
                                anchor('admin/transfers', lang('transfers'))
                            ); ?>
                        </p>
                    </div>


                    <div class="uk-width-1-1">
                        <div class="md-input-wrapper md-input-filled">
                            <label for="">Add product</label>
                            <input class="md-input  label-fixed" type="text" name="start_date" placeholder="Add Item"
                                autocomplete="off">
                        </div>
                        <?= admin_form_open("products/print_barcodes", 'id="barcode-print-form" data-toggle="validator"'); ?>
                    </div>

                    <div class="uk-width-1-1">
                        <div class="dt_colVis_buttons"></div>
                        <table id="dt_tableExport" class="uk-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sale ID</th>
                                    <th>Date</th>
                                    <th>Ref No</th>
                                    <th>Product Name</th>
                                    <th>Product Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>


                    <div class="uk-width-1-1">
                        <div class="md-input-wrapper md-input-filled">
                            <label for="">Style</label>
                            <select name="style" class="md-input  label-fixed" id="style" required="required"
                                data-original-title="" title="" data-bv-field="style" fdprocessedid="ab2o2k">
                                <option value="">Select Style</option>
                                <option value="40">40 per sheet (a4) (1.799" x 1.003")</option>
                                <option value="30">30 per sheet (2.625" x 1")</option>
                                <option value="24" selected="selected">24 per sheet (a4) (2.48" x 1.334")</option>
                                <option value="20">20 per sheet (4" x 1")</option>
                                <option value="18">18 per sheet (a4) (2.5" x 1.835")</option>
                                <option value="14">14 per sheet (4" x 1.33")</option>
                                <option value="12">12 per sheet (a4) (2.5" x 2.834")</option>
                                <option value="10">10 per sheet (4" x 2")</option>
                                <option value="50">Continuous feed</option>
                            </select>
                        </div>

                        <span class="help-block">Please don't forget to set correct page size and margin for your printer. You can set right and bottom to 0 while left and top margin can be adjusted according to your need.</span>

                        <div class="md-input-wrapper md-input-filled">
                            <span style="font-weight: bold; margin-right: 15px;"><?= lang('print'); ?>:
                            </span>
                            <input name="site_name" type="checkbox" id="site_name" value="1" checked="checked" style="display:inline-block;" />
                            <label for="site_name" class="padding05">
                                <?= lang('site_name'); ?>
                            </label>
                            <input name="product_name" type="checkbox" id="product_name" value="1" checked="checked"
                                style="display:inline-block;" />
                            <label for="product_name" class="padding05">
                                <?= lang('product_name'); ?>
                            </label>
                            <input name="price" type="checkbox" id="price" value="1" checked="checked" style="display:inline-block;" />
                            <label for="price" class="padding05">
                                <?= lang('price'); ?>
                            </label>
                            <input name="currencies" type="checkbox" id="currencies" value="1" style="display:inline-block;" />
                            <label for="currencies" class="padding05">
                                <?= lang('currencies'); ?>
                            </label>
                            <input name="unit" type="checkbox" id="unit" value="1" style="display:inline-block;" />
                            <label for="unit" class="padding05">
                                <?= lang('unit'); ?>
                            </label>
                            <input name="category" type="checkbox" id="category" value="1" style="display:inline-block;" />
                            <label for="category" class="padding05">
                                <?= lang('category'); ?>
                            </label>
                            <input name="variants" type="checkbox" id="variants" value="1" style="display:inline-block;" />
                            <label for="variants" class="padding05">
                                <?= lang('variants'); ?>
                            </label>
                            <input name="product_image" type="checkbox" id="product_image" value="1" style="display:inline-block;" />
                            <label for="product_image" class="padding05">
                                <?= lang('product_image'); ?>
                            </label>
                            <input name="check_promo" type="checkbox" id="check_promo" value="1" checked="checked"
                                style="display:inline-block;" />
                            <label for="check_promo" class="padding05">
                                <?= lang('check_promo'); ?>
                            </label>
                        </div>
                        <div class="md-input-wrapper md-input-filled">
                             <button type="submit" id="submit" class="btn btn-primary">
                                Update
                            </button>
                            <button type="button" id="reset" class="btn btn-danger">
                               Reset
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script
        src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <!-- datatables buttons-->
    <!-- datatables custom integration -->
    <script>
        var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
            csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
        var data = [];
        data[csrfName] = csrfHash;

        $(document).ready(function () {
            $('#dt_tableExport').DataTable({
                dom: 'rti',
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#dt_tableExport').DataTable();
        });
    </script>