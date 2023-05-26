<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><?= isset($fetched_data[0]['id']) ? 'Update' : 'Add' ?> Blog</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Category for blog</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/blogs/add_blog'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php if (isset($fetched_data[0]['id'])) { ?>
                                <input type="hidden" name="edit_blog" value="<?= @$fetched_data[0]['id'] ?>">
                            <?php } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="blog_title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="blog_title" placeholder="Title" name="blog_title" value="<?= isset($fetched_data[0]['title']) ? output_escaping($fetched_data[0]['title']) : "" ?>">
                                    </div>
                                </div>

                                <?php
                                $category_id = (isset($fetched_data[0]['category_id']) && !empty($fetched_data[0]['category_id']) ? $fetched_data[0]['category_id'] : '');
                                ?>
                                <div class="form-group row">
                                    <label for="blog_category" class="col-sm-2 col-form-label">Select Categories <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-6 ">
                                        <select name="blog_category" class="get_blog_category w-100" data-placeholder=" Type to search and select products">

                                            <?php
                                            $category_name =  fetch_details('blog_categories', "", 'name,id', "", "", "", "", "id", $category_id);
                                            foreach ($category_name as $row) {
                                            ?>

                                                <?php if (isset($fetched_data[0]['category_id']) && ($fetched_data[0]['category_id']) != '') {
                                                ?>
                                                    <option><?= $row['name'] ?></option>

                                                <?php } else { ?>
                                                    <option><?= '' ?></option>
                                                <?php } ?>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <br>
                                <div class="form-group">
                                    <label name='blog_category' for="image">Main Image <span class='text-danger text-sm'>*</span><small>(Recommended Size : larger than 400 x 260 & smaller than 600 x 300 pixels.)</small></label>
                                    <div class="col-sm-10">
                                        <div class='col-md-3'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='blog_image' data-isremovable='0' data-is-multiple-uploads-allowed='0' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                        <?php
                                        if (file_exists(FCPATH . @$fetched_data[0]['image']) && !empty(@$fetched_data[0]['image'])) {
                                        ?>
                                            <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                            <div class="container-fluid row image-upload-section">
                                                <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                    <div class='image-upload-div'><img class="img-fluid mb-2" src="<?= BASE_URL() . $fetched_data[0]['image'] ?>" alt="Image Not Found"></div>
                                                    <input type="hidden" name="blog_image" value='<?= $fetched_data[0]['image'] ?>'>
                                                </div>
                                            </div>
                                        <?php
                                        } else { ?>
                                            <div class="container-fluid row image-upload-section">
                                                <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none"></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea name="blog_description" class="textarea addr_editor" placeholder="Place some text here"><?= (isset($fetched_data[0]['description'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $fetched_data[0]['description'])) : ''; ?></textarea>
                                    <div class="form-group">
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                        <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Blog' : 'Add Blog' ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                    </div>
                    <!-- /.card-footer -->
                    </form>
                </div>
                <!--/.card-->
            </div>
            <!--/.col-md-12-->
        </div>
        <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>