<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>Order Tracking </h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('seller/home') ?>">Home</a></li>
            <li class="breadcrumb-item active">Order Tracking</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 main-content">
          <div class="card content-area p-4">
            <div class="card-innr">
              <div class="gaps-1-5x"></div>
              <table class='table-striped' data-toggle="table" data-url="<?= base_url('seller/orders/get-order-tracking') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="customer_wallet_query_params">
                <thead>
                  <tr>
                    <th data-field="id" data-sortable="true">ID</th>
                    <th data-field="order_id" data-sortable="true">Order ID</th>
                    <th data-field="order_item_id" data-sortable="false">Order Item ID</th>
                    <th data-field="courier_agency" data-sortable="false">courier_agency</th>
                    <th data-field="tracking_id" data-sortable="false">tracking_id</th>
                    <th data-field="url" data-sortable="false" >URL</th>
                    <th data-field="date" data-sortable="false">Date</th>
                    <th data-field="operate" data-sortable="true">Actions</th>
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