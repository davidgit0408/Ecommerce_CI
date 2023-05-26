<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>Attribute</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
            <li class="breadcrumb-item active">Attribute</li>
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
            <form class="form-horizontal form-submit-event" action="<?= base_url('admin/attributes/add_attributes'); ?>"  method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <?php if (isset($fetched_data[0]['id'])) { ?>
                  <input type="hidden" name="edit_attribute" value="<?= @$fetched_data[0]['id'] ?>">
                <?php  } ?>
                <div class="form-group row">
                  <label for="attribute_set" class="col-sm-2 col-form-label">Select Attribute Set <span class='text-danger text-sm'>*</span></label>
                  <div class="col-sm-5">
                    <select class="form-control" id="attribute_set" name="attribute_set">
                      <option value=""> None </option>
                      <?php foreach ($attribute_set as $row) {
                      ?>
                        <option value="<?= $row['id'] ?>" <?= (isset($fetched_data[0]['attribute_set_id']) && $fetched_data[0]['attribute_set_id'] == $row['id']) ? 'selected' : '' ?>> <?= $row['name'] ?> </option>
                      <?php
                      } ?>

                    </select>
                  </div>
                  <?php if (empty($fetched_data[0]['id'])) { ?>
                  <div>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#attributeSet_add"><i class="fas fa-plus"></i></a>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#attributeSet_list"><i class="fas fa-list"></i></a>
                  </div>
                  <?php  } ?>


                </div>
                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Attribute Name <span class='text-danger text-sm'>*</span></label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="<?= @$fetched_data[0]['name'] ?>">
                  </div>
                  
                </div>

                <!-- attribute value  -->

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Attribute Values <span class='text-danger text-sm'>*</span></label>
                  <button type="button" id="add_attribute_value" class="btn btn-primary">Attribute Values </button>
                </div>
                <div id="attribute_section"> </div>
                <br>

                <div class="form-group">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Attribute' : 'Add Attribute' ?></button>
                </div>
              </div>
              <div class="d-flex justify-content-center">
                <div class="form-group" id="error_box">
                </div>
              </div>
              <!-- /.card-body -->

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

<?php if (empty($fetched_data[0]['id'])) { ?>

<!-- atttribute set add modal  -->
<div class="modal fade" id="attributeSet_add" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Attribute Set</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-info">
            <!-- form start -->
            <form class="form-horizontal form-submit-event" action="<?= base_url('admin/attribute_set/add_attribute_set'); ?>" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <?php if (isset($fetched_data[0]['id'])) { ?>
                  <input type="hidden" name="edit_attribute_set" value="<?= @$fetched_data[0]['id'] ?>">
                <?php  } ?>
                <div class="form-group row">

                  <label for="name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="<?= @$fetched_data[0]['name'] ?>">
                  </div>

                  <!-- test code -->

                  
                </div>
                <div class="form-group">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Attribute Set' : 'Add Attribute Set' ?></button>
                </div>
              </div>



              <div class="d-flex justify-content-center">
                <div class="form-group" id="error_box">
                </div>
              </div>
            </form>
          </div>
          <!--/.card-->
        </div>
        <!--/.col-md-12-->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
      </div>
    </div>
  </div>
</div>

<!-- attribute set list modal -->

<div class="modal fade" id="attributeSet_list" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">List Of Attribute Set</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content p-3 p-md-5">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Edit Attribute Set</h5>
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

                  </div>
                  <div class="card-innr">
                    <div class="card-head">
                      <h4 class="card-title">Attribute Set</h4>
                    </div>
                    <div class="gaps-1-5x"></div>
                    <table class='table-striped' id='category_table' data-toggle="table" data-url="<?= base_url('admin/attribute_set/attribute_set_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel","csv"]' data-export-options='{
                        "fileName": "attribute-set-list",
                        "ignoreColumn": ["state"] 
                        }' data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="id" data-sortable="true">ID</th>
                          <th data-field="name" data-sortable="false">Name</th>
                          <th data-field="status" data-sortable="false">Status</th>
                          <th data-field="operate" data-sortable="true">Action</th>
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
      </div>
    </div>
  </div>
</div>
<?php  } ?>