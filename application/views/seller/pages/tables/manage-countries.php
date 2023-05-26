<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Country List</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Countries </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="card content-area p-4">
                        <div class="card-innr">
                            <div class="card-head">
                                <h4 class="card-title">Countries </h4>
                            </div>
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' id='countries_table' data-toggle="table" data-url="<?= base_url('seller/area/country_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{
                        "fileName": "countries-list",
                        "ignoreColumn": ["state"] 
                        }' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="numeric_code" data-sortable="false">Numeric Code</th>
                                        <th data-field="name" data-sortable="false">Name</th>
                                        <th data-field="capital" data-sortable="false" data-visible="false">Capital</th>
                                        <th data-field="phonecode" data-sortable="false">Phonecode</th>
                                        <th data-field="currency" data-sortable="false">Currency</th>
                                        <th data-field="currency_name" data-sortable="false" data-visible="false">Currency Name</th>
                                        <th data-field="currency_symbol" data-sortable="false" data-visible="false">Currency Symbol</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>