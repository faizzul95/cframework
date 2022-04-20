<?php

class Migrate
{
    public function index($param = NULL)
    {
        if (filter_var($_ENV['MIGRATION_ENABLE'], FILTER_VALIDATE_BOOLEAN) === TRUE) {
            // $this->migration($param);
            $this->listFileMigrate();
        } else {
            error('404'); // redirect to page error 404
        }
    }

    // migrate specific filess
    public function file($classNameRun = NULL, $drop = NULL)
    {
        if (empty($drop)) {
            $filename = "../database/migrations/$classNameRun.php";
            if (file_exists($filename)) {
                require_once(str_replace('\\', '/', $filename));
                $classes = get_declared_classes();
                $class = end($classes);

                $obj = new $class; // create new object

                // check if function up is exist
                if (method_exists($obj, 'up')) {
                    $obj->up();
                }

                $this->relation($classNameRun); // add relation

            } else {
                echo "The file <b style='color:red'><i>$filename</i></b> does not exist <br>";
            }
        } else {
            $filename = "../database/migrations/$classNameRun.php";
            if (file_exists($filename)) {
                require_once(str_replace('\\', '/', $filename));
                $classes = get_declared_classes();
                $class = end($classes);

                $obj = new $class; // create new object

                // check if function down is exist
                if (method_exists($obj, 'down')) {
                    $obj->down();
                }
            } else {
                echo "The file <b style='color:red'><i>$filename</i></b> does not exist <br>";
            }
        }
    }

    // migrate all files
    public function all()
    {
        // check_db_exist(); // check if db not exist create it first.
        removeAllRelation(); // remove all Constrain / relation table first before migrate
        // echo "===== Migration start ===== <br><br>";
        foreach (glob('../database/migrations/*.php') as $filename) {
            if (file_exists($filename)) {
                require_once(str_replace('\\', '/', $filename));
                $classes = get_declared_classes();
                $class = end($classes);

                $obj = new $class; // create new object

                // check if function down is exist
                // if (method_exists($obj, 'down')) {
                //     $obj->down();
                // }

                // check if function up is exist
                if (method_exists($obj, 'up')) {
                    $obj->up();
                }
            } else {
                echo "The file <b style='color:red'><i>$filename</i></b> does not exist <br>";
            }
        }
        // echo "<br> ===== Migration ended ===== <br><br>";
        $this->relation(); // add relation table back
    }

    // relation
    public function relation($className = NULL)
    {
        if (empty($className)) {
            // removeAllRelation(); // remove all Constrain / relation table first before migrate
            foreach (glob('../database/migrations/*.php') as $filename) {
                if (file_exists($filename)) {
                    $className = getClassFullNameFromFile(str_replace('', "'\\'", $filename));

                    $obj = new $className; // create new object

                    // // check if function relation is exist
                    if (method_exists($obj, 'relation')) {
                        $obj->relation();
                    }
                } else {
                    echo "The file $filename does not exist";
                }
            }
        } else {
            $filename = glob("../database/migrations/$className.php");
            if (file_exists($filename[0])) {
                require_once(str_replace('\\', '/', $filename[0]));
                $obj = new $className; // create new object

                // check if function relation is exist
                if (method_exists($obj, 'relation')) {
                    $obj->relation();
                }
            } else {
                echo "The file $filename does not exist";
            }
        }
    }

    public function listFileMigrate()
    {
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">';
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
        echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
        echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

        echo '<meta name="csrf-token" content="' . csrf_token() . '" />';
        echo '<meta name="base_url" content="' . base_url . '" />';

        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" />';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>';

        echo '<script src="' . base_url . 'public/framework/js/common.js"></script>';
        echo '<script src="' . base_url . 'public/framework/js/axios.min.js"></script>';

        echo '<script src="' . base_url . 'public/framework/vendor/cute-alert/cute-alert.js"></script>';
        echo '<link rel="stylesheet" href="' . base_url . 'public/framework/vendor/cute-alert/style.css">';

        $url = 'migrate/all';
        $urlSeed = url('seed');

        echo '<style>';
        echo 'body {
                    background-color: #cccccc;
                }';
        echo '</style>';

        echo '<div class="container">';
        echo '<div class="row">';

        echo '<div class="col-7">'; // start col-7
        echo '<div class="card mt-5 mb-5">';
        echo '<div class="card-body">';
        echo '<div class="card-header">
                <h1> Migration Files 
                <a href="' . $urlSeed . '" class="btn btn-sm btn-outline-danger float-end"> Go to Seed <i class="fa fa-arrow-right"></i> </a>
                <a href="javascript:void(0)" onclick="migrateAll(\'' . $url . '\')" class="btn btn-sm btn-outline-info mr-2 float-end"> <i class="fa fa-plus"> </i> Migrate All Files </a>
                </h1>
              </div>';

        echo '<div>
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <td width="3%">
                                    No
                                </td>
                                <td width="50%">
                                    File Name
                                </td>
                                <td width="47%">
                                    Action
                                </td>
                            </tr>
                        </thead>
                        <tbody>';

        $no = 1;
        foreach (glob('../database/migrations/*.php') as $file) {

            if (file_exists($file)) {

                $filename = explode('/', $file);
                $urlFiles = 'migrate/file/' . pathinfo(end($filename), PATHINFO_FILENAME);
                $urlFilesRelation = 'migrate/relation/' . pathinfo(end($filename), PATHINFO_FILENAME);
                echo '<tr>';
                echo '<td><center> ' . $no++ . '</center></td>';
                echo '<td>' . pathinfo(end($filename), PATHINFO_FILENAME) . '</td>';
                echo '<td>
                            <center> 
                                <a href="javascript:void(0)" onclick="migrateTable(\'' . $urlFiles . '\')"  class="btn btn-sm btn-primary"> <i class="fa fa-plus"> </i> Migrate </a>
                                <a href="javascript:void(0)" onclick="relationTable(\'' . $urlFilesRelation . '\')"  class="btn btn-sm btn-info"> <i class="fa fa-plus"> </i> Relation </a>
                                <a href="javascript:void(0)" onclick="dropTable(\'' . $urlFiles . '/drop\')"  class="btn btn-sm btn-danger"> <i class="fa fa-minus"> </i> Drop </a>
                            </center>
                        </td>';
                echo '</tr>';
            }
        }

        echo '      <tbody>
                        </table>
                </div>';

        echo '</div>';
        echo '</div>';
        echo '</div>'; // end of col-7 

        echo '<div class="col-5">'; // start of col-5

        echo '<div class="card mt-5 mb-5">';
        echo '<div class="card-body">';
        echo '<div class="card-header">
                <h1> Log Migration 
                <a href="javascript:void(0)" onclick="clearLog()"  class="btn btn-sm btn-warning"> <i class="fa fa-refresh"> </i> Clear </a>
                </h1>
              </div>';

        echo '<div class="mt-2" id="logMigrateNoData"> No file running </div>';
        echo '<div class="mt-2" id="logMigrateShow"> </div>';

        echo '</div>'; // end of card body
        echo '</div>'; // end of fard
        echo '</div>'; // end of col-5

        echo '</div>'; // end of row
        echo '</div>'; // end of container

        echo '<script>';
        echo 'function migrateTable(url){

                var filename = url.substring(url.lastIndexOf("/") + 1);

                Swal.fire({
                    title: "Are you sure?",
                    text: "This table will be added / updated !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                    cancelButtonText: "Discard",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-outline-danger"
                    },
                    reverseButtons: true
                }).then(
                    async (result) => {
                        if (result.isConfirmed) {
                            const res = await callApi("post", url);
                            if(isSuccess(res.status))
                            {
                                $("#logMigrateShow").append(res.data);
                                $("#logMigrateNoData").empty();
                            }else{
                                noti(res.status);
                            }
                        }
                }) 

              }';
        echo 'function dropTable(url){

                var filename = url.split("/").slice(-2)[0];

                Swal.fire({
                    title: "Are you sure?",
                    text: "This table will be drop !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                    cancelButtonText: "Discard",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-outline-danger"
                    },
                    reverseButtons: true
                }).then(
                    async (result) => {
                        if (result.isConfirmed) {
                            const res = await callApi("post", url);
                            if(isSuccess(res.status))
                            {
                                $("#logMigrateShow").append(res.data);
                                $("#logMigrateNoData").empty();
                            }else{
                                noti(res.status);
                            }
                        }
                })
                
            }';

        echo 'function relationTable(url){

                var filename = url.split("/").slice(-2)[1];

                Swal.fire({
                    title: "Are you sure?",
                    text: "Add relation for this files",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                    cancelButtonText: "Discard",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-outline-danger"
                    },
                    reverseButtons: true
                }).then(
                    async (result) => {
                        if (result.isConfirmed) {
                            const res = await callApi("post", url);
                            if(isSuccess(res.status))
                            {
                                $("#logMigrateShow").append(res.data);
                                $("#logMigrateNoData").empty();
                            }else{
                                noti(res.status);
                            }
                        }
                })
                
            }';

        echo 'function migrateAll(url){

                Swal.fire({
                    title: "Are you sure?",
                    text: "This function will migrate all table !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Migrate Now !",
                    cancelButtonText: "Discard",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-outline-danger"
                    },
                    reverseButtons: true
                }).then(
                    async (result) => {
                        if (result.isConfirmed) {
                            const res = await callApi("post", url);
                            if(isSuccess(res.status))
                            {
                                $("#logMigrateShow").append(res.data);
                                $("#logMigrateNoData").empty();
                            }else{
                                noti(res.status);
                            }
                        }
                }) 

              }';

        echo 'function clearLog(){
                $("#logMigrateNoData").empty();
                $("#logMigrateNoData").append("No file running");
                $("#logMigrateShow").empty();
              }';
        echo '</script>';
    }
}
