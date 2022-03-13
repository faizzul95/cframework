<form id="formUser" action="user/save" method="POST">

    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <div>
            <i class='fa fa-exclamation-triangle'></i>
            <b class="text-danger">*</b> Indicates a required field
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label> Code <span class="text-danger">*</span> </label>
                <input type="text" id="user_code" name="user_code" class="form-control" maxlength="5" autocomplete="off" required>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label> Preferred Name <span class="text-danger">*</span> </label>
                <input type="text" id="user_preferred_name" name="user_preferred_name" class="form-control" maxlength="10" autocomplete="off" required>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="form-group">
                <label> Full Name <span class="text-danger">*</span> </label>
                <input type="text" id="user_full_name" name="user_full_name" class="form-control" maxlength="30" autocomplete="off" required>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label> Email <span class="text-danger">*</span> </label>
                <input type="text" id="user_email" name="user_email" class="form-control" maxlength="25" autocomplete="off" required>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label> Gender <span class="text-danger">*</span> </label>
                <select id="user_gender" name="user_gender" class="form-control" required>
                    <option value=""> - Select -</option>
                    <option value="1"> Male </option>
                    <option value="2"> Female </option>
                </select>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <center>
                <input type="hidden" id="user_id" name="user_id" class="form-control" readonly>
                <!-- button submit must be put id "submitBtn" -->
                <button type="submit" id="submitBtn" class="btn btn-primary"> <i class='fa fa-save'></i> Save </button>
            </center>
        </div>
    </div>
</form>

<script>
    $("#formUser").submit(function(event) {
        event.preventDefault();

        const form = $(this);
        const url = form.attr('action');
        const buttonName = "submitBtn";

        Swal.fire({
            title: 'Are you sure?',
            // text: "Your last data wont be able to view",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Discard',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-danger'
            },
            reverseButtons: true
        }).then(
            async (result) => {
                if (result.isConfirmed) {
                    // 1st param = url to function in controller
                    // 2nd param = form that have been convert and encode to array
                    // 3rd param = id form (required)
                    // 4th param = functionName to reload (if needed) 
                    // if want to reload. example : getDataList() <-- remove () in param  
                    const res = await submitApi(url, form.serializeArray(), 'formUser', getDataList);
                }
            })
    });
</script>

<!-- 

 SIMPLE DOCUMENTATION FOR VIEW

 FUNCTION AVAILABLE (USE ONLY IN SCRIPT)
    1) loadFormContent(fileToLoad, modalBodyID, modalSize, urlForm, modalTitle, data, modalType)
    2) loadFileContent(fileToLoad, modalBodyID, modalSize, modalTitle, res.data, modalType)
    3) callApi(methodToPost, url, id)
    4) submitForm(url, dataFromFormToSubmit, formID, functionNameToReload)
    5) deleteData(id, url, functionNameToReload)
    6) noti(codeResponse, textToDisplay)
    7) isset(value)

 Notes : 
 - for more global function please go to folder :-
    1) public/framework/js/common.js 
    2) public/framework/php/general.php 

 Reminder :
 - Please avoid redeclare same function name in both file above
       
 -->