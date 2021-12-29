<div class="container">
    <div class="row mt-2">
        <div class="col-lg-6">
            <label style="color : #b3b3cc">Code</label><br>
            <span id="user_code" style="font-weight:bold"></span>
        </div>
        <div class="col-lg-6">
            <label style="color : #b3b3cc">Full Name</label><br>
            <span id="user_full_name" style="font-weight:bold"></span>
        </div>
    </div>

    <div class="row mt-4 mb-4">
        <div class="col-lg-6">
            <label style="color : #b3b3cc">Preferred Name</label><br>
            <span id="user_preferred_name" style="font-weight:bold"></span>
        </div>
        <div class="col-lg-6">
            <label style="color : #b3b3cc">Email</label><br>
            <span id="user_email" style="font-weight:bold"></span>
        </div>
    </div>
</div>

<script>
    // get data array from general function
    function getPassData(baseUrl, token, data) {
        $('#user_code').text(data.user_code);
        $('#user_full_name').text(data.user_full_name);
        $('#user_preferred_name').text(data.user_preferred_name);
        $('#user_email').text(data.user_email);
    }
</script>