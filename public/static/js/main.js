(function() {


    /* Controller */

    var control = function(settings) {
        console.log('send', settings);

        msg.show('Loading data...');

        microAjax(
            settings.url,
            settings.method || 'GET',
            function(data) {
                if (data.result && data.redirect && settings.redirect) {
                    console.log('----->client redirect', settings.redirect);
                    document.location.href = settings.redirect;
                } else {
                    if (settings.title) data.title = settings.title;
                    renderTo(data, settings.tplFile);
                    msg.hide();
                }
            },
            settings.data
        );

    };


    /* View */

    var njEnv = nunjucks.env;

    var msg = {
        getCont: function() {
            return document.querySelector('.msg');
        },
        hide: function() {
            var cont = this.getCont();
            setTimeout(function() {
                cont.innerHTML = '';
            }, 1000);
        },
        show: function(msg, result) {
            console.log('msg', this );
            if (msg) {
                var cont = this.getCont();
                if (cont) {
                    var alertClass = '';
                    if (result) {
                        alertClass = 'alert-success';
                    } else if (result === false) {
                        alertClass = 'alert-error';
                    } else {
                        alertClass = 'alert-info';
                    }
                    cont.innerHTML = '<span class="alert ' + alertClass + '">' + msg + '</span>';
                }
            } else {
                this.hide();
            }
        }
    };

    var renderTo = function(data, tplFile) {
        console.log('client render', data, tplFile);

        if (data.title) {
            document.title = data.title;
            document.querySelector('.subheader').innerHTML = data.title;
        }

        msg.show(data.msg, data.result);

        if (tplFile) {
            var html = njEnv.render(tplFile, data),
                main = document.querySelector('main');
            main.innerHTML = html;

            var form = document.querySelector('main form');
            if (form) {
                form.onsubmit = function(e) {
                    e.preventDefault();
                    control({
                        url: this.action,
                        method: this.method,
                        tplFile: tplFile,
                        data: serialize(this)
                    });
                    console.log('onsumbit', this);
                };
            }
        }
    };


    /* Helpers */

    var serialize = function(form) {
        if (!form || form.nodeName !== "FORM") {
            return;
        }
        var i, j, q = [];
        for (i = form.elements.length - 1; i >= 0; i = i - 1) {
            if (form.elements[i].name === "") {
                continue;
            }
            switch (form.elements[i].nodeName) {
                case 'INPUT':
                    switch (form.elements[i].type) {
                        case 'text':
                        case 'hidden':
                        case 'password':
                        case 'button':
                        case 'reset':
                        case 'submit':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                        case 'checkbox':
                        case 'radio':
                            if (form.elements[i].checked) {
                                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            }
                            break;
                    }
                    break;
                case 'file':
                    break;
                case 'TEXTAREA':
                    q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                    break;
                case 'SELECT':
                    switch (form.elements[i].type) {
                        case 'select-one':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                        case 'select-multiple':
                            for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                                if (form.elements[i].options[j].selected) {
                                    q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
                                }
                            }
                            break;
                    }
                    break;
                case 'BUTTON':
                    switch (form.elements[i].type) {
                        case 'reset':
                        case 'submit':
                        case 'button':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                    }
                    break;
            }
        }
        return q.join("&");
    };

    var microAjax = function(url, method, callbackFunction, data) {
        this.bindFunction = function(caller, object) {
            return function() {
                return caller.apply(object, [object]);
            };
        };

        this.stateChange = function() {
            if (this.request.readyState == 4)
                this.callbackFunction(JSON.parse(this.request.responseText));
        };

        this.getRequest = function() {
            if (window.ActiveXObject)
                return new ActiveXObject('Microsoft.XMLHTTP');
            else if (window.XMLHttpRequest)
                return new XMLHttpRequest();
            return false;
        };

        this.postBody = (data || "");

        this.callbackFunction = callbackFunction;
        this.url = url;
        this.request = this.getRequest();

        if (this.request) {
            var req = this.request;
            req.onreadystatechange = this.bindFunction(this.stateChange, this);

            if (method == "POST") {
                req.open("POST", url, true);
                req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    //            req.setRequestHeader('Connection', 'close');
            } else {
                req.open("GET", url, true);
            }
            req.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            req.send(this.postBody);
        }
    };


    /* Router */

    routie({

        'login': function() {
            control({
                url: './?action=user_login',
                tplFile: 'login.nj.html',
                redirect: '/'
            });
        },
        'logout': function() {
            control({
                url: './?action=user_logout',
                method: 'POST',
                redirect: '/'
            });
        },
        'reg': function() {
            control({
                url: './?action=user_new',
                tplFile: 'userCreate.nj.html',
                redirect: '/'
            });
        },
        'user/:id': function(id) {
            control({
                url: './?action=user_edit&id='+parseInt(id),
                tplFile: 'edit.nj.html',
                redirect: '#'
            });
        },

        'new': function() {
            control({
                url: './?action=entry_new',
                tplFile: 'edit.nj.html',
                title: 'New message',
                redirect: '#'
            });
        },

        'msg/:id': function(id) {
            control({
                url: './?action=entry_edit&id='+parseInt(id),
                tplFile: 'edit.nj.html',
                redirect: '#'
            });
        },

        'msg/:id/ok': function(id) {
            control({
                url: './?action=entry_approve&id='+parseInt(id),
                method: 'POST',
                redirect: '#'
            });
        },

        'msg/:id/off': function(id) {
            //if (confirm('Do you really want to delete this message?')) {
                control({
                    url: './?action=entry_delete&id='+parseInt(id),
                    method: 'POST',
                    redirect: '#'
                });
//                routie('');
            //}
        },

        '': function() {
            console.log('default');
            control({
                url: './?action=entries',
                tplFile: 'entryList.nj.html'
            });
        }

    });

}());