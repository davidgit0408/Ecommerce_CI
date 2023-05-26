<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>System Notifications</h4>
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
                            <div class="form-group col-md-2">
                                <div>
                                    <select id="message_type" name="message_type" placeholder="Select Message Type" required="" class="form-control">
                                        <option value="">All Messages</option>
                                        <option value="1">Read</option>
                                        <option value="0">Un-Read</option>
                                    </select>
                                </div>
                            </div>
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' id='system_notofication_table' data-toggle="table" data-url="<?= base_url('admin/Notification_settings/get_notifications_data') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="read_by" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-query-params="noti_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true" data-align='center'>ID</th>
                                        <th data-field="title" data-sortable="false" data-align='center'>Title</th>
                                        <th data-field="message" data-sortable="false" data-align='center'>Message</th>
                                        <th data-field="type" data-sortable="false" data-align='center'>Type</th>
                                        <th data-field="type_id" data-sortable="false" data-align='center'>Type Id</th>
                                        <th data-field="read_by" data-sortable="false" data-align='center'>Read By</th>
                                        <th data-field="operate" data-sortable="true" data-align='center'>Actions</th>
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