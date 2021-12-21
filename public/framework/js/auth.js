$(document).ready(function() {
    googleLogin();
    localStorage.clear();
});

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

function googleLogin() {
    setTimeout(function() {
        var auth2;

        gapi.load('auth2', function() {
            var gapiConfig = {
                client_id: '66325050228-7gvlg0558n58hm1ioqfdpqbb5366tbei.apps.googleusercontent.com',
                cookiepolicy: 'single_host_origin',
                fetch_basic_profile: true,
                redirect_uri: 'https://michelia.schoolscan.xyz/auth',
            }

            // Retrieve the singleton for the GoogleAuth library and set up the client.
            auth2 = gapi.auth2.init(gapiConfig)
                .then(
                    //oninit
                    function(GoogleAuth) {
                        attachSignin(GoogleAuth, document.getElementsByClassName('google-signin')[0]);
                    },
                    //onerror
                    function(error) {
                        console.log(error);
                        toastr.error("Not a valid origin for the client");
                    }
                );
            // attachSignin(document.getElementById('google-signin'));
        });
    }, 0);
}

function attachSignin(GoogleAuth, element) {
    GoogleAuth.attachClickHandler(element, {},
        function(googleUser) {
            var profile = googleUser.getBasicProfile();
            var google_id_token = googleUser.getAuthResponse().id_token;
            console.log(googleUser.getBasicProfile());
            $('#google_id').val(profile.getEmail());
            $('#loginBtn').click();
            loginGoogle(profile.getEmail());
        },
        function(error) {
            console.log(error);
            toastr.error(error);
        });
}

function loginGoogle(googleEmail) {
    $.ajax({
        url: "{{ url('auth/socialite') }}",
        type: 'POST',
        data: {
            'email': googleEmail,
            '_token': "{{ $token }}"
        },
        dataType: "JSON",
        success: function(data) {
            if (data.resCode == 200) {
                setTimeout(function() {
                    window.location.href = data.redirectUrl;
                }, 10);
            } else {
                toastr.error(data.message);
                $("#username").addClass("is-invalid");
                $("#password").addClass("is-invalid");
                $("#password").val("");
            }
        }
    });
}