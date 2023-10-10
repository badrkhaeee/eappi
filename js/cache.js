$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
    if (options.cache) {
        var success = originalOptions.success || $.noop,
            url = originalOptions.url;
    
        options.cache = false; //remove jQuery cache as we have our own localStorage
        options.beforeSend = function () {
            if (JSON.parse(localStorage.getItem(url))) {
                success(JSON.parse(localStorage.getItem(url)));
                return false;
            }
            return true;
        };
        options.success = function (data, textStatus) {
            var responseData = JSON.stringify(data);
            localStorage.setItem(url, responseData);
            if ($.isFunction(success)) success(data); //call back to original ajax call
        };
    }
});