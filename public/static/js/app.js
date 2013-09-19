/*!
 * routie - a tiny hash router
 * v0.3.2
 * http://projects.jga.me/routie
 * copyright Greg Allen 2013
 * MIT License
*/
(function(n){var e=[],t={},r="routie",o=n[r],i=function(n,e){this.name=e,this.path=n,this.keys=[],this.fns=[],this.params={},this.regex=a(this.path,this.keys,!1,!1)};i.prototype.addHandler=function(n){this.fns.push(n)},i.prototype.removeHandler=function(n){for(var e=0,t=this.fns.length;t>e;e++){var r=this.fns[e];if(n==r)return this.fns.splice(e,1),void 0}},i.prototype.run=function(n){for(var e=0,t=this.fns.length;t>e;e++)this.fns[e].apply(this,n)},i.prototype.match=function(n,e){var t=this.regex.exec(n);if(!t)return!1;for(var r=1,o=t.length;o>r;++r){var i=this.keys[r-1],a="string"==typeof t[r]?decodeURIComponent(t[r]):t[r];i&&(this.params[i.name]=a),e.push(a)}return!0},i.prototype.toURL=function(n){var e=this.path;for(var t in n)e=e.replace("/:"+t,"/"+n[t]);if(e=e.replace(/\/:.*\?/g,"/").replace(/\?/g,""),-1!=e.indexOf(":"))throw Error("missing parameters for url: "+e);return e};var a=function(n,e,t,r){return n instanceof RegExp?n:(n instanceof Array&&(n="("+n.join("|")+")"),n=n.concat(r?"":"/?").replace(/\/\(/g,"(?:/").replace(/\+/g,"__plus__").replace(/(\/)?(\.)?:(\w+)(?:(\(.*?\)))?(\?)?/g,function(n,t,r,o,i,a){return e.push({name:o,optional:!!a}),t=t||"",""+(a?"":t)+"(?:"+(a?t:"")+(r||"")+(i||r&&"([^/.]+?)"||"([^/]+?)")+")"+(a||"")}).replace(/([\/.])/g,"\\$1").replace(/__plus__/g,"(.+)").replace(/\*/g,"(.*)"),RegExp("^"+n+"$",t?"":"i"))},s=function(n,r){var o=n.split(" "),a=2==o.length?o[0]:null;n=2==o.length?o[1]:o[0],t[n]||(t[n]=new i(n,a),e.push(t[n])),t[n].addHandler(r)},h=function(n,e){if("function"==typeof e)s(n,e),h.reload();else if("object"==typeof n){for(var t in n)s(t,n[t]);h.reload()}else e===void 0&&h.navigate(n)};h.lookup=function(n,t){for(var r=0,o=e.length;o>r;r++){var i=e[r];if(i.name==n)return i.toURL(t)}},h.remove=function(n,e){var r=t[n];r&&r.removeHandler(e)},h.removeAll=function(){t={},e=[]},h.navigate=function(n,e){e=e||{};var t=e.silent||!1;t&&l(),setTimeout(function(){window.location.hash=n,t&&setTimeout(function(){p()},1)},1)},h.noConflict=function(){return n[r]=o,h};var f=function(){return window.location.hash.substring(1)},c=function(n,e){var t=[];return e.match(n,t)?(e.run(t),!0):!1},u=h.reload=function(){for(var n=f(),t=0,r=e.length;r>t;t++){var o=e[t];if(c(n,o))return}},p=function(){n.addEventListener?n.addEventListener("hashchange",u,!1):n.attachEvent("onhashchange",u)},l=function(){n.removeEventListener?n.removeEventListener("hashchange",u):n.detachEvent("onhashchange",u)};p(),n[r]=h})(window);(function(){var modules={};(function(){function extend(cls,name,props){var prototype=Object.create(cls.prototype);var fnTest=/xyz/.test(function(){xyz})?/\bparent\b/:/.*/;props=props||{};for(var k in props){var src=props[k];var parent=prototype[k];if(typeof parent=="function"&&typeof src=="function"&&fnTest.test(src)){prototype[k]=function(src,parent){return function(){var tmp=this.parent;this.parent=parent;var res=src.apply(this,arguments);this.parent=tmp;return res}}(src,parent)}else{prototype[k]=src}}prototype.typename=name;var new_cls=function(){if(prototype.init){prototype.init.apply(this,arguments)}};new_cls.prototype=prototype;new_cls.prototype.constructor=new_cls;new_cls.extend=function(name,props){if(typeof name=="object"){props=name;name="anonymous"}return extend(new_cls,name,props)};return new_cls}modules["object"]=extend(Object,"Object",{})})();(function(){var ArrayProto=Array.prototype;var ObjProto=Object.prototype;var escapeMap={"&":"&amp;",'"':"&quot;","'":"&#39;","<":"&lt;",">":"&gt;"};var lookupEscape=function(ch){return escapeMap[ch]};var exports=modules["lib"]={};exports.withPrettyErrors=function(path,withInternals,func){try{return func()}catch(e){if(!e.Update){e=new exports.TemplateError(e)}e.Update(path);if(!withInternals){var old=e;e=new Error(old.message);e.name=old.name}throw e}};exports.TemplateError=function(message,lineno,colno){var err=this;if(message instanceof Error){err=message;message=message.name+": "+message.message}else{if(Error.captureStackTrace){Error.captureStackTrace(err)}}err.name="Template render error";err.message=message;err.lineno=lineno;err.colno=colno;err.firstUpdate=true;err.Update=function(path){var message="("+(path||"unknown path")+")";if(this.firstUpdate){if(this.lineno&&this.colno){message+=" [Line "+this.lineno+", Column "+this.colno+"]"}else if(this.lineno){message+=" [Line "+this.lineno+"]"}}message+="\n ";if(this.firstUpdate){message+=" "}this.message=message+(this.message||"");this.firstUpdate=false;return this};return err};exports.TemplateError.prototype=Error.prototype;exports.escape=function(val){return val.replace(/[&"'<>]/g,lookupEscape)};exports.isFunction=function(obj){return ObjProto.toString.call(obj)=="[object Function]"};exports.isArray=Array.isArray||function(obj){return ObjProto.toString.call(obj)=="[object Array]"};exports.isString=function(obj){return ObjProto.toString.call(obj)=="[object String]"};exports.isObject=function(obj){return obj===Object(obj)};exports.groupBy=function(obj,val){var result={};var iterator=exports.isFunction(val)?val:function(obj){return obj[val]};for(var i=0;i<obj.length;i++){var value=obj[i];var key=iterator(value,i);(result[key]||(result[key]=[])).push(value)}return result};exports.toArray=function(obj){return Array.prototype.slice.call(obj)};exports.without=function(array){var result=[];if(!array){return result}var index=-1,length=array.length,contains=exports.toArray(arguments).slice(1);while(++index<length){if(contains.indexOf(array[index])===-1){result.push(array[index])}}return result};exports.extend=function(obj,obj2){for(var k in obj2){obj[k]=obj2[k]}return obj};exports.repeat=function(char_,n){var str="";for(var i=0;i<n;i++){str+=char_}return str};exports.each=function(obj,func,context){if(obj==null){return}if(ArrayProto.each&&obj.each==ArrayProto.each){obj.forEach(func,context)}else if(obj.length===+obj.length){for(var i=0,l=obj.length;i<l;i++){func.call(context,obj[i],i,obj)}}};exports.map=function(obj,func){var results=[];if(obj==null){return results}if(ArrayProto.map&&obj.map===ArrayProto.map){return obj.map(func)}for(var i=0;i<obj.length;i++){results[results.length]=func(obj[i],i)}if(obj.length===+obj.length){results.length=obj.length}return results}})();(function(){var lib=modules["lib"];var Object=modules["object"];var Frame=Object.extend({init:function(parent){this.variables={};this.parent=parent},set:function(name,val){var parts=name.split(".");var obj=this.variables;for(var i=0;i<parts.length-1;i++){var id=parts[i];if(!obj[id]){obj[id]={}}obj=obj[id]}obj[parts[parts.length-1]]=val},get:function(name){var val=this.variables[name];if(val!==undefined&&val!==null){return val}return null},lookup:function(name){var p=this.parent;var val=this.variables[name];if(val!==undefined&&val!==null){return val}return p&&p.lookup(name)},push:function(){return new Frame(this)},pop:function(){return this.parent}});function makeMacro(argNames,kwargNames,func){return function(){var argCount=numArgs(arguments);var args;var kwargs=getKeywordArgs(arguments);if(argCount>argNames.length){args=Array.prototype.slice.call(arguments,0,argNames.length);var vals=Array.prototype.slice.call(arguments,args.length,argCount);for(var i=0;i<vals.length;i++){if(i<kwargNames.length){kwargs[kwargNames[i]]=vals[i]}}args.push(kwargs)}else if(argCount<argNames.length){args=Array.prototype.slice.call(arguments,0,argCount);for(var i=argCount;i<argNames.length;i++){var arg=argNames[i];args.push(kwargs[arg]);delete kwargs[arg]}args.push(kwargs)}else{args=arguments}return func.apply(this,args)}}function makeKeywordArgs(obj){obj.__keywords=true;return obj}function getKeywordArgs(args){var len=args.length;if(len){var lastArg=args[len-1];if(lastArg&&lastArg.hasOwnProperty("__keywords")){return lastArg}}return{}}function numArgs(args){var len=args.length;if(len===0){return 0}var lastArg=args[len-1];if(lastArg&&lastArg.hasOwnProperty("__keywords")){return len-1}else{return len}}function SafeString(val){if(typeof val!="string"){return val}this.toString=function(){return val};this.length=val.length;var methods=["charAt","charCodeAt","concat","contains","endsWith","fromCharCode","indexOf","lastIndexOf","length","localeCompare","match","quote","replace","search","slice","split","startsWith","substr","substring","toLocaleLowerCase","toLocaleUpperCase","toLowerCase","toUpperCase","trim","trimLeft","trimRight"];for(var i=0;i<methods.length;i++){this[methods[i]]=proxyStr(val[methods[i]])}}function copySafeness(dest,target){if(dest instanceof SafeString){return new SafeString(target)}return target.toString()}function proxyStr(func){return function(){var ret=func.apply(this,arguments);if(typeof ret=="string"){return new SafeString(ret)}return ret}}function suppressValue(val,autoescape){val=val!==undefined&&val!==null?val:"";if(autoescape&&typeof val==="string"){val=lib.escape(val)}return val}function memberLookup(obj,val){obj=obj||{};if(typeof obj[val]==="function"){return function(){return obj[val].apply(obj,arguments)}}return obj[val]}function callWrap(obj,name,args){if(!obj){throw new Error("Unable to call `"+name+"`, which is undefined or falsey")}else if(typeof obj!=="function"){throw new Error("Unable to call `"+name+"`, which is not a function")}return obj.apply(this,args)}function contextOrFrameLookup(context,frame,name){var val=context.lookup(name);return val!==undefined&&val!==null?val:frame.lookup(name)}function handleError(error,lineno,colno){if(error.lineno){throw error}else{throw new lib.TemplateError(error,lineno,colno)}}modules["runtime"]={Frame:Frame,makeMacro:makeMacro,makeKeywordArgs:makeKeywordArgs,numArgs:numArgs,suppressValue:suppressValue,memberLookup:memberLookup,contextOrFrameLookup:contextOrFrameLookup,callWrap:callWrap,handleError:handleError,isArray:lib.isArray,SafeString:SafeString,copySafeness:copySafeness}})();(function(){var lib=modules["lib"];var r=modules["runtime"];var filters={abs:function(n){return Math.abs(n)},batch:function(arr,linecount,fill_with){var res=[];var tmp=[];for(var i=0;i<arr.length;i++){if(i%linecount===0&&tmp.length){res.push(tmp);tmp=[]}tmp.push(arr[i])}if(tmp.length){if(fill_with){for(var i=tmp.length;i<linecount;i++){tmp.push(fill_with)}}res.push(tmp)}return res},capitalize:function(str){var ret=str.toLowerCase();return r.copySafeness(str,ret[0].toUpperCase()+ret.slice(1))},center:function(str,width){width=width||80;if(str.length>=width){return str}var spaces=width-str.length;var pre=lib.repeat(" ",spaces/2-spaces%2);var post=lib.repeat(" ",spaces/2);return r.copySafeness(str,pre+str+post)},"default":function(val,def){return val?val:def},dictsort:function(val,case_sensitive,by){if(!lib.isObject(val)){throw new lib.TemplateError("dictsort filter: val must be an object")}var array=[];for(var k in val){array.push([k,val[k]])}var si;if(by===undefined||by==="key"){si=0}else if(by==="value"){si=1}else{throw new lib.TemplateError("dictsort filter: You can only sort by either key or value")}array.sort(function(t1,t2){var a=t1[si];var b=t2[si];if(!case_sensitive){if(lib.isString(a)){a=a.toUpperCase()}if(lib.isString(b)){b=b.toUpperCase()}}return a>b?1:a==b?0:-1});return array},escape:function(str){if(typeof str=="string"||str instanceof r.SafeString){return lib.escape(str)}return str},safe:function(str){return new r.SafeString(str)},first:function(arr){return arr[0]},groupby:function(arr,attr){return lib.groupBy(arr,attr)},indent:function(str,width,indentfirst){width=width||4;var res="";var lines=str.split("\n");var sp=lib.repeat(" ",width);for(var i=0;i<lines.length;i++){if(i==0&&!indentfirst){res+=lines[i]+"\n"}else{res+=sp+lines[i]+"\n"}}return r.copySafeness(str,res)},join:function(arr,del,attr){del=del||"";if(attr){arr=lib.map(arr,function(v){return v[attr]})}return arr.join(del)},last:function(arr){return arr[arr.length-1]},length:function(arr){return arr.length},list:function(val){if(lib.isString(val)){return val.split("")}else if(lib.isObject(val)){var keys=[];if(Object.keys){keys=Object.keys(val)}else{for(var k in val){keys.push(k)}}return lib.map(keys,function(k){return{key:k,value:val[k]}})}else{throw new lib.TemplateError("list filter: type not iterable")}},lower:function(str){return str.toLowerCase()},random:function(arr){var i=Math.floor(Math.random()*arr.length);if(i==arr.length){i--}return arr[i]},replace:function(str,old,new_,maxCount){var res=str;var last=res;var count=1;res=res.replace(old,new_);while(last!=res){if(count>=maxCount){break}last=res;res=res.replace(old,new_);count++}return r.copySafeness(str,res)},reverse:function(val){var arr;if(lib.isString(val)){arr=filters.list(val)}else{arr=lib.map(val,function(v){return v})}arr.reverse();if(lib.isString(val)){return r.copySafeness(val,arr.join(""))}return arr},round:function(val,precision,method){precision=precision||0;var factor=Math.pow(10,precision);var rounder;if(method=="ceil"){rounder=Math.ceil}else if(method=="floor"){rounder=Math.floor}else{rounder=Math.round}return rounder(val*factor)/factor},slice:function(arr,slices,fillWith){var sliceLength=Math.floor(arr.length/slices);var extra=arr.length%slices;var offset=0;var res=[];for(var i=0;i<slices;i++){var start=offset+i*sliceLength;if(i<extra){offset++}var end=offset+(i+1)*sliceLength;var slice=arr.slice(start,end);if(fillWith&&i>=extra){slice.push(fillWith)}res.push(slice)}return res},sort:function(arr,reverse,caseSens,attr){arr=lib.map(arr,function(v){return v});arr.sort(function(a,b){var x,y;if(attr){x=a[attr];y=b[attr]}else{x=a;y=b}if(!caseSens&&lib.isString(x)&&lib.isString(y)){x=x.toLowerCase();y=y.toLowerCase()}if(x<y){return reverse?1:-1}else if(x>y){return reverse?-1:1}else{return 0}});return arr},string:function(obj){return r.copySafeness(obj,obj)},title:function(str){var words=str.split(" ");for(var i=0;i<words.length;i++){words[i]=filters.capitalize(words[i])}return r.copySafeness(str,words.join(" "))},trim:function(str){return r.copySafeness(str,str.replace(/^\s*|\s*$/g,""))},truncate:function(input,length,killwords,end){var orig=input;length=length||255;if(input.length<=length)return input;if(killwords){input=input.substring(0,length)}else{var idx=input.lastIndexOf(" ",length);if(idx===-1){idx=length}input=input.substring(0,idx)}input+=end!==undefined&&end!==null?end:"...";return r.copySafeness(orig,input)},upper:function(str){return str.toUpperCase()},wordcount:function(str){return str.match(/\w+/g).length},"float":function(val,def){var res=parseFloat(val);return isNaN(res)?def:res},"int":function(val,def){var res=parseInt(val,10);return isNaN(res)?def:res}};filters.d=filters["default"];filters.e=filters.escape;modules["filters"]=filters})();(function(){function cycler(items){var index=-1;var current=null;return{reset:function(){index=-1;current=null},next:function(){index++;if(index>=items.length){index=0}current=items[index];return current}}}function joiner(sep){sep=sep||",";var first=true;return function(){var val=first?"":sep;first=false;return val}}var globals={range:function(start,stop,step){if(!stop){stop=start;start=0;step=1}else if(!step){step=1}var arr=[];for(var i=start;i<stop;i+=step){arr.push(i)}return arr},cycler:function(){return cycler(Array.prototype.slice.call(arguments))},joiner:function(sep){return joiner(sep)}};modules["globals"]=globals})();(function(){var lib=modules["lib"];var Object=modules["object"];var lexer=modules["lexer"];var compiler=modules["compiler"];var builtin_filters=modules["filters"];var builtin_loaders=modules["loaders"];var runtime=modules["runtime"];var globals=modules["globals"];var Frame=runtime.Frame;var Environment=Object.extend({init:function(loaders,opts){opts=opts||{};this.dev=!!opts.dev;this.autoesc=!!opts.autoescape;if(!loaders){if(builtin_loaders.FileSystemLoader){this.loaders=[new builtin_loaders.FileSystemLoader]}else{this.loaders=[new builtin_loaders.HttpLoader("/views")]}}else{this.loaders=lib.isArray(loaders)?loaders:[loaders]}if(opts.tags){lexer.setTags(opts.tags)}this.filters=builtin_filters;this.cache={};this.extensions={};this.extensionsList=[]},addExtension:function(name,extension){extension._name=name;this.extensions[name]=extension;this.extensionsList.push(extension)},getExtension:function(name){return this.extensions[name]},addFilter:function(name,func){this.filters[name]=func},getFilter:function(name){if(!this.filters[name]){throw new Error("filter not found: "+name)}return this.filters[name]},getTemplate:function(name,eagerCompile){if(name&&name.raw){name=name.raw}var info=null;var tmpl=this.cache[name];var upToDate;if(typeof name!=="string"){throw new Error("template names must be a string: "+name)}if(!tmpl||!tmpl.isUpToDate()){for(var i=0;i<this.loaders.length;i++){if(info=this.loaders[i].getSource(name)){break}}if(!info){throw new Error("template not found: "+name)}this.cache[name]=new Template(info.src,this,info.path,info.upToDate,eagerCompile)}return this.cache[name]},registerPrecompiled:function(templates){for(var name in templates){this.cache[name]=new Template({type:"code",obj:templates[name]},this,name,function(){return true},true)}},express:function(app){var env=this;if(app.render){app.render=function(name,ctx,k){var context={};if(lib.isFunction(ctx)){k=ctx;ctx={}}context=lib.extend(context,this.locals);if(ctx._locals){context=lib.extend(context,ctx._locals)}context=lib.extend(context,ctx);var res=env.render(name,context);k(null,res)}}else{var http=modules["http"];var res=http.ServerResponse.prototype;res._render=function(name,ctx,k){var app=this.app;var context={};if(this._locals){context=lib.extend(context,this._locals)}if(ctx){context=lib.extend(context,ctx);if(ctx.locals){context=lib.extend(context,ctx.locals)}}context=lib.extend(context,app._locals);var str=env.render(name,context);if(k){k(null,str)}else{this.send(str)}}}},render:function(name,ctx){return this.getTemplate(name).render(ctx)}});var Context=Object.extend({init:function(ctx,blocks){this.ctx=ctx;this.blocks={};this.exported=[];for(var name in blocks){this.addBlock(name,blocks[name])}},lookup:function(name){if(name in globals&&!(name in this.ctx)){return globals[name]}else{return this.ctx[name]}},setVariable:function(name,val){this.ctx[name]=val},getVariables:function(){return this.ctx},addBlock:function(name,block){this.blocks[name]=this.blocks[name]||[];this.blocks[name].push(block)},getBlock:function(name){if(!this.blocks[name]){throw new Error('unknown block "'+name+'"')}return this.blocks[name][0]},getSuper:function(env,name,block,frame,runtime){var idx=(this.blocks[name]||[]).indexOf(block);var blk=this.blocks[name][idx+1];var context=this;return function(){if(idx==-1||!blk){throw new Error('no super block available for "'+name+'"')}return blk(env,context,frame,runtime)}},addExport:function(name){this.exported.push(name)},getExported:function(){var exported={};for(var i=0;i<this.exported.length;i++){var name=this.exported[i];exported[name]=this.ctx[name]}return exported}});var Template=Object.extend({init:function(src,env,path,upToDate,eagerCompile){this.env=env||new Environment;if(lib.isObject(src)){switch(src.type){case"code":this.tmplProps=src.obj;break;case"string":this.tmplStr=src.obj;break}}else if(lib.isString(src)){this.tmplStr=src}else{throw new Error("src must be a string or an object describing "+"the source")}this.path=path;this.upToDate=upToDate||function(){return false};if(eagerCompile){var _this=this;lib.withPrettyErrors(this.path,this.env.dev,function(){_this._compile()})}else{this.compiled=false}},render:function(ctx,frame){var self=this;var render=function(){if(!self.compiled){self._compile()}var context=new Context(ctx||{},self.blocks);return self.rootRenderFunc(self.env,context,frame||new Frame,runtime)};return lib.withPrettyErrors(this.path,this.env.dev,render)},isUpToDate:function(){return this.upToDate()},getExported:function(){if(!this.compiled){this._compile()}var context=new Context({},this.blocks);this.rootRenderFunc(this.env,context,new Frame,runtime);return context.getExported()},_compile:function(){var props;if(this.tmplProps){props=this.tmplProps}else{var source=compiler.compile(this.tmplStr,this.env.extensionsList,this.path);var func=new Function(source);props=func()}this.blocks=this._getBlocks(props);this.rootRenderFunc=props.root;this.compiled=true},_getBlocks:function(props){var blocks={};for(var k in props){if(k.slice(0,2)=="b_"){blocks[k.slice(2)]=props[k]}}return blocks}});modules["environment"]={Environment:Environment,Template:Template}})();var nunjucks;var env=modules["environment"];var compiler=modules["compiler"];var parser=modules["parser"];var lexer=modules["lexer"];var runtime=modules["runtime"];var loaders=modules["loaders"];nunjucks={};nunjucks.Environment=env.Environment;nunjucks.Template=env.Template;if(loaders){if(loaders.FileSystemLoader){nunjucks.FileSystemLoader=loaders.FileSystemLoader}else{nunjucks.HttpLoader=loaders.HttpLoader}}nunjucks.compiler=compiler;nunjucks.parser=parser;nunjucks.lexer=lexer;nunjucks.runtime=runtime;nunjucks.require=function(name){return modules[name]};if(typeof define==="function"&&define.amd){define(function(){return nunjucks})}else{window.nunjucks=nunjucks}})();(function() {
var templates = {};
templates["edit.nj.html"] = (function() {
function root(env, context, frame, runtime) {
var lineno = null;
var colno = null;
var output = "";
try {
output += "<form method=\"POST\" class=\"form-horizontal\" action=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "url"), env.autoesc);
output += "\">\n    ";
frame = frame.push();
var t_2 = runtime.contextOrFrameLookup(context, frame, "form");
if(t_2) {for(var t_1=0; t_1 < t_2.length; t_1++) {
var t_3 = t_2[t_1];
frame.set("field", t_3);
output += "\n        ";
if(runtime.memberLookup((t_3),"editable", env.autoesc) || runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "user")),"is_admin", env.autoesc)) {
output += "\n            <div class=\"control-group\">\n\n                <label class=\"control-label\">";
output += runtime.suppressValue(runtime.memberLookup((t_3),"label", env.autoesc), env.autoesc);
output += ":</label>\n                <div class=\"controls\">\n\n                    ";
if(runtime.memberLookup((t_3),"type", env.autoesc) == "textarea") {
output += "\n\n                        <textarea name=\"";
output += runtime.suppressValue(runtime.memberLookup((t_3),"name", env.autoesc), env.autoesc);
output += "\">\n                            ";
output += runtime.suppressValue(runtime.memberLookup((t_3),"value", env.autoesc), env.autoesc);
output += "\n                        </textarea>\n\n                    ";
}
else {
output += "\n\n                        <input\n                            type=\"";
output += runtime.suppressValue(runtime.memberLookup((t_3),"type", env.autoesc), env.autoesc);
output += "\"\n                            name=\"";
output += runtime.suppressValue(runtime.memberLookup((t_3),"name", env.autoesc), env.autoesc);
output += "\"\n                            ";
if(runtime.memberLookup((t_3),"type", env.autoesc) == "checkbox") {
output += "\n                                ";
if(runtime.memberLookup((t_3),"value", env.autoesc)) {
output += "checked";
}
output += "\n                            ";
}
else {
output += "\n                                value=\"";
output += runtime.suppressValue(runtime.memberLookup((t_3),"value", env.autoesc), env.autoesc);
output += "\"\n                            ";
}
output += "\n                        >\n\n                    ";
}
output += "\n\n                </div>\n            </div>\n        ";
}
output += "\n    ";
}
}frame = frame.pop();
output += "\n\n    <div class=\"control-group\">\n        <div class=\"controls\">\n            <button type=\"submit\" class=\"btn btn-primary\">Save</button>\n        </div>\n    </div>\n\n</form>";
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
output += "<ul class=\"unstyled entries\">\n    ";
frame = frame.push();
var t_2 = runtime.contextOrFrameLookup(context, frame, "entry_list");
if(t_2) {for(var t_1=0; t_1 < t_2.length; t_1++) {
var t_3 = t_2[t_1];
frame.set("entry", t_3);
output += "\n        <li class=\"unstyled\">\n            <blockquote>\n                <header>\n                    <div class=\"author\">\n                        ";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "user")),"is_admin", env.autoesc)) {
output += "<a href=\"#user/";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((t_3),"author", env.autoesc)),"id", env.autoesc), env.autoesc);
output += "\">";
}
output += "\n                            ";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((t_3),"author", env.autoesc)),"name", env.autoesc), env.autoesc);
output += "\n                        ";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "user")),"is_admin", env.autoesc)) {
output += "</a>";
}
output += "\n                    </div>\n                    <div class=\"title\"><strong>";
output += runtime.suppressValue(runtime.memberLookup((t_3),"name", env.autoesc), env.autoesc);
output += "</strong></div>\n                    <div class=\"time muted\"><em>";
output += runtime.suppressValue(runtime.memberLookup((t_3),"created", env.autoesc), env.autoesc);
output += "</em></div>\n                </header>\n\n                <section>\n                    ";
output += runtime.suppressValue(env.getFilter("replace").call(context, runtime.memberLookup((t_3),"text", env.autoesc),"\n","<br>"), env.autoesc);
output += "\n                </section>\n\n                ";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "user")),"is_admin", env.autoesc)) {
output += "\n                    <footer>\n                        <ul>\n\n                            ";
if(runtime.memberLookup((t_3),"is_active", env.autoesc) == 0) {
output += "\n                                <li>\n                                    <a href=\"#msg/";
output += runtime.suppressValue(runtime.memberLookup((t_3),"id", env.autoesc), env.autoesc);
output += "/ok\">approve</a>\n                                </li>\n                            ";
}
else {
output += "\n                                <li>\n                                    <a href=\"#msg/";
output += runtime.suppressValue(runtime.memberLookup((t_3),"id", env.autoesc), env.autoesc);
output += "/off\">hide</a>\n                                </li>\n                            ";
}
output += "\n                            <li>\n                                <a href=\"#msg/";
output += runtime.suppressValue(runtime.memberLookup((t_3),"id", env.autoesc), env.autoesc);
output += "\">edit</a>\n                            </li>\n\n                        </ul>\n                    </footer>\n                ";
}
output += "\n\n            </blockquote>\n        </li>\n    ";
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
templates["login.nj.html"] = (function() {
function root(env, context, frame, runtime) {
var lineno = null;
var colno = null;
var output = "";
try {
output += "<form method=\"POST\" action=\"./?action=user_login\" class=\"form-horizontal\">\n\n    <div class=\"control-group\">\n        <label class=\"control-label\">Username:</label>\n        <div class=\"controls\">\n            <input type=\"text\" name=\"user_name\" value=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "user_name"), env.autoesc);
output += "\">\n        </div>\n    </div>\n\n    <div class=\"control-group\">\n        <label class=\"control-label\">Password:</label>\n        <div class=\"controls\">\n            <input type=\"password\" name=\"pass\">\n        </div>\n    </div>\n\n    <div class=\"control-group\">\n        <div class=\"controls\">\n            <button type=\"submit\" class=\"btn btn-primary\">Sign in</button>\n        </div>\n    </div>\n\n</form>\n";
return output;
} catch (e) {
  runtime.handleError(e, lineno, colno);
}
}
return {
root: root
};

})();
templates["userCreate.nj.html"] = (function() {
function root(env, context, frame, runtime) {
var lineno = null;
var colno = null;
var output = "";
try {
output += "<form method=\"POST\" action=\"./?action=user_new\" class=\"form-horizontal\">\n    <div class=\"control-group\">\n        <label class=\"control-label\">Username*:</label>\n        <div class=\"controls\">\n            <input type=\"text\" name=\"user_name\" value=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "user_name"), env.autoesc);
output += "\">\n        </div>\n    </div>\n    <div class=\"control-group\">\n        <label class=\"control-label\">Password*:</label>\n        <div class=\"controls\">\n            <input type=\"password\" name=\"pass\">\n        </div>\n    </div>\n    <div class=\"control-group\">\n        <label class=\"control-label\">Confirm password*:</label>\n        <div class=\"controls\">\n            <input type=\"password\" name=\"pass_confirm\">\n        </div>\n    </div>\n    <div class=\"control-group\">\n        <div class=\"controls\">\n            <button type=\"submit\" class=\"btn btn-primary\">Register</button>\n        </div>\n    </div>\n</form>";
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
(function() {


    /* Controller */

    var control = function(settings) {
        //console.log('-> ajax', settings);

        msg.show('Loading data...');

        microAjax(
            settings.url,
            settings.method || 'GET',
            function(data) {
                if (data.result && data.redirect) {
                    //console.log('-> redirect', data.redirect);
                    document.location.href = data.redirect;
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
                setHtml(cont, '');
            }, 1000);
        },
        show: function(msg, result) {
            if (msg) {
                var alertClass = '';
                if (result) {
                    alertClass = 'alert-success';
                } else if (result === false) {
                    alertClass = 'alert-error';
                } else {
                    alertClass = 'alert-info';
                }
                setHtml(this.getCont(), '<span class="alert ' + alertClass + '">' + msg + '</span>');
            } else {
                this.hide();
            }
        }
    };

    var renderTo = function(data, tplFile) {
        //console.log('-> render', data, tplFile);

        if (data.title) {
            document.title = data.title;
            setHtml('.subheader', data.title);
        }

        msg.show(data.msg, data.result);

        if (tplFile) {
            var html = njEnv.render(tplFile, data);

            setHtml('main', html);

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

                    //console.log('-> onsumbit', this);
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

            req.open(method, url, false);
            req.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            if (method == "POST") {
                req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            }

            req.send(this.postBody);
            //console.log('-> ajax sending', url, method, this.postBody);
        }
    };

    function setHtml(selectorOrElement, html) {

        if (typeof(selectorOrElement) == 'string') {
            var setHtmlNow = function() {
                    var element = document.querySelector(selectorOrElement);
                    if (element) element.innerHTML = html;
                    return element;
                },
                element = setHtmlNow();
            if (!element) setTimeout(setHtmlNow, 0);

            return element;
        }

        if (selectorOrElement) selectorOrElement.innerHTML = html;

        return selectorOrElement;
    }


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
        'reg': function() {
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
                tplFile: 'edit.nj.html',
                title: 'New message'
            });
        },

        'msg/:id': function(id) {
            control({
                url: './?action=entry_edit&id='+parseInt(id),
                tplFile: 'edit.nj.html'
            });
        },

        'msg/:id/ok': function(id) {
            control({
                url: './?action=entry_approve&id='+parseInt(id),
                method: 'POST'
            });
        },

        'msg/:id/off': function(id) {
            //if (confirm('Do you really want to delete this message?')) {
                control({
                    url: './?action=entry_delete&id='+parseInt(id),
                    method: 'POST'
                });
//                routie('');
            //}
        },

        '': function() {
            //console.log('-> default');
            control({
                url: './?action=entries',
                tplFile: 'entryList.nj.html'
            });
        }

    });

}());