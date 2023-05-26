<?php
    $current_version = get_current_version();
    $profileUrl = base_url('delivery_boy/home/profile');
    $logoutUrl = base_url('delivery_boy/home/logout');
    if ($this->ion_auth->is_admin()) {
        $profileUrl = base_url('admin/home/profile');
        $logoutUrl = base_url('admin/home/logout');
    }
?>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <div class="badge bg-primary rounded-pill ms-auto">v <?= (isset($current_version) && !empty($current_version)) ? $current_version : '1.0' ?></div>
            </div>
        </div>

        <?php
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            ?>
            <li class="nav-item my-auto ml-2">
                <span class="badge badge-danger">Demo mode</span>
            </li>
        <?php } ?>

        <!-- google translate  -->
<!--        <div id="google_translate_element"></div>-->
        <!-- start send admin notification  -->
        <?php
        $notifications = fetch_details('system_notification',  NULL,  '*',  '3', '0',  'read_by', 'ASC',  '',  '');
        $count_noti = fetch_details('system_notification',  ["read_by" => 0],  'count(id) as total');
        ?>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Language -->
            <li class="nav-item dropdown-language dropdown me-1 me-xl-0">
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-language="ar">
                            <span class="align-middle">عربي</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-language="en">
                            <span class="align-middle">English</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ Language -->

            <!-- Style Switcher -->
            <li class="nav-item me-1 me-xl-0">
                <a
                        class="nav-link btn btn-text-secondary rounded-pill btn-icon style-switcher-toggle hide-arrow"
                        href="javascript:void(0);">
                    <i class="mdi mdi-24px"></i>
                </a>
            </li>
            <!--/ Style Switcher -->

            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                <a
                        class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                        href="javascript:void(0);"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        id="refresh_notification1">
                    <i class="mdi mdi-bell-outline mdi-24px"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="mb-0 me-auto">Notification</h6>
                            <span class="badge rounded-pill bg-label-primary" id="notification_count1"></span>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush" id="notification_content">

                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top p-2">
                        <a href="<?= base_url('admin/Notification_settings/manage_system_notifications') ?>" class="btn btn-primary d-flex justify-content-center">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ Notification -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="<?= base_url('assets/admin/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="<?= base_url('assets/admin/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block"><?= ucfirst($this->ion_auth->user()->row()->username) ?></span>
                                    <small class="text-muted">Welcome</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= $profileUrl ?>">
                            <i class="mdi mdi-account-outline me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= $logoutUrl ?>" target="_blank">
                            <i class="mdi mdi-logout me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
<script>
    $(document).ready(function () {
        setInterval(function () {
            $.ajax({
                type: 'GET',
                url: base_url + 'admin/home/get_notification',
                dataType: 'json',
                success: function (result) {
                    var html = '';
                    if (result.count_notifications > 0) {
                        html = '<i class="mdi mdi-bell-outline mdi-24px"></i><span class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>';
                    } else {
                        html = '<i class="mdi mdi-bell-outline mdi-24px"></i>';
                    }
                    $('#refresh_notification1').html(html);
                    $('#notification_count1').html('news ' + result.count_notifications);
                }
            });
        }, 30000);
    });

    $(document).on('click', '#refresh_notification1', function (e, rows) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: base_url + 'admin/home/new_notification_list',
            dataType: 'json',
            success: function (result) {
                var html = '';
                var beep;
                var seconds_ago;
                var time;
                $.each(result.notifications, function (i, a) {
                    beep = (a.read_by && a.read_by == 0) ? '<span><i class="fa fa-certificate ml-3 text-orange text-sm"></i></span>' : "";
                    seconds_ago = a.date_sent;

                    html += '<li class="list-group-item list-group-item-action dropdown-notifications-item"> \
    <a href=" ' + base_url + 'admin/orders/edit_orders' + '?edit_id=' + a.type_id + '&noti_id=' + a.id + '" class="d-flex gap-2"> \
        <div class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200"> \
            <h6 class="mb-1 text-truncate">' + a.title + beep + '</h6> \
            <small class="text-truncate text-body">' + a.message + '</small> \
        </div> \
        <div class="flex-shrink-0 dropdown-notifications-actions"> \
            <small class="text-muted">' + seconds_ago + '</small> \
        </div> \
    </a> \
</li>';
                //     html += '  <a href=" ' + base_url + 'admin/orders/edit_orders' + '?edit_id=' + a.type_id + '&noti_id=' + a.id + '"\ class="dropdown-item">\
                //             <div class="media">\
                //                 <div class="media-body">\
                //                     <h3 class="dropdown-item-title">' + a.title + beep + '</h3>\
                //                     <p class="text-sm">' + a.message + '</p>\
                //                     <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>' + seconds_ago + '</p>\
                //                 </div>\
                //             </div>\
                //         </a>\
                // <div class="dropdown-divider"></div>';
                });

                // html += '<a href="' + base_url + 'admin/Notification_settings/manage_system_notifications' + '" class="dropdown-item dropdown-footer">See All Messages</a>';

                $('ul#notification_content').html(html);
                // $('#notification_content').on('click', function (e) {
                //     $("#list").removeClass("show");
                // });
            }
        });
    });
</script>