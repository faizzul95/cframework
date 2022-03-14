// set default code
var successStatus = [200, 201];
var unauthorizedStatus = [401, 403];
var errorStatus = [500, 422];

async function submitApi(url, dataObj, formID = null, reloadFunction = null) {
    const submitBtnText = $('#submitBtn').html();
    loadingBtn('submitBtn', true, submitBtnText);

    if (dataObj != null) {
        url = $('meta[name="base_url"]').attr('content') + url;

        const dataArr = new URLSearchParams();
        $.each(dataObj, function (i, field) {
            dataArr.append(field.name, field.value);
        });

        // dataArr.append('_token', $('meta[name="csrf-token"]').attr('content'));

        try {
            return axios({
                    method: 'POST',
                    headers: {
                        "Authorization": `Bearer ${$('meta[name="csrf-token"]').attr('content')}`,
                        'X-Requested-With': 'XMLHttpRequest',
                        'content-type': 'application/x-www-form-urlencoded'
                    },
                    url: url,
                    data: dataArr
                })
                .then(result => {
                    loadingBtn('submitBtn', false, submitBtnText);

                    if (isSuccess(result.status) && reloadFunction != null) {
                        reloadFunction();
                    }

                    if (formID != null) {
                        var modalID = $('#' + formID).attr('data-modal');
                        setTimeout(function () {

                            if (modalID == '#generaloffcanvas-right') {
                                $(modalID).offcanvas('toggle');
                            } else {
                                $(modalID).modal('hide');
                            }

                        }, 200);
                    }

                    noti(result.status);
                    return result;
                })
                .catch(error => {
                    if (isError(error.response.status)) {
                        noti(error.response.status);
                    } else if (isUnauthorized(error.response.status)) {
                        noti(error.response.status, "Unauthorized: Access is denied");
                        // console.log(error.response.status);
                    }
                    loadingBtn('submitBtn', false);
                    throw error;
                });
        } catch (e) {
            const res = e.response;
            loadingBtn('submitBtn', false);

            if (isUnauthorized(res.status)) {
                noti(res.status, "Unauthorized: Access is denied");
            } else {
                if (isError(res.status)) {
                    var error_count = 0;
                    for (var error in res.data.errors) {
                        if (error_count == 0) {
                            noti(400, res.data.errors[error][0]);
                        }
                        error_count++;
                    }
                } else {
                    noti(400);
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
                        'content-type': 'application/x-www-form-urlencoded'
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
                            noti(400, res.data.errors[error][0]);
                        }
                        error_count++;
                    }
                } else {
                    noti(400);
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

    try {
        return axios({
                method: method,
                headers: {
                    "Authorization": `Bearer ${$('meta[name="csrf-token"]').attr('content')}`,
                    'X-Requested-With': 'XMLHttpRequest',
                    'content-type': 'application/x-www-form-urlencoded'
                },
                url: url,
                data: dataSent
            }).then(result => {
                return result;
            })
            .catch(error => {
                if (isError(error.response.status)) {
                    noti(error.response.status);
                } else if (isUnauthorized(error.response.status)) {
                    noti(error.response.status, "Unauthorized: Access is denied");
                    // console.log(error.response.status);
                }
                throw error;
            });
    } catch (e) {
        const res = e.response;
        // console.log(res);
        if (isUnauthorized(res.status)) {
            noti(res.status, "Unauthorized: Access is denied");
        } else {
            if (isError(res.status)) {
                var error_count = 0;
                for (var error in res.data.errors) {
                    if (error_count == 0) {
                        noti(400, res.data.errors[error][0]);
                    }
                    error_count++;
                }
            } else {
                noti(400);
            }
            return res;
        }
    }
}

function noti(code = 200, text = 'Save') {
    toastr.options = {
        preventDuplicates: true,
        timeOut: 4000,
        progressBar: true,
        positionClass: "toast-top-right"
    }

    if (isSuccess(code)) {
        toastr.success(ucfirst(text) + ' successfully');
    } else {
        toastr.error(text == 'save' ? "Ops! Something went wrong!" : text);
        console.log(text);
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