/* Controller */

var control = function(settings) {

    var sendRequest = function(path, callback, method) {
        console.log('send', path, method);
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

        httpRequest.open(method, path);
        httpRequest.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        httpRequest.send();
    };

    sendRequest(
        settings.url,
        function(data) {
            renderTo(data, settings.tplFile);
        },
        settings.method || 'GET'
    );

};

/* View */

var njEnv = nunjucks.env;

var renderTo = function(data, tplFile) {
    console.log('render', data, tplFile);

    if (data.title) {
        document.title = data.title;
        document.querySelector('.subheader').innerHTML = data.title;
    }

    if (data.msg) {
        var msgCont = document.querySelector('.msg'),
            alertClass = data.result ? 'alert-success' : 'alert-error';
        msgCont.innerHTML = '<span class="' + alertClass + '">' + data.msg + '</span>';
    }

    if (tplFile) {
        var html = njEnv.render(tplFile, data),
            main = document.querySelector('main');
        main.innerHTML = html;
    }
};


/* Router */

routie({

    'login': function() {
        control({
            url: './?action=user_login',
            tplFile: 'login.nj.html'
        });
    },
    'logout': function() {
        control({
            url: './?action=user_logout',
            method: 'POST'
        });
    },
    'user/new': function() {
        control({
            url: './?action=user_new',
            tplFile: 'userCreate.nj.html'
        });
    },
    'user/:id': function(id) {
        control({
            url: './?action=user_edit&id='+parseInt(id),
            tplFile: 'edit.nj.html'
        });
    },

    'new': function() {
        control({
            url: './?action=entry_new',
            tplFile: 'edit.nj.html'
        });
    },

    'msg/:id': function(id) {
        control({
            url: './?action=entry_edit&id='+parseInt(id),
            tplFile: 'edit.nj.html'
        });
    },

    'msg/:id/delete': function(id) {
        if (confirm('Do you really want to delete this message?')) {
            control({
                url: './?action=entry_delete&id='+parseInt(id),
                method: 'POST'
            });
            routie('');
        }
    },

    '': function() {
        console.log('default');
        control({
            url: './?action=entries',
            tplFile: 'entryList.nj.html'
        });
    }

});
