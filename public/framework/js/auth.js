$("#login").submit(function(event) {
    event.preventDefault();
    var username = $('#username').val();
    var password = $('#password').val();

    $('#usernameErr').hide();
    $('#passwordErr').hide();
    $('#alertMessage').hide();

    $("#username").removeClass("is-invalid");
    $("#password").removeClass("is-invalid");
    $("#username").removeClass("is-valid");
    $("#password").removeClass("is-valid");

    if (username != '' && password != '') {
        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            dataType: "JSON",
            beforeSend: function() {
                $("#loginBtn").html('Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>');
                $("#loginBtn").attr('disabled', true);
            },
            success: function(data) {
                if (data.resCode == 200) {
                    toastr.success(data.message);
                    setTimeout(function() {
                        window.location.href = data.redirectUrl;
                    }, 10);

                } else if (data.resCode == 'token') {
                    toastr.error(data.message);
                    $("#username").addClass("is-invalid");
                    $("#password").addClass("is-invalid");
                    $("#password").val("");
                } else {
                    toastr.error(data.message);
                    $("#username").addClass("is-invalid");
                    $("#password").addClass("is-invalid");
                    $("#password").val("");
                }
            },
            complete: function() {
                $("#loginBtn").html('<span class="indicator-label"> Log In </span> <span class="spinner-border spinner-border-sm align-middle ms-2"></span>');
                $("#loginBtn").attr('disabled', false);
            }
        });
    } else {
        if (username == '' && password == '') {
            $('#usernameErr').show();
            $('#passwordErr').show();
            $("#username").addClass("is-invalid");
            $("#password").addClass("is-invalid");
        } else if (password != '') {
            $('#usernameErr').show();
            $("#username").addClass("is-invalid");
        } else if (username != '') {
            $('#passwordErr').show();
            $("#password").addClass("is-invalid");
        }
    }
});
