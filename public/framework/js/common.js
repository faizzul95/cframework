// set default code
var successStatus = [200, 201, 302];
var unauthorizedStatus = [401, 403];
var errorStatus = [500, 422];

async function submitApi(url, dataObj, formID = null, reloadFunction = null, closedModal = true) {
    const submitBtnText = $('#submitBtn').html();

    var btnSubmitIDs = $('#' + formID + ' button[type=submit]').attr("id");
    var inputSubmitIDs = $('#' + formID + ' input[type=submit]').attr("id");
    var submitIdBtn = isDef(btnSubmitIDs) ? btnSubmitIDs : isDef(inputSubmitIDs) ? inputSubmitIDs : null;

    loadingBtn(submitIdBtn, true, submitBtnText);

    if (dataObj != null) {
        url = $('meta[name="base_url"]').attr('content') + url;

        // const dataArr = new URLSearchParams();

        // $.each(dataObj, function (i, field) {
        //     required = $('input[name="' + field.name + '"]').attr('required');
        //     // if (isDef(required)) {
        //     //     console.log('field ' + field.name + ' is ' + required);
        //     // }

        //     dataArr.append(field.name, field.value);
        // });

        try {
            var frm = $('#' + formID);
            const dataArr = new FormData(frm[0]);

            return axios({
                    method: 'POST',
                    headers: {
                        "Authorization": `Bearer ${$('meta[name="csrf-token"]').attr('content')}`,
                        'X-Requested-With': 'XMLHttpRequest',
                        'content-type': 'application/x-www-form-urlencoded',
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                    },
                    url: url,
                    data: dataArr
                })
                .then(result => {

                    if (isSuccess(result.status) && reloadFunction != null) {
                        reloadFunction();
                    }

                    if (formID != null) {
                        var modalID = $('#' + formID).attr('data-modal');

                        // console.log(formID);
                        // console.log(modalID);
                        // var modalID = $(".modal").filter(".show").attr('id');
                        // var modalID = $('#' + formID).parents().closest('.modal').attr('id');

                        if (closedModal) {
                            setTimeout(function () {
                                if (modalID == '#generaloffcanvas-right') {
                                    $(modalID).offcanvas('toggle');
                                } else {
                                    // $('#' + modalID).modal('hide');
                                    $(modalID).modal('hide');
                                }

                            }, 200);
                        }
                    }

                    loadingBtn(submitIdBtn, false, submitBtnText);
                    noti(result.status, 'Submit');
                    return result;
                })
                .catch(error => {
                    const res = error.response;
                    console.log('ERROR 1', res);
                    if (isError(res.status)) {
                        for (var error in res.data) {
                            noti(res.status, res.data[error]);
                            // console.log('test error', res.data[error]);
                        }
                    } else if (isUnauthorized(res.status)) {
                        noti(res.status, "Unauthorized: Access is denied");
                    }
                    loadingBtn(submitIdBtn, false);
                    throw error;
                });
        } catch (e) {
            const res = e.response;
            console.log('ERROR 2', res);
            loadingBtn(submitIdBtn, false);

            if (isUnauthorized(res.status)) {
                noti(res.status, "Unauthorized: Access is denied");
            } else {
                if (isError(res.status)) {
                    var error_count = 0;
                    for (var error in res.data.errors) {
                        if (error_count == 0) {
                            noti(res.status, res.data.errors[error][0]);
                        }
                        error_count++;
                    }
                } else {
                    noti(res.status, 'Something went wrong');
                }
                return res;
            }
        }
    } else {
        noti(400, "No data to insert!");
        loadingBtn('submitBtn', false);
    }
}

async function deleteApi(id, url, reloadFunction = null) {
    if (id != '') {
        url = $('meta[name="base_url"]').attr('content') + url;
        try {
            return axios({
                    method: 'POST',
                    headers: {
                        "Authorization": `Bearer ${$('meta[name="csrf-token"]').attr('content')}`,
                        'X-Requested-With': 'XMLHttpRequest',
                        'content-type': 'application/x-www-form-urlencoded',
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                    },
                    url: url,
                    data: new URLSearchParams({
                        id: id,
                        // _token: $('meta[name="csrf-token"]').attr('content')
                    })
                })
                .then(result => {
                    if (isSuccess(result.status) && reloadFunction != null) {
                        reloadFunction();
                    }
                    noti(result.status, 'Remove');
                    return result;
                })
                .catch(error => {
                    if (isError(error.response.status)) {
                        noti(error.response.status);
                    } else if (isUnauthorized(error.response.status)) {
                        noti(error.response.status, "Unauthorized: Access is denied");
                    }
                    throw error;
                });
        } catch (e) {
            const res = e.response;
            if (isUnauthorized(res.status)) {
                noti(res.status, "Unauthorized: Access is denied");
            } else {
                if (isError(res.status)) {
                    var error_count = 0;
                    for (var error in res.data.errors) {
                        if (error_count == 0) {
                            noti(res.status, res.data.errors[error][0]);
                        }
                        error_count++;
                    }
                } else {
                    noti(500, 'Something went wrong');
                }
                return res;
            }
        }
    } else {
        noti(400);
    }
}

async function callApi(method = 'POST', url, dataObj = null) {
    url = $('meta[name="base_url"]').attr('content') + url;

    if (dataObj != null) {
        if (isObject(dataObj) || isArray(dataObj)) {
            dataArr = {}; // {} will create an object
            for (var key in dataObj) {
                if (dataObj.hasOwnProperty(key)) {
                    dataArr[key] = dataObj[key];
                }
            }
            dataSent = new URLSearchParams(dataArr);
        } else {
            dataSent = new URLSearchParams({
                id: dataObj
            });
        }
    } else {
        dataSent = null;
    }

    try {
        return axios({
                method: method,
                headers: {
                    "Authorization": `Bearer ${$('meta[name="csrf-token"]').attr('content')}`,
                    'X-Requested-With': 'XMLHttpRequest',
                    'content-type': 'application/x-www-form-urlencoded',
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                },
                url: url,
                data: dataSent
            }).then(result => {
                return result;
            })
            .catch(error => {
                if (isError(error.response.status)) {
                    noti(error.response.status, 'Something went wrong');
                } else if (isUnauthorized(error.response.status)) {
                    noti(error.response.status, "Unauthorized: Access is denied");
                }
                throw error;
            });
    } catch (e) {
        const res = e.response;
        if (isUnauthorized(res.status)) {
            noti(res.status, "Unauthorized: Access is denied");
        } else {
            if (isError(res.status)) {
                var error_count = 0;
                for (var error in res.data.errors) {
                    if (error_count == 0) {
                        noti(500, res.data.errors[error][0]);
                    }
                    error_count++;
                }
            } else {
                noti(500, 'Something went wrong');
            }
            return res;
        }
    }
}

function noti(code = 200, text = 'Something went wrong', typeToast = 'toast') {
    if (typeToast == 'toast') {
        cuteToast({
            type: (isSuccess(code)) ? 'success' : 'error',
            title: (isSuccess(code)) ? 'Great!' : 'Ops!',
            message: (isSuccess(code)) ? ucfirst(text) + ' successfully' : (isError(code)) ? text : 'Something went wrong',
            timer: 5000,
        });
    } else {
        cuteAlert({
            type: (isSuccess(code)) ? 'success' : 'error',
            title: (isSuccess(code)) ? 'Great!' : 'Ops!',
            message: (isSuccess(code)) ? ucfirst(text) + ' successfully' : (isError(code)) ? text : 'Something went wrong',
            closeStyle: 'circle',
        });
    }
}

function log(inp) {
    if ($('pre').length) {
        $('pre').html(inp);
    } else {
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

function loadingBtn(id, display = false, text = "<i class='fa fa-save'></i> Save") {
    if (display) {
        $("#" + id).html('Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>');
        $("#" + id).attr('disabled', true);
    } else {
        $("#" + id).html(text);
        $("#" + id).attr('disabled', false);
    }
}

function disableBtn(id, display = true, text = null) {
    $("#" + id).attr("disabled", display);
}

function isset(variable_name) {
    if (typeof variable_name !== 'undefined') {
        return true;
    }

    return false;
}

function isSuccess(res) {
    const status = typeof res === 'number' ? res : res.status;
    return this.successStatus.includes(status);
}

function isError(res) {
    const status = typeof res === 'number' ? res : res.status;
    return this.errorStatus.includes(status);
}

function isUnauthorized(res) {
    const status = typeof res === 'number' ? res : res.status;
    return this.unauthorizedStatus.includes(status);
}

// These helpers produce better VM code in JS engines due to their
// explicitness and function inlining.
function isUndef(v) {
    return v === undefined || v === null
}

function isDef(v) {
    return v !== undefined && v !== null
}

function isTrue(v) {
    return v === true
}

function isFalse(v) {
    return v === false
}

/**
 * Check if value is primitive.
 */
function isPrimitive(value) {
    return (
        typeof value === 'string' ||
        typeof value === 'number' ||
        // $flow-disable-line
        typeof value === 'symbol' ||
        typeof value === 'boolean'
    )
}

/**
 * Quick object check - this is primarily used to tell
 * Objects from primitive values when we know the value
 * is a JSON-compliant type.
 */
function isObject(obj) {
    return obj !== null && typeof obj === 'object'
}

/**
 * Get the raw type string of a value, e.g., [object Object].
 */
var _toString = Object.prototype.toString;

function toRawType(value) {
    return _toString.call(value).slice(8, -1)
}

/**
 * Strict object type check. Only returns true
 * for plain JavaScript objects.
 */
function isPlainObject(obj) {
    return _toString.call(obj) === '[object Object]'
}

function isRegExp(v) {
    return _toString.call(v) === '[object RegExp]'
}

/**
 * Check if val is a valid array index.
 */
function isValidArrayIndex(val) {
    var n = parseFloat(String(val));
    return n >= 0 && Math.floor(n) === n && isFinite(val)
}

function isPromise(val) {
    return (
        isDef(val) &&
        typeof val.then === 'function' &&
        typeof val.catch === 'function'
    )
}

function isArray(val) {
    return Array.isArray(val) ? true : false;
}

/**
 * Convert a value to a string that is actually rendered.
 */
function toString(val) {
    return val == null ?
        '' :
        Array.isArray(val) || (isPlainObject(val) && val.toString === _toString) ?
        JSON.stringify(val, null, 2) :
        String(val)
}

/**
 * Convert an input value to a number for persistence.
 * If the conversion fails, return original string.
 */
function toNumber(val) {
    var n = parseFloat(val);
    return isNaN(n) ? val : n
}

/**
 * Remove an item from an array.
 */
function remove(arr, item) {
    if (arr.length) {
        var index = arr.indexOf(item);
        if (index > -1) {
            return arr.splice(index, 1)
        }
    }
}

/**
 * Check whether an object has the property.
 */
var hasOwnProperty = Object.prototype.hasOwnProperty;

function hasOwn(obj, key) {
    return hasOwnProperty.call(obj, key)
}

/**
 * Create a cached version of a pure function.
 */
function cached(fn) {
    var cache = Object.create(null);
    return (function cachedFn(str) {
        var hit = cache[str];
        return hit || (cache[str] = fn(str))
    })
}

/**
 * Convert an Array-like object to a real Array.
 */
function toArray(list, start) {
    start = start || 0;
    var i = list.length - start;
    var ret = new Array(i);
    while (i--) {
        ret[i] = list[i + start];
    }
    return ret
}

/**
 * Merge an Array of Objects into a single Object.
 */
function toObject(arr) {
    var res = {};
    for (var i = 0; i < arr.length; i++) {
        if (arr[i]) {
            extend(res, arr[i]);
        }
    }
    return res
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function capitalize(str) {
    return str.toLowerCase().split(' ').map(function (word) {
        return word.replace(word[0], word[0].toUpperCase());
    }).join(' ');
}