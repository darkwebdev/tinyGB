
/* Templating */

var njEnv = nunjucks.env;

var doAjax = function(path, callback, method) {
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
    httpRequest.open(method || 'GET', path);
    httpRequest.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    httpRequest.send();
};

var applyJson = function(data) {
    console.log(data);

    if (data.html_title) {
        document.title = data.html_title;
    }

    if (data.msg) {
        document.querySelector('.msg').innerHTML = data.msg;
    }

    var html = njEnv.render('entryList.nj.html', data),
        main = document.querySelector('main');
    main.innerHTML = html;
};

/* Routing */

routie({

    'user/:id': function(id) {
        doAjax('./?action=user_edit&id='+parseInt(id), applyJson, 'POST');
    },

    'new': function() {
        doAjax('./?action=entry_new', applyJson, 'POST');
    },

    'msg/:id': function(id) {
        doAjax('./?action=entry_edit&id='+parseInt(id), applyJson, 'POST');
    },

    'msg/:id/delete': function(id) {
        if (confirm('Do you really want to delete this message?')) {
            doAjax('./?action=entry_delete&id='+parseInt(id), applyJson, 'POST');
            routie('');
        }
    },

    '': function() {
        console.log('default');
        doAjax('./?action=entries', applyJson);
    }

});
