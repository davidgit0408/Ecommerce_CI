<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage blogs</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Blogs</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade edit-modal-lg" id="category_form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                            <div class="col-md-3">
                                <label for="zipcode" class="col-form-label">Filter By Category</label>
                                <select class='form-control' name='category_parent' id="category_parent">
                                    <option value="">Select Category</option>
                                    <?php foreach ($fetched_data as $categories) { ?>
                                        <option value="<?= $categories['id'] ?>" <?= (isset($categories[0]['id']) && $categories[0]['id'] == $categories['id']) ? 'selected' : "" ?>><?= $categories['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="card-tools">
                                <a href="<?= base_url() . 'admin/blogs/create-blog' ?>" class="btn btn-block  btn-outline-primary btn-sm">Add Blogs</a>
                            </div>
                        </div>
                        <div class="card-innr" id="list_view_html">
                            <div class="card-head">
                                <h4 class="card-title">Blogs</h4>
                            </div>
                            <div class="gaps-1-5x"></div>

                            <table class='table-striped' id='category_table' data-toggle="table" data-url="<?= base_url('admin/blogs/view_blogs') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{
                        "fileName": "category-list",
                        "ignoreColumn": ["state"] 
                        }' data-query-params="blog_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true" data-visible='true'>ID</th>
                                        <th data-field="blog_category" data-sortable="false" data-align='center'>Category</th>
                                        <th data-field="title" data-sortable="true" data-align='center'>Title</th>
                                        <th data-field="description" data-sortable="true" data-align='center'>Description</th>
                                        <th data-field="image" data-sortable="true" data-align='center'>Image</th>
                                        <th data-field="operate" data-sortable="true" data-align='center'>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                        <div id="tree_view_html">
                        </div>
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>