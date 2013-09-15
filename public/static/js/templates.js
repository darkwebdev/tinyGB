(function() {
var templates = {};
templates["edit.nj.html"] = (function() {
function root(env, context, frame, runtime) {
var lineno = null;
var colno = null;
var output = "";
try {
output += "<form method=\"POST\">\n    <ul>\n        ";
frame = frame.push();
var t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "form")),"fields", env.autoesc);
if(t_2) {for(var t_1=0; t_1 < t_2.length; t_1++) {
var t_3 = t_2[t_1];
frame.set("field", t_3);
output += "\n            ";
if(runtime.memberLookup((t_3),"editable", env.autoesc) || runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "user")),"is_admin", env.autoesc)) {
output += "\n                <li>\n\n                    <span class=\"label\">\n                        ";
output += runtime.suppressValue(runtime.memberLookup((t_3),"label", env.autoesc), env.autoesc);
output += ":\n                    </span>\n\n                    <span class=\"input\">\n                        ";
if(runtime.memberLookup((t_3),"type", env.autoesc) == "textarea") {
output += "\n                            <textarea name=\"";
output += runtime.suppressValue(runtime.memberLookup((t_3),"name", env.autoesc), env.autoesc);
output += "\">\n                                ";
output += runtime.suppressValue(runtime.memberLookup((t_3),"value", env.autoesc), env.autoesc);
output += "\n                            </textarea>\n                        ";
}
else {
output += "\n                            <input\n                                type=\"";
output += runtime.suppressValue(runtime.memberLookup((t_3),"type", env.autoesc), env.autoesc);
output += "\"\n                                name=\"";
output += runtime.suppressValue(runtime.memberLookup((t_3),"name", env.autoesc), env.autoesc);
output += "\"\n                                ";
if(runtime.memberLookup((t_3),"type", env.autoesc) == "checkbox") {
output += "\n                                    ";
if(runtime.memberLookup((t_3),"value", env.autoesc)) {
output += "checked";
}
output += "\n                                ";
}
else {
output += "\n                                    value=\"";
output += runtime.suppressValue(runtime.memberLookup((t_3),"value", env.autoesc), env.autoesc);
output += "\"\n                                ";
}
output += "\n                            >\n                        ";
}
output += "\n                    </span>\n\n                </li>\n            ";
}
output += "\n        ";
}
}frame = frame.pop();
output += "\n\n        <li>\n            <input type=\"submit\" value=\"Save\">\n        </li>\n    </ul>\n</form>";
return output;
} catch (e) {
  runtime.handleError(e, lineno, colno);
}
}
return {
root: root
};

})();
templates["entryList.nj.html"] = (function() {
function root(env, context, frame, runtime) {
var lineno = null;
var colno = null;
var output = "";
try {
output += "<ul class=\"entries\">\n    ";
frame = frame.push();
var t_2 = runtime.contextOrFrameLookup(context, frame, "entry_list");
if(t_2) {for(var t_1=0; t_1 < t_2.length; t_1++) {
var t_3 = t_2[t_1];
frame.set("entry", t_3);
output += "\n        ";
if(runtime.memberLookup((t_3),"is_active", env.autoesc) || runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "user")),"is_admin", env.autoesc)) {
output += "\n            <li>\n                <article>\n                    <header>\n                        <div class=\"author\">";
output += runtime.suppressValue(runtime.memberLookup((t_3),"author", env.autoesc), env.autoesc);
output += "</div>\n                        <div class=\"title\">";
output += runtime.suppressValue(runtime.memberLookup((t_3),"name", env.autoesc), env.autoesc);
output += "</div>\n                        <div class=\"time\">";
output += runtime.suppressValue(runtime.memberLookup((t_3),"created", env.autoesc), env.autoesc);
output += "</div>\n                    </header>\n\n                    <section>\n                        ";
output += runtime.suppressValue(runtime.memberLookup((t_3),"text", env.autoesc), env.autoesc);
output += "\n                    </section>\n\n                    ";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "user")),"is_admin", env.autoesc)) {
output += "\n                        <footer>\n                            <ul>\n\n                                <li>\n                                    ";
if(runtime.memberLookup((t_3),"is_active", env.autoesc) == 0) {
output += "\n                                        <div class=\"notice\">HIDDEN</div>\n                                    ";
}
output += "\n                                    <a href=\"#msg/";
output += runtime.suppressValue(runtime.memberLookup((t_3),"id", env.autoesc), env.autoesc);
output += "\">edit</a>\n                                </li>\n\n                                <li>\n                                    <a href=\"#msg/";
output += runtime.suppressValue(runtime.memberLookup((t_3),"id", env.autoesc), env.autoesc);
output += "/delete\">delete</a>\n                                </li>\n\n                            </ul>\n                        </footer>\n                    ";
}
output += "\n\n                </article>\n            </li>\n        ";
}
output += "\n    ";
}
}frame = frame.pop();
output += "\n</ul>\n";
return output;
} catch (e) {
  runtime.handleError(e, lineno, colno);
}
}
return {
root: root
};

})();
if(typeof define === "function" && define.amd) {
    define(["nunjucks"], function(nunjucks) {
        nunjucks.env = new nunjucks.Environment([], null);
        nunjucks.env.registerPrecompiled(templates);
        return nunjucks;
    });
}
else if(typeof nunjucks === "object") {
    nunjucks.env = new nunjucks.Environment([], null);
    nunjucks.env.registerPrecompiled(templates);
}
else {
    console.error("ERROR: You must load nunjucks before the precompiled templates");
}
})();
