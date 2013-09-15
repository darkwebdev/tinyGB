function fetchJSONFile(path, callback) {
    var httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState === 4) {
            if (httpRequest.status === 200) {
                var data = JSON.parse(httpRequest.responseText);
                if (callback) callback(data);
            }
        }
    };
    httpRequest.onerror = function() {
        console.log('Ajax error');
    };
    httpRequest.open('GET', path);
    httpRequest.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    httpRequest.send();
}

// this requires the file and executes a callback with the parsed result once
//   it is available
fetchJSONFile('./?action=entries', function(data){
    // do something with your data
    console.log(data);
    if (data.html_title) document.title = data.html_title;
});

var njEnv = nunjucks.env;
