<?php

class Seed
{
    public function index($param = NULL)
    {
        if (filter_var($_ENV['MIGRATION_ENABLE'], FILTER_VALIDATE_BOOLEAN) === TRUE) {
            $this->listFileSeed();
        } else {
            error('404'); // redirect to page error 404
        }
    }

    // Seed specific file
    public function file($fileNames = NULL)
    {
        // echo "===== Seeders start ===== <br><br>";
        $filename = "../database/seeders/$fileNames.php";
        if (file_exists($filename)) {
            require_once(str_replace('\\', '/', $filename));
            $classes = get_declared_classes();
            $class = end($classes);

            $obj = new $class; // create new object

            // check if function up is exist
            if (method_exists($obj, 'run')) {
                $obj->run();
            }
        } else {
            echo "The file <b style='color:red'><i>$filename</i></b> does not exist <br>";
        }
        // echo "<br> ===== Seeders ended =====";
    }

    // Seed all
    public function all()
    {
        // echo "===== Seeders start ===== <br><br>";
        foreach (glob('../database/seeders/*.php') as $filename) {
            if (file_exists($filename)) {
                require_once(str_replace('\\', '/', $filename));
                $classes = get_declared_classes();
                $class = end($classes);

                $obj = new $class; // create new object

                // check if function up is exist
                if (method_exists($obj, 'run')) {
                    $obj->run();
                }
            } else {
                echo "The file <b style='color:red'><i>$filename</i></b> does not exist <br>";
            }
        }
        // echo "<br> ===== Seeders ended =====";
        die;
    }

    public function listFileSeed()
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

        $url = 'seed/all';
        $urlMigrate = url('migrate');

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
                <h1> Seeder Files 
                <a href="' . $urlMigrate . '" class="btn btn-sm btn-outline-danger float-end"> Go to Migration <i class="fa fa-arrow-right"></i> </a>
                <a href="javascript:void(0)" onclick="seedAll(\'' . $url . '\')"  class="btn btn-sm btn-outline-info float-end"> <i class="fa fa-plus"> </i> Seed All Files </a>
                </h1>
              </div>';

        echo '<div>
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <td width="3%">
                                No
                            </td>
                            <td width="55%">
                                File Name
                            </td>
                            <td width="25%">
                                Total Data
                            </td>
                            <td width="15%">
                                Action
                            </td>
                        </tr>
                    </thead>
                    <tbody>';

        $no = 1;
        foreach (glob('../database/seeders/*.php') as $file) {

            if (file_exists($file)) {

                require_once(str_replace('\\', '/', $file));
                $classes = get_declared_classes();
                $class = end($classes);

                $obj = new $class; // create new object

                // check if function up is exist
                $countTotalData = (method_exists($obj, '_dataSeed')) ? count($obj->_dataSeed()) : 0;

                $filename = explode('/', $file);
                $urlFiles = 'seed/file/' . pathinfo(end($filename), PATHINFO_FILENAME);
                echo '<tr>';
                echo '<td><center> ' . $no++ . '</center></td>';
                echo '<td>' . pathinfo(end($filename), PATHINFO_FILENAME) . '</td>';
                echo '<td>' . $countTotalData . '</td>';
                echo '<td>
                        <center> 
                            <a href="javascript:void(0)" onclick="seedTable(\'' . $urlFiles . '\')"  class="btn btn-sm btn-primary"> <i class="fa fa-plus"> </i> Seed </a>
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
        echo '</div>'; // end col-7

        echo '<div class="col-5">'; // start of col-5

        echo '<div class="card mt-5 mb-5">';
        echo '<div class="card-body">';
        echo '<div class="card-header">
                <h1> Log Seeder 
                <a href="javascript:void(0)" onclick="clearLog()"  class="btn btn-sm btn-warning"> <i class="fa fa-refresh"> </i> Clear </a>
                </h1>
              </div>';

        echo '<div class="mt-2" id="logSeedNoData"> No file running </div>';
        echo '<div class="mt-2" id="logSeedShow"> </div>';

        echo '</div>'; // end of card body
        echo '</div>'; // end of fard
        echo '</div>'; // end of col-5

        echo '</div>'; // end row
        echo '</div>'; // end container

        echo '<script>';
        echo 'function seedTable(url){

                var filename = url.substring(url.lastIndexOf("/") + 1);

                Swal.fire({
                    title: "Are you sure?",
                    text: "This table will be seeding !",
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
                                $("#logSeedShow").append(res.data);
                                $("#logSeedNoData").empty();
                            }else{
                                noti(res.status);
                            }
                        }
                }) 

              }';


        echo 'function seedAll(url){

                Swal.fire({
                    title: "Are you sure?",
                    text: "This function will seed all files !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Seed Now !",
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
                                $("#logSeedShow").append(res.data);
                                $("#logSeedNoData").empty();
                            }else{
                                noti(res.status);
                            }
                        }
                }) 

              }';

        echo 'function clearLog(){
                $("#logSeedNoData").empty();
                $("#logSeedNoData").append("No file running");
                $("#logSeedShow").empty();
              }';
        echo '</script>';
    }
}
