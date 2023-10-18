<style>
    /* .canvas-container {
    display: inline-block;
    vertical-align: top;
    margin-right: 20px;
} */
</style>
<div id="page_content">
    <div id="page_content_inner">
        <?php //foreach($sales_data as $s){
        // print_r($s->sale_date);
        // exit;
        //} 
        ?>
        <!-- statistics (small charts) -->


        <div class="uk-grid uk-grid-width-large-1-4 uk-grid-width-medium-1-2 uk-grid-medium" data-uk-grid-margin>
            <div>
                <div class="md-card">
                    <div class="md-card-content">
                        <span class="uk-text-muted uk-text-small">Today Sale</span>
                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $current_status['today_sale']; ?></span></h2>
                    </div>
                </div>
            </div>
            <div>
                <div class="md-card">
                    <div class="md-card-content">
                        <span class="uk-text-muted uk-text-small">Yesterday Sale</span>
                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $current_status['yesterday_sale']; ?></span></h2>
                    </div>
                </div>
            </div>
            <div>
                <div class="md-card">
                    <div class="md-card-content">
                        <span class="uk-text-muted uk-text-small">Current Month Sale</span>
                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $current_status['current_month_sale']; ?></span></h2>
                    </div>
                </div>
            </div>
            <div>
                <div class="md-card">
                    <div class="md-card-content">
                        <span class="uk-text-muted uk-text-small">Previous Month Sale</span>
                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $current_status['last_month_sale']; ?></span></h2>
                    </div>
                </div>
            </div>
        </div>


        <!-- tasks -->

        <!-- <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
                <div class="uk-width-medium-1-2">
                    <div class="md-card ">  
                    <canvas id="salesChart" width="400" height="200"></canvas>

                    <canvas id="purchaseChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div> -->

            <?php          $user = $this->site->getUser(); if ($user->group_id != 6 ) {
      ?>

        <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
            <div class="uk-width-medium-1-2">

                <div class="md-card">
                    <div class="canvas-container">
                        <canvas id="salesChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="uk-width-medium-1-2">

                <div class="md-card">
                    <div class="canvas-container">
                        <canvas id="purchaseChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- <div  class="uk-width-medium-1-2">

             <div class="md-card"> 
                 <div class="canvas-container" >
                   <canvas id="yearlyPrchaseChart" width="400" height="200"></canvas>
                 </div>
             </div>
</div> -->

            <div class="uk-width-medium-1-2">

                <div class="md-card">
                    <div class="canvas-container">
                        <canvas id="yearlySaleChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="uk-width-medium-1-2">

                <div class="md-card">
                    <div class="canvas-container">
                        <canvas id="yearlyPrchaseChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="uk-width-medium-1-1">

                <div class="md-card">
                    <div class="canvas-container">
                        <canvas id="productWiseSale" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="uk-width-medium-1-1">

                <div class="md-card">
                    <div class="canvas-container">
                        <canvas id="productWisePurchase" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="uk-width-medium-1-2">

                <div class="md-card">
                    <div class="canvas-container">
                        <canvas id="supplier" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php } ?>



<div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
        <div class="uk-width-medium-1-2">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                    </div>
                    <h3 class="md-card-toolbar-heading-text">
                        Brand Wise sales
                    </h3>
                </div>
                <div class="md-card-content">
                    <div class="uk-overflow-container">
                        <table class="uk-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Brand Name</th>
                                    <th>Total Sales In Liters</th>
                                    <th>Carton Qty</th>
                                    <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($bs != "") {
                                    foreach ($brandwiseseller as $key => $b) {
                                ?>
                                        <tr>
                                            <td><?php echo $key + 1 ?></td>
                                            <td><?php echo $b->brandname ?></td>
                                            <td><?php echo $b->Total_Sales_In_Liters ?></td>
                                            <td><?php echo $b->Carton_Size ?></td>
                                            <td><?php echo $b->value_excl_tax ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-width-medium-1-2">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                    </div>
                    <h3 class="md-card-toolbar-heading-text">
                        Etailer Wise Sale
                    </h3>
                </div>
                <div class="md-card-content">
                    <div class="uk-overflow-container">
                        <table class="uk-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Etailer Name</th>
                                    <!-- <th>Etailer ID</th> -->
                                    <th>Total Sales in Liter</th>
                                    <th>Carton Qty</th>
                                    <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($lmbs != "") {

                                    foreach ($etailerwiseseller as $key => $lmb) {
                                ?>
                                        <tr>
                                            <td><?php echo $key + 1 ?></td>
                                            <td><?php echo $lmb->etailername ?></td>
                                            <!-- <td><?php // echo $lmb->etailerID ?></td> -->
                                            <td><?php echo $lmb->Total_Sales_In_Liters ?></td>
                                            <td><?php echo $lmb->Carton_Size ?></td>
                                            <td><?php echo $lmb->value_excl_tax ?></td>
                                           
                                        </tr>
                                <?php
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- tasks -->
    <!-- <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
        <div class="uk-width-medium-1-2">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                    </div>
                    <h3 class="md-card-toolbar-heading-text">
                        Best Selling Products
                    </h3>
                </div>
                <div class="md-card-content">
                    <div class="uk-overflow-container">
                        <table class="uk-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Brand</th>
                                    <th>Product Name</th>
                                    <th>Qty in Liters</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($bs != "") {
                                    foreach ($bs as $key => $b) {
                                ?>
                                        <tr>
                                            <td><?php echo $key + 1 ?></td>
                                            <td><?php echo $b->brand ?></td>
                                            <td><?php echo $b->product_name ?></td>
                                            <td><?php echo $b->quantity ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-width-medium-1-2">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <i class="md-icon material-icons md-card-fullscreen-activate"></i>
                    </div>
                    <h3 class="md-card-toolbar-heading-text">
                        Last Month Best Selling Products
                    </h3>
                </div>
                <div class="md-card-content">
                    <div class="uk-overflow-container">
                        <table class="uk-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Brand</th>
                                    <th>Product Name</th>
                                    <th>Qty in Liters</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($lmbs != "") {

                                    foreach ($lmbs as $key => $lmb) {
                                ?>
                                        <tr>
                                            <td><?php echo $key + 1 ?></td>
                                            <td><?php echo $lmb->brand ?></td>
                                            <td><?php echo $lmb->product_name ?></td>
                                            <td><?php echo $lmb->quantity ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> -->



 
</div>
</div>



<!-- page specific plugins -->
<!-- d3 -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/d3/d3.min.js"></script>
<!-- metrics graphics (charts) -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/metrics-graphics/dist/metricsgraphics.min.js"></script>
<!-- c3.js (charts) -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/c3js-chart/c3.min.js"></script>
<!-- chartist (charts) -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/chartist/dist/chartist.min.js"></script>
<!--  charts functions -->
<!-- peity (small charts) -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/peity/jquery.peity.min.js"></script>
<!-- easy-pie-chart (circular statistics) -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
<!-- countUp -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/countUp.js/dist/countUp.min.js"></script>
<!-- handlebars.js -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/handlebars/handlebars.min.js"></script>
<script src="<?php echo base_url('themes/v1/assets/'); ?>js/custom/handlebars_helpers.min.js"></script>
<!-- CLNDR -->
<script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/clndr/clndr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>

<!-- chart.js -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.3.3/chart.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // $(document).ready(function() {

    // // var csrfName = "<?php //echo $this->security->get_csrf_token_name(); 
                            ?>",
    // //         csrfHash = "<?php //echo $this->security->get_csrf_hash(); 
                                ?>";
    // //     $.ajax({
    // //         url:"<?php //echo admin_url('welcome/salesreport'); 
                        ?>",
    // //         success: function (data) {
    // //             // console.log(data);
    // //         },

    // var csrfName = "<?php //echo $this->security->get_csrf_token_name(); 
                        ?>",
    //     csrfHash = "<?php //echo $this->security->get_csrf_hash(); 
                        ?>";

    //     $.ajax({
    //     url: "<?php //echo admin_url('welcome/salesreport'); 
                    ?>",
    //     success: function (data) {
    //         var salesData = JSON.parse(data);

    //         var labels = [];
    //         var totalSales = [];
    //         salesData.forEach(function(item) {
    //             labels.push(item.sale_date);
    //             totalSales.push(item.total_sales);
    //         });

    //         const totalDuration = 10000;
    //         const delayBetweenPoints = totalDuration / totalSales.length;

    //         const animation = {
    //             x: {
    //                 type: 'number',
    //                 easing: 'linear',
    //                 duration: delayBetweenPoints,
    //                 from: NaN,
    //                 delay(ctx) {
    //                     if (ctx.type !== 'data' || ctx.xStarted) {
    //                         return 0;
    //                     }
    //                     ctx.xStarted = true;
    //                     return ctx.index * delayBetweenPoints;
    //                 }
    //             },
    //             y: {
    //                 type: 'number',
    //                 easing: 'linear',
    //                 duration: delayBetweenPoints,
    //                 from: (ctx) => ctx.chart.scales.y.getPixelForValue(100),
    //                 delay(ctx) {
    //                     if (ctx.type !== 'data' || ctx.yStarted) {
    //                         return 0;
    //                     }
    //                     ctx.yStarted = true;
    //                     return ctx.index * delayBetweenPoints;
    //                 }
    //             }
    //         };

    //         var ctx = document.getElementById('salesChart').getContext('2d');
    //         var salesChart = new Chart(ctx, {
    //             type: 'line',
    //             data: {
    //                 labels: labels,
    //                 datasets: [{
    //                     label: 'Total Sales',
    //                     data: totalSales,
    //                     borderColor: Utils.CHART_COLORS.red,
    //                     borderWidth: 1,
    //                     radius: 0,
    //                 }]
    //             },
    //             options: {
    //                 animation: animation,
    //                 interaction: {
    //                     intersect: false
    //                 },
    //                 plugins: {
    //                     legend: false
    //                 },
    //                 scales: {
    //                     x: {
    //                         type: 'linear',
    //                         grid: {
    //                             display: false 
    //                         }
    //                     },
    //                     y: {
    //                         beginAtZero: true,
    //                         ticks: {
    //                             callback: function(value, index, values) {
    //                                 if (value >= 1000000) {
    //                                     return (value / 1000000).toFixed(1) + ' Million';
    //                                 }
    //                                 return value;
    //                             }
    //                         },
    //                         grid: {
    //                             display: false 
    //                         }
    //                     }
    //                 }
    //             }
    //         });
    //     }
    // });



    //     });


    $(document).ready(function() {

        var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
            csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";

        $.ajax({
            url: "<?php echo admin_url('welcome/salesreport'); ?>",
            success: function(data) {
                var salesData = JSON.parse(data);

                var today = new Date();
                var thirtyDaysAgo = new Date(today);
                thirtyDaysAgo.setDate(today.getDate() - 30);

                var filteredSalesData = salesData.filter(function(item) {
                    var saleDate = new Date(item.sale_date);
                    return saleDate >= thirtyDaysAgo;
                });

                var labels = [];
                var totalSales = [];
                filteredSalesData.forEach(function(item) {
                    var saleDate = new Date(item.sale_date);
                    labels.push(moment(saleDate).format('MMM D'));
                    totalSales.push(item.total_sales);
                });

                var ctx = document.getElementById('salesChart').getContext('2d');
                var gradient = ctx.createLinearGradient(215, 171, 75, 100);
                gradient.addColorStop(0, 'rgba(252, 0, 52, 100)');
                gradient.addColorStop(1, 'rgba(20, 42, 153, 100)');

                var salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Last Month Sale',
                            data: totalSales,
                            borderColor: gradient,
                            borderWidth: 3,
                            fill: false,
                            pointStyle: 'rect',
                            pointRadius: 6,
                            pointBackgroundColor: 'white',
                            backgroundColor: 'rgba(75, 192, 192, 1)',
                            hoverBackgroundColor: 'rgba(75, 192, 192, 1)'
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                type: 'category',
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000000) {
                                            return (value / 1000000).toFixed(0) + ' Million';
                                        }
                                        return value;
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });





        var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
            csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";


        $.ajax({
            url: "<?php echo admin_url('welcome/purchasereport'); ?>",
            success: function(data) {
                console.log(data);
                var salesData = JSON.parse(data);

                var today = new Date();
                var thirtyDaysAgo = new Date(today);
                thirtyDaysAgo.setDate(today.getDate() - 30);

                var filteredSalesData = salesData.filter(function(item) {
                    var saleDate = new Date(item.sale_date);
                    return saleDate >= thirtyDaysAgo;
                });

                var labels = [];
                var totalSales = [];
                filteredSalesData.forEach(function(item) {
                    var saleDate = new Date(item.sale_date);
                    var formattedDate = saleDate.toLocaleString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });
                    labels.push(formattedDate);
                    totalSales.push(item.total_sales);
                });

                var ctx = document.getElementById('purchaseChart').getContext('2d');

                var gradient = ctx.createLinearGradient(215, 171, 75, 100);
                gradient.addColorStop(0, 'rgba(252, 0, 52, 100)');
                gradient.addColorStop(1, 'rgba(20, 42, 153, 100)');

                var purchaseChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Last Month Purchase',
                            data: totalSales,
                            borderColor: gradient,
                            borderWidth: 3,
                            fill: false,
                            pointStyle: 'rect',
                            pointBackgroundColor: 'white',
                            pointRadius: 6,
                            backgroundColor: 'rgba(75, 192, 192, 1)',
                            hoverBackgroundColor: 'rgba(75, 192, 192, 1)'
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000000) {
                                            return (value / 1000000).toFixed(0) + ' Million';
                                        }
                                        return value;
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                            }
                        }
                    }
                });
            }
        });


        var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>",
            csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";


        $.ajax({
            url: "<?php echo admin_url('welcome/yearlySalereport'); ?>",
            success: function(data) {
                console.log(data);
                var salesData = JSON.parse(data);

                var today = new Date();
                var thirtyDaysAgo = new Date(today);
                // thirtyDaysAgo.setDate(today.getDate() - 30);

                var filteredSalesData = salesData.filter(function(item) {
                    var saleDate = new Date(item.year, item.month - 1);
                    return saleDate;
                });

                var labels = [];
                var totalSales = [];
                filteredSalesData.forEach(function(item) {
                    var saleDate = new Date(item.year, item.month - 1);
                    console.log(saleDate);
                    var formattedDate = saleDate.toLocaleString('en-US', {
                        month: 'short'
                    });
                    labels.push(formattedDate);
                    totalSales.push(item.total_sales);
                });

                var ctx = document.getElementById('yearlySaleChart').getContext('2d');

                var gradient = ctx.createLinearGradient(215, 171, 75, 100);
                gradient.addColorStop(0, 'rgba(252, 0, 52, 100)');
                gradient.addColorStop(1, 'rgba(20, 42, 153, 100)');

                var yearlyPrchaseChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Yearly Sale',
                            data: totalSales,
                            borderColor: gradient,
                            borderWidth: 3,
                            fill: false,
                            pointStyle: 'rect',
                            pointBackgroundColor: 'white',
                            pointRadius: 6,
                            backgroundColor: 'rgba(75, 192, 192, 1)',
                            hoverBackgroundColor: 'rgba(75, 192, 192, 1)'
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000000) {
                                            return (value / 1000000).toFixed(0) + ' Million';
                                        }
                                        return value;
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                            }
                        }
                    }
                });
            }
        });


        $.ajax({
            url: "<?php echo admin_url('welcome/yearlyPurchasereport'); ?>",
            success: function(data) {
                console.log(data);
                var salesData = JSON.parse(data);

                var today = new Date();
                var thirtyDaysAgo = new Date(today);
                // thirtyDaysAgo.setDate(today.getDate() - 30);

                var filteredSalesData = salesData.filter(function(item) {
                    var saleDate = new Date(item.year, item.month - 1);
                    return saleDate;
                });

                var labels = [];
                var totalSales = [];
                filteredSalesData.forEach(function(item) {
                    var saleDate = new Date(item.year, item.month - 1);
                    console.log(saleDate);
                    var formattedDate = saleDate.toLocaleString('en-US', {
                        month: 'short'
                    });
                    labels.push(formattedDate);
                    totalSales.push(item.total_sales);
                });

                var ctx = document.getElementById('yearlyPrchaseChart').getContext('2d');

                var gradient = ctx.createLinearGradient(215, 171, 75, 100);
                gradient.addColorStop(0, 'rgba(252, 0, 52, 100)');
                gradient.addColorStop(1, 'rgba(20, 42, 153, 100)');

                var yearlyPrchaseChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Yearly Purchase',
                            data: totalSales,
                            borderColor: gradient,
                            borderWidth: 3,
                            fill: false,
                            pointStyle: 'rect',
                            pointBackgroundColor: 'white',
                            pointRadius: 6,
                            backgroundColor: 'rgba(75, 192, 192, 1)',
                            hoverBackgroundColor: 'rgba(75, 192, 192, 1)'
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000000) {
                                            return (value / 1000000).toFixed(0) + ' Million';
                                        }
                                        return value;
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                            }
                        }
                    }
                });
            }
        });

        $.ajax({
            url: "<?php echo admin_url('welcome/productsale'); ?>",
            success: function(data) {
                console.log(data);
                var salesData = JSON.parse(data);

                var labels = [];
                var totalSales = [];

                salesData.forEach(function(item) {
                    labels.push(item.product_name);
                    totalSales.push(item.total_sum);
                });

                var ctx = document.getElementById('productWiseSale').getContext('2d');

                var gradient = ctx.createLinearGradient(215, 171, 75, 100);
                gradient.addColorStop(0, 'rgba(252, 0, 52, 100)');
                gradient.addColorStop(1, 'rgba(20, 42, 153, 100)');

                var productSaleChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Product Sales',
                            data: totalSales,
                            backgroundColor: gradient,
                            borderWidth: 1,
                            barThickness: 15,
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000000) {
                                            return (value / 1000000).toFixed(0) + ' Million';
                                        }
                                        return value;
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false,
                            }
                        },

                        plugins: {
                            title: {
                                display: true,
                                // text: 'Product Sales', 
                                position: 'top'
                            }
                        }
                    }
                });
            }
        });



        $.ajax({
            url: "<?php echo admin_url('welcome/productpurchase'); ?>",
            success: function(data) {
                console.log(data);
                var salesData = JSON.parse(data);

                var labels = [];
                var totalSales = [];

                salesData.forEach(function(item) {
                    labels.push(item.product_name);
                    totalSales.push(item.total_sum);
                });

                var ctx = document.getElementById('productWisePurchase').getContext('2d');

                var gradient = ctx.createLinearGradient(215, 171, 75, 100);
                gradient.addColorStop(0, 'rgba(252, 0, 52, 100)');
                gradient.addColorStop(1, 'rgba(20, 42, 153, 100)');

                var productSaleChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Product Purchases',
                            data: totalSales,
                            backgroundColor: gradient,
                            borderWidth: 1,
                            barThickness: 15,
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000000) {
                                            return (value / 1000000).toFixed(0) + ' Million';
                                        }
                                        return value;
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false,
                            }
                        },

                        plugins: {
                            title: {
                                display: true,
                                // text: 'Product Sales', 
                                position: 'top'
                            }
                        }
                    }
                });
            }
        });

        $.ajax({
            url: "<?php echo admin_url('welcome/supplierPurchase'); ?>",
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                var productNames = [];
                var productCounts = [];

                data.forEach(function(item) {
                    productNames.push(item.name);
                    productCounts.push(item.product_count);
                });

                var ctx = document.getElementById('supplier').getContext('2d');
                var pieChart = new Chart(ctx, {
                    type: 'pie',

                    data: {
                        labels: productNames,
                        datasets: [{
                            data: productCounts,
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                            ]
                        }]
                    },
                    options: {
                        cutoutPercentage: 0,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = productNames[context.dataIndex];
                                        var value = context.formattedValue;
                                        return label + ': ' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });



    });



    // });
</script>