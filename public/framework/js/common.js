async function submitForm(url, form, formID = null, reloadFunction = null) {
    const submitBtnText = $('#submitBtn').html();
    loadingBtn('submitBtn', true, submitBtnText);
    const res = await submitApi(url, form)
                .then(result => { loadingBtn('submitBtn', false, submitBtnText); return result; })
                .catch(error => { 
                    console.error(error); 
                    toastr.error('Ops! Something went wrong! Please try again.');
                    loadingBtn('submitBtn', false); 
                    throw error; 
                });
    noti(res.status);

    if(res.status == 200 && reloadFunction != null){
        reloadFunction();
    }
    
    if(formID != null)
    {
        var modalID = $('#'+formID).attr('data-modal');
        setTimeout(function() {

            if(modalID == '#generaloffcanvas-right'){
                $(modalID).offcanvas('toggle');
            }else{
                $(modalID).modal('hide');
            }

        }, 200);
    }
}

async function deleteData(id, url, reloadFunction = null) {
    const res = await deleteApi(id, url)
                .then(result => { console.log('remove : ',result); return result; })
                .catch(error => { console.error(error); throw error; });
    noti(res.status, 'remove');

    if(res.status == 200 && reloadFunction != null){
        reloadFunction();
    }
}

async function submitApi(url = null, dataObj = null)
{
    if(dataObj != null)
    {
        url = $('meta[name="base_url"]').attr('content')+url;
        // const dataArr = new FormData();
        const dataArr = new URLSearchParams();
        $.each(dataObj, function(i, field){
            dataArr.append(field.name, field.value);
        });

        dataArr.append('_token', $('meta[name="csrf-token"]').attr('content'));

        try {
            return axios({
                method: 'POST',
                headers: { 
                    'content-type': 'application/x-www-form-urlencoded' 
                },
                url: url,
                data: dataArr
            }); 
        } catch (e) {
            const res = e.response;
            return res;
        }
    }
    else{
        return toastr.error('Ops! Something went wrong! Please try again.');
    }
}

async function deleteApi(id, url)
{
    if(id != '')
    {
        url = $('meta[name="base_url"]').attr('content')+url;

        try {
            const data = new URLSearchParams({ 
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            });
            
            return axios({
                method: 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                url: url,
                data: data
            }); 
    
        } catch (e) {
            const res = e.response;
            return res;
        }

    }else{
        toastr.error('ID is empty');
    }
}

async function callApi(method = 'POST', url, dataObj = null) 
{
    url = $('meta[name="base_url"]').attr('content')+url;

    const data = new URLSearchParams({ 
        data: dataObj,
        _token: $('meta[name="csrf-token"]').attr('content')
    });

    try {
        return axios({
            method: method,
            headers: { 'content-type': 'application/x-www-form-urlencoded' },
            url: url,
            data: data
        }); 
    } catch (e) {
        const res = e.response;
        return res;
    }
}

function noti(code = 200, text = 'Save')
{
    toastr.options = {
        preventDuplicates: true,
        timeOut: 4000,
        progressBar: true,
        positionClass: "toast-top-right"
    }

    if(code == 200 || code == 201) {
        toastr.success(ucfirst(text) + ' successfully');
    } else {
        if(text != 'save'){
            toastr.error(text);
        }else{
            toastr.error("Ops! Something went wrong! Please try again.");
        }
    }
}

function log(inp) {
    if( $('pre').length )  
    {
        $('pre').html(inp);
    }
    else{
        document.body.appendChild(document.createElement('pre')).innerHTML = syntaxHighlight(inp);
    }
}

function syntaxHighlight(json) {
    if (typeof json != 'string') {
         json = JSON.stringify(json, undefined, 2);
    }
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function loadingBtn(id, display = false, text = "<i class='fa fa-save'></i> Save")
{
    if(display){
        $("#"+id).html('Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>');
        $("#"+id).attr('disabled', true);
    }else{
        $("#"+id).html(text);
        $("#"+id).attr('disabled', false);
    } 
}

function isset(variable_name) {
    if (typeof variable_name !== 'undefined') {
        return true;
    }

    return false;
}