@extends('app.templates.blade')

@section('content')

<!--begin::Row-->
<div class="card mt-5 mb-5">
    <div class="card-header">
        <h1> Server Side Datatable Example </h1>
    </div>
    <div class="card-body">
        <div class="container-fluid mt-5 mb-5">
            <button type="button" class="btn btn-primary btn-sm float-end ms-2" onclick="formModal()">
                <i class="fa fa-user"></i> Add User
            </button>

            <button type="button" class="btn btn-warning  btn-sm float-end" onclick="getDataList()" title="reload">
                <i class="fa fa-refresh"></i>
            </button>

            <a href="{{ url('user/list') }}" class="btn btn-outline-danger btn-sm mr-2" title="Go to page Client Side">
                <i class="fa fa-table" aria-hidden="true"></i> Client Side Datatable
            </a>

        </div>

        <div class="container-fluid mt-5 mb-10">
            <!-- This div are required -->
            <div id="nodatadiv" style="display: none;"> {{ nodata() }} </div>

            <!-- id for div datatable must be save as id table -->
            <div id="dataListDiv" style="display: none;">
                <table id="dataList" class="table table-bordered table-striped table-hover" width="100%" style="margin-top: 15px">
                    <thead class="table-dark">
                        <tr>
                            <th> Code </th>
                            <th> Name </th>
                            <th> Gender </th>
                            <th> Email </th>
                            <th width="2%"> Action </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--end::Row-->

<script type="text/javascript">
    $(document).ready(function() {
        getDataList();
    });

    // server side datatable
    async function getDataList() {

        // example to use with declaration
        const tableID = 'dataList';
        const typeDatatable = 'serverside'; // client or serverside (without -)
        const urlToGetData = 'user/getAllServerSide'; // only for server-side 
        const idNoDataToDisplay = 'nodatadiv'; // only for server-side 
        generateDatatable(tableID, typeDatatable, urlToGetData, idNoDataToDisplay);

        // or use inline like this without variable declaration (recommend)
        // generateDatatable('dataList', 'serverside', 'user/getAllServerSide', 'nodatadiv');
    }

    async function viewRecord(id) {

        // get specific data using axios
        // 1st param = post or get (method to sent)
        // 2nd param = url to function in controller
        // 3rd id, or any unique key 
        const res = await callApi('post', "user/getUsersByID", id);

        const modalTitle = 'User Detail';
        const fileToLoad = 'user/_view.php';
        const modalBodyID = null; // set null if using offcanvas
        const modalSize = null; // sm / md/ lg / xl / xs (according to template bootstrap version) - set null if use offcanvas
        const modalType = 'offcanvas'; // modal or offcanvas (bootstrap 5 only)
        loadFileContent(fileToLoad, modalBodyID, modalSize, modalTitle, res.data, modalType);

        // or use inline like this without variable declaration (recommend)
        // loadFileContent('user/_view.php', null, null, 'User Detail', res.data, 'offcanvas');
    }

    async function updateRecord(id) {
        // get specific data using axios
        // 1st param = post or get (method to sent)
        // 2nd param = url to function in controller
        // 3rd id, or any unique key 
        const res = await callApi('post', "user/getUsersByID", id);

        // 1st param = type form (create or update)
        // 2nd param = data to update (result form axios call)
        formModal('update', res.data);
    }

    async function deleteRecord(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Discard',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-danger'
            },
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // 1st param = id to delete
                // 2nd param = url to function delete in controller
                // 3rd functionName to reload (if needed) if want to reload. example : getDataList() <-- remove () in param  
                deleteApi(id, "user/delete", getDataList);
            }
        })
    }

    function formModal(type = 'create', data = null) {

        // example to use with declaration
        const modalTitle = (type == 'create') ? 'Register User' : 'Update User';
        const urlForm = (type == 'create') ? 'user/create' : 'user/update';
        const fileToLoad = 'user/_form.php';
        const modalBodyID = null; // set null if using offcanvas
        const modalSize = null; // sm / md/ lg / xl / xs (according to template bootstrap version) - set null if use offcanvas
        const modalType = 'offcanvas'; // modal or offcanvas (bootstrap 5 only)
        loadFormContent(fileToLoad, modalBodyID, modalSize, urlForm, modalTitle, data, modalType);

        // or use inline like this without variable declaration (recommend)
        // loadFormContent('user/_form.php', 'generalContent', 'lg', urlForm, modalTitle, data, 'offcanvas');

    }
</script>

@endsection

<!-- 

 SIMPLE DOCUMENTATION FOR VIEW

 FUNCTION AVAILABLE (USE ONLY IN SCRIPT)
    1) loadFormContent(fileToLoad, modalBodyID, modalSize, urlForm, modalTitle, data, modalType)
    2) loadFileContent(fileToLoad, modalBodyID, modalSize, modalTitle, data, modalType)
    3) callApi(methodToPost, url, id)
    4) submitApi(url, dataFromFormToSubmit, formID, functionNameToReload)
    5) deleteApi(id, url, functionNameToReload)
    6) noti(codeResponse, textToDisplay)
    7) isset(value)

 Notes : 
 - for more global function please go to folder :-
    1) public/framework/js/common.js 
    2) public/framework/php/general.php 

 Reminder :
 - Please avoid redeclare same function name in both file above
       
 -->