<div class="container-xxl flex-grow-1 container-p-y">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade" id="product-rating-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">View Product Rating</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="tab-pane " role="tabpanel" aria-labelledby="product-rating-tab">
                                    <table class='table-striped' id="product-rating-table" data-toggle="table" data-url="<?= base_url('admin/product/get_rating_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="ratingParams">
                                        <thead>
                                        <tr>
                                            <th data-field="id" data-sortable="true">ID</th>
                                            <th data-field="username" data-width='500' data-sortable="false" class="col-md-6">Username</th>
                                            <th data-field="rating" data-sortable="false">Rating</th>
                                            <th data-field="comment" data-sortable="false">Comment</th>
                                            <th data-field="images" data-sortable="true">Images</th>
                                            <th data-field="data_added" data-sortable="false">Data added</th>
                                            <th data-field="operate" data-sortable="false">Operate</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-3 mb-3">
                    <!-- Change /upload-target to your upload address -->
                    <div id="dropzone_reel" class="dropzone"></div>
                    <br>
                    <a href="" id="upload-files-btn-reel" class="btn btn-success float-right">Upload</a>
                </div>
                <div class="col-12 border-bottom">
                    <div class="col-lg-9 col-md-8">
                        <div class="section-title">
                            <h4 class="title mb-2">Reel Gallery</h4>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label>Date and time range:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="text" class="form-control float-right" autocomplete="off" id="datepicker">
                                <input type="hidden" id="start_date" class="form-control float-right">
                                <input type="hidden" id="end_date" class="form-control float-right">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-4">
                            <div class="row mt-2">
                                <div class="col-md-4 d-flex align-items-center pt-4">
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-2" onclick="status_date_wise_search()">Search</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="resetfilters()">Reset</button>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12 main-content">
                            <div class="card content-area p-4">
                                <div class="card-head">
                                    <h4 class="card-title">Reel Details</h4>
                                </div>
                                <div class="card-innr">
                                    <div class="gaps-1-5x"></div>
                                    <table class='table-striped' id='media-table' data-page-size="5" data-toggle="table" data-url="<?= base_url('admin/reel/fetch') ?>" data-click-to-select="true" data-single-select='true' data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-query-params="mediaUploadParams">
                                        <thead>
                                            <tr>
                                                <th data-field="state" data-checkbox="true"></th>
                                                <th data-field="id" data-sortable="true" data-visible='false' data-align='center'>ID</th>
                                                <th data-field="seller_id" data-sortable="true" data-visible='false' data-align='center'>Seller ID</th>
                                                <th data-field="seller_name" data-sortable="true" data-align='center'>Seller Name</th>
                                                <th data-field="name" data-sortable="false" data-align='center'>Name</th>
                                                <th data-field="image" data-sortable="false" data-align='center'>Image</th>
                                                <th data-field="extension" data-sortable="false" data-align='center'>Extension</th>
                                                <th data-field="favorites_count" data-sortable="false" data-align='center'>favorites_count</th>
                                                <th data-field="sub_directory" data-sortable="false" data-align='center'>Sub directory</th>
                                                <th data-field="size" data-sortable="false" data-align='center'>Size</th>
                                                <th data-field="operate" data-sortable="false" data-align='center'>Actions</th>
                                                <th data-field="status" data-sortable="true">Status</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div><!-- .card-innr -->
                            </div><!-- .card -->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end col-->
            </div>
        </div>
    </section>
</div>