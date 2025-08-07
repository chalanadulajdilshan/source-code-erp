<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';
$COUNTRY = new Country();

// Get the last inserted package id
$lastId = $COUNTRY->getLastID();
$country_id = 'COU/0' . ($lastId + 1);

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Country Management | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>



</head>

<body data-layout="horizontal" data-topbar="colored" class="someBlock">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include 'navigation.php' ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-md-8 d-flex align-items-center flex-wrap gap-2">
                            <a href="#" class="btn btn-success" id="new">
                                <i class="uil uil-plus me-1"></i> New
                            </a>

                            <?php if ($PERMISSIONS['add_page']): ?>
                            <a href="#" class="btn btn-primary" id="create">
                                <i class="uil uil-save me-1"></i> Save
                            </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['edit_page']): ?>
                            <a href="#" class="btn btn-warning" id="update">
                                <i class="uil uil-edit me-1"></i> Update
                            </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['delete_page']): ?>
                            <a href="#" class="btn btn-danger delete-country">
                                <i class="uil uil-trash-alt me-1"></i> Delete
                            </a>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Country Master</li>
                            </ol>
                        </div>
                    </div>

                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">

                                <div class="p-4">

                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xs">
                                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                    01
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1">Country Master</h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>

                                </div>

                                <div class="p-4">

                                    <form id="form-data" autocomplete="off">
                                        <div class="row">
 
                                            <div class="col-md-2">
                                                <label class="form-label" for="code">Ref No</label>
                                                <div class="input-group mb-3">
                                                <input id="code" name="code" type="text" value="<?php echo $country_id; ?>"
                                                    readonly class="form-control" placeholder="Ref No"> 
                                                
                                                    <button class="btn btn-info" type="button"  data-bs-toggle="modal"
                                                    data-bs-target="#countryModel">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>                                    

                                            <div class="col-md-2">
                                                <label for="name" class="form-label">Country Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="name" name="name" type="text"
                                                    placeholder="Enter Country Name" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_active"
                                                        name="is_active">
                                                    <label class="form-check-label" for="is_active">
                                                        Active
                                                    </label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <input type="hidden" id="id" name="id" >
                                        
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- container-fluid -->
            </div>


            <?php include 'footer.php' ?>

        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <div class="modal fade bs-example-modal-xl" id="countryModel" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Manage Country</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <table  class="datatable table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <td>Id</td>
                                        <th>Ref No</th>
                                        <th>Name</th>
                                        <th>Is Active</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php
                                $COUNTRY = new Country(NULL);
                                foreach ($COUNTRY->all() as $key => $country) {
                                    $key++;
                                    ?>
                                    <tr class="select-country" data-id="<?php echo $country['id']; ?>"
                                        data-code="<?php echo htmlspecialchars($country['code']); ?>"
                                        data-name="<?php echo htmlspecialchars($country['name']); ?>"
                                        data-active="<?php echo htmlspecialchars($country['is_active']); ?>"
                                    >
                                        <td><?php echo $key; ?></td>
                                        <td><?php echo htmlspecialchars($country['code']); ?></td>
                                        <td><?php echo htmlspecialchars($country['name']); ?></td>
                                        <td>
                                                <?php if ($country['is_active'] == 1): ?>
                                                    <span class="badge bg-soft-success font-size-12">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-soft-danger font-size-12">Inactive</span>
                                                <?php endif; ?>
                                            </td> 
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/country.js"></script>


       <!-- include main js  -->
       <?php include 'main-js.php' ?>

</body>

</html>