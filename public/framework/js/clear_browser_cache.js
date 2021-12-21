(function () {
    var process_scripts = false;
    var rep = /.*\?.*/,
        links = document.getElementsByTagName('link'),
        scripts = document.getElementsByTagName('script');
    var value = document.getElementsByName('clear-browser-cache');
    for (var i = 0; i < value.length; i++) {
        var val = value[i],
            outerHTML = val.outerHTML;
        var check = /.*value="true".*/;
        if (check.test(outerHTML)) {
            process_scripts = true;
        }
    }
    for (var i = 0; i < links.length; i++) {
        var link = links[i],
            href = link.href;
        if (rep.test(href)) {
            link.href = href + '&' + Date.now();
        } else {
            link.href = href + '?' + Date.now();
        }
    }
    if (process_scripts) {
        for (var i = 0; i < scripts.length; i++) {
            var script = scripts[i],
                src = script.src;
            if (src !== "") {
                if (rep.test(src)) {
                    script.src = src + '&' + Date.now();
                } else {
                    script.src = src + '?' + Date.now();
                }
            }
        }
    }
})();