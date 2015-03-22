/*! jQuery v2.1.3 | (c) 2005, 2014 jQuery Foundation, Inc. | jquery.org/license */
!function(a,b){"object"==typeof module&&"object"==typeof module.exports?module.exports=a.document?b(a,!0):function(a){if(!a.document)throw new Error("jQuery requires a window with a document");return b(a)}:b(a)}("undefined"!=typeof window?window:this,function(a,b){var c=[],d=c.slice,e=c.concat,f=c.push,g=c.indexOf,h={},i=h.toString,j=h.hasOwnProperty,k={},l=a.document,m="2.1.3",n=function(a,b){return new n.fn.init(a,b)},o=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,p=/^-ms-/,q=/-([\da-z])/gi,r=function(a,b){return b.toUpperCase()};n.fn=n.prototype={jquery:m,constructor:n,selector:"",length:0,toArray:function(){return d.call(this)},get:function(a){return null!=a?0>a?this[a+this.length]:this[a]:d.call(this)},pushStack:function(a){var b=n.merge(this.constructor(),a);return b.prevObject=this,b.context=this.context,b},each:function(a,b){return n.each(this,a,b)},map:function(a){return this.pushStack(n.map(this,function(b,c){return a.call(b,c,b)}))},slice:function(){return this.pushStack(d.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(a){var b=this.length,c=+a+(0>a?b:0);return this.pushStack(c>=0&&b>c?[this[c]]:[])},end:function(){return this.prevObject||this.constructor(null)},push:f,sort:c.sort,splice:c.splice},n.extend=n.fn.extend=function(){var a,b,c,d,e,f,g=arguments[0]||{},h=1,i=arguments.length,j=!1;for("boolean"==typeof g&&(j=g,g=arguments[h]||{},h++),"object"==typeof g||n.isFunction(g)||(g={}),h===i&&(g=this,h--);i>h;h++)if(null!=(a=arguments[h]))for(b in a)c=g[b],d=a[b],g!==d&&(j&&d&&(n.isPlainObject(d)||(e=n.isArray(d)))?(e?(e=!1,f=c&&n.isArray(c)?c:[]):f=c&&n.isPlainObject(c)?c:{},g[b]=n.extend(j,f,d)):void 0!==d&&(g[b]=d));return g},n.extend({expando:"jQuery"+(m+Math.random()).replace(/\D/g,""),isReady:!0,error:function(a){throw new Error(a)},noop:function(){},isFunction:function(a){return"function"===n.type(a)},isArray:Array.isArray,isWindow:function(a){return null!=a&&a===a.window},isNumeric:function(a){return!n.isArray(a)&&a-parseFloat(a)+1>=0},isPlainObject:function(a){return"object"!==n.type(a)||a.nodeType||n.isWindow(a)?!1:a.constructor&&!j.call(a.constructor.prototype,"isPrototypeOf")?!1:!0},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},type:function(a){return null==a?a+"":"object"==typeof a||"function"==typeof a?h[i.call(a)]||"object":typeof a},globalEval:function(a){var b,c=eval;a=n.trim(a),a&&(1===a.indexOf("use strict")?(b=l.createElement("script"),b.text=a,l.head.appendChild(b).parentNode.removeChild(b)):c(a))},camelCase:function(a){return a.replace(p,"ms-").replace(q,r)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,b,c){var d,e=0,f=a.length,g=s(a);if(c){if(g){for(;f>e;e++)if(d=b.apply(a[e],c),d===!1)break}else for(e in a)if(d=b.apply(a[e],c),d===!1)break}else if(g){for(;f>e;e++)if(d=b.call(a[e],e,a[e]),d===!1)break}else for(e in a)if(d=b.call(a[e],e,a[e]),d===!1)break;return a},trim:function(a){return null==a?"":(a+"").replace(o,"")},makeArray:function(a,b){var c=b||[];return null!=a&&(s(Object(a))?n.merge(c,"string"==typeof a?[a]:a):f.call(c,a)),c},inArray:function(a,b,c){return null==b?-1:g.call(b,a,c)},merge:function(a,b){for(var c=+b.length,d=0,e=a.length;c>d;d++)a[e++]=b[d];return a.length=e,a},grep:function(a,b,c){for(var d,e=[],f=0,g=a.length,h=!c;g>f;f++)d=!b(a[f],f),d!==h&&e.push(a[f]);return e},map:function(a,b,c){var d,f=0,g=a.length,h=s(a),i=[];if(h)for(;g>f;f++)d=b(a[f],f,c),null!=d&&i.push(d);else for(f in a)d=b(a[f],f,c),null!=d&&i.push(d);return e.apply([],i)},guid:1,proxy:function(a,b){var c,e,f;return"string"==typeof b&&(c=a[b],b=a,a=c),n.isFunction(a)?(e=d.call(arguments,2),f=function(){return a.apply(b||this,e.concat(d.call(arguments)))},f.guid=a.guid=a.guid||n.guid++,f):void 0},now:Date.now,support:k}),n.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),function(a,b){h["[object "+b+"]"]=b.toLowerCase()});function s(a){var b=a.length,c=n.type(a);return"function"===c||n.isWindow(a)?!1:1===a.nodeType&&b?!0:"array"===c||0===b||"number"==typeof b&&b>0&&b-1 in a}var t=function(a){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u="sizzle"+1*new Date,v=a.document,w=0,x=0,y=hb(),z=hb(),A=hb(),B=function(a,b){return a===b&&(l=!0),0},C=1<<31,D={}.hasOwnProperty,E=[],F=E.pop,G=E.push,H=E.push,I=E.slice,J=function(a,b){for(var c=0,d=a.length;d>c;c++)if(a[c]===b)return c;return-1},K="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",L="[\\x20\\t\\r\\n\\f]",M="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",N=M.replace("w","w#"),O="\\["+L+"*("+M+")(?:"+L+"*([*^$|!~]?=)"+L+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+N+"))|)"+L+"*\\]",P=":("+M+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+O+")*)|.*)\\)|)",Q=new RegExp(L+"+","g"),R=new RegExp("^"+L+"+|((?:^|[^\\\\])(?:\\\\.)*)"+L+"+$","g"),S=new RegExp("^"+L+"*,"+L+"*"),T=new RegExp("^"+L+"*([>+~]|"+L+")"+L+"*"),U=new RegExp("="+L+"*([^\\]'\"]*?)"+L+"*\\]","g"),V=new RegExp(P),W=new RegExp("^"+N+"$"),X={ID:new RegExp("^#("+M+")"),CLASS:new RegExp("^\\.("+M+")"),TAG:new RegExp("^("+M.replace("w","w*")+")"),ATTR:new RegExp("^"+O),PSEUDO:new RegExp("^"+P),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+L+"*(even|odd|(([+-]|)(\\d*)n|)"+L+"*(?:([+-]|)"+L+"*(\\d+)|))"+L+"*\\)|)","i"),bool:new RegExp("^(?:"+K+")$","i"),needsContext:new RegExp("^"+L+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+L+"*((?:-\\d)?\\d*)"+L+"*\\)|)(?=[^-]|$)","i")},Y=/^(?:input|select|textarea|button)$/i,Z=/^h\d$/i,$=/^[^{]+\{\s*\[native \w/,_=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,ab=/[+~]/,bb=/'|\\/g,cb=new RegExp("\\\\([\\da-f]{1,6}"+L+"?|("+L+")|.)","ig"),db=function(a,b,c){var d="0x"+b-65536;return d!==d||c?b:0>d?String.fromCharCode(d+65536):String.fromCharCode(d>>10|55296,1023&d|56320)},eb=function(){m()};try{H.apply(E=I.call(v.childNodes),v.childNodes),E[v.childNodes.length].nodeType}catch(fb){H={apply:E.length?function(a,b){G.apply(a,I.call(b))}:function(a,b){var c=a.length,d=0;while(a[c++]=b[d++]);a.length=c-1}}}function gb(a,b,d,e){var f,h,j,k,l,o,r,s,w,x;if((b?b.ownerDocument||b:v)!==n&&m(b),b=b||n,d=d||[],k=b.nodeType,"string"!=typeof a||!a||1!==k&&9!==k&&11!==k)return d;if(!e&&p){if(11!==k&&(f=_.exec(a)))if(j=f[1]){if(9===k){if(h=b.getElementById(j),!h||!h.parentNode)return d;if(h.id===j)return d.push(h),d}else if(b.ownerDocument&&(h=b.ownerDocument.getElementById(j))&&t(b,h)&&h.id===j)return d.push(h),d}else{if(f[2])return H.apply(d,b.getElementsByTagName(a)),d;if((j=f[3])&&c.getElementsByClassName)return H.apply(d,b.getElementsByClassName(j)),d}if(c.qsa&&(!q||!q.test(a))){if(s=r=u,w=b,x=1!==k&&a,1===k&&"object"!==b.nodeName.toLowerCase()){o=g(a),(r=b.getAttribute("id"))?s=r.replace(bb,"\\$&"):b.setAttribute("id",s),s="[id='"+s+"'] ",l=o.length;while(l--)o[l]=s+rb(o[l]);w=ab.test(a)&&pb(b.parentNode)||b,x=o.join(",")}if(x)try{return H.apply(d,w.querySelectorAll(x)),d}catch(y){}finally{r||b.removeAttribute("id")}}}return i(a.replace(R,"$1"),b,d,e)}function hb(){var a=[];function b(c,e){return a.push(c+" ")>d.cacheLength&&delete b[a.shift()],b[c+" "]=e}return b}function ib(a){return a[u]=!0,a}function jb(a){var b=n.createElement("div");try{return!!a(b)}catch(c){return!1}finally{b.parentNode&&b.parentNode.removeChild(b),b=null}}function kb(a,b){var c=a.split("|"),e=a.length;while(e--)d.attrHandle[c[e]]=b}function lb(a,b){var c=b&&a,d=c&&1===a.nodeType&&1===b.nodeType&&(~b.sourceIndex||C)-(~a.sourceIndex||C);if(d)return d;if(c)while(c=c.nextSibling)if(c===b)return-1;return a?1:-1}function mb(a){return function(b){var c=b.nodeName.toLowerCase();return"input"===c&&b.type===a}}function nb(a){return function(b){var c=b.nodeName.toLowerCase();return("input"===c||"button"===c)&&b.type===a}}function ob(a){return ib(function(b){return b=+b,ib(function(c,d){var e,f=a([],c.length,b),g=f.length;while(g--)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function pb(a){return a&&"undefined"!=typeof a.getElementsByTagName&&a}c=gb.support={},f=gb.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return b?"HTML"!==b.nodeName:!1},m=gb.setDocument=function(a){var b,e,g=a?a.ownerDocument||a:v;return g!==n&&9===g.nodeType&&g.documentElement?(n=g,o=g.documentElement,e=g.defaultView,e&&e!==e.top&&(e.addEventListener?e.addEventListener("unload",eb,!1):e.attachEvent&&e.attachEvent("onunload",eb)),p=!f(g),c.attributes=jb(function(a){return a.className="i",!a.getAttribute("className")}),c.getElementsByTagName=jb(function(a){return a.appendChild(g.createComment("")),!a.getElementsByTagName("*").length}),c.getElementsByClassName=$.test(g.getElementsByClassName),c.getById=jb(function(a){return o.appendChild(a).id=u,!g.getElementsByName||!g.getElementsByName(u).length}),c.getById?(d.find.ID=function(a,b){if("undefined"!=typeof b.getElementById&&p){var c=b.getElementById(a);return c&&c.parentNode?[c]:[]}},d.filter.ID=function(a){var b=a.replace(cb,db);return function(a){return a.getAttribute("id")===b}}):(delete d.find.ID,d.filter.ID=function(a){var b=a.replace(cb,db);return function(a){var c="undefined"!=typeof a.getAttributeNode&&a.getAttributeNode("id");return c&&c.value===b}}),d.find.TAG=c.getElementsByTagName?function(a,b){return"undefined"!=typeof b.getElementsByTagName?b.getElementsByTagName(a):c.qsa?b.querySelectorAll(a):void 0}:function(a,b){var c,d=[],e=0,f=b.getElementsByTagName(a);if("*"===a){while(c=f[e++])1===c.nodeType&&d.push(c);return d}return f},d.find.CLASS=c.getElementsByClassName&&function(a,b){return p?b.getElementsByClassName(a):void 0},r=[],q=[],(c.qsa=$.test(g.querySelectorAll))&&(jb(function(a){o.appendChild(a).innerHTML="<a id='"+u+"'></a><select id='"+u+"-\f]' msallowcapture=''><option selected=''></option></select>",a.querySelectorAll("[msallowcapture^='']").length&&q.push("[*^$]="+L+"*(?:''|\"\")"),a.querySelectorAll("[selected]").length||q.push("\\["+L+"*(?:value|"+K+")"),a.querySelectorAll("[id~="+u+"-]").length||q.push("~="),a.querySelectorAll(":checked").length||q.push(":checked"),a.querySelectorAll("a#"+u+"+*").length||q.push(".#.+[+~]")}),jb(function(a){var b=g.createElement("input");b.setAttribute("type","hidden"),a.appendChild(b).setAttribute("name","D"),a.querySelectorAll("[name=d]").length&&q.push("name"+L+"*[*^$|!~]?="),a.querySelectorAll(":enabled").length||q.push(":enabled",":disabled"),a.querySelectorAll("*,:x"),q.push(",.*:")})),(c.matchesSelector=$.test(s=o.matches||o.webkitMatchesSelector||o.mozMatchesSelector||o.oMatchesSelector||o.msMatchesSelector))&&jb(function(a){c.disconnectedMatch=s.call(a,"div"),s.call(a,"[s!='']:x"),r.push("!=",P)}),q=q.length&&new RegExp(q.join("|")),r=r.length&&new RegExp(r.join("|")),b=$.test(o.compareDocumentPosition),t=b||$.test(o.contains)?function(a,b){var c=9===a.nodeType?a.documentElement:a,d=b&&b.parentNode;return a===d||!(!d||1!==d.nodeType||!(c.contains?c.contains(d):a.compareDocumentPosition&&16&a.compareDocumentPosition(d)))}:function(a,b){if(b)while(b=b.parentNode)if(b===a)return!0;return!1},B=b?function(a,b){if(a===b)return l=!0,0;var d=!a.compareDocumentPosition-!b.compareDocumentPosition;return d?d:(d=(a.ownerDocument||a)===(b.ownerDocument||b)?a.compareDocumentPosition(b):1,1&d||!c.sortDetached&&b.compareDocumentPosition(a)===d?a===g||a.ownerDocument===v&&t(v,a)?-1:b===g||b.ownerDocument===v&&t(v,b)?1:k?J(k,a)-J(k,b):0:4&d?-1:1)}:function(a,b){if(a===b)return l=!0,0;var c,d=0,e=a.parentNode,f=b.parentNode,h=[a],i=[b];if(!e||!f)return a===g?-1:b===g?1:e?-1:f?1:k?J(k,a)-J(k,b):0;if(e===f)return lb(a,b);c=a;while(c=c.parentNode)h.unshift(c);c=b;while(c=c.parentNode)i.unshift(c);while(h[d]===i[d])d++;return d?lb(h[d],i[d]):h[d]===v?-1:i[d]===v?1:0},g):n},gb.matches=function(a,b){return gb(a,null,null,b)},gb.matchesSelector=function(a,b){if((a.ownerDocument||a)!==n&&m(a),b=b.replace(U,"='$1']"),!(!c.matchesSelector||!p||r&&r.test(b)||q&&q.test(b)))try{var d=s.call(a,b);if(d||c.disconnectedMatch||a.document&&11!==a.document.nodeType)return d}catch(e){}return gb(b,n,null,[a]).length>0},gb.contains=function(a,b){return(a.ownerDocument||a)!==n&&m(a),t(a,b)},gb.attr=function(a,b){(a.ownerDocument||a)!==n&&m(a);var e=d.attrHandle[b.toLowerCase()],f=e&&D.call(d.attrHandle,b.toLowerCase())?e(a,b,!p):void 0;return void 0!==f?f:c.attributes||!p?a.getAttribute(b):(f=a.getAttributeNode(b))&&f.specified?f.value:null},gb.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},gb.uniqueSort=function(a){var b,d=[],e=0,f=0;if(l=!c.detectDuplicates,k=!c.sortStable&&a.slice(0),a.sort(B),l){while(b=a[f++])b===a[f]&&(e=d.push(f));while(e--)a.splice(d[e],1)}return k=null,a},e=gb.getText=function(a){var b,c="",d=0,f=a.nodeType;if(f){if(1===f||9===f||11===f){if("string"==typeof a.textContent)return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=e(a)}else if(3===f||4===f)return a.nodeValue}else while(b=a[d++])c+=e(b);return c},d=gb.selectors={cacheLength:50,createPseudo:ib,match:X,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(cb,db),a[3]=(a[3]||a[4]||a[5]||"").replace(cb,db),"~="===a[2]&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),"nth"===a[1].slice(0,3)?(a[3]||gb.error(a[0]),a[4]=+(a[4]?a[5]+(a[6]||1):2*("even"===a[3]||"odd"===a[3])),a[5]=+(a[7]+a[8]||"odd"===a[3])):a[3]&&gb.error(a[0]),a},PSEUDO:function(a){var b,c=!a[6]&&a[2];return X.CHILD.test(a[0])?null:(a[3]?a[2]=a[4]||a[5]||"":c&&V.test(c)&&(b=g(c,!0))&&(b=c.indexOf(")",c.length-b)-c.length)&&(a[0]=a[0].slice(0,b),a[2]=c.slice(0,b)),a.slice(0,3))}},filter:{TAG:function(a){var b=a.replace(cb,db).toLowerCase();return"*"===a?function(){return!0}:function(a){return a.nodeName&&a.nodeName.toLowerCase()===b}},CLASS:function(a){var b=y[a+" "];return b||(b=new RegExp("(^|"+L+")"+a+"("+L+"|$)"))&&y(a,function(a){return b.test("string"==typeof a.className&&a.className||"undefined"!=typeof a.getAttribute&&a.getAttribute("class")||"")})},ATTR:function(a,b,c){return function(d){var e=gb.attr(d,a);return null==e?"!="===b:b?(e+="","="===b?e===c:"!="===b?e!==c:"^="===b?c&&0===e.indexOf(c):"*="===b?c&&e.indexOf(c)>-1:"$="===b?c&&e.slice(-c.length)===c:"~="===b?(" "+e.replace(Q," ")+" ").indexOf(c)>-1:"|="===b?e===c||e.slice(0,c.length+1)===c+"-":!1):!0}},CHILD:function(a,b,c,d,e){var f="nth"!==a.slice(0,3),g="last"!==a.slice(-4),h="of-type"===b;return 1===d&&0===e?function(a){return!!a.parentNode}:function(b,c,i){var j,k,l,m,n,o,p=f!==g?"nextSibling":"previousSibling",q=b.parentNode,r=h&&b.nodeName.toLowerCase(),s=!i&&!h;if(q){if(f){while(p){l=b;while(l=l[p])if(h?l.nodeName.toLowerCase()===r:1===l.nodeType)return!1;o=p="only"===a&&!o&&"nextSibling"}return!0}if(o=[g?q.firstChild:q.lastChild],g&&s){k=q[u]||(q[u]={}),j=k[a]||[],n=j[0]===w&&j[1],m=j[0]===w&&j[2],l=n&&q.childNodes[n];while(l=++n&&l&&l[p]||(m=n=0)||o.pop())if(1===l.nodeType&&++m&&l===b){k[a]=[w,n,m];break}}else if(s&&(j=(b[u]||(b[u]={}))[a])&&j[0]===w)m=j[1];else while(l=++n&&l&&l[p]||(m=n=0)||o.pop())if((h?l.nodeName.toLowerCase()===r:1===l.nodeType)&&++m&&(s&&((l[u]||(l[u]={}))[a]=[w,m]),l===b))break;return m-=e,m===d||m%d===0&&m/d>=0}}},PSEUDO:function(a,b){var c,e=d.pseudos[a]||d.setFilters[a.toLowerCase()]||gb.error("unsupported pseudo: "+a);return e[u]?e(b):e.length>1?(c=[a,a,"",b],d.setFilters.hasOwnProperty(a.toLowerCase())?ib(function(a,c){var d,f=e(a,b),g=f.length;while(g--)d=J(a,f[g]),a[d]=!(c[d]=f[g])}):function(a){return e(a,0,c)}):e}},pseudos:{not:ib(function(a){var b=[],c=[],d=h(a.replace(R,"$1"));return d[u]?ib(function(a,b,c,e){var f,g=d(a,null,e,[]),h=a.length;while(h--)(f=g[h])&&(a[h]=!(b[h]=f))}):function(a,e,f){return b[0]=a,d(b,null,f,c),b[0]=null,!c.pop()}}),has:ib(function(a){return function(b){return gb(a,b).length>0}}),contains:ib(function(a){return a=a.replace(cb,db),function(b){return(b.textContent||b.innerText||e(b)).indexOf(a)>-1}}),lang:ib(function(a){return W.test(a||"")||gb.error("unsupported lang: "+a),a=a.replace(cb,db).toLowerCase(),function(b){var c;do if(c=p?b.lang:b.getAttribute("xml:lang")||b.getAttribute("lang"))return c=c.toLowerCase(),c===a||0===c.indexOf(a+"-");while((b=b.parentNode)&&1===b.nodeType);return!1}}),target:function(b){var c=a.location&&a.location.hash;return c&&c.slice(1)===b.id},root:function(a){return a===o},focus:function(a){return a===n.activeElement&&(!n.hasFocus||n.hasFocus())&&!!(a.type||a.href||~a.tabIndex)},enabled:function(a){return a.disabled===!1},disabled:function(a){return a.disabled===!0},checked:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&!!a.checked||"option"===b&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},empty:function(a){for(a=a.firstChild;a;a=a.nextSibling)if(a.nodeType<6)return!1;return!0},parent:function(a){return!d.pseudos.empty(a)},header:function(a){return Z.test(a.nodeName)},input:function(a){return Y.test(a.nodeName)},button:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&"button"===a.type||"button"===b},text:function(a){var b;return"input"===a.nodeName.toLowerCase()&&"text"===a.type&&(null==(b=a.getAttribute("type"))||"text"===b.toLowerCase())},first:ob(function(){return[0]}),last:ob(function(a,b){return[b-1]}),eq:ob(function(a,b,c){return[0>c?c+b:c]}),even:ob(function(a,b){for(var c=0;b>c;c+=2)a.push(c);return a}),odd:ob(function(a,b){for(var c=1;b>c;c+=2)a.push(c);return a}),lt:ob(function(a,b,c){for(var d=0>c?c+b:c;--d>=0;)a.push(d);return a}),gt:ob(function(a,b,c){for(var d=0>c?c+b:c;++d<b;)a.push(d);return a})}},d.pseudos.nth=d.pseudos.eq;for(b in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})d.pseudos[b]=mb(b);for(b in{submit:!0,reset:!0})d.pseudos[b]=nb(b);function qb(){}qb.prototype=d.filters=d.pseudos,d.setFilters=new qb,g=gb.tokenize=function(a,b){var c,e,f,g,h,i,j,k=z[a+" "];if(k)return b?0:k.slice(0);h=a,i=[],j=d.preFilter;while(h){(!c||(e=S.exec(h)))&&(e&&(h=h.slice(e[0].length)||h),i.push(f=[])),c=!1,(e=T.exec(h))&&(c=e.shift(),f.push({value:c,type:e[0].replace(R," ")}),h=h.slice(c.length));for(g in d.filter)!(e=X[g].exec(h))||j[g]&&!(e=j[g](e))||(c=e.shift(),f.push({value:c,type:g,matches:e}),h=h.slice(c.length));if(!c)break}return b?h.length:h?gb.error(a):z(a,i).slice(0)};function rb(a){for(var b=0,c=a.length,d="";c>b;b++)d+=a[b].value;return d}function sb(a,b,c){var d=b.dir,e=c&&"parentNode"===d,f=x++;return b.first?function(b,c,f){while(b=b[d])if(1===b.nodeType||e)return a(b,c,f)}:function(b,c,g){var h,i,j=[w,f];if(g){while(b=b[d])if((1===b.nodeType||e)&&a(b,c,g))return!0}else while(b=b[d])if(1===b.nodeType||e){if(i=b[u]||(b[u]={}),(h=i[d])&&h[0]===w&&h[1]===f)return j[2]=h[2];if(i[d]=j,j[2]=a(b,c,g))return!0}}}function tb(a){return a.length>1?function(b,c,d){var e=a.length;while(e--)if(!a[e](b,c,d))return!1;return!0}:a[0]}function ub(a,b,c){for(var d=0,e=b.length;e>d;d++)gb(a,b[d],c);return c}function vb(a,b,c,d,e){for(var f,g=[],h=0,i=a.length,j=null!=b;i>h;h++)(f=a[h])&&(!c||c(f,d,e))&&(g.push(f),j&&b.push(h));return g}function wb(a,b,c,d,e,f){return d&&!d[u]&&(d=wb(d)),e&&!e[u]&&(e=wb(e,f)),ib(function(f,g,h,i){var j,k,l,m=[],n=[],o=g.length,p=f||ub(b||"*",h.nodeType?[h]:h,[]),q=!a||!f&&b?p:vb(p,m,a,h,i),r=c?e||(f?a:o||d)?[]:g:q;if(c&&c(q,r,h,i),d){j=vb(r,n),d(j,[],h,i),k=j.length;while(k--)(l=j[k])&&(r[n[k]]=!(q[n[k]]=l))}if(f){if(e||a){if(e){j=[],k=r.length;while(k--)(l=r[k])&&j.push(q[k]=l);e(null,r=[],j,i)}k=r.length;while(k--)(l=r[k])&&(j=e?J(f,l):m[k])>-1&&(f[j]=!(g[j]=l))}}else r=vb(r===g?r.splice(o,r.length):r),e?e(null,g,r,i):H.apply(g,r)})}function xb(a){for(var b,c,e,f=a.length,g=d.relative[a[0].type],h=g||d.relative[" "],i=g?1:0,k=sb(function(a){return a===b},h,!0),l=sb(function(a){return J(b,a)>-1},h,!0),m=[function(a,c,d){var e=!g&&(d||c!==j)||((b=c).nodeType?k(a,c,d):l(a,c,d));return b=null,e}];f>i;i++)if(c=d.relative[a[i].type])m=[sb(tb(m),c)];else{if(c=d.filter[a[i].type].apply(null,a[i].matches),c[u]){for(e=++i;f>e;e++)if(d.relative[a[e].type])break;return wb(i>1&&tb(m),i>1&&rb(a.slice(0,i-1).concat({value:" "===a[i-2].type?"*":""})).replace(R,"$1"),c,e>i&&xb(a.slice(i,e)),f>e&&xb(a=a.slice(e)),f>e&&rb(a))}m.push(c)}return tb(m)}function yb(a,b){var c=b.length>0,e=a.length>0,f=function(f,g,h,i,k){var l,m,o,p=0,q="0",r=f&&[],s=[],t=j,u=f||e&&d.find.TAG("*",k),v=w+=null==t?1:Math.random()||.1,x=u.length;for(k&&(j=g!==n&&g);q!==x&&null!=(l=u[q]);q++){if(e&&l){m=0;while(o=a[m++])if(o(l,g,h)){i.push(l);break}k&&(w=v)}c&&((l=!o&&l)&&p--,f&&r.push(l))}if(p+=q,c&&q!==p){m=0;while(o=b[m++])o(r,s,g,h);if(f){if(p>0)while(q--)r[q]||s[q]||(s[q]=F.call(i));s=vb(s)}H.apply(i,s),k&&!f&&s.length>0&&p+b.length>1&&gb.uniqueSort(i)}return k&&(w=v,j=t),r};return c?ib(f):f}return h=gb.compile=function(a,b){var c,d=[],e=[],f=A[a+" "];if(!f){b||(b=g(a)),c=b.length;while(c--)f=xb(b[c]),f[u]?d.push(f):e.push(f);f=A(a,yb(e,d)),f.selector=a}return f},i=gb.select=function(a,b,e,f){var i,j,k,l,m,n="function"==typeof a&&a,o=!f&&g(a=n.selector||a);if(e=e||[],1===o.length){if(j=o[0]=o[0].slice(0),j.length>2&&"ID"===(k=j[0]).type&&c.getById&&9===b.nodeType&&p&&d.relative[j[1].type]){if(b=(d.find.ID(k.matches[0].replace(cb,db),b)||[])[0],!b)return e;n&&(b=b.parentNode),a=a.slice(j.shift().value.length)}i=X.needsContext.test(a)?0:j.length;while(i--){if(k=j[i],d.relative[l=k.type])break;if((m=d.find[l])&&(f=m(k.matches[0].replace(cb,db),ab.test(j[0].type)&&pb(b.parentNode)||b))){if(j.splice(i,1),a=f.length&&rb(j),!a)return H.apply(e,f),e;break}}}return(n||h(a,o))(f,b,!p,e,ab.test(a)&&pb(b.parentNode)||b),e},c.sortStable=u.split("").sort(B).join("")===u,c.detectDuplicates=!!l,m(),c.sortDetached=jb(function(a){return 1&a.compareDocumentPosition(n.createElement("div"))}),jb(function(a){return a.innerHTML="<a href='#'></a>","#"===a.firstChild.getAttribute("href")})||kb("type|href|height|width",function(a,b,c){return c?void 0:a.getAttribute(b,"type"===b.toLowerCase()?1:2)}),c.attributes&&jb(function(a){return a.innerHTML="<input/>",a.firstChild.setAttribute("value",""),""===a.firstChild.getAttribute("value")})||kb("value",function(a,b,c){return c||"input"!==a.nodeName.toLowerCase()?void 0:a.defaultValue}),jb(function(a){return null==a.getAttribute("disabled")})||kb(K,function(a,b,c){var d;return c?void 0:a[b]===!0?b.toLowerCase():(d=a.getAttributeNode(b))&&d.specified?d.value:null}),gb}(a);n.find=t,n.expr=t.selectors,n.expr[":"]=n.expr.pseudos,n.unique=t.uniqueSort,n.text=t.getText,n.isXMLDoc=t.isXML,n.contains=t.contains;var u=n.expr.match.needsContext,v=/^<(\w+)\s*\/?>(?:<\/\1>|)$/,w=/^.[^:#\[\.,]*$/;function x(a,b,c){if(n.isFunction(b))return n.grep(a,function(a,d){return!!b.call(a,d,a)!==c});if(b.nodeType)return n.grep(a,function(a){return a===b!==c});if("string"==typeof b){if(w.test(b))return n.filter(b,a,c);b=n.filter(b,a)}return n.grep(a,function(a){return g.call(b,a)>=0!==c})}n.filter=function(a,b,c){var d=b[0];return c&&(a=":not("+a+")"),1===b.length&&1===d.nodeType?n.find.matchesSelector(d,a)?[d]:[]:n.find.matches(a,n.grep(b,function(a){return 1===a.nodeType}))},n.fn.extend({find:function(a){var b,c=this.length,d=[],e=this;if("string"!=typeof a)return this.pushStack(n(a).filter(function(){for(b=0;c>b;b++)if(n.contains(e[b],this))return!0}));for(b=0;c>b;b++)n.find(a,e[b],d);return d=this.pushStack(c>1?n.unique(d):d),d.selector=this.selector?this.selector+" "+a:a,d},filter:function(a){return this.pushStack(x(this,a||[],!1))},not:function(a){return this.pushStack(x(this,a||[],!0))},is:function(a){return!!x(this,"string"==typeof a&&u.test(a)?n(a):a||[],!1).length}});var y,z=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,A=n.fn.init=function(a,b){var c,d;if(!a)return this;if("string"==typeof a){if(c="<"===a[0]&&">"===a[a.length-1]&&a.length>=3?[null,a,null]:z.exec(a),!c||!c[1]&&b)return!b||b.jquery?(b||y).find(a):this.constructor(b).find(a);if(c[1]){if(b=b instanceof n?b[0]:b,n.merge(this,n.parseHTML(c[1],b&&b.nodeType?b.ownerDocument||b:l,!0)),v.test(c[1])&&n.isPlainObject(b))for(c in b)n.isFunction(this[c])?this[c](b[c]):this.attr(c,b[c]);return this}return d=l.getElementById(c[2]),d&&d.parentNode&&(this.length=1,this[0]=d),this.context=l,this.selector=a,this}return a.nodeType?(this.context=this[0]=a,this.length=1,this):n.isFunction(a)?"undefined"!=typeof y.ready?y.ready(a):a(n):(void 0!==a.selector&&(this.selector=a.selector,this.context=a.context),n.makeArray(a,this))};A.prototype=n.fn,y=n(l);var B=/^(?:parents|prev(?:Until|All))/,C={children:!0,contents:!0,next:!0,prev:!0};n.extend({dir:function(a,b,c){var d=[],e=void 0!==c;while((a=a[b])&&9!==a.nodeType)if(1===a.nodeType){if(e&&n(a).is(c))break;d.push(a)}return d},sibling:function(a,b){for(var c=[];a;a=a.nextSibling)1===a.nodeType&&a!==b&&c.push(a);return c}}),n.fn.extend({has:function(a){var b=n(a,this),c=b.length;return this.filter(function(){for(var a=0;c>a;a++)if(n.contains(this,b[a]))return!0})},closest:function(a,b){for(var c,d=0,e=this.length,f=[],g=u.test(a)||"string"!=typeof a?n(a,b||this.context):0;e>d;d++)for(c=this[d];c&&c!==b;c=c.parentNode)if(c.nodeType<11&&(g?g.index(c)>-1:1===c.nodeType&&n.find.matchesSelector(c,a))){f.push(c);break}return this.pushStack(f.length>1?n.unique(f):f)},index:function(a){return a?"string"==typeof a?g.call(n(a),this[0]):g.call(this,a.jquery?a[0]:a):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(a,b){return this.pushStack(n.unique(n.merge(this.get(),n(a,b))))},addBack:function(a){return this.add(null==a?this.prevObject:this.prevObject.filter(a))}});function D(a,b){while((a=a[b])&&1!==a.nodeType);return a}n.each({parent:function(a){var b=a.parentNode;return b&&11!==b.nodeType?b:null},parents:function(a){return n.dir(a,"parentNode")},parentsUntil:function(a,b,c){return n.dir(a,"parentNode",c)},next:function(a){return D(a,"nextSibling")},prev:function(a){return D(a,"previousSibling")},nextAll:function(a){return n.dir(a,"nextSibling")},prevAll:function(a){return n.dir(a,"previousSibling")},nextUntil:function(a,b,c){return n.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return n.dir(a,"previousSibling",c)},siblings:function(a){return n.sibling((a.parentNode||{}).firstChild,a)},children:function(a){return n.sibling(a.firstChild)},contents:function(a){return a.contentDocument||n.merge([],a.childNodes)}},function(a,b){n.fn[a]=function(c,d){var e=n.map(this,b,c);return"Until"!==a.slice(-5)&&(d=c),d&&"string"==typeof d&&(e=n.filter(d,e)),this.length>1&&(C[a]||n.unique(e),B.test(a)&&e.reverse()),this.pushStack(e)}});var E=/\S+/g,F={};function G(a){var b=F[a]={};return n.each(a.match(E)||[],function(a,c){b[c]=!0}),b}n.Callbacks=function(a){a="string"==typeof a?F[a]||G(a):n.extend({},a);var b,c,d,e,f,g,h=[],i=!a.once&&[],j=function(l){for(b=a.memory&&l,c=!0,g=e||0,e=0,f=h.length,d=!0;h&&f>g;g++)if(h[g].apply(l[0],l[1])===!1&&a.stopOnFalse){b=!1;break}d=!1,h&&(i?i.length&&j(i.shift()):b?h=[]:k.disable())},k={add:function(){if(h){var c=h.length;!function g(b){n.each(b,function(b,c){var d=n.type(c);"function"===d?a.unique&&k.has(c)||h.push(c):c&&c.length&&"string"!==d&&g(c)})}(arguments),d?f=h.length:b&&(e=c,j(b))}return this},remove:function(){return h&&n.each(arguments,function(a,b){var c;while((c=n.inArray(b,h,c))>-1)h.splice(c,1),d&&(f>=c&&f--,g>=c&&g--)}),this},has:function(a){return a?n.inArray(a,h)>-1:!(!h||!h.length)},empty:function(){return h=[],f=0,this},disable:function(){return h=i=b=void 0,this},disabled:function(){return!h},lock:function(){return i=void 0,b||k.disable(),this},locked:function(){return!i},fireWith:function(a,b){return!h||c&&!i||(b=b||[],b=[a,b.slice?b.slice():b],d?i.push(b):j(b)),this},fire:function(){return k.fireWith(this,arguments),this},fired:function(){return!!c}};return k},n.extend({Deferred:function(a){var b=[["resolve","done",n.Callbacks("once memory"),"resolved"],["reject","fail",n.Callbacks("once memory"),"rejected"],["notify","progress",n.Callbacks("memory")]],c="pending",d={state:function(){return c},always:function(){return e.done(arguments).fail(arguments),this},then:function(){var a=arguments;return n.Deferred(function(c){n.each(b,function(b,f){var g=n.isFunction(a[b])&&a[b];e[f[1]](function(){var a=g&&g.apply(this,arguments);a&&n.isFunction(a.promise)?a.promise().done(c.resolve).fail(c.reject).progress(c.notify):c[f[0]+"With"](this===d?c.promise():this,g?[a]:arguments)})}),a=null}).promise()},promise:function(a){return null!=a?n.extend(a,d):d}},e={};return d.pipe=d.then,n.each(b,function(a,f){var g=f[2],h=f[3];d[f[1]]=g.add,h&&g.add(function(){c=h},b[1^a][2].disable,b[2][2].lock),e[f[0]]=function(){return e[f[0]+"With"](this===e?d:this,arguments),this},e[f[0]+"With"]=g.fireWith}),d.promise(e),a&&a.call(e,e),e},when:function(a){var b=0,c=d.call(arguments),e=c.length,f=1!==e||a&&n.isFunction(a.promise)?e:0,g=1===f?a:n.Deferred(),h=function(a,b,c){return function(e){b[a]=this,c[a]=arguments.length>1?d.call(arguments):e,c===i?g.notifyWith(b,c):--f||g.resolveWith(b,c)}},i,j,k;if(e>1)for(i=new Array(e),j=new Array(e),k=new Array(e);e>b;b++)c[b]&&n.isFunction(c[b].promise)?c[b].promise().done(h(b,k,c)).fail(g.reject).progress(h(b,j,i)):--f;return f||g.resolveWith(k,c),g.promise()}});var H;n.fn.ready=function(a){return n.ready.promise().done(a),this},n.extend({isReady:!1,readyWait:1,holdReady:function(a){a?n.readyWait++:n.ready(!0)},ready:function(a){(a===!0?--n.readyWait:n.isReady)||(n.isReady=!0,a!==!0&&--n.readyWait>0||(H.resolveWith(l,[n]),n.fn.triggerHandler&&(n(l).triggerHandler("ready"),n(l).off("ready"))))}});function I(){l.removeEventListener("DOMContentLoaded",I,!1),a.removeEventListener("load",I,!1),n.ready()}n.ready.promise=function(b){return H||(H=n.Deferred(),"complete"===l.readyState?setTimeout(n.ready):(l.addEventListener("DOMContentLoaded",I,!1),a.addEventListener("load",I,!1))),H.promise(b)},n.ready.promise();var J=n.access=function(a,b,c,d,e,f,g){var h=0,i=a.length,j=null==c;if("object"===n.type(c)){e=!0;for(h in c)n.access(a,b,h,c[h],!0,f,g)}else if(void 0!==d&&(e=!0,n.isFunction(d)||(g=!0),j&&(g?(b.call(a,d),b=null):(j=b,b=function(a,b,c){return j.call(n(a),c)})),b))for(;i>h;h++)b(a[h],c,g?d:d.call(a[h],h,b(a[h],c)));return e?a:j?b.call(a):i?b(a[0],c):f};n.acceptData=function(a){return 1===a.nodeType||9===a.nodeType||!+a.nodeType};function K(){Object.defineProperty(this.cache={},0,{get:function(){return{}}}),this.expando=n.expando+K.uid++}K.uid=1,K.accepts=n.acceptData,K.prototype={key:function(a){if(!K.accepts(a))return 0;var b={},c=a[this.expando];if(!c){c=K.uid++;try{b[this.expando]={value:c},Object.defineProperties(a,b)}catch(d){b[this.expando]=c,n.extend(a,b)}}return this.cache[c]||(this.cache[c]={}),c},set:function(a,b,c){var d,e=this.key(a),f=this.cache[e];if("string"==typeof b)f[b]=c;else if(n.isEmptyObject(f))n.extend(this.cache[e],b);else for(d in b)f[d]=b[d];return f},get:function(a,b){var c=this.cache[this.key(a)];return void 0===b?c:c[b]},access:function(a,b,c){var d;return void 0===b||b&&"string"==typeof b&&void 0===c?(d=this.get(a,b),void 0!==d?d:this.get(a,n.camelCase(b))):(this.set(a,b,c),void 0!==c?c:b)},remove:function(a,b){var c,d,e,f=this.key(a),g=this.cache[f];if(void 0===b)this.cache[f]={};else{n.isArray(b)?d=b.concat(b.map(n.camelCase)):(e=n.camelCase(b),b in g?d=[b,e]:(d=e,d=d in g?[d]:d.match(E)||[])),c=d.length;while(c--)delete g[d[c]]}},hasData:function(a){return!n.isEmptyObject(this.cache[a[this.expando]]||{})},discard:function(a){a[this.expando]&&delete this.cache[a[this.expando]]}};var L=new K,M=new K,N=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,O=/([A-Z])/g;function P(a,b,c){var d;if(void 0===c&&1===a.nodeType)if(d="data-"+b.replace(O,"-$1").toLowerCase(),c=a.getAttribute(d),"string"==typeof c){try{c="true"===c?!0:"false"===c?!1:"null"===c?null:+c+""===c?+c:N.test(c)?n.parseJSON(c):c}catch(e){}M.set(a,b,c)}else c=void 0;return c}n.extend({hasData:function(a){return M.hasData(a)||L.hasData(a)},data:function(a,b,c){return M.access(a,b,c)
},removeData:function(a,b){M.remove(a,b)},_data:function(a,b,c){return L.access(a,b,c)},_removeData:function(a,b){L.remove(a,b)}}),n.fn.extend({data:function(a,b){var c,d,e,f=this[0],g=f&&f.attributes;if(void 0===a){if(this.length&&(e=M.get(f),1===f.nodeType&&!L.get(f,"hasDataAttrs"))){c=g.length;while(c--)g[c]&&(d=g[c].name,0===d.indexOf("data-")&&(d=n.camelCase(d.slice(5)),P(f,d,e[d])));L.set(f,"hasDataAttrs",!0)}return e}return"object"==typeof a?this.each(function(){M.set(this,a)}):J(this,function(b){var c,d=n.camelCase(a);if(f&&void 0===b){if(c=M.get(f,a),void 0!==c)return c;if(c=M.get(f,d),void 0!==c)return c;if(c=P(f,d,void 0),void 0!==c)return c}else this.each(function(){var c=M.get(this,d);M.set(this,d,b),-1!==a.indexOf("-")&&void 0!==c&&M.set(this,a,b)})},null,b,arguments.length>1,null,!0)},removeData:function(a){return this.each(function(){M.remove(this,a)})}}),n.extend({queue:function(a,b,c){var d;return a?(b=(b||"fx")+"queue",d=L.get(a,b),c&&(!d||n.isArray(c)?d=L.access(a,b,n.makeArray(c)):d.push(c)),d||[]):void 0},dequeue:function(a,b){b=b||"fx";var c=n.queue(a,b),d=c.length,e=c.shift(),f=n._queueHooks(a,b),g=function(){n.dequeue(a,b)};"inprogress"===e&&(e=c.shift(),d--),e&&("fx"===b&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return L.get(a,c)||L.access(a,c,{empty:n.Callbacks("once memory").add(function(){L.remove(a,[b+"queue",c])})})}}),n.fn.extend({queue:function(a,b){var c=2;return"string"!=typeof a&&(b=a,a="fx",c--),arguments.length<c?n.queue(this[0],a):void 0===b?this:this.each(function(){var c=n.queue(this,a,b);n._queueHooks(this,a),"fx"===a&&"inprogress"!==c[0]&&n.dequeue(this,a)})},dequeue:function(a){return this.each(function(){n.dequeue(this,a)})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,b){var c,d=1,e=n.Deferred(),f=this,g=this.length,h=function(){--d||e.resolveWith(f,[f])};"string"!=typeof a&&(b=a,a=void 0),a=a||"fx";while(g--)c=L.get(f[g],a+"queueHooks"),c&&c.empty&&(d++,c.empty.add(h));return h(),e.promise(b)}});var Q=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,R=["Top","Right","Bottom","Left"],S=function(a,b){return a=b||a,"none"===n.css(a,"display")||!n.contains(a.ownerDocument,a)},T=/^(?:checkbox|radio)$/i;!function(){var a=l.createDocumentFragment(),b=a.appendChild(l.createElement("div")),c=l.createElement("input");c.setAttribute("type","radio"),c.setAttribute("checked","checked"),c.setAttribute("name","t"),b.appendChild(c),k.checkClone=b.cloneNode(!0).cloneNode(!0).lastChild.checked,b.innerHTML="<textarea>x</textarea>",k.noCloneChecked=!!b.cloneNode(!0).lastChild.defaultValue}();var U="undefined";k.focusinBubbles="onfocusin"in a;var V=/^key/,W=/^(?:mouse|pointer|contextmenu)|click/,X=/^(?:focusinfocus|focusoutblur)$/,Y=/^([^.]*)(?:\.(.+)|)$/;function Z(){return!0}function $(){return!1}function _(){try{return l.activeElement}catch(a){}}n.event={global:{},add:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,o,p,q,r=L.get(a);if(r){c.handler&&(f=c,c=f.handler,e=f.selector),c.guid||(c.guid=n.guid++),(i=r.events)||(i=r.events={}),(g=r.handle)||(g=r.handle=function(b){return typeof n!==U&&n.event.triggered!==b.type?n.event.dispatch.apply(a,arguments):void 0}),b=(b||"").match(E)||[""],j=b.length;while(j--)h=Y.exec(b[j])||[],o=q=h[1],p=(h[2]||"").split(".").sort(),o&&(l=n.event.special[o]||{},o=(e?l.delegateType:l.bindType)||o,l=n.event.special[o]||{},k=n.extend({type:o,origType:q,data:d,handler:c,guid:c.guid,selector:e,needsContext:e&&n.expr.match.needsContext.test(e),namespace:p.join(".")},f),(m=i[o])||(m=i[o]=[],m.delegateCount=0,l.setup&&l.setup.call(a,d,p,g)!==!1||a.addEventListener&&a.addEventListener(o,g,!1)),l.add&&(l.add.call(a,k),k.handler.guid||(k.handler.guid=c.guid)),e?m.splice(m.delegateCount++,0,k):m.push(k),n.event.global[o]=!0)}},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,o,p,q,r=L.hasData(a)&&L.get(a);if(r&&(i=r.events)){b=(b||"").match(E)||[""],j=b.length;while(j--)if(h=Y.exec(b[j])||[],o=q=h[1],p=(h[2]||"").split(".").sort(),o){l=n.event.special[o]||{},o=(d?l.delegateType:l.bindType)||o,m=i[o]||[],h=h[2]&&new RegExp("(^|\\.)"+p.join("\\.(?:.*\\.|)")+"(\\.|$)"),g=f=m.length;while(f--)k=m[f],!e&&q!==k.origType||c&&c.guid!==k.guid||h&&!h.test(k.namespace)||d&&d!==k.selector&&("**"!==d||!k.selector)||(m.splice(f,1),k.selector&&m.delegateCount--,l.remove&&l.remove.call(a,k));g&&!m.length&&(l.teardown&&l.teardown.call(a,p,r.handle)!==!1||n.removeEvent(a,o,r.handle),delete i[o])}else for(o in i)n.event.remove(a,o+b[j],c,d,!0);n.isEmptyObject(i)&&(delete r.handle,L.remove(a,"events"))}},trigger:function(b,c,d,e){var f,g,h,i,k,m,o,p=[d||l],q=j.call(b,"type")?b.type:b,r=j.call(b,"namespace")?b.namespace.split("."):[];if(g=h=d=d||l,3!==d.nodeType&&8!==d.nodeType&&!X.test(q+n.event.triggered)&&(q.indexOf(".")>=0&&(r=q.split("."),q=r.shift(),r.sort()),k=q.indexOf(":")<0&&"on"+q,b=b[n.expando]?b:new n.Event(q,"object"==typeof b&&b),b.isTrigger=e?2:3,b.namespace=r.join("."),b.namespace_re=b.namespace?new RegExp("(^|\\.)"+r.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,b.result=void 0,b.target||(b.target=d),c=null==c?[b]:n.makeArray(c,[b]),o=n.event.special[q]||{},e||!o.trigger||o.trigger.apply(d,c)!==!1)){if(!e&&!o.noBubble&&!n.isWindow(d)){for(i=o.delegateType||q,X.test(i+q)||(g=g.parentNode);g;g=g.parentNode)p.push(g),h=g;h===(d.ownerDocument||l)&&p.push(h.defaultView||h.parentWindow||a)}f=0;while((g=p[f++])&&!b.isPropagationStopped())b.type=f>1?i:o.bindType||q,m=(L.get(g,"events")||{})[b.type]&&L.get(g,"handle"),m&&m.apply(g,c),m=k&&g[k],m&&m.apply&&n.acceptData(g)&&(b.result=m.apply(g,c),b.result===!1&&b.preventDefault());return b.type=q,e||b.isDefaultPrevented()||o._default&&o._default.apply(p.pop(),c)!==!1||!n.acceptData(d)||k&&n.isFunction(d[q])&&!n.isWindow(d)&&(h=d[k],h&&(d[k]=null),n.event.triggered=q,d[q](),n.event.triggered=void 0,h&&(d[k]=h)),b.result}},dispatch:function(a){a=n.event.fix(a);var b,c,e,f,g,h=[],i=d.call(arguments),j=(L.get(this,"events")||{})[a.type]||[],k=n.event.special[a.type]||{};if(i[0]=a,a.delegateTarget=this,!k.preDispatch||k.preDispatch.call(this,a)!==!1){h=n.event.handlers.call(this,a,j),b=0;while((f=h[b++])&&!a.isPropagationStopped()){a.currentTarget=f.elem,c=0;while((g=f.handlers[c++])&&!a.isImmediatePropagationStopped())(!a.namespace_re||a.namespace_re.test(g.namespace))&&(a.handleObj=g,a.data=g.data,e=((n.event.special[g.origType]||{}).handle||g.handler).apply(f.elem,i),void 0!==e&&(a.result=e)===!1&&(a.preventDefault(),a.stopPropagation()))}return k.postDispatch&&k.postDispatch.call(this,a),a.result}},handlers:function(a,b){var c,d,e,f,g=[],h=b.delegateCount,i=a.target;if(h&&i.nodeType&&(!a.button||"click"!==a.type))for(;i!==this;i=i.parentNode||this)if(i.disabled!==!0||"click"!==a.type){for(d=[],c=0;h>c;c++)f=b[c],e=f.selector+" ",void 0===d[e]&&(d[e]=f.needsContext?n(e,this).index(i)>=0:n.find(e,this,null,[i]).length),d[e]&&d.push(f);d.length&&g.push({elem:i,handlers:d})}return h<b.length&&g.push({elem:this,handlers:b.slice(h)}),g},props:"altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){return null==a.which&&(a.which=null!=b.charCode?b.charCode:b.keyCode),a}},mouseHooks:{props:"button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,b){var c,d,e,f=b.button;return null==a.pageX&&null!=b.clientX&&(c=a.target.ownerDocument||l,d=c.documentElement,e=c.body,a.pageX=b.clientX+(d&&d.scrollLeft||e&&e.scrollLeft||0)-(d&&d.clientLeft||e&&e.clientLeft||0),a.pageY=b.clientY+(d&&d.scrollTop||e&&e.scrollTop||0)-(d&&d.clientTop||e&&e.clientTop||0)),a.which||void 0===f||(a.which=1&f?1:2&f?3:4&f?2:0),a}},fix:function(a){if(a[n.expando])return a;var b,c,d,e=a.type,f=a,g=this.fixHooks[e];g||(this.fixHooks[e]=g=W.test(e)?this.mouseHooks:V.test(e)?this.keyHooks:{}),d=g.props?this.props.concat(g.props):this.props,a=new n.Event(f),b=d.length;while(b--)c=d[b],a[c]=f[c];return a.target||(a.target=l),3===a.target.nodeType&&(a.target=a.target.parentNode),g.filter?g.filter(a,f):a},special:{load:{noBubble:!0},focus:{trigger:function(){return this!==_()&&this.focus?(this.focus(),!1):void 0},delegateType:"focusin"},blur:{trigger:function(){return this===_()&&this.blur?(this.blur(),!1):void 0},delegateType:"focusout"},click:{trigger:function(){return"checkbox"===this.type&&this.click&&n.nodeName(this,"input")?(this.click(),!1):void 0},_default:function(a){return n.nodeName(a.target,"a")}},beforeunload:{postDispatch:function(a){void 0!==a.result&&a.originalEvent&&(a.originalEvent.returnValue=a.result)}}},simulate:function(a,b,c,d){var e=n.extend(new n.Event,c,{type:a,isSimulated:!0,originalEvent:{}});d?n.event.trigger(e,null,b):n.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},n.removeEvent=function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)},n.Event=function(a,b){return this instanceof n.Event?(a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||void 0===a.defaultPrevented&&a.returnValue===!1?Z:$):this.type=a,b&&n.extend(this,b),this.timeStamp=a&&a.timeStamp||n.now(),void(this[n.expando]=!0)):new n.Event(a,b)},n.Event.prototype={isDefaultPrevented:$,isPropagationStopped:$,isImmediatePropagationStopped:$,preventDefault:function(){var a=this.originalEvent;this.isDefaultPrevented=Z,a&&a.preventDefault&&a.preventDefault()},stopPropagation:function(){var a=this.originalEvent;this.isPropagationStopped=Z,a&&a.stopPropagation&&a.stopPropagation()},stopImmediatePropagation:function(){var a=this.originalEvent;this.isImmediatePropagationStopped=Z,a&&a.stopImmediatePropagation&&a.stopImmediatePropagation(),this.stopPropagation()}},n.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(a,b){n.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj;return(!e||e!==d&&!n.contains(d,e))&&(a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b),c}}}),k.focusinBubbles||n.each({focus:"focusin",blur:"focusout"},function(a,b){var c=function(a){n.event.simulate(b,a.target,n.event.fix(a),!0)};n.event.special[b]={setup:function(){var d=this.ownerDocument||this,e=L.access(d,b);e||d.addEventListener(a,c,!0),L.access(d,b,(e||0)+1)},teardown:function(){var d=this.ownerDocument||this,e=L.access(d,b)-1;e?L.access(d,b,e):(d.removeEventListener(a,c,!0),L.remove(d,b))}}}),n.fn.extend({on:function(a,b,c,d,e){var f,g;if("object"==typeof a){"string"!=typeof b&&(c=c||b,b=void 0);for(g in a)this.on(g,b,c,a[g],e);return this}if(null==c&&null==d?(d=b,c=b=void 0):null==d&&("string"==typeof b?(d=c,c=void 0):(d=c,c=b,b=void 0)),d===!1)d=$;else if(!d)return this;return 1===e&&(f=d,d=function(a){return n().off(a),f.apply(this,arguments)},d.guid=f.guid||(f.guid=n.guid++)),this.each(function(){n.event.add(this,a,d,c,b)})},one:function(a,b,c,d){return this.on(a,b,c,d,1)},off:function(a,b,c){var d,e;if(a&&a.preventDefault&&a.handleObj)return d=a.handleObj,n(a.delegateTarget).off(d.namespace?d.origType+"."+d.namespace:d.origType,d.selector,d.handler),this;if("object"==typeof a){for(e in a)this.off(e,b,a[e]);return this}return(b===!1||"function"==typeof b)&&(c=b,b=void 0),c===!1&&(c=$),this.each(function(){n.event.remove(this,a,c,b)})},trigger:function(a,b){return this.each(function(){n.event.trigger(a,b,this)})},triggerHandler:function(a,b){var c=this[0];return c?n.event.trigger(a,b,c,!0):void 0}});var ab=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,bb=/<([\w:]+)/,cb=/<|&#?\w+;/,db=/<(?:script|style|link)/i,eb=/checked\s*(?:[^=]|=\s*.checked.)/i,fb=/^$|\/(?:java|ecma)script/i,gb=/^true\/(.*)/,hb=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,ib={option:[1,"<select multiple='multiple'>","</select>"],thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};ib.optgroup=ib.option,ib.tbody=ib.tfoot=ib.colgroup=ib.caption=ib.thead,ib.th=ib.td;function jb(a,b){return n.nodeName(a,"table")&&n.nodeName(11!==b.nodeType?b:b.firstChild,"tr")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a}function kb(a){return a.type=(null!==a.getAttribute("type"))+"/"+a.type,a}function lb(a){var b=gb.exec(a.type);return b?a.type=b[1]:a.removeAttribute("type"),a}function mb(a,b){for(var c=0,d=a.length;d>c;c++)L.set(a[c],"globalEval",!b||L.get(b[c],"globalEval"))}function nb(a,b){var c,d,e,f,g,h,i,j;if(1===b.nodeType){if(L.hasData(a)&&(f=L.access(a),g=L.set(b,f),j=f.events)){delete g.handle,g.events={};for(e in j)for(c=0,d=j[e].length;d>c;c++)n.event.add(b,e,j[e][c])}M.hasData(a)&&(h=M.access(a),i=n.extend({},h),M.set(b,i))}}function ob(a,b){var c=a.getElementsByTagName?a.getElementsByTagName(b||"*"):a.querySelectorAll?a.querySelectorAll(b||"*"):[];return void 0===b||b&&n.nodeName(a,b)?n.merge([a],c):c}function pb(a,b){var c=b.nodeName.toLowerCase();"input"===c&&T.test(a.type)?b.checked=a.checked:("input"===c||"textarea"===c)&&(b.defaultValue=a.defaultValue)}n.extend({clone:function(a,b,c){var d,e,f,g,h=a.cloneNode(!0),i=n.contains(a.ownerDocument,a);if(!(k.noCloneChecked||1!==a.nodeType&&11!==a.nodeType||n.isXMLDoc(a)))for(g=ob(h),f=ob(a),d=0,e=f.length;e>d;d++)pb(f[d],g[d]);if(b)if(c)for(f=f||ob(a),g=g||ob(h),d=0,e=f.length;e>d;d++)nb(f[d],g[d]);else nb(a,h);return g=ob(h,"script"),g.length>0&&mb(g,!i&&ob(a,"script")),h},buildFragment:function(a,b,c,d){for(var e,f,g,h,i,j,k=b.createDocumentFragment(),l=[],m=0,o=a.length;o>m;m++)if(e=a[m],e||0===e)if("object"===n.type(e))n.merge(l,e.nodeType?[e]:e);else if(cb.test(e)){f=f||k.appendChild(b.createElement("div")),g=(bb.exec(e)||["",""])[1].toLowerCase(),h=ib[g]||ib._default,f.innerHTML=h[1]+e.replace(ab,"<$1></$2>")+h[2],j=h[0];while(j--)f=f.lastChild;n.merge(l,f.childNodes),f=k.firstChild,f.textContent=""}else l.push(b.createTextNode(e));k.textContent="",m=0;while(e=l[m++])if((!d||-1===n.inArray(e,d))&&(i=n.contains(e.ownerDocument,e),f=ob(k.appendChild(e),"script"),i&&mb(f),c)){j=0;while(e=f[j++])fb.test(e.type||"")&&c.push(e)}return k},cleanData:function(a){for(var b,c,d,e,f=n.event.special,g=0;void 0!==(c=a[g]);g++){if(n.acceptData(c)&&(e=c[L.expando],e&&(b=L.cache[e]))){if(b.events)for(d in b.events)f[d]?n.event.remove(c,d):n.removeEvent(c,d,b.handle);L.cache[e]&&delete L.cache[e]}delete M.cache[c[M.expando]]}}}),n.fn.extend({text:function(a){return J(this,function(a){return void 0===a?n.text(this):this.empty().each(function(){(1===this.nodeType||11===this.nodeType||9===this.nodeType)&&(this.textContent=a)})},null,a,arguments.length)},append:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=jb(this,a);b.appendChild(a)}})},prepend:function(){return this.domManip(arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=jb(this,a);b.insertBefore(a,b.firstChild)}})},before:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this)})},after:function(){return this.domManip(arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this.nextSibling)})},remove:function(a,b){for(var c,d=a?n.filter(a,this):this,e=0;null!=(c=d[e]);e++)b||1!==c.nodeType||n.cleanData(ob(c)),c.parentNode&&(b&&n.contains(c.ownerDocument,c)&&mb(ob(c,"script")),c.parentNode.removeChild(c));return this},empty:function(){for(var a,b=0;null!=(a=this[b]);b++)1===a.nodeType&&(n.cleanData(ob(a,!1)),a.textContent="");return this},clone:function(a,b){return a=null==a?!1:a,b=null==b?a:b,this.map(function(){return n.clone(this,a,b)})},html:function(a){return J(this,function(a){var b=this[0]||{},c=0,d=this.length;if(void 0===a&&1===b.nodeType)return b.innerHTML;if("string"==typeof a&&!db.test(a)&&!ib[(bb.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(ab,"<$1></$2>");try{for(;d>c;c++)b=this[c]||{},1===b.nodeType&&(n.cleanData(ob(b,!1)),b.innerHTML=a);b=0}catch(e){}}b&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(){var a=arguments[0];return this.domManip(arguments,function(b){a=this.parentNode,n.cleanData(ob(this)),a&&a.replaceChild(b,this)}),a&&(a.length||a.nodeType)?this:this.remove()},detach:function(a){return this.remove(a,!0)},domManip:function(a,b){a=e.apply([],a);var c,d,f,g,h,i,j=0,l=this.length,m=this,o=l-1,p=a[0],q=n.isFunction(p);if(q||l>1&&"string"==typeof p&&!k.checkClone&&eb.test(p))return this.each(function(c){var d=m.eq(c);q&&(a[0]=p.call(this,c,d.html())),d.domManip(a,b)});if(l&&(c=n.buildFragment(a,this[0].ownerDocument,!1,this),d=c.firstChild,1===c.childNodes.length&&(c=d),d)){for(f=n.map(ob(c,"script"),kb),g=f.length;l>j;j++)h=c,j!==o&&(h=n.clone(h,!0,!0),g&&n.merge(f,ob(h,"script"))),b.call(this[j],h,j);if(g)for(i=f[f.length-1].ownerDocument,n.map(f,lb),j=0;g>j;j++)h=f[j],fb.test(h.type||"")&&!L.access(h,"globalEval")&&n.contains(i,h)&&(h.src?n._evalUrl&&n._evalUrl(h.src):n.globalEval(h.textContent.replace(hb,"")))}return this}}),n.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){n.fn[a]=function(a){for(var c,d=[],e=n(a),g=e.length-1,h=0;g>=h;h++)c=h===g?this:this.clone(!0),n(e[h])[b](c),f.apply(d,c.get());return this.pushStack(d)}});var qb,rb={};function sb(b,c){var d,e=n(c.createElement(b)).appendTo(c.body),f=a.getDefaultComputedStyle&&(d=a.getDefaultComputedStyle(e[0]))?d.display:n.css(e[0],"display");return e.detach(),f}function tb(a){var b=l,c=rb[a];return c||(c=sb(a,b),"none"!==c&&c||(qb=(qb||n("<iframe frameborder='0' width='0' height='0'/>")).appendTo(b.documentElement),b=qb[0].contentDocument,b.write(),b.close(),c=sb(a,b),qb.detach()),rb[a]=c),c}var ub=/^margin/,vb=new RegExp("^("+Q+")(?!px)[a-z%]+$","i"),wb=function(b){return b.ownerDocument.defaultView.opener?b.ownerDocument.defaultView.getComputedStyle(b,null):a.getComputedStyle(b,null)};function xb(a,b,c){var d,e,f,g,h=a.style;return c=c||wb(a),c&&(g=c.getPropertyValue(b)||c[b]),c&&(""!==g||n.contains(a.ownerDocument,a)||(g=n.style(a,b)),vb.test(g)&&ub.test(b)&&(d=h.width,e=h.minWidth,f=h.maxWidth,h.minWidth=h.maxWidth=h.width=g,g=c.width,h.width=d,h.minWidth=e,h.maxWidth=f)),void 0!==g?g+"":g}function yb(a,b){return{get:function(){return a()?void delete this.get:(this.get=b).apply(this,arguments)}}}!function(){var b,c,d=l.documentElement,e=l.createElement("div"),f=l.createElement("div");if(f.style){f.style.backgroundClip="content-box",f.cloneNode(!0).style.backgroundClip="",k.clearCloneStyle="content-box"===f.style.backgroundClip,e.style.cssText="border:0;width:0;height:0;top:0;left:-9999px;margin-top:1px;position:absolute",e.appendChild(f);function g(){f.style.cssText="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute",f.innerHTML="",d.appendChild(e);var g=a.getComputedStyle(f,null);b="1%"!==g.top,c="4px"===g.width,d.removeChild(e)}a.getComputedStyle&&n.extend(k,{pixelPosition:function(){return g(),b},boxSizingReliable:function(){return null==c&&g(),c},reliableMarginRight:function(){var b,c=f.appendChild(l.createElement("div"));return c.style.cssText=f.style.cssText="-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0",c.style.marginRight=c.style.width="0",f.style.width="1px",d.appendChild(e),b=!parseFloat(a.getComputedStyle(c,null).marginRight),d.removeChild(e),f.removeChild(c),b}})}}(),n.swap=function(a,b,c,d){var e,f,g={};for(f in b)g[f]=a.style[f],a.style[f]=b[f];e=c.apply(a,d||[]);for(f in b)a.style[f]=g[f];return e};var zb=/^(none|table(?!-c[ea]).+)/,Ab=new RegExp("^("+Q+")(.*)$","i"),Bb=new RegExp("^([+-])=("+Q+")","i"),Cb={position:"absolute",visibility:"hidden",display:"block"},Db={letterSpacing:"0",fontWeight:"400"},Eb=["Webkit","O","Moz","ms"];function Fb(a,b){if(b in a)return b;var c=b[0].toUpperCase()+b.slice(1),d=b,e=Eb.length;while(e--)if(b=Eb[e]+c,b in a)return b;return d}function Gb(a,b,c){var d=Ab.exec(b);return d?Math.max(0,d[1]-(c||0))+(d[2]||"px"):b}function Hb(a,b,c,d,e){for(var f=c===(d?"border":"content")?4:"width"===b?1:0,g=0;4>f;f+=2)"margin"===c&&(g+=n.css(a,c+R[f],!0,e)),d?("content"===c&&(g-=n.css(a,"padding"+R[f],!0,e)),"margin"!==c&&(g-=n.css(a,"border"+R[f]+"Width",!0,e))):(g+=n.css(a,"padding"+R[f],!0,e),"padding"!==c&&(g+=n.css(a,"border"+R[f]+"Width",!0,e)));return g}function Ib(a,b,c){var d=!0,e="width"===b?a.offsetWidth:a.offsetHeight,f=wb(a),g="border-box"===n.css(a,"boxSizing",!1,f);if(0>=e||null==e){if(e=xb(a,b,f),(0>e||null==e)&&(e=a.style[b]),vb.test(e))return e;d=g&&(k.boxSizingReliable()||e===a.style[b]),e=parseFloat(e)||0}return e+Hb(a,b,c||(g?"border":"content"),d,f)+"px"}function Jb(a,b){for(var c,d,e,f=[],g=0,h=a.length;h>g;g++)d=a[g],d.style&&(f[g]=L.get(d,"olddisplay"),c=d.style.display,b?(f[g]||"none"!==c||(d.style.display=""),""===d.style.display&&S(d)&&(f[g]=L.access(d,"olddisplay",tb(d.nodeName)))):(e=S(d),"none"===c&&e||L.set(d,"olddisplay",e?c:n.css(d,"display"))));for(g=0;h>g;g++)d=a[g],d.style&&(b&&"none"!==d.style.display&&""!==d.style.display||(d.style.display=b?f[g]||"":"none"));return a}n.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=xb(a,"opacity");return""===c?"1":c}}}},cssNumber:{columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":"cssFloat"},style:function(a,b,c,d){if(a&&3!==a.nodeType&&8!==a.nodeType&&a.style){var e,f,g,h=n.camelCase(b),i=a.style;return b=n.cssProps[h]||(n.cssProps[h]=Fb(i,h)),g=n.cssHooks[b]||n.cssHooks[h],void 0===c?g&&"get"in g&&void 0!==(e=g.get(a,!1,d))?e:i[b]:(f=typeof c,"string"===f&&(e=Bb.exec(c))&&(c=(e[1]+1)*e[2]+parseFloat(n.css(a,b)),f="number"),null!=c&&c===c&&("number"!==f||n.cssNumber[h]||(c+="px"),k.clearCloneStyle||""!==c||0!==b.indexOf("background")||(i[b]="inherit"),g&&"set"in g&&void 0===(c=g.set(a,c,d))||(i[b]=c)),void 0)}},css:function(a,b,c,d){var e,f,g,h=n.camelCase(b);return b=n.cssProps[h]||(n.cssProps[h]=Fb(a.style,h)),g=n.cssHooks[b]||n.cssHooks[h],g&&"get"in g&&(e=g.get(a,!0,c)),void 0===e&&(e=xb(a,b,d)),"normal"===e&&b in Db&&(e=Db[b]),""===c||c?(f=parseFloat(e),c===!0||n.isNumeric(f)?f||0:e):e}}),n.each(["height","width"],function(a,b){n.cssHooks[b]={get:function(a,c,d){return c?zb.test(n.css(a,"display"))&&0===a.offsetWidth?n.swap(a,Cb,function(){return Ib(a,b,d)}):Ib(a,b,d):void 0},set:function(a,c,d){var e=d&&wb(a);return Gb(a,c,d?Hb(a,b,d,"border-box"===n.css(a,"boxSizing",!1,e),e):0)}}}),n.cssHooks.marginRight=yb(k.reliableMarginRight,function(a,b){return b?n.swap(a,{display:"inline-block"},xb,[a,"marginRight"]):void 0}),n.each({margin:"",padding:"",border:"Width"},function(a,b){n.cssHooks[a+b]={expand:function(c){for(var d=0,e={},f="string"==typeof c?c.split(" "):[c];4>d;d++)e[a+R[d]+b]=f[d]||f[d-2]||f[0];return e}},ub.test(a)||(n.cssHooks[a+b].set=Gb)}),n.fn.extend({css:function(a,b){return J(this,function(a,b,c){var d,e,f={},g=0;if(n.isArray(b)){for(d=wb(a),e=b.length;e>g;g++)f[b[g]]=n.css(a,b[g],!1,d);return f}return void 0!==c?n.style(a,b,c):n.css(a,b)},a,b,arguments.length>1)},show:function(){return Jb(this,!0)},hide:function(){return Jb(this)},toggle:function(a){return"boolean"==typeof a?a?this.show():this.hide():this.each(function(){S(this)?n(this).show():n(this).hide()})}});function Kb(a,b,c,d,e){return new Kb.prototype.init(a,b,c,d,e)}n.Tween=Kb,Kb.prototype={constructor:Kb,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||"swing",this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(n.cssNumber[c]?"":"px")},cur:function(){var a=Kb.propHooks[this.prop];return a&&a.get?a.get(this):Kb.propHooks._default.get(this)},run:function(a){var b,c=Kb.propHooks[this.prop];return this.pos=b=this.options.duration?n.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):Kb.propHooks._default.set(this),this}},Kb.prototype.init.prototype=Kb.prototype,Kb.propHooks={_default:{get:function(a){var b;return null==a.elem[a.prop]||a.elem.style&&null!=a.elem.style[a.prop]?(b=n.css(a.elem,a.prop,""),b&&"auto"!==b?b:0):a.elem[a.prop]},set:function(a){n.fx.step[a.prop]?n.fx.step[a.prop](a):a.elem.style&&(null!=a.elem.style[n.cssProps[a.prop]]||n.cssHooks[a.prop])?n.style(a.elem,a.prop,a.now+a.unit):a.elem[a.prop]=a.now}}},Kb.propHooks.scrollTop=Kb.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},n.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2}},n.fx=Kb.prototype.init,n.fx.step={};var Lb,Mb,Nb=/^(?:toggle|show|hide)$/,Ob=new RegExp("^(?:([+-])=|)("+Q+")([a-z%]*)$","i"),Pb=/queueHooks$/,Qb=[Vb],Rb={"*":[function(a,b){var c=this.createTween(a,b),d=c.cur(),e=Ob.exec(b),f=e&&e[3]||(n.cssNumber[a]?"":"px"),g=(n.cssNumber[a]||"px"!==f&&+d)&&Ob.exec(n.css(c.elem,a)),h=1,i=20;if(g&&g[3]!==f){f=f||g[3],e=e||[],g=+d||1;do h=h||".5",g/=h,n.style(c.elem,a,g+f);while(h!==(h=c.cur()/d)&&1!==h&&--i)}return e&&(g=c.start=+g||+d||0,c.unit=f,c.end=e[1]?g+(e[1]+1)*e[2]:+e[2]),c}]};function Sb(){return setTimeout(function(){Lb=void 0}),Lb=n.now()}function Tb(a,b){var c,d=0,e={height:a};for(b=b?1:0;4>d;d+=2-b)c=R[d],e["margin"+c]=e["padding"+c]=a;return b&&(e.opacity=e.width=a),e}function Ub(a,b,c){for(var d,e=(Rb[b]||[]).concat(Rb["*"]),f=0,g=e.length;g>f;f++)if(d=e[f].call(c,b,a))return d}function Vb(a,b,c){var d,e,f,g,h,i,j,k,l=this,m={},o=a.style,p=a.nodeType&&S(a),q=L.get(a,"fxshow");c.queue||(h=n._queueHooks(a,"fx"),null==h.unqueued&&(h.unqueued=0,i=h.empty.fire,h.empty.fire=function(){h.unqueued||i()}),h.unqueued++,l.always(function(){l.always(function(){h.unqueued--,n.queue(a,"fx").length||h.empty.fire()})})),1===a.nodeType&&("height"in b||"width"in b)&&(c.overflow=[o.overflow,o.overflowX,o.overflowY],j=n.css(a,"display"),k="none"===j?L.get(a,"olddisplay")||tb(a.nodeName):j,"inline"===k&&"none"===n.css(a,"float")&&(o.display="inline-block")),c.overflow&&(o.overflow="hidden",l.always(function(){o.overflow=c.overflow[0],o.overflowX=c.overflow[1],o.overflowY=c.overflow[2]}));for(d in b)if(e=b[d],Nb.exec(e)){if(delete b[d],f=f||"toggle"===e,e===(p?"hide":"show")){if("show"!==e||!q||void 0===q[d])continue;p=!0}m[d]=q&&q[d]||n.style(a,d)}else j=void 0;if(n.isEmptyObject(m))"inline"===("none"===j?tb(a.nodeName):j)&&(o.display=j);else{q?"hidden"in q&&(p=q.hidden):q=L.access(a,"fxshow",{}),f&&(q.hidden=!p),p?n(a).show():l.done(function(){n(a).hide()}),l.done(function(){var b;L.remove(a,"fxshow");for(b in m)n.style(a,b,m[b])});for(d in m)g=Ub(p?q[d]:0,d,l),d in q||(q[d]=g.start,p&&(g.end=g.start,g.start="width"===d||"height"===d?1:0))}}function Wb(a,b){var c,d,e,f,g;for(c in a)if(d=n.camelCase(c),e=b[d],f=a[c],n.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=n.cssHooks[d],g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}function Xb(a,b,c){var d,e,f=0,g=Qb.length,h=n.Deferred().always(function(){delete i.elem}),i=function(){if(e)return!1;for(var b=Lb||Sb(),c=Math.max(0,j.startTime+j.duration-b),d=c/j.duration||0,f=1-d,g=0,i=j.tweens.length;i>g;g++)j.tweens[g].run(f);return h.notifyWith(a,[j,f,c]),1>f&&i?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:n.extend({},b),opts:n.extend(!0,{specialEasing:{}},c),originalProperties:b,originalOptions:c,startTime:Lb||Sb(),duration:c.duration,tweens:[],createTween:function(b,c){var d=n.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(d),d},stop:function(b){var c=0,d=b?j.tweens.length:0;if(e)return this;for(e=!0;d>c;c++)j.tweens[c].run(1);return b?h.resolveWith(a,[j,b]):h.rejectWith(a,[j,b]),this}}),k=j.props;for(Wb(k,j.opts.specialEasing);g>f;f++)if(d=Qb[f].call(j,a,k,j.opts))return d;return n.map(k,Ub,j),n.isFunction(j.opts.start)&&j.opts.start.call(a,j),n.fx.timer(n.extend(i,{elem:a,anim:j,queue:j.opts.queue})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}n.Animation=n.extend(Xb,{tweener:function(a,b){n.isFunction(a)?(b=a,a=["*"]):a=a.split(" ");for(var c,d=0,e=a.length;e>d;d++)c=a[d],Rb[c]=Rb[c]||[],Rb[c].unshift(b)},prefilter:function(a,b){b?Qb.unshift(a):Qb.push(a)}}),n.speed=function(a,b,c){var d=a&&"object"==typeof a?n.extend({},a):{complete:c||!c&&b||n.isFunction(a)&&a,duration:a,easing:c&&b||b&&!n.isFunction(b)&&b};return d.duration=n.fx.off?0:"number"==typeof d.duration?d.duration:d.duration in n.fx.speeds?n.fx.speeds[d.duration]:n.fx.speeds._default,(null==d.queue||d.queue===!0)&&(d.queue="fx"),d.old=d.complete,d.complete=function(){n.isFunction(d.old)&&d.old.call(this),d.queue&&n.dequeue(this,d.queue)},d},n.fn.extend({fadeTo:function(a,b,c,d){return this.filter(S).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=n.isEmptyObject(a),f=n.speed(b,c,d),g=function(){var b=Xb(this,n.extend({},a),f);(e||L.get(this,"finish"))&&b.stop(!0)};return g.finish=g,e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,b,c){var d=function(a){var b=a.stop;delete a.stop,b(c)};return"string"!=typeof a&&(c=b,b=a,a=void 0),b&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,e=null!=a&&a+"queueHooks",f=n.timers,g=L.get(this);if(e)g[e]&&g[e].stop&&d(g[e]);else for(e in g)g[e]&&g[e].stop&&Pb.test(e)&&d(g[e]);for(e=f.length;e--;)f[e].elem!==this||null!=a&&f[e].queue!==a||(f[e].anim.stop(c),b=!1,f.splice(e,1));(b||!c)&&n.dequeue(this,a)})},finish:function(a){return a!==!1&&(a=a||"fx"),this.each(function(){var b,c=L.get(this),d=c[a+"queue"],e=c[a+"queueHooks"],f=n.timers,g=d?d.length:0;for(c.finish=!0,n.queue(this,a,[]),e&&e.stop&&e.stop.call(this,!0),b=f.length;b--;)f[b].elem===this&&f[b].queue===a&&(f[b].anim.stop(!0),f.splice(b,1));for(b=0;g>b;b++)d[b]&&d[b].finish&&d[b].finish.call(this);delete c.finish})}}),n.each(["toggle","show","hide"],function(a,b){var c=n.fn[b];n.fn[b]=function(a,d,e){return null==a||"boolean"==typeof a?c.apply(this,arguments):this.animate(Tb(b,!0),a,d,e)}}),n.each({slideDown:Tb("show"),slideUp:Tb("hide"),slideToggle:Tb("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){n.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),n.timers=[],n.fx.tick=function(){var a,b=0,c=n.timers;for(Lb=n.now();b<c.length;b++)a=c[b],a()||c[b]!==a||c.splice(b--,1);c.length||n.fx.stop(),Lb=void 0},n.fx.timer=function(a){n.timers.push(a),a()?n.fx.start():n.timers.pop()},n.fx.interval=13,n.fx.start=function(){Mb||(Mb=setInterval(n.fx.tick,n.fx.interval))},n.fx.stop=function(){clearInterval(Mb),Mb=null},n.fx.speeds={slow:600,fast:200,_default:400},n.fn.delay=function(a,b){return a=n.fx?n.fx.speeds[a]||a:a,b=b||"fx",this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},function(){var a=l.createElement("input"),b=l.createElement("select"),c=b.appendChild(l.createElement("option"));a.type="checkbox",k.checkOn=""!==a.value,k.optSelected=c.selected,b.disabled=!0,k.optDisabled=!c.disabled,a=l.createElement("input"),a.value="t",a.type="radio",k.radioValue="t"===a.value}();var Yb,Zb,$b=n.expr.attrHandle;n.fn.extend({attr:function(a,b){return J(this,n.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){n.removeAttr(this,a)})}}),n.extend({attr:function(a,b,c){var d,e,f=a.nodeType;if(a&&3!==f&&8!==f&&2!==f)return typeof a.getAttribute===U?n.prop(a,b,c):(1===f&&n.isXMLDoc(a)||(b=b.toLowerCase(),d=n.attrHooks[b]||(n.expr.match.bool.test(b)?Zb:Yb)),void 0===c?d&&"get"in d&&null!==(e=d.get(a,b))?e:(e=n.find.attr(a,b),null==e?void 0:e):null!==c?d&&"set"in d&&void 0!==(e=d.set(a,c,b))?e:(a.setAttribute(b,c+""),c):void n.removeAttr(a,b))
},removeAttr:function(a,b){var c,d,e=0,f=b&&b.match(E);if(f&&1===a.nodeType)while(c=f[e++])d=n.propFix[c]||c,n.expr.match.bool.test(c)&&(a[d]=!1),a.removeAttribute(c)},attrHooks:{type:{set:function(a,b){if(!k.radioValue&&"radio"===b&&n.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}}}}),Zb={set:function(a,b,c){return b===!1?n.removeAttr(a,c):a.setAttribute(c,c),c}},n.each(n.expr.match.bool.source.match(/\w+/g),function(a,b){var c=$b[b]||n.find.attr;$b[b]=function(a,b,d){var e,f;return d||(f=$b[b],$b[b]=e,e=null!=c(a,b,d)?b.toLowerCase():null,$b[b]=f),e}});var _b=/^(?:input|select|textarea|button)$/i;n.fn.extend({prop:function(a,b){return J(this,n.prop,a,b,arguments.length>1)},removeProp:function(a){return this.each(function(){delete this[n.propFix[a]||a]})}}),n.extend({propFix:{"for":"htmlFor","class":"className"},prop:function(a,b,c){var d,e,f,g=a.nodeType;if(a&&3!==g&&8!==g&&2!==g)return f=1!==g||!n.isXMLDoc(a),f&&(b=n.propFix[b]||b,e=n.propHooks[b]),void 0!==c?e&&"set"in e&&void 0!==(d=e.set(a,c,b))?d:a[b]=c:e&&"get"in e&&null!==(d=e.get(a,b))?d:a[b]},propHooks:{tabIndex:{get:function(a){return a.hasAttribute("tabindex")||_b.test(a.nodeName)||a.href?a.tabIndex:-1}}}}),k.optSelected||(n.propHooks.selected={get:function(a){var b=a.parentNode;return b&&b.parentNode&&b.parentNode.selectedIndex,null}}),n.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){n.propFix[this.toLowerCase()]=this});var ac=/[\t\r\n\f]/g;n.fn.extend({addClass:function(a){var b,c,d,e,f,g,h="string"==typeof a&&a,i=0,j=this.length;if(n.isFunction(a))return this.each(function(b){n(this).addClass(a.call(this,b,this.className))});if(h)for(b=(a||"").match(E)||[];j>i;i++)if(c=this[i],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(ac," "):" ")){f=0;while(e=b[f++])d.indexOf(" "+e+" ")<0&&(d+=e+" ");g=n.trim(d),c.className!==g&&(c.className=g)}return this},removeClass:function(a){var b,c,d,e,f,g,h=0===arguments.length||"string"==typeof a&&a,i=0,j=this.length;if(n.isFunction(a))return this.each(function(b){n(this).removeClass(a.call(this,b,this.className))});if(h)for(b=(a||"").match(E)||[];j>i;i++)if(c=this[i],d=1===c.nodeType&&(c.className?(" "+c.className+" ").replace(ac," "):"")){f=0;while(e=b[f++])while(d.indexOf(" "+e+" ")>=0)d=d.replace(" "+e+" "," ");g=a?n.trim(d):"",c.className!==g&&(c.className=g)}return this},toggleClass:function(a,b){var c=typeof a;return"boolean"==typeof b&&"string"===c?b?this.addClass(a):this.removeClass(a):this.each(n.isFunction(a)?function(c){n(this).toggleClass(a.call(this,c,this.className,b),b)}:function(){if("string"===c){var b,d=0,e=n(this),f=a.match(E)||[];while(b=f[d++])e.hasClass(b)?e.removeClass(b):e.addClass(b)}else(c===U||"boolean"===c)&&(this.className&&L.set(this,"__className__",this.className),this.className=this.className||a===!1?"":L.get(this,"__className__")||"")})},hasClass:function(a){for(var b=" "+a+" ",c=0,d=this.length;d>c;c++)if(1===this[c].nodeType&&(" "+this[c].className+" ").replace(ac," ").indexOf(b)>=0)return!0;return!1}});var bc=/\r/g;n.fn.extend({val:function(a){var b,c,d,e=this[0];{if(arguments.length)return d=n.isFunction(a),this.each(function(c){var e;1===this.nodeType&&(e=d?a.call(this,c,n(this).val()):a,null==e?e="":"number"==typeof e?e+="":n.isArray(e)&&(e=n.map(e,function(a){return null==a?"":a+""})),b=n.valHooks[this.type]||n.valHooks[this.nodeName.toLowerCase()],b&&"set"in b&&void 0!==b.set(this,e,"value")||(this.value=e))});if(e)return b=n.valHooks[e.type]||n.valHooks[e.nodeName.toLowerCase()],b&&"get"in b&&void 0!==(c=b.get(e,"value"))?c:(c=e.value,"string"==typeof c?c.replace(bc,""):null==c?"":c)}}}),n.extend({valHooks:{option:{get:function(a){var b=n.find.attr(a,"value");return null!=b?b:n.trim(n.text(a))}},select:{get:function(a){for(var b,c,d=a.options,e=a.selectedIndex,f="select-one"===a.type||0>e,g=f?null:[],h=f?e+1:d.length,i=0>e?h:f?e:0;h>i;i++)if(c=d[i],!(!c.selected&&i!==e||(k.optDisabled?c.disabled:null!==c.getAttribute("disabled"))||c.parentNode.disabled&&n.nodeName(c.parentNode,"optgroup"))){if(b=n(c).val(),f)return b;g.push(b)}return g},set:function(a,b){var c,d,e=a.options,f=n.makeArray(b),g=e.length;while(g--)d=e[g],(d.selected=n.inArray(d.value,f)>=0)&&(c=!0);return c||(a.selectedIndex=-1),f}}}}),n.each(["radio","checkbox"],function(){n.valHooks[this]={set:function(a,b){return n.isArray(b)?a.checked=n.inArray(n(a).val(),b)>=0:void 0}},k.checkOn||(n.valHooks[this].get=function(a){return null===a.getAttribute("value")?"on":a.value})}),n.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){n.fn[b]=function(a,c){return arguments.length>0?this.on(b,null,a,c):this.trigger(b)}}),n.fn.extend({hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return 1===arguments.length?this.off(a,"**"):this.off(b,a||"**",c)}});var cc=n.now(),dc=/\?/;n.parseJSON=function(a){return JSON.parse(a+"")},n.parseXML=function(a){var b,c;if(!a||"string"!=typeof a)return null;try{c=new DOMParser,b=c.parseFromString(a,"text/xml")}catch(d){b=void 0}return(!b||b.getElementsByTagName("parsererror").length)&&n.error("Invalid XML: "+a),b};var ec=/#.*$/,fc=/([?&])_=[^&]*/,gc=/^(.*?):[ \t]*([^\r\n]*)$/gm,hc=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,ic=/^(?:GET|HEAD)$/,jc=/^\/\//,kc=/^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,lc={},mc={},nc="*/".concat("*"),oc=a.location.href,pc=kc.exec(oc.toLowerCase())||[];function qc(a){return function(b,c){"string"!=typeof b&&(c=b,b="*");var d,e=0,f=b.toLowerCase().match(E)||[];if(n.isFunction(c))while(d=f[e++])"+"===d[0]?(d=d.slice(1)||"*",(a[d]=a[d]||[]).unshift(c)):(a[d]=a[d]||[]).push(c)}}function rc(a,b,c,d){var e={},f=a===mc;function g(h){var i;return e[h]=!0,n.each(a[h]||[],function(a,h){var j=h(b,c,d);return"string"!=typeof j||f||e[j]?f?!(i=j):void 0:(b.dataTypes.unshift(j),g(j),!1)}),i}return g(b.dataTypes[0])||!e["*"]&&g("*")}function sc(a,b){var c,d,e=n.ajaxSettings.flatOptions||{};for(c in b)void 0!==b[c]&&((e[c]?a:d||(d={}))[c]=b[c]);return d&&n.extend(!0,a,d),a}function tc(a,b,c){var d,e,f,g,h=a.contents,i=a.dataTypes;while("*"===i[0])i.shift(),void 0===d&&(d=a.mimeType||b.getResponseHeader("Content-Type"));if(d)for(e in h)if(h[e]&&h[e].test(d)){i.unshift(e);break}if(i[0]in c)f=i[0];else{for(e in c){if(!i[0]||a.converters[e+" "+i[0]]){f=e;break}g||(g=e)}f=f||g}return f?(f!==i[0]&&i.unshift(f),c[f]):void 0}function uc(a,b,c,d){var e,f,g,h,i,j={},k=a.dataTypes.slice();if(k[1])for(g in a.converters)j[g.toLowerCase()]=a.converters[g];f=k.shift();while(f)if(a.responseFields[f]&&(c[a.responseFields[f]]=b),!i&&d&&a.dataFilter&&(b=a.dataFilter(b,a.dataType)),i=f,f=k.shift())if("*"===f)f=i;else if("*"!==i&&i!==f){if(g=j[i+" "+f]||j["* "+f],!g)for(e in j)if(h=e.split(" "),h[1]===f&&(g=j[i+" "+h[0]]||j["* "+h[0]])){g===!0?g=j[e]:j[e]!==!0&&(f=h[0],k.unshift(h[1]));break}if(g!==!0)if(g&&a["throws"])b=g(b);else try{b=g(b)}catch(l){return{state:"parsererror",error:g?l:"No conversion from "+i+" to "+f}}}return{state:"success",data:b}}n.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:oc,type:"GET",isLocal:hc.test(pc[1]),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":nc,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":n.parseJSON,"text xml":n.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(a,b){return b?sc(sc(a,n.ajaxSettings),b):sc(n.ajaxSettings,a)},ajaxPrefilter:qc(lc),ajaxTransport:qc(mc),ajax:function(a,b){"object"==typeof a&&(b=a,a=void 0),b=b||{};var c,d,e,f,g,h,i,j,k=n.ajaxSetup({},b),l=k.context||k,m=k.context&&(l.nodeType||l.jquery)?n(l):n.event,o=n.Deferred(),p=n.Callbacks("once memory"),q=k.statusCode||{},r={},s={},t=0,u="canceled",v={readyState:0,getResponseHeader:function(a){var b;if(2===t){if(!f){f={};while(b=gc.exec(e))f[b[1].toLowerCase()]=b[2]}b=f[a.toLowerCase()]}return null==b?null:b},getAllResponseHeaders:function(){return 2===t?e:null},setRequestHeader:function(a,b){var c=a.toLowerCase();return t||(a=s[c]=s[c]||a,r[a]=b),this},overrideMimeType:function(a){return t||(k.mimeType=a),this},statusCode:function(a){var b;if(a)if(2>t)for(b in a)q[b]=[q[b],a[b]];else v.always(a[v.status]);return this},abort:function(a){var b=a||u;return c&&c.abort(b),x(0,b),this}};if(o.promise(v).complete=p.add,v.success=v.done,v.error=v.fail,k.url=((a||k.url||oc)+"").replace(ec,"").replace(jc,pc[1]+"//"),k.type=b.method||b.type||k.method||k.type,k.dataTypes=n.trim(k.dataType||"*").toLowerCase().match(E)||[""],null==k.crossDomain&&(h=kc.exec(k.url.toLowerCase()),k.crossDomain=!(!h||h[1]===pc[1]&&h[2]===pc[2]&&(h[3]||("http:"===h[1]?"80":"443"))===(pc[3]||("http:"===pc[1]?"80":"443")))),k.data&&k.processData&&"string"!=typeof k.data&&(k.data=n.param(k.data,k.traditional)),rc(lc,k,b,v),2===t)return v;i=n.event&&k.global,i&&0===n.active++&&n.event.trigger("ajaxStart"),k.type=k.type.toUpperCase(),k.hasContent=!ic.test(k.type),d=k.url,k.hasContent||(k.data&&(d=k.url+=(dc.test(d)?"&":"?")+k.data,delete k.data),k.cache===!1&&(k.url=fc.test(d)?d.replace(fc,"$1_="+cc++):d+(dc.test(d)?"&":"?")+"_="+cc++)),k.ifModified&&(n.lastModified[d]&&v.setRequestHeader("If-Modified-Since",n.lastModified[d]),n.etag[d]&&v.setRequestHeader("If-None-Match",n.etag[d])),(k.data&&k.hasContent&&k.contentType!==!1||b.contentType)&&v.setRequestHeader("Content-Type",k.contentType),v.setRequestHeader("Accept",k.dataTypes[0]&&k.accepts[k.dataTypes[0]]?k.accepts[k.dataTypes[0]]+("*"!==k.dataTypes[0]?", "+nc+"; q=0.01":""):k.accepts["*"]);for(j in k.headers)v.setRequestHeader(j,k.headers[j]);if(k.beforeSend&&(k.beforeSend.call(l,v,k)===!1||2===t))return v.abort();u="abort";for(j in{success:1,error:1,complete:1})v[j](k[j]);if(c=rc(mc,k,b,v)){v.readyState=1,i&&m.trigger("ajaxSend",[v,k]),k.async&&k.timeout>0&&(g=setTimeout(function(){v.abort("timeout")},k.timeout));try{t=1,c.send(r,x)}catch(w){if(!(2>t))throw w;x(-1,w)}}else x(-1,"No Transport");function x(a,b,f,h){var j,r,s,u,w,x=b;2!==t&&(t=2,g&&clearTimeout(g),c=void 0,e=h||"",v.readyState=a>0?4:0,j=a>=200&&300>a||304===a,f&&(u=tc(k,v,f)),u=uc(k,u,v,j),j?(k.ifModified&&(w=v.getResponseHeader("Last-Modified"),w&&(n.lastModified[d]=w),w=v.getResponseHeader("etag"),w&&(n.etag[d]=w)),204===a||"HEAD"===k.type?x="nocontent":304===a?x="notmodified":(x=u.state,r=u.data,s=u.error,j=!s)):(s=x,(a||!x)&&(x="error",0>a&&(a=0))),v.status=a,v.statusText=(b||x)+"",j?o.resolveWith(l,[r,x,v]):o.rejectWith(l,[v,x,s]),v.statusCode(q),q=void 0,i&&m.trigger(j?"ajaxSuccess":"ajaxError",[v,k,j?r:s]),p.fireWith(l,[v,x]),i&&(m.trigger("ajaxComplete",[v,k]),--n.active||n.event.trigger("ajaxStop")))}return v},getJSON:function(a,b,c){return n.get(a,b,c,"json")},getScript:function(a,b){return n.get(a,void 0,b,"script")}}),n.each(["get","post"],function(a,b){n[b]=function(a,c,d,e){return n.isFunction(c)&&(e=e||d,d=c,c=void 0),n.ajax({url:a,type:b,dataType:e,data:c,success:d})}}),n._evalUrl=function(a){return n.ajax({url:a,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0})},n.fn.extend({wrapAll:function(a){var b;return n.isFunction(a)?this.each(function(b){n(this).wrapAll(a.call(this,b))}):(this[0]&&(b=n(a,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstElementChild)a=a.firstElementChild;return a}).append(this)),this)},wrapInner:function(a){return this.each(n.isFunction(a)?function(b){n(this).wrapInner(a.call(this,b))}:function(){var b=n(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=n.isFunction(a);return this.each(function(c){n(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){n.nodeName(this,"body")||n(this).replaceWith(this.childNodes)}).end()}}),n.expr.filters.hidden=function(a){return a.offsetWidth<=0&&a.offsetHeight<=0},n.expr.filters.visible=function(a){return!n.expr.filters.hidden(a)};var vc=/%20/g,wc=/\[\]$/,xc=/\r?\n/g,yc=/^(?:submit|button|image|reset|file)$/i,zc=/^(?:input|select|textarea|keygen)/i;function Ac(a,b,c,d){var e;if(n.isArray(b))n.each(b,function(b,e){c||wc.test(a)?d(a,e):Ac(a+"["+("object"==typeof e?b:"")+"]",e,c,d)});else if(c||"object"!==n.type(b))d(a,b);else for(e in b)Ac(a+"["+e+"]",b[e],c,d)}n.param=function(a,b){var c,d=[],e=function(a,b){b=n.isFunction(b)?b():null==b?"":b,d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};if(void 0===b&&(b=n.ajaxSettings&&n.ajaxSettings.traditional),n.isArray(a)||a.jquery&&!n.isPlainObject(a))n.each(a,function(){e(this.name,this.value)});else for(c in a)Ac(c,a[c],b,e);return d.join("&").replace(vc,"+")},n.fn.extend({serialize:function(){return n.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var a=n.prop(this,"elements");return a?n.makeArray(a):this}).filter(function(){var a=this.type;return this.name&&!n(this).is(":disabled")&&zc.test(this.nodeName)&&!yc.test(a)&&(this.checked||!T.test(a))}).map(function(a,b){var c=n(this).val();return null==c?null:n.isArray(c)?n.map(c,function(a){return{name:b.name,value:a.replace(xc,"\r\n")}}):{name:b.name,value:c.replace(xc,"\r\n")}}).get()}}),n.ajaxSettings.xhr=function(){try{return new XMLHttpRequest}catch(a){}};var Bc=0,Cc={},Dc={0:200,1223:204},Ec=n.ajaxSettings.xhr();a.attachEvent&&a.attachEvent("onunload",function(){for(var a in Cc)Cc[a]()}),k.cors=!!Ec&&"withCredentials"in Ec,k.ajax=Ec=!!Ec,n.ajaxTransport(function(a){var b;return k.cors||Ec&&!a.crossDomain?{send:function(c,d){var e,f=a.xhr(),g=++Bc;if(f.open(a.type,a.url,a.async,a.username,a.password),a.xhrFields)for(e in a.xhrFields)f[e]=a.xhrFields[e];a.mimeType&&f.overrideMimeType&&f.overrideMimeType(a.mimeType),a.crossDomain||c["X-Requested-With"]||(c["X-Requested-With"]="XMLHttpRequest");for(e in c)f.setRequestHeader(e,c[e]);b=function(a){return function(){b&&(delete Cc[g],b=f.onload=f.onerror=null,"abort"===a?f.abort():"error"===a?d(f.status,f.statusText):d(Dc[f.status]||f.status,f.statusText,"string"==typeof f.responseText?{text:f.responseText}:void 0,f.getAllResponseHeaders()))}},f.onload=b(),f.onerror=b("error"),b=Cc[g]=b("abort");try{f.send(a.hasContent&&a.data||null)}catch(h){if(b)throw h}},abort:function(){b&&b()}}:void 0}),n.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/(?:java|ecma)script/},converters:{"text script":function(a){return n.globalEval(a),a}}}),n.ajaxPrefilter("script",function(a){void 0===a.cache&&(a.cache=!1),a.crossDomain&&(a.type="GET")}),n.ajaxTransport("script",function(a){if(a.crossDomain){var b,c;return{send:function(d,e){b=n("<script>").prop({async:!0,charset:a.scriptCharset,src:a.url}).on("load error",c=function(a){b.remove(),c=null,a&&e("error"===a.type?404:200,a.type)}),l.head.appendChild(b[0])},abort:function(){c&&c()}}}});var Fc=[],Gc=/(=)\?(?=&|$)|\?\?/;n.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=Fc.pop()||n.expando+"_"+cc++;return this[a]=!0,a}}),n.ajaxPrefilter("json jsonp",function(b,c,d){var e,f,g,h=b.jsonp!==!1&&(Gc.test(b.url)?"url":"string"==typeof b.data&&!(b.contentType||"").indexOf("application/x-www-form-urlencoded")&&Gc.test(b.data)&&"data");return h||"jsonp"===b.dataTypes[0]?(e=b.jsonpCallback=n.isFunction(b.jsonpCallback)?b.jsonpCallback():b.jsonpCallback,h?b[h]=b[h].replace(Gc,"$1"+e):b.jsonp!==!1&&(b.url+=(dc.test(b.url)?"&":"?")+b.jsonp+"="+e),b.converters["script json"]=function(){return g||n.error(e+" was not called"),g[0]},b.dataTypes[0]="json",f=a[e],a[e]=function(){g=arguments},d.always(function(){a[e]=f,b[e]&&(b.jsonpCallback=c.jsonpCallback,Fc.push(e)),g&&n.isFunction(f)&&f(g[0]),g=f=void 0}),"script"):void 0}),n.parseHTML=function(a,b,c){if(!a||"string"!=typeof a)return null;"boolean"==typeof b&&(c=b,b=!1),b=b||l;var d=v.exec(a),e=!c&&[];return d?[b.createElement(d[1])]:(d=n.buildFragment([a],b,e),e&&e.length&&n(e).remove(),n.merge([],d.childNodes))};var Hc=n.fn.load;n.fn.load=function(a,b,c){if("string"!=typeof a&&Hc)return Hc.apply(this,arguments);var d,e,f,g=this,h=a.indexOf(" ");return h>=0&&(d=n.trim(a.slice(h)),a=a.slice(0,h)),n.isFunction(b)?(c=b,b=void 0):b&&"object"==typeof b&&(e="POST"),g.length>0&&n.ajax({url:a,type:e,dataType:"html",data:b}).done(function(a){f=arguments,g.html(d?n("<div>").append(n.parseHTML(a)).find(d):a)}).complete(c&&function(a,b){g.each(c,f||[a.responseText,b,a])}),this},n.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(a,b){n.fn[b]=function(a){return this.on(b,a)}}),n.expr.filters.animated=function(a){return n.grep(n.timers,function(b){return a===b.elem}).length};var Ic=a.document.documentElement;function Jc(a){return n.isWindow(a)?a:9===a.nodeType&&a.defaultView}n.offset={setOffset:function(a,b,c){var d,e,f,g,h,i,j,k=n.css(a,"position"),l=n(a),m={};"static"===k&&(a.style.position="relative"),h=l.offset(),f=n.css(a,"top"),i=n.css(a,"left"),j=("absolute"===k||"fixed"===k)&&(f+i).indexOf("auto")>-1,j?(d=l.position(),g=d.top,e=d.left):(g=parseFloat(f)||0,e=parseFloat(i)||0),n.isFunction(b)&&(b=b.call(a,c,h)),null!=b.top&&(m.top=b.top-h.top+g),null!=b.left&&(m.left=b.left-h.left+e),"using"in b?b.using.call(a,m):l.css(m)}},n.fn.extend({offset:function(a){if(arguments.length)return void 0===a?this:this.each(function(b){n.offset.setOffset(this,a,b)});var b,c,d=this[0],e={top:0,left:0},f=d&&d.ownerDocument;if(f)return b=f.documentElement,n.contains(b,d)?(typeof d.getBoundingClientRect!==U&&(e=d.getBoundingClientRect()),c=Jc(f),{top:e.top+c.pageYOffset-b.clientTop,left:e.left+c.pageXOffset-b.clientLeft}):e},position:function(){if(this[0]){var a,b,c=this[0],d={top:0,left:0};return"fixed"===n.css(c,"position")?b=c.getBoundingClientRect():(a=this.offsetParent(),b=this.offset(),n.nodeName(a[0],"html")||(d=a.offset()),d.top+=n.css(a[0],"borderTopWidth",!0),d.left+=n.css(a[0],"borderLeftWidth",!0)),{top:b.top-d.top-n.css(c,"marginTop",!0),left:b.left-d.left-n.css(c,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||Ic;while(a&&!n.nodeName(a,"html")&&"static"===n.css(a,"position"))a=a.offsetParent;return a||Ic})}}),n.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(b,c){var d="pageYOffset"===c;n.fn[b]=function(e){return J(this,function(b,e,f){var g=Jc(b);return void 0===f?g?g[c]:b[e]:void(g?g.scrollTo(d?a.pageXOffset:f,d?f:a.pageYOffset):b[e]=f)},b,e,arguments.length,null)}}),n.each(["top","left"],function(a,b){n.cssHooks[b]=yb(k.pixelPosition,function(a,c){return c?(c=xb(a,b),vb.test(c)?n(a).position()[b]+"px":c):void 0})}),n.each({Height:"height",Width:"width"},function(a,b){n.each({padding:"inner"+a,content:b,"":"outer"+a},function(c,d){n.fn[d]=function(d,e){var f=arguments.length&&(c||"boolean"!=typeof d),g=c||(d===!0||e===!0?"margin":"border");return J(this,function(b,c,d){var e;return n.isWindow(b)?b.document.documentElement["client"+a]:9===b.nodeType?(e=b.documentElement,Math.max(b.body["scroll"+a],e["scroll"+a],b.body["offset"+a],e["offset"+a],e["client"+a])):void 0===d?n.css(b,c,g):n.style(b,c,d,g)},b,f?d:void 0,f,null)}})}),n.fn.size=function(){return this.length},n.fn.andSelf=n.fn.addBack,"function"==typeof define&&define.amd&&define("jquery",[],function(){return n});var Kc=a.jQuery,Lc=a.$;return n.noConflict=function(b){return a.$===n&&(a.$=Lc),b&&a.jQuery===n&&(a.jQuery=Kc),n},typeof b===U&&(a.jQuery=a.$=n),n});

/*! jQuery UI - v1.11.4 - 2015-03-11
* http://jqueryui.com
* Includes: core.js, widget.js, mouse.js, position.js, accordion.js, autocomplete.js, button.js, datepicker.js, dialog.js, draggable.js, droppable.js, effect.js, effect-blind.js, effect-bounce.js, effect-clip.js, effect-drop.js, effect-explode.js, effect-fade.js, effect-fold.js, effect-highlight.js, effect-puff.js, effect-pulsate.js, effect-scale.js, effect-shake.js, effect-size.js, effect-slide.js, effect-transfer.js, menu.js, progressbar.js, resizable.js, selectable.js, selectmenu.js, slider.js, sortable.js, spinner.js, tabs.js, tooltip.js
* Copyright 2015 jQuery Foundation and other contributors; Licensed MIT */

(function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)})(function(e){function t(t,s){var n,a,o,r=t.nodeName.toLowerCase();return"area"===r?(n=t.parentNode,a=n.name,t.href&&a&&"map"===n.nodeName.toLowerCase()?(o=e("img[usemap='#"+a+"']")[0],!!o&&i(o)):!1):(/^(input|select|textarea|button|object)$/.test(r)?!t.disabled:"a"===r?t.href||s:s)&&i(t)}function i(t){return e.expr.filters.visible(t)&&!e(t).parents().addBack().filter(function(){return"hidden"===e.css(this,"visibility")}).length}function s(e){for(var t,i;e.length&&e[0]!==document;){if(t=e.css("position"),("absolute"===t||"relative"===t||"fixed"===t)&&(i=parseInt(e.css("zIndex"),10),!isNaN(i)&&0!==i))return i;e=e.parent()}return 0}function n(){this._curInst=null,this._keyEvent=!1,this._disabledInputs=[],this._datepickerShowing=!1,this._inDialog=!1,this._mainDivId="ui-datepicker-div",this._inlineClass="ui-datepicker-inline",this._appendClass="ui-datepicker-append",this._triggerClass="ui-datepicker-trigger",this._dialogClass="ui-datepicker-dialog",this._disableClass="ui-datepicker-disabled",this._unselectableClass="ui-datepicker-unselectable",this._currentClass="ui-datepicker-current-day",this._dayOverClass="ui-datepicker-days-cell-over",this.regional=[],this.regional[""]={closeText:"Done",prevText:"Prev",nextText:"Next",currentText:"Today",monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayNamesMin:["Su","Mo","Tu","We","Th","Fr","Sa"],weekHeader:"Wk",dateFormat:"mm/dd/yy",firstDay:0,isRTL:!1,showMonthAfterYear:!1,yearSuffix:""},this._defaults={showOn:"focus",showAnim:"fadeIn",showOptions:{},defaultDate:null,appendText:"",buttonText:"...",buttonImage:"",buttonImageOnly:!1,hideIfNoPrevNext:!1,navigationAsDateFormat:!1,gotoCurrent:!1,changeMonth:!1,changeYear:!1,yearRange:"c-10:c+10",showOtherMonths:!1,selectOtherMonths:!1,showWeek:!1,calculateWeek:this.iso8601Week,shortYearCutoff:"+10",minDate:null,maxDate:null,duration:"fast",beforeShowDay:null,beforeShow:null,onSelect:null,onChangeMonthYear:null,onClose:null,numberOfMonths:1,showCurrentAtPos:0,stepMonths:1,stepBigMonths:12,altField:"",altFormat:"",constrainInput:!0,showButtonPanel:!1,autoSize:!1,disabled:!1},e.extend(this._defaults,this.regional[""]),this.regional.en=e.extend(!0,{},this.regional[""]),this.regional["en-US"]=e.extend(!0,{},this.regional.en),this.dpDiv=a(e("<div id='"+this._mainDivId+"' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"))}function a(t){var i="button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";return t.delegate(i,"mouseout",function(){e(this).removeClass("ui-state-hover"),-1!==this.className.indexOf("ui-datepicker-prev")&&e(this).removeClass("ui-datepicker-prev-hover"),-1!==this.className.indexOf("ui-datepicker-next")&&e(this).removeClass("ui-datepicker-next-hover")}).delegate(i,"mouseover",o)}function o(){e.datepicker._isDisabledDatepicker(v.inline?v.dpDiv.parent()[0]:v.input[0])||(e(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover"),e(this).addClass("ui-state-hover"),-1!==this.className.indexOf("ui-datepicker-prev")&&e(this).addClass("ui-datepicker-prev-hover"),-1!==this.className.indexOf("ui-datepicker-next")&&e(this).addClass("ui-datepicker-next-hover"))}function r(t,i){e.extend(t,i);for(var s in i)null==i[s]&&(t[s]=i[s]);return t}function h(e){return function(){var t=this.element.val();e.apply(this,arguments),this._refresh(),t!==this.element.val()&&this._trigger("change")}}e.ui=e.ui||{},e.extend(e.ui,{version:"1.11.4",keyCode:{BACKSPACE:8,COMMA:188,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,LEFT:37,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SPACE:32,TAB:9,UP:38}}),e.fn.extend({scrollParent:function(t){var i=this.css("position"),s="absolute"===i,n=t?/(auto|scroll|hidden)/:/(auto|scroll)/,a=this.parents().filter(function(){var t=e(this);return s&&"static"===t.css("position")?!1:n.test(t.css("overflow")+t.css("overflow-y")+t.css("overflow-x"))}).eq(0);return"fixed"!==i&&a.length?a:e(this[0].ownerDocument||document)},uniqueId:function(){var e=0;return function(){return this.each(function(){this.id||(this.id="ui-id-"+ ++e)})}}(),removeUniqueId:function(){return this.each(function(){/^ui-id-\d+$/.test(this.id)&&e(this).removeAttr("id")})}}),e.extend(e.expr[":"],{data:e.expr.createPseudo?e.expr.createPseudo(function(t){return function(i){return!!e.data(i,t)}}):function(t,i,s){return!!e.data(t,s[3])},focusable:function(i){return t(i,!isNaN(e.attr(i,"tabindex")))},tabbable:function(i){var s=e.attr(i,"tabindex"),n=isNaN(s);return(n||s>=0)&&t(i,!n)}}),e("<a>").outerWidth(1).jquery||e.each(["Width","Height"],function(t,i){function s(t,i,s,a){return e.each(n,function(){i-=parseFloat(e.css(t,"padding"+this))||0,s&&(i-=parseFloat(e.css(t,"border"+this+"Width"))||0),a&&(i-=parseFloat(e.css(t,"margin"+this))||0)}),i}var n="Width"===i?["Left","Right"]:["Top","Bottom"],a=i.toLowerCase(),o={innerWidth:e.fn.innerWidth,innerHeight:e.fn.innerHeight,outerWidth:e.fn.outerWidth,outerHeight:e.fn.outerHeight};e.fn["inner"+i]=function(t){return void 0===t?o["inner"+i].call(this):this.each(function(){e(this).css(a,s(this,t)+"px")})},e.fn["outer"+i]=function(t,n){return"number"!=typeof t?o["outer"+i].call(this,t):this.each(function(){e(this).css(a,s(this,t,!0,n)+"px")})}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e("<a>").data("a-b","a").removeData("a-b").data("a-b")&&(e.fn.removeData=function(t){return function(i){return arguments.length?t.call(this,e.camelCase(i)):t.call(this)}}(e.fn.removeData)),e.ui.ie=!!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()),e.fn.extend({focus:function(t){return function(i,s){return"number"==typeof i?this.each(function(){var t=this;setTimeout(function(){e(t).focus(),s&&s.call(t)},i)}):t.apply(this,arguments)}}(e.fn.focus),disableSelection:function(){var e="onselectstart"in document.createElement("div")?"selectstart":"mousedown";return function(){return this.bind(e+".ui-disableSelection",function(e){e.preventDefault()})}}(),enableSelection:function(){return this.unbind(".ui-disableSelection")},zIndex:function(t){if(void 0!==t)return this.css("zIndex",t);if(this.length)for(var i,s,n=e(this[0]);n.length&&n[0]!==document;){if(i=n.css("position"),("absolute"===i||"relative"===i||"fixed"===i)&&(s=parseInt(n.css("zIndex"),10),!isNaN(s)&&0!==s))return s;n=n.parent()}return 0}}),e.ui.plugin={add:function(t,i,s){var n,a=e.ui[t].prototype;for(n in s)a.plugins[n]=a.plugins[n]||[],a.plugins[n].push([i,s[n]])},call:function(e,t,i,s){var n,a=e.plugins[t];if(a&&(s||e.element[0].parentNode&&11!==e.element[0].parentNode.nodeType))for(n=0;a.length>n;n++)e.options[a[n][0]]&&a[n][1].apply(e.element,i)}};var l=0,u=Array.prototype.slice;e.cleanData=function(t){return function(i){var s,n,a;for(a=0;null!=(n=i[a]);a++)try{s=e._data(n,"events"),s&&s.remove&&e(n).triggerHandler("remove")}catch(o){}t(i)}}(e.cleanData),e.widget=function(t,i,s){var n,a,o,r,h={},l=t.split(".")[0];return t=t.split(".")[1],n=l+"-"+t,s||(s=i,i=e.Widget),e.expr[":"][n.toLowerCase()]=function(t){return!!e.data(t,n)},e[l]=e[l]||{},a=e[l][t],o=e[l][t]=function(e,t){return this._createWidget?(arguments.length&&this._createWidget(e,t),void 0):new o(e,t)},e.extend(o,a,{version:s.version,_proto:e.extend({},s),_childConstructors:[]}),r=new i,r.options=e.widget.extend({},r.options),e.each(s,function(t,s){return e.isFunction(s)?(h[t]=function(){var e=function(){return i.prototype[t].apply(this,arguments)},n=function(e){return i.prototype[t].apply(this,e)};return function(){var t,i=this._super,a=this._superApply;return this._super=e,this._superApply=n,t=s.apply(this,arguments),this._super=i,this._superApply=a,t}}(),void 0):(h[t]=s,void 0)}),o.prototype=e.widget.extend(r,{widgetEventPrefix:a?r.widgetEventPrefix||t:t},h,{constructor:o,namespace:l,widgetName:t,widgetFullName:n}),a?(e.each(a._childConstructors,function(t,i){var s=i.prototype;e.widget(s.namespace+"."+s.widgetName,o,i._proto)}),delete a._childConstructors):i._childConstructors.push(o),e.widget.bridge(t,o),o},e.widget.extend=function(t){for(var i,s,n=u.call(arguments,1),a=0,o=n.length;o>a;a++)for(i in n[a])s=n[a][i],n[a].hasOwnProperty(i)&&void 0!==s&&(t[i]=e.isPlainObject(s)?e.isPlainObject(t[i])?e.widget.extend({},t[i],s):e.widget.extend({},s):s);return t},e.widget.bridge=function(t,i){var s=i.prototype.widgetFullName||t;e.fn[t]=function(n){var a="string"==typeof n,o=u.call(arguments,1),r=this;return a?this.each(function(){var i,a=e.data(this,s);return"instance"===n?(r=a,!1):a?e.isFunction(a[n])&&"_"!==n.charAt(0)?(i=a[n].apply(a,o),i!==a&&void 0!==i?(r=i&&i.jquery?r.pushStack(i.get()):i,!1):void 0):e.error("no such method '"+n+"' for "+t+" widget instance"):e.error("cannot call methods on "+t+" prior to initialization; "+"attempted to call method '"+n+"'")}):(o.length&&(n=e.widget.extend.apply(null,[n].concat(o))),this.each(function(){var t=e.data(this,s);t?(t.option(n||{}),t._init&&t._init()):e.data(this,s,new i(n,this))})),r}},e.Widget=function(){},e.Widget._childConstructors=[],e.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:!1,create:null},_createWidget:function(t,i){i=e(i||this.defaultElement||this)[0],this.element=e(i),this.uuid=l++,this.eventNamespace="."+this.widgetName+this.uuid,this.bindings=e(),this.hoverable=e(),this.focusable=e(),i!==this&&(e.data(i,this.widgetFullName,this),this._on(!0,this.element,{remove:function(e){e.target===i&&this.destroy()}}),this.document=e(i.style?i.ownerDocument:i.document||i),this.window=e(this.document[0].defaultView||this.document[0].parentWindow)),this.options=e.widget.extend({},this.options,this._getCreateOptions(),t),this._create(),this._trigger("create",null,this._getCreateEventData()),this._init()},_getCreateOptions:e.noop,_getCreateEventData:e.noop,_create:e.noop,_init:e.noop,destroy:function(){this._destroy(),this.element.unbind(this.eventNamespace).removeData(this.widgetFullName).removeData(e.camelCase(this.widgetFullName)),this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled "+"ui-state-disabled"),this.bindings.unbind(this.eventNamespace),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")},_destroy:e.noop,widget:function(){return this.element},option:function(t,i){var s,n,a,o=t;if(0===arguments.length)return e.widget.extend({},this.options);if("string"==typeof t)if(o={},s=t.split("."),t=s.shift(),s.length){for(n=o[t]=e.widget.extend({},this.options[t]),a=0;s.length-1>a;a++)n[s[a]]=n[s[a]]||{},n=n[s[a]];if(t=s.pop(),1===arguments.length)return void 0===n[t]?null:n[t];n[t]=i}else{if(1===arguments.length)return void 0===this.options[t]?null:this.options[t];o[t]=i}return this._setOptions(o),this},_setOptions:function(e){var t;for(t in e)this._setOption(t,e[t]);return this},_setOption:function(e,t){return this.options[e]=t,"disabled"===e&&(this.widget().toggleClass(this.widgetFullName+"-disabled",!!t),t&&(this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus"))),this},enable:function(){return this._setOptions({disabled:!1})},disable:function(){return this._setOptions({disabled:!0})},_on:function(t,i,s){var n,a=this;"boolean"!=typeof t&&(s=i,i=t,t=!1),s?(i=n=e(i),this.bindings=this.bindings.add(i)):(s=i,i=this.element,n=this.widget()),e.each(s,function(s,o){function r(){return t||a.options.disabled!==!0&&!e(this).hasClass("ui-state-disabled")?("string"==typeof o?a[o]:o).apply(a,arguments):void 0}"string"!=typeof o&&(r.guid=o.guid=o.guid||r.guid||e.guid++);var h=s.match(/^([\w:-]*)\s*(.*)$/),l=h[1]+a.eventNamespace,u=h[2];u?n.delegate(u,l,r):i.bind(l,r)})},_off:function(t,i){i=(i||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace,t.unbind(i).undelegate(i),this.bindings=e(this.bindings.not(t).get()),this.focusable=e(this.focusable.not(t).get()),this.hoverable=e(this.hoverable.not(t).get())},_delay:function(e,t){function i(){return("string"==typeof e?s[e]:e).apply(s,arguments)}var s=this;return setTimeout(i,t||0)},_hoverable:function(t){this.hoverable=this.hoverable.add(t),this._on(t,{mouseenter:function(t){e(t.currentTarget).addClass("ui-state-hover")},mouseleave:function(t){e(t.currentTarget).removeClass("ui-state-hover")}})},_focusable:function(t){this.focusable=this.focusable.add(t),this._on(t,{focusin:function(t){e(t.currentTarget).addClass("ui-state-focus")},focusout:function(t){e(t.currentTarget).removeClass("ui-state-focus")}})},_trigger:function(t,i,s){var n,a,o=this.options[t];if(s=s||{},i=e.Event(i),i.type=(t===this.widgetEventPrefix?t:this.widgetEventPrefix+t).toLowerCase(),i.target=this.element[0],a=i.originalEvent)for(n in a)n in i||(i[n]=a[n]);return this.element.trigger(i,s),!(e.isFunction(o)&&o.apply(this.element[0],[i].concat(s))===!1||i.isDefaultPrevented())}},e.each({show:"fadeIn",hide:"fadeOut"},function(t,i){e.Widget.prototype["_"+t]=function(s,n,a){"string"==typeof n&&(n={effect:n});var o,r=n?n===!0||"number"==typeof n?i:n.effect||i:t;n=n||{},"number"==typeof n&&(n={duration:n}),o=!e.isEmptyObject(n),n.complete=a,n.delay&&s.delay(n.delay),o&&e.effects&&e.effects.effect[r]?s[t](n):r!==t&&s[r]?s[r](n.duration,n.easing,a):s.queue(function(i){e(this)[t](),a&&a.call(s[0]),i()})}}),e.widget;var d=!1;e(document).mouseup(function(){d=!1}),e.widget("ui.mouse",{version:"1.11.4",options:{cancel:"input,textarea,button,select,option",distance:1,delay:0},_mouseInit:function(){var t=this;this.element.bind("mousedown."+this.widgetName,function(e){return t._mouseDown(e)}).bind("click."+this.widgetName,function(i){return!0===e.data(i.target,t.widgetName+".preventClickEvent")?(e.removeData(i.target,t.widgetName+".preventClickEvent"),i.stopImmediatePropagation(),!1):void 0}),this.started=!1},_mouseDestroy:function(){this.element.unbind("."+this.widgetName),this._mouseMoveDelegate&&this.document.unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate)},_mouseDown:function(t){if(!d){this._mouseMoved=!1,this._mouseStarted&&this._mouseUp(t),this._mouseDownEvent=t;var i=this,s=1===t.which,n="string"==typeof this.options.cancel&&t.target.nodeName?e(t.target).closest(this.options.cancel).length:!1;return s&&!n&&this._mouseCapture(t)?(this.mouseDelayMet=!this.options.delay,this.mouseDelayMet||(this._mouseDelayTimer=setTimeout(function(){i.mouseDelayMet=!0},this.options.delay)),this._mouseDistanceMet(t)&&this._mouseDelayMet(t)&&(this._mouseStarted=this._mouseStart(t)!==!1,!this._mouseStarted)?(t.preventDefault(),!0):(!0===e.data(t.target,this.widgetName+".preventClickEvent")&&e.removeData(t.target,this.widgetName+".preventClickEvent"),this._mouseMoveDelegate=function(e){return i._mouseMove(e)},this._mouseUpDelegate=function(e){return i._mouseUp(e)},this.document.bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate),t.preventDefault(),d=!0,!0)):!0}},_mouseMove:function(t){if(this._mouseMoved){if(e.ui.ie&&(!document.documentMode||9>document.documentMode)&&!t.button)return this._mouseUp(t);if(!t.which)return this._mouseUp(t)}return(t.which||t.button)&&(this._mouseMoved=!0),this._mouseStarted?(this._mouseDrag(t),t.preventDefault()):(this._mouseDistanceMet(t)&&this._mouseDelayMet(t)&&(this._mouseStarted=this._mouseStart(this._mouseDownEvent,t)!==!1,this._mouseStarted?this._mouseDrag(t):this._mouseUp(t)),!this._mouseStarted)},_mouseUp:function(t){return this.document.unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate),this._mouseStarted&&(this._mouseStarted=!1,t.target===this._mouseDownEvent.target&&e.data(t.target,this.widgetName+".preventClickEvent",!0),this._mouseStop(t)),d=!1,!1},_mouseDistanceMet:function(e){return Math.max(Math.abs(this._mouseDownEvent.pageX-e.pageX),Math.abs(this._mouseDownEvent.pageY-e.pageY))>=this.options.distance},_mouseDelayMet:function(){return this.mouseDelayMet},_mouseStart:function(){},_mouseDrag:function(){},_mouseStop:function(){},_mouseCapture:function(){return!0}}),function(){function t(e,t,i){return[parseFloat(e[0])*(p.test(e[0])?t/100:1),parseFloat(e[1])*(p.test(e[1])?i/100:1)]}function i(t,i){return parseInt(e.css(t,i),10)||0}function s(t){var i=t[0];return 9===i.nodeType?{width:t.width(),height:t.height(),offset:{top:0,left:0}}:e.isWindow(i)?{width:t.width(),height:t.height(),offset:{top:t.scrollTop(),left:t.scrollLeft()}}:i.preventDefault?{width:0,height:0,offset:{top:i.pageY,left:i.pageX}}:{width:t.outerWidth(),height:t.outerHeight(),offset:t.offset()}}e.ui=e.ui||{};var n,a,o=Math.max,r=Math.abs,h=Math.round,l=/left|center|right/,u=/top|center|bottom/,d=/[\+\-]\d+(\.[\d]+)?%?/,c=/^\w+/,p=/%$/,f=e.fn.position;e.position={scrollbarWidth:function(){if(void 0!==n)return n;var t,i,s=e("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),a=s.children()[0];return e("body").append(s),t=a.offsetWidth,s.css("overflow","scroll"),i=a.offsetWidth,t===i&&(i=s[0].clientWidth),s.remove(),n=t-i},getScrollInfo:function(t){var i=t.isWindow||t.isDocument?"":t.element.css("overflow-x"),s=t.isWindow||t.isDocument?"":t.element.css("overflow-y"),n="scroll"===i||"auto"===i&&t.width<t.element[0].scrollWidth,a="scroll"===s||"auto"===s&&t.height<t.element[0].scrollHeight;return{width:a?e.position.scrollbarWidth():0,height:n?e.position.scrollbarWidth():0}},getWithinInfo:function(t){var i=e(t||window),s=e.isWindow(i[0]),n=!!i[0]&&9===i[0].nodeType;return{element:i,isWindow:s,isDocument:n,offset:i.offset()||{left:0,top:0},scrollLeft:i.scrollLeft(),scrollTop:i.scrollTop(),width:s||n?i.width():i.outerWidth(),height:s||n?i.height():i.outerHeight()}}},e.fn.position=function(n){if(!n||!n.of)return f.apply(this,arguments);n=e.extend({},n);var p,m,g,v,y,b,_=e(n.of),x=e.position.getWithinInfo(n.within),w=e.position.getScrollInfo(x),k=(n.collision||"flip").split(" "),T={};return b=s(_),_[0].preventDefault&&(n.at="left top"),m=b.width,g=b.height,v=b.offset,y=e.extend({},v),e.each(["my","at"],function(){var e,t,i=(n[this]||"").split(" ");1===i.length&&(i=l.test(i[0])?i.concat(["center"]):u.test(i[0])?["center"].concat(i):["center","center"]),i[0]=l.test(i[0])?i[0]:"center",i[1]=u.test(i[1])?i[1]:"center",e=d.exec(i[0]),t=d.exec(i[1]),T[this]=[e?e[0]:0,t?t[0]:0],n[this]=[c.exec(i[0])[0],c.exec(i[1])[0]]}),1===k.length&&(k[1]=k[0]),"right"===n.at[0]?y.left+=m:"center"===n.at[0]&&(y.left+=m/2),"bottom"===n.at[1]?y.top+=g:"center"===n.at[1]&&(y.top+=g/2),p=t(T.at,m,g),y.left+=p[0],y.top+=p[1],this.each(function(){var s,l,u=e(this),d=u.outerWidth(),c=u.outerHeight(),f=i(this,"marginLeft"),b=i(this,"marginTop"),D=d+f+i(this,"marginRight")+w.width,S=c+b+i(this,"marginBottom")+w.height,M=e.extend({},y),C=t(T.my,u.outerWidth(),u.outerHeight());"right"===n.my[0]?M.left-=d:"center"===n.my[0]&&(M.left-=d/2),"bottom"===n.my[1]?M.top-=c:"center"===n.my[1]&&(M.top-=c/2),M.left+=C[0],M.top+=C[1],a||(M.left=h(M.left),M.top=h(M.top)),s={marginLeft:f,marginTop:b},e.each(["left","top"],function(t,i){e.ui.position[k[t]]&&e.ui.position[k[t]][i](M,{targetWidth:m,targetHeight:g,elemWidth:d,elemHeight:c,collisionPosition:s,collisionWidth:D,collisionHeight:S,offset:[p[0]+C[0],p[1]+C[1]],my:n.my,at:n.at,within:x,elem:u})}),n.using&&(l=function(e){var t=v.left-M.left,i=t+m-d,s=v.top-M.top,a=s+g-c,h={target:{element:_,left:v.left,top:v.top,width:m,height:g},element:{element:u,left:M.left,top:M.top,width:d,height:c},horizontal:0>i?"left":t>0?"right":"center",vertical:0>a?"top":s>0?"bottom":"middle"};d>m&&m>r(t+i)&&(h.horizontal="center"),c>g&&g>r(s+a)&&(h.vertical="middle"),h.important=o(r(t),r(i))>o(r(s),r(a))?"horizontal":"vertical",n.using.call(this,e,h)}),u.offset(e.extend(M,{using:l}))})},e.ui.position={fit:{left:function(e,t){var i,s=t.within,n=s.isWindow?s.scrollLeft:s.offset.left,a=s.width,r=e.left-t.collisionPosition.marginLeft,h=n-r,l=r+t.collisionWidth-a-n;t.collisionWidth>a?h>0&&0>=l?(i=e.left+h+t.collisionWidth-a-n,e.left+=h-i):e.left=l>0&&0>=h?n:h>l?n+a-t.collisionWidth:n:h>0?e.left+=h:l>0?e.left-=l:e.left=o(e.left-r,e.left)},top:function(e,t){var i,s=t.within,n=s.isWindow?s.scrollTop:s.offset.top,a=t.within.height,r=e.top-t.collisionPosition.marginTop,h=n-r,l=r+t.collisionHeight-a-n;t.collisionHeight>a?h>0&&0>=l?(i=e.top+h+t.collisionHeight-a-n,e.top+=h-i):e.top=l>0&&0>=h?n:h>l?n+a-t.collisionHeight:n:h>0?e.top+=h:l>0?e.top-=l:e.top=o(e.top-r,e.top)}},flip:{left:function(e,t){var i,s,n=t.within,a=n.offset.left+n.scrollLeft,o=n.width,h=n.isWindow?n.scrollLeft:n.offset.left,l=e.left-t.collisionPosition.marginLeft,u=l-h,d=l+t.collisionWidth-o-h,c="left"===t.my[0]?-t.elemWidth:"right"===t.my[0]?t.elemWidth:0,p="left"===t.at[0]?t.targetWidth:"right"===t.at[0]?-t.targetWidth:0,f=-2*t.offset[0];0>u?(i=e.left+c+p+f+t.collisionWidth-o-a,(0>i||r(u)>i)&&(e.left+=c+p+f)):d>0&&(s=e.left-t.collisionPosition.marginLeft+c+p+f-h,(s>0||d>r(s))&&(e.left+=c+p+f))},top:function(e,t){var i,s,n=t.within,a=n.offset.top+n.scrollTop,o=n.height,h=n.isWindow?n.scrollTop:n.offset.top,l=e.top-t.collisionPosition.marginTop,u=l-h,d=l+t.collisionHeight-o-h,c="top"===t.my[1],p=c?-t.elemHeight:"bottom"===t.my[1]?t.elemHeight:0,f="top"===t.at[1]?t.targetHeight:"bottom"===t.at[1]?-t.targetHeight:0,m=-2*t.offset[1];0>u?(s=e.top+p+f+m+t.collisionHeight-o-a,(0>s||r(u)>s)&&(e.top+=p+f+m)):d>0&&(i=e.top-t.collisionPosition.marginTop+p+f+m-h,(i>0||d>r(i))&&(e.top+=p+f+m))}},flipfit:{left:function(){e.ui.position.flip.left.apply(this,arguments),e.ui.position.fit.left.apply(this,arguments)},top:function(){e.ui.position.flip.top.apply(this,arguments),e.ui.position.fit.top.apply(this,arguments)}}},function(){var t,i,s,n,o,r=document.getElementsByTagName("body")[0],h=document.createElement("div");t=document.createElement(r?"div":"body"),s={visibility:"hidden",width:0,height:0,border:0,margin:0,background:"none"},r&&e.extend(s,{position:"absolute",left:"-1000px",top:"-1000px"});for(o in s)t.style[o]=s[o];t.appendChild(h),i=r||document.documentElement,i.insertBefore(t,i.firstChild),h.style.cssText="position: absolute; left: 10.7432222px;",n=e(h).offset().left,a=n>10&&11>n,t.innerHTML="",i.removeChild(t)}()}(),e.ui.position,e.widget("ui.accordion",{version:"1.11.4",options:{active:0,animate:{},collapsible:!1,event:"click",header:"> li > :first-child,> :not(li):even",heightStyle:"auto",icons:{activeHeader:"ui-icon-triangle-1-s",header:"ui-icon-triangle-1-e"},activate:null,beforeActivate:null},hideProps:{borderTopWidth:"hide",borderBottomWidth:"hide",paddingTop:"hide",paddingBottom:"hide",height:"hide"},showProps:{borderTopWidth:"show",borderBottomWidth:"show",paddingTop:"show",paddingBottom:"show",height:"show"},_create:function(){var t=this.options;this.prevShow=this.prevHide=e(),this.element.addClass("ui-accordion ui-widget ui-helper-reset").attr("role","tablist"),t.collapsible||t.active!==!1&&null!=t.active||(t.active=0),this._processPanels(),0>t.active&&(t.active+=this.headers.length),this._refresh()},_getCreateEventData:function(){return{header:this.active,panel:this.active.length?this.active.next():e()}},_createIcons:function(){var t=this.options.icons;t&&(e("<span>").addClass("ui-accordion-header-icon ui-icon "+t.header).prependTo(this.headers),this.active.children(".ui-accordion-header-icon").removeClass(t.header).addClass(t.activeHeader),this.headers.addClass("ui-accordion-icons"))},_destroyIcons:function(){this.headers.removeClass("ui-accordion-icons").children(".ui-accordion-header-icon").remove()},_destroy:function(){var e;this.element.removeClass("ui-accordion ui-widget ui-helper-reset").removeAttr("role"),this.headers.removeClass("ui-accordion-header ui-accordion-header-active ui-state-default ui-corner-all ui-state-active ui-state-disabled ui-corner-top").removeAttr("role").removeAttr("aria-expanded").removeAttr("aria-selected").removeAttr("aria-controls").removeAttr("tabIndex").removeUniqueId(),this._destroyIcons(),e=this.headers.next().removeClass("ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content ui-accordion-content-active ui-state-disabled").css("display","").removeAttr("role").removeAttr("aria-hidden").removeAttr("aria-labelledby").removeUniqueId(),"content"!==this.options.heightStyle&&e.css("height","")},_setOption:function(e,t){return"active"===e?(this._activate(t),void 0):("event"===e&&(this.options.event&&this._off(this.headers,this.options.event),this._setupEvents(t)),this._super(e,t),"collapsible"!==e||t||this.options.active!==!1||this._activate(0),"icons"===e&&(this._destroyIcons(),t&&this._createIcons()),"disabled"===e&&(this.element.toggleClass("ui-state-disabled",!!t).attr("aria-disabled",t),this.headers.add(this.headers.next()).toggleClass("ui-state-disabled",!!t)),void 0)},_keydown:function(t){if(!t.altKey&&!t.ctrlKey){var i=e.ui.keyCode,s=this.headers.length,n=this.headers.index(t.target),a=!1;switch(t.keyCode){case i.RIGHT:case i.DOWN:a=this.headers[(n+1)%s];break;case i.LEFT:case i.UP:a=this.headers[(n-1+s)%s];break;case i.SPACE:case i.ENTER:this._eventHandler(t);break;case i.HOME:a=this.headers[0];break;case i.END:a=this.headers[s-1]}a&&(e(t.target).attr("tabIndex",-1),e(a).attr("tabIndex",0),a.focus(),t.preventDefault())}},_panelKeyDown:function(t){t.keyCode===e.ui.keyCode.UP&&t.ctrlKey&&e(t.currentTarget).prev().focus()},refresh:function(){var t=this.options;this._processPanels(),t.active===!1&&t.collapsible===!0||!this.headers.length?(t.active=!1,this.active=e()):t.active===!1?this._activate(0):this.active.length&&!e.contains(this.element[0],this.active[0])?this.headers.length===this.headers.find(".ui-state-disabled").length?(t.active=!1,this.active=e()):this._activate(Math.max(0,t.active-1)):t.active=this.headers.index(this.active),this._destroyIcons(),this._refresh()},_processPanels:function(){var e=this.headers,t=this.panels;this.headers=this.element.find(this.options.header).addClass("ui-accordion-header ui-state-default ui-corner-all"),this.panels=this.headers.next().addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").filter(":not(.ui-accordion-content-active)").hide(),t&&(this._off(e.not(this.headers)),this._off(t.not(this.panels)))},_refresh:function(){var t,i=this.options,s=i.heightStyle,n=this.element.parent();this.active=this._findActive(i.active).addClass("ui-accordion-header-active ui-state-active ui-corner-top").removeClass("ui-corner-all"),this.active.next().addClass("ui-accordion-content-active").show(),this.headers.attr("role","tab").each(function(){var t=e(this),i=t.uniqueId().attr("id"),s=t.next(),n=s.uniqueId().attr("id");t.attr("aria-controls",n),s.attr("aria-labelledby",i)}).next().attr("role","tabpanel"),this.headers.not(this.active).attr({"aria-selected":"false","aria-expanded":"false",tabIndex:-1}).next().attr({"aria-hidden":"true"}).hide(),this.active.length?this.active.attr({"aria-selected":"true","aria-expanded":"true",tabIndex:0}).next().attr({"aria-hidden":"false"}):this.headers.eq(0).attr("tabIndex",0),this._createIcons(),this._setupEvents(i.event),"fill"===s?(t=n.height(),this.element.siblings(":visible").each(function(){var i=e(this),s=i.css("position");"absolute"!==s&&"fixed"!==s&&(t-=i.outerHeight(!0))}),this.headers.each(function(){t-=e(this).outerHeight(!0)}),this.headers.next().each(function(){e(this).height(Math.max(0,t-e(this).innerHeight()+e(this).height()))}).css("overflow","auto")):"auto"===s&&(t=0,this.headers.next().each(function(){t=Math.max(t,e(this).css("height","").height())}).height(t))},_activate:function(t){var i=this._findActive(t)[0];i!==this.active[0]&&(i=i||this.active[0],this._eventHandler({target:i,currentTarget:i,preventDefault:e.noop}))},_findActive:function(t){return"number"==typeof t?this.headers.eq(t):e()},_setupEvents:function(t){var i={keydown:"_keydown"};t&&e.each(t.split(" "),function(e,t){i[t]="_eventHandler"}),this._off(this.headers.add(this.headers.next())),this._on(this.headers,i),this._on(this.headers.next(),{keydown:"_panelKeyDown"}),this._hoverable(this.headers),this._focusable(this.headers)},_eventHandler:function(t){var i=this.options,s=this.active,n=e(t.currentTarget),a=n[0]===s[0],o=a&&i.collapsible,r=o?e():n.next(),h=s.next(),l={oldHeader:s,oldPanel:h,newHeader:o?e():n,newPanel:r};t.preventDefault(),a&&!i.collapsible||this._trigger("beforeActivate",t,l)===!1||(i.active=o?!1:this.headers.index(n),this.active=a?e():n,this._toggle(l),s.removeClass("ui-accordion-header-active ui-state-active"),i.icons&&s.children(".ui-accordion-header-icon").removeClass(i.icons.activeHeader).addClass(i.icons.header),a||(n.removeClass("ui-corner-all").addClass("ui-accordion-header-active ui-state-active ui-corner-top"),i.icons&&n.children(".ui-accordion-header-icon").removeClass(i.icons.header).addClass(i.icons.activeHeader),n.next().addClass("ui-accordion-content-active")))},_toggle:function(t){var i=t.newPanel,s=this.prevShow.length?this.prevShow:t.oldPanel;this.prevShow.add(this.prevHide).stop(!0,!0),this.prevShow=i,this.prevHide=s,this.options.animate?this._animate(i,s,t):(s.hide(),i.show(),this._toggleComplete(t)),s.attr({"aria-hidden":"true"}),s.prev().attr({"aria-selected":"false","aria-expanded":"false"}),i.length&&s.length?s.prev().attr({tabIndex:-1,"aria-expanded":"false"}):i.length&&this.headers.filter(function(){return 0===parseInt(e(this).attr("tabIndex"),10)}).attr("tabIndex",-1),i.attr("aria-hidden","false").prev().attr({"aria-selected":"true","aria-expanded":"true",tabIndex:0})},_animate:function(e,t,i){var s,n,a,o=this,r=0,h=e.css("box-sizing"),l=e.length&&(!t.length||e.index()<t.index()),u=this.options.animate||{},d=l&&u.down||u,c=function(){o._toggleComplete(i)};return"number"==typeof d&&(a=d),"string"==typeof d&&(n=d),n=n||d.easing||u.easing,a=a||d.duration||u.duration,t.length?e.length?(s=e.show().outerHeight(),t.animate(this.hideProps,{duration:a,easing:n,step:function(e,t){t.now=Math.round(e)}}),e.hide().animate(this.showProps,{duration:a,easing:n,complete:c,step:function(e,i){i.now=Math.round(e),"height"!==i.prop?"content-box"===h&&(r+=i.now):"content"!==o.options.heightStyle&&(i.now=Math.round(s-t.outerHeight()-r),r=0)}}),void 0):t.animate(this.hideProps,a,n,c):e.animate(this.showProps,a,n,c)},_toggleComplete:function(e){var t=e.oldPanel;t.removeClass("ui-accordion-content-active").prev().removeClass("ui-corner-top").addClass("ui-corner-all"),t.length&&(t.parent()[0].className=t.parent()[0].className),this._trigger("activate",null,e)}}),e.widget("ui.menu",{version:"1.11.4",defaultElement:"<ul>",delay:300,options:{icons:{submenu:"ui-icon-carat-1-e"},items:"> *",menus:"ul",position:{my:"left-1 top",at:"right top"},role:"menu",blur:null,focus:null,select:null},_create:function(){this.activeMenu=this.element,this.mouseHandled=!1,this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content").toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length).attr({role:this.options.role,tabIndex:0}),this.options.disabled&&this.element.addClass("ui-state-disabled").attr("aria-disabled","true"),this._on({"mousedown .ui-menu-item":function(e){e.preventDefault()},"click .ui-menu-item":function(t){var i=e(t.target);!this.mouseHandled&&i.not(".ui-state-disabled").length&&(this.select(t),t.isPropagationStopped()||(this.mouseHandled=!0),i.has(".ui-menu").length?this.expand(t):!this.element.is(":focus")&&e(this.document[0].activeElement).closest(".ui-menu").length&&(this.element.trigger("focus",[!0]),this.active&&1===this.active.parents(".ui-menu").length&&clearTimeout(this.timer)))},"mouseenter .ui-menu-item":function(t){if(!this.previousFilter){var i=e(t.currentTarget);
i.siblings(".ui-state-active").removeClass("ui-state-active"),this.focus(t,i)}},mouseleave:"collapseAll","mouseleave .ui-menu":"collapseAll",focus:function(e,t){var i=this.active||this.element.find(this.options.items).eq(0);t||this.focus(e,i)},blur:function(t){this._delay(function(){e.contains(this.element[0],this.document[0].activeElement)||this.collapseAll(t)})},keydown:"_keydown"}),this.refresh(),this._on(this.document,{click:function(e){this._closeOnDocumentClick(e)&&this.collapseAll(e),this.mouseHandled=!1}})},_destroy:function(){this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeClass("ui-menu ui-widget ui-widget-content ui-menu-icons ui-front").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(),this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").removeUniqueId().removeClass("ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function(){var t=e(this);t.data("ui-menu-submenu-carat")&&t.remove()}),this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")},_keydown:function(t){var i,s,n,a,o=!0;switch(t.keyCode){case e.ui.keyCode.PAGE_UP:this.previousPage(t);break;case e.ui.keyCode.PAGE_DOWN:this.nextPage(t);break;case e.ui.keyCode.HOME:this._move("first","first",t);break;case e.ui.keyCode.END:this._move("last","last",t);break;case e.ui.keyCode.UP:this.previous(t);break;case e.ui.keyCode.DOWN:this.next(t);break;case e.ui.keyCode.LEFT:this.collapse(t);break;case e.ui.keyCode.RIGHT:this.active&&!this.active.is(".ui-state-disabled")&&this.expand(t);break;case e.ui.keyCode.ENTER:case e.ui.keyCode.SPACE:this._activate(t);break;case e.ui.keyCode.ESCAPE:this.collapse(t);break;default:o=!1,s=this.previousFilter||"",n=String.fromCharCode(t.keyCode),a=!1,clearTimeout(this.filterTimer),n===s?a=!0:n=s+n,i=this._filterMenuItems(n),i=a&&-1!==i.index(this.active.next())?this.active.nextAll(".ui-menu-item"):i,i.length||(n=String.fromCharCode(t.keyCode),i=this._filterMenuItems(n)),i.length?(this.focus(t,i),this.previousFilter=n,this.filterTimer=this._delay(function(){delete this.previousFilter},1e3)):delete this.previousFilter}o&&t.preventDefault()},_activate:function(e){this.active.is(".ui-state-disabled")||(this.active.is("[aria-haspopup='true']")?this.expand(e):this.select(e))},refresh:function(){var t,i,s=this,n=this.options.icons.submenu,a=this.element.find(this.options.menus);this.element.toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length),a.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-front").hide().attr({role:this.options.role,"aria-hidden":"true","aria-expanded":"false"}).each(function(){var t=e(this),i=t.parent(),s=e("<span>").addClass("ui-menu-icon ui-icon "+n).data("ui-menu-submenu-carat",!0);i.attr("aria-haspopup","true").prepend(s),t.attr("aria-labelledby",i.attr("id"))}),t=a.add(this.element),i=t.find(this.options.items),i.not(".ui-menu-item").each(function(){var t=e(this);s._isDivider(t)&&t.addClass("ui-widget-content ui-menu-divider")}),i.not(".ui-menu-item, .ui-menu-divider").addClass("ui-menu-item").uniqueId().attr({tabIndex:-1,role:this._itemRole()}),i.filter(".ui-state-disabled").attr("aria-disabled","true"),this.active&&!e.contains(this.element[0],this.active[0])&&this.blur()},_itemRole:function(){return{menu:"menuitem",listbox:"option"}[this.options.role]},_setOption:function(e,t){"icons"===e&&this.element.find(".ui-menu-icon").removeClass(this.options.icons.submenu).addClass(t.submenu),"disabled"===e&&this.element.toggleClass("ui-state-disabled",!!t).attr("aria-disabled",t),this._super(e,t)},focus:function(e,t){var i,s;this.blur(e,e&&"focus"===e.type),this._scrollIntoView(t),this.active=t.first(),s=this.active.addClass("ui-state-focus").removeClass("ui-state-active"),this.options.role&&this.element.attr("aria-activedescendant",s.attr("id")),this.active.parent().closest(".ui-menu-item").addClass("ui-state-active"),e&&"keydown"===e.type?this._close():this.timer=this._delay(function(){this._close()},this.delay),i=t.children(".ui-menu"),i.length&&e&&/^mouse/.test(e.type)&&this._startOpening(i),this.activeMenu=t.parent(),this._trigger("focus",e,{item:t})},_scrollIntoView:function(t){var i,s,n,a,o,r;this._hasScroll()&&(i=parseFloat(e.css(this.activeMenu[0],"borderTopWidth"))||0,s=parseFloat(e.css(this.activeMenu[0],"paddingTop"))||0,n=t.offset().top-this.activeMenu.offset().top-i-s,a=this.activeMenu.scrollTop(),o=this.activeMenu.height(),r=t.outerHeight(),0>n?this.activeMenu.scrollTop(a+n):n+r>o&&this.activeMenu.scrollTop(a+n-o+r))},blur:function(e,t){t||clearTimeout(this.timer),this.active&&(this.active.removeClass("ui-state-focus"),this.active=null,this._trigger("blur",e,{item:this.active}))},_startOpening:function(e){clearTimeout(this.timer),"true"===e.attr("aria-hidden")&&(this.timer=this._delay(function(){this._close(),this._open(e)},this.delay))},_open:function(t){var i=e.extend({of:this.active},this.options.position);clearTimeout(this.timer),this.element.find(".ui-menu").not(t.parents(".ui-menu")).hide().attr("aria-hidden","true"),t.show().removeAttr("aria-hidden").attr("aria-expanded","true").position(i)},collapseAll:function(t,i){clearTimeout(this.timer),this.timer=this._delay(function(){var s=i?this.element:e(t&&t.target).closest(this.element.find(".ui-menu"));s.length||(s=this.element),this._close(s),this.blur(t),this.activeMenu=s},this.delay)},_close:function(e){e||(e=this.active?this.active.parent():this.element),e.find(".ui-menu").hide().attr("aria-hidden","true").attr("aria-expanded","false").end().find(".ui-state-active").not(".ui-state-focus").removeClass("ui-state-active")},_closeOnDocumentClick:function(t){return!e(t.target).closest(".ui-menu").length},_isDivider:function(e){return!/[^\-\u2014\u2013\s]/.test(e.text())},collapse:function(e){var t=this.active&&this.active.parent().closest(".ui-menu-item",this.element);t&&t.length&&(this._close(),this.focus(e,t))},expand:function(e){var t=this.active&&this.active.children(".ui-menu ").find(this.options.items).first();t&&t.length&&(this._open(t.parent()),this._delay(function(){this.focus(e,t)}))},next:function(e){this._move("next","first",e)},previous:function(e){this._move("prev","last",e)},isFirstItem:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length},isLastItem:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length},_move:function(e,t,i){var s;this.active&&(s="first"===e||"last"===e?this.active["first"===e?"prevAll":"nextAll"](".ui-menu-item").eq(-1):this.active[e+"All"](".ui-menu-item").eq(0)),s&&s.length&&this.active||(s=this.activeMenu.find(this.options.items)[t]()),this.focus(i,s)},nextPage:function(t){var i,s,n;return this.active?(this.isLastItem()||(this._hasScroll()?(s=this.active.offset().top,n=this.element.height(),this.active.nextAll(".ui-menu-item").each(function(){return i=e(this),0>i.offset().top-s-n}),this.focus(t,i)):this.focus(t,this.activeMenu.find(this.options.items)[this.active?"last":"first"]())),void 0):(this.next(t),void 0)},previousPage:function(t){var i,s,n;return this.active?(this.isFirstItem()||(this._hasScroll()?(s=this.active.offset().top,n=this.element.height(),this.active.prevAll(".ui-menu-item").each(function(){return i=e(this),i.offset().top-s+n>0}),this.focus(t,i)):this.focus(t,this.activeMenu.find(this.options.items).first())),void 0):(this.next(t),void 0)},_hasScroll:function(){return this.element.outerHeight()<this.element.prop("scrollHeight")},select:function(t){this.active=this.active||e(t.target).closest(".ui-menu-item");var i={item:this.active};this.active.has(".ui-menu").length||this.collapseAll(t,!0),this._trigger("select",t,i)},_filterMenuItems:function(t){var i=t.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&"),s=RegExp("^"+i,"i");return this.activeMenu.find(this.options.items).filter(".ui-menu-item").filter(function(){return s.test(e.trim(e(this).text()))})}}),e.widget("ui.autocomplete",{version:"1.11.4",defaultElement:"<input>",options:{appendTo:null,autoFocus:!1,delay:300,minLength:1,position:{my:"left top",at:"left bottom",collision:"none"},source:null,change:null,close:null,focus:null,open:null,response:null,search:null,select:null},requestIndex:0,pending:0,_create:function(){var t,i,s,n=this.element[0].nodeName.toLowerCase(),a="textarea"===n,o="input"===n;this.isMultiLine=a?!0:o?!1:this.element.prop("isContentEditable"),this.valueMethod=this.element[a||o?"val":"text"],this.isNewMenu=!0,this.element.addClass("ui-autocomplete-input").attr("autocomplete","off"),this._on(this.element,{keydown:function(n){if(this.element.prop("readOnly"))return t=!0,s=!0,i=!0,void 0;t=!1,s=!1,i=!1;var a=e.ui.keyCode;switch(n.keyCode){case a.PAGE_UP:t=!0,this._move("previousPage",n);break;case a.PAGE_DOWN:t=!0,this._move("nextPage",n);break;case a.UP:t=!0,this._keyEvent("previous",n);break;case a.DOWN:t=!0,this._keyEvent("next",n);break;case a.ENTER:this.menu.active&&(t=!0,n.preventDefault(),this.menu.select(n));break;case a.TAB:this.menu.active&&this.menu.select(n);break;case a.ESCAPE:this.menu.element.is(":visible")&&(this.isMultiLine||this._value(this.term),this.close(n),n.preventDefault());break;default:i=!0,this._searchTimeout(n)}},keypress:function(s){if(t)return t=!1,(!this.isMultiLine||this.menu.element.is(":visible"))&&s.preventDefault(),void 0;if(!i){var n=e.ui.keyCode;switch(s.keyCode){case n.PAGE_UP:this._move("previousPage",s);break;case n.PAGE_DOWN:this._move("nextPage",s);break;case n.UP:this._keyEvent("previous",s);break;case n.DOWN:this._keyEvent("next",s)}}},input:function(e){return s?(s=!1,e.preventDefault(),void 0):(this._searchTimeout(e),void 0)},focus:function(){this.selectedItem=null,this.previous=this._value()},blur:function(e){return this.cancelBlur?(delete this.cancelBlur,void 0):(clearTimeout(this.searching),this.close(e),this._change(e),void 0)}}),this._initSource(),this.menu=e("<ul>").addClass("ui-autocomplete ui-front").appendTo(this._appendTo()).menu({role:null}).hide().menu("instance"),this._on(this.menu.element,{mousedown:function(t){t.preventDefault(),this.cancelBlur=!0,this._delay(function(){delete this.cancelBlur});var i=this.menu.element[0];e(t.target).closest(".ui-menu-item").length||this._delay(function(){var t=this;this.document.one("mousedown",function(s){s.target===t.element[0]||s.target===i||e.contains(i,s.target)||t.close()})})},menufocus:function(t,i){var s,n;return this.isNewMenu&&(this.isNewMenu=!1,t.originalEvent&&/^mouse/.test(t.originalEvent.type))?(this.menu.blur(),this.document.one("mousemove",function(){e(t.target).trigger(t.originalEvent)}),void 0):(n=i.item.data("ui-autocomplete-item"),!1!==this._trigger("focus",t,{item:n})&&t.originalEvent&&/^key/.test(t.originalEvent.type)&&this._value(n.value),s=i.item.attr("aria-label")||n.value,s&&e.trim(s).length&&(this.liveRegion.children().hide(),e("<div>").text(s).appendTo(this.liveRegion)),void 0)},menuselect:function(e,t){var i=t.item.data("ui-autocomplete-item"),s=this.previous;this.element[0]!==this.document[0].activeElement&&(this.element.focus(),this.previous=s,this._delay(function(){this.previous=s,this.selectedItem=i})),!1!==this._trigger("select",e,{item:i})&&this._value(i.value),this.term=this._value(),this.close(e),this.selectedItem=i}}),this.liveRegion=e("<span>",{role:"status","aria-live":"assertive","aria-relevant":"additions"}).addClass("ui-helper-hidden-accessible").appendTo(this.document[0].body),this._on(this.window,{beforeunload:function(){this.element.removeAttr("autocomplete")}})},_destroy:function(){clearTimeout(this.searching),this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"),this.menu.element.remove(),this.liveRegion.remove()},_setOption:function(e,t){this._super(e,t),"source"===e&&this._initSource(),"appendTo"===e&&this.menu.element.appendTo(this._appendTo()),"disabled"===e&&t&&this.xhr&&this.xhr.abort()},_appendTo:function(){var t=this.options.appendTo;return t&&(t=t.jquery||t.nodeType?e(t):this.document.find(t).eq(0)),t&&t[0]||(t=this.element.closest(".ui-front")),t.length||(t=this.document[0].body),t},_initSource:function(){var t,i,s=this;e.isArray(this.options.source)?(t=this.options.source,this.source=function(i,s){s(e.ui.autocomplete.filter(t,i.term))}):"string"==typeof this.options.source?(i=this.options.source,this.source=function(t,n){s.xhr&&s.xhr.abort(),s.xhr=e.ajax({url:i,data:t,dataType:"json",success:function(e){n(e)},error:function(){n([])}})}):this.source=this.options.source},_searchTimeout:function(e){clearTimeout(this.searching),this.searching=this._delay(function(){var t=this.term===this._value(),i=this.menu.element.is(":visible"),s=e.altKey||e.ctrlKey||e.metaKey||e.shiftKey;(!t||t&&!i&&!s)&&(this.selectedItem=null,this.search(null,e))},this.options.delay)},search:function(e,t){return e=null!=e?e:this._value(),this.term=this._value(),e.length<this.options.minLength?this.close(t):this._trigger("search",t)!==!1?this._search(e):void 0},_search:function(e){this.pending++,this.element.addClass("ui-autocomplete-loading"),this.cancelSearch=!1,this.source({term:e},this._response())},_response:function(){var t=++this.requestIndex;return e.proxy(function(e){t===this.requestIndex&&this.__response(e),this.pending--,this.pending||this.element.removeClass("ui-autocomplete-loading")},this)},__response:function(e){e&&(e=this._normalize(e)),this._trigger("response",null,{content:e}),!this.options.disabled&&e&&e.length&&!this.cancelSearch?(this._suggest(e),this._trigger("open")):this._close()},close:function(e){this.cancelSearch=!0,this._close(e)},_close:function(e){this.menu.element.is(":visible")&&(this.menu.element.hide(),this.menu.blur(),this.isNewMenu=!0,this._trigger("close",e))},_change:function(e){this.previous!==this._value()&&this._trigger("change",e,{item:this.selectedItem})},_normalize:function(t){return t.length&&t[0].label&&t[0].value?t:e.map(t,function(t){return"string"==typeof t?{label:t,value:t}:e.extend({},t,{label:t.label||t.value,value:t.value||t.label})})},_suggest:function(t){var i=this.menu.element.empty();this._renderMenu(i,t),this.isNewMenu=!0,this.menu.refresh(),i.show(),this._resizeMenu(),i.position(e.extend({of:this.element},this.options.position)),this.options.autoFocus&&this.menu.next()},_resizeMenu:function(){var e=this.menu.element;e.outerWidth(Math.max(e.width("").outerWidth()+1,this.element.outerWidth()))},_renderMenu:function(t,i){var s=this;e.each(i,function(e,i){s._renderItemData(t,i)})},_renderItemData:function(e,t){return this._renderItem(e,t).data("ui-autocomplete-item",t)},_renderItem:function(t,i){return e("<li>").text(i.label).appendTo(t)},_move:function(e,t){return this.menu.element.is(":visible")?this.menu.isFirstItem()&&/^previous/.test(e)||this.menu.isLastItem()&&/^next/.test(e)?(this.isMultiLine||this._value(this.term),this.menu.blur(),void 0):(this.menu[e](t),void 0):(this.search(null,t),void 0)},widget:function(){return this.menu.element},_value:function(){return this.valueMethod.apply(this.element,arguments)},_keyEvent:function(e,t){(!this.isMultiLine||this.menu.element.is(":visible"))&&(this._move(e,t),t.preventDefault())}}),e.extend(e.ui.autocomplete,{escapeRegex:function(e){return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")},filter:function(t,i){var s=RegExp(e.ui.autocomplete.escapeRegex(i),"i");return e.grep(t,function(e){return s.test(e.label||e.value||e)})}}),e.widget("ui.autocomplete",e.ui.autocomplete,{options:{messages:{noResults:"No search results.",results:function(e){return e+(e>1?" results are":" result is")+" available, use up and down arrow keys to navigate."}}},__response:function(t){var i;this._superApply(arguments),this.options.disabled||this.cancelSearch||(i=t&&t.length?this.options.messages.results(t.length):this.options.messages.noResults,this.liveRegion.children().hide(),e("<div>").text(i).appendTo(this.liveRegion))}}),e.ui.autocomplete;var c,p="ui-button ui-widget ui-state-default ui-corner-all",f="ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only",m=function(){var t=e(this);setTimeout(function(){t.find(":ui-button").button("refresh")},1)},g=function(t){var i=t.name,s=t.form,n=e([]);return i&&(i=i.replace(/'/g,"\\'"),n=s?e(s).find("[name='"+i+"'][type=radio]"):e("[name='"+i+"'][type=radio]",t.ownerDocument).filter(function(){return!this.form})),n};e.widget("ui.button",{version:"1.11.4",defaultElement:"<button>",options:{disabled:null,text:!0,label:null,icons:{primary:null,secondary:null}},_create:function(){this.element.closest("form").unbind("reset"+this.eventNamespace).bind("reset"+this.eventNamespace,m),"boolean"!=typeof this.options.disabled?this.options.disabled=!!this.element.prop("disabled"):this.element.prop("disabled",this.options.disabled),this._determineButtonType(),this.hasTitle=!!this.buttonElement.attr("title");var t=this,i=this.options,s="checkbox"===this.type||"radio"===this.type,n=s?"":"ui-state-active";null===i.label&&(i.label="input"===this.type?this.buttonElement.val():this.buttonElement.html()),this._hoverable(this.buttonElement),this.buttonElement.addClass(p).attr("role","button").bind("mouseenter"+this.eventNamespace,function(){i.disabled||this===c&&e(this).addClass("ui-state-active")}).bind("mouseleave"+this.eventNamespace,function(){i.disabled||e(this).removeClass(n)}).bind("click"+this.eventNamespace,function(e){i.disabled&&(e.preventDefault(),e.stopImmediatePropagation())}),this._on({focus:function(){this.buttonElement.addClass("ui-state-focus")},blur:function(){this.buttonElement.removeClass("ui-state-focus")}}),s&&this.element.bind("change"+this.eventNamespace,function(){t.refresh()}),"checkbox"===this.type?this.buttonElement.bind("click"+this.eventNamespace,function(){return i.disabled?!1:void 0}):"radio"===this.type?this.buttonElement.bind("click"+this.eventNamespace,function(){if(i.disabled)return!1;e(this).addClass("ui-state-active"),t.buttonElement.attr("aria-pressed","true");var s=t.element[0];g(s).not(s).map(function(){return e(this).button("widget")[0]}).removeClass("ui-state-active").attr("aria-pressed","false")}):(this.buttonElement.bind("mousedown"+this.eventNamespace,function(){return i.disabled?!1:(e(this).addClass("ui-state-active"),c=this,t.document.one("mouseup",function(){c=null}),void 0)}).bind("mouseup"+this.eventNamespace,function(){return i.disabled?!1:(e(this).removeClass("ui-state-active"),void 0)}).bind("keydown"+this.eventNamespace,function(t){return i.disabled?!1:((t.keyCode===e.ui.keyCode.SPACE||t.keyCode===e.ui.keyCode.ENTER)&&e(this).addClass("ui-state-active"),void 0)}).bind("keyup"+this.eventNamespace+" blur"+this.eventNamespace,function(){e(this).removeClass("ui-state-active")}),this.buttonElement.is("a")&&this.buttonElement.keyup(function(t){t.keyCode===e.ui.keyCode.SPACE&&e(this).click()})),this._setOption("disabled",i.disabled),this._resetButton()},_determineButtonType:function(){var e,t,i;this.type=this.element.is("[type=checkbox]")?"checkbox":this.element.is("[type=radio]")?"radio":this.element.is("input")?"input":"button","checkbox"===this.type||"radio"===this.type?(e=this.element.parents().last(),t="label[for='"+this.element.attr("id")+"']",this.buttonElement=e.find(t),this.buttonElement.length||(e=e.length?e.siblings():this.element.siblings(),this.buttonElement=e.filter(t),this.buttonElement.length||(this.buttonElement=e.find(t))),this.element.addClass("ui-helper-hidden-accessible"),i=this.element.is(":checked"),i&&this.buttonElement.addClass("ui-state-active"),this.buttonElement.prop("aria-pressed",i)):this.buttonElement=this.element},widget:function(){return this.buttonElement},_destroy:function(){this.element.removeClass("ui-helper-hidden-accessible"),this.buttonElement.removeClass(p+" ui-state-active "+f).removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html()),this.hasTitle||this.buttonElement.removeAttr("title")},_setOption:function(e,t){return this._super(e,t),"disabled"===e?(this.widget().toggleClass("ui-state-disabled",!!t),this.element.prop("disabled",!!t),t&&("checkbox"===this.type||"radio"===this.type?this.buttonElement.removeClass("ui-state-focus"):this.buttonElement.removeClass("ui-state-focus ui-state-active")),void 0):(this._resetButton(),void 0)},refresh:function(){var t=this.element.is("input, button")?this.element.is(":disabled"):this.element.hasClass("ui-button-disabled");t!==this.options.disabled&&this._setOption("disabled",t),"radio"===this.type?g(this.element[0]).each(function(){e(this).is(":checked")?e(this).button("widget").addClass("ui-state-active").attr("aria-pressed","true"):e(this).button("widget").removeClass("ui-state-active").attr("aria-pressed","false")}):"checkbox"===this.type&&(this.element.is(":checked")?this.buttonElement.addClass("ui-state-active").attr("aria-pressed","true"):this.buttonElement.removeClass("ui-state-active").attr("aria-pressed","false"))},_resetButton:function(){if("input"===this.type)return this.options.label&&this.element.val(this.options.label),void 0;var t=this.buttonElement.removeClass(f),i=e("<span></span>",this.document[0]).addClass("ui-button-text").html(this.options.label).appendTo(t.empty()).text(),s=this.options.icons,n=s.primary&&s.secondary,a=[];s.primary||s.secondary?(this.options.text&&a.push("ui-button-text-icon"+(n?"s":s.primary?"-primary":"-secondary")),s.primary&&t.prepend("<span class='ui-button-icon-primary ui-icon "+s.primary+"'></span>"),s.secondary&&t.append("<span class='ui-button-icon-secondary ui-icon "+s.secondary+"'></span>"),this.options.text||(a.push(n?"ui-button-icons-only":"ui-button-icon-only"),this.hasTitle||t.attr("title",e.trim(i)))):a.push("ui-button-text-only"),t.addClass(a.join(" "))}}),e.widget("ui.buttonset",{version:"1.11.4",options:{items:"button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(ui-button)"},_create:function(){this.element.addClass("ui-buttonset")},_init:function(){this.refresh()},_setOption:function(e,t){"disabled"===e&&this.buttons.button("option",e,t),this._super(e,t)},refresh:function(){var t="rtl"===this.element.css("direction"),i=this.element.find(this.options.items),s=i.filter(":ui-button");i.not(":ui-button").button(),s.button("refresh"),this.buttons=i.map(function(){return e(this).button("widget")[0]}).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass(t?"ui-corner-right":"ui-corner-left").end().filter(":last").addClass(t?"ui-corner-left":"ui-corner-right").end().end()},_destroy:function(){this.element.removeClass("ui-buttonset"),this.buttons.map(function(){return e(this).button("widget")[0]}).removeClass("ui-corner-left ui-corner-right").end().button("destroy")}}),e.ui.button,e.extend(e.ui,{datepicker:{version:"1.11.4"}});var v;e.extend(n.prototype,{markerClassName:"hasDatepicker",maxRows:4,_widgetDatepicker:function(){return this.dpDiv},setDefaults:function(e){return r(this._defaults,e||{}),this},_attachDatepicker:function(t,i){var s,n,a;s=t.nodeName.toLowerCase(),n="div"===s||"span"===s,t.id||(this.uuid+=1,t.id="dp"+this.uuid),a=this._newInst(e(t),n),a.settings=e.extend({},i||{}),"input"===s?this._connectDatepicker(t,a):n&&this._inlineDatepicker(t,a)},_newInst:function(t,i){var s=t[0].id.replace(/([^A-Za-z0-9_\-])/g,"\\\\$1");return{id:s,input:t,selectedDay:0,selectedMonth:0,selectedYear:0,drawMonth:0,drawYear:0,inline:i,dpDiv:i?a(e("<div class='"+this._inlineClass+" ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")):this.dpDiv}},_connectDatepicker:function(t,i){var s=e(t);i.append=e([]),i.trigger=e([]),s.hasClass(this.markerClassName)||(this._attachments(s,i),s.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp),this._autoSize(i),e.data(t,"datepicker",i),i.settings.disabled&&this._disableDatepicker(t))},_attachments:function(t,i){var s,n,a,o=this._get(i,"appendText"),r=this._get(i,"isRTL");i.append&&i.append.remove(),o&&(i.append=e("<span class='"+this._appendClass+"'>"+o+"</span>"),t[r?"before":"after"](i.append)),t.unbind("focus",this._showDatepicker),i.trigger&&i.trigger.remove(),s=this._get(i,"showOn"),("focus"===s||"both"===s)&&t.focus(this._showDatepicker),("button"===s||"both"===s)&&(n=this._get(i,"buttonText"),a=this._get(i,"buttonImage"),i.trigger=e(this._get(i,"buttonImageOnly")?e("<img/>").addClass(this._triggerClass).attr({src:a,alt:n,title:n}):e("<button type='button'></button>").addClass(this._triggerClass).html(a?e("<img/>").attr({src:a,alt:n,title:n}):n)),t[r?"before":"after"](i.trigger),i.trigger.click(function(){return e.datepicker._datepickerShowing&&e.datepicker._lastInput===t[0]?e.datepicker._hideDatepicker():e.datepicker._datepickerShowing&&e.datepicker._lastInput!==t[0]?(e.datepicker._hideDatepicker(),e.datepicker._showDatepicker(t[0])):e.datepicker._showDatepicker(t[0]),!1}))},_autoSize:function(e){if(this._get(e,"autoSize")&&!e.inline){var t,i,s,n,a=new Date(2009,11,20),o=this._get(e,"dateFormat");o.match(/[DM]/)&&(t=function(e){for(i=0,s=0,n=0;e.length>n;n++)e[n].length>i&&(i=e[n].length,s=n);return s},a.setMonth(t(this._get(e,o.match(/MM/)?"monthNames":"monthNamesShort"))),a.setDate(t(this._get(e,o.match(/DD/)?"dayNames":"dayNamesShort"))+20-a.getDay())),e.input.attr("size",this._formatDate(e,a).length)}},_inlineDatepicker:function(t,i){var s=e(t);s.hasClass(this.markerClassName)||(s.addClass(this.markerClassName).append(i.dpDiv),e.data(t,"datepicker",i),this._setDate(i,this._getDefaultDate(i),!0),this._updateDatepicker(i),this._updateAlternate(i),i.settings.disabled&&this._disableDatepicker(t),i.dpDiv.css("display","block"))},_dialogDatepicker:function(t,i,s,n,a){var o,h,l,u,d,c=this._dialogInst;return c||(this.uuid+=1,o="dp"+this.uuid,this._dialogInput=e("<input type='text' id='"+o+"' style='position: absolute; top: -100px; width: 0px;'/>"),this._dialogInput.keydown(this._doKeyDown),e("body").append(this._dialogInput),c=this._dialogInst=this._newInst(this._dialogInput,!1),c.settings={},e.data(this._dialogInput[0],"datepicker",c)),r(c.settings,n||{}),i=i&&i.constructor===Date?this._formatDate(c,i):i,this._dialogInput.val(i),this._pos=a?a.length?a:[a.pageX,a.pageY]:null,this._pos||(h=document.documentElement.clientWidth,l=document.documentElement.clientHeight,u=document.documentElement.scrollLeft||document.body.scrollLeft,d=document.documentElement.scrollTop||document.body.scrollTop,this._pos=[h/2-100+u,l/2-150+d]),this._dialogInput.css("left",this._pos[0]+20+"px").css("top",this._pos[1]+"px"),c.settings.onSelect=s,this._inDialog=!0,this.dpDiv.addClass(this._dialogClass),this._showDatepicker(this._dialogInput[0]),e.blockUI&&e.blockUI(this.dpDiv),e.data(this._dialogInput[0],"datepicker",c),this},_destroyDatepicker:function(t){var i,s=e(t),n=e.data(t,"datepicker");s.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),e.removeData(t,"datepicker"),"input"===i?(n.append.remove(),n.trigger.remove(),s.removeClass(this.markerClassName).unbind("focus",this._showDatepicker).unbind("keydown",this._doKeyDown).unbind("keypress",this._doKeyPress).unbind("keyup",this._doKeyUp)):("div"===i||"span"===i)&&s.removeClass(this.markerClassName).empty(),v===n&&(v=null))},_enableDatepicker:function(t){var i,s,n=e(t),a=e.data(t,"datepicker");n.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),"input"===i?(t.disabled=!1,a.trigger.filter("button").each(function(){this.disabled=!1}).end().filter("img").css({opacity:"1.0",cursor:""})):("div"===i||"span"===i)&&(s=n.children("."+this._inlineClass),s.children().removeClass("ui-state-disabled"),s.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled",!1)),this._disabledInputs=e.map(this._disabledInputs,function(e){return e===t?null:e}))},_disableDatepicker:function(t){var i,s,n=e(t),a=e.data(t,"datepicker");n.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),"input"===i?(t.disabled=!0,a.trigger.filter("button").each(function(){this.disabled=!0}).end().filter("img").css({opacity:"0.5",cursor:"default"})):("div"===i||"span"===i)&&(s=n.children("."+this._inlineClass),s.children().addClass("ui-state-disabled"),s.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled",!0)),this._disabledInputs=e.map(this._disabledInputs,function(e){return e===t?null:e}),this._disabledInputs[this._disabledInputs.length]=t)},_isDisabledDatepicker:function(e){if(!e)return!1;for(var t=0;this._disabledInputs.length>t;t++)if(this._disabledInputs[t]===e)return!0;return!1},_getInst:function(t){try{return e.data(t,"datepicker")}catch(i){throw"Missing instance data for this datepicker"}},_optionDatepicker:function(t,i,s){var n,a,o,h,l=this._getInst(t);return 2===arguments.length&&"string"==typeof i?"defaults"===i?e.extend({},e.datepicker._defaults):l?"all"===i?e.extend({},l.settings):this._get(l,i):null:(n=i||{},"string"==typeof i&&(n={},n[i]=s),l&&(this._curInst===l&&this._hideDatepicker(),a=this._getDateDatepicker(t,!0),o=this._getMinMaxDate(l,"min"),h=this._getMinMaxDate(l,"max"),r(l.settings,n),null!==o&&void 0!==n.dateFormat&&void 0===n.minDate&&(l.settings.minDate=this._formatDate(l,o)),null!==h&&void 0!==n.dateFormat&&void 0===n.maxDate&&(l.settings.maxDate=this._formatDate(l,h)),"disabled"in n&&(n.disabled?this._disableDatepicker(t):this._enableDatepicker(t)),this._attachments(e(t),l),this._autoSize(l),this._setDate(l,a),this._updateAlternate(l),this._updateDatepicker(l)),void 0)},_changeDatepicker:function(e,t,i){this._optionDatepicker(e,t,i)},_refreshDatepicker:function(e){var t=this._getInst(e);t&&this._updateDatepicker(t)},_setDateDatepicker:function(e,t){var i=this._getInst(e);i&&(this._setDate(i,t),this._updateDatepicker(i),this._updateAlternate(i))},_getDateDatepicker:function(e,t){var i=this._getInst(e);return i&&!i.inline&&this._setDateFromField(i,t),i?this._getDate(i):null},_doKeyDown:function(t){var i,s,n,a=e.datepicker._getInst(t.target),o=!0,r=a.dpDiv.is(".ui-datepicker-rtl");if(a._keyEvent=!0,e.datepicker._datepickerShowing)switch(t.keyCode){case 9:e.datepicker._hideDatepicker(),o=!1;break;case 13:return n=e("td."+e.datepicker._dayOverClass+":not(."+e.datepicker._currentClass+")",a.dpDiv),n[0]&&e.datepicker._selectDay(t.target,a.selectedMonth,a.selectedYear,n[0]),i=e.datepicker._get(a,"onSelect"),i?(s=e.datepicker._formatDate(a),i.apply(a.input?a.input[0]:null,[s,a])):e.datepicker._hideDatepicker(),!1;case 27:e.datepicker._hideDatepicker();break;case 33:e.datepicker._adjustDate(t.target,t.ctrlKey?-e.datepicker._get(a,"stepBigMonths"):-e.datepicker._get(a,"stepMonths"),"M");break;case 34:e.datepicker._adjustDate(t.target,t.ctrlKey?+e.datepicker._get(a,"stepBigMonths"):+e.datepicker._get(a,"stepMonths"),"M");break;case 35:(t.ctrlKey||t.metaKey)&&e.datepicker._clearDate(t.target),o=t.ctrlKey||t.metaKey;break;case 36:(t.ctrlKey||t.metaKey)&&e.datepicker._gotoToday(t.target),o=t.ctrlKey||t.metaKey;break;case 37:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,r?1:-1,"D"),o=t.ctrlKey||t.metaKey,t.originalEvent.altKey&&e.datepicker._adjustDate(t.target,t.ctrlKey?-e.datepicker._get(a,"stepBigMonths"):-e.datepicker._get(a,"stepMonths"),"M");break;case 38:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,-7,"D"),o=t.ctrlKey||t.metaKey;break;case 39:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,r?-1:1,"D"),o=t.ctrlKey||t.metaKey,t.originalEvent.altKey&&e.datepicker._adjustDate(t.target,t.ctrlKey?+e.datepicker._get(a,"stepBigMonths"):+e.datepicker._get(a,"stepMonths"),"M");break;case 40:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,7,"D"),o=t.ctrlKey||t.metaKey;break;default:o=!1}else 36===t.keyCode&&t.ctrlKey?e.datepicker._showDatepicker(this):o=!1;o&&(t.preventDefault(),t.stopPropagation())},_doKeyPress:function(t){var i,s,n=e.datepicker._getInst(t.target);
return e.datepicker._get(n,"constrainInput")?(i=e.datepicker._possibleChars(e.datepicker._get(n,"dateFormat")),s=String.fromCharCode(null==t.charCode?t.keyCode:t.charCode),t.ctrlKey||t.metaKey||" ">s||!i||i.indexOf(s)>-1):void 0},_doKeyUp:function(t){var i,s=e.datepicker._getInst(t.target);if(s.input.val()!==s.lastVal)try{i=e.datepicker.parseDate(e.datepicker._get(s,"dateFormat"),s.input?s.input.val():null,e.datepicker._getFormatConfig(s)),i&&(e.datepicker._setDateFromField(s),e.datepicker._updateAlternate(s),e.datepicker._updateDatepicker(s))}catch(n){}return!0},_showDatepicker:function(t){if(t=t.target||t,"input"!==t.nodeName.toLowerCase()&&(t=e("input",t.parentNode)[0]),!e.datepicker._isDisabledDatepicker(t)&&e.datepicker._lastInput!==t){var i,n,a,o,h,l,u;i=e.datepicker._getInst(t),e.datepicker._curInst&&e.datepicker._curInst!==i&&(e.datepicker._curInst.dpDiv.stop(!0,!0),i&&e.datepicker._datepickerShowing&&e.datepicker._hideDatepicker(e.datepicker._curInst.input[0])),n=e.datepicker._get(i,"beforeShow"),a=n?n.apply(t,[t,i]):{},a!==!1&&(r(i.settings,a),i.lastVal=null,e.datepicker._lastInput=t,e.datepicker._setDateFromField(i),e.datepicker._inDialog&&(t.value=""),e.datepicker._pos||(e.datepicker._pos=e.datepicker._findPos(t),e.datepicker._pos[1]+=t.offsetHeight),o=!1,e(t).parents().each(function(){return o|="fixed"===e(this).css("position"),!o}),h={left:e.datepicker._pos[0],top:e.datepicker._pos[1]},e.datepicker._pos=null,i.dpDiv.empty(),i.dpDiv.css({position:"absolute",display:"block",top:"-1000px"}),e.datepicker._updateDatepicker(i),h=e.datepicker._checkOffset(i,h,o),i.dpDiv.css({position:e.datepicker._inDialog&&e.blockUI?"static":o?"fixed":"absolute",display:"none",left:h.left+"px",top:h.top+"px"}),i.inline||(l=e.datepicker._get(i,"showAnim"),u=e.datepicker._get(i,"duration"),i.dpDiv.css("z-index",s(e(t))+1),e.datepicker._datepickerShowing=!0,e.effects&&e.effects.effect[l]?i.dpDiv.show(l,e.datepicker._get(i,"showOptions"),u):i.dpDiv[l||"show"](l?u:null),e.datepicker._shouldFocusInput(i)&&i.input.focus(),e.datepicker._curInst=i))}},_updateDatepicker:function(t){this.maxRows=4,v=t,t.dpDiv.empty().append(this._generateHTML(t)),this._attachHandlers(t);var i,s=this._getNumberOfMonths(t),n=s[1],a=17,r=t.dpDiv.find("."+this._dayOverClass+" a");r.length>0&&o.apply(r.get(0)),t.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width(""),n>1&&t.dpDiv.addClass("ui-datepicker-multi-"+n).css("width",a*n+"em"),t.dpDiv[(1!==s[0]||1!==s[1]?"add":"remove")+"Class"]("ui-datepicker-multi"),t.dpDiv[(this._get(t,"isRTL")?"add":"remove")+"Class"]("ui-datepicker-rtl"),t===e.datepicker._curInst&&e.datepicker._datepickerShowing&&e.datepicker._shouldFocusInput(t)&&t.input.focus(),t.yearshtml&&(i=t.yearshtml,setTimeout(function(){i===t.yearshtml&&t.yearshtml&&t.dpDiv.find("select.ui-datepicker-year:first").replaceWith(t.yearshtml),i=t.yearshtml=null},0))},_shouldFocusInput:function(e){return e.input&&e.input.is(":visible")&&!e.input.is(":disabled")&&!e.input.is(":focus")},_checkOffset:function(t,i,s){var n=t.dpDiv.outerWidth(),a=t.dpDiv.outerHeight(),o=t.input?t.input.outerWidth():0,r=t.input?t.input.outerHeight():0,h=document.documentElement.clientWidth+(s?0:e(document).scrollLeft()),l=document.documentElement.clientHeight+(s?0:e(document).scrollTop());return i.left-=this._get(t,"isRTL")?n-o:0,i.left-=s&&i.left===t.input.offset().left?e(document).scrollLeft():0,i.top-=s&&i.top===t.input.offset().top+r?e(document).scrollTop():0,i.left-=Math.min(i.left,i.left+n>h&&h>n?Math.abs(i.left+n-h):0),i.top-=Math.min(i.top,i.top+a>l&&l>a?Math.abs(a+r):0),i},_findPos:function(t){for(var i,s=this._getInst(t),n=this._get(s,"isRTL");t&&("hidden"===t.type||1!==t.nodeType||e.expr.filters.hidden(t));)t=t[n?"previousSibling":"nextSibling"];return i=e(t).offset(),[i.left,i.top]},_hideDatepicker:function(t){var i,s,n,a,o=this._curInst;!o||t&&o!==e.data(t,"datepicker")||this._datepickerShowing&&(i=this._get(o,"showAnim"),s=this._get(o,"duration"),n=function(){e.datepicker._tidyDialog(o)},e.effects&&(e.effects.effect[i]||e.effects[i])?o.dpDiv.hide(i,e.datepicker._get(o,"showOptions"),s,n):o.dpDiv["slideDown"===i?"slideUp":"fadeIn"===i?"fadeOut":"hide"](i?s:null,n),i||n(),this._datepickerShowing=!1,a=this._get(o,"onClose"),a&&a.apply(o.input?o.input[0]:null,[o.input?o.input.val():"",o]),this._lastInput=null,this._inDialog&&(this._dialogInput.css({position:"absolute",left:"0",top:"-100px"}),e.blockUI&&(e.unblockUI(),e("body").append(this.dpDiv))),this._inDialog=!1)},_tidyDialog:function(e){e.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")},_checkExternalClick:function(t){if(e.datepicker._curInst){var i=e(t.target),s=e.datepicker._getInst(i[0]);(i[0].id!==e.datepicker._mainDivId&&0===i.parents("#"+e.datepicker._mainDivId).length&&!i.hasClass(e.datepicker.markerClassName)&&!i.closest("."+e.datepicker._triggerClass).length&&e.datepicker._datepickerShowing&&(!e.datepicker._inDialog||!e.blockUI)||i.hasClass(e.datepicker.markerClassName)&&e.datepicker._curInst!==s)&&e.datepicker._hideDatepicker()}},_adjustDate:function(t,i,s){var n=e(t),a=this._getInst(n[0]);this._isDisabledDatepicker(n[0])||(this._adjustInstDate(a,i+("M"===s?this._get(a,"showCurrentAtPos"):0),s),this._updateDatepicker(a))},_gotoToday:function(t){var i,s=e(t),n=this._getInst(s[0]);this._get(n,"gotoCurrent")&&n.currentDay?(n.selectedDay=n.currentDay,n.drawMonth=n.selectedMonth=n.currentMonth,n.drawYear=n.selectedYear=n.currentYear):(i=new Date,n.selectedDay=i.getDate(),n.drawMonth=n.selectedMonth=i.getMonth(),n.drawYear=n.selectedYear=i.getFullYear()),this._notifyChange(n),this._adjustDate(s)},_selectMonthYear:function(t,i,s){var n=e(t),a=this._getInst(n[0]);a["selected"+("M"===s?"Month":"Year")]=a["draw"+("M"===s?"Month":"Year")]=parseInt(i.options[i.selectedIndex].value,10),this._notifyChange(a),this._adjustDate(n)},_selectDay:function(t,i,s,n){var a,o=e(t);e(n).hasClass(this._unselectableClass)||this._isDisabledDatepicker(o[0])||(a=this._getInst(o[0]),a.selectedDay=a.currentDay=e("a",n).html(),a.selectedMonth=a.currentMonth=i,a.selectedYear=a.currentYear=s,this._selectDate(t,this._formatDate(a,a.currentDay,a.currentMonth,a.currentYear)))},_clearDate:function(t){var i=e(t);this._selectDate(i,"")},_selectDate:function(t,i){var s,n=e(t),a=this._getInst(n[0]);i=null!=i?i:this._formatDate(a),a.input&&a.input.val(i),this._updateAlternate(a),s=this._get(a,"onSelect"),s?s.apply(a.input?a.input[0]:null,[i,a]):a.input&&a.input.trigger("change"),a.inline?this._updateDatepicker(a):(this._hideDatepicker(),this._lastInput=a.input[0],"object"!=typeof a.input[0]&&a.input.focus(),this._lastInput=null)},_updateAlternate:function(t){var i,s,n,a=this._get(t,"altField");a&&(i=this._get(t,"altFormat")||this._get(t,"dateFormat"),s=this._getDate(t),n=this.formatDate(i,s,this._getFormatConfig(t)),e(a).each(function(){e(this).val(n)}))},noWeekends:function(e){var t=e.getDay();return[t>0&&6>t,""]},iso8601Week:function(e){var t,i=new Date(e.getTime());return i.setDate(i.getDate()+4-(i.getDay()||7)),t=i.getTime(),i.setMonth(0),i.setDate(1),Math.floor(Math.round((t-i)/864e5)/7)+1},parseDate:function(t,i,s){if(null==t||null==i)throw"Invalid arguments";if(i="object"==typeof i?""+i:i+"",""===i)return null;var n,a,o,r,h=0,l=(s?s.shortYearCutoff:null)||this._defaults.shortYearCutoff,u="string"!=typeof l?l:(new Date).getFullYear()%100+parseInt(l,10),d=(s?s.dayNamesShort:null)||this._defaults.dayNamesShort,c=(s?s.dayNames:null)||this._defaults.dayNames,p=(s?s.monthNamesShort:null)||this._defaults.monthNamesShort,f=(s?s.monthNames:null)||this._defaults.monthNames,m=-1,g=-1,v=-1,y=-1,b=!1,_=function(e){var i=t.length>n+1&&t.charAt(n+1)===e;return i&&n++,i},x=function(e){var t=_(e),s="@"===e?14:"!"===e?20:"y"===e&&t?4:"o"===e?3:2,n="y"===e?s:1,a=RegExp("^\\d{"+n+","+s+"}"),o=i.substring(h).match(a);if(!o)throw"Missing number at position "+h;return h+=o[0].length,parseInt(o[0],10)},w=function(t,s,n){var a=-1,o=e.map(_(t)?n:s,function(e,t){return[[t,e]]}).sort(function(e,t){return-(e[1].length-t[1].length)});if(e.each(o,function(e,t){var s=t[1];return i.substr(h,s.length).toLowerCase()===s.toLowerCase()?(a=t[0],h+=s.length,!1):void 0}),-1!==a)return a+1;throw"Unknown name at position "+h},k=function(){if(i.charAt(h)!==t.charAt(n))throw"Unexpected literal at position "+h;h++};for(n=0;t.length>n;n++)if(b)"'"!==t.charAt(n)||_("'")?k():b=!1;else switch(t.charAt(n)){case"d":v=x("d");break;case"D":w("D",d,c);break;case"o":y=x("o");break;case"m":g=x("m");break;case"M":g=w("M",p,f);break;case"y":m=x("y");break;case"@":r=new Date(x("@")),m=r.getFullYear(),g=r.getMonth()+1,v=r.getDate();break;case"!":r=new Date((x("!")-this._ticksTo1970)/1e4),m=r.getFullYear(),g=r.getMonth()+1,v=r.getDate();break;case"'":_("'")?k():b=!0;break;default:k()}if(i.length>h&&(o=i.substr(h),!/^\s+/.test(o)))throw"Extra/unparsed characters found in date: "+o;if(-1===m?m=(new Date).getFullYear():100>m&&(m+=(new Date).getFullYear()-(new Date).getFullYear()%100+(u>=m?0:-100)),y>-1)for(g=1,v=y;;){if(a=this._getDaysInMonth(m,g-1),a>=v)break;g++,v-=a}if(r=this._daylightSavingAdjust(new Date(m,g-1,v)),r.getFullYear()!==m||r.getMonth()+1!==g||r.getDate()!==v)throw"Invalid date";return r},ATOM:"yy-mm-dd",COOKIE:"D, dd M yy",ISO_8601:"yy-mm-dd",RFC_822:"D, d M y",RFC_850:"DD, dd-M-y",RFC_1036:"D, d M y",RFC_1123:"D, d M yy",RFC_2822:"D, d M yy",RSS:"D, d M y",TICKS:"!",TIMESTAMP:"@",W3C:"yy-mm-dd",_ticksTo1970:1e7*60*60*24*(718685+Math.floor(492.5)-Math.floor(19.7)+Math.floor(4.925)),formatDate:function(e,t,i){if(!t)return"";var s,n=(i?i.dayNamesShort:null)||this._defaults.dayNamesShort,a=(i?i.dayNames:null)||this._defaults.dayNames,o=(i?i.monthNamesShort:null)||this._defaults.monthNamesShort,r=(i?i.monthNames:null)||this._defaults.monthNames,h=function(t){var i=e.length>s+1&&e.charAt(s+1)===t;return i&&s++,i},l=function(e,t,i){var s=""+t;if(h(e))for(;i>s.length;)s="0"+s;return s},u=function(e,t,i,s){return h(e)?s[t]:i[t]},d="",c=!1;if(t)for(s=0;e.length>s;s++)if(c)"'"!==e.charAt(s)||h("'")?d+=e.charAt(s):c=!1;else switch(e.charAt(s)){case"d":d+=l("d",t.getDate(),2);break;case"D":d+=u("D",t.getDay(),n,a);break;case"o":d+=l("o",Math.round((new Date(t.getFullYear(),t.getMonth(),t.getDate()).getTime()-new Date(t.getFullYear(),0,0).getTime())/864e5),3);break;case"m":d+=l("m",t.getMonth()+1,2);break;case"M":d+=u("M",t.getMonth(),o,r);break;case"y":d+=h("y")?t.getFullYear():(10>t.getYear()%100?"0":"")+t.getYear()%100;break;case"@":d+=t.getTime();break;case"!":d+=1e4*t.getTime()+this._ticksTo1970;break;case"'":h("'")?d+="'":c=!0;break;default:d+=e.charAt(s)}return d},_possibleChars:function(e){var t,i="",s=!1,n=function(i){var s=e.length>t+1&&e.charAt(t+1)===i;return s&&t++,s};for(t=0;e.length>t;t++)if(s)"'"!==e.charAt(t)||n("'")?i+=e.charAt(t):s=!1;else switch(e.charAt(t)){case"d":case"m":case"y":case"@":i+="0123456789";break;case"D":case"M":return null;case"'":n("'")?i+="'":s=!0;break;default:i+=e.charAt(t)}return i},_get:function(e,t){return void 0!==e.settings[t]?e.settings[t]:this._defaults[t]},_setDateFromField:function(e,t){if(e.input.val()!==e.lastVal){var i=this._get(e,"dateFormat"),s=e.lastVal=e.input?e.input.val():null,n=this._getDefaultDate(e),a=n,o=this._getFormatConfig(e);try{a=this.parseDate(i,s,o)||n}catch(r){s=t?"":s}e.selectedDay=a.getDate(),e.drawMonth=e.selectedMonth=a.getMonth(),e.drawYear=e.selectedYear=a.getFullYear(),e.currentDay=s?a.getDate():0,e.currentMonth=s?a.getMonth():0,e.currentYear=s?a.getFullYear():0,this._adjustInstDate(e)}},_getDefaultDate:function(e){return this._restrictMinMax(e,this._determineDate(e,this._get(e,"defaultDate"),new Date))},_determineDate:function(t,i,s){var n=function(e){var t=new Date;return t.setDate(t.getDate()+e),t},a=function(i){try{return e.datepicker.parseDate(e.datepicker._get(t,"dateFormat"),i,e.datepicker._getFormatConfig(t))}catch(s){}for(var n=(i.toLowerCase().match(/^c/)?e.datepicker._getDate(t):null)||new Date,a=n.getFullYear(),o=n.getMonth(),r=n.getDate(),h=/([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g,l=h.exec(i);l;){switch(l[2]||"d"){case"d":case"D":r+=parseInt(l[1],10);break;case"w":case"W":r+=7*parseInt(l[1],10);break;case"m":case"M":o+=parseInt(l[1],10),r=Math.min(r,e.datepicker._getDaysInMonth(a,o));break;case"y":case"Y":a+=parseInt(l[1],10),r=Math.min(r,e.datepicker._getDaysInMonth(a,o))}l=h.exec(i)}return new Date(a,o,r)},o=null==i||""===i?s:"string"==typeof i?a(i):"number"==typeof i?isNaN(i)?s:n(i):new Date(i.getTime());return o=o&&"Invalid Date"==""+o?s:o,o&&(o.setHours(0),o.setMinutes(0),o.setSeconds(0),o.setMilliseconds(0)),this._daylightSavingAdjust(o)},_daylightSavingAdjust:function(e){return e?(e.setHours(e.getHours()>12?e.getHours()+2:0),e):null},_setDate:function(e,t,i){var s=!t,n=e.selectedMonth,a=e.selectedYear,o=this._restrictMinMax(e,this._determineDate(e,t,new Date));e.selectedDay=e.currentDay=o.getDate(),e.drawMonth=e.selectedMonth=e.currentMonth=o.getMonth(),e.drawYear=e.selectedYear=e.currentYear=o.getFullYear(),n===e.selectedMonth&&a===e.selectedYear||i||this._notifyChange(e),this._adjustInstDate(e),e.input&&e.input.val(s?"":this._formatDate(e))},_getDate:function(e){var t=!e.currentYear||e.input&&""===e.input.val()?null:this._daylightSavingAdjust(new Date(e.currentYear,e.currentMonth,e.currentDay));return t},_attachHandlers:function(t){var i=this._get(t,"stepMonths"),s="#"+t.id.replace(/\\\\/g,"\\");t.dpDiv.find("[data-handler]").map(function(){var t={prev:function(){e.datepicker._adjustDate(s,-i,"M")},next:function(){e.datepicker._adjustDate(s,+i,"M")},hide:function(){e.datepicker._hideDatepicker()},today:function(){e.datepicker._gotoToday(s)},selectDay:function(){return e.datepicker._selectDay(s,+this.getAttribute("data-month"),+this.getAttribute("data-year"),this),!1},selectMonth:function(){return e.datepicker._selectMonthYear(s,this,"M"),!1},selectYear:function(){return e.datepicker._selectMonthYear(s,this,"Y"),!1}};e(this).bind(this.getAttribute("data-event"),t[this.getAttribute("data-handler")])})},_generateHTML:function(e){var t,i,s,n,a,o,r,h,l,u,d,c,p,f,m,g,v,y,b,_,x,w,k,T,D,S,M,C,N,A,P,I,H,z,F,E,O,j,W,L=new Date,R=this._daylightSavingAdjust(new Date(L.getFullYear(),L.getMonth(),L.getDate())),Y=this._get(e,"isRTL"),B=this._get(e,"showButtonPanel"),J=this._get(e,"hideIfNoPrevNext"),q=this._get(e,"navigationAsDateFormat"),K=this._getNumberOfMonths(e),V=this._get(e,"showCurrentAtPos"),U=this._get(e,"stepMonths"),Q=1!==K[0]||1!==K[1],G=this._daylightSavingAdjust(e.currentDay?new Date(e.currentYear,e.currentMonth,e.currentDay):new Date(9999,9,9)),X=this._getMinMaxDate(e,"min"),$=this._getMinMaxDate(e,"max"),Z=e.drawMonth-V,et=e.drawYear;if(0>Z&&(Z+=12,et--),$)for(t=this._daylightSavingAdjust(new Date($.getFullYear(),$.getMonth()-K[0]*K[1]+1,$.getDate())),t=X&&X>t?X:t;this._daylightSavingAdjust(new Date(et,Z,1))>t;)Z--,0>Z&&(Z=11,et--);for(e.drawMonth=Z,e.drawYear=et,i=this._get(e,"prevText"),i=q?this.formatDate(i,this._daylightSavingAdjust(new Date(et,Z-U,1)),this._getFormatConfig(e)):i,s=this._canAdjustMonth(e,-1,et,Z)?"<a class='ui-datepicker-prev ui-corner-all' data-handler='prev' data-event='click' title='"+i+"'><span class='ui-icon ui-icon-circle-triangle-"+(Y?"e":"w")+"'>"+i+"</span></a>":J?"":"<a class='ui-datepicker-prev ui-corner-all ui-state-disabled' title='"+i+"'><span class='ui-icon ui-icon-circle-triangle-"+(Y?"e":"w")+"'>"+i+"</span></a>",n=this._get(e,"nextText"),n=q?this.formatDate(n,this._daylightSavingAdjust(new Date(et,Z+U,1)),this._getFormatConfig(e)):n,a=this._canAdjustMonth(e,1,et,Z)?"<a class='ui-datepicker-next ui-corner-all' data-handler='next' data-event='click' title='"+n+"'><span class='ui-icon ui-icon-circle-triangle-"+(Y?"w":"e")+"'>"+n+"</span></a>":J?"":"<a class='ui-datepicker-next ui-corner-all ui-state-disabled' title='"+n+"'><span class='ui-icon ui-icon-circle-triangle-"+(Y?"w":"e")+"'>"+n+"</span></a>",o=this._get(e,"currentText"),r=this._get(e,"gotoCurrent")&&e.currentDay?G:R,o=q?this.formatDate(o,r,this._getFormatConfig(e)):o,h=e.inline?"":"<button type='button' class='ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all' data-handler='hide' data-event='click'>"+this._get(e,"closeText")+"</button>",l=B?"<div class='ui-datepicker-buttonpane ui-widget-content'>"+(Y?h:"")+(this._isInRange(e,r)?"<button type='button' class='ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all' data-handler='today' data-event='click'>"+o+"</button>":"")+(Y?"":h)+"</div>":"",u=parseInt(this._get(e,"firstDay"),10),u=isNaN(u)?0:u,d=this._get(e,"showWeek"),c=this._get(e,"dayNames"),p=this._get(e,"dayNamesMin"),f=this._get(e,"monthNames"),m=this._get(e,"monthNamesShort"),g=this._get(e,"beforeShowDay"),v=this._get(e,"showOtherMonths"),y=this._get(e,"selectOtherMonths"),b=this._getDefaultDate(e),_="",w=0;K[0]>w;w++){for(k="",this.maxRows=4,T=0;K[1]>T;T++){if(D=this._daylightSavingAdjust(new Date(et,Z,e.selectedDay)),S=" ui-corner-all",M="",Q){if(M+="<div class='ui-datepicker-group",K[1]>1)switch(T){case 0:M+=" ui-datepicker-group-first",S=" ui-corner-"+(Y?"right":"left");break;case K[1]-1:M+=" ui-datepicker-group-last",S=" ui-corner-"+(Y?"left":"right");break;default:M+=" ui-datepicker-group-middle",S=""}M+="'>"}for(M+="<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix"+S+"'>"+(/all|left/.test(S)&&0===w?Y?a:s:"")+(/all|right/.test(S)&&0===w?Y?s:a:"")+this._generateMonthYearHeader(e,Z,et,X,$,w>0||T>0,f,m)+"</div><table class='ui-datepicker-calendar'><thead>"+"<tr>",C=d?"<th class='ui-datepicker-week-col'>"+this._get(e,"weekHeader")+"</th>":"",x=0;7>x;x++)N=(x+u)%7,C+="<th scope='col'"+((x+u+6)%7>=5?" class='ui-datepicker-week-end'":"")+">"+"<span title='"+c[N]+"'>"+p[N]+"</span></th>";for(M+=C+"</tr></thead><tbody>",A=this._getDaysInMonth(et,Z),et===e.selectedYear&&Z===e.selectedMonth&&(e.selectedDay=Math.min(e.selectedDay,A)),P=(this._getFirstDayOfMonth(et,Z)-u+7)%7,I=Math.ceil((P+A)/7),H=Q?this.maxRows>I?this.maxRows:I:I,this.maxRows=H,z=this._daylightSavingAdjust(new Date(et,Z,1-P)),F=0;H>F;F++){for(M+="<tr>",E=d?"<td class='ui-datepicker-week-col'>"+this._get(e,"calculateWeek")(z)+"</td>":"",x=0;7>x;x++)O=g?g.apply(e.input?e.input[0]:null,[z]):[!0,""],j=z.getMonth()!==Z,W=j&&!y||!O[0]||X&&X>z||$&&z>$,E+="<td class='"+((x+u+6)%7>=5?" ui-datepicker-week-end":"")+(j?" ui-datepicker-other-month":"")+(z.getTime()===D.getTime()&&Z===e.selectedMonth&&e._keyEvent||b.getTime()===z.getTime()&&b.getTime()===D.getTime()?" "+this._dayOverClass:"")+(W?" "+this._unselectableClass+" ui-state-disabled":"")+(j&&!v?"":" "+O[1]+(z.getTime()===G.getTime()?" "+this._currentClass:"")+(z.getTime()===R.getTime()?" ui-datepicker-today":""))+"'"+(j&&!v||!O[2]?"":" title='"+O[2].replace(/'/g,"&#39;")+"'")+(W?"":" data-handler='selectDay' data-event='click' data-month='"+z.getMonth()+"' data-year='"+z.getFullYear()+"'")+">"+(j&&!v?"&#xa0;":W?"<span class='ui-state-default'>"+z.getDate()+"</span>":"<a class='ui-state-default"+(z.getTime()===R.getTime()?" ui-state-highlight":"")+(z.getTime()===G.getTime()?" ui-state-active":"")+(j?" ui-priority-secondary":"")+"' href='#'>"+z.getDate()+"</a>")+"</td>",z.setDate(z.getDate()+1),z=this._daylightSavingAdjust(z);M+=E+"</tr>"}Z++,Z>11&&(Z=0,et++),M+="</tbody></table>"+(Q?"</div>"+(K[0]>0&&T===K[1]-1?"<div class='ui-datepicker-row-break'></div>":""):""),k+=M}_+=k}return _+=l,e._keyEvent=!1,_},_generateMonthYearHeader:function(e,t,i,s,n,a,o,r){var h,l,u,d,c,p,f,m,g=this._get(e,"changeMonth"),v=this._get(e,"changeYear"),y=this._get(e,"showMonthAfterYear"),b="<div class='ui-datepicker-title'>",_="";if(a||!g)_+="<span class='ui-datepicker-month'>"+o[t]+"</span>";else{for(h=s&&s.getFullYear()===i,l=n&&n.getFullYear()===i,_+="<select class='ui-datepicker-month' data-handler='selectMonth' data-event='change'>",u=0;12>u;u++)(!h||u>=s.getMonth())&&(!l||n.getMonth()>=u)&&(_+="<option value='"+u+"'"+(u===t?" selected='selected'":"")+">"+r[u]+"</option>");_+="</select>"}if(y||(b+=_+(!a&&g&&v?"":"&#xa0;")),!e.yearshtml)if(e.yearshtml="",a||!v)b+="<span class='ui-datepicker-year'>"+i+"</span>";else{for(d=this._get(e,"yearRange").split(":"),c=(new Date).getFullYear(),p=function(e){var t=e.match(/c[+\-].*/)?i+parseInt(e.substring(1),10):e.match(/[+\-].*/)?c+parseInt(e,10):parseInt(e,10);return isNaN(t)?c:t},f=p(d[0]),m=Math.max(f,p(d[1]||"")),f=s?Math.max(f,s.getFullYear()):f,m=n?Math.min(m,n.getFullYear()):m,e.yearshtml+="<select class='ui-datepicker-year' data-handler='selectYear' data-event='change'>";m>=f;f++)e.yearshtml+="<option value='"+f+"'"+(f===i?" selected='selected'":"")+">"+f+"</option>";e.yearshtml+="</select>",b+=e.yearshtml,e.yearshtml=null}return b+=this._get(e,"yearSuffix"),y&&(b+=(!a&&g&&v?"":"&#xa0;")+_),b+="</div>"},_adjustInstDate:function(e,t,i){var s=e.drawYear+("Y"===i?t:0),n=e.drawMonth+("M"===i?t:0),a=Math.min(e.selectedDay,this._getDaysInMonth(s,n))+("D"===i?t:0),o=this._restrictMinMax(e,this._daylightSavingAdjust(new Date(s,n,a)));e.selectedDay=o.getDate(),e.drawMonth=e.selectedMonth=o.getMonth(),e.drawYear=e.selectedYear=o.getFullYear(),("M"===i||"Y"===i)&&this._notifyChange(e)},_restrictMinMax:function(e,t){var i=this._getMinMaxDate(e,"min"),s=this._getMinMaxDate(e,"max"),n=i&&i>t?i:t;return s&&n>s?s:n},_notifyChange:function(e){var t=this._get(e,"onChangeMonthYear");t&&t.apply(e.input?e.input[0]:null,[e.selectedYear,e.selectedMonth+1,e])},_getNumberOfMonths:function(e){var t=this._get(e,"numberOfMonths");return null==t?[1,1]:"number"==typeof t?[1,t]:t},_getMinMaxDate:function(e,t){return this._determineDate(e,this._get(e,t+"Date"),null)},_getDaysInMonth:function(e,t){return 32-this._daylightSavingAdjust(new Date(e,t,32)).getDate()},_getFirstDayOfMonth:function(e,t){return new Date(e,t,1).getDay()},_canAdjustMonth:function(e,t,i,s){var n=this._getNumberOfMonths(e),a=this._daylightSavingAdjust(new Date(i,s+(0>t?t:n[0]*n[1]),1));return 0>t&&a.setDate(this._getDaysInMonth(a.getFullYear(),a.getMonth())),this._isInRange(e,a)},_isInRange:function(e,t){var i,s,n=this._getMinMaxDate(e,"min"),a=this._getMinMaxDate(e,"max"),o=null,r=null,h=this._get(e,"yearRange");return h&&(i=h.split(":"),s=(new Date).getFullYear(),o=parseInt(i[0],10),r=parseInt(i[1],10),i[0].match(/[+\-].*/)&&(o+=s),i[1].match(/[+\-].*/)&&(r+=s)),(!n||t.getTime()>=n.getTime())&&(!a||t.getTime()<=a.getTime())&&(!o||t.getFullYear()>=o)&&(!r||r>=t.getFullYear())},_getFormatConfig:function(e){var t=this._get(e,"shortYearCutoff");return t="string"!=typeof t?t:(new Date).getFullYear()%100+parseInt(t,10),{shortYearCutoff:t,dayNamesShort:this._get(e,"dayNamesShort"),dayNames:this._get(e,"dayNames"),monthNamesShort:this._get(e,"monthNamesShort"),monthNames:this._get(e,"monthNames")}},_formatDate:function(e,t,i,s){t||(e.currentDay=e.selectedDay,e.currentMonth=e.selectedMonth,e.currentYear=e.selectedYear);var n=t?"object"==typeof t?t:this._daylightSavingAdjust(new Date(s,i,t)):this._daylightSavingAdjust(new Date(e.currentYear,e.currentMonth,e.currentDay));return this.formatDate(this._get(e,"dateFormat"),n,this._getFormatConfig(e))}}),e.fn.datepicker=function(t){if(!this.length)return this;e.datepicker.initialized||(e(document).mousedown(e.datepicker._checkExternalClick),e.datepicker.initialized=!0),0===e("#"+e.datepicker._mainDivId).length&&e("body").append(e.datepicker.dpDiv);var i=Array.prototype.slice.call(arguments,1);return"string"!=typeof t||"isDisabled"!==t&&"getDate"!==t&&"widget"!==t?"option"===t&&2===arguments.length&&"string"==typeof arguments[1]?e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this[0]].concat(i)):this.each(function(){"string"==typeof t?e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this].concat(i)):e.datepicker._attachDatepicker(this,t)}):e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this[0]].concat(i))},e.datepicker=new n,e.datepicker.initialized=!1,e.datepicker.uuid=(new Date).getTime(),e.datepicker.version="1.11.4",e.datepicker,e.widget("ui.draggable",e.ui.mouse,{version:"1.11.4",widgetEventPrefix:"drag",options:{addClasses:!0,appendTo:"parent",axis:!1,connectToSortable:!1,containment:!1,cursor:"auto",cursorAt:!1,grid:!1,handle:!1,helper:"original",iframeFix:!1,opacity:!1,refreshPositions:!1,revert:!1,revertDuration:500,scope:"default",scroll:!0,scrollSensitivity:20,scrollSpeed:20,snap:!1,snapMode:"both",snapTolerance:20,stack:!1,zIndex:!1,drag:null,start:null,stop:null},_create:function(){"original"===this.options.helper&&this._setPositionRelative(),this.options.addClasses&&this.element.addClass("ui-draggable"),this.options.disabled&&this.element.addClass("ui-draggable-disabled"),this._setHandleClassName(),this._mouseInit()},_setOption:function(e,t){this._super(e,t),"handle"===e&&(this._removeHandleClassName(),this._setHandleClassName())},_destroy:function(){return(this.helper||this.element).is(".ui-draggable-dragging")?(this.destroyOnClear=!0,void 0):(this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"),this._removeHandleClassName(),this._mouseDestroy(),void 0)},_mouseCapture:function(t){var i=this.options;return this._blurActiveElement(t),this.helper||i.disabled||e(t.target).closest(".ui-resizable-handle").length>0?!1:(this.handle=this._getHandle(t),this.handle?(this._blockFrames(i.iframeFix===!0?"iframe":i.iframeFix),!0):!1)},_blockFrames:function(t){this.iframeBlocks=this.document.find(t).map(function(){var t=e(this);return e("<div>").css("position","absolute").appendTo(t.parent()).outerWidth(t.outerWidth()).outerHeight(t.outerHeight()).offset(t.offset())[0]})},_unblockFrames:function(){this.iframeBlocks&&(this.iframeBlocks.remove(),delete this.iframeBlocks)},_blurActiveElement:function(t){var i=this.document[0];if(this.handleElement.is(t.target))try{i.activeElement&&"body"!==i.activeElement.nodeName.toLowerCase()&&e(i.activeElement).blur()}catch(s){}},_mouseStart:function(t){var i=this.options;return this.helper=this._createHelper(t),this.helper.addClass("ui-draggable-dragging"),this._cacheHelperProportions(),e.ui.ddmanager&&(e.ui.ddmanager.current=this),this._cacheMargins(),this.cssPosition=this.helper.css("position"),this.scrollParent=this.helper.scrollParent(!0),this.offsetParent=this.helper.offsetParent(),this.hasFixedAncestor=this.helper.parents().filter(function(){return"fixed"===e(this).css("position")}).length>0,this.positionAbs=this.element.offset(),this._refreshOffsets(t),this.originalPosition=this.position=this._generatePosition(t,!1),this.originalPageX=t.pageX,this.originalPageY=t.pageY,i.cursorAt&&this._adjustOffsetFromHelper(i.cursorAt),this._setContainment(),this._trigger("start",t)===!1?(this._clear(),!1):(this._cacheHelperProportions(),e.ui.ddmanager&&!i.dropBehaviour&&e.ui.ddmanager.prepareOffsets(this,t),this._normalizeRightBottom(),this._mouseDrag(t,!0),e.ui.ddmanager&&e.ui.ddmanager.dragStart(this,t),!0)},_refreshOffsets:function(e){this.offset={top:this.positionAbs.top-this.margins.top,left:this.positionAbs.left-this.margins.left,scroll:!1,parent:this._getParentOffset(),relative:this._getRelativeOffset()},this.offset.click={left:e.pageX-this.offset.left,top:e.pageY-this.offset.top}},_mouseDrag:function(t,i){if(this.hasFixedAncestor&&(this.offset.parent=this._getParentOffset()),this.position=this._generatePosition(t,!0),this.positionAbs=this._convertPositionTo("absolute"),!i){var s=this._uiHash();if(this._trigger("drag",t,s)===!1)return this._mouseUp({}),!1;this.position=s.position}return this.helper[0].style.left=this.position.left+"px",this.helper[0].style.top=this.position.top+"px",e.ui.ddmanager&&e.ui.ddmanager.drag(this,t),!1},_mouseStop:function(t){var i=this,s=!1;return e.ui.ddmanager&&!this.options.dropBehaviour&&(s=e.ui.ddmanager.drop(this,t)),this.dropped&&(s=this.dropped,this.dropped=!1),"invalid"===this.options.revert&&!s||"valid"===this.options.revert&&s||this.options.revert===!0||e.isFunction(this.options.revert)&&this.options.revert.call(this.element,s)?e(this.helper).animate(this.originalPosition,parseInt(this.options.revertDuration,10),function(){i._trigger("stop",t)!==!1&&i._clear()}):this._trigger("stop",t)!==!1&&this._clear(),!1},_mouseUp:function(t){return this._unblockFrames(),e.ui.ddmanager&&e.ui.ddmanager.dragStop(this,t),this.handleElement.is(t.target)&&this.element.focus(),e.ui.mouse.prototype._mouseUp.call(this,t)},cancel:function(){return this.helper.is(".ui-draggable-dragging")?this._mouseUp({}):this._clear(),this},_getHandle:function(t){return this.options.handle?!!e(t.target).closest(this.element.find(this.options.handle)).length:!0},_setHandleClassName:function(){this.handleElement=this.options.handle?this.element.find(this.options.handle):this.element,this.handleElement.addClass("ui-draggable-handle")},_removeHandleClassName:function(){this.handleElement.removeClass("ui-draggable-handle")},_createHelper:function(t){var i=this.options,s=e.isFunction(i.helper),n=s?e(i.helper.apply(this.element[0],[t])):"clone"===i.helper?this.element.clone().removeAttr("id"):this.element;return n.parents("body").length||n.appendTo("parent"===i.appendTo?this.element[0].parentNode:i.appendTo),s&&n[0]===this.element[0]&&this._setPositionRelative(),n[0]===this.element[0]||/(fixed|absolute)/.test(n.css("position"))||n.css("position","absolute"),n},_setPositionRelative:function(){/^(?:r|a|f)/.test(this.element.css("position"))||(this.element[0].style.position="relative")},_adjustOffsetFromHelper:function(t){"string"==typeof t&&(t=t.split(" ")),e.isArray(t)&&(t={left:+t[0],top:+t[1]||0}),"left"in t&&(this.offset.click.left=t.left+this.margins.left),"right"in t&&(this.offset.click.left=this.helperProportions.width-t.right+this.margins.left),"top"in t&&(this.offset.click.top=t.top+this.margins.top),"bottom"in t&&(this.offset.click.top=this.helperProportions.height-t.bottom+this.margins.top)},_isRootNode:function(e){return/(html|body)/i.test(e.tagName)||e===this.document[0]},_getParentOffset:function(){var t=this.offsetParent.offset(),i=this.document[0];return"absolute"===this.cssPosition&&this.scrollParent[0]!==i&&e.contains(this.scrollParent[0],this.offsetParent[0])&&(t.left+=this.scrollParent.scrollLeft(),t.top+=this.scrollParent.scrollTop()),this._isRootNode(this.offsetParent[0])&&(t={top:0,left:0}),{top:t.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:t.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if("relative"!==this.cssPosition)return{top:0,left:0};var e=this.element.position(),t=this._isRootNode(this.scrollParent[0]);return{top:e.top-(parseInt(this.helper.css("top"),10)||0)+(t?0:this.scrollParent.scrollTop()),left:e.left-(parseInt(this.helper.css("left"),10)||0)+(t?0:this.scrollParent.scrollLeft())}},_cacheMargins:function(){this.margins={left:parseInt(this.element.css("marginLeft"),10)||0,top:parseInt(this.element.css("marginTop"),10)||0,right:parseInt(this.element.css("marginRight"),10)||0,bottom:parseInt(this.element.css("marginBottom"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var t,i,s,n=this.options,a=this.document[0];return this.relativeContainer=null,n.containment?"window"===n.containment?(this.containment=[e(window).scrollLeft()-this.offset.relative.left-this.offset.parent.left,e(window).scrollTop()-this.offset.relative.top-this.offset.parent.top,e(window).scrollLeft()+e(window).width()-this.helperProportions.width-this.margins.left,e(window).scrollTop()+(e(window).height()||a.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top],void 0):"document"===n.containment?(this.containment=[0,0,e(a).width()-this.helperProportions.width-this.margins.left,(e(a).height()||a.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top],void 0):n.containment.constructor===Array?(this.containment=n.containment,void 0):("parent"===n.containment&&(n.containment=this.helper[0].parentNode),i=e(n.containment),s=i[0],s&&(t=/(scroll|auto)/.test(i.css("overflow")),this.containment=[(parseInt(i.css("borderLeftWidth"),10)||0)+(parseInt(i.css("paddingLeft"),10)||0),(parseInt(i.css("borderTopWidth"),10)||0)+(parseInt(i.css("paddingTop"),10)||0),(t?Math.max(s.scrollWidth,s.offsetWidth):s.offsetWidth)-(parseInt(i.css("borderRightWidth"),10)||0)-(parseInt(i.css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left-this.margins.right,(t?Math.max(s.scrollHeight,s.offsetHeight):s.offsetHeight)-(parseInt(i.css("borderBottomWidth"),10)||0)-(parseInt(i.css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top-this.margins.bottom],this.relativeContainer=i),void 0):(this.containment=null,void 0)
},_convertPositionTo:function(e,t){t||(t=this.position);var i="absolute"===e?1:-1,s=this._isRootNode(this.scrollParent[0]);return{top:t.top+this.offset.relative.top*i+this.offset.parent.top*i-("fixed"===this.cssPosition?-this.offset.scroll.top:s?0:this.offset.scroll.top)*i,left:t.left+this.offset.relative.left*i+this.offset.parent.left*i-("fixed"===this.cssPosition?-this.offset.scroll.left:s?0:this.offset.scroll.left)*i}},_generatePosition:function(e,t){var i,s,n,a,o=this.options,r=this._isRootNode(this.scrollParent[0]),h=e.pageX,l=e.pageY;return r&&this.offset.scroll||(this.offset.scroll={top:this.scrollParent.scrollTop(),left:this.scrollParent.scrollLeft()}),t&&(this.containment&&(this.relativeContainer?(s=this.relativeContainer.offset(),i=[this.containment[0]+s.left,this.containment[1]+s.top,this.containment[2]+s.left,this.containment[3]+s.top]):i=this.containment,e.pageX-this.offset.click.left<i[0]&&(h=i[0]+this.offset.click.left),e.pageY-this.offset.click.top<i[1]&&(l=i[1]+this.offset.click.top),e.pageX-this.offset.click.left>i[2]&&(h=i[2]+this.offset.click.left),e.pageY-this.offset.click.top>i[3]&&(l=i[3]+this.offset.click.top)),o.grid&&(n=o.grid[1]?this.originalPageY+Math.round((l-this.originalPageY)/o.grid[1])*o.grid[1]:this.originalPageY,l=i?n-this.offset.click.top>=i[1]||n-this.offset.click.top>i[3]?n:n-this.offset.click.top>=i[1]?n-o.grid[1]:n+o.grid[1]:n,a=o.grid[0]?this.originalPageX+Math.round((h-this.originalPageX)/o.grid[0])*o.grid[0]:this.originalPageX,h=i?a-this.offset.click.left>=i[0]||a-this.offset.click.left>i[2]?a:a-this.offset.click.left>=i[0]?a-o.grid[0]:a+o.grid[0]:a),"y"===o.axis&&(h=this.originalPageX),"x"===o.axis&&(l=this.originalPageY)),{top:l-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+("fixed"===this.cssPosition?-this.offset.scroll.top:r?0:this.offset.scroll.top),left:h-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+("fixed"===this.cssPosition?-this.offset.scroll.left:r?0:this.offset.scroll.left)}},_clear:function(){this.helper.removeClass("ui-draggable-dragging"),this.helper[0]===this.element[0]||this.cancelHelperRemoval||this.helper.remove(),this.helper=null,this.cancelHelperRemoval=!1,this.destroyOnClear&&this.destroy()},_normalizeRightBottom:function(){"y"!==this.options.axis&&"auto"!==this.helper.css("right")&&(this.helper.width(this.helper.width()),this.helper.css("right","auto")),"x"!==this.options.axis&&"auto"!==this.helper.css("bottom")&&(this.helper.height(this.helper.height()),this.helper.css("bottom","auto"))},_trigger:function(t,i,s){return s=s||this._uiHash(),e.ui.plugin.call(this,t,[i,s,this],!0),/^(drag|start|stop)/.test(t)&&(this.positionAbs=this._convertPositionTo("absolute"),s.offset=this.positionAbs),e.Widget.prototype._trigger.call(this,t,i,s)},plugins:{},_uiHash:function(){return{helper:this.helper,position:this.position,originalPosition:this.originalPosition,offset:this.positionAbs}}}),e.ui.plugin.add("draggable","connectToSortable",{start:function(t,i,s){var n=e.extend({},i,{item:s.element});s.sortables=[],e(s.options.connectToSortable).each(function(){var i=e(this).sortable("instance");i&&!i.options.disabled&&(s.sortables.push(i),i.refreshPositions(),i._trigger("activate",t,n))})},stop:function(t,i,s){var n=e.extend({},i,{item:s.element});s.cancelHelperRemoval=!1,e.each(s.sortables,function(){var e=this;e.isOver?(e.isOver=0,s.cancelHelperRemoval=!0,e.cancelHelperRemoval=!1,e._storedCSS={position:e.placeholder.css("position"),top:e.placeholder.css("top"),left:e.placeholder.css("left")},e._mouseStop(t),e.options.helper=e.options._helper):(e.cancelHelperRemoval=!0,e._trigger("deactivate",t,n))})},drag:function(t,i,s){e.each(s.sortables,function(){var n=!1,a=this;a.positionAbs=s.positionAbs,a.helperProportions=s.helperProportions,a.offset.click=s.offset.click,a._intersectsWith(a.containerCache)&&(n=!0,e.each(s.sortables,function(){return this.positionAbs=s.positionAbs,this.helperProportions=s.helperProportions,this.offset.click=s.offset.click,this!==a&&this._intersectsWith(this.containerCache)&&e.contains(a.element[0],this.element[0])&&(n=!1),n})),n?(a.isOver||(a.isOver=1,s._parent=i.helper.parent(),a.currentItem=i.helper.appendTo(a.element).data("ui-sortable-item",!0),a.options._helper=a.options.helper,a.options.helper=function(){return i.helper[0]},t.target=a.currentItem[0],a._mouseCapture(t,!0),a._mouseStart(t,!0,!0),a.offset.click.top=s.offset.click.top,a.offset.click.left=s.offset.click.left,a.offset.parent.left-=s.offset.parent.left-a.offset.parent.left,a.offset.parent.top-=s.offset.parent.top-a.offset.parent.top,s._trigger("toSortable",t),s.dropped=a.element,e.each(s.sortables,function(){this.refreshPositions()}),s.currentItem=s.element,a.fromOutside=s),a.currentItem&&(a._mouseDrag(t),i.position=a.position)):a.isOver&&(a.isOver=0,a.cancelHelperRemoval=!0,a.options._revert=a.options.revert,a.options.revert=!1,a._trigger("out",t,a._uiHash(a)),a._mouseStop(t,!0),a.options.revert=a.options._revert,a.options.helper=a.options._helper,a.placeholder&&a.placeholder.remove(),i.helper.appendTo(s._parent),s._refreshOffsets(t),i.position=s._generatePosition(t,!0),s._trigger("fromSortable",t),s.dropped=!1,e.each(s.sortables,function(){this.refreshPositions()}))})}}),e.ui.plugin.add("draggable","cursor",{start:function(t,i,s){var n=e("body"),a=s.options;n.css("cursor")&&(a._cursor=n.css("cursor")),n.css("cursor",a.cursor)},stop:function(t,i,s){var n=s.options;n._cursor&&e("body").css("cursor",n._cursor)}}),e.ui.plugin.add("draggable","opacity",{start:function(t,i,s){var n=e(i.helper),a=s.options;n.css("opacity")&&(a._opacity=n.css("opacity")),n.css("opacity",a.opacity)},stop:function(t,i,s){var n=s.options;n._opacity&&e(i.helper).css("opacity",n._opacity)}}),e.ui.plugin.add("draggable","scroll",{start:function(e,t,i){i.scrollParentNotHidden||(i.scrollParentNotHidden=i.helper.scrollParent(!1)),i.scrollParentNotHidden[0]!==i.document[0]&&"HTML"!==i.scrollParentNotHidden[0].tagName&&(i.overflowOffset=i.scrollParentNotHidden.offset())},drag:function(t,i,s){var n=s.options,a=!1,o=s.scrollParentNotHidden[0],r=s.document[0];o!==r&&"HTML"!==o.tagName?(n.axis&&"x"===n.axis||(s.overflowOffset.top+o.offsetHeight-t.pageY<n.scrollSensitivity?o.scrollTop=a=o.scrollTop+n.scrollSpeed:t.pageY-s.overflowOffset.top<n.scrollSensitivity&&(o.scrollTop=a=o.scrollTop-n.scrollSpeed)),n.axis&&"y"===n.axis||(s.overflowOffset.left+o.offsetWidth-t.pageX<n.scrollSensitivity?o.scrollLeft=a=o.scrollLeft+n.scrollSpeed:t.pageX-s.overflowOffset.left<n.scrollSensitivity&&(o.scrollLeft=a=o.scrollLeft-n.scrollSpeed))):(n.axis&&"x"===n.axis||(t.pageY-e(r).scrollTop()<n.scrollSensitivity?a=e(r).scrollTop(e(r).scrollTop()-n.scrollSpeed):e(window).height()-(t.pageY-e(r).scrollTop())<n.scrollSensitivity&&(a=e(r).scrollTop(e(r).scrollTop()+n.scrollSpeed))),n.axis&&"y"===n.axis||(t.pageX-e(r).scrollLeft()<n.scrollSensitivity?a=e(r).scrollLeft(e(r).scrollLeft()-n.scrollSpeed):e(window).width()-(t.pageX-e(r).scrollLeft())<n.scrollSensitivity&&(a=e(r).scrollLeft(e(r).scrollLeft()+n.scrollSpeed)))),a!==!1&&e.ui.ddmanager&&!n.dropBehaviour&&e.ui.ddmanager.prepareOffsets(s,t)}}),e.ui.plugin.add("draggable","snap",{start:function(t,i,s){var n=s.options;s.snapElements=[],e(n.snap.constructor!==String?n.snap.items||":data(ui-draggable)":n.snap).each(function(){var t=e(this),i=t.offset();this!==s.element[0]&&s.snapElements.push({item:this,width:t.outerWidth(),height:t.outerHeight(),top:i.top,left:i.left})})},drag:function(t,i,s){var n,a,o,r,h,l,u,d,c,p,f=s.options,m=f.snapTolerance,g=i.offset.left,v=g+s.helperProportions.width,y=i.offset.top,b=y+s.helperProportions.height;for(c=s.snapElements.length-1;c>=0;c--)h=s.snapElements[c].left-s.margins.left,l=h+s.snapElements[c].width,u=s.snapElements[c].top-s.margins.top,d=u+s.snapElements[c].height,h-m>v||g>l+m||u-m>b||y>d+m||!e.contains(s.snapElements[c].item.ownerDocument,s.snapElements[c].item)?(s.snapElements[c].snapping&&s.options.snap.release&&s.options.snap.release.call(s.element,t,e.extend(s._uiHash(),{snapItem:s.snapElements[c].item})),s.snapElements[c].snapping=!1):("inner"!==f.snapMode&&(n=m>=Math.abs(u-b),a=m>=Math.abs(d-y),o=m>=Math.abs(h-v),r=m>=Math.abs(l-g),n&&(i.position.top=s._convertPositionTo("relative",{top:u-s.helperProportions.height,left:0}).top),a&&(i.position.top=s._convertPositionTo("relative",{top:d,left:0}).top),o&&(i.position.left=s._convertPositionTo("relative",{top:0,left:h-s.helperProportions.width}).left),r&&(i.position.left=s._convertPositionTo("relative",{top:0,left:l}).left)),p=n||a||o||r,"outer"!==f.snapMode&&(n=m>=Math.abs(u-y),a=m>=Math.abs(d-b),o=m>=Math.abs(h-g),r=m>=Math.abs(l-v),n&&(i.position.top=s._convertPositionTo("relative",{top:u,left:0}).top),a&&(i.position.top=s._convertPositionTo("relative",{top:d-s.helperProportions.height,left:0}).top),o&&(i.position.left=s._convertPositionTo("relative",{top:0,left:h}).left),r&&(i.position.left=s._convertPositionTo("relative",{top:0,left:l-s.helperProportions.width}).left)),!s.snapElements[c].snapping&&(n||a||o||r||p)&&s.options.snap.snap&&s.options.snap.snap.call(s.element,t,e.extend(s._uiHash(),{snapItem:s.snapElements[c].item})),s.snapElements[c].snapping=n||a||o||r||p)}}),e.ui.plugin.add("draggable","stack",{start:function(t,i,s){var n,a=s.options,o=e.makeArray(e(a.stack)).sort(function(t,i){return(parseInt(e(t).css("zIndex"),10)||0)-(parseInt(e(i).css("zIndex"),10)||0)});o.length&&(n=parseInt(e(o[0]).css("zIndex"),10)||0,e(o).each(function(t){e(this).css("zIndex",n+t)}),this.css("zIndex",n+o.length))}}),e.ui.plugin.add("draggable","zIndex",{start:function(t,i,s){var n=e(i.helper),a=s.options;n.css("zIndex")&&(a._zIndex=n.css("zIndex")),n.css("zIndex",a.zIndex)},stop:function(t,i,s){var n=s.options;n._zIndex&&e(i.helper).css("zIndex",n._zIndex)}}),e.ui.draggable,e.widget("ui.resizable",e.ui.mouse,{version:"1.11.4",widgetEventPrefix:"resize",options:{alsoResize:!1,animate:!1,animateDuration:"slow",animateEasing:"swing",aspectRatio:!1,autoHide:!1,containment:!1,ghost:!1,grid:!1,handles:"e,s,se",helper:!1,maxHeight:null,maxWidth:null,minHeight:10,minWidth:10,zIndex:90,resize:null,start:null,stop:null},_num:function(e){return parseInt(e,10)||0},_isNumber:function(e){return!isNaN(parseInt(e,10))},_hasScroll:function(t,i){if("hidden"===e(t).css("overflow"))return!1;var s=i&&"left"===i?"scrollLeft":"scrollTop",n=!1;return t[s]>0?!0:(t[s]=1,n=t[s]>0,t[s]=0,n)},_create:function(){var t,i,s,n,a,o=this,r=this.options;if(this.element.addClass("ui-resizable"),e.extend(this,{_aspectRatio:!!r.aspectRatio,aspectRatio:r.aspectRatio,originalElement:this.element,_proportionallyResizeElements:[],_helper:r.helper||r.ghost||r.animate?r.helper||"ui-resizable-helper":null}),this.element[0].nodeName.match(/^(canvas|textarea|input|select|button|img)$/i)&&(this.element.wrap(e("<div class='ui-wrapper' style='overflow: hidden;'></div>").css({position:this.element.css("position"),width:this.element.outerWidth(),height:this.element.outerHeight(),top:this.element.css("top"),left:this.element.css("left")})),this.element=this.element.parent().data("ui-resizable",this.element.resizable("instance")),this.elementIsWrapper=!0,this.element.css({marginLeft:this.originalElement.css("marginLeft"),marginTop:this.originalElement.css("marginTop"),marginRight:this.originalElement.css("marginRight"),marginBottom:this.originalElement.css("marginBottom")}),this.originalElement.css({marginLeft:0,marginTop:0,marginRight:0,marginBottom:0}),this.originalResizeStyle=this.originalElement.css("resize"),this.originalElement.css("resize","none"),this._proportionallyResizeElements.push(this.originalElement.css({position:"static",zoom:1,display:"block"})),this.originalElement.css({margin:this.originalElement.css("margin")}),this._proportionallyResize()),this.handles=r.handles||(e(".ui-resizable-handle",this.element).length?{n:".ui-resizable-n",e:".ui-resizable-e",s:".ui-resizable-s",w:".ui-resizable-w",se:".ui-resizable-se",sw:".ui-resizable-sw",ne:".ui-resizable-ne",nw:".ui-resizable-nw"}:"e,s,se"),this._handles=e(),this.handles.constructor===String)for("all"===this.handles&&(this.handles="n,e,s,w,se,sw,ne,nw"),t=this.handles.split(","),this.handles={},i=0;t.length>i;i++)s=e.trim(t[i]),a="ui-resizable-"+s,n=e("<div class='ui-resizable-handle "+a+"'></div>"),n.css({zIndex:r.zIndex}),"se"===s&&n.addClass("ui-icon ui-icon-gripsmall-diagonal-se"),this.handles[s]=".ui-resizable-"+s,this.element.append(n);this._renderAxis=function(t){var i,s,n,a;t=t||this.element;for(i in this.handles)this.handles[i].constructor===String?this.handles[i]=this.element.children(this.handles[i]).first().show():(this.handles[i].jquery||this.handles[i].nodeType)&&(this.handles[i]=e(this.handles[i]),this._on(this.handles[i],{mousedown:o._mouseDown})),this.elementIsWrapper&&this.originalElement[0].nodeName.match(/^(textarea|input|select|button)$/i)&&(s=e(this.handles[i],this.element),a=/sw|ne|nw|se|n|s/.test(i)?s.outerHeight():s.outerWidth(),n=["padding",/ne|nw|n/.test(i)?"Top":/se|sw|s/.test(i)?"Bottom":/^e$/.test(i)?"Right":"Left"].join(""),t.css(n,a),this._proportionallyResize()),this._handles=this._handles.add(this.handles[i])},this._renderAxis(this.element),this._handles=this._handles.add(this.element.find(".ui-resizable-handle")),this._handles.disableSelection(),this._handles.mouseover(function(){o.resizing||(this.className&&(n=this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)),o.axis=n&&n[1]?n[1]:"se")}),r.autoHide&&(this._handles.hide(),e(this.element).addClass("ui-resizable-autohide").mouseenter(function(){r.disabled||(e(this).removeClass("ui-resizable-autohide"),o._handles.show())}).mouseleave(function(){r.disabled||o.resizing||(e(this).addClass("ui-resizable-autohide"),o._handles.hide())})),this._mouseInit()},_destroy:function(){this._mouseDestroy();var t,i=function(t){e(t).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove()};return this.elementIsWrapper&&(i(this.element),t=this.element,this.originalElement.css({position:t.css("position"),width:t.outerWidth(),height:t.outerHeight(),top:t.css("top"),left:t.css("left")}).insertAfter(t),t.remove()),this.originalElement.css("resize",this.originalResizeStyle),i(this.originalElement),this},_mouseCapture:function(t){var i,s,n=!1;for(i in this.handles)s=e(this.handles[i])[0],(s===t.target||e.contains(s,t.target))&&(n=!0);return!this.options.disabled&&n},_mouseStart:function(t){var i,s,n,a=this.options,o=this.element;return this.resizing=!0,this._renderProxy(),i=this._num(this.helper.css("left")),s=this._num(this.helper.css("top")),a.containment&&(i+=e(a.containment).scrollLeft()||0,s+=e(a.containment).scrollTop()||0),this.offset=this.helper.offset(),this.position={left:i,top:s},this.size=this._helper?{width:this.helper.width(),height:this.helper.height()}:{width:o.width(),height:o.height()},this.originalSize=this._helper?{width:o.outerWidth(),height:o.outerHeight()}:{width:o.width(),height:o.height()},this.sizeDiff={width:o.outerWidth()-o.width(),height:o.outerHeight()-o.height()},this.originalPosition={left:i,top:s},this.originalMousePosition={left:t.pageX,top:t.pageY},this.aspectRatio="number"==typeof a.aspectRatio?a.aspectRatio:this.originalSize.width/this.originalSize.height||1,n=e(".ui-resizable-"+this.axis).css("cursor"),e("body").css("cursor","auto"===n?this.axis+"-resize":n),o.addClass("ui-resizable-resizing"),this._propagate("start",t),!0},_mouseDrag:function(t){var i,s,n=this.originalMousePosition,a=this.axis,o=t.pageX-n.left||0,r=t.pageY-n.top||0,h=this._change[a];return this._updatePrevProperties(),h?(i=h.apply(this,[t,o,r]),this._updateVirtualBoundaries(t.shiftKey),(this._aspectRatio||t.shiftKey)&&(i=this._updateRatio(i,t)),i=this._respectSize(i,t),this._updateCache(i),this._propagate("resize",t),s=this._applyChanges(),!this._helper&&this._proportionallyResizeElements.length&&this._proportionallyResize(),e.isEmptyObject(s)||(this._updatePrevProperties(),this._trigger("resize",t,this.ui()),this._applyChanges()),!1):!1},_mouseStop:function(t){this.resizing=!1;var i,s,n,a,o,r,h,l=this.options,u=this;return this._helper&&(i=this._proportionallyResizeElements,s=i.length&&/textarea/i.test(i[0].nodeName),n=s&&this._hasScroll(i[0],"left")?0:u.sizeDiff.height,a=s?0:u.sizeDiff.width,o={width:u.helper.width()-a,height:u.helper.height()-n},r=parseInt(u.element.css("left"),10)+(u.position.left-u.originalPosition.left)||null,h=parseInt(u.element.css("top"),10)+(u.position.top-u.originalPosition.top)||null,l.animate||this.element.css(e.extend(o,{top:h,left:r})),u.helper.height(u.size.height),u.helper.width(u.size.width),this._helper&&!l.animate&&this._proportionallyResize()),e("body").css("cursor","auto"),this.element.removeClass("ui-resizable-resizing"),this._propagate("stop",t),this._helper&&this.helper.remove(),!1},_updatePrevProperties:function(){this.prevPosition={top:this.position.top,left:this.position.left},this.prevSize={width:this.size.width,height:this.size.height}},_applyChanges:function(){var e={};return this.position.top!==this.prevPosition.top&&(e.top=this.position.top+"px"),this.position.left!==this.prevPosition.left&&(e.left=this.position.left+"px"),this.size.width!==this.prevSize.width&&(e.width=this.size.width+"px"),this.size.height!==this.prevSize.height&&(e.height=this.size.height+"px"),this.helper.css(e),e},_updateVirtualBoundaries:function(e){var t,i,s,n,a,o=this.options;a={minWidth:this._isNumber(o.minWidth)?o.minWidth:0,maxWidth:this._isNumber(o.maxWidth)?o.maxWidth:1/0,minHeight:this._isNumber(o.minHeight)?o.minHeight:0,maxHeight:this._isNumber(o.maxHeight)?o.maxHeight:1/0},(this._aspectRatio||e)&&(t=a.minHeight*this.aspectRatio,s=a.minWidth/this.aspectRatio,i=a.maxHeight*this.aspectRatio,n=a.maxWidth/this.aspectRatio,t>a.minWidth&&(a.minWidth=t),s>a.minHeight&&(a.minHeight=s),a.maxWidth>i&&(a.maxWidth=i),a.maxHeight>n&&(a.maxHeight=n)),this._vBoundaries=a},_updateCache:function(e){this.offset=this.helper.offset(),this._isNumber(e.left)&&(this.position.left=e.left),this._isNumber(e.top)&&(this.position.top=e.top),this._isNumber(e.height)&&(this.size.height=e.height),this._isNumber(e.width)&&(this.size.width=e.width)},_updateRatio:function(e){var t=this.position,i=this.size,s=this.axis;return this._isNumber(e.height)?e.width=e.height*this.aspectRatio:this._isNumber(e.width)&&(e.height=e.width/this.aspectRatio),"sw"===s&&(e.left=t.left+(i.width-e.width),e.top=null),"nw"===s&&(e.top=t.top+(i.height-e.height),e.left=t.left+(i.width-e.width)),e},_respectSize:function(e){var t=this._vBoundaries,i=this.axis,s=this._isNumber(e.width)&&t.maxWidth&&t.maxWidth<e.width,n=this._isNumber(e.height)&&t.maxHeight&&t.maxHeight<e.height,a=this._isNumber(e.width)&&t.minWidth&&t.minWidth>e.width,o=this._isNumber(e.height)&&t.minHeight&&t.minHeight>e.height,r=this.originalPosition.left+this.originalSize.width,h=this.position.top+this.size.height,l=/sw|nw|w/.test(i),u=/nw|ne|n/.test(i);return a&&(e.width=t.minWidth),o&&(e.height=t.minHeight),s&&(e.width=t.maxWidth),n&&(e.height=t.maxHeight),a&&l&&(e.left=r-t.minWidth),s&&l&&(e.left=r-t.maxWidth),o&&u&&(e.top=h-t.minHeight),n&&u&&(e.top=h-t.maxHeight),e.width||e.height||e.left||!e.top?e.width||e.height||e.top||!e.left||(e.left=null):e.top=null,e},_getPaddingPlusBorderDimensions:function(e){for(var t=0,i=[],s=[e.css("borderTopWidth"),e.css("borderRightWidth"),e.css("borderBottomWidth"),e.css("borderLeftWidth")],n=[e.css("paddingTop"),e.css("paddingRight"),e.css("paddingBottom"),e.css("paddingLeft")];4>t;t++)i[t]=parseInt(s[t],10)||0,i[t]+=parseInt(n[t],10)||0;return{height:i[0]+i[2],width:i[1]+i[3]}},_proportionallyResize:function(){if(this._proportionallyResizeElements.length)for(var e,t=0,i=this.helper||this.element;this._proportionallyResizeElements.length>t;t++)e=this._proportionallyResizeElements[t],this.outerDimensions||(this.outerDimensions=this._getPaddingPlusBorderDimensions(e)),e.css({height:i.height()-this.outerDimensions.height||0,width:i.width()-this.outerDimensions.width||0})},_renderProxy:function(){var t=this.element,i=this.options;this.elementOffset=t.offset(),this._helper?(this.helper=this.helper||e("<div style='overflow:hidden;'></div>"),this.helper.addClass(this._helper).css({width:this.element.outerWidth()-1,height:this.element.outerHeight()-1,position:"absolute",left:this.elementOffset.left+"px",top:this.elementOffset.top+"px",zIndex:++i.zIndex}),this.helper.appendTo("body").disableSelection()):this.helper=this.element},_change:{e:function(e,t){return{width:this.originalSize.width+t}},w:function(e,t){var i=this.originalSize,s=this.originalPosition;return{left:s.left+t,width:i.width-t}},n:function(e,t,i){var s=this.originalSize,n=this.originalPosition;return{top:n.top+i,height:s.height-i}},s:function(e,t,i){return{height:this.originalSize.height+i}},se:function(t,i,s){return e.extend(this._change.s.apply(this,arguments),this._change.e.apply(this,[t,i,s]))},sw:function(t,i,s){return e.extend(this._change.s.apply(this,arguments),this._change.w.apply(this,[t,i,s]))},ne:function(t,i,s){return e.extend(this._change.n.apply(this,arguments),this._change.e.apply(this,[t,i,s]))},nw:function(t,i,s){return e.extend(this._change.n.apply(this,arguments),this._change.w.apply(this,[t,i,s]))}},_propagate:function(t,i){e.ui.plugin.call(this,t,[i,this.ui()]),"resize"!==t&&this._trigger(t,i,this.ui())},plugins:{},ui:function(){return{originalElement:this.originalElement,element:this.element,helper:this.helper,position:this.position,size:this.size,originalSize:this.originalSize,originalPosition:this.originalPosition}}}),e.ui.plugin.add("resizable","animate",{stop:function(t){var i=e(this).resizable("instance"),s=i.options,n=i._proportionallyResizeElements,a=n.length&&/textarea/i.test(n[0].nodeName),o=a&&i._hasScroll(n[0],"left")?0:i.sizeDiff.height,r=a?0:i.sizeDiff.width,h={width:i.size.width-r,height:i.size.height-o},l=parseInt(i.element.css("left"),10)+(i.position.left-i.originalPosition.left)||null,u=parseInt(i.element.css("top"),10)+(i.position.top-i.originalPosition.top)||null;i.element.animate(e.extend(h,u&&l?{top:u,left:l}:{}),{duration:s.animateDuration,easing:s.animateEasing,step:function(){var s={width:parseInt(i.element.css("width"),10),height:parseInt(i.element.css("height"),10),top:parseInt(i.element.css("top"),10),left:parseInt(i.element.css("left"),10)};n&&n.length&&e(n[0]).css({width:s.width,height:s.height}),i._updateCache(s),i._propagate("resize",t)}})}}),e.ui.plugin.add("resizable","containment",{start:function(){var t,i,s,n,a,o,r,h=e(this).resizable("instance"),l=h.options,u=h.element,d=l.containment,c=d instanceof e?d.get(0):/parent/.test(d)?u.parent().get(0):d;c&&(h.containerElement=e(c),/document/.test(d)||d===document?(h.containerOffset={left:0,top:0},h.containerPosition={left:0,top:0},h.parentData={element:e(document),left:0,top:0,width:e(document).width(),height:e(document).height()||document.body.parentNode.scrollHeight}):(t=e(c),i=[],e(["Top","Right","Left","Bottom"]).each(function(e,s){i[e]=h._num(t.css("padding"+s))}),h.containerOffset=t.offset(),h.containerPosition=t.position(),h.containerSize={height:t.innerHeight()-i[3],width:t.innerWidth()-i[1]},s=h.containerOffset,n=h.containerSize.height,a=h.containerSize.width,o=h._hasScroll(c,"left")?c.scrollWidth:a,r=h._hasScroll(c)?c.scrollHeight:n,h.parentData={element:c,left:s.left,top:s.top,width:o,height:r}))},resize:function(t){var i,s,n,a,o=e(this).resizable("instance"),r=o.options,h=o.containerOffset,l=o.position,u=o._aspectRatio||t.shiftKey,d={top:0,left:0},c=o.containerElement,p=!0;c[0]!==document&&/static/.test(c.css("position"))&&(d=h),l.left<(o._helper?h.left:0)&&(o.size.width=o.size.width+(o._helper?o.position.left-h.left:o.position.left-d.left),u&&(o.size.height=o.size.width/o.aspectRatio,p=!1),o.position.left=r.helper?h.left:0),l.top<(o._helper?h.top:0)&&(o.size.height=o.size.height+(o._helper?o.position.top-h.top:o.position.top),u&&(o.size.width=o.size.height*o.aspectRatio,p=!1),o.position.top=o._helper?h.top:0),n=o.containerElement.get(0)===o.element.parent().get(0),a=/relative|absolute/.test(o.containerElement.css("position")),n&&a?(o.offset.left=o.parentData.left+o.position.left,o.offset.top=o.parentData.top+o.position.top):(o.offset.left=o.element.offset().left,o.offset.top=o.element.offset().top),i=Math.abs(o.sizeDiff.width+(o._helper?o.offset.left-d.left:o.offset.left-h.left)),s=Math.abs(o.sizeDiff.height+(o._helper?o.offset.top-d.top:o.offset.top-h.top)),i+o.size.width>=o.parentData.width&&(o.size.width=o.parentData.width-i,u&&(o.size.height=o.size.width/o.aspectRatio,p=!1)),s+o.size.height>=o.parentData.height&&(o.size.height=o.parentData.height-s,u&&(o.size.width=o.size.height*o.aspectRatio,p=!1)),p||(o.position.left=o.prevPosition.left,o.position.top=o.prevPosition.top,o.size.width=o.prevSize.width,o.size.height=o.prevSize.height)},stop:function(){var t=e(this).resizable("instance"),i=t.options,s=t.containerOffset,n=t.containerPosition,a=t.containerElement,o=e(t.helper),r=o.offset(),h=o.outerWidth()-t.sizeDiff.width,l=o.outerHeight()-t.sizeDiff.height;t._helper&&!i.animate&&/relative/.test(a.css("position"))&&e(this).css({left:r.left-n.left-s.left,width:h,height:l}),t._helper&&!i.animate&&/static/.test(a.css("position"))&&e(this).css({left:r.left-n.left-s.left,width:h,height:l})}}),e.ui.plugin.add("resizable","alsoResize",{start:function(){var t=e(this).resizable("instance"),i=t.options;e(i.alsoResize).each(function(){var t=e(this);t.data("ui-resizable-alsoresize",{width:parseInt(t.width(),10),height:parseInt(t.height(),10),left:parseInt(t.css("left"),10),top:parseInt(t.css("top"),10)})})},resize:function(t,i){var s=e(this).resizable("instance"),n=s.options,a=s.originalSize,o=s.originalPosition,r={height:s.size.height-a.height||0,width:s.size.width-a.width||0,top:s.position.top-o.top||0,left:s.position.left-o.left||0};e(n.alsoResize).each(function(){var t=e(this),s=e(this).data("ui-resizable-alsoresize"),n={},a=t.parents(i.originalElement[0]).length?["width","height"]:["width","height","top","left"];e.each(a,function(e,t){var i=(s[t]||0)+(r[t]||0);i&&i>=0&&(n[t]=i||null)}),t.css(n)})},stop:function(){e(this).removeData("resizable-alsoresize")}}),e.ui.plugin.add("resizable","ghost",{start:function(){var t=e(this).resizable("instance"),i=t.options,s=t.size;t.ghost=t.originalElement.clone(),t.ghost.css({opacity:.25,display:"block",position:"relative",height:s.height,width:s.width,margin:0,left:0,top:0}).addClass("ui-resizable-ghost").addClass("string"==typeof i.ghost?i.ghost:""),t.ghost.appendTo(t.helper)},resize:function(){var t=e(this).resizable("instance");t.ghost&&t.ghost.css({position:"relative",height:t.size.height,width:t.size.width})},stop:function(){var t=e(this).resizable("instance");t.ghost&&t.helper&&t.helper.get(0).removeChild(t.ghost.get(0))}}),e.ui.plugin.add("resizable","grid",{resize:function(){var t,i=e(this).resizable("instance"),s=i.options,n=i.size,a=i.originalSize,o=i.originalPosition,r=i.axis,h="number"==typeof s.grid?[s.grid,s.grid]:s.grid,l=h[0]||1,u=h[1]||1,d=Math.round((n.width-a.width)/l)*l,c=Math.round((n.height-a.height)/u)*u,p=a.width+d,f=a.height+c,m=s.maxWidth&&p>s.maxWidth,g=s.maxHeight&&f>s.maxHeight,v=s.minWidth&&s.minWidth>p,y=s.minHeight&&s.minHeight>f;s.grid=h,v&&(p+=l),y&&(f+=u),m&&(p-=l),g&&(f-=u),/^(se|s|e)$/.test(r)?(i.size.width=p,i.size.height=f):/^(ne)$/.test(r)?(i.size.width=p,i.size.height=f,i.position.top=o.top-c):/^(sw)$/.test(r)?(i.size.width=p,i.size.height=f,i.position.left=o.left-d):((0>=f-u||0>=p-l)&&(t=i._getPaddingPlusBorderDimensions(this)),f-u>0?(i.size.height=f,i.position.top=o.top-c):(f=u-t.height,i.size.height=f,i.position.top=o.top+a.height-f),p-l>0?(i.size.width=p,i.position.left=o.left-d):(p=l-t.width,i.size.width=p,i.position.left=o.left+a.width-p))}}),e.ui.resizable,e.widget("ui.dialog",{version:"1.11.4",options:{appendTo:"body",autoOpen:!0,buttons:[],closeOnEscape:!0,closeText:"Close",dialogClass:"",draggable:!0,hide:null,height:"auto",maxHeight:null,maxWidth:null,minHeight:150,minWidth:150,modal:!1,position:{my:"center",at:"center",of:window,collision:"fit",using:function(t){var i=e(this).css(t).offset().top;0>i&&e(this).css("top",t.top-i)}},resizable:!0,show:null,title:null,width:300,beforeClose:null,close:null,drag:null,dragStart:null,dragStop:null,focus:null,open:null,resize:null,resizeStart:null,resizeStop:null},sizeRelatedOptions:{buttons:!0,height:!0,maxHeight:!0,maxWidth:!0,minHeight:!0,minWidth:!0,width:!0},resizableRelatedOptions:{maxHeight:!0,maxWidth:!0,minHeight:!0,minWidth:!0},_create:function(){this.originalCss={display:this.element[0].style.display,width:this.element[0].style.width,minHeight:this.element[0].style.minHeight,maxHeight:this.element[0].style.maxHeight,height:this.element[0].style.height},this.originalPosition={parent:this.element.parent(),index:this.element.parent().children().index(this.element)},this.originalTitle=this.element.attr("title"),this.options.title=this.options.title||this.originalTitle,this._createWrapper(),this.element.show().removeAttr("title").addClass("ui-dialog-content ui-widget-content").appendTo(this.uiDialog),this._createTitlebar(),this._createButtonPane(),this.options.draggable&&e.fn.draggable&&this._makeDraggable(),this.options.resizable&&e.fn.resizable&&this._makeResizable(),this._isOpen=!1,this._trackFocus()},_init:function(){this.options.autoOpen&&this.open()},_appendTo:function(){var t=this.options.appendTo;return t&&(t.jquery||t.nodeType)?e(t):this.document.find(t||"body").eq(0)},_destroy:function(){var e,t=this.originalPosition;this._untrackInstance(),this._destroyOverlay(),this.element.removeUniqueId().removeClass("ui-dialog-content ui-widget-content").css(this.originalCss).detach(),this.uiDialog.stop(!0,!0).remove(),this.originalTitle&&this.element.attr("title",this.originalTitle),e=t.parent.children().eq(t.index),e.length&&e[0]!==this.element[0]?e.before(this.element):t.parent.append(this.element)},widget:function(){return this.uiDialog},disable:e.noop,enable:e.noop,close:function(t){var i,s=this;if(this._isOpen&&this._trigger("beforeClose",t)!==!1){if(this._isOpen=!1,this._focusedElement=null,this._destroyOverlay(),this._untrackInstance(),!this.opener.filter(":focusable").focus().length)try{i=this.document[0].activeElement,i&&"body"!==i.nodeName.toLowerCase()&&e(i).blur()}catch(n){}this._hide(this.uiDialog,this.options.hide,function(){s._trigger("close",t)})}},isOpen:function(){return this._isOpen},moveToTop:function(){this._moveToTop()},_moveToTop:function(t,i){var s=!1,n=this.uiDialog.siblings(".ui-front:visible").map(function(){return+e(this).css("z-index")}).get(),a=Math.max.apply(null,n);return a>=+this.uiDialog.css("z-index")&&(this.uiDialog.css("z-index",a+1),s=!0),s&&!i&&this._trigger("focus",t),s},open:function(){var t=this;return this._isOpen?(this._moveToTop()&&this._focusTabbable(),void 0):(this._isOpen=!0,this.opener=e(this.document[0].activeElement),this._size(),this._position(),this._createOverlay(),this._moveToTop(null,!0),this.overlay&&this.overlay.css("z-index",this.uiDialog.css("z-index")-1),this._show(this.uiDialog,this.options.show,function(){t._focusTabbable(),t._trigger("focus")}),this._makeFocusTarget(),this._trigger("open"),void 0)},_focusTabbable:function(){var e=this._focusedElement;e||(e=this.element.find("[autofocus]")),e.length||(e=this.element.find(":tabbable")),e.length||(e=this.uiDialogButtonPane.find(":tabbable")),e.length||(e=this.uiDialogTitlebarClose.filter(":tabbable")),e.length||(e=this.uiDialog),e.eq(0).focus()},_keepFocus:function(t){function i(){var t=this.document[0].activeElement,i=this.uiDialog[0]===t||e.contains(this.uiDialog[0],t);i||this._focusTabbable()}t.preventDefault(),i.call(this),this._delay(i)},_createWrapper:function(){this.uiDialog=e("<div>").addClass("ui-dialog ui-widget ui-widget-content ui-corner-all ui-front "+this.options.dialogClass).hide().attr({tabIndex:-1,role:"dialog"}).appendTo(this._appendTo()),this._on(this.uiDialog,{keydown:function(t){if(this.options.closeOnEscape&&!t.isDefaultPrevented()&&t.keyCode&&t.keyCode===e.ui.keyCode.ESCAPE)return t.preventDefault(),this.close(t),void 0;
if(t.keyCode===e.ui.keyCode.TAB&&!t.isDefaultPrevented()){var i=this.uiDialog.find(":tabbable"),s=i.filter(":first"),n=i.filter(":last");t.target!==n[0]&&t.target!==this.uiDialog[0]||t.shiftKey?t.target!==s[0]&&t.target!==this.uiDialog[0]||!t.shiftKey||(this._delay(function(){n.focus()}),t.preventDefault()):(this._delay(function(){s.focus()}),t.preventDefault())}},mousedown:function(e){this._moveToTop(e)&&this._focusTabbable()}}),this.element.find("[aria-describedby]").length||this.uiDialog.attr({"aria-describedby":this.element.uniqueId().attr("id")})},_createTitlebar:function(){var t;this.uiDialogTitlebar=e("<div>").addClass("ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix").prependTo(this.uiDialog),this._on(this.uiDialogTitlebar,{mousedown:function(t){e(t.target).closest(".ui-dialog-titlebar-close")||this.uiDialog.focus()}}),this.uiDialogTitlebarClose=e("<button type='button'></button>").button({label:this.options.closeText,icons:{primary:"ui-icon-closethick"},text:!1}).addClass("ui-dialog-titlebar-close").appendTo(this.uiDialogTitlebar),this._on(this.uiDialogTitlebarClose,{click:function(e){e.preventDefault(),this.close(e)}}),t=e("<span>").uniqueId().addClass("ui-dialog-title").prependTo(this.uiDialogTitlebar),this._title(t),this.uiDialog.attr({"aria-labelledby":t.attr("id")})},_title:function(e){this.options.title||e.html("&#160;"),e.text(this.options.title)},_createButtonPane:function(){this.uiDialogButtonPane=e("<div>").addClass("ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"),this.uiButtonSet=e("<div>").addClass("ui-dialog-buttonset").appendTo(this.uiDialogButtonPane),this._createButtons()},_createButtons:function(){var t=this,i=this.options.buttons;return this.uiDialogButtonPane.remove(),this.uiButtonSet.empty(),e.isEmptyObject(i)||e.isArray(i)&&!i.length?(this.uiDialog.removeClass("ui-dialog-buttons"),void 0):(e.each(i,function(i,s){var n,a;s=e.isFunction(s)?{click:s,text:i}:s,s=e.extend({type:"button"},s),n=s.click,s.click=function(){n.apply(t.element[0],arguments)},a={icons:s.icons,text:s.showText},delete s.icons,delete s.showText,e("<button></button>",s).button(a).appendTo(t.uiButtonSet)}),this.uiDialog.addClass("ui-dialog-buttons"),this.uiDialogButtonPane.appendTo(this.uiDialog),void 0)},_makeDraggable:function(){function t(e){return{position:e.position,offset:e.offset}}var i=this,s=this.options;this.uiDialog.draggable({cancel:".ui-dialog-content, .ui-dialog-titlebar-close",handle:".ui-dialog-titlebar",containment:"document",start:function(s,n){e(this).addClass("ui-dialog-dragging"),i._blockFrames(),i._trigger("dragStart",s,t(n))},drag:function(e,s){i._trigger("drag",e,t(s))},stop:function(n,a){var o=a.offset.left-i.document.scrollLeft(),r=a.offset.top-i.document.scrollTop();s.position={my:"left top",at:"left"+(o>=0?"+":"")+o+" "+"top"+(r>=0?"+":"")+r,of:i.window},e(this).removeClass("ui-dialog-dragging"),i._unblockFrames(),i._trigger("dragStop",n,t(a))}})},_makeResizable:function(){function t(e){return{originalPosition:e.originalPosition,originalSize:e.originalSize,position:e.position,size:e.size}}var i=this,s=this.options,n=s.resizable,a=this.uiDialog.css("position"),o="string"==typeof n?n:"n,e,s,w,se,sw,ne,nw";this.uiDialog.resizable({cancel:".ui-dialog-content",containment:"document",alsoResize:this.element,maxWidth:s.maxWidth,maxHeight:s.maxHeight,minWidth:s.minWidth,minHeight:this._minHeight(),handles:o,start:function(s,n){e(this).addClass("ui-dialog-resizing"),i._blockFrames(),i._trigger("resizeStart",s,t(n))},resize:function(e,s){i._trigger("resize",e,t(s))},stop:function(n,a){var o=i.uiDialog.offset(),r=o.left-i.document.scrollLeft(),h=o.top-i.document.scrollTop();s.height=i.uiDialog.height(),s.width=i.uiDialog.width(),s.position={my:"left top",at:"left"+(r>=0?"+":"")+r+" "+"top"+(h>=0?"+":"")+h,of:i.window},e(this).removeClass("ui-dialog-resizing"),i._unblockFrames(),i._trigger("resizeStop",n,t(a))}}).css("position",a)},_trackFocus:function(){this._on(this.widget(),{focusin:function(t){this._makeFocusTarget(),this._focusedElement=e(t.target)}})},_makeFocusTarget:function(){this._untrackInstance(),this._trackingInstances().unshift(this)},_untrackInstance:function(){var t=this._trackingInstances(),i=e.inArray(this,t);-1!==i&&t.splice(i,1)},_trackingInstances:function(){var e=this.document.data("ui-dialog-instances");return e||(e=[],this.document.data("ui-dialog-instances",e)),e},_minHeight:function(){var e=this.options;return"auto"===e.height?e.minHeight:Math.min(e.minHeight,e.height)},_position:function(){var e=this.uiDialog.is(":visible");e||this.uiDialog.show(),this.uiDialog.position(this.options.position),e||this.uiDialog.hide()},_setOptions:function(t){var i=this,s=!1,n={};e.each(t,function(e,t){i._setOption(e,t),e in i.sizeRelatedOptions&&(s=!0),e in i.resizableRelatedOptions&&(n[e]=t)}),s&&(this._size(),this._position()),this.uiDialog.is(":data(ui-resizable)")&&this.uiDialog.resizable("option",n)},_setOption:function(e,t){var i,s,n=this.uiDialog;"dialogClass"===e&&n.removeClass(this.options.dialogClass).addClass(t),"disabled"!==e&&(this._super(e,t),"appendTo"===e&&this.uiDialog.appendTo(this._appendTo()),"buttons"===e&&this._createButtons(),"closeText"===e&&this.uiDialogTitlebarClose.button({label:""+t}),"draggable"===e&&(i=n.is(":data(ui-draggable)"),i&&!t&&n.draggable("destroy"),!i&&t&&this._makeDraggable()),"position"===e&&this._position(),"resizable"===e&&(s=n.is(":data(ui-resizable)"),s&&!t&&n.resizable("destroy"),s&&"string"==typeof t&&n.resizable("option","handles",t),s||t===!1||this._makeResizable()),"title"===e&&this._title(this.uiDialogTitlebar.find(".ui-dialog-title")))},_size:function(){var e,t,i,s=this.options;this.element.show().css({width:"auto",minHeight:0,maxHeight:"none",height:0}),s.minWidth>s.width&&(s.width=s.minWidth),e=this.uiDialog.css({height:"auto",width:s.width}).outerHeight(),t=Math.max(0,s.minHeight-e),i="number"==typeof s.maxHeight?Math.max(0,s.maxHeight-e):"none","auto"===s.height?this.element.css({minHeight:t,maxHeight:i,height:"auto"}):this.element.height(Math.max(0,s.height-e)),this.uiDialog.is(":data(ui-resizable)")&&this.uiDialog.resizable("option","minHeight",this._minHeight())},_blockFrames:function(){this.iframeBlocks=this.document.find("iframe").map(function(){var t=e(this);return e("<div>").css({position:"absolute",width:t.outerWidth(),height:t.outerHeight()}).appendTo(t.parent()).offset(t.offset())[0]})},_unblockFrames:function(){this.iframeBlocks&&(this.iframeBlocks.remove(),delete this.iframeBlocks)},_allowInteraction:function(t){return e(t.target).closest(".ui-dialog").length?!0:!!e(t.target).closest(".ui-datepicker").length},_createOverlay:function(){if(this.options.modal){var t=!0;this._delay(function(){t=!1}),this.document.data("ui-dialog-overlays")||this._on(this.document,{focusin:function(e){t||this._allowInteraction(e)||(e.preventDefault(),this._trackingInstances()[0]._focusTabbable())}}),this.overlay=e("<div>").addClass("ui-widget-overlay ui-front").appendTo(this._appendTo()),this._on(this.overlay,{mousedown:"_keepFocus"}),this.document.data("ui-dialog-overlays",(this.document.data("ui-dialog-overlays")||0)+1)}},_destroyOverlay:function(){if(this.options.modal&&this.overlay){var e=this.document.data("ui-dialog-overlays")-1;e?this.document.data("ui-dialog-overlays",e):this.document.unbind("focusin").removeData("ui-dialog-overlays"),this.overlay.remove(),this.overlay=null}}}),e.widget("ui.droppable",{version:"1.11.4",widgetEventPrefix:"drop",options:{accept:"*",activeClass:!1,addClasses:!0,greedy:!1,hoverClass:!1,scope:"default",tolerance:"intersect",activate:null,deactivate:null,drop:null,out:null,over:null},_create:function(){var t,i=this.options,s=i.accept;this.isover=!1,this.isout=!0,this.accept=e.isFunction(s)?s:function(e){return e.is(s)},this.proportions=function(){return arguments.length?(t=arguments[0],void 0):t?t:t={width:this.element[0].offsetWidth,height:this.element[0].offsetHeight}},this._addToManager(i.scope),i.addClasses&&this.element.addClass("ui-droppable")},_addToManager:function(t){e.ui.ddmanager.droppables[t]=e.ui.ddmanager.droppables[t]||[],e.ui.ddmanager.droppables[t].push(this)},_splice:function(e){for(var t=0;e.length>t;t++)e[t]===this&&e.splice(t,1)},_destroy:function(){var t=e.ui.ddmanager.droppables[this.options.scope];this._splice(t),this.element.removeClass("ui-droppable ui-droppable-disabled")},_setOption:function(t,i){if("accept"===t)this.accept=e.isFunction(i)?i:function(e){return e.is(i)};else if("scope"===t){var s=e.ui.ddmanager.droppables[this.options.scope];this._splice(s),this._addToManager(i)}this._super(t,i)},_activate:function(t){var i=e.ui.ddmanager.current;this.options.activeClass&&this.element.addClass(this.options.activeClass),i&&this._trigger("activate",t,this.ui(i))},_deactivate:function(t){var i=e.ui.ddmanager.current;this.options.activeClass&&this.element.removeClass(this.options.activeClass),i&&this._trigger("deactivate",t,this.ui(i))},_over:function(t){var i=e.ui.ddmanager.current;i&&(i.currentItem||i.element)[0]!==this.element[0]&&this.accept.call(this.element[0],i.currentItem||i.element)&&(this.options.hoverClass&&this.element.addClass(this.options.hoverClass),this._trigger("over",t,this.ui(i)))},_out:function(t){var i=e.ui.ddmanager.current;i&&(i.currentItem||i.element)[0]!==this.element[0]&&this.accept.call(this.element[0],i.currentItem||i.element)&&(this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("out",t,this.ui(i)))},_drop:function(t,i){var s=i||e.ui.ddmanager.current,n=!1;return s&&(s.currentItem||s.element)[0]!==this.element[0]?(this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function(){var i=e(this).droppable("instance");return i.options.greedy&&!i.options.disabled&&i.options.scope===s.options.scope&&i.accept.call(i.element[0],s.currentItem||s.element)&&e.ui.intersect(s,e.extend(i,{offset:i.element.offset()}),i.options.tolerance,t)?(n=!0,!1):void 0}),n?!1:this.accept.call(this.element[0],s.currentItem||s.element)?(this.options.activeClass&&this.element.removeClass(this.options.activeClass),this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("drop",t,this.ui(s)),this.element):!1):!1},ui:function(e){return{draggable:e.currentItem||e.element,helper:e.helper,position:e.position,offset:e.positionAbs}}}),e.ui.intersect=function(){function e(e,t,i){return e>=t&&t+i>e}return function(t,i,s,n){if(!i.offset)return!1;var a=(t.positionAbs||t.position.absolute).left+t.margins.left,o=(t.positionAbs||t.position.absolute).top+t.margins.top,r=a+t.helperProportions.width,h=o+t.helperProportions.height,l=i.offset.left,u=i.offset.top,d=l+i.proportions().width,c=u+i.proportions().height;switch(s){case"fit":return a>=l&&d>=r&&o>=u&&c>=h;case"intersect":return a+t.helperProportions.width/2>l&&d>r-t.helperProportions.width/2&&o+t.helperProportions.height/2>u&&c>h-t.helperProportions.height/2;case"pointer":return e(n.pageY,u,i.proportions().height)&&e(n.pageX,l,i.proportions().width);case"touch":return(o>=u&&c>=o||h>=u&&c>=h||u>o&&h>c)&&(a>=l&&d>=a||r>=l&&d>=r||l>a&&r>d);default:return!1}}}(),e.ui.ddmanager={current:null,droppables:{"default":[]},prepareOffsets:function(t,i){var s,n,a=e.ui.ddmanager.droppables[t.options.scope]||[],o=i?i.type:null,r=(t.currentItem||t.element).find(":data(ui-droppable)").addBack();e:for(s=0;a.length>s;s++)if(!(a[s].options.disabled||t&&!a[s].accept.call(a[s].element[0],t.currentItem||t.element))){for(n=0;r.length>n;n++)if(r[n]===a[s].element[0]){a[s].proportions().height=0;continue e}a[s].visible="none"!==a[s].element.css("display"),a[s].visible&&("mousedown"===o&&a[s]._activate.call(a[s],i),a[s].offset=a[s].element.offset(),a[s].proportions({width:a[s].element[0].offsetWidth,height:a[s].element[0].offsetHeight}))}},drop:function(t,i){var s=!1;return e.each((e.ui.ddmanager.droppables[t.options.scope]||[]).slice(),function(){this.options&&(!this.options.disabled&&this.visible&&e.ui.intersect(t,this,this.options.tolerance,i)&&(s=this._drop.call(this,i)||s),!this.options.disabled&&this.visible&&this.accept.call(this.element[0],t.currentItem||t.element)&&(this.isout=!0,this.isover=!1,this._deactivate.call(this,i)))}),s},dragStart:function(t,i){t.element.parentsUntil("body").bind("scroll.droppable",function(){t.options.refreshPositions||e.ui.ddmanager.prepareOffsets(t,i)})},drag:function(t,i){t.options.refreshPositions&&e.ui.ddmanager.prepareOffsets(t,i),e.each(e.ui.ddmanager.droppables[t.options.scope]||[],function(){if(!this.options.disabled&&!this.greedyChild&&this.visible){var s,n,a,o=e.ui.intersect(t,this,this.options.tolerance,i),r=!o&&this.isover?"isout":o&&!this.isover?"isover":null;r&&(this.options.greedy&&(n=this.options.scope,a=this.element.parents(":data(ui-droppable)").filter(function(){return e(this).droppable("instance").options.scope===n}),a.length&&(s=e(a[0]).droppable("instance"),s.greedyChild="isover"===r)),s&&"isover"===r&&(s.isover=!1,s.isout=!0,s._out.call(s,i)),this[r]=!0,this["isout"===r?"isover":"isout"]=!1,this["isover"===r?"_over":"_out"].call(this,i),s&&"isout"===r&&(s.isout=!1,s.isover=!0,s._over.call(s,i)))}})},dragStop:function(t,i){t.element.parentsUntil("body").unbind("scroll.droppable"),t.options.refreshPositions||e.ui.ddmanager.prepareOffsets(t,i)}},e.ui.droppable;var y="ui-effects-",b=e;e.effects={effect:{}},function(e,t){function i(e,t,i){var s=d[t.type]||{};return null==e?i||!t.def?null:t.def:(e=s.floor?~~e:parseFloat(e),isNaN(e)?t.def:s.mod?(e+s.mod)%s.mod:0>e?0:e>s.max?s.max:e)}function s(i){var s=l(),n=s._rgba=[];return i=i.toLowerCase(),f(h,function(e,a){var o,r=a.re.exec(i),h=r&&a.parse(r),l=a.space||"rgba";return h?(o=s[l](h),s[u[l].cache]=o[u[l].cache],n=s._rgba=o._rgba,!1):t}),n.length?("0,0,0,0"===n.join()&&e.extend(n,a.transparent),s):a[i]}function n(e,t,i){return i=(i+1)%1,1>6*i?e+6*(t-e)*i:1>2*i?t:2>3*i?e+6*(t-e)*(2/3-i):e}var a,o="backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",r=/^([\-+])=\s*(\d+\.?\d*)/,h=[{re:/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(e){return[e[1],e[2],e[3],e[4]]}},{re:/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(e){return[2.55*e[1],2.55*e[2],2.55*e[3],e[4]]}},{re:/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,parse:function(e){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}},{re:/#([a-f0-9])([a-f0-9])([a-f0-9])/,parse:function(e){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}},{re:/hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,space:"hsla",parse:function(e){return[e[1],e[2]/100,e[3]/100,e[4]]}}],l=e.Color=function(t,i,s,n){return new e.Color.fn.parse(t,i,s,n)},u={rgba:{props:{red:{idx:0,type:"byte"},green:{idx:1,type:"byte"},blue:{idx:2,type:"byte"}}},hsla:{props:{hue:{idx:0,type:"degrees"},saturation:{idx:1,type:"percent"},lightness:{idx:2,type:"percent"}}}},d={"byte":{floor:!0,max:255},percent:{max:1},degrees:{mod:360,floor:!0}},c=l.support={},p=e("<p>")[0],f=e.each;p.style.cssText="background-color:rgba(1,1,1,.5)",c.rgba=p.style.backgroundColor.indexOf("rgba")>-1,f(u,function(e,t){t.cache="_"+e,t.props.alpha={idx:3,type:"percent",def:1}}),l.fn=e.extend(l.prototype,{parse:function(n,o,r,h){if(n===t)return this._rgba=[null,null,null,null],this;(n.jquery||n.nodeType)&&(n=e(n).css(o),o=t);var d=this,c=e.type(n),p=this._rgba=[];return o!==t&&(n=[n,o,r,h],c="array"),"string"===c?this.parse(s(n)||a._default):"array"===c?(f(u.rgba.props,function(e,t){p[t.idx]=i(n[t.idx],t)}),this):"object"===c?(n instanceof l?f(u,function(e,t){n[t.cache]&&(d[t.cache]=n[t.cache].slice())}):f(u,function(t,s){var a=s.cache;f(s.props,function(e,t){if(!d[a]&&s.to){if("alpha"===e||null==n[e])return;d[a]=s.to(d._rgba)}d[a][t.idx]=i(n[e],t,!0)}),d[a]&&0>e.inArray(null,d[a].slice(0,3))&&(d[a][3]=1,s.from&&(d._rgba=s.from(d[a])))}),this):t},is:function(e){var i=l(e),s=!0,n=this;return f(u,function(e,a){var o,r=i[a.cache];return r&&(o=n[a.cache]||a.to&&a.to(n._rgba)||[],f(a.props,function(e,i){return null!=r[i.idx]?s=r[i.idx]===o[i.idx]:t})),s}),s},_space:function(){var e=[],t=this;return f(u,function(i,s){t[s.cache]&&e.push(i)}),e.pop()},transition:function(e,t){var s=l(e),n=s._space(),a=u[n],o=0===this.alpha()?l("transparent"):this,r=o[a.cache]||a.to(o._rgba),h=r.slice();return s=s[a.cache],f(a.props,function(e,n){var a=n.idx,o=r[a],l=s[a],u=d[n.type]||{};null!==l&&(null===o?h[a]=l:(u.mod&&(l-o>u.mod/2?o+=u.mod:o-l>u.mod/2&&(o-=u.mod)),h[a]=i((l-o)*t+o,n)))}),this[n](h)},blend:function(t){if(1===this._rgba[3])return this;var i=this._rgba.slice(),s=i.pop(),n=l(t)._rgba;return l(e.map(i,function(e,t){return(1-s)*n[t]+s*e}))},toRgbaString:function(){var t="rgba(",i=e.map(this._rgba,function(e,t){return null==e?t>2?1:0:e});return 1===i[3]&&(i.pop(),t="rgb("),t+i.join()+")"},toHslaString:function(){var t="hsla(",i=e.map(this.hsla(),function(e,t){return null==e&&(e=t>2?1:0),t&&3>t&&(e=Math.round(100*e)+"%"),e});return 1===i[3]&&(i.pop(),t="hsl("),t+i.join()+")"},toHexString:function(t){var i=this._rgba.slice(),s=i.pop();return t&&i.push(~~(255*s)),"#"+e.map(i,function(e){return e=(e||0).toString(16),1===e.length?"0"+e:e}).join("")},toString:function(){return 0===this._rgba[3]?"transparent":this.toRgbaString()}}),l.fn.parse.prototype=l.fn,u.hsla.to=function(e){if(null==e[0]||null==e[1]||null==e[2])return[null,null,null,e[3]];var t,i,s=e[0]/255,n=e[1]/255,a=e[2]/255,o=e[3],r=Math.max(s,n,a),h=Math.min(s,n,a),l=r-h,u=r+h,d=.5*u;return t=h===r?0:s===r?60*(n-a)/l+360:n===r?60*(a-s)/l+120:60*(s-n)/l+240,i=0===l?0:.5>=d?l/u:l/(2-u),[Math.round(t)%360,i,d,null==o?1:o]},u.hsla.from=function(e){if(null==e[0]||null==e[1]||null==e[2])return[null,null,null,e[3]];var t=e[0]/360,i=e[1],s=e[2],a=e[3],o=.5>=s?s*(1+i):s+i-s*i,r=2*s-o;return[Math.round(255*n(r,o,t+1/3)),Math.round(255*n(r,o,t)),Math.round(255*n(r,o,t-1/3)),a]},f(u,function(s,n){var a=n.props,o=n.cache,h=n.to,u=n.from;l.fn[s]=function(s){if(h&&!this[o]&&(this[o]=h(this._rgba)),s===t)return this[o].slice();var n,r=e.type(s),d="array"===r||"object"===r?s:arguments,c=this[o].slice();return f(a,function(e,t){var s=d["object"===r?e:t.idx];null==s&&(s=c[t.idx]),c[t.idx]=i(s,t)}),u?(n=l(u(c)),n[o]=c,n):l(c)},f(a,function(t,i){l.fn[t]||(l.fn[t]=function(n){var a,o=e.type(n),h="alpha"===t?this._hsla?"hsla":"rgba":s,l=this[h](),u=l[i.idx];return"undefined"===o?u:("function"===o&&(n=n.call(this,u),o=e.type(n)),null==n&&i.empty?this:("string"===o&&(a=r.exec(n),a&&(n=u+parseFloat(a[2])*("+"===a[1]?1:-1))),l[i.idx]=n,this[h](l)))})})}),l.hook=function(t){var i=t.split(" ");f(i,function(t,i){e.cssHooks[i]={set:function(t,n){var a,o,r="";if("transparent"!==n&&("string"!==e.type(n)||(a=s(n)))){if(n=l(a||n),!c.rgba&&1!==n._rgba[3]){for(o="backgroundColor"===i?t.parentNode:t;(""===r||"transparent"===r)&&o&&o.style;)try{r=e.css(o,"backgroundColor"),o=o.parentNode}catch(h){}n=n.blend(r&&"transparent"!==r?r:"_default")}n=n.toRgbaString()}try{t.style[i]=n}catch(h){}}},e.fx.step[i]=function(t){t.colorInit||(t.start=l(t.elem,i),t.end=l(t.end),t.colorInit=!0),e.cssHooks[i].set(t.elem,t.start.transition(t.end,t.pos))}})},l.hook(o),e.cssHooks.borderColor={expand:function(e){var t={};return f(["Top","Right","Bottom","Left"],function(i,s){t["border"+s+"Color"]=e}),t}},a=e.Color.names={aqua:"#00ffff",black:"#000000",blue:"#0000ff",fuchsia:"#ff00ff",gray:"#808080",green:"#008000",lime:"#00ff00",maroon:"#800000",navy:"#000080",olive:"#808000",purple:"#800080",red:"#ff0000",silver:"#c0c0c0",teal:"#008080",white:"#ffffff",yellow:"#ffff00",transparent:[null,null,null,0],_default:"#ffffff"}}(b),function(){function t(t){var i,s,n=t.ownerDocument.defaultView?t.ownerDocument.defaultView.getComputedStyle(t,null):t.currentStyle,a={};if(n&&n.length&&n[0]&&n[n[0]])for(s=n.length;s--;)i=n[s],"string"==typeof n[i]&&(a[e.camelCase(i)]=n[i]);else for(i in n)"string"==typeof n[i]&&(a[i]=n[i]);return a}function i(t,i){var s,a,o={};for(s in i)a=i[s],t[s]!==a&&(n[s]||(e.fx.step[s]||!isNaN(parseFloat(a)))&&(o[s]=a));return o}var s=["add","remove","toggle"],n={border:1,borderBottom:1,borderColor:1,borderLeft:1,borderRight:1,borderTop:1,borderWidth:1,margin:1,padding:1};e.each(["borderLeftStyle","borderRightStyle","borderBottomStyle","borderTopStyle"],function(t,i){e.fx.step[i]=function(e){("none"!==e.end&&!e.setAttr||1===e.pos&&!e.setAttr)&&(b.style(e.elem,i,e.end),e.setAttr=!0)}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e.effects.animateClass=function(n,a,o,r){var h=e.speed(a,o,r);return this.queue(function(){var a,o=e(this),r=o.attr("class")||"",l=h.children?o.find("*").addBack():o;l=l.map(function(){var i=e(this);return{el:i,start:t(this)}}),a=function(){e.each(s,function(e,t){n[t]&&o[t+"Class"](n[t])})},a(),l=l.map(function(){return this.end=t(this.el[0]),this.diff=i(this.start,this.end),this}),o.attr("class",r),l=l.map(function(){var t=this,i=e.Deferred(),s=e.extend({},h,{queue:!1,complete:function(){i.resolve(t)}});return this.el.animate(this.diff,s),i.promise()}),e.when.apply(e,l.get()).done(function(){a(),e.each(arguments,function(){var t=this.el;e.each(this.diff,function(e){t.css(e,"")})}),h.complete.call(o[0])})})},e.fn.extend({addClass:function(t){return function(i,s,n,a){return s?e.effects.animateClass.call(this,{add:i},s,n,a):t.apply(this,arguments)}}(e.fn.addClass),removeClass:function(t){return function(i,s,n,a){return arguments.length>1?e.effects.animateClass.call(this,{remove:i},s,n,a):t.apply(this,arguments)}}(e.fn.removeClass),toggleClass:function(t){return function(i,s,n,a,o){return"boolean"==typeof s||void 0===s?n?e.effects.animateClass.call(this,s?{add:i}:{remove:i},n,a,o):t.apply(this,arguments):e.effects.animateClass.call(this,{toggle:i},s,n,a)}}(e.fn.toggleClass),switchClass:function(t,i,s,n,a){return e.effects.animateClass.call(this,{add:i,remove:t},s,n,a)}})}(),function(){function t(t,i,s,n){return e.isPlainObject(t)&&(i=t,t=t.effect),t={effect:t},null==i&&(i={}),e.isFunction(i)&&(n=i,s=null,i={}),("number"==typeof i||e.fx.speeds[i])&&(n=s,s=i,i={}),e.isFunction(s)&&(n=s,s=null),i&&e.extend(t,i),s=s||i.duration,t.duration=e.fx.off?0:"number"==typeof s?s:s in e.fx.speeds?e.fx.speeds[s]:e.fx.speeds._default,t.complete=n||i.complete,t}function i(t){return!t||"number"==typeof t||e.fx.speeds[t]?!0:"string"!=typeof t||e.effects.effect[t]?e.isFunction(t)?!0:"object"!=typeof t||t.effect?!1:!0:!0}e.extend(e.effects,{version:"1.11.4",save:function(e,t){for(var i=0;t.length>i;i++)null!==t[i]&&e.data(y+t[i],e[0].style[t[i]])},restore:function(e,t){var i,s;for(s=0;t.length>s;s++)null!==t[s]&&(i=e.data(y+t[s]),void 0===i&&(i=""),e.css(t[s],i))},setMode:function(e,t){return"toggle"===t&&(t=e.is(":hidden")?"show":"hide"),t},getBaseline:function(e,t){var i,s;switch(e[0]){case"top":i=0;break;case"middle":i=.5;break;case"bottom":i=1;break;default:i=e[0]/t.height}switch(e[1]){case"left":s=0;break;case"center":s=.5;break;case"right":s=1;break;default:s=e[1]/t.width}return{x:s,y:i}},createWrapper:function(t){if(t.parent().is(".ui-effects-wrapper"))return t.parent();var i={width:t.outerWidth(!0),height:t.outerHeight(!0),"float":t.css("float")},s=e("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",background:"transparent",border:"none",margin:0,padding:0}),n={width:t.width(),height:t.height()},a=document.activeElement;try{a.id}catch(o){a=document.body}return t.wrap(s),(t[0]===a||e.contains(t[0],a))&&e(a).focus(),s=t.parent(),"static"===t.css("position")?(s.css({position:"relative"}),t.css({position:"relative"})):(e.extend(i,{position:t.css("position"),zIndex:t.css("z-index")}),e.each(["top","left","bottom","right"],function(e,s){i[s]=t.css(s),isNaN(parseInt(i[s],10))&&(i[s]="auto")}),t.css({position:"relative",top:0,left:0,right:"auto",bottom:"auto"})),t.css(n),s.css(i).show()},removeWrapper:function(t){var i=document.activeElement;return t.parent().is(".ui-effects-wrapper")&&(t.parent().replaceWith(t),(t[0]===i||e.contains(t[0],i))&&e(i).focus()),t},setTransition:function(t,i,s,n){return n=n||{},e.each(i,function(e,i){var a=t.cssUnit(i);a[0]>0&&(n[i]=a[0]*s+a[1])}),n}}),e.fn.extend({effect:function(){function i(t){function i(){e.isFunction(a)&&a.call(n[0]),e.isFunction(t)&&t()}var n=e(this),a=s.complete,r=s.mode;(n.is(":hidden")?"hide"===r:"show"===r)?(n[r](),i()):o.call(n[0],s,i)}var s=t.apply(this,arguments),n=s.mode,a=s.queue,o=e.effects.effect[s.effect];return e.fx.off||!o?n?this[n](s.duration,s.complete):this.each(function(){s.complete&&s.complete.call(this)}):a===!1?this.each(i):this.queue(a||"fx",i)},show:function(e){return function(s){if(i(s))return e.apply(this,arguments);var n=t.apply(this,arguments);return n.mode="show",this.effect.call(this,n)}}(e.fn.show),hide:function(e){return function(s){if(i(s))return e.apply(this,arguments);var n=t.apply(this,arguments);return n.mode="hide",this.effect.call(this,n)}}(e.fn.hide),toggle:function(e){return function(s){if(i(s)||"boolean"==typeof s)return e.apply(this,arguments);var n=t.apply(this,arguments);return n.mode="toggle",this.effect.call(this,n)}}(e.fn.toggle),cssUnit:function(t){var i=this.css(t),s=[];return e.each(["em","px","%","pt"],function(e,t){i.indexOf(t)>0&&(s=[parseFloat(i),t])}),s}})}(),function(){var t={};e.each(["Quad","Cubic","Quart","Quint","Expo"],function(e,i){t[i]=function(t){return Math.pow(t,e+2)}}),e.extend(t,{Sine:function(e){return 1-Math.cos(e*Math.PI/2)},Circ:function(e){return 1-Math.sqrt(1-e*e)},Elastic:function(e){return 0===e||1===e?e:-Math.pow(2,8*(e-1))*Math.sin((80*(e-1)-7.5)*Math.PI/15)},Back:function(e){return e*e*(3*e-2)},Bounce:function(e){for(var t,i=4;((t=Math.pow(2,--i))-1)/11>e;);return 1/Math.pow(4,3-i)-7.5625*Math.pow((3*t-2)/22-e,2)}}),e.each(t,function(t,i){e.easing["easeIn"+t]=i,e.easing["easeOut"+t]=function(e){return 1-i(1-e)},e.easing["easeInOut"+t]=function(e){return.5>e?i(2*e)/2:1-i(-2*e+2)/2}})}(),e.effects,e.effects.effect.blind=function(t,i){var s,n,a,o=e(this),r=/up|down|vertical/,h=/up|left|vertical|horizontal/,l=["position","top","bottom","left","right","height","width"],u=e.effects.setMode(o,t.mode||"hide"),d=t.direction||"up",c=r.test(d),p=c?"height":"width",f=c?"top":"left",m=h.test(d),g={},v="show"===u;o.parent().is(".ui-effects-wrapper")?e.effects.save(o.parent(),l):e.effects.save(o,l),o.show(),s=e.effects.createWrapper(o).css({overflow:"hidden"}),n=s[p](),a=parseFloat(s.css(f))||0,g[p]=v?n:0,m||(o.css(c?"bottom":"right",0).css(c?"top":"left","auto").css({position:"absolute"}),g[f]=v?a:n+a),v&&(s.css(p,0),m||s.css(f,a+n)),s.animate(g,{duration:t.duration,easing:t.easing,queue:!1,complete:function(){"hide"===u&&o.hide(),e.effects.restore(o,l),e.effects.removeWrapper(o),i()}})},e.effects.effect.bounce=function(t,i){var s,n,a,o=e(this),r=["position","top","bottom","left","right","height","width"],h=e.effects.setMode(o,t.mode||"effect"),l="hide"===h,u="show"===h,d=t.direction||"up",c=t.distance,p=t.times||5,f=2*p+(u||l?1:0),m=t.duration/f,g=t.easing,v="up"===d||"down"===d?"top":"left",y="up"===d||"left"===d,b=o.queue(),_=b.length;for((u||l)&&r.push("opacity"),e.effects.save(o,r),o.show(),e.effects.createWrapper(o),c||(c=o["top"===v?"outerHeight":"outerWidth"]()/3),u&&(a={opacity:1},a[v]=0,o.css("opacity",0).css(v,y?2*-c:2*c).animate(a,m,g)),l&&(c/=Math.pow(2,p-1)),a={},a[v]=0,s=0;p>s;s++)n={},n[v]=(y?"-=":"+=")+c,o.animate(n,m,g).animate(a,m,g),c=l?2*c:c/2;l&&(n={opacity:0},n[v]=(y?"-=":"+=")+c,o.animate(n,m,g)),o.queue(function(){l&&o.hide(),e.effects.restore(o,r),e.effects.removeWrapper(o),i()}),_>1&&b.splice.apply(b,[1,0].concat(b.splice(_,f+1))),o.dequeue()},e.effects.effect.clip=function(t,i){var s,n,a,o=e(this),r=["position","top","bottom","left","right","height","width"],h=e.effects.setMode(o,t.mode||"hide"),l="show"===h,u=t.direction||"vertical",d="vertical"===u,c=d?"height":"width",p=d?"top":"left",f={};e.effects.save(o,r),o.show(),s=e.effects.createWrapper(o).css({overflow:"hidden"}),n="IMG"===o[0].tagName?s:o,a=n[c](),l&&(n.css(c,0),n.css(p,a/2)),f[c]=l?a:0,f[p]=l?0:a/2,n.animate(f,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){l||o.hide(),e.effects.restore(o,r),e.effects.removeWrapper(o),i()}})},e.effects.effect.drop=function(t,i){var s,n=e(this),a=["position","top","bottom","left","right","opacity","height","width"],o=e.effects.setMode(n,t.mode||"hide"),r="show"===o,h=t.direction||"left",l="up"===h||"down"===h?"top":"left",u="up"===h||"left"===h?"pos":"neg",d={opacity:r?1:0};e.effects.save(n,a),n.show(),e.effects.createWrapper(n),s=t.distance||n["top"===l?"outerHeight":"outerWidth"](!0)/2,r&&n.css("opacity",0).css(l,"pos"===u?-s:s),d[l]=(r?"pos"===u?"+=":"-=":"pos"===u?"-=":"+=")+s,n.animate(d,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){"hide"===o&&n.hide(),e.effects.restore(n,a),e.effects.removeWrapper(n),i()}})},e.effects.effect.explode=function(t,i){function s(){b.push(this),b.length===d*c&&n()}function n(){p.css({visibility:"visible"}),e(b).remove(),m||p.hide(),i()}var a,o,r,h,l,u,d=t.pieces?Math.round(Math.sqrt(t.pieces)):3,c=d,p=e(this),f=e.effects.setMode(p,t.mode||"hide"),m="show"===f,g=p.show().css("visibility","hidden").offset(),v=Math.ceil(p.outerWidth()/c),y=Math.ceil(p.outerHeight()/d),b=[];for(a=0;d>a;a++)for(h=g.top+a*y,u=a-(d-1)/2,o=0;c>o;o++)r=g.left+o*v,l=o-(c-1)/2,p.clone().appendTo("body").wrap("<div></div>").css({position:"absolute",visibility:"visible",left:-o*v,top:-a*y}).parent().addClass("ui-effects-explode").css({position:"absolute",overflow:"hidden",width:v,height:y,left:r+(m?l*v:0),top:h+(m?u*y:0),opacity:m?0:1}).animate({left:r+(m?0:l*v),top:h+(m?0:u*y),opacity:m?1:0},t.duration||500,t.easing,s)},e.effects.effect.fade=function(t,i){var s=e(this),n=e.effects.setMode(s,t.mode||"toggle");s.animate({opacity:n},{queue:!1,duration:t.duration,easing:t.easing,complete:i})},e.effects.effect.fold=function(t,i){var s,n,a=e(this),o=["position","top","bottom","left","right","height","width"],r=e.effects.setMode(a,t.mode||"hide"),h="show"===r,l="hide"===r,u=t.size||15,d=/([0-9]+)%/.exec(u),c=!!t.horizFirst,p=h!==c,f=p?["width","height"]:["height","width"],m=t.duration/2,g={},v={};e.effects.save(a,o),a.show(),s=e.effects.createWrapper(a).css({overflow:"hidden"}),n=p?[s.width(),s.height()]:[s.height(),s.width()],d&&(u=parseInt(d[1],10)/100*n[l?0:1]),h&&s.css(c?{height:0,width:u}:{height:u,width:0}),g[f[0]]=h?n[0]:u,v[f[1]]=h?n[1]:0,s.animate(g,m,t.easing).animate(v,m,t.easing,function(){l&&a.hide(),e.effects.restore(a,o),e.effects.removeWrapper(a),i()})},e.effects.effect.highlight=function(t,i){var s=e(this),n=["backgroundImage","backgroundColor","opacity"],a=e.effects.setMode(s,t.mode||"show"),o={backgroundColor:s.css("backgroundColor")};"hide"===a&&(o.opacity=0),e.effects.save(s,n),s.show().css({backgroundImage:"none",backgroundColor:t.color||"#ffff99"}).animate(o,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){"hide"===a&&s.hide(),e.effects.restore(s,n),i()}})},e.effects.effect.size=function(t,i){var s,n,a,o=e(this),r=["position","top","bottom","left","right","width","height","overflow","opacity"],h=["position","top","bottom","left","right","overflow","opacity"],l=["width","height","overflow"],u=["fontSize"],d=["borderTopWidth","borderBottomWidth","paddingTop","paddingBottom"],c=["borderLeftWidth","borderRightWidth","paddingLeft","paddingRight"],p=e.effects.setMode(o,t.mode||"effect"),f=t.restore||"effect"!==p,m=t.scale||"both",g=t.origin||["middle","center"],v=o.css("position"),y=f?r:h,b={height:0,width:0,outerHeight:0,outerWidth:0};"show"===p&&o.show(),s={height:o.height(),width:o.width(),outerHeight:o.outerHeight(),outerWidth:o.outerWidth()},"toggle"===t.mode&&"show"===p?(o.from=t.to||b,o.to=t.from||s):(o.from=t.from||("show"===p?b:s),o.to=t.to||("hide"===p?b:s)),a={from:{y:o.from.height/s.height,x:o.from.width/s.width},to:{y:o.to.height/s.height,x:o.to.width/s.width}},("box"===m||"both"===m)&&(a.from.y!==a.to.y&&(y=y.concat(d),o.from=e.effects.setTransition(o,d,a.from.y,o.from),o.to=e.effects.setTransition(o,d,a.to.y,o.to)),a.from.x!==a.to.x&&(y=y.concat(c),o.from=e.effects.setTransition(o,c,a.from.x,o.from),o.to=e.effects.setTransition(o,c,a.to.x,o.to))),("content"===m||"both"===m)&&a.from.y!==a.to.y&&(y=y.concat(u).concat(l),o.from=e.effects.setTransition(o,u,a.from.y,o.from),o.to=e.effects.setTransition(o,u,a.to.y,o.to)),e.effects.save(o,y),o.show(),e.effects.createWrapper(o),o.css("overflow","hidden").css(o.from),g&&(n=e.effects.getBaseline(g,s),o.from.top=(s.outerHeight-o.outerHeight())*n.y,o.from.left=(s.outerWidth-o.outerWidth())*n.x,o.to.top=(s.outerHeight-o.to.outerHeight)*n.y,o.to.left=(s.outerWidth-o.to.outerWidth)*n.x),o.css(o.from),("content"===m||"both"===m)&&(d=d.concat(["marginTop","marginBottom"]).concat(u),c=c.concat(["marginLeft","marginRight"]),l=r.concat(d).concat(c),o.find("*[width]").each(function(){var i=e(this),s={height:i.height(),width:i.width(),outerHeight:i.outerHeight(),outerWidth:i.outerWidth()};
f&&e.effects.save(i,l),i.from={height:s.height*a.from.y,width:s.width*a.from.x,outerHeight:s.outerHeight*a.from.y,outerWidth:s.outerWidth*a.from.x},i.to={height:s.height*a.to.y,width:s.width*a.to.x,outerHeight:s.height*a.to.y,outerWidth:s.width*a.to.x},a.from.y!==a.to.y&&(i.from=e.effects.setTransition(i,d,a.from.y,i.from),i.to=e.effects.setTransition(i,d,a.to.y,i.to)),a.from.x!==a.to.x&&(i.from=e.effects.setTransition(i,c,a.from.x,i.from),i.to=e.effects.setTransition(i,c,a.to.x,i.to)),i.css(i.from),i.animate(i.to,t.duration,t.easing,function(){f&&e.effects.restore(i,l)})})),o.animate(o.to,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){0===o.to.opacity&&o.css("opacity",o.from.opacity),"hide"===p&&o.hide(),e.effects.restore(o,y),f||("static"===v?o.css({position:"relative",top:o.to.top,left:o.to.left}):e.each(["top","left"],function(e,t){o.css(t,function(t,i){var s=parseInt(i,10),n=e?o.to.left:o.to.top;return"auto"===i?n+"px":s+n+"px"})})),e.effects.removeWrapper(o),i()}})},e.effects.effect.scale=function(t,i){var s=e(this),n=e.extend(!0,{},t),a=e.effects.setMode(s,t.mode||"effect"),o=parseInt(t.percent,10)||(0===parseInt(t.percent,10)?0:"hide"===a?0:100),r=t.direction||"both",h=t.origin,l={height:s.height(),width:s.width(),outerHeight:s.outerHeight(),outerWidth:s.outerWidth()},u={y:"horizontal"!==r?o/100:1,x:"vertical"!==r?o/100:1};n.effect="size",n.queue=!1,n.complete=i,"effect"!==a&&(n.origin=h||["middle","center"],n.restore=!0),n.from=t.from||("show"===a?{height:0,width:0,outerHeight:0,outerWidth:0}:l),n.to={height:l.height*u.y,width:l.width*u.x,outerHeight:l.outerHeight*u.y,outerWidth:l.outerWidth*u.x},n.fade&&("show"===a&&(n.from.opacity=0,n.to.opacity=1),"hide"===a&&(n.from.opacity=1,n.to.opacity=0)),s.effect(n)},e.effects.effect.puff=function(t,i){var s=e(this),n=e.effects.setMode(s,t.mode||"hide"),a="hide"===n,o=parseInt(t.percent,10)||150,r=o/100,h={height:s.height(),width:s.width(),outerHeight:s.outerHeight(),outerWidth:s.outerWidth()};e.extend(t,{effect:"scale",queue:!1,fade:!0,mode:n,complete:i,percent:a?o:100,from:a?h:{height:h.height*r,width:h.width*r,outerHeight:h.outerHeight*r,outerWidth:h.outerWidth*r}}),s.effect(t)},e.effects.effect.pulsate=function(t,i){var s,n=e(this),a=e.effects.setMode(n,t.mode||"show"),o="show"===a,r="hide"===a,h=o||"hide"===a,l=2*(t.times||5)+(h?1:0),u=t.duration/l,d=0,c=n.queue(),p=c.length;for((o||!n.is(":visible"))&&(n.css("opacity",0).show(),d=1),s=1;l>s;s++)n.animate({opacity:d},u,t.easing),d=1-d;n.animate({opacity:d},u,t.easing),n.queue(function(){r&&n.hide(),i()}),p>1&&c.splice.apply(c,[1,0].concat(c.splice(p,l+1))),n.dequeue()},e.effects.effect.shake=function(t,i){var s,n=e(this),a=["position","top","bottom","left","right","height","width"],o=e.effects.setMode(n,t.mode||"effect"),r=t.direction||"left",h=t.distance||20,l=t.times||3,u=2*l+1,d=Math.round(t.duration/u),c="up"===r||"down"===r?"top":"left",p="up"===r||"left"===r,f={},m={},g={},v=n.queue(),y=v.length;for(e.effects.save(n,a),n.show(),e.effects.createWrapper(n),f[c]=(p?"-=":"+=")+h,m[c]=(p?"+=":"-=")+2*h,g[c]=(p?"-=":"+=")+2*h,n.animate(f,d,t.easing),s=1;l>s;s++)n.animate(m,d,t.easing).animate(g,d,t.easing);n.animate(m,d,t.easing).animate(f,d/2,t.easing).queue(function(){"hide"===o&&n.hide(),e.effects.restore(n,a),e.effects.removeWrapper(n),i()}),y>1&&v.splice.apply(v,[1,0].concat(v.splice(y,u+1))),n.dequeue()},e.effects.effect.slide=function(t,i){var s,n=e(this),a=["position","top","bottom","left","right","width","height"],o=e.effects.setMode(n,t.mode||"show"),r="show"===o,h=t.direction||"left",l="up"===h||"down"===h?"top":"left",u="up"===h||"left"===h,d={};e.effects.save(n,a),n.show(),s=t.distance||n["top"===l?"outerHeight":"outerWidth"](!0),e.effects.createWrapper(n).css({overflow:"hidden"}),r&&n.css(l,u?isNaN(s)?"-"+s:-s:s),d[l]=(r?u?"+=":"-=":u?"-=":"+=")+s,n.animate(d,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){"hide"===o&&n.hide(),e.effects.restore(n,a),e.effects.removeWrapper(n),i()}})},e.effects.effect.transfer=function(t,i){var s=e(this),n=e(t.to),a="fixed"===n.css("position"),o=e("body"),r=a?o.scrollTop():0,h=a?o.scrollLeft():0,l=n.offset(),u={top:l.top-r,left:l.left-h,height:n.innerHeight(),width:n.innerWidth()},d=s.offset(),c=e("<div class='ui-effects-transfer'></div>").appendTo(document.body).addClass(t.className).css({top:d.top-r,left:d.left-h,height:s.innerHeight(),width:s.innerWidth(),position:a?"fixed":"absolute"}).animate(u,t.duration,t.easing,function(){c.remove(),i()})},e.widget("ui.progressbar",{version:"1.11.4",options:{max:100,value:0,change:null,complete:null},min:0,_create:function(){this.oldValue=this.options.value=this._constrainedValue(),this.element.addClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").attr({role:"progressbar","aria-valuemin":this.min}),this.valueDiv=e("<div class='ui-progressbar-value ui-widget-header ui-corner-left'></div>").appendTo(this.element),this._refreshValue()},_destroy:function(){this.element.removeClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"),this.valueDiv.remove()},value:function(e){return void 0===e?this.options.value:(this.options.value=this._constrainedValue(e),this._refreshValue(),void 0)},_constrainedValue:function(e){return void 0===e&&(e=this.options.value),this.indeterminate=e===!1,"number"!=typeof e&&(e=0),this.indeterminate?!1:Math.min(this.options.max,Math.max(this.min,e))},_setOptions:function(e){var t=e.value;delete e.value,this._super(e),this.options.value=this._constrainedValue(t),this._refreshValue()},_setOption:function(e,t){"max"===e&&(t=Math.max(this.min,t)),"disabled"===e&&this.element.toggleClass("ui-state-disabled",!!t).attr("aria-disabled",t),this._super(e,t)},_percentage:function(){return this.indeterminate?100:100*(this.options.value-this.min)/(this.options.max-this.min)},_refreshValue:function(){var t=this.options.value,i=this._percentage();this.valueDiv.toggle(this.indeterminate||t>this.min).toggleClass("ui-corner-right",t===this.options.max).width(i.toFixed(0)+"%"),this.element.toggleClass("ui-progressbar-indeterminate",this.indeterminate),this.indeterminate?(this.element.removeAttr("aria-valuenow"),this.overlayDiv||(this.overlayDiv=e("<div class='ui-progressbar-overlay'></div>").appendTo(this.valueDiv))):(this.element.attr({"aria-valuemax":this.options.max,"aria-valuenow":t}),this.overlayDiv&&(this.overlayDiv.remove(),this.overlayDiv=null)),this.oldValue!==t&&(this.oldValue=t,this._trigger("change")),t===this.options.max&&this._trigger("complete")}}),e.widget("ui.selectable",e.ui.mouse,{version:"1.11.4",options:{appendTo:"body",autoRefresh:!0,distance:0,filter:"*",tolerance:"touch",selected:null,selecting:null,start:null,stop:null,unselected:null,unselecting:null},_create:function(){var t,i=this;this.element.addClass("ui-selectable"),this.dragged=!1,this.refresh=function(){t=e(i.options.filter,i.element[0]),t.addClass("ui-selectee"),t.each(function(){var t=e(this),i=t.offset();e.data(this,"selectable-item",{element:this,$element:t,left:i.left,top:i.top,right:i.left+t.outerWidth(),bottom:i.top+t.outerHeight(),startselected:!1,selected:t.hasClass("ui-selected"),selecting:t.hasClass("ui-selecting"),unselecting:t.hasClass("ui-unselecting")})})},this.refresh(),this.selectees=t.addClass("ui-selectee"),this._mouseInit(),this.helper=e("<div class='ui-selectable-helper'></div>")},_destroy:function(){this.selectees.removeClass("ui-selectee").removeData("selectable-item"),this.element.removeClass("ui-selectable ui-selectable-disabled"),this._mouseDestroy()},_mouseStart:function(t){var i=this,s=this.options;this.opos=[t.pageX,t.pageY],this.options.disabled||(this.selectees=e(s.filter,this.element[0]),this._trigger("start",t),e(s.appendTo).append(this.helper),this.helper.css({left:t.pageX,top:t.pageY,width:0,height:0}),s.autoRefresh&&this.refresh(),this.selectees.filter(".ui-selected").each(function(){var s=e.data(this,"selectable-item");s.startselected=!0,t.metaKey||t.ctrlKey||(s.$element.removeClass("ui-selected"),s.selected=!1,s.$element.addClass("ui-unselecting"),s.unselecting=!0,i._trigger("unselecting",t,{unselecting:s.element}))}),e(t.target).parents().addBack().each(function(){var s,n=e.data(this,"selectable-item");return n?(s=!t.metaKey&&!t.ctrlKey||!n.$element.hasClass("ui-selected"),n.$element.removeClass(s?"ui-unselecting":"ui-selected").addClass(s?"ui-selecting":"ui-unselecting"),n.unselecting=!s,n.selecting=s,n.selected=s,s?i._trigger("selecting",t,{selecting:n.element}):i._trigger("unselecting",t,{unselecting:n.element}),!1):void 0}))},_mouseDrag:function(t){if(this.dragged=!0,!this.options.disabled){var i,s=this,n=this.options,a=this.opos[0],o=this.opos[1],r=t.pageX,h=t.pageY;return a>r&&(i=r,r=a,a=i),o>h&&(i=h,h=o,o=i),this.helper.css({left:a,top:o,width:r-a,height:h-o}),this.selectees.each(function(){var i=e.data(this,"selectable-item"),l=!1;i&&i.element!==s.element[0]&&("touch"===n.tolerance?l=!(i.left>r||a>i.right||i.top>h||o>i.bottom):"fit"===n.tolerance&&(l=i.left>a&&r>i.right&&i.top>o&&h>i.bottom),l?(i.selected&&(i.$element.removeClass("ui-selected"),i.selected=!1),i.unselecting&&(i.$element.removeClass("ui-unselecting"),i.unselecting=!1),i.selecting||(i.$element.addClass("ui-selecting"),i.selecting=!0,s._trigger("selecting",t,{selecting:i.element}))):(i.selecting&&((t.metaKey||t.ctrlKey)&&i.startselected?(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.$element.addClass("ui-selected"),i.selected=!0):(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.startselected&&(i.$element.addClass("ui-unselecting"),i.unselecting=!0),s._trigger("unselecting",t,{unselecting:i.element}))),i.selected&&(t.metaKey||t.ctrlKey||i.startselected||(i.$element.removeClass("ui-selected"),i.selected=!1,i.$element.addClass("ui-unselecting"),i.unselecting=!0,s._trigger("unselecting",t,{unselecting:i.element})))))}),!1}},_mouseStop:function(t){var i=this;return this.dragged=!1,e(".ui-unselecting",this.element[0]).each(function(){var s=e.data(this,"selectable-item");s.$element.removeClass("ui-unselecting"),s.unselecting=!1,s.startselected=!1,i._trigger("unselected",t,{unselected:s.element})}),e(".ui-selecting",this.element[0]).each(function(){var s=e.data(this,"selectable-item");s.$element.removeClass("ui-selecting").addClass("ui-selected"),s.selecting=!1,s.selected=!0,s.startselected=!0,i._trigger("selected",t,{selected:s.element})}),this._trigger("stop",t),this.helper.remove(),!1}}),e.widget("ui.selectmenu",{version:"1.11.4",defaultElement:"<select>",options:{appendTo:null,disabled:null,icons:{button:"ui-icon-triangle-1-s"},position:{my:"left top",at:"left bottom",collision:"none"},width:null,change:null,close:null,focus:null,open:null,select:null},_create:function(){var e=this.element.uniqueId().attr("id");this.ids={element:e,button:e+"-button",menu:e+"-menu"},this._drawButton(),this._drawMenu(),this.options.disabled&&this.disable()},_drawButton:function(){var t=this;this.label=e("label[for='"+this.ids.element+"']").attr("for",this.ids.button),this._on(this.label,{click:function(e){this.button.focus(),e.preventDefault()}}),this.element.hide(),this.button=e("<span>",{"class":"ui-selectmenu-button ui-widget ui-state-default ui-corner-all",tabindex:this.options.disabled?-1:0,id:this.ids.button,role:"combobox","aria-expanded":"false","aria-autocomplete":"list","aria-owns":this.ids.menu,"aria-haspopup":"true"}).insertAfter(this.element),e("<span>",{"class":"ui-icon "+this.options.icons.button}).prependTo(this.button),this.buttonText=e("<span>",{"class":"ui-selectmenu-text"}).appendTo(this.button),this._setText(this.buttonText,this.element.find("option:selected").text()),this._resizeButton(),this._on(this.button,this._buttonEvents),this.button.one("focusin",function(){t.menuItems||t._refreshMenu()}),this._hoverable(this.button),this._focusable(this.button)},_drawMenu:function(){var t=this;this.menu=e("<ul>",{"aria-hidden":"true","aria-labelledby":this.ids.button,id:this.ids.menu}),this.menuWrap=e("<div>",{"class":"ui-selectmenu-menu ui-front"}).append(this.menu).appendTo(this._appendTo()),this.menuInstance=this.menu.menu({role:"listbox",select:function(e,i){e.preventDefault(),t._setSelection(),t._select(i.item.data("ui-selectmenu-item"),e)},focus:function(e,i){var s=i.item.data("ui-selectmenu-item");null!=t.focusIndex&&s.index!==t.focusIndex&&(t._trigger("focus",e,{item:s}),t.isOpen||t._select(s,e)),t.focusIndex=s.index,t.button.attr("aria-activedescendant",t.menuItems.eq(s.index).attr("id"))}}).menu("instance"),this.menu.addClass("ui-corner-bottom").removeClass("ui-corner-all"),this.menuInstance._off(this.menu,"mouseleave"),this.menuInstance._closeOnDocumentClick=function(){return!1},this.menuInstance._isDivider=function(){return!1}},refresh:function(){this._refreshMenu(),this._setText(this.buttonText,this._getSelectedItem().text()),this.options.width||this._resizeButton()},_refreshMenu:function(){this.menu.empty();var e,t=this.element.find("option");t.length&&(this._parseOptions(t),this._renderMenu(this.menu,this.items),this.menuInstance.refresh(),this.menuItems=this.menu.find("li").not(".ui-selectmenu-optgroup"),e=this._getSelectedItem(),this.menuInstance.focus(null,e),this._setAria(e.data("ui-selectmenu-item")),this._setOption("disabled",this.element.prop("disabled")))},open:function(e){this.options.disabled||(this.menuItems?(this.menu.find(".ui-state-focus").removeClass("ui-state-focus"),this.menuInstance.focus(null,this._getSelectedItem())):this._refreshMenu(),this.isOpen=!0,this._toggleAttr(),this._resizeMenu(),this._position(),this._on(this.document,this._documentClick),this._trigger("open",e))},_position:function(){this.menuWrap.position(e.extend({of:this.button},this.options.position))},close:function(e){this.isOpen&&(this.isOpen=!1,this._toggleAttr(),this.range=null,this._off(this.document),this._trigger("close",e))},widget:function(){return this.button},menuWidget:function(){return this.menu},_renderMenu:function(t,i){var s=this,n="";e.each(i,function(i,a){a.optgroup!==n&&(e("<li>",{"class":"ui-selectmenu-optgroup ui-menu-divider"+(a.element.parent("optgroup").prop("disabled")?" ui-state-disabled":""),text:a.optgroup}).appendTo(t),n=a.optgroup),s._renderItemData(t,a)})},_renderItemData:function(e,t){return this._renderItem(e,t).data("ui-selectmenu-item",t)},_renderItem:function(t,i){var s=e("<li>");return i.disabled&&s.addClass("ui-state-disabled"),this._setText(s,i.label),s.appendTo(t)},_setText:function(e,t){t?e.text(t):e.html("&#160;")},_move:function(e,t){var i,s,n=".ui-menu-item";this.isOpen?i=this.menuItems.eq(this.focusIndex):(i=this.menuItems.eq(this.element[0].selectedIndex),n+=":not(.ui-state-disabled)"),s="first"===e||"last"===e?i["first"===e?"prevAll":"nextAll"](n).eq(-1):i[e+"All"](n).eq(0),s.length&&this.menuInstance.focus(t,s)},_getSelectedItem:function(){return this.menuItems.eq(this.element[0].selectedIndex)},_toggle:function(e){this[this.isOpen?"close":"open"](e)},_setSelection:function(){var e;this.range&&(window.getSelection?(e=window.getSelection(),e.removeAllRanges(),e.addRange(this.range)):this.range.select(),this.button.focus())},_documentClick:{mousedown:function(t){this.isOpen&&(e(t.target).closest(".ui-selectmenu-menu, #"+this.ids.button).length||this.close(t))}},_buttonEvents:{mousedown:function(){var e;window.getSelection?(e=window.getSelection(),e.rangeCount&&(this.range=e.getRangeAt(0))):this.range=document.selection.createRange()},click:function(e){this._setSelection(),this._toggle(e)},keydown:function(t){var i=!0;switch(t.keyCode){case e.ui.keyCode.TAB:case e.ui.keyCode.ESCAPE:this.close(t),i=!1;break;case e.ui.keyCode.ENTER:this.isOpen&&this._selectFocusedItem(t);break;case e.ui.keyCode.UP:t.altKey?this._toggle(t):this._move("prev",t);break;case e.ui.keyCode.DOWN:t.altKey?this._toggle(t):this._move("next",t);break;case e.ui.keyCode.SPACE:this.isOpen?this._selectFocusedItem(t):this._toggle(t);break;case e.ui.keyCode.LEFT:this._move("prev",t);break;case e.ui.keyCode.RIGHT:this._move("next",t);break;case e.ui.keyCode.HOME:case e.ui.keyCode.PAGE_UP:this._move("first",t);break;case e.ui.keyCode.END:case e.ui.keyCode.PAGE_DOWN:this._move("last",t);break;default:this.menu.trigger(t),i=!1}i&&t.preventDefault()}},_selectFocusedItem:function(e){var t=this.menuItems.eq(this.focusIndex);t.hasClass("ui-state-disabled")||this._select(t.data("ui-selectmenu-item"),e)},_select:function(e,t){var i=this.element[0].selectedIndex;this.element[0].selectedIndex=e.index,this._setText(this.buttonText,e.label),this._setAria(e),this._trigger("select",t,{item:e}),e.index!==i&&this._trigger("change",t,{item:e}),this.close(t)},_setAria:function(e){var t=this.menuItems.eq(e.index).attr("id");this.button.attr({"aria-labelledby":t,"aria-activedescendant":t}),this.menu.attr("aria-activedescendant",t)},_setOption:function(e,t){"icons"===e&&this.button.find("span.ui-icon").removeClass(this.options.icons.button).addClass(t.button),this._super(e,t),"appendTo"===e&&this.menuWrap.appendTo(this._appendTo()),"disabled"===e&&(this.menuInstance.option("disabled",t),this.button.toggleClass("ui-state-disabled",t).attr("aria-disabled",t),this.element.prop("disabled",t),t?(this.button.attr("tabindex",-1),this.close()):this.button.attr("tabindex",0)),"width"===e&&this._resizeButton()},_appendTo:function(){var t=this.options.appendTo;return t&&(t=t.jquery||t.nodeType?e(t):this.document.find(t).eq(0)),t&&t[0]||(t=this.element.closest(".ui-front")),t.length||(t=this.document[0].body),t},_toggleAttr:function(){this.button.toggleClass("ui-corner-top",this.isOpen).toggleClass("ui-corner-all",!this.isOpen).attr("aria-expanded",this.isOpen),this.menuWrap.toggleClass("ui-selectmenu-open",this.isOpen),this.menu.attr("aria-hidden",!this.isOpen)},_resizeButton:function(){var e=this.options.width;e||(e=this.element.show().outerWidth(),this.element.hide()),this.button.outerWidth(e)},_resizeMenu:function(){this.menu.outerWidth(Math.max(this.button.outerWidth(),this.menu.width("").outerWidth()+1))},_getCreateOptions:function(){return{disabled:this.element.prop("disabled")}},_parseOptions:function(t){var i=[];t.each(function(t,s){var n=e(s),a=n.parent("optgroup");i.push({element:n,index:t,value:n.val(),label:n.text(),optgroup:a.attr("label")||"",disabled:a.prop("disabled")||n.prop("disabled")})}),this.items=i},_destroy:function(){this.menuWrap.remove(),this.button.remove(),this.element.show(),this.element.removeUniqueId(),this.label.attr("for",this.ids.element)}}),e.widget("ui.slider",e.ui.mouse,{version:"1.11.4",widgetEventPrefix:"slide",options:{animate:!1,distance:0,max:100,min:0,orientation:"horizontal",range:!1,step:1,value:0,values:null,change:null,slide:null,start:null,stop:null},numPages:5,_create:function(){this._keySliding=!1,this._mouseSliding=!1,this._animateOff=!0,this._handleIndex=null,this._detectOrientation(),this._mouseInit(),this._calculateNewMax(),this.element.addClass("ui-slider ui-slider-"+this.orientation+" ui-widget"+" ui-widget-content"+" ui-corner-all"),this._refresh(),this._setOption("disabled",this.options.disabled),this._animateOff=!1},_refresh:function(){this._createRange(),this._createHandles(),this._setupEvents(),this._refreshValue()},_createHandles:function(){var t,i,s=this.options,n=this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),a="<span class='ui-slider-handle ui-state-default ui-corner-all' tabindex='0'></span>",o=[];for(i=s.values&&s.values.length||1,n.length>i&&(n.slice(i).remove(),n=n.slice(0,i)),t=n.length;i>t;t++)o.push(a);this.handles=n.add(e(o.join("")).appendTo(this.element)),this.handle=this.handles.eq(0),this.handles.each(function(t){e(this).data("ui-slider-handle-index",t)})},_createRange:function(){var t=this.options,i="";t.range?(t.range===!0&&(t.values?t.values.length&&2!==t.values.length?t.values=[t.values[0],t.values[0]]:e.isArray(t.values)&&(t.values=t.values.slice(0)):t.values=[this._valueMin(),this._valueMin()]),this.range&&this.range.length?this.range.removeClass("ui-slider-range-min ui-slider-range-max").css({left:"",bottom:""}):(this.range=e("<div></div>").appendTo(this.element),i="ui-slider-range ui-widget-header ui-corner-all"),this.range.addClass(i+("min"===t.range||"max"===t.range?" ui-slider-range-"+t.range:""))):(this.range&&this.range.remove(),this.range=null)},_setupEvents:function(){this._off(this.handles),this._on(this.handles,this._handleEvents),this._hoverable(this.handles),this._focusable(this.handles)},_destroy:function(){this.handles.remove(),this.range&&this.range.remove(),this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-widget ui-widget-content ui-corner-all"),this._mouseDestroy()},_mouseCapture:function(t){var i,s,n,a,o,r,h,l,u=this,d=this.options;return d.disabled?!1:(this.elementSize={width:this.element.outerWidth(),height:this.element.outerHeight()},this.elementOffset=this.element.offset(),i={x:t.pageX,y:t.pageY},s=this._normValueFromMouse(i),n=this._valueMax()-this._valueMin()+1,this.handles.each(function(t){var i=Math.abs(s-u.values(t));(n>i||n===i&&(t===u._lastChangedValue||u.values(t)===d.min))&&(n=i,a=e(this),o=t)}),r=this._start(t,o),r===!1?!1:(this._mouseSliding=!0,this._handleIndex=o,a.addClass("ui-state-active").focus(),h=a.offset(),l=!e(t.target).parents().addBack().is(".ui-slider-handle"),this._clickOffset=l?{left:0,top:0}:{left:t.pageX-h.left-a.width()/2,top:t.pageY-h.top-a.height()/2-(parseInt(a.css("borderTopWidth"),10)||0)-(parseInt(a.css("borderBottomWidth"),10)||0)+(parseInt(a.css("marginTop"),10)||0)},this.handles.hasClass("ui-state-hover")||this._slide(t,o,s),this._animateOff=!0,!0))},_mouseStart:function(){return!0},_mouseDrag:function(e){var t={x:e.pageX,y:e.pageY},i=this._normValueFromMouse(t);return this._slide(e,this._handleIndex,i),!1},_mouseStop:function(e){return this.handles.removeClass("ui-state-active"),this._mouseSliding=!1,this._stop(e,this._handleIndex),this._change(e,this._handleIndex),this._handleIndex=null,this._clickOffset=null,this._animateOff=!1,!1},_detectOrientation:function(){this.orientation="vertical"===this.options.orientation?"vertical":"horizontal"},_normValueFromMouse:function(e){var t,i,s,n,a;return"horizontal"===this.orientation?(t=this.elementSize.width,i=e.x-this.elementOffset.left-(this._clickOffset?this._clickOffset.left:0)):(t=this.elementSize.height,i=e.y-this.elementOffset.top-(this._clickOffset?this._clickOffset.top:0)),s=i/t,s>1&&(s=1),0>s&&(s=0),"vertical"===this.orientation&&(s=1-s),n=this._valueMax()-this._valueMin(),a=this._valueMin()+s*n,this._trimAlignValue(a)},_start:function(e,t){var i={handle:this.handles[t],value:this.value()};return this.options.values&&this.options.values.length&&(i.value=this.values(t),i.values=this.values()),this._trigger("start",e,i)},_slide:function(e,t,i){var s,n,a;this.options.values&&this.options.values.length?(s=this.values(t?0:1),2===this.options.values.length&&this.options.range===!0&&(0===t&&i>s||1===t&&s>i)&&(i=s),i!==this.values(t)&&(n=this.values(),n[t]=i,a=this._trigger("slide",e,{handle:this.handles[t],value:i,values:n}),s=this.values(t?0:1),a!==!1&&this.values(t,i))):i!==this.value()&&(a=this._trigger("slide",e,{handle:this.handles[t],value:i}),a!==!1&&this.value(i))},_stop:function(e,t){var i={handle:this.handles[t],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(t),i.values=this.values()),this._trigger("stop",e,i)},_change:function(e,t){if(!this._keySliding&&!this._mouseSliding){var i={handle:this.handles[t],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(t),i.values=this.values()),this._lastChangedValue=t,this._trigger("change",e,i)}},value:function(e){return arguments.length?(this.options.value=this._trimAlignValue(e),this._refreshValue(),this._change(null,0),void 0):this._value()},values:function(t,i){var s,n,a;if(arguments.length>1)return this.options.values[t]=this._trimAlignValue(i),this._refreshValue(),this._change(null,t),void 0;if(!arguments.length)return this._values();if(!e.isArray(arguments[0]))return this.options.values&&this.options.values.length?this._values(t):this.value();for(s=this.options.values,n=arguments[0],a=0;s.length>a;a+=1)s[a]=this._trimAlignValue(n[a]),this._change(null,a);this._refreshValue()},_setOption:function(t,i){var s,n=0;switch("range"===t&&this.options.range===!0&&("min"===i?(this.options.value=this._values(0),this.options.values=null):"max"===i&&(this.options.value=this._values(this.options.values.length-1),this.options.values=null)),e.isArray(this.options.values)&&(n=this.options.values.length),"disabled"===t&&this.element.toggleClass("ui-state-disabled",!!i),this._super(t,i),t){case"orientation":this._detectOrientation(),this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-"+this.orientation),this._refreshValue(),this.handles.css("horizontal"===i?"bottom":"left","");break;case"value":this._animateOff=!0,this._refreshValue(),this._change(null,0),this._animateOff=!1;break;case"values":for(this._animateOff=!0,this._refreshValue(),s=0;n>s;s+=1)this._change(null,s);this._animateOff=!1;break;case"step":case"min":case"max":this._animateOff=!0,this._calculateNewMax(),this._refreshValue(),this._animateOff=!1;break;case"range":this._animateOff=!0,this._refresh(),this._animateOff=!1}},_value:function(){var e=this.options.value;return e=this._trimAlignValue(e)},_values:function(e){var t,i,s;if(arguments.length)return t=this.options.values[e],t=this._trimAlignValue(t);if(this.options.values&&this.options.values.length){for(i=this.options.values.slice(),s=0;i.length>s;s+=1)i[s]=this._trimAlignValue(i[s]);return i}return[]},_trimAlignValue:function(e){if(this._valueMin()>=e)return this._valueMin();if(e>=this._valueMax())return this._valueMax();var t=this.options.step>0?this.options.step:1,i=(e-this._valueMin())%t,s=e-i;return 2*Math.abs(i)>=t&&(s+=i>0?t:-t),parseFloat(s.toFixed(5))},_calculateNewMax:function(){var e=this.options.max,t=this._valueMin(),i=this.options.step,s=Math.floor(+(e-t).toFixed(this._precision())/i)*i;e=s+t,this.max=parseFloat(e.toFixed(this._precision()))},_precision:function(){var e=this._precisionOf(this.options.step);return null!==this.options.min&&(e=Math.max(e,this._precisionOf(this.options.min))),e},_precisionOf:function(e){var t=""+e,i=t.indexOf(".");return-1===i?0:t.length-i-1},_valueMin:function(){return this.options.min},_valueMax:function(){return this.max},_refreshValue:function(){var t,i,s,n,a,o=this.options.range,r=this.options,h=this,l=this._animateOff?!1:r.animate,u={};this.options.values&&this.options.values.length?this.handles.each(function(s){i=100*((h.values(s)-h._valueMin())/(h._valueMax()-h._valueMin())),u["horizontal"===h.orientation?"left":"bottom"]=i+"%",e(this).stop(1,1)[l?"animate":"css"](u,r.animate),h.options.range===!0&&("horizontal"===h.orientation?(0===s&&h.range.stop(1,1)[l?"animate":"css"]({left:i+"%"},r.animate),1===s&&h.range[l?"animate":"css"]({width:i-t+"%"},{queue:!1,duration:r.animate})):(0===s&&h.range.stop(1,1)[l?"animate":"css"]({bottom:i+"%"},r.animate),1===s&&h.range[l?"animate":"css"]({height:i-t+"%"},{queue:!1,duration:r.animate}))),t=i}):(s=this.value(),n=this._valueMin(),a=this._valueMax(),i=a!==n?100*((s-n)/(a-n)):0,u["horizontal"===this.orientation?"left":"bottom"]=i+"%",this.handle.stop(1,1)[l?"animate":"css"](u,r.animate),"min"===o&&"horizontal"===this.orientation&&this.range.stop(1,1)[l?"animate":"css"]({width:i+"%"},r.animate),"max"===o&&"horizontal"===this.orientation&&this.range[l?"animate":"css"]({width:100-i+"%"},{queue:!1,duration:r.animate}),"min"===o&&"vertical"===this.orientation&&this.range.stop(1,1)[l?"animate":"css"]({height:i+"%"},r.animate),"max"===o&&"vertical"===this.orientation&&this.range[l?"animate":"css"]({height:100-i+"%"},{queue:!1,duration:r.animate}))},_handleEvents:{keydown:function(t){var i,s,n,a,o=e(t.target).data("ui-slider-handle-index");switch(t.keyCode){case e.ui.keyCode.HOME:case e.ui.keyCode.END:case e.ui.keyCode.PAGE_UP:case e.ui.keyCode.PAGE_DOWN:case e.ui.keyCode.UP:case e.ui.keyCode.RIGHT:case e.ui.keyCode.DOWN:case e.ui.keyCode.LEFT:if(t.preventDefault(),!this._keySliding&&(this._keySliding=!0,e(t.target).addClass("ui-state-active"),i=this._start(t,o),i===!1))return}switch(a=this.options.step,s=n=this.options.values&&this.options.values.length?this.values(o):this.value(),t.keyCode){case e.ui.keyCode.HOME:n=this._valueMin();break;case e.ui.keyCode.END:n=this._valueMax();break;case e.ui.keyCode.PAGE_UP:n=this._trimAlignValue(s+(this._valueMax()-this._valueMin())/this.numPages);break;case e.ui.keyCode.PAGE_DOWN:n=this._trimAlignValue(s-(this._valueMax()-this._valueMin())/this.numPages);break;case e.ui.keyCode.UP:case e.ui.keyCode.RIGHT:if(s===this._valueMax())return;n=this._trimAlignValue(s+a);break;case e.ui.keyCode.DOWN:case e.ui.keyCode.LEFT:if(s===this._valueMin())return;n=this._trimAlignValue(s-a)}this._slide(t,o,n)},keyup:function(t){var i=e(t.target).data("ui-slider-handle-index");this._keySliding&&(this._keySliding=!1,this._stop(t,i),this._change(t,i),e(t.target).removeClass("ui-state-active"))}}}),e.widget("ui.sortable",e.ui.mouse,{version:"1.11.4",widgetEventPrefix:"sort",ready:!1,options:{appendTo:"parent",axis:!1,connectWith:!1,containment:!1,cursor:"auto",cursorAt:!1,dropOnEmpty:!0,forcePlaceholderSize:!1,forceHelperSize:!1,grid:!1,handle:!1,helper:"original",items:"> *",opacity:!1,placeholder:!1,revert:!1,scroll:!0,scrollSensitivity:20,scrollSpeed:20,scope:"default",tolerance:"intersect",zIndex:1e3,activate:null,beforeStop:null,change:null,deactivate:null,out:null,over:null,receive:null,remove:null,sort:null,start:null,stop:null,update:null},_isOverAxis:function(e,t,i){return e>=t&&t+i>e},_isFloating:function(e){return/left|right/.test(e.css("float"))||/inline|table-cell/.test(e.css("display"))},_create:function(){this.containerCache={},this.element.addClass("ui-sortable"),this.refresh(),this.offset=this.element.offset(),this._mouseInit(),this._setHandleClassName(),this.ready=!0},_setOption:function(e,t){this._super(e,t),"handle"===e&&this._setHandleClassName()},_setHandleClassName:function(){this.element.find(".ui-sortable-handle").removeClass("ui-sortable-handle"),e.each(this.items,function(){(this.instance.options.handle?this.item.find(this.instance.options.handle):this.item).addClass("ui-sortable-handle")})},_destroy:function(){this.element.removeClass("ui-sortable ui-sortable-disabled").find(".ui-sortable-handle").removeClass("ui-sortable-handle"),this._mouseDestroy();for(var e=this.items.length-1;e>=0;e--)this.items[e].item.removeData(this.widgetName+"-item");return this},_mouseCapture:function(t,i){var s=null,n=!1,a=this;return this.reverting?!1:this.options.disabled||"static"===this.options.type?!1:(this._refreshItems(t),e(t.target).parents().each(function(){return e.data(this,a.widgetName+"-item")===a?(s=e(this),!1):void 0}),e.data(t.target,a.widgetName+"-item")===a&&(s=e(t.target)),s?!this.options.handle||i||(e(this.options.handle,s).find("*").addBack().each(function(){this===t.target&&(n=!0)}),n)?(this.currentItem=s,this._removeCurrentsFromItems(),!0):!1:!1)},_mouseStart:function(t,i,s){var n,a,o=this.options;if(this.currentContainer=this,this.refreshPositions(),this.helper=this._createHelper(t),this._cacheHelperProportions(),this._cacheMargins(),this.scrollParent=this.helper.scrollParent(),this.offset=this.currentItem.offset(),this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left},e.extend(this.offset,{click:{left:t.pageX-this.offset.left,top:t.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()}),this.helper.css("position","absolute"),this.cssPosition=this.helper.css("position"),this.originalPosition=this._generatePosition(t),this.originalPageX=t.pageX,this.originalPageY=t.pageY,o.cursorAt&&this._adjustOffsetFromHelper(o.cursorAt),this.domPosition={prev:this.currentItem.prev()[0],parent:this.currentItem.parent()[0]},this.helper[0]!==this.currentItem[0]&&this.currentItem.hide(),this._createPlaceholder(),o.containment&&this._setContainment(),o.cursor&&"auto"!==o.cursor&&(a=this.document.find("body"),this.storedCursor=a.css("cursor"),a.css("cursor",o.cursor),this.storedStylesheet=e("<style>*{ cursor: "+o.cursor+" !important; }</style>").appendTo(a)),o.opacity&&(this.helper.css("opacity")&&(this._storedOpacity=this.helper.css("opacity")),this.helper.css("opacity",o.opacity)),o.zIndex&&(this.helper.css("zIndex")&&(this._storedZIndex=this.helper.css("zIndex")),this.helper.css("zIndex",o.zIndex)),this.scrollParent[0]!==this.document[0]&&"HTML"!==this.scrollParent[0].tagName&&(this.overflowOffset=this.scrollParent.offset()),this._trigger("start",t,this._uiHash()),this._preserveHelperProportions||this._cacheHelperProportions(),!s)for(n=this.containers.length-1;n>=0;n--)this.containers[n]._trigger("activate",t,this._uiHash(this));
return e.ui.ddmanager&&(e.ui.ddmanager.current=this),e.ui.ddmanager&&!o.dropBehaviour&&e.ui.ddmanager.prepareOffsets(this,t),this.dragging=!0,this.helper.addClass("ui-sortable-helper"),this._mouseDrag(t),!0},_mouseDrag:function(t){var i,s,n,a,o=this.options,r=!1;for(this.position=this._generatePosition(t),this.positionAbs=this._convertPositionTo("absolute"),this.lastPositionAbs||(this.lastPositionAbs=this.positionAbs),this.options.scroll&&(this.scrollParent[0]!==this.document[0]&&"HTML"!==this.scrollParent[0].tagName?(this.overflowOffset.top+this.scrollParent[0].offsetHeight-t.pageY<o.scrollSensitivity?this.scrollParent[0].scrollTop=r=this.scrollParent[0].scrollTop+o.scrollSpeed:t.pageY-this.overflowOffset.top<o.scrollSensitivity&&(this.scrollParent[0].scrollTop=r=this.scrollParent[0].scrollTop-o.scrollSpeed),this.overflowOffset.left+this.scrollParent[0].offsetWidth-t.pageX<o.scrollSensitivity?this.scrollParent[0].scrollLeft=r=this.scrollParent[0].scrollLeft+o.scrollSpeed:t.pageX-this.overflowOffset.left<o.scrollSensitivity&&(this.scrollParent[0].scrollLeft=r=this.scrollParent[0].scrollLeft-o.scrollSpeed)):(t.pageY-this.document.scrollTop()<o.scrollSensitivity?r=this.document.scrollTop(this.document.scrollTop()-o.scrollSpeed):this.window.height()-(t.pageY-this.document.scrollTop())<o.scrollSensitivity&&(r=this.document.scrollTop(this.document.scrollTop()+o.scrollSpeed)),t.pageX-this.document.scrollLeft()<o.scrollSensitivity?r=this.document.scrollLeft(this.document.scrollLeft()-o.scrollSpeed):this.window.width()-(t.pageX-this.document.scrollLeft())<o.scrollSensitivity&&(r=this.document.scrollLeft(this.document.scrollLeft()+o.scrollSpeed))),r!==!1&&e.ui.ddmanager&&!o.dropBehaviour&&e.ui.ddmanager.prepareOffsets(this,t)),this.positionAbs=this._convertPositionTo("absolute"),this.options.axis&&"y"===this.options.axis||(this.helper[0].style.left=this.position.left+"px"),this.options.axis&&"x"===this.options.axis||(this.helper[0].style.top=this.position.top+"px"),i=this.items.length-1;i>=0;i--)if(s=this.items[i],n=s.item[0],a=this._intersectsWithPointer(s),a&&s.instance===this.currentContainer&&n!==this.currentItem[0]&&this.placeholder[1===a?"next":"prev"]()[0]!==n&&!e.contains(this.placeholder[0],n)&&("semi-dynamic"===this.options.type?!e.contains(this.element[0],n):!0)){if(this.direction=1===a?"down":"up","pointer"!==this.options.tolerance&&!this._intersectsWithSides(s))break;this._rearrange(t,s),this._trigger("change",t,this._uiHash());break}return this._contactContainers(t),e.ui.ddmanager&&e.ui.ddmanager.drag(this,t),this._trigger("sort",t,this._uiHash()),this.lastPositionAbs=this.positionAbs,!1},_mouseStop:function(t,i){if(t){if(e.ui.ddmanager&&!this.options.dropBehaviour&&e.ui.ddmanager.drop(this,t),this.options.revert){var s=this,n=this.placeholder.offset(),a=this.options.axis,o={};a&&"x"!==a||(o.left=n.left-this.offset.parent.left-this.margins.left+(this.offsetParent[0]===this.document[0].body?0:this.offsetParent[0].scrollLeft)),a&&"y"!==a||(o.top=n.top-this.offset.parent.top-this.margins.top+(this.offsetParent[0]===this.document[0].body?0:this.offsetParent[0].scrollTop)),this.reverting=!0,e(this.helper).animate(o,parseInt(this.options.revert,10)||500,function(){s._clear(t)})}else this._clear(t,i);return!1}},cancel:function(){if(this.dragging){this._mouseUp({target:null}),"original"===this.options.helper?this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper"):this.currentItem.show();for(var t=this.containers.length-1;t>=0;t--)this.containers[t]._trigger("deactivate",null,this._uiHash(this)),this.containers[t].containerCache.over&&(this.containers[t]._trigger("out",null,this._uiHash(this)),this.containers[t].containerCache.over=0)}return this.placeholder&&(this.placeholder[0].parentNode&&this.placeholder[0].parentNode.removeChild(this.placeholder[0]),"original"!==this.options.helper&&this.helper&&this.helper[0].parentNode&&this.helper.remove(),e.extend(this,{helper:null,dragging:!1,reverting:!1,_noFinalSort:null}),this.domPosition.prev?e(this.domPosition.prev).after(this.currentItem):e(this.domPosition.parent).prepend(this.currentItem)),this},serialize:function(t){var i=this._getItemsAsjQuery(t&&t.connected),s=[];return t=t||{},e(i).each(function(){var i=(e(t.item||this).attr(t.attribute||"id")||"").match(t.expression||/(.+)[\-=_](.+)/);i&&s.push((t.key||i[1]+"[]")+"="+(t.key&&t.expression?i[1]:i[2]))}),!s.length&&t.key&&s.push(t.key+"="),s.join("&")},toArray:function(t){var i=this._getItemsAsjQuery(t&&t.connected),s=[];return t=t||{},i.each(function(){s.push(e(t.item||this).attr(t.attribute||"id")||"")}),s},_intersectsWith:function(e){var t=this.positionAbs.left,i=t+this.helperProportions.width,s=this.positionAbs.top,n=s+this.helperProportions.height,a=e.left,o=a+e.width,r=e.top,h=r+e.height,l=this.offset.click.top,u=this.offset.click.left,d="x"===this.options.axis||s+l>r&&h>s+l,c="y"===this.options.axis||t+u>a&&o>t+u,p=d&&c;return"pointer"===this.options.tolerance||this.options.forcePointerForContainers||"pointer"!==this.options.tolerance&&this.helperProportions[this.floating?"width":"height"]>e[this.floating?"width":"height"]?p:t+this.helperProportions.width/2>a&&o>i-this.helperProportions.width/2&&s+this.helperProportions.height/2>r&&h>n-this.helperProportions.height/2},_intersectsWithPointer:function(e){var t="x"===this.options.axis||this._isOverAxis(this.positionAbs.top+this.offset.click.top,e.top,e.height),i="y"===this.options.axis||this._isOverAxis(this.positionAbs.left+this.offset.click.left,e.left,e.width),s=t&&i,n=this._getDragVerticalDirection(),a=this._getDragHorizontalDirection();return s?this.floating?a&&"right"===a||"down"===n?2:1:n&&("down"===n?2:1):!1},_intersectsWithSides:function(e){var t=this._isOverAxis(this.positionAbs.top+this.offset.click.top,e.top+e.height/2,e.height),i=this._isOverAxis(this.positionAbs.left+this.offset.click.left,e.left+e.width/2,e.width),s=this._getDragVerticalDirection(),n=this._getDragHorizontalDirection();return this.floating&&n?"right"===n&&i||"left"===n&&!i:s&&("down"===s&&t||"up"===s&&!t)},_getDragVerticalDirection:function(){var e=this.positionAbs.top-this.lastPositionAbs.top;return 0!==e&&(e>0?"down":"up")},_getDragHorizontalDirection:function(){var e=this.positionAbs.left-this.lastPositionAbs.left;return 0!==e&&(e>0?"right":"left")},refresh:function(e){return this._refreshItems(e),this._setHandleClassName(),this.refreshPositions(),this},_connectWith:function(){var e=this.options;return e.connectWith.constructor===String?[e.connectWith]:e.connectWith},_getItemsAsjQuery:function(t){function i(){r.push(this)}var s,n,a,o,r=[],h=[],l=this._connectWith();if(l&&t)for(s=l.length-1;s>=0;s--)for(a=e(l[s],this.document[0]),n=a.length-1;n>=0;n--)o=e.data(a[n],this.widgetFullName),o&&o!==this&&!o.options.disabled&&h.push([e.isFunction(o.options.items)?o.options.items.call(o.element):e(o.options.items,o.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),o]);for(h.push([e.isFunction(this.options.items)?this.options.items.call(this.element,null,{options:this.options,item:this.currentItem}):e(this.options.items,this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),this]),s=h.length-1;s>=0;s--)h[s][0].each(i);return e(r)},_removeCurrentsFromItems:function(){var t=this.currentItem.find(":data("+this.widgetName+"-item)");this.items=e.grep(this.items,function(e){for(var i=0;t.length>i;i++)if(t[i]===e.item[0])return!1;return!0})},_refreshItems:function(t){this.items=[],this.containers=[this];var i,s,n,a,o,r,h,l,u=this.items,d=[[e.isFunction(this.options.items)?this.options.items.call(this.element[0],t,{item:this.currentItem}):e(this.options.items,this.element),this]],c=this._connectWith();if(c&&this.ready)for(i=c.length-1;i>=0;i--)for(n=e(c[i],this.document[0]),s=n.length-1;s>=0;s--)a=e.data(n[s],this.widgetFullName),a&&a!==this&&!a.options.disabled&&(d.push([e.isFunction(a.options.items)?a.options.items.call(a.element[0],t,{item:this.currentItem}):e(a.options.items,a.element),a]),this.containers.push(a));for(i=d.length-1;i>=0;i--)for(o=d[i][1],r=d[i][0],s=0,l=r.length;l>s;s++)h=e(r[s]),h.data(this.widgetName+"-item",o),u.push({item:h,instance:o,width:0,height:0,left:0,top:0})},refreshPositions:function(t){this.floating=this.items.length?"x"===this.options.axis||this._isFloating(this.items[0].item):!1,this.offsetParent&&this.helper&&(this.offset.parent=this._getParentOffset());var i,s,n,a;for(i=this.items.length-1;i>=0;i--)s=this.items[i],s.instance!==this.currentContainer&&this.currentContainer&&s.item[0]!==this.currentItem[0]||(n=this.options.toleranceElement?e(this.options.toleranceElement,s.item):s.item,t||(s.width=n.outerWidth(),s.height=n.outerHeight()),a=n.offset(),s.left=a.left,s.top=a.top);if(this.options.custom&&this.options.custom.refreshContainers)this.options.custom.refreshContainers.call(this);else for(i=this.containers.length-1;i>=0;i--)a=this.containers[i].element.offset(),this.containers[i].containerCache.left=a.left,this.containers[i].containerCache.top=a.top,this.containers[i].containerCache.width=this.containers[i].element.outerWidth(),this.containers[i].containerCache.height=this.containers[i].element.outerHeight();return this},_createPlaceholder:function(t){t=t||this;var i,s=t.options;s.placeholder&&s.placeholder.constructor!==String||(i=s.placeholder,s.placeholder={element:function(){var s=t.currentItem[0].nodeName.toLowerCase(),n=e("<"+s+">",t.document[0]).addClass(i||t.currentItem[0].className+" ui-sortable-placeholder").removeClass("ui-sortable-helper");return"tbody"===s?t._createTrPlaceholder(t.currentItem.find("tr").eq(0),e("<tr>",t.document[0]).appendTo(n)):"tr"===s?t._createTrPlaceholder(t.currentItem,n):"img"===s&&n.attr("src",t.currentItem.attr("src")),i||n.css("visibility","hidden"),n},update:function(e,n){(!i||s.forcePlaceholderSize)&&(n.height()||n.height(t.currentItem.innerHeight()-parseInt(t.currentItem.css("paddingTop")||0,10)-parseInt(t.currentItem.css("paddingBottom")||0,10)),n.width()||n.width(t.currentItem.innerWidth()-parseInt(t.currentItem.css("paddingLeft")||0,10)-parseInt(t.currentItem.css("paddingRight")||0,10)))}}),t.placeholder=e(s.placeholder.element.call(t.element,t.currentItem)),t.currentItem.after(t.placeholder),s.placeholder.update(t,t.placeholder)},_createTrPlaceholder:function(t,i){var s=this;t.children().each(function(){e("<td>&#160;</td>",s.document[0]).attr("colspan",e(this).attr("colspan")||1).appendTo(i)})},_contactContainers:function(t){var i,s,n,a,o,r,h,l,u,d,c=null,p=null;for(i=this.containers.length-1;i>=0;i--)if(!e.contains(this.currentItem[0],this.containers[i].element[0]))if(this._intersectsWith(this.containers[i].containerCache)){if(c&&e.contains(this.containers[i].element[0],c.element[0]))continue;c=this.containers[i],p=i}else this.containers[i].containerCache.over&&(this.containers[i]._trigger("out",t,this._uiHash(this)),this.containers[i].containerCache.over=0);if(c)if(1===this.containers.length)this.containers[p].containerCache.over||(this.containers[p]._trigger("over",t,this._uiHash(this)),this.containers[p].containerCache.over=1);else{for(n=1e4,a=null,u=c.floating||this._isFloating(this.currentItem),o=u?"left":"top",r=u?"width":"height",d=u?"clientX":"clientY",s=this.items.length-1;s>=0;s--)e.contains(this.containers[p].element[0],this.items[s].item[0])&&this.items[s].item[0]!==this.currentItem[0]&&(h=this.items[s].item.offset()[o],l=!1,t[d]-h>this.items[s][r]/2&&(l=!0),n>Math.abs(t[d]-h)&&(n=Math.abs(t[d]-h),a=this.items[s],this.direction=l?"up":"down"));if(!a&&!this.options.dropOnEmpty)return;if(this.currentContainer===this.containers[p])return this.currentContainer.containerCache.over||(this.containers[p]._trigger("over",t,this._uiHash()),this.currentContainer.containerCache.over=1),void 0;a?this._rearrange(t,a,null,!0):this._rearrange(t,null,this.containers[p].element,!0),this._trigger("change",t,this._uiHash()),this.containers[p]._trigger("change",t,this._uiHash(this)),this.currentContainer=this.containers[p],this.options.placeholder.update(this.currentContainer,this.placeholder),this.containers[p]._trigger("over",t,this._uiHash(this)),this.containers[p].containerCache.over=1}},_createHelper:function(t){var i=this.options,s=e.isFunction(i.helper)?e(i.helper.apply(this.element[0],[t,this.currentItem])):"clone"===i.helper?this.currentItem.clone():this.currentItem;return s.parents("body").length||e("parent"!==i.appendTo?i.appendTo:this.currentItem[0].parentNode)[0].appendChild(s[0]),s[0]===this.currentItem[0]&&(this._storedCSS={width:this.currentItem[0].style.width,height:this.currentItem[0].style.height,position:this.currentItem.css("position"),top:this.currentItem.css("top"),left:this.currentItem.css("left")}),(!s[0].style.width||i.forceHelperSize)&&s.width(this.currentItem.width()),(!s[0].style.height||i.forceHelperSize)&&s.height(this.currentItem.height()),s},_adjustOffsetFromHelper:function(t){"string"==typeof t&&(t=t.split(" ")),e.isArray(t)&&(t={left:+t[0],top:+t[1]||0}),"left"in t&&(this.offset.click.left=t.left+this.margins.left),"right"in t&&(this.offset.click.left=this.helperProportions.width-t.right+this.margins.left),"top"in t&&(this.offset.click.top=t.top+this.margins.top),"bottom"in t&&(this.offset.click.top=this.helperProportions.height-t.bottom+this.margins.top)},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var t=this.offsetParent.offset();return"absolute"===this.cssPosition&&this.scrollParent[0]!==this.document[0]&&e.contains(this.scrollParent[0],this.offsetParent[0])&&(t.left+=this.scrollParent.scrollLeft(),t.top+=this.scrollParent.scrollTop()),(this.offsetParent[0]===this.document[0].body||this.offsetParent[0].tagName&&"html"===this.offsetParent[0].tagName.toLowerCase()&&e.ui.ie)&&(t={top:0,left:0}),{top:t.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:t.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if("relative"===this.cssPosition){var e=this.currentItem.position();return{top:e.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:e.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.currentItem.css("marginLeft"),10)||0,top:parseInt(this.currentItem.css("marginTop"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var t,i,s,n=this.options;"parent"===n.containment&&(n.containment=this.helper[0].parentNode),("document"===n.containment||"window"===n.containment)&&(this.containment=[0-this.offset.relative.left-this.offset.parent.left,0-this.offset.relative.top-this.offset.parent.top,"document"===n.containment?this.document.width():this.window.width()-this.helperProportions.width-this.margins.left,("document"===n.containment?this.document.width():this.window.height()||this.document[0].body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top]),/^(document|window|parent)$/.test(n.containment)||(t=e(n.containment)[0],i=e(n.containment).offset(),s="hidden"!==e(t).css("overflow"),this.containment=[i.left+(parseInt(e(t).css("borderLeftWidth"),10)||0)+(parseInt(e(t).css("paddingLeft"),10)||0)-this.margins.left,i.top+(parseInt(e(t).css("borderTopWidth"),10)||0)+(parseInt(e(t).css("paddingTop"),10)||0)-this.margins.top,i.left+(s?Math.max(t.scrollWidth,t.offsetWidth):t.offsetWidth)-(parseInt(e(t).css("borderLeftWidth"),10)||0)-(parseInt(e(t).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left,i.top+(s?Math.max(t.scrollHeight,t.offsetHeight):t.offsetHeight)-(parseInt(e(t).css("borderTopWidth"),10)||0)-(parseInt(e(t).css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top])},_convertPositionTo:function(t,i){i||(i=this.position);var s="absolute"===t?1:-1,n="absolute"!==this.cssPosition||this.scrollParent[0]!==this.document[0]&&e.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,a=/(html|body)/i.test(n[0].tagName);return{top:i.top+this.offset.relative.top*s+this.offset.parent.top*s-("fixed"===this.cssPosition?-this.scrollParent.scrollTop():a?0:n.scrollTop())*s,left:i.left+this.offset.relative.left*s+this.offset.parent.left*s-("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():a?0:n.scrollLeft())*s}},_generatePosition:function(t){var i,s,n=this.options,a=t.pageX,o=t.pageY,r="absolute"!==this.cssPosition||this.scrollParent[0]!==this.document[0]&&e.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,h=/(html|body)/i.test(r[0].tagName);return"relative"!==this.cssPosition||this.scrollParent[0]!==this.document[0]&&this.scrollParent[0]!==this.offsetParent[0]||(this.offset.relative=this._getRelativeOffset()),this.originalPosition&&(this.containment&&(t.pageX-this.offset.click.left<this.containment[0]&&(a=this.containment[0]+this.offset.click.left),t.pageY-this.offset.click.top<this.containment[1]&&(o=this.containment[1]+this.offset.click.top),t.pageX-this.offset.click.left>this.containment[2]&&(a=this.containment[2]+this.offset.click.left),t.pageY-this.offset.click.top>this.containment[3]&&(o=this.containment[3]+this.offset.click.top)),n.grid&&(i=this.originalPageY+Math.round((o-this.originalPageY)/n.grid[1])*n.grid[1],o=this.containment?i-this.offset.click.top>=this.containment[1]&&i-this.offset.click.top<=this.containment[3]?i:i-this.offset.click.top>=this.containment[1]?i-n.grid[1]:i+n.grid[1]:i,s=this.originalPageX+Math.round((a-this.originalPageX)/n.grid[0])*n.grid[0],a=this.containment?s-this.offset.click.left>=this.containment[0]&&s-this.offset.click.left<=this.containment[2]?s:s-this.offset.click.left>=this.containment[0]?s-n.grid[0]:s+n.grid[0]:s)),{top:o-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+("fixed"===this.cssPosition?-this.scrollParent.scrollTop():h?0:r.scrollTop()),left:a-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():h?0:r.scrollLeft())}},_rearrange:function(e,t,i,s){i?i[0].appendChild(this.placeholder[0]):t.item[0].parentNode.insertBefore(this.placeholder[0],"down"===this.direction?t.item[0]:t.item[0].nextSibling),this.counter=this.counter?++this.counter:1;var n=this.counter;this._delay(function(){n===this.counter&&this.refreshPositions(!s)})},_clear:function(e,t){function i(e,t,i){return function(s){i._trigger(e,s,t._uiHash(t))}}this.reverting=!1;var s,n=[];if(!this._noFinalSort&&this.currentItem.parent().length&&this.placeholder.before(this.currentItem),this._noFinalSort=null,this.helper[0]===this.currentItem[0]){for(s in this._storedCSS)("auto"===this._storedCSS[s]||"static"===this._storedCSS[s])&&(this._storedCSS[s]="");this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")}else this.currentItem.show();for(this.fromOutside&&!t&&n.push(function(e){this._trigger("receive",e,this._uiHash(this.fromOutside))}),!this.fromOutside&&this.domPosition.prev===this.currentItem.prev().not(".ui-sortable-helper")[0]&&this.domPosition.parent===this.currentItem.parent()[0]||t||n.push(function(e){this._trigger("update",e,this._uiHash())}),this!==this.currentContainer&&(t||(n.push(function(e){this._trigger("remove",e,this._uiHash())}),n.push(function(e){return function(t){e._trigger("receive",t,this._uiHash(this))}}.call(this,this.currentContainer)),n.push(function(e){return function(t){e._trigger("update",t,this._uiHash(this))}}.call(this,this.currentContainer)))),s=this.containers.length-1;s>=0;s--)t||n.push(i("deactivate",this,this.containers[s])),this.containers[s].containerCache.over&&(n.push(i("out",this,this.containers[s])),this.containers[s].containerCache.over=0);if(this.storedCursor&&(this.document.find("body").css("cursor",this.storedCursor),this.storedStylesheet.remove()),this._storedOpacity&&this.helper.css("opacity",this._storedOpacity),this._storedZIndex&&this.helper.css("zIndex","auto"===this._storedZIndex?"":this._storedZIndex),this.dragging=!1,t||this._trigger("beforeStop",e,this._uiHash()),this.placeholder[0].parentNode.removeChild(this.placeholder[0]),this.cancelHelperRemoval||(this.helper[0]!==this.currentItem[0]&&this.helper.remove(),this.helper=null),!t){for(s=0;n.length>s;s++)n[s].call(this,e);this._trigger("stop",e,this._uiHash())}return this.fromOutside=!1,!this.cancelHelperRemoval},_trigger:function(){e.Widget.prototype._trigger.apply(this,arguments)===!1&&this.cancel()},_uiHash:function(t){var i=t||this;return{helper:i.helper,placeholder:i.placeholder||e([]),position:i.position,originalPosition:i.originalPosition,offset:i.positionAbs,item:i.currentItem,sender:t?t.element:null}}}),e.widget("ui.spinner",{version:"1.11.4",defaultElement:"<input>",widgetEventPrefix:"spin",options:{culture:null,icons:{down:"ui-icon-triangle-1-s",up:"ui-icon-triangle-1-n"},incremental:!0,max:null,min:null,numberFormat:null,page:10,step:1,change:null,spin:null,start:null,stop:null},_create:function(){this._setOption("max",this.options.max),this._setOption("min",this.options.min),this._setOption("step",this.options.step),""!==this.value()&&this._value(this.element.val(),!0),this._draw(),this._on(this._events),this._refresh(),this._on(this.window,{beforeunload:function(){this.element.removeAttr("autocomplete")}})},_getCreateOptions:function(){var t={},i=this.element;return e.each(["min","max","step"],function(e,s){var n=i.attr(s);void 0!==n&&n.length&&(t[s]=n)}),t},_events:{keydown:function(e){this._start(e)&&this._keydown(e)&&e.preventDefault()},keyup:"_stop",focus:function(){this.previous=this.element.val()},blur:function(e){return this.cancelBlur?(delete this.cancelBlur,void 0):(this._stop(),this._refresh(),this.previous!==this.element.val()&&this._trigger("change",e),void 0)},mousewheel:function(e,t){if(t){if(!this.spinning&&!this._start(e))return!1;this._spin((t>0?1:-1)*this.options.step,e),clearTimeout(this.mousewheelTimer),this.mousewheelTimer=this._delay(function(){this.spinning&&this._stop(e)},100),e.preventDefault()}},"mousedown .ui-spinner-button":function(t){function i(){var e=this.element[0]===this.document[0].activeElement;e||(this.element.focus(),this.previous=s,this._delay(function(){this.previous=s}))}var s;s=this.element[0]===this.document[0].activeElement?this.previous:this.element.val(),t.preventDefault(),i.call(this),this.cancelBlur=!0,this._delay(function(){delete this.cancelBlur,i.call(this)}),this._start(t)!==!1&&this._repeat(null,e(t.currentTarget).hasClass("ui-spinner-up")?1:-1,t)},"mouseup .ui-spinner-button":"_stop","mouseenter .ui-spinner-button":function(t){return e(t.currentTarget).hasClass("ui-state-active")?this._start(t)===!1?!1:(this._repeat(null,e(t.currentTarget).hasClass("ui-spinner-up")?1:-1,t),void 0):void 0},"mouseleave .ui-spinner-button":"_stop"},_draw:function(){var e=this.uiSpinner=this.element.addClass("ui-spinner-input").attr("autocomplete","off").wrap(this._uiSpinnerHtml()).parent().append(this._buttonHtml());this.element.attr("role","spinbutton"),this.buttons=e.find(".ui-spinner-button").attr("tabIndex",-1).button().removeClass("ui-corner-all"),this.buttons.height()>Math.ceil(.5*e.height())&&e.height()>0&&e.height(e.height()),this.options.disabled&&this.disable()},_keydown:function(t){var i=this.options,s=e.ui.keyCode;switch(t.keyCode){case s.UP:return this._repeat(null,1,t),!0;case s.DOWN:return this._repeat(null,-1,t),!0;case s.PAGE_UP:return this._repeat(null,i.page,t),!0;case s.PAGE_DOWN:return this._repeat(null,-i.page,t),!0}return!1},_uiSpinnerHtml:function(){return"<span class='ui-spinner ui-widget ui-widget-content ui-corner-all'></span>"},_buttonHtml:function(){return"<a class='ui-spinner-button ui-spinner-up ui-corner-tr'><span class='ui-icon "+this.options.icons.up+"'>&#9650;</span>"+"</a>"+"<a class='ui-spinner-button ui-spinner-down ui-corner-br'>"+"<span class='ui-icon "+this.options.icons.down+"'>&#9660;</span>"+"</a>"},_start:function(e){return this.spinning||this._trigger("start",e)!==!1?(this.counter||(this.counter=1),this.spinning=!0,!0):!1},_repeat:function(e,t,i){e=e||500,clearTimeout(this.timer),this.timer=this._delay(function(){this._repeat(40,t,i)},e),this._spin(t*this.options.step,i)},_spin:function(e,t){var i=this.value()||0;this.counter||(this.counter=1),i=this._adjustValue(i+e*this._increment(this.counter)),this.spinning&&this._trigger("spin",t,{value:i})===!1||(this._value(i),this.counter++)},_increment:function(t){var i=this.options.incremental;return i?e.isFunction(i)?i(t):Math.floor(t*t*t/5e4-t*t/500+17*t/200+1):1},_precision:function(){var e=this._precisionOf(this.options.step);return null!==this.options.min&&(e=Math.max(e,this._precisionOf(this.options.min))),e},_precisionOf:function(e){var t=""+e,i=t.indexOf(".");return-1===i?0:t.length-i-1},_adjustValue:function(e){var t,i,s=this.options;return t=null!==s.min?s.min:0,i=e-t,i=Math.round(i/s.step)*s.step,e=t+i,e=parseFloat(e.toFixed(this._precision())),null!==s.max&&e>s.max?s.max:null!==s.min&&s.min>e?s.min:e},_stop:function(e){this.spinning&&(clearTimeout(this.timer),clearTimeout(this.mousewheelTimer),this.counter=0,this.spinning=!1,this._trigger("stop",e))},_setOption:function(e,t){if("culture"===e||"numberFormat"===e){var i=this._parse(this.element.val());return this.options[e]=t,this.element.val(this._format(i)),void 0}("max"===e||"min"===e||"step"===e)&&"string"==typeof t&&(t=this._parse(t)),"icons"===e&&(this.buttons.first().find(".ui-icon").removeClass(this.options.icons.up).addClass(t.up),this.buttons.last().find(".ui-icon").removeClass(this.options.icons.down).addClass(t.down)),this._super(e,t),"disabled"===e&&(this.widget().toggleClass("ui-state-disabled",!!t),this.element.prop("disabled",!!t),this.buttons.button(t?"disable":"enable"))},_setOptions:h(function(e){this._super(e)}),_parse:function(e){return"string"==typeof e&&""!==e&&(e=window.Globalize&&this.options.numberFormat?Globalize.parseFloat(e,10,this.options.culture):+e),""===e||isNaN(e)?null:e},_format:function(e){return""===e?"":window.Globalize&&this.options.numberFormat?Globalize.format(e,this.options.numberFormat,this.options.culture):e},_refresh:function(){this.element.attr({"aria-valuemin":this.options.min,"aria-valuemax":this.options.max,"aria-valuenow":this._parse(this.element.val())})},isValid:function(){var e=this.value();return null===e?!1:e===this._adjustValue(e)},_value:function(e,t){var i;""!==e&&(i=this._parse(e),null!==i&&(t||(i=this._adjustValue(i)),e=this._format(i))),this.element.val(e),this._refresh()},_destroy:function(){this.element.removeClass("ui-spinner-input").prop("disabled",!1).removeAttr("autocomplete").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"),this.uiSpinner.replaceWith(this.element)},stepUp:h(function(e){this._stepUp(e)}),_stepUp:function(e){this._start()&&(this._spin((e||1)*this.options.step),this._stop())},stepDown:h(function(e){this._stepDown(e)}),_stepDown:function(e){this._start()&&(this._spin((e||1)*-this.options.step),this._stop())},pageUp:h(function(e){this._stepUp((e||1)*this.options.page)}),pageDown:h(function(e){this._stepDown((e||1)*this.options.page)}),value:function(e){return arguments.length?(h(this._value).call(this,e),void 0):this._parse(this.element.val())},widget:function(){return this.uiSpinner}}),e.widget("ui.tabs",{version:"1.11.4",delay:300,options:{active:null,collapsible:!1,event:"click",heightStyle:"content",hide:null,show:null,activate:null,beforeActivate:null,beforeLoad:null,load:null},_isLocal:function(){var e=/#.*$/;return function(t){var i,s;t=t.cloneNode(!1),i=t.href.replace(e,""),s=location.href.replace(e,"");try{i=decodeURIComponent(i)}catch(n){}try{s=decodeURIComponent(s)}catch(n){}return t.hash.length>1&&i===s}}(),_create:function(){var t=this,i=this.options;this.running=!1,this.element.addClass("ui-tabs ui-widget ui-widget-content ui-corner-all").toggleClass("ui-tabs-collapsible",i.collapsible),this._processTabs(),i.active=this._initialActive(),e.isArray(i.disabled)&&(i.disabled=e.unique(i.disabled.concat(e.map(this.tabs.filter(".ui-state-disabled"),function(e){return t.tabs.index(e)}))).sort()),this.active=this.options.active!==!1&&this.anchors.length?this._findActive(i.active):e(),this._refresh(),this.active.length&&this.load(i.active)},_initialActive:function(){var t=this.options.active,i=this.options.collapsible,s=location.hash.substring(1);return null===t&&(s&&this.tabs.each(function(i,n){return e(n).attr("aria-controls")===s?(t=i,!1):void 0}),null===t&&(t=this.tabs.index(this.tabs.filter(".ui-tabs-active"))),(null===t||-1===t)&&(t=this.tabs.length?0:!1)),t!==!1&&(t=this.tabs.index(this.tabs.eq(t)),-1===t&&(t=i?!1:0)),!i&&t===!1&&this.anchors.length&&(t=0),t},_getCreateEventData:function(){return{tab:this.active,panel:this.active.length?this._getPanelForTab(this.active):e()}},_tabKeydown:function(t){var i=e(this.document[0].activeElement).closest("li"),s=this.tabs.index(i),n=!0;if(!this._handlePageNav(t)){switch(t.keyCode){case e.ui.keyCode.RIGHT:case e.ui.keyCode.DOWN:s++;break;case e.ui.keyCode.UP:case e.ui.keyCode.LEFT:n=!1,s--;break;case e.ui.keyCode.END:s=this.anchors.length-1;break;case e.ui.keyCode.HOME:s=0;break;case e.ui.keyCode.SPACE:return t.preventDefault(),clearTimeout(this.activating),this._activate(s),void 0;case e.ui.keyCode.ENTER:return t.preventDefault(),clearTimeout(this.activating),this._activate(s===this.options.active?!1:s),void 0;default:return}t.preventDefault(),clearTimeout(this.activating),s=this._focusNextTab(s,n),t.ctrlKey||t.metaKey||(i.attr("aria-selected","false"),this.tabs.eq(s).attr("aria-selected","true"),this.activating=this._delay(function(){this.option("active",s)},this.delay))}},_panelKeydown:function(t){this._handlePageNav(t)||t.ctrlKey&&t.keyCode===e.ui.keyCode.UP&&(t.preventDefault(),this.active.focus())},_handlePageNav:function(t){return t.altKey&&t.keyCode===e.ui.keyCode.PAGE_UP?(this._activate(this._focusNextTab(this.options.active-1,!1)),!0):t.altKey&&t.keyCode===e.ui.keyCode.PAGE_DOWN?(this._activate(this._focusNextTab(this.options.active+1,!0)),!0):void 0},_findNextTab:function(t,i){function s(){return t>n&&(t=0),0>t&&(t=n),t}for(var n=this.tabs.length-1;-1!==e.inArray(s(),this.options.disabled);)t=i?t+1:t-1;return t},_focusNextTab:function(e,t){return e=this._findNextTab(e,t),this.tabs.eq(e).focus(),e},_setOption:function(e,t){return"active"===e?(this._activate(t),void 0):"disabled"===e?(this._setupDisabled(t),void 0):(this._super(e,t),"collapsible"===e&&(this.element.toggleClass("ui-tabs-collapsible",t),t||this.options.active!==!1||this._activate(0)),"event"===e&&this._setupEvents(t),"heightStyle"===e&&this._setupHeightStyle(t),void 0)},_sanitizeSelector:function(e){return e?e.replace(/[!"$%&'()*+,.\/:;<=>?@\[\]\^`{|}~]/g,"\\$&"):""},refresh:function(){var t=this.options,i=this.tablist.children(":has(a[href])");t.disabled=e.map(i.filter(".ui-state-disabled"),function(e){return i.index(e)}),this._processTabs(),t.active!==!1&&this.anchors.length?this.active.length&&!e.contains(this.tablist[0],this.active[0])?this.tabs.length===t.disabled.length?(t.active=!1,this.active=e()):this._activate(this._findNextTab(Math.max(0,t.active-1),!1)):t.active=this.tabs.index(this.active):(t.active=!1,this.active=e()),this._refresh()},_refresh:function(){this._setupDisabled(this.options.disabled),this._setupEvents(this.options.event),this._setupHeightStyle(this.options.heightStyle),this.tabs.not(this.active).attr({"aria-selected":"false","aria-expanded":"false",tabIndex:-1}),this.panels.not(this._getPanelForTab(this.active)).hide().attr({"aria-hidden":"true"}),this.active.length?(this.active.addClass("ui-tabs-active ui-state-active").attr({"aria-selected":"true","aria-expanded":"true",tabIndex:0}),this._getPanelForTab(this.active).show().attr({"aria-hidden":"false"})):this.tabs.eq(0).attr("tabIndex",0)},_processTabs:function(){var t=this,i=this.tabs,s=this.anchors,n=this.panels;
this.tablist=this._getList().addClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").attr("role","tablist").delegate("> li","mousedown"+this.eventNamespace,function(t){e(this).is(".ui-state-disabled")&&t.preventDefault()}).delegate(".ui-tabs-anchor","focus"+this.eventNamespace,function(){e(this).closest("li").is(".ui-state-disabled")&&this.blur()}),this.tabs=this.tablist.find("> li:has(a[href])").addClass("ui-state-default ui-corner-top").attr({role:"tab",tabIndex:-1}),this.anchors=this.tabs.map(function(){return e("a",this)[0]}).addClass("ui-tabs-anchor").attr({role:"presentation",tabIndex:-1}),this.panels=e(),this.anchors.each(function(i,s){var n,a,o,r=e(s).uniqueId().attr("id"),h=e(s).closest("li"),l=h.attr("aria-controls");t._isLocal(s)?(n=s.hash,o=n.substring(1),a=t.element.find(t._sanitizeSelector(n))):(o=h.attr("aria-controls")||e({}).uniqueId()[0].id,n="#"+o,a=t.element.find(n),a.length||(a=t._createPanel(o),a.insertAfter(t.panels[i-1]||t.tablist)),a.attr("aria-live","polite")),a.length&&(t.panels=t.panels.add(a)),l&&h.data("ui-tabs-aria-controls",l),h.attr({"aria-controls":o,"aria-labelledby":r}),a.attr("aria-labelledby",r)}),this.panels.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").attr("role","tabpanel"),i&&(this._off(i.not(this.tabs)),this._off(s.not(this.anchors)),this._off(n.not(this.panels)))},_getList:function(){return this.tablist||this.element.find("ol,ul").eq(0)},_createPanel:function(t){return e("<div>").attr("id",t).addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").data("ui-tabs-destroy",!0)},_setupDisabled:function(t){e.isArray(t)&&(t.length?t.length===this.anchors.length&&(t=!0):t=!1);for(var i,s=0;i=this.tabs[s];s++)t===!0||-1!==e.inArray(s,t)?e(i).addClass("ui-state-disabled").attr("aria-disabled","true"):e(i).removeClass("ui-state-disabled").removeAttr("aria-disabled");this.options.disabled=t},_setupEvents:function(t){var i={};t&&e.each(t.split(" "),function(e,t){i[t]="_eventHandler"}),this._off(this.anchors.add(this.tabs).add(this.panels)),this._on(!0,this.anchors,{click:function(e){e.preventDefault()}}),this._on(this.anchors,i),this._on(this.tabs,{keydown:"_tabKeydown"}),this._on(this.panels,{keydown:"_panelKeydown"}),this._focusable(this.tabs),this._hoverable(this.tabs)},_setupHeightStyle:function(t){var i,s=this.element.parent();"fill"===t?(i=s.height(),i-=this.element.outerHeight()-this.element.height(),this.element.siblings(":visible").each(function(){var t=e(this),s=t.css("position");"absolute"!==s&&"fixed"!==s&&(i-=t.outerHeight(!0))}),this.element.children().not(this.panels).each(function(){i-=e(this).outerHeight(!0)}),this.panels.each(function(){e(this).height(Math.max(0,i-e(this).innerHeight()+e(this).height()))}).css("overflow","auto")):"auto"===t&&(i=0,this.panels.each(function(){i=Math.max(i,e(this).height("").height())}).height(i))},_eventHandler:function(t){var i=this.options,s=this.active,n=e(t.currentTarget),a=n.closest("li"),o=a[0]===s[0],r=o&&i.collapsible,h=r?e():this._getPanelForTab(a),l=s.length?this._getPanelForTab(s):e(),u={oldTab:s,oldPanel:l,newTab:r?e():a,newPanel:h};t.preventDefault(),a.hasClass("ui-state-disabled")||a.hasClass("ui-tabs-loading")||this.running||o&&!i.collapsible||this._trigger("beforeActivate",t,u)===!1||(i.active=r?!1:this.tabs.index(a),this.active=o?e():a,this.xhr&&this.xhr.abort(),l.length||h.length||e.error("jQuery UI Tabs: Mismatching fragment identifier."),h.length&&this.load(this.tabs.index(a),t),this._toggle(t,u))},_toggle:function(t,i){function s(){a.running=!1,a._trigger("activate",t,i)}function n(){i.newTab.closest("li").addClass("ui-tabs-active ui-state-active"),o.length&&a.options.show?a._show(o,a.options.show,s):(o.show(),s())}var a=this,o=i.newPanel,r=i.oldPanel;this.running=!0,r.length&&this.options.hide?this._hide(r,this.options.hide,function(){i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"),n()}):(i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"),r.hide(),n()),r.attr("aria-hidden","true"),i.oldTab.attr({"aria-selected":"false","aria-expanded":"false"}),o.length&&r.length?i.oldTab.attr("tabIndex",-1):o.length&&this.tabs.filter(function(){return 0===e(this).attr("tabIndex")}).attr("tabIndex",-1),o.attr("aria-hidden","false"),i.newTab.attr({"aria-selected":"true","aria-expanded":"true",tabIndex:0})},_activate:function(t){var i,s=this._findActive(t);s[0]!==this.active[0]&&(s.length||(s=this.active),i=s.find(".ui-tabs-anchor")[0],this._eventHandler({target:i,currentTarget:i,preventDefault:e.noop}))},_findActive:function(t){return t===!1?e():this.tabs.eq(t)},_getIndex:function(e){return"string"==typeof e&&(e=this.anchors.index(this.anchors.filter("[href$='"+e+"']"))),e},_destroy:function(){this.xhr&&this.xhr.abort(),this.element.removeClass("ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible"),this.tablist.removeClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").removeAttr("role"),this.anchors.removeClass("ui-tabs-anchor").removeAttr("role").removeAttr("tabIndex").removeUniqueId(),this.tablist.unbind(this.eventNamespace),this.tabs.add(this.panels).each(function(){e.data(this,"ui-tabs-destroy")?e(this).remove():e(this).removeClass("ui-state-default ui-state-active ui-state-disabled ui-corner-top ui-corner-bottom ui-widget-content ui-tabs-active ui-tabs-panel").removeAttr("tabIndex").removeAttr("aria-live").removeAttr("aria-busy").removeAttr("aria-selected").removeAttr("aria-labelledby").removeAttr("aria-hidden").removeAttr("aria-expanded").removeAttr("role")}),this.tabs.each(function(){var t=e(this),i=t.data("ui-tabs-aria-controls");i?t.attr("aria-controls",i).removeData("ui-tabs-aria-controls"):t.removeAttr("aria-controls")}),this.panels.show(),"content"!==this.options.heightStyle&&this.panels.css("height","")},enable:function(t){var i=this.options.disabled;i!==!1&&(void 0===t?i=!1:(t=this._getIndex(t),i=e.isArray(i)?e.map(i,function(e){return e!==t?e:null}):e.map(this.tabs,function(e,i){return i!==t?i:null})),this._setupDisabled(i))},disable:function(t){var i=this.options.disabled;if(i!==!0){if(void 0===t)i=!0;else{if(t=this._getIndex(t),-1!==e.inArray(t,i))return;i=e.isArray(i)?e.merge([t],i).sort():[t]}this._setupDisabled(i)}},load:function(t,i){t=this._getIndex(t);var s=this,n=this.tabs.eq(t),a=n.find(".ui-tabs-anchor"),o=this._getPanelForTab(n),r={tab:n,panel:o},h=function(e,t){"abort"===t&&s.panels.stop(!1,!0),n.removeClass("ui-tabs-loading"),o.removeAttr("aria-busy"),e===s.xhr&&delete s.xhr};this._isLocal(a[0])||(this.xhr=e.ajax(this._ajaxSettings(a,i,r)),this.xhr&&"canceled"!==this.xhr.statusText&&(n.addClass("ui-tabs-loading"),o.attr("aria-busy","true"),this.xhr.done(function(e,t,n){setTimeout(function(){o.html(e),s._trigger("load",i,r),h(n,t)},1)}).fail(function(e,t){setTimeout(function(){h(e,t)},1)})))},_ajaxSettings:function(t,i,s){var n=this;return{url:t.attr("href"),beforeSend:function(t,a){return n._trigger("beforeLoad",i,e.extend({jqXHR:t,ajaxSettings:a},s))}}},_getPanelForTab:function(t){var i=e(t).attr("aria-controls");return this.element.find(this._sanitizeSelector("#"+i))}}),e.widget("ui.tooltip",{version:"1.11.4",options:{content:function(){var t=e(this).attr("title")||"";return e("<a>").text(t).html()},hide:!0,items:"[title]:not([disabled])",position:{my:"left top+15",at:"left bottom",collision:"flipfit flip"},show:!0,tooltipClass:null,track:!1,close:null,open:null},_addDescribedBy:function(t,i){var s=(t.attr("aria-describedby")||"").split(/\s+/);s.push(i),t.data("ui-tooltip-id",i).attr("aria-describedby",e.trim(s.join(" ")))},_removeDescribedBy:function(t){var i=t.data("ui-tooltip-id"),s=(t.attr("aria-describedby")||"").split(/\s+/),n=e.inArray(i,s);-1!==n&&s.splice(n,1),t.removeData("ui-tooltip-id"),s=e.trim(s.join(" ")),s?t.attr("aria-describedby",s):t.removeAttr("aria-describedby")},_create:function(){this._on({mouseover:"open",focusin:"open"}),this.tooltips={},this.parents={},this.options.disabled&&this._disable(),this.liveRegion=e("<div>").attr({role:"log","aria-live":"assertive","aria-relevant":"additions"}).addClass("ui-helper-hidden-accessible").appendTo(this.document[0].body)},_setOption:function(t,i){var s=this;return"disabled"===t?(this[i?"_disable":"_enable"](),this.options[t]=i,void 0):(this._super(t,i),"content"===t&&e.each(this.tooltips,function(e,t){s._updateContent(t.element)}),void 0)},_disable:function(){var t=this;e.each(this.tooltips,function(i,s){var n=e.Event("blur");n.target=n.currentTarget=s.element[0],t.close(n,!0)}),this.element.find(this.options.items).addBack().each(function(){var t=e(this);t.is("[title]")&&t.data("ui-tooltip-title",t.attr("title")).removeAttr("title")})},_enable:function(){this.element.find(this.options.items).addBack().each(function(){var t=e(this);t.data("ui-tooltip-title")&&t.attr("title",t.data("ui-tooltip-title"))})},open:function(t){var i=this,s=e(t?t.target:this.element).closest(this.options.items);s.length&&!s.data("ui-tooltip-id")&&(s.attr("title")&&s.data("ui-tooltip-title",s.attr("title")),s.data("ui-tooltip-open",!0),t&&"mouseover"===t.type&&s.parents().each(function(){var t,s=e(this);s.data("ui-tooltip-open")&&(t=e.Event("blur"),t.target=t.currentTarget=this,i.close(t,!0)),s.attr("title")&&(s.uniqueId(),i.parents[this.id]={element:this,title:s.attr("title")},s.attr("title",""))}),this._registerCloseHandlers(t,s),this._updateContent(s,t))},_updateContent:function(e,t){var i,s=this.options.content,n=this,a=t?t.type:null;return"string"==typeof s?this._open(t,e,s):(i=s.call(e[0],function(i){n._delay(function(){e.data("ui-tooltip-open")&&(t&&(t.type=a),this._open(t,e,i))})}),i&&this._open(t,e,i),void 0)},_open:function(t,i,s){function n(e){l.of=e,o.is(":hidden")||o.position(l)}var a,o,r,h,l=e.extend({},this.options.position);if(s){if(a=this._find(i))return a.tooltip.find(".ui-tooltip-content").html(s),void 0;i.is("[title]")&&(t&&"mouseover"===t.type?i.attr("title",""):i.removeAttr("title")),a=this._tooltip(i),o=a.tooltip,this._addDescribedBy(i,o.attr("id")),o.find(".ui-tooltip-content").html(s),this.liveRegion.children().hide(),s.clone?(h=s.clone(),h.removeAttr("id").find("[id]").removeAttr("id")):h=s,e("<div>").html(h).appendTo(this.liveRegion),this.options.track&&t&&/^mouse/.test(t.type)?(this._on(this.document,{mousemove:n}),n(t)):o.position(e.extend({of:i},this.options.position)),o.hide(),this._show(o,this.options.show),this.options.show&&this.options.show.delay&&(r=this.delayedShow=setInterval(function(){o.is(":visible")&&(n(l.of),clearInterval(r))},e.fx.interval)),this._trigger("open",t,{tooltip:o})}},_registerCloseHandlers:function(t,i){var s={keyup:function(t){if(t.keyCode===e.ui.keyCode.ESCAPE){var s=e.Event(t);s.currentTarget=i[0],this.close(s,!0)}}};i[0]!==this.element[0]&&(s.remove=function(){this._removeTooltip(this._find(i).tooltip)}),t&&"mouseover"!==t.type||(s.mouseleave="close"),t&&"focusin"!==t.type||(s.focusout="close"),this._on(!0,i,s)},close:function(t){var i,s=this,n=e(t?t.currentTarget:this.element),a=this._find(n);return a?(i=a.tooltip,a.closing||(clearInterval(this.delayedShow),n.data("ui-tooltip-title")&&!n.attr("title")&&n.attr("title",n.data("ui-tooltip-title")),this._removeDescribedBy(n),a.hiding=!0,i.stop(!0),this._hide(i,this.options.hide,function(){s._removeTooltip(e(this))}),n.removeData("ui-tooltip-open"),this._off(n,"mouseleave focusout keyup"),n[0]!==this.element[0]&&this._off(n,"remove"),this._off(this.document,"mousemove"),t&&"mouseleave"===t.type&&e.each(this.parents,function(t,i){e(i.element).attr("title",i.title),delete s.parents[t]}),a.closing=!0,this._trigger("close",t,{tooltip:i}),a.hiding||(a.closing=!1)),void 0):(n.removeData("ui-tooltip-open"),void 0)},_tooltip:function(t){var i=e("<div>").attr("role","tooltip").addClass("ui-tooltip ui-widget ui-corner-all ui-widget-content "+(this.options.tooltipClass||"")),s=i.uniqueId().attr("id");return e("<div>").addClass("ui-tooltip-content").appendTo(i),i.appendTo(this.document[0].body),this.tooltips[s]={element:t,tooltip:i}},_find:function(e){var t=e.data("ui-tooltip-id");return t?this.tooltips[t]:null},_removeTooltip:function(e){e.remove(),delete this.tooltips[e.attr("id")]},_destroy:function(){var t=this;e.each(this.tooltips,function(i,s){var n=e.Event("blur"),a=s.element;n.target=n.currentTarget=a[0],t.close(n,!0),e("#"+i).remove(),a.data("ui-tooltip-title")&&(a.attr("title")||a.attr("title",a.data("ui-tooltip-title")),a.removeData("ui-tooltip-title"))}),this.liveRegion.remove()}})});
/*!
 * Bootstrap v3.1.1 (http://getbootstrap.com)
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */
if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(a){"use strict";function b(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var c in b)if(void 0!==a.style[c])return{end:b[c]};return!1}a.fn.emulateTransitionEnd=function(b){var c=!1,d=this;a(this).one(a.support.transition.end,function(){c=!0});var e=function(){c||a(d).trigger(a.support.transition.end)};return setTimeout(e,b),this},a(function(){a.support.transition=b()})}(jQuery),+function(a){"use strict";var b='[data-dismiss="alert"]',c=function(c){a(c).on("click",b,this.close)};c.prototype.close=function(b){function c(){f.trigger("closed.bs.alert").remove()}var d=a(this),e=d.attr("data-target");e||(e=d.attr("href"),e=e&&e.replace(/.*(?=#[^\s]*$)/,""));var f=a(e);b&&b.preventDefault(),f.length||(f=d.hasClass("alert")?d:d.parent()),f.trigger(b=a.Event("close.bs.alert")),b.isDefaultPrevented()||(f.removeClass("in"),a.support.transition&&f.hasClass("fade")?f.one(a.support.transition.end,c).emulateTransitionEnd(150):c())};var d=a.fn.alert;a.fn.alert=function(b){return this.each(function(){var d=a(this),e=d.data("bs.alert");e||d.data("bs.alert",e=new c(this)),"string"==typeof b&&e[b].call(d)})},a.fn.alert.Constructor=c,a.fn.alert.noConflict=function(){return a.fn.alert=d,this},a(document).on("click.bs.alert.data-api",b,c.prototype.close)}(jQuery),+function(a){"use strict";var b=function(c,d){this.$element=a(c),this.options=a.extend({},b.DEFAULTS,d),this.isLoading=!1};b.DEFAULTS={loadingText:"loading..."},b.prototype.setState=function(b){var c="disabled",d=this.$element,e=d.is("input")?"val":"html",f=d.data();b+="Text",f.resetText||d.data("resetText",d[e]()),d[e](f[b]||this.options[b]),setTimeout(a.proxy(function(){"loadingText"==b?(this.isLoading=!0,d.addClass(c).attr(c,c)):this.isLoading&&(this.isLoading=!1,d.removeClass(c).removeAttr(c))},this),0)},b.prototype.toggle=function(){var a=!0,b=this.$element.closest('[data-toggle="buttons"]');if(b.length){var c=this.$element.find("input");"radio"==c.prop("type")&&(c.prop("checked")&&this.$element.hasClass("active")?a=!1:b.find(".active").removeClass("active")),a&&c.prop("checked",!this.$element.hasClass("active")).trigger("change")}a&&this.$element.toggleClass("active")};var c=a.fn.button;a.fn.button=function(c){return this.each(function(){var d=a(this),e=d.data("bs.button"),f="object"==typeof c&&c;e||d.data("bs.button",e=new b(this,f)),"toggle"==c?e.toggle():c&&e.setState(c)})},a.fn.button.Constructor=b,a.fn.button.noConflict=function(){return a.fn.button=c,this},a(document).on("click.bs.button.data-api","[data-toggle^=button]",function(b){var c=a(b.target);c.hasClass("btn")||(c=c.closest(".btn")),c.button("toggle"),b.preventDefault()})}(jQuery),+function(a){"use strict";var b=function(b,c){this.$element=a(b),this.$indicators=this.$element.find(".carousel-indicators"),this.options=c,this.paused=this.sliding=this.interval=this.$active=this.$items=null,"hover"==this.options.pause&&this.$element.on("mouseenter",a.proxy(this.pause,this)).on("mouseleave",a.proxy(this.cycle,this))};b.DEFAULTS={interval:5e3,pause:"hover",wrap:!0},b.prototype.cycle=function(b){return b||(this.paused=!1),this.interval&&clearInterval(this.interval),this.options.interval&&!this.paused&&(this.interval=setInterval(a.proxy(this.next,this),this.options.interval)),this},b.prototype.getActiveIndex=function(){return this.$active=this.$element.find(".item.active"),this.$items=this.$active.parent().children(),this.$items.index(this.$active)},b.prototype.to=function(b){var c=this,d=this.getActiveIndex();return b>this.$items.length-1||0>b?void 0:this.sliding?this.$element.one("slid.bs.carousel",function(){c.to(b)}):d==b?this.pause().cycle():this.slide(b>d?"next":"prev",a(this.$items[b]))},b.prototype.pause=function(b){return b||(this.paused=!0),this.$element.find(".next, .prev").length&&a.support.transition&&(this.$element.trigger(a.support.transition.end),this.cycle(!0)),this.interval=clearInterval(this.interval),this},b.prototype.next=function(){return this.sliding?void 0:this.slide("next")},b.prototype.prev=function(){return this.sliding?void 0:this.slide("prev")},b.prototype.slide=function(b,c){var d=this.$element.find(".item.active"),e=c||d[b](),f=this.interval,g="next"==b?"left":"right",h="next"==b?"first":"last",i=this;if(!e.length){if(!this.options.wrap)return;e=this.$element.find(".item")[h]()}if(e.hasClass("active"))return this.sliding=!1;var j=a.Event("slide.bs.carousel",{relatedTarget:e[0],direction:g});return this.$element.trigger(j),j.isDefaultPrevented()?void 0:(this.sliding=!0,f&&this.pause(),this.$indicators.length&&(this.$indicators.find(".active").removeClass("active"),this.$element.one("slid.bs.carousel",function(){var b=a(i.$indicators.children()[i.getActiveIndex()]);b&&b.addClass("active")})),a.support.transition&&this.$element.hasClass("slide")?(e.addClass(b),e[0].offsetWidth,d.addClass(g),e.addClass(g),d.one(a.support.transition.end,function(){e.removeClass([b,g].join(" ")).addClass("active"),d.removeClass(["active",g].join(" ")),i.sliding=!1,setTimeout(function(){i.$element.trigger("slid.bs.carousel")},0)}).emulateTransitionEnd(1e3*d.css("transition-duration").slice(0,-1))):(d.removeClass("active"),e.addClass("active"),this.sliding=!1,this.$element.trigger("slid.bs.carousel")),f&&this.cycle(),this)};var c=a.fn.carousel;a.fn.carousel=function(c){return this.each(function(){var d=a(this),e=d.data("bs.carousel"),f=a.extend({},b.DEFAULTS,d.data(),"object"==typeof c&&c),g="string"==typeof c?c:f.slide;e||d.data("bs.carousel",e=new b(this,f)),"number"==typeof c?e.to(c):g?e[g]():f.interval&&e.pause().cycle()})},a.fn.carousel.Constructor=b,a.fn.carousel.noConflict=function(){return a.fn.carousel=c,this},a(document).on("click.bs.carousel.data-api","[data-slide], [data-slide-to]",function(b){var c,d=a(this),e=a(d.attr("data-target")||(c=d.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,"")),f=a.extend({},e.data(),d.data()),g=d.attr("data-slide-to");g&&(f.interval=!1),e.carousel(f),(g=d.attr("data-slide-to"))&&e.data("bs.carousel").to(g),b.preventDefault()}),a(window).on("load",function(){a('[data-ride="carousel"]').each(function(){var b=a(this);b.carousel(b.data())})})}(jQuery),+function(a){"use strict";var b=function(c,d){this.$element=a(c),this.options=a.extend({},b.DEFAULTS,d),this.transitioning=null,this.options.parent&&(this.$parent=a(this.options.parent)),this.options.toggle&&this.toggle()};b.DEFAULTS={toggle:!0},b.prototype.dimension=function(){var a=this.$element.hasClass("width");return a?"width":"height"},b.prototype.show=function(){if(!this.transitioning&&!this.$element.hasClass("in")){var b=a.Event("show.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.$parent&&this.$parent.find("> .panel > .in");if(c&&c.length){var d=c.data("bs.collapse");if(d&&d.transitioning)return;c.collapse("hide"),d||c.data("bs.collapse",null)}var e=this.dimension();this.$element.removeClass("collapse").addClass("collapsing")[e](0),this.transitioning=1;var f=function(){this.$element.removeClass("collapsing").addClass("collapse in")[e]("auto"),this.transitioning=0,this.$element.trigger("shown.bs.collapse")};if(!a.support.transition)return f.call(this);var g=a.camelCase(["scroll",e].join("-"));this.$element.one(a.support.transition.end,a.proxy(f,this)).emulateTransitionEnd(350)[e](this.$element[0][g])}}},b.prototype.hide=function(){if(!this.transitioning&&this.$element.hasClass("in")){var b=a.Event("hide.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.dimension();this.$element[c](this.$element[c]())[0].offsetHeight,this.$element.addClass("collapsing").removeClass("collapse").removeClass("in"),this.transitioning=1;var d=function(){this.transitioning=0,this.$element.trigger("hidden.bs.collapse").removeClass("collapsing").addClass("collapse")};return a.support.transition?void this.$element[c](0).one(a.support.transition.end,a.proxy(d,this)).emulateTransitionEnd(350):d.call(this)}}},b.prototype.toggle=function(){this[this.$element.hasClass("in")?"hide":"show"]()};var c=a.fn.collapse;a.fn.collapse=function(c){return this.each(function(){var d=a(this),e=d.data("bs.collapse"),f=a.extend({},b.DEFAULTS,d.data(),"object"==typeof c&&c);!e&&f.toggle&&"show"==c&&(c=!c),e||d.data("bs.collapse",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.collapse.Constructor=b,a.fn.collapse.noConflict=function(){return a.fn.collapse=c,this},a(document).on("click.bs.collapse.data-api","[data-toggle=collapse]",function(b){var c,d=a(this),e=d.attr("data-target")||b.preventDefault()||(c=d.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,""),f=a(e),g=f.data("bs.collapse"),h=g?"toggle":d.data(),i=d.attr("data-parent"),j=i&&a(i);g&&g.transitioning||(j&&j.find('[data-toggle=collapse][data-parent="'+i+'"]').not(d).addClass("collapsed"),d[f.hasClass("in")?"addClass":"removeClass"]("collapsed")),f.collapse(h)})}(jQuery),+function(a){"use strict";function b(b){a(d).remove(),a(e).each(function(){var d=c(a(this)),e={relatedTarget:this};d.hasClass("open")&&(d.trigger(b=a.Event("hide.bs.dropdown",e)),b.isDefaultPrevented()||d.removeClass("open").trigger("hidden.bs.dropdown",e))})}function c(b){var c=b.attr("data-target");c||(c=b.attr("href"),c=c&&/#[A-Za-z]/.test(c)&&c.replace(/.*(?=#[^\s]*$)/,""));var d=c&&a(c);return d&&d.length?d:b.parent()}var d=".dropdown-backdrop",e="[data-toggle=dropdown]",f=function(b){a(b).on("click.bs.dropdown",this.toggle)};f.prototype.toggle=function(d){var e=a(this);if(!e.is(".disabled, :disabled")){var f=c(e),g=f.hasClass("open");if(b(),!g){"ontouchstart"in document.documentElement&&!f.closest(".navbar-nav").length&&a('<div class="dropdown-backdrop"/>').insertAfter(a(this)).on("click",b);var h={relatedTarget:this};if(f.trigger(d=a.Event("show.bs.dropdown",h)),d.isDefaultPrevented())return;f.toggleClass("open").trigger("shown.bs.dropdown",h),e.focus()}return!1}},f.prototype.keydown=function(b){if(/(38|40|27)/.test(b.keyCode)){var d=a(this);if(b.preventDefault(),b.stopPropagation(),!d.is(".disabled, :disabled")){var f=c(d),g=f.hasClass("open");if(!g||g&&27==b.keyCode)return 27==b.which&&f.find(e).focus(),d.click();var h=" li:not(.divider):visible a",i=f.find("[role=menu]"+h+", [role=listbox]"+h);if(i.length){var j=i.index(i.filter(":focus"));38==b.keyCode&&j>0&&j--,40==b.keyCode&&j<i.length-1&&j++,~j||(j=0),i.eq(j).focus()}}}};var g=a.fn.dropdown;a.fn.dropdown=function(b){return this.each(function(){var c=a(this),d=c.data("bs.dropdown");d||c.data("bs.dropdown",d=new f(this)),"string"==typeof b&&d[b].call(c)})},a.fn.dropdown.Constructor=f,a.fn.dropdown.noConflict=function(){return a.fn.dropdown=g,this},a(document).on("click.bs.dropdown.data-api",b).on("click.bs.dropdown.data-api",".dropdown form",function(a){a.stopPropagation()}).on("click.bs.dropdown.data-api",e,f.prototype.toggle).on("keydown.bs.dropdown.data-api",e+", [role=menu], [role=listbox]",f.prototype.keydown)}(jQuery),+function(a){"use strict";var b=function(b,c){this.options=c,this.$element=a(b),this.$backdrop=this.isShown=null,this.options.remote&&this.$element.find(".modal-content").load(this.options.remote,a.proxy(function(){this.$element.trigger("loaded.bs.modal")},this))};b.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},b.prototype.toggle=function(a){return this[this.isShown?"hide":"show"](a)},b.prototype.show=function(b){var c=this,d=a.Event("show.bs.modal",{relatedTarget:b});this.$element.trigger(d),this.isShown||d.isDefaultPrevented()||(this.isShown=!0,this.escape(),this.$element.on("click.dismiss.bs.modal",'[data-dismiss="modal"]',a.proxy(this.hide,this)),this.backdrop(function(){var d=a.support.transition&&c.$element.hasClass("fade");c.$element.parent().length||c.$element.appendTo(document.body),c.$element.show().scrollTop(0),d&&c.$element[0].offsetWidth,c.$element.addClass("in").attr("aria-hidden",!1),c.enforceFocus();var e=a.Event("shown.bs.modal",{relatedTarget:b});d?c.$element.find(".modal-dialog").one(a.support.transition.end,function(){c.$element.focus().trigger(e)}).emulateTransitionEnd(300):c.$element.focus().trigger(e)}))},b.prototype.hide=function(b){b&&b.preventDefault(),b=a.Event("hide.bs.modal"),this.$element.trigger(b),this.isShown&&!b.isDefaultPrevented()&&(this.isShown=!1,this.escape(),a(document).off("focusin.bs.modal"),this.$element.removeClass("in").attr("aria-hidden",!0).off("click.dismiss.bs.modal"),a.support.transition&&this.$element.hasClass("fade")?this.$element.one(a.support.transition.end,a.proxy(this.hideModal,this)).emulateTransitionEnd(300):this.hideModal())},b.prototype.enforceFocus=function(){a(document).off("focusin.bs.modal").on("focusin.bs.modal",a.proxy(function(a){this.$element[0]===a.target||this.$element.has(a.target).length||this.$element.focus()},this))},b.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keyup.dismiss.bs.modal",a.proxy(function(a){27==a.which&&this.hide()},this)):this.isShown||this.$element.off("keyup.dismiss.bs.modal")},b.prototype.hideModal=function(){var a=this;this.$element.hide(),this.backdrop(function(){a.removeBackdrop(),a.$element.trigger("hidden.bs.modal")})},b.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},b.prototype.backdrop=function(b){var c=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var d=a.support.transition&&c;if(this.$backdrop=a('<div class="modal-backdrop '+c+'" />').appendTo(document.body),this.$element.on("click.dismiss.bs.modal",a.proxy(function(a){a.target===a.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus.call(this.$element[0]):this.hide.call(this))},this)),d&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!b)return;d?this.$backdrop.one(a.support.transition.end,b).emulateTransitionEnd(150):b()}else!this.isShown&&this.$backdrop?(this.$backdrop.removeClass("in"),a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one(a.support.transition.end,b).emulateTransitionEnd(150):b()):b&&b()};var c=a.fn.modal;a.fn.modal=function(c,d){return this.each(function(){var e=a(this),f=e.data("bs.modal"),g=a.extend({},b.DEFAULTS,e.data(),"object"==typeof c&&c);f||e.data("bs.modal",f=new b(this,g)),"string"==typeof c?f[c](d):g.show&&f.show(d)})},a.fn.modal.Constructor=b,a.fn.modal.noConflict=function(){return a.fn.modal=c,this},a(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(b){var c=a(this),d=c.attr("href"),e=a(c.attr("data-target")||d&&d.replace(/.*(?=#[^\s]+$)/,"")),f=e.data("bs.modal")?"toggle":a.extend({remote:!/#/.test(d)&&d},e.data(),c.data());c.is("a")&&b.preventDefault(),e.modal(f,this).one("hide",function(){c.is(":visible")&&c.focus()})}),a(document).on("show.bs.modal",".modal",function(){a(document.body).addClass("modal-open")}).on("hidden.bs.modal",".modal",function(){a(document.body).removeClass("modal-open")})}(jQuery),+function(a){"use strict";var b=function(a,b){this.type=this.options=this.enabled=this.timeout=this.hoverState=this.$element=null,this.init("tooltip",a,b)};b.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1},b.prototype.init=function(b,c,d){this.enabled=!0,this.type=b,this.$element=a(c),this.options=this.getOptions(d);for(var e=this.options.trigger.split(" "),f=e.length;f--;){var g=e[f];if("click"==g)this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this));else if("manual"!=g){var h="hover"==g?"mouseenter":"focusin",i="hover"==g?"mouseleave":"focusout";this.$element.on(h+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(i+"."+this.type,this.options.selector,a.proxy(this.leave,this))}}this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},b.prototype.getDefaults=function(){return b.DEFAULTS},b.prototype.getOptions=function(b){return b=a.extend({},this.getDefaults(),this.$element.data(),b),b.delay&&"number"==typeof b.delay&&(b.delay={show:b.delay,hide:b.delay}),b},b.prototype.getDelegateOptions=function(){var b={},c=this.getDefaults();return this._options&&a.each(this._options,function(a,d){c[a]!=d&&(b[a]=d)}),b},b.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type);return clearTimeout(c.timeout),c.hoverState="in",c.options.delay&&c.options.delay.show?void(c.timeout=setTimeout(function(){"in"==c.hoverState&&c.show()},c.options.delay.show)):c.show()},b.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type);return clearTimeout(c.timeout),c.hoverState="out",c.options.delay&&c.options.delay.hide?void(c.timeout=setTimeout(function(){"out"==c.hoverState&&c.hide()},c.options.delay.hide)):c.hide()},b.prototype.show=function(){var b=a.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){if(this.$element.trigger(b),b.isDefaultPrevented())return;var c=this,d=this.tip();this.setContent(),this.options.animation&&d.addClass("fade");var e="function"==typeof this.options.placement?this.options.placement.call(this,d[0],this.$element[0]):this.options.placement,f=/\s?auto?\s?/i,g=f.test(e);g&&(e=e.replace(f,"")||"top"),d.detach().css({top:0,left:0,display:"block"}).addClass(e),this.options.container?d.appendTo(this.options.container):d.insertAfter(this.$element);var h=this.getPosition(),i=d[0].offsetWidth,j=d[0].offsetHeight;if(g){var k=this.$element.parent(),l=e,m=document.documentElement.scrollTop||document.body.scrollTop,n="body"==this.options.container?window.innerWidth:k.outerWidth(),o="body"==this.options.container?window.innerHeight:k.outerHeight(),p="body"==this.options.container?0:k.offset().left;e="bottom"==e&&h.top+h.height+j-m>o?"top":"top"==e&&h.top-m-j<0?"bottom":"right"==e&&h.right+i>n?"left":"left"==e&&h.left-i<p?"right":e,d.removeClass(l).addClass(e)}var q=this.getCalculatedOffset(e,h,i,j);this.applyPlacement(q,e),this.hoverState=null;var r=function(){c.$element.trigger("shown.bs."+c.type)};a.support.transition&&this.$tip.hasClass("fade")?d.one(a.support.transition.end,r).emulateTransitionEnd(150):r()}},b.prototype.applyPlacement=function(b,c){var d,e=this.tip(),f=e[0].offsetWidth,g=e[0].offsetHeight,h=parseInt(e.css("margin-top"),10),i=parseInt(e.css("margin-left"),10);isNaN(h)&&(h=0),isNaN(i)&&(i=0),b.top=b.top+h,b.left=b.left+i,a.offset.setOffset(e[0],a.extend({using:function(a){e.css({top:Math.round(a.top),left:Math.round(a.left)})}},b),0),e.addClass("in");var j=e[0].offsetWidth,k=e[0].offsetHeight;if("top"==c&&k!=g&&(d=!0,b.top=b.top+g-k),/bottom|top/.test(c)){var l=0;b.left<0&&(l=-2*b.left,b.left=0,e.offset(b),j=e[0].offsetWidth,k=e[0].offsetHeight),this.replaceArrow(l-f+j,j,"left")}else this.replaceArrow(k-g,k,"top");d&&e.offset(b)},b.prototype.replaceArrow=function(a,b,c){this.arrow().css(c,a?50*(1-a/b)+"%":"")},b.prototype.setContent=function(){var a=this.tip(),b=this.getTitle();a.find(".tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},b.prototype.hide=function(){function b(){"in"!=c.hoverState&&d.detach(),c.$element.trigger("hidden.bs."+c.type)}var c=this,d=this.tip(),e=a.Event("hide.bs."+this.type);return this.$element.trigger(e),e.isDefaultPrevented()?void 0:(d.removeClass("in"),a.support.transition&&this.$tip.hasClass("fade")?d.one(a.support.transition.end,b).emulateTransitionEnd(150):b(),this.hoverState=null,this)},b.prototype.fixTitle=function(){var a=this.$element;(a.attr("title")||"string"!=typeof a.attr("data-original-title"))&&a.attr("data-original-title",a.attr("title")||"").attr("title","")},b.prototype.hasContent=function(){return this.getTitle()},b.prototype.getPosition=function(){var b=this.$element[0];return a.extend({},"function"==typeof b.getBoundingClientRect?b.getBoundingClientRect():{width:b.offsetWidth,height:b.offsetHeight},this.$element.offset())},b.prototype.getCalculatedOffset=function(a,b,c,d){return"bottom"==a?{top:b.top+b.height,left:b.left+b.width/2-c/2}:"top"==a?{top:b.top-d,left:b.left+b.width/2-c/2}:"left"==a?{top:b.top+b.height/2-d/2,left:b.left-c}:{top:b.top+b.height/2-d/2,left:b.left+b.width}},b.prototype.getTitle=function(){var a,b=this.$element,c=this.options;return a=b.attr("data-original-title")||("function"==typeof c.title?c.title.call(b[0]):c.title)},b.prototype.tip=function(){return this.$tip=this.$tip||a(this.options.template)},b.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},b.prototype.validate=function(){this.$element[0].parentNode||(this.hide(),this.$element=null,this.options=null)},b.prototype.enable=function(){this.enabled=!0},b.prototype.disable=function(){this.enabled=!1},b.prototype.toggleEnabled=function(){this.enabled=!this.enabled},b.prototype.toggle=function(b){var c=b?a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs."+this.type):this;c.tip().hasClass("in")?c.leave(c):c.enter(c)},b.prototype.destroy=function(){clearTimeout(this.timeout),this.hide().$element.off("."+this.type).removeData("bs."+this.type)};var c=a.fn.tooltip;a.fn.tooltip=function(c){return this.each(function(){var d=a(this),e=d.data("bs.tooltip"),f="object"==typeof c&&c;(e||"destroy"!=c)&&(e||d.data("bs.tooltip",e=new b(this,f)),"string"==typeof c&&e[c]())})},a.fn.tooltip.Constructor=b,a.fn.tooltip.noConflict=function(){return a.fn.tooltip=c,this}}(jQuery),+function(a){"use strict";var b=function(a,b){this.init("popover",a,b)};if(!a.fn.tooltip)throw new Error("Popover requires tooltip.js");b.DEFAULTS=a.extend({},a.fn.tooltip.Constructor.DEFAULTS,{placement:"right",trigger:"click",content:"",template:'<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'}),b.prototype=a.extend({},a.fn.tooltip.Constructor.prototype),b.prototype.constructor=b,b.prototype.getDefaults=function(){return b.DEFAULTS},b.prototype.setContent=function(){var a=this.tip(),b=this.getTitle(),c=this.getContent();a.find(".popover-title")[this.options.html?"html":"text"](b),a.find(".popover-content")[this.options.html?"string"==typeof c?"html":"append":"text"](c),a.removeClass("fade top bottom left right in"),a.find(".popover-title").html()||a.find(".popover-title").hide()},b.prototype.hasContent=function(){return this.getTitle()||this.getContent()},b.prototype.getContent=function(){var a=this.$element,b=this.options;return a.attr("data-content")||("function"==typeof b.content?b.content.call(a[0]):b.content)},b.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".arrow")},b.prototype.tip=function(){return this.$tip||(this.$tip=a(this.options.template)),this.$tip};var c=a.fn.popover;a.fn.popover=function(c){return this.each(function(){var d=a(this),e=d.data("bs.popover"),f="object"==typeof c&&c;(e||"destroy"!=c)&&(e||d.data("bs.popover",e=new b(this,f)),"string"==typeof c&&e[c]())})},a.fn.popover.Constructor=b,a.fn.popover.noConflict=function(){return a.fn.popover=c,this}}(jQuery),+function(a){"use strict";function b(c,d){var e,f=a.proxy(this.process,this);this.$element=a(a(c).is("body")?window:c),this.$body=a("body"),this.$scrollElement=this.$element.on("scroll.bs.scroll-spy.data-api",f),this.options=a.extend({},b.DEFAULTS,d),this.selector=(this.options.target||(e=a(c).attr("href"))&&e.replace(/.*(?=#[^\s]+$)/,"")||"")+" .nav li > a",this.offsets=a([]),this.targets=a([]),this.activeTarget=null,this.refresh(),this.process()}b.DEFAULTS={offset:10},b.prototype.refresh=function(){var b=this.$element[0]==window?"offset":"position";this.offsets=a([]),this.targets=a([]);{var c=this;this.$body.find(this.selector).map(function(){var d=a(this),e=d.data("target")||d.attr("href"),f=/^#./.test(e)&&a(e);return f&&f.length&&f.is(":visible")&&[[f[b]().top+(!a.isWindow(c.$scrollElement.get(0))&&c.$scrollElement.scrollTop()),e]]||null}).sort(function(a,b){return a[0]-b[0]}).each(function(){c.offsets.push(this[0]),c.targets.push(this[1])})}},b.prototype.process=function(){var a,b=this.$scrollElement.scrollTop()+this.options.offset,c=this.$scrollElement[0].scrollHeight||this.$body[0].scrollHeight,d=c-this.$scrollElement.height(),e=this.offsets,f=this.targets,g=this.activeTarget;if(b>=d)return g!=(a=f.last()[0])&&this.activate(a);if(g&&b<=e[0])return g!=(a=f[0])&&this.activate(a);for(a=e.length;a--;)g!=f[a]&&b>=e[a]&&(!e[a+1]||b<=e[a+1])&&this.activate(f[a])},b.prototype.activate=function(b){this.activeTarget=b,a(this.selector).parentsUntil(this.options.target,".active").removeClass("active");var c=this.selector+'[data-target="'+b+'"],'+this.selector+'[href="'+b+'"]',d=a(c).parents("li").addClass("active");d.parent(".dropdown-menu").length&&(d=d.closest("li.dropdown").addClass("active")),d.trigger("activate.bs.scrollspy")};var c=a.fn.scrollspy;a.fn.scrollspy=function(c){return this.each(function(){var d=a(this),e=d.data("bs.scrollspy"),f="object"==typeof c&&c;e||d.data("bs.scrollspy",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.scrollspy.Constructor=b,a.fn.scrollspy.noConflict=function(){return a.fn.scrollspy=c,this},a(window).on("load",function(){a('[data-spy="scroll"]').each(function(){var b=a(this);b.scrollspy(b.data())})})}(jQuery),+function(a){"use strict";var b=function(b){this.element=a(b)};b.prototype.show=function(){var b=this.element,c=b.closest("ul:not(.dropdown-menu)"),d=b.data("target");if(d||(d=b.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,"")),!b.parent("li").hasClass("active")){var e=c.find(".active:last a")[0],f=a.Event("show.bs.tab",{relatedTarget:e});if(b.trigger(f),!f.isDefaultPrevented()){var g=a(d);this.activate(b.parent("li"),c),this.activate(g,g.parent(),function(){b.trigger({type:"shown.bs.tab",relatedTarget:e})})}}},b.prototype.activate=function(b,c,d){function e(){f.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"),b.addClass("active"),g?(b[0].offsetWidth,b.addClass("in")):b.removeClass("fade"),b.parent(".dropdown-menu")&&b.closest("li.dropdown").addClass("active"),d&&d()}var f=c.find("> .active"),g=d&&a.support.transition&&f.hasClass("fade");g?f.one(a.support.transition.end,e).emulateTransitionEnd(150):e(),f.removeClass("in")};var c=a.fn.tab;a.fn.tab=function(c){return this.each(function(){var d=a(this),e=d.data("bs.tab");e||d.data("bs.tab",e=new b(this)),"string"==typeof c&&e[c]()})},a.fn.tab.Constructor=b,a.fn.tab.noConflict=function(){return a.fn.tab=c,this},a(document).on("click.bs.tab.data-api",'[data-toggle="tab"], [data-toggle="pill"]',function(b){b.preventDefault(),a(this).tab("show")})}(jQuery),+function(a){"use strict";var b=function(c,d){this.options=a.extend({},b.DEFAULTS,d),this.$window=a(window).on("scroll.bs.affix.data-api",a.proxy(this.checkPosition,this)).on("click.bs.affix.data-api",a.proxy(this.checkPositionWithEventLoop,this)),this.$element=a(c),this.affixed=this.unpin=this.pinnedOffset=null,this.checkPosition()};b.RESET="affix affix-top affix-bottom",b.DEFAULTS={offset:0},b.prototype.getPinnedOffset=function(){if(this.pinnedOffset)return this.pinnedOffset;this.$element.removeClass(b.RESET).addClass("affix");var a=this.$window.scrollTop(),c=this.$element.offset();return this.pinnedOffset=c.top-a},b.prototype.checkPositionWithEventLoop=function(){setTimeout(a.proxy(this.checkPosition,this),1)},b.prototype.checkPosition=function(){if(this.$element.is(":visible")){var c=a(document).height(),d=this.$window.scrollTop(),e=this.$element.offset(),f=this.options.offset,g=f.top,h=f.bottom;"top"==this.affixed&&(e.top+=d),"object"!=typeof f&&(h=g=f),"function"==typeof g&&(g=f.top(this.$element)),"function"==typeof h&&(h=f.bottom(this.$element));var i=null!=this.unpin&&d+this.unpin<=e.top?!1:null!=h&&e.top+this.$element.height()>=c-h?"bottom":null!=g&&g>=d?"top":!1;if(this.affixed!==i){this.unpin&&this.$element.css("top","");var j="affix"+(i?"-"+i:""),k=a.Event(j+".bs.affix");this.$element.trigger(k),k.isDefaultPrevented()||(this.affixed=i,this.unpin="bottom"==i?this.getPinnedOffset():null,this.$element.removeClass(b.RESET).addClass(j).trigger(a.Event(j.replace("affix","affixed"))),"bottom"==i&&this.$element.offset({top:c-h-this.$element.height()}))}}};var c=a.fn.affix;a.fn.affix=function(c){return this.each(function(){var d=a(this),e=d.data("bs.affix"),f="object"==typeof c&&c;e||d.data("bs.affix",e=new b(this,f)),"string"==typeof c&&e[c]()})},a.fn.affix.Constructor=b,a.fn.affix.noConflict=function(){return a.fn.affix=c,this},a(window).on("load",function(){a('[data-spy="affix"]').each(function(){var b=a(this),c=b.data();c.offset=c.offset||{},c.offsetBottom&&(c.offset.bottom=c.offsetBottom),c.offsetTop&&(c.offset.top=c.offsetTop),b.affix(c)})})}(jQuery);
/* ============================================================
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Open source under the BSD License.
 *
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * https://raw.github.com/danro/jquery-easing/master/LICENSE
 * ======================================================== */jQuery.easing.jswing=jQuery.easing.swing,jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(a,b,c,d,e){return jQuery.easing[jQuery.easing.def](a,b,c,d,e)},easeInQuad:function(a,b,c,d,e){return d*(b/=e)*b+c},easeOutQuad:function(a,b,c,d,e){return-d*(b/=e)*(b-2)+c},easeInOutQuad:function(a,b,c,d,e){return(b/=e/2)<1?d/2*b*b+c:-d/2*(--b*(b-2)-1)+c},easeInCubic:function(a,b,c,d,e){return d*(b/=e)*b*b+c},easeOutCubic:function(a,b,c,d,e){return d*((b=b/e-1)*b*b+1)+c},easeInOutCubic:function(a,b,c,d,e){return(b/=e/2)<1?d/2*b*b*b+c:d/2*((b-=2)*b*b+2)+c},easeInQuart:function(a,b,c,d,e){return d*(b/=e)*b*b*b+c},easeOutQuart:function(a,b,c,d,e){return-d*((b=b/e-1)*b*b*b-1)+c},easeInOutQuart:function(a,b,c,d,e){return(b/=e/2)<1?d/2*b*b*b*b+c:-d/2*((b-=2)*b*b*b-2)+c},easeInQuint:function(a,b,c,d,e){return d*(b/=e)*b*b*b*b+c},easeOutQuint:function(a,b,c,d,e){return d*((b=b/e-1)*b*b*b*b+1)+c},easeInOutQuint:function(a,b,c,d,e){return(b/=e/2)<1?d/2*b*b*b*b*b+c:d/2*((b-=2)*b*b*b*b+2)+c},easeInSine:function(a,b,c,d,e){return-d*Math.cos(b/e*(Math.PI/2))+d+c},easeOutSine:function(a,b,c,d,e){return d*Math.sin(b/e*(Math.PI/2))+c},easeInOutSine:function(a,b,c,d,e){return-d/2*(Math.cos(Math.PI*b/e)-1)+c},easeInExpo:function(a,b,c,d,e){return b==0?c:d*Math.pow(2,10*(b/e-1))+c},easeOutExpo:function(a,b,c,d,e){return b==e?c+d:d*(-Math.pow(2,-10*b/e)+1)+c},easeInOutExpo:function(a,b,c,d,e){return b==0?c:b==e?c+d:(b/=e/2)<1?d/2*Math.pow(2,10*(b-1))+c:d/2*(-Math.pow(2,-10*--b)+2)+c},easeInCirc:function(a,b,c,d,e){return-d*(Math.sqrt(1-(b/=e)*b)-1)+c},easeOutCirc:function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c},easeInOutCirc:function(a,b,c,d,e){return(b/=e/2)<1?-d/2*(Math.sqrt(1-b*b)-1)+c:d/2*(Math.sqrt(1-(b-=2)*b)+1)+c},easeInElastic:function(a,b,c,d,e){var f=1.70158,g=0,h=d;if(b==0)return c;if((b/=e)==1)return c+d;g||(g=e*.3);if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return-(h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g))+c},easeOutElastic:function(a,b,c,d,e){var f=1.70158,g=0,h=d;if(b==0)return c;if((b/=e)==1)return c+d;g||(g=e*.3);if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return h*Math.pow(2,-10*b)*Math.sin((b*e-f)*2*Math.PI/g)+d+c},easeInOutElastic:function(a,b,c,d,e){var f=1.70158,g=0,h=d;if(b==0)return c;if((b/=e/2)==2)return c+d;g||(g=e*.3*1.5);if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return b<1?-0.5*h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)+c:h*Math.pow(2,-10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)*.5+d+c},easeInBack:function(a,b,c,d,e,f){return f==undefined&&(f=1.70158),d*(b/=e)*b*((f+1)*b-f)+c},easeOutBack:function(a,b,c,d,e,f){return f==undefined&&(f=1.70158),d*((b=b/e-1)*b*((f+1)*b+f)+1)+c},easeInOutBack:function(a,b,c,d,e,f){return f==undefined&&(f=1.70158),(b/=e/2)<1?d/2*b*b*(((f*=1.525)+1)*b-f)+c:d/2*((b-=2)*b*(((f*=1.525)+1)*b+f)+2)+c},easeInBounce:function(a,b,c,d,e){return d-jQuery.easing.easeOutBounce(a,e-b,0,d,e)+c},easeOutBounce:function(a,b,c,d,e){return(b/=e)<1/2.75?d*7.5625*b*b+c:b<2/2.75?d*(7.5625*(b-=1.5/2.75)*b+.75)+c:b<2.5/2.75?d*(7.5625*(b-=2.25/2.75)*b+.9375)+c:d*(7.5625*(b-=2.625/2.75)*b+.984375)+c},easeInOutBounce:function(a,b,c,d,e){return b<e/2?jQuery.easing.easeInBounce(a,b*2,0,d,e)*.5+c:jQuery.easing.easeOutBounce(a,b*2-e,0,d,e)*.5+d*.5+c}});
/*!

Holder - 2.3.1 - client side image placeholders
(c) 2012-2014 Ivan Malopinsky / http://imsky.co

Provided under the MIT License.
Commercial use requires attribution.

*/
var Holder = Holder || {};
(function (app, win) {
var system_config = {
	use_svg: false,
	use_canvas: false,
	use_fallback: false
};
var instance_config = {};
var preempted = false;
canvas = document.createElement('canvas');
var dpr = 1, bsr = 1;
var resizable_images = [];

if (!canvas.getContext) {
	system_config.use_fallback = true;
} else {
	if (canvas.toDataURL("image/png")
		.indexOf("data:image/png") < 0) {
		//Android doesn't support data URI
		system_config.use_fallback = true;
	} else {
		var ctx = canvas.getContext("2d");
	}
}

if(!!document.createElementNS && !!document.createElementNS('http://www.w3.org/2000/svg', 'svg').createSVGRect){
	system_config.use_svg = true;
	system_config.use_canvas = false;
}

if(!system_config.use_fallback){
    dpr = window.devicePixelRatio || 1,
    bsr = ctx.webkitBackingStorePixelRatio || ctx.mozBackingStorePixelRatio || ctx.msBackingStorePixelRatio || ctx.oBackingStorePixelRatio || ctx.backingStorePixelRatio || 1;
}

var ratio = dpr / bsr;

var settings = {
	domain: "holder.js",
	images: "img",
	bgnodes: ".holderjs",
	themes: {
		"gray": {
			background: "#eee",
			foreground: "#aaa",
			size: 12
		},
		"social": {
			background: "#3a5a97",
			foreground: "#fff",
			size: 12
		},
		"industrial": {
			background: "#434A52",
			foreground: "#C2F200",
			size: 12
		},
		"sky": {
			background: "#0D8FDB",
			foreground: "#fff",
			size: 12
		},
		"vine": {
			background: "#39DBAC",
			foreground: "#1E292C",
			size: 12
		},
		"lava": {
			background: "#F8591A",
			foreground: "#1C2846",
			size: 12
		}
	},
	stylesheet: ""
};
app.flags = {
	dimensions: {
		regex: /^(\d+)x(\d+)$/,
		output: function (val) {
			var exec = this.regex.exec(val);
			return {
				width: +exec[1],
				height: +exec[2]
			}
		}
	},
	fluid: {
		regex: /^([0-9%]+)x([0-9%]+)$/,
		output: function (val) {
			var exec = this.regex.exec(val);
			return {
				width: exec[1],
				height: exec[2]
			}
		}
	},
	colors: {
		regex: /#([0-9a-f]{3,})\:#([0-9a-f]{3,})/i,
		output: function (val) {
			var exec = this.regex.exec(val);
			return {
				size: settings.themes.gray.size,
				foreground: "#" + exec[2],
				background: "#" + exec[1]
			}
		}
	},
	text: {
		regex: /text\:(.*)/,
		output: function (val) {
			return this.regex.exec(val)[1];
		}
	},
	font: {
		regex: /font\:(.*)/,
		output: function (val) {
			return this.regex.exec(val)[1];
		}
	},
	auto: {
		regex: /^auto$/
	},
	textmode: {
		regex: /textmode\:(.*)/,
		output: function(val){
			return this.regex.exec(val)[1];
		}
	}
}

function text_size(width, height, template) {
	height = parseInt(height, 10);
	width = parseInt(width, 10);
	var bigSide = Math.max(height, width)
	var smallSide = Math.min(height, width)
	var scale = 1 / 12;
	var newHeight = Math.min(smallSide * 0.75, 0.75 * bigSide * scale);
	return {
		height: Math.round(Math.max(template.size, newHeight))
	}
}

var svg_el = (function(){
	//Prevent IE <9 from initializing SVG renderer
	if(!window.XMLSerializer) return;
	var serializer = new XMLSerializer();
	var svg_ns = "http://www.w3.org/2000/svg"
	var svg = document.createElementNS(svg_ns, "svg");
	//IE throws an exception if this is set and Chrome requires it to be set
	if(svg.webkitMatchesSelector){
		svg.setAttribute("xmlns", "http://www.w3.org/2000/svg")
	}
	var bg_el = document.createElementNS(svg_ns, "rect")
	var text_el = document.createElementNS(svg_ns, "text")
	var textnode_el = document.createTextNode(null)
	text_el.setAttribute("text-anchor", "middle")
	text_el.appendChild(textnode_el)
	svg.appendChild(bg_el)
	svg.appendChild(text_el)

	return function(props){
		svg.setAttribute("width",props.width);
		svg.setAttribute("height", props.height);
		bg_el.setAttribute("width", props.width);
		bg_el.setAttribute("height", props.height);
		bg_el.setAttribute("fill", props.template.background);
		text_el.setAttribute("x", props.width/2)
		text_el.setAttribute("y", props.height/2)
		textnode_el.nodeValue=props.text
		text_el.setAttribute("style", css_properties({
		"fill": props.template.foreground,
		"font-weight": "bold",
		"font-size": props.text_height+"px",
		"font-family":props.font,
		"dominant-baseline":"central"
		}))
		return serializer.serializeToString(svg)
	}
})()

function css_properties(props){
	var ret = [];
	for(p in props){
		if(props.hasOwnProperty(p)){
			ret.push(p+":"+props[p])
		}
	}
	return ret.join(";")
}

function draw_canvas(args) {
	var ctx = args.ctx,
		dimensions = args.dimensions,
		template = args.template,
		ratio = args.ratio,
		holder = args.holder,
		literal = holder.textmode == "literal",
		exact = holder.textmode == "exact";

	var ts = text_size(dimensions.width, dimensions.height, template);
	var text_height = ts.height;
	var width = dimensions.width * ratio,
		height = dimensions.height * ratio;
	var font = template.font ? template.font : "Arial,Helvetica,sans-serif";
	canvas.width = width;
	canvas.height = height;
	ctx.textAlign = "center";
	ctx.textBaseline = "middle";
	ctx.fillStyle = template.background;
	ctx.fillRect(0, 0, width, height);
	ctx.fillStyle = template.foreground;
	ctx.font = "bold " + text_height + "px " + font;
	var text = template.text ? template.text : (Math.floor(dimensions.width) + "x" + Math.floor(dimensions.height));
	if (literal) {
		var dimensions = holder.dimensions;
		text = dimensions.width + "x" + dimensions.height;
	}
	else if(exact && holder.exact_dimensions){
		var dimensions = holder.exact_dimensions;
		text = (Math.floor(dimensions.width) + "x" + Math.floor(dimensions.height));
	}
	var text_width = ctx.measureText(text).width;
	if (text_width / width >= 0.75) {
		text_height = Math.floor(text_height * 0.75 * (width / text_width));
	}
	//Resetting font size if necessary
	ctx.font = "bold " + (text_height * ratio) + "px " + font;
	ctx.fillText(text, (width / 2), (height / 2), width);
	return canvas.toDataURL("image/png");
}

function draw_svg(args){
	var dimensions = args.dimensions,
		template = args.template,
		holder = args.holder,
		literal = holder.textmode == "literal",
		exact = holder.textmode == "exact";

	var ts = text_size(dimensions.width, dimensions.height, template);
	var text_height = ts.height;
	var width = dimensions.width,
		height = dimensions.height;
		
	var font = template.font ? template.font : "Arial,Helvetica,sans-serif";
	var text = template.text ? template.text : (Math.floor(dimensions.width) + "x" + Math.floor(dimensions.height));
	
	if (literal) {
		var dimensions = holder.dimensions;
		text = dimensions.width + "x" + dimensions.height;
	}
	else if(exact && holder.exact_dimensions){
		var dimensions = holder.exact_dimensions;
		text = (Math.floor(dimensions.width) + "x" + Math.floor(dimensions.height));
	}
	var string = svg_el({
		text: text, 
		width:width, 
		height:height, 
		text_height:text_height, 
		font:font, 
		template:template
	})
	return "data:image/svg+xml;base64,"+btoa(string);
}

function draw(args) {
	if(instance_config.use_canvas && !instance_config.use_svg){
		return draw_canvas(args);
	}
	else{
		return draw_svg(args);
	}
}

function render(mode, el, holder, src) {
	var dimensions = holder.dimensions,
		theme = holder.theme,
		text = holder.text ? decodeURIComponent(holder.text) : holder.text;
	var dimensions_caption = dimensions.width + "x" + dimensions.height;
	theme = (text ? extend(theme, {
		text: text
	}) : theme);
	theme = (holder.font ? extend(theme, {
		font: holder.font
	}) : theme);
	el.setAttribute("data-src", src);
	holder.theme = theme;
	el.holder_data = holder;
	
	if (mode == "image") {
		el.setAttribute("alt", text ? text : theme.text ? theme.text + " [" + dimensions_caption + "]" : dimensions_caption);
		if (instance_config.use_fallback || !holder.auto) {
			el.style.width = dimensions.width + "px";
			el.style.height = dimensions.height + "px";
		}
		if (instance_config.use_fallback) {
			el.style.backgroundColor = theme.background;
		} else {
			el.setAttribute("src", draw({ctx: ctx, dimensions: dimensions, template: theme, ratio:ratio, holder: holder}));
			
			if(holder.textmode && holder.textmode == "exact"){
				resizable_images.push(el);
				resizable_update(el);
			}
			
		}
	} else if (mode == "background") {
		if (!instance_config.use_fallback) {
			el.style.backgroundImage = "url(" + draw({ctx:ctx, dimensions: dimensions, template: theme, ratio: ratio, holder: holder}) + ")";
			el.style.backgroundSize = dimensions.width + "px " + dimensions.height + "px";
		}
	} else if (mode == "fluid") {
		el.setAttribute("alt", text ? text : theme.text ? theme.text + " [" + dimensions_caption + "]" : dimensions_caption);
		if (dimensions.height.slice(-1) == "%") {
			el.style.height = dimensions.height
		} else if(holder.auto == null || !holder.auto){
			el.style.height = dimensions.height + "px"
		}
		if (dimensions.width.slice(-1) == "%") {
			el.style.width = dimensions.width
		} else if(holder.auto == null || !holder.auto){
			el.style.width = dimensions.width + "px"
		}
		if (el.style.display == "inline" || el.style.display === "" || el.style.display == "none") {
			el.style.display = "block";
		}
		
		set_initial_dimensions(el)
		
		if (instance_config.use_fallback) {
			el.style.backgroundColor = theme.background;
		} else {
			resizable_images.push(el);
			resizable_update(el);
		}
	}
}

function dimension_check(el, callback) {
	var dimensions = {
		height: el.clientHeight,
		width: el.clientWidth
	};
	if (!dimensions.height && !dimensions.width) {
		el.setAttribute("data-holder-invisible", true)
		callback.call(this, el)
	}
	else{
		el.removeAttribute("data-holder-invisible")
		return dimensions;
	}
}

function set_initial_dimensions(el){
	if(el.holder_data){
		var dimensions = dimension_check(el, app.invisible_error_fn( set_initial_dimensions))
		if(dimensions){
			var holder = el.holder_data;
			holder.initial_dimensions = dimensions;
			holder.fluid_data = {
				fluid_height: holder.dimensions.height.slice(-1) == "%",
				fluid_width: holder.dimensions.width.slice(-1) == "%",
				mode: null
			}
			if(holder.fluid_data.fluid_width && !holder.fluid_data.fluid_height){
				holder.fluid_data.mode = "width"
				holder.fluid_data.ratio = holder.initial_dimensions.width / parseFloat(holder.dimensions.height)
			}
			else if(!holder.fluid_data.fluid_width && holder.fluid_data.fluid_height){
				holder.fluid_data.mode = "height";
				holder.fluid_data.ratio = parseFloat(holder.dimensions.width) / holder.initial_dimensions.height
			}
		}
	}
}

function resizable_update(element) {
	var images;
	if (element.nodeType == null) {
		images = resizable_images;
	} else {
		images = [element]
	}
	for (var i in images) {
		if (!images.hasOwnProperty(i)) {
			continue;
		}
		var el = images[i]
		if (el.holder_data) {
			var holder = el.holder_data;
			var dimensions = dimension_check(el, app.invisible_error_fn( resizable_update))
			if(dimensions){
				if(holder.fluid){
					if(holder.auto){
						switch(holder.fluid_data.mode){
							case "width":
								dimensions.height = dimensions.width / holder.fluid_data.ratio;
							break;
							case "height":
								dimensions.width = dimensions.height * holder.fluid_data.ratio;
							break;
						}
					}
					el.setAttribute("src", draw({
						ctx: ctx,
						dimensions: dimensions,
						template: holder.theme,
						ratio: ratio,
						holder: holder
					}))
				}
				if(holder.textmode && holder.textmode == "exact"){
					holder.exact_dimensions = dimensions;
					el.setAttribute("src", draw({
						ctx: ctx,
						dimensions: holder.dimensions,
						template: holder.theme,
						ratio: ratio,
						holder: holder
					}))
				}
			}
		}
	}
}

function parse_flags(flags, options) {
	var ret = {
		theme: extend(settings.themes.gray, {})
	};
	var render = false;
	for (var fl = flags.length, j = 0; j < fl; j++) {
		var flag = flags[j];
		if (app.flags.dimensions.match(flag)) {
			render = true;
			ret.dimensions = app.flags.dimensions.output(flag);
		} else if (app.flags.fluid.match(flag)) {
			render = true;
			ret.dimensions = app.flags.fluid.output(flag);
			ret.fluid = true;
		} else if (app.flags.textmode.match(flag)) {
			ret.textmode = app.flags.textmode.output(flag)
		} else if (app.flags.colors.match(flag)) {
			ret.theme = app.flags.colors.output(flag);
		} else if (options.themes[flag]) {
			//If a theme is specified, it will override custom colors
			if(options.themes.hasOwnProperty(flag)){
				ret.theme = extend(options.themes[flag], {});
			}
		} else if (app.flags.font.match(flag)) {
			ret.font = app.flags.font.output(flag);
		} else if (app.flags.auto.match(flag)) {
			ret.auto = true;
		} else if (app.flags.text.match(flag)) {
			ret.text = app.flags.text.output(flag);
		}
	}
	return render ? ret : false;
}

for (var flag in app.flags) {
	if (!app.flags.hasOwnProperty(flag)) continue;
	app.flags[flag].match = function (val) {
		return val.match(this.regex)
	}
}

app.invisible_error_fn = function(fn){
	return function(el){
		if(el.hasAttribute("data-holder-invisible")){
			throw new Error("Holder: invisible placeholder")
		}
	}
}

app.add_theme = function (name, theme) {
	name != null && theme != null && (settings.themes[name] = theme);
	return app;
};

app.add_image = function (src, el) {
	var node = selector(el);
	if (node.length) {
		for (var i = 0, l = node.length; i < l; i++) {
			var img = document.createElement("img")
			img.setAttribute("data-src", src);
			node[i].appendChild(img);
		}
	}
	return app;
};

app.run = function (o) {
	instance_config = extend({}, system_config)
	preempted = true;

	var options = extend(settings, o),
		images = [],
		imageNodes = [],
		bgnodes = [];
		
	if(options.use_canvas != null && options.use_canvas){
		instance_config.use_canvas = true;
		instance_config.use_svg = false;
	}
		
	if (typeof (options.images) == "string") {
		imageNodes = selector(options.images);
	} else if (window.NodeList && options.images instanceof window.NodeList) {
		imageNodes = options.images;
	} else if (window.Node && options.images instanceof window.Node) {
		imageNodes = [options.images];
	} else if(window.HTMLCollection && options.images instanceof window.HTMLCollection){
		imageNodes = options.images
	}

	if (typeof (options.bgnodes) == "string") {
		bgnodes = selector(options.bgnodes);
	} else if (window.NodeList && options.elements instanceof window.NodeList) {
		bgnodes = options.bgnodes;
	} else if (window.Node && options.bgnodes instanceof window.Node) {
		bgnodes = [options.bgnodes];
	}
	for (i = 0, l = imageNodes.length; i < l; i++) images.push(imageNodes[i]);
	var holdercss = document.getElementById("holderjs-style");
	if (!holdercss) {
		holdercss = document.createElement("style");
		holdercss.setAttribute("id", "holderjs-style");
		holdercss.type = "text/css";
		document.getElementsByTagName("head")[0].appendChild(holdercss);
	}
	if (!options.nocss) {
		if (holdercss.styleSheet) {
			holdercss.styleSheet.cssText += options.stylesheet;
		} else {
			holdercss.appendChild(document.createTextNode(options.stylesheet));
		}
	}
	var cssregex = new RegExp(options.domain + "\/(.*?)\"?\\)");
	for (var l = bgnodes.length, i = 0; i < l; i++) {
		var src = window.getComputedStyle(bgnodes[i], null)
			.getPropertyValue("background-image");
		var flags = src.match(cssregex);
		var bgsrc = bgnodes[i].getAttribute("data-background-src");
		if (flags) {
			var holder = parse_flags(flags[1].split("/"), options);
			if (holder) {
				render("background", bgnodes[i], holder, src);
			}
		} else if (bgsrc != null) {
			var holder = parse_flags(bgsrc.substr(bgsrc.lastIndexOf(options.domain) + options.domain.length + 1)
				.split("/"), options);
			if (holder) {
				render("background", bgnodes[i], holder, src);
			}
		}
	}
	for (l = images.length, i = 0; i < l; i++) {
		var attr_data_src, attr_src;
		attr_src = attr_data_src = src = null;
		try {
			attr_src = images[i].getAttribute("src");
			attr_datasrc = images[i].getAttribute("data-src");
		} catch (e) {}
		if (attr_datasrc == null && !! attr_src && attr_src.indexOf(options.domain) >= 0) {
			src = attr_src;
		} else if ( !! attr_datasrc && attr_datasrc.indexOf(options.domain) >= 0) {
			src = attr_datasrc;
		}
		if (src) {
			var holder = parse_flags(src.substr(src.lastIndexOf(options.domain) + options.domain.length + 1).split("/"), options);
			if (holder) {
				if (holder.fluid) {
					render("fluid", images[i], holder, src)
				} else {
					render("image", images[i], holder, src);
				}
			}
		}
	}
	return app;
};

contentLoaded(win, function () {
	if (window.addEventListener) {
		window.addEventListener("resize", resizable_update, false);
		window.addEventListener("orientationchange", resizable_update, false);
	} else {
		window.attachEvent("onresize", resizable_update)
	}
	preempted || app.run({});
});
if (typeof define === "function" && define.amd) {
	define([], function () {
		return app;
	});
}

//github.com/davidchambers/Base64.js
(function(){function t(t){this.message=t}var e="undefined"!=typeof exports?exports:this,r="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";t.prototype=Error(),t.prototype.name="InvalidCharacterError",e.btoa||(e.btoa=function(e){for(var o,n,a=0,i=r,c="";e.charAt(0|a)||(i="=",a%1);c+=i.charAt(63&o>>8-8*(a%1))){if(n=e.charCodeAt(a+=.75),n>255)throw new t("'btoa' failed");o=o<<8|n}return c}),e.atob||(e.atob=function(e){if(e=e.replace(/=+$/,""),1==e.length%4)throw new t("'atob' failed");for(var o,n,a=0,i=0,c="";n=e.charAt(i++);~n&&(o=a%4?64*o+n:n,a++%4)?c+=String.fromCharCode(255&o>>(6&-2*a)):0)n=r.indexOf(n);return c})})();

//getElementsByClassName polyfill
document.getElementsByClassName||(document.getElementsByClassName=function(e){var t=document,n,r,i,s=[];if(t.querySelectorAll)return t.querySelectorAll("."+e);if(t.evaluate){r=".//*[contains(concat(' ', @class, ' '), ' "+e+" ')]",n=t.evaluate(r,t,null,0,null);while(i=n.iterateNext())s.push(i)}else{n=t.getElementsByTagName("*"),r=new RegExp("(^|\\s)"+e+"(\\s|$)");for(i=0;i<n.length;i++)r.test(n[i].className)&&s.push(n[i])}return s})

//getComputedStyle polyfill
window.getComputedStyle||(window.getComputedStyle=function(e){return this.el=e,this.getPropertyValue=function(t){var n=/(\-([a-z]){1})/g;return t=="float"&&(t="styleFloat"),n.test(t)&&(t=t.replace(n,function(){return arguments[2].toUpperCase()})),e.currentStyle[t]?e.currentStyle[t]:null},this})

//http://javascript.nwbox.com/ContentLoaded by Diego Perini with modifications
function contentLoaded(n,t){var l="complete",s="readystatechange",u=!1,h=u,c=!0,i=n.document,a=i.documentElement,e=i.addEventListener?"addEventListener":"attachEvent",v=i.addEventListener?"removeEventListener":"detachEvent",f=i.addEventListener?"":"on",r=function(e){(e.type!=s||i.readyState==l)&&((e.type=="load"?n:i)[v](f+e.type,r,u),!h&&(h=!0)&&t.call(n,null))},o=function(){try{a.doScroll("left")}catch(n){setTimeout(o,50);return}r("poll")};if(i.readyState==l)t.call(n,"lazy");else{if(i.createEventObject&&a.doScroll){try{c=!n.frameElement}catch(y){}c&&o()}i[e](f+"DOMContentLoaded",r,u),i[e](f+s,r,u),n[e](f+"load",r,u)}}

//https://gist.github.com/991057 by Jed Schmidt with modifications
function selector(a,b){var a=a.match(/^(\W)?(.*)/),b=b||document,c=b["getElement"+(a[1]?"#"==a[1]?"ById":"sByClassName":"sByTagName")],d=c.call(b,a[2]),e=[];return null!==d&&(e=d.length||0===d.length?d:[d]),e}

//shallow object property extend
function extend(a,b){
	var c={};
	for(var i in a){
		if(a.hasOwnProperty(i)){
			c[i]=a[i];
		}
	}
	for(var i in b){
		if(b.hasOwnProperty(i)){
			c[i]=b[i];
		}
	}
	return c
}

//hasOwnProperty polyfill
if (!Object.prototype.hasOwnProperty)
    /*jshint -W001, -W103 */
    Object.prototype.hasOwnProperty = function(prop) {
		var proto = this.__proto__ || this.constructor.prototype;
		return (prop in this) && (!(prop in proto) || proto[prop] !== this[prop]);
	}
    /*jshint +W001, +W103 */

})(Holder, window);

/*!
 * Knockout JavaScript library v3.2.0
 * (c) Steven Sanderson - http://knockoutjs.com/
 * License: MIT (http://www.opensource.org/licenses/mit-license.php)
 */

(function() {(function(p){var s=this||(0,eval)("this"),v=s.document,L=s.navigator,w=s.jQuery,D=s.JSON;(function(p){"function"===typeof require&&"object"===typeof exports&&"object"===typeof module?p(module.exports||exports,require):"function"===typeof define&&define.amd?define(["exports","require"],p):p(s.ko={})})(function(M,N){function H(a,d){return null===a||typeof a in R?a===d:!1}function S(a,d){var c;return function(){c||(c=setTimeout(function(){c=p;a()},d))}}function T(a,d){var c;return function(){clearTimeout(c);
c=setTimeout(a,d)}}function I(b,d,c,e){a.d[b]={init:function(b,h,k,f,m){var l,q;a.s(function(){var f=a.a.c(h()),k=!c!==!f,z=!q;if(z||d||k!==l)z&&a.Y.la()&&(q=a.a.ia(a.f.childNodes(b),!0)),k?(z||a.f.T(b,a.a.ia(q)),a.Ca(e?e(m,f):m,b)):a.f.ja(b),l=k},null,{o:b});return{controlsDescendantBindings:!0}}};a.h.ha[b]=!1;a.f.Q[b]=!0}var a="undefined"!==typeof M?M:{};a.b=function(b,d){for(var c=b.split("."),e=a,g=0;g<c.length-1;g++)e=e[c[g]];e[c[c.length-1]]=d};a.A=function(a,d,c){a[d]=c};a.version="3.2.0";
a.b("version",a.version);a.a=function(){function b(a,b){for(var c in a)a.hasOwnProperty(c)&&b(c,a[c])}function d(a,b){if(b)for(var c in b)b.hasOwnProperty(c)&&(a[c]=b[c]);return a}function c(a,b){a.__proto__=b;return a}var e={__proto__:[]}instanceof Array,g={},h={};g[L&&/Firefox\/2/i.test(L.userAgent)?"KeyboardEvent":"UIEvents"]=["keyup","keydown","keypress"];g.MouseEvents="click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave".split(" ");b(g,function(a,b){if(b.length)for(var c=
0,d=b.length;c<d;c++)h[b[c]]=a});var k={propertychange:!0},f=v&&function(){for(var a=3,b=v.createElement("div"),c=b.getElementsByTagName("i");b.innerHTML="\x3c!--[if gt IE "+ ++a+"]><i></i><![endif]--\x3e",c[0];);return 4<a?a:p}();return{vb:["authenticity_token",/^__RequestVerificationToken(_.*)?$/],u:function(a,b){for(var c=0,d=a.length;c<d;c++)b(a[c],c)},m:function(a,b){if("function"==typeof Array.prototype.indexOf)return Array.prototype.indexOf.call(a,b);for(var c=0,d=a.length;c<d;c++)if(a[c]===
b)return c;return-1},qb:function(a,b,c){for(var d=0,f=a.length;d<f;d++)if(b.call(c,a[d],d))return a[d];return null},ua:function(m,b){var c=a.a.m(m,b);0<c?m.splice(c,1):0===c&&m.shift()},rb:function(m){m=m||[];for(var b=[],c=0,d=m.length;c<d;c++)0>a.a.m(b,m[c])&&b.push(m[c]);return b},Da:function(a,b){a=a||[];for(var c=[],d=0,f=a.length;d<f;d++)c.push(b(a[d],d));return c},ta:function(a,b){a=a||[];for(var c=[],d=0,f=a.length;d<f;d++)b(a[d],d)&&c.push(a[d]);return c},ga:function(a,b){if(b instanceof
Array)a.push.apply(a,b);else for(var c=0,d=b.length;c<d;c++)a.push(b[c]);return a},ea:function(b,c,d){var f=a.a.m(a.a.Xa(b),c);0>f?d&&b.push(c):d||b.splice(f,1)},xa:e,extend:d,za:c,Aa:e?c:d,G:b,na:function(a,b){if(!a)return a;var c={},d;for(d in a)a.hasOwnProperty(d)&&(c[d]=b(a[d],d,a));return c},Ka:function(b){for(;b.firstChild;)a.removeNode(b.firstChild)},oc:function(b){b=a.a.S(b);for(var c=v.createElement("div"),d=0,f=b.length;d<f;d++)c.appendChild(a.R(b[d]));return c},ia:function(b,c){for(var d=
0,f=b.length,e=[];d<f;d++){var k=b[d].cloneNode(!0);e.push(c?a.R(k):k)}return e},T:function(b,c){a.a.Ka(b);if(c)for(var d=0,f=c.length;d<f;d++)b.appendChild(c[d])},Lb:function(b,c){var d=b.nodeType?[b]:b;if(0<d.length){for(var f=d[0],e=f.parentNode,k=0,g=c.length;k<g;k++)e.insertBefore(c[k],f);k=0;for(g=d.length;k<g;k++)a.removeNode(d[k])}},ka:function(a,b){if(a.length){for(b=8===b.nodeType&&b.parentNode||b;a.length&&a[0].parentNode!==b;)a.shift();if(1<a.length){var c=a[0],d=a[a.length-1];for(a.length=
0;c!==d;)if(a.push(c),c=c.nextSibling,!c)return;a.push(d)}}return a},Nb:function(a,b){7>f?a.setAttribute("selected",b):a.selected=b},cb:function(a){return null===a||a===p?"":a.trim?a.trim():a.toString().replace(/^[\s\xa0]+|[\s\xa0]+$/g,"")},vc:function(a,b){a=a||"";return b.length>a.length?!1:a.substring(0,b.length)===b},cc:function(a,b){if(a===b)return!0;if(11===a.nodeType)return!1;if(b.contains)return b.contains(3===a.nodeType?a.parentNode:a);if(b.compareDocumentPosition)return 16==(b.compareDocumentPosition(a)&
16);for(;a&&a!=b;)a=a.parentNode;return!!a},Ja:function(b){return a.a.cc(b,b.ownerDocument.documentElement)},ob:function(b){return!!a.a.qb(b,a.a.Ja)},t:function(a){return a&&a.tagName&&a.tagName.toLowerCase()},n:function(b,c,d){var e=f&&k[c];if(!e&&w)w(b).bind(c,d);else if(e||"function"!=typeof b.addEventListener)if("undefined"!=typeof b.attachEvent){var g=function(a){d.call(b,a)},h="on"+c;b.attachEvent(h,g);a.a.w.da(b,function(){b.detachEvent(h,g)})}else throw Error("Browser doesn't support addEventListener or attachEvent");
else b.addEventListener(c,d,!1)},oa:function(b,c){if(!b||!b.nodeType)throw Error("element must be a DOM node when calling triggerEvent");var d;"input"===a.a.t(b)&&b.type&&"click"==c.toLowerCase()?(d=b.type,d="checkbox"==d||"radio"==d):d=!1;if(w&&!d)w(b).trigger(c);else if("function"==typeof v.createEvent)if("function"==typeof b.dispatchEvent)d=v.createEvent(h[c]||"HTMLEvents"),d.initEvent(c,!0,!0,s,0,0,0,0,0,!1,!1,!1,!1,0,b),b.dispatchEvent(d);else throw Error("The supplied element doesn't support dispatchEvent");
else if(d&&b.click)b.click();else if("undefined"!=typeof b.fireEvent)b.fireEvent("on"+c);else throw Error("Browser doesn't support triggering events");},c:function(b){return a.C(b)?b():b},Xa:function(b){return a.C(b)?b.v():b},Ba:function(b,c,d){if(c){var f=/\S+/g,e=b.className.match(f)||[];a.a.u(c.match(f),function(b){a.a.ea(e,b,d)});b.className=e.join(" ")}},bb:function(b,c){var d=a.a.c(c);if(null===d||d===p)d="";var f=a.f.firstChild(b);!f||3!=f.nodeType||a.f.nextSibling(f)?a.f.T(b,[b.ownerDocument.createTextNode(d)]):
f.data=d;a.a.fc(b)},Mb:function(a,b){a.name=b;if(7>=f)try{a.mergeAttributes(v.createElement("<input name='"+a.name+"'/>"),!1)}catch(c){}},fc:function(a){9<=f&&(a=1==a.nodeType?a:a.parentNode,a.style&&(a.style.zoom=a.style.zoom))},dc:function(a){if(f){var b=a.style.width;a.style.width=0;a.style.width=b}},sc:function(b,c){b=a.a.c(b);c=a.a.c(c);for(var d=[],f=b;f<=c;f++)d.push(f);return d},S:function(a){for(var b=[],c=0,d=a.length;c<d;c++)b.push(a[c]);return b},yc:6===f,zc:7===f,L:f,xb:function(b,c){for(var d=
a.a.S(b.getElementsByTagName("input")).concat(a.a.S(b.getElementsByTagName("textarea"))),f="string"==typeof c?function(a){return a.name===c}:function(a){return c.test(a.name)},e=[],k=d.length-1;0<=k;k--)f(d[k])&&e.push(d[k]);return e},pc:function(b){return"string"==typeof b&&(b=a.a.cb(b))?D&&D.parse?D.parse(b):(new Function("return "+b))():null},eb:function(b,c,d){if(!D||!D.stringify)throw Error("Cannot find JSON.stringify(). Some browsers (e.g., IE < 8) don't support it natively, but you can overcome this by adding a script reference to json2.js, downloadable from http://www.json.org/json2.js");
return D.stringify(a.a.c(b),c,d)},qc:function(c,d,f){f=f||{};var e=f.params||{},k=f.includeFields||this.vb,g=c;if("object"==typeof c&&"form"===a.a.t(c))for(var g=c.action,h=k.length-1;0<=h;h--)for(var r=a.a.xb(c,k[h]),E=r.length-1;0<=E;E--)e[r[E].name]=r[E].value;d=a.a.c(d);var y=v.createElement("form");y.style.display="none";y.action=g;y.method="post";for(var p in d)c=v.createElement("input"),c.type="hidden",c.name=p,c.value=a.a.eb(a.a.c(d[p])),y.appendChild(c);b(e,function(a,b){var c=v.createElement("input");
c.type="hidden";c.name=a;c.value=b;y.appendChild(c)});v.body.appendChild(y);f.submitter?f.submitter(y):y.submit();setTimeout(function(){y.parentNode.removeChild(y)},0)}}}();a.b("utils",a.a);a.b("utils.arrayForEach",a.a.u);a.b("utils.arrayFirst",a.a.qb);a.b("utils.arrayFilter",a.a.ta);a.b("utils.arrayGetDistinctValues",a.a.rb);a.b("utils.arrayIndexOf",a.a.m);a.b("utils.arrayMap",a.a.Da);a.b("utils.arrayPushAll",a.a.ga);a.b("utils.arrayRemoveItem",a.a.ua);a.b("utils.extend",a.a.extend);a.b("utils.fieldsIncludedWithJsonPost",
a.a.vb);a.b("utils.getFormFields",a.a.xb);a.b("utils.peekObservable",a.a.Xa);a.b("utils.postJson",a.a.qc);a.b("utils.parseJson",a.a.pc);a.b("utils.registerEventHandler",a.a.n);a.b("utils.stringifyJson",a.a.eb);a.b("utils.range",a.a.sc);a.b("utils.toggleDomNodeCssClass",a.a.Ba);a.b("utils.triggerEvent",a.a.oa);a.b("utils.unwrapObservable",a.a.c);a.b("utils.objectForEach",a.a.G);a.b("utils.addOrRemoveItem",a.a.ea);a.b("unwrap",a.a.c);Function.prototype.bind||(Function.prototype.bind=function(a){var d=
this,c=Array.prototype.slice.call(arguments);a=c.shift();return function(){return d.apply(a,c.concat(Array.prototype.slice.call(arguments)))}});a.a.e=new function(){function a(b,h){var k=b[c];if(!k||"null"===k||!e[k]){if(!h)return p;k=b[c]="ko"+d++;e[k]={}}return e[k]}var d=0,c="__ko__"+(new Date).getTime(),e={};return{get:function(c,d){var e=a(c,!1);return e===p?p:e[d]},set:function(c,d,e){if(e!==p||a(c,!1)!==p)a(c,!0)[d]=e},clear:function(a){var b=a[c];return b?(delete e[b],a[c]=null,!0):!1},F:function(){return d++ +
c}}};a.b("utils.domData",a.a.e);a.b("utils.domData.clear",a.a.e.clear);a.a.w=new function(){function b(b,d){var f=a.a.e.get(b,c);f===p&&d&&(f=[],a.a.e.set(b,c,f));return f}function d(c){var e=b(c,!1);if(e)for(var e=e.slice(0),f=0;f<e.length;f++)e[f](c);a.a.e.clear(c);a.a.w.cleanExternalData(c);if(g[c.nodeType])for(e=c.firstChild;c=e;)e=c.nextSibling,8===c.nodeType&&d(c)}var c=a.a.e.F(),e={1:!0,8:!0,9:!0},g={1:!0,9:!0};return{da:function(a,c){if("function"!=typeof c)throw Error("Callback must be a function");
b(a,!0).push(c)},Kb:function(d,e){var f=b(d,!1);f&&(a.a.ua(f,e),0==f.length&&a.a.e.set(d,c,p))},R:function(b){if(e[b.nodeType]&&(d(b),g[b.nodeType])){var c=[];a.a.ga(c,b.getElementsByTagName("*"));for(var f=0,m=c.length;f<m;f++)d(c[f])}return b},removeNode:function(b){a.R(b);b.parentNode&&b.parentNode.removeChild(b)},cleanExternalData:function(a){w&&"function"==typeof w.cleanData&&w.cleanData([a])}}};a.R=a.a.w.R;a.removeNode=a.a.w.removeNode;a.b("cleanNode",a.R);a.b("removeNode",a.removeNode);a.b("utils.domNodeDisposal",
a.a.w);a.b("utils.domNodeDisposal.addDisposeCallback",a.a.w.da);a.b("utils.domNodeDisposal.removeDisposeCallback",a.a.w.Kb);(function(){a.a.ba=function(b){var d;if(w)if(w.parseHTML)d=w.parseHTML(b)||[];else{if((d=w.clean([b]))&&d[0]){for(b=d[0];b.parentNode&&11!==b.parentNode.nodeType;)b=b.parentNode;b.parentNode&&b.parentNode.removeChild(b)}}else{var c=a.a.cb(b).toLowerCase();d=v.createElement("div");c=c.match(/^<(thead|tbody|tfoot)/)&&[1,"<table>","</table>"]||!c.indexOf("<tr")&&[2,"<table><tbody>",
"</tbody></table>"]||(!c.indexOf("<td")||!c.indexOf("<th"))&&[3,"<table><tbody><tr>","</tr></tbody></table>"]||[0,"",""];b="ignored<div>"+c[1]+b+c[2]+"</div>";for("function"==typeof s.innerShiv?d.appendChild(s.innerShiv(b)):d.innerHTML=b;c[0]--;)d=d.lastChild;d=a.a.S(d.lastChild.childNodes)}return d};a.a.$a=function(b,d){a.a.Ka(b);d=a.a.c(d);if(null!==d&&d!==p)if("string"!=typeof d&&(d=d.toString()),w)w(b).html(d);else for(var c=a.a.ba(d),e=0;e<c.length;e++)b.appendChild(c[e])}})();a.b("utils.parseHtmlFragment",
a.a.ba);a.b("utils.setHtml",a.a.$a);a.D=function(){function b(c,d){if(c)if(8==c.nodeType){var g=a.D.Gb(c.nodeValue);null!=g&&d.push({bc:c,mc:g})}else if(1==c.nodeType)for(var g=0,h=c.childNodes,k=h.length;g<k;g++)b(h[g],d)}var d={};return{Ua:function(a){if("function"!=typeof a)throw Error("You can only pass a function to ko.memoization.memoize()");var b=(4294967296*(1+Math.random())|0).toString(16).substring(1)+(4294967296*(1+Math.random())|0).toString(16).substring(1);d[b]=a;return"\x3c!--[ko_memo:"+
b+"]--\x3e"},Rb:function(a,b){var g=d[a];if(g===p)throw Error("Couldn't find any memo with ID "+a+". Perhaps it's already been unmemoized.");try{return g.apply(null,b||[]),!0}finally{delete d[a]}},Sb:function(c,d){var g=[];b(c,g);for(var h=0,k=g.length;h<k;h++){var f=g[h].bc,m=[f];d&&a.a.ga(m,d);a.D.Rb(g[h].mc,m);f.nodeValue="";f.parentNode&&f.parentNode.removeChild(f)}},Gb:function(a){return(a=a.match(/^\[ko_memo\:(.*?)\]$/))?a[1]:null}}}();a.b("memoization",a.D);a.b("memoization.memoize",a.D.Ua);
a.b("memoization.unmemoize",a.D.Rb);a.b("memoization.parseMemoText",a.D.Gb);a.b("memoization.unmemoizeDomNodeAndDescendants",a.D.Sb);a.La={throttle:function(b,d){b.throttleEvaluation=d;var c=null;return a.j({read:b,write:function(a){clearTimeout(c);c=setTimeout(function(){b(a)},d)}})},rateLimit:function(a,d){var c,e,g;"number"==typeof d?c=d:(c=d.timeout,e=d.method);g="notifyWhenChangesStop"==e?T:S;a.Ta(function(a){return g(a,c)})},notify:function(a,d){a.equalityComparer="always"==d?null:H}};var R=
{undefined:1,"boolean":1,number:1,string:1};a.b("extenders",a.La);a.Pb=function(b,d,c){this.target=b;this.wa=d;this.ac=c;this.Cb=!1;a.A(this,"dispose",this.K)};a.Pb.prototype.K=function(){this.Cb=!0;this.ac()};a.P=function(){a.a.Aa(this,a.P.fn);this.M={}};var G="change",A={U:function(b,d,c){var e=this;c=c||G;var g=new a.Pb(e,d?b.bind(d):b,function(){a.a.ua(e.M[c],g);e.nb&&e.nb()});e.va&&e.va(c);e.M[c]||(e.M[c]=[]);e.M[c].push(g);return g},notifySubscribers:function(b,d){d=d||G;if(this.Ab(d))try{a.k.Ea();
for(var c=this.M[d].slice(0),e=0,g;g=c[e];++e)g.Cb||g.wa(b)}finally{a.k.end()}},Ta:function(b){var d=this,c=a.C(d),e,g,h;d.qa||(d.qa=d.notifySubscribers,d.notifySubscribers=function(a,b){b&&b!==G?"beforeChange"===b?d.kb(a):d.qa(a,b):d.lb(a)});var k=b(function(){c&&h===d&&(h=d());e=!1;d.Pa(g,h)&&d.qa(g=h)});d.lb=function(a){e=!0;h=a;k()};d.kb=function(a){e||(g=a,d.qa(a,"beforeChange"))}},Ab:function(a){return this.M[a]&&this.M[a].length},yb:function(){var b=0;a.a.G(this.M,function(a,c){b+=c.length});
return b},Pa:function(a,d){return!this.equalityComparer||!this.equalityComparer(a,d)},extend:function(b){var d=this;b&&a.a.G(b,function(b,e){var g=a.La[b];"function"==typeof g&&(d=g(d,e)||d)});return d}};a.A(A,"subscribe",A.U);a.A(A,"extend",A.extend);a.A(A,"getSubscriptionsCount",A.yb);a.a.xa&&a.a.za(A,Function.prototype);a.P.fn=A;a.Db=function(a){return null!=a&&"function"==typeof a.U&&"function"==typeof a.notifySubscribers};a.b("subscribable",a.P);a.b("isSubscribable",a.Db);a.Y=a.k=function(){function b(a){c.push(e);
e=a}function d(){e=c.pop()}var c=[],e,g=0;return{Ea:b,end:d,Jb:function(b){if(e){if(!a.Db(b))throw Error("Only subscribable things can act as dependencies");e.wa(b,b.Vb||(b.Vb=++g))}},B:function(a,c,f){try{return b(),a.apply(c,f||[])}finally{d()}},la:function(){if(e)return e.s.la()},ma:function(){if(e)return e.ma}}}();a.b("computedContext",a.Y);a.b("computedContext.getDependenciesCount",a.Y.la);a.b("computedContext.isInitial",a.Y.ma);a.b("computedContext.isSleeping",a.Y.Ac);a.p=function(b){function d(){if(0<
arguments.length)return d.Pa(c,arguments[0])&&(d.X(),c=arguments[0],d.W()),this;a.k.Jb(d);return c}var c=b;a.P.call(d);a.a.Aa(d,a.p.fn);d.v=function(){return c};d.W=function(){d.notifySubscribers(c)};d.X=function(){d.notifySubscribers(c,"beforeChange")};a.A(d,"peek",d.v);a.A(d,"valueHasMutated",d.W);a.A(d,"valueWillMutate",d.X);return d};a.p.fn={equalityComparer:H};var F=a.p.rc="__ko_proto__";a.p.fn[F]=a.p;a.a.xa&&a.a.za(a.p.fn,a.P.fn);a.Ma=function(b,d){return null===b||b===p||b[F]===p?!1:b[F]===
d?!0:a.Ma(b[F],d)};a.C=function(b){return a.Ma(b,a.p)};a.Ra=function(b){return"function"==typeof b&&b[F]===a.p||"function"==typeof b&&b[F]===a.j&&b.hc?!0:!1};a.b("observable",a.p);a.b("isObservable",a.C);a.b("isWriteableObservable",a.Ra);a.b("isWritableObservable",a.Ra);a.aa=function(b){b=b||[];if("object"!=typeof b||!("length"in b))throw Error("The argument passed when initializing an observable array must be an array, or null, or undefined.");b=a.p(b);a.a.Aa(b,a.aa.fn);return b.extend({trackArrayChanges:!0})};
a.aa.fn={remove:function(b){for(var d=this.v(),c=[],e="function"!=typeof b||a.C(b)?function(a){return a===b}:b,g=0;g<d.length;g++){var h=d[g];e(h)&&(0===c.length&&this.X(),c.push(h),d.splice(g,1),g--)}c.length&&this.W();return c},removeAll:function(b){if(b===p){var d=this.v(),c=d.slice(0);this.X();d.splice(0,d.length);this.W();return c}return b?this.remove(function(c){return 0<=a.a.m(b,c)}):[]},destroy:function(b){var d=this.v(),c="function"!=typeof b||a.C(b)?function(a){return a===b}:b;this.X();
for(var e=d.length-1;0<=e;e--)c(d[e])&&(d[e]._destroy=!0);this.W()},destroyAll:function(b){return b===p?this.destroy(function(){return!0}):b?this.destroy(function(d){return 0<=a.a.m(b,d)}):[]},indexOf:function(b){var d=this();return a.a.m(d,b)},replace:function(a,d){var c=this.indexOf(a);0<=c&&(this.X(),this.v()[c]=d,this.W())}};a.a.u("pop push reverse shift sort splice unshift".split(" "),function(b){a.aa.fn[b]=function(){var a=this.v();this.X();this.sb(a,b,arguments);a=a[b].apply(a,arguments);this.W();
return a}});a.a.u(["slice"],function(b){a.aa.fn[b]=function(){var a=this();return a[b].apply(a,arguments)}});a.a.xa&&a.a.za(a.aa.fn,a.p.fn);a.b("observableArray",a.aa);var J="arrayChange";a.La.trackArrayChanges=function(b){function d(){if(!c){c=!0;var d=b.notifySubscribers;b.notifySubscribers=function(a,b){b&&b!==G||++g;return d.apply(this,arguments)};var f=[].concat(b.v()||[]);e=null;b.U(function(c){c=[].concat(c||[]);if(b.Ab(J)){var d;if(!e||1<g)e=a.a.Fa(f,c,{sparse:!0});d=e;d.length&&b.notifySubscribers(d,
J)}f=c;e=null;g=0})}}if(!b.sb){var c=!1,e=null,g=0,h=b.U;b.U=b.subscribe=function(a,b,c){c===J&&d();return h.apply(this,arguments)};b.sb=function(b,d,m){function l(a,b,c){return q[q.length]={status:a,value:b,index:c}}if(c&&!g){var q=[],h=b.length,t=m.length,z=0;switch(d){case "push":z=h;case "unshift":for(d=0;d<t;d++)l("added",m[d],z+d);break;case "pop":z=h-1;case "shift":h&&l("deleted",b[z],z);break;case "splice":d=Math.min(Math.max(0,0>m[0]?h+m[0]:m[0]),h);for(var h=1===t?h:Math.min(d+(m[1]||0),
h),t=d+t-2,z=Math.max(h,t),u=[],r=[],E=2;d<z;++d,++E)d<h&&r.push(l("deleted",b[d],d)),d<t&&u.push(l("added",m[E],d));a.a.wb(r,u);break;default:return}e=q}}}};a.s=a.j=function(b,d,c){function e(){a.a.G(v,function(a,b){b.K()});v={}}function g(){e();C=0;u=!0;n=!1}function h(){var a=f.throttleEvaluation;a&&0<=a?(clearTimeout(P),P=setTimeout(k,a)):f.ib?f.ib():k()}function k(b){if(t){if(E)throw Error("A 'pure' computed must not be called recursively");}else if(!u){if(w&&w()){if(!z){s();return}}else z=!1;
t=!0;if(y)try{var c={};a.k.Ea({wa:function(a,b){c[b]||(c[b]=1,++C)},s:f,ma:p});C=0;q=r.call(d)}finally{a.k.end(),t=!1}else try{var e=v,m=C;a.k.Ea({wa:function(a,b){u||(m&&e[b]?(v[b]=e[b],++C,delete e[b],--m):v[b]||(v[b]=a.U(h),++C))},s:f,ma:E?p:!C});v={};C=0;try{var l=d?r.call(d):r()}finally{a.k.end(),m&&a.a.G(e,function(a,b){b.K()}),n=!1}f.Pa(q,l)&&(f.notifySubscribers(q,"beforeChange"),q=l,!0!==b&&f.notifySubscribers(q))}finally{t=!1}C||s()}}function f(){if(0<arguments.length){if("function"===typeof O)O.apply(d,
arguments);else throw Error("Cannot write a value to a ko.computed unless you specify a 'write' option. If you wish to read the current value, don't pass any parameters.");return this}a.k.Jb(f);n&&k(!0);return q}function m(){n&&!C&&k(!0);return q}function l(){return n||0<C}var q,n=!0,t=!1,z=!1,u=!1,r=b,E=!1,y=!1;r&&"object"==typeof r?(c=r,r=c.read):(c=c||{},r||(r=c.read));if("function"!=typeof r)throw Error("Pass a function that returns the value of the ko.computed");var O=c.write,x=c.disposeWhenNodeIsRemoved||
c.o||null,B=c.disposeWhen||c.Ia,w=B,s=g,v={},C=0,P=null;d||(d=c.owner);a.P.call(f);a.a.Aa(f,a.j.fn);f.v=m;f.la=function(){return C};f.hc="function"===typeof c.write;f.K=function(){s()};f.Z=l;var A=f.Ta;f.Ta=function(a){A.call(f,a);f.ib=function(){f.kb(q);n=!0;f.lb(f)}};c.pure?(y=E=!0,f.va=function(){y&&(y=!1,k(!0))},f.nb=function(){f.yb()||(e(),y=n=!0)}):c.deferEvaluation&&(f.va=function(){m();delete f.va});a.A(f,"peek",f.v);a.A(f,"dispose",f.K);a.A(f,"isActive",f.Z);a.A(f,"getDependenciesCount",
f.la);x&&(z=!0,x.nodeType&&(w=function(){return!a.a.Ja(x)||B&&B()}));y||c.deferEvaluation||k();x&&l()&&x.nodeType&&(s=function(){a.a.w.Kb(x,s);g()},a.a.w.da(x,s));return f};a.jc=function(b){return a.Ma(b,a.j)};A=a.p.rc;a.j[A]=a.p;a.j.fn={equalityComparer:H};a.j.fn[A]=a.j;a.a.xa&&a.a.za(a.j.fn,a.P.fn);a.b("dependentObservable",a.j);a.b("computed",a.j);a.b("isComputed",a.jc);a.Ib=function(b,d){if("function"===typeof b)return a.s(b,d,{pure:!0});b=a.a.extend({},b);b.pure=!0;return a.s(b,d)};a.b("pureComputed",
a.Ib);(function(){function b(a,g,h){h=h||new c;a=g(a);if("object"!=typeof a||null===a||a===p||a instanceof Date||a instanceof String||a instanceof Number||a instanceof Boolean)return a;var k=a instanceof Array?[]:{};h.save(a,k);d(a,function(c){var d=g(a[c]);switch(typeof d){case "boolean":case "number":case "string":case "function":k[c]=d;break;case "object":case "undefined":var l=h.get(d);k[c]=l!==p?l:b(d,g,h)}});return k}function d(a,b){if(a instanceof Array){for(var c=0;c<a.length;c++)b(c);"function"==
typeof a.toJSON&&b("toJSON")}else for(c in a)b(c)}function c(){this.keys=[];this.hb=[]}a.Qb=function(c){if(0==arguments.length)throw Error("When calling ko.toJS, pass the object you want to convert.");return b(c,function(b){for(var c=0;a.C(b)&&10>c;c++)b=b();return b})};a.toJSON=function(b,c,d){b=a.Qb(b);return a.a.eb(b,c,d)};c.prototype={save:function(b,c){var d=a.a.m(this.keys,b);0<=d?this.hb[d]=c:(this.keys.push(b),this.hb.push(c))},get:function(b){b=a.a.m(this.keys,b);return 0<=b?this.hb[b]:p}}})();
a.b("toJS",a.Qb);a.b("toJSON",a.toJSON);(function(){a.i={q:function(b){switch(a.a.t(b)){case "option":return!0===b.__ko__hasDomDataOptionValue__?a.a.e.get(b,a.d.options.Va):7>=a.a.L?b.getAttributeNode("value")&&b.getAttributeNode("value").specified?b.value:b.text:b.value;case "select":return 0<=b.selectedIndex?a.i.q(b.options[b.selectedIndex]):p;default:return b.value}},ca:function(b,d,c){switch(a.a.t(b)){case "option":switch(typeof d){case "string":a.a.e.set(b,a.d.options.Va,p);"__ko__hasDomDataOptionValue__"in
b&&delete b.__ko__hasDomDataOptionValue__;b.value=d;break;default:a.a.e.set(b,a.d.options.Va,d),b.__ko__hasDomDataOptionValue__=!0,b.value="number"===typeof d?d:""}break;case "select":if(""===d||null===d)d=p;for(var e=-1,g=0,h=b.options.length,k;g<h;++g)if(k=a.i.q(b.options[g]),k==d||""==k&&d===p){e=g;break}if(c||0<=e||d===p&&1<b.size)b.selectedIndex=e;break;default:if(null===d||d===p)d="";b.value=d}}}})();a.b("selectExtensions",a.i);a.b("selectExtensions.readValue",a.i.q);a.b("selectExtensions.writeValue",
a.i.ca);a.h=function(){function b(b){b=a.a.cb(b);123===b.charCodeAt(0)&&(b=b.slice(1,-1));var c=[],d=b.match(e),k,n,t=0;if(d){d.push(",");for(var z=0,u;u=d[z];++z){var r=u.charCodeAt(0);if(44===r){if(0>=t){k&&c.push(n?{key:k,value:n.join("")}:{unknown:k});k=n=t=0;continue}}else if(58===r){if(!n)continue}else if(47===r&&z&&1<u.length)(r=d[z-1].match(g))&&!h[r[0]]&&(b=b.substr(b.indexOf(u)+1),d=b.match(e),d.push(","),z=-1,u="/");else if(40===r||123===r||91===r)++t;else if(41===r||125===r||93===r)--t;
else if(!k&&!n){k=34===r||39===r?u.slice(1,-1):u;continue}n?n.push(u):n=[u]}}return c}var d=["true","false","null","undefined"],c=/^(?:[$_a-z][$\w]*|(.+)(\.\s*[$_a-z][$\w]*|\[.+\]))$/i,e=RegExp("\"(?:[^\"\\\\]|\\\\.)*\"|'(?:[^'\\\\]|\\\\.)*'|/(?:[^/\\\\]|\\\\.)*/w*|[^\\s:,/][^,\"'{}()/:[\\]]*[^\\s,\"'{}()/:[\\]]|[^\\s]","g"),g=/[\])"'A-Za-z0-9_$]+$/,h={"in":1,"return":1,"typeof":1},k={};return{ha:[],V:k,Wa:b,ya:function(f,m){function e(b,m){var f;if(!z){var u=a.getBindingHandler(b);if(u&&u.preprocess&&
!(m=u.preprocess(m,b,e)))return;if(u=k[b])f=m,0<=a.a.m(d,f)?f=!1:(u=f.match(c),f=null===u?!1:u[1]?"Object("+u[1]+")"+u[2]:f),u=f;u&&h.push("'"+b+"':function(_z){"+f+"=_z}")}t&&(m="function(){return "+m+" }");g.push("'"+b+"':"+m)}m=m||{};var g=[],h=[],t=m.valueAccessors,z=m.bindingParams,u="string"===typeof f?b(f):f;a.a.u(u,function(a){e(a.key||a.unknown,a.value)});h.length&&e("_ko_property_writers","{"+h.join(",")+" }");return g.join(",")},lc:function(a,b){for(var c=0;c<a.length;c++)if(a[c].key==
b)return!0;return!1},pa:function(b,c,d,e,k){if(b&&a.C(b))!a.Ra(b)||k&&b.v()===e||b(e);else if((b=c.get("_ko_property_writers"))&&b[d])b[d](e)}}}();a.b("expressionRewriting",a.h);a.b("expressionRewriting.bindingRewriteValidators",a.h.ha);a.b("expressionRewriting.parseObjectLiteral",a.h.Wa);a.b("expressionRewriting.preProcessBindings",a.h.ya);a.b("expressionRewriting._twoWayBindings",a.h.V);a.b("jsonExpressionRewriting",a.h);a.b("jsonExpressionRewriting.insertPropertyAccessorsIntoJson",a.h.ya);(function(){function b(a){return 8==
a.nodeType&&h.test(g?a.text:a.nodeValue)}function d(a){return 8==a.nodeType&&k.test(g?a.text:a.nodeValue)}function c(a,c){for(var f=a,e=1,k=[];f=f.nextSibling;){if(d(f)&&(e--,0===e))return k;k.push(f);b(f)&&e++}if(!c)throw Error("Cannot find closing comment tag to match: "+a.nodeValue);return null}function e(a,b){var d=c(a,b);return d?0<d.length?d[d.length-1].nextSibling:a.nextSibling:null}var g=v&&"\x3c!--test--\x3e"===v.createComment("test").text,h=g?/^\x3c!--\s*ko(?:\s+([\s\S]+))?\s*--\x3e$/:/^\s*ko(?:\s+([\s\S]+))?\s*$/,
k=g?/^\x3c!--\s*\/ko\s*--\x3e$/:/^\s*\/ko\s*$/,f={ul:!0,ol:!0};a.f={Q:{},childNodes:function(a){return b(a)?c(a):a.childNodes},ja:function(c){if(b(c)){c=a.f.childNodes(c);for(var d=0,f=c.length;d<f;d++)a.removeNode(c[d])}else a.a.Ka(c)},T:function(c,d){if(b(c)){a.f.ja(c);for(var f=c.nextSibling,e=0,k=d.length;e<k;e++)f.parentNode.insertBefore(d[e],f)}else a.a.T(c,d)},Hb:function(a,c){b(a)?a.parentNode.insertBefore(c,a.nextSibling):a.firstChild?a.insertBefore(c,a.firstChild):a.appendChild(c)},Bb:function(c,
d,f){f?b(c)?c.parentNode.insertBefore(d,f.nextSibling):f.nextSibling?c.insertBefore(d,f.nextSibling):c.appendChild(d):a.f.Hb(c,d)},firstChild:function(a){return b(a)?!a.nextSibling||d(a.nextSibling)?null:a.nextSibling:a.firstChild},nextSibling:function(a){b(a)&&(a=e(a));return a.nextSibling&&d(a.nextSibling)?null:a.nextSibling},gc:b,xc:function(a){return(a=(g?a.text:a.nodeValue).match(h))?a[1]:null},Fb:function(c){if(f[a.a.t(c)]){var k=c.firstChild;if(k){do if(1===k.nodeType){var g;g=k.firstChild;
var h=null;if(g){do if(h)h.push(g);else if(b(g)){var t=e(g,!0);t?g=t:h=[g]}else d(g)&&(h=[g]);while(g=g.nextSibling)}if(g=h)for(h=k.nextSibling,t=0;t<g.length;t++)h?c.insertBefore(g[t],h):c.appendChild(g[t])}while(k=k.nextSibling)}}}}})();a.b("virtualElements",a.f);a.b("virtualElements.allowedBindings",a.f.Q);a.b("virtualElements.emptyNode",a.f.ja);a.b("virtualElements.insertAfter",a.f.Bb);a.b("virtualElements.prepend",a.f.Hb);a.b("virtualElements.setDomNodeChildren",a.f.T);(function(){a.J=function(){this.Yb=
{}};a.a.extend(a.J.prototype,{nodeHasBindings:function(b){switch(b.nodeType){case 1:return null!=b.getAttribute("data-bind")||a.g.getComponentNameForNode(b);case 8:return a.f.gc(b);default:return!1}},getBindings:function(b,d){var c=this.getBindingsString(b,d),c=c?this.parseBindingsString(c,d,b):null;return a.g.mb(c,b,d,!1)},getBindingAccessors:function(b,d){var c=this.getBindingsString(b,d),c=c?this.parseBindingsString(c,d,b,{valueAccessors:!0}):null;return a.g.mb(c,b,d,!0)},getBindingsString:function(b){switch(b.nodeType){case 1:return b.getAttribute("data-bind");
case 8:return a.f.xc(b);default:return null}},parseBindingsString:function(b,d,c,e){try{var g=this.Yb,h=b+(e&&e.valueAccessors||""),k;if(!(k=g[h])){var f,m="with($context){with($data||{}){return{"+a.h.ya(b,e)+"}}}";f=new Function("$context","$element",m);k=g[h]=f}return k(d,c)}catch(l){throw l.message="Unable to parse bindings.\nBindings value: "+b+"\nMessage: "+l.message,l;}}});a.J.instance=new a.J})();a.b("bindingProvider",a.J);(function(){function b(a){return function(){return a}}function d(a){return a()}
function c(b){return a.a.na(a.k.B(b),function(a,c){return function(){return b()[c]}})}function e(a,b){return c(this.getBindings.bind(this,a,b))}function g(b,c,d){var f,e=a.f.firstChild(c),k=a.J.instance,g=k.preprocessNode;if(g){for(;f=e;)e=a.f.nextSibling(f),g.call(k,f);e=a.f.firstChild(c)}for(;f=e;)e=a.f.nextSibling(f),h(b,f,d)}function h(b,c,d){var e=!0,k=1===c.nodeType;k&&a.f.Fb(c);if(k&&d||a.J.instance.nodeHasBindings(c))e=f(c,null,b,d).shouldBindDescendants;e&&!l[a.a.t(c)]&&g(b,c,!k)}function k(b){var c=
[],d={},f=[];a.a.G(b,function y(e){if(!d[e]){var k=a.getBindingHandler(e);k&&(k.after&&(f.push(e),a.a.u(k.after,function(c){if(b[c]){if(-1!==a.a.m(f,c))throw Error("Cannot combine the following bindings, because they have a cyclic dependency: "+f.join(", "));y(c)}}),f.length--),c.push({key:e,zb:k}));d[e]=!0}});return c}function f(b,c,f,g){var m=a.a.e.get(b,q);if(!c){if(m)throw Error("You cannot apply bindings multiple times to the same element.");a.a.e.set(b,q,!0)}!m&&g&&a.Ob(b,f);var l;if(c&&"function"!==
typeof c)l=c;else{var h=a.J.instance,n=h.getBindingAccessors||e,s=a.j(function(){(l=c?c(f,b):n.call(h,b,f))&&f.I&&f.I();return l},null,{o:b});l&&s.Z()||(s=null)}var v;if(l){var w=s?function(a){return function(){return d(s()[a])}}:function(a){return l[a]},A=function(){return a.a.na(s?s():l,d)};A.get=function(a){return l[a]&&d(w(a))};A.has=function(a){return a in l};g=k(l);a.a.u(g,function(c){var d=c.zb.init,e=c.zb.update,k=c.key;if(8===b.nodeType&&!a.f.Q[k])throw Error("The binding '"+k+"' cannot be used with virtual elements");
try{"function"==typeof d&&a.k.B(function(){var a=d(b,w(k),A,f.$data,f);if(a&&a.controlsDescendantBindings){if(v!==p)throw Error("Multiple bindings ("+v+" and "+k+") are trying to control descendant bindings of the same element. You cannot use these bindings together on the same element.");v=k}}),"function"==typeof e&&a.j(function(){e(b,w(k),A,f.$data,f)},null,{o:b})}catch(g){throw g.message='Unable to process binding "'+k+": "+l[k]+'"\nMessage: '+g.message,g;}})}return{shouldBindDescendants:v===p}}
function m(b){return b&&b instanceof a.N?b:new a.N(b)}a.d={};var l={script:!0};a.getBindingHandler=function(b){return a.d[b]};a.N=function(b,c,d,f){var e=this,k="function"==typeof b&&!a.C(b),g,m=a.j(function(){var g=k?b():b,l=a.a.c(g);c?(c.I&&c.I(),a.a.extend(e,c),m&&(e.I=m)):(e.$parents=[],e.$root=l,e.ko=a);e.$rawData=g;e.$data=l;d&&(e[d]=l);f&&f(e,c,l);return e.$data},null,{Ia:function(){return g&&!a.a.ob(g)},o:!0});m.Z()&&(e.I=m,m.equalityComparer=null,g=[],m.Tb=function(b){g.push(b);a.a.w.da(b,
function(b){a.a.ua(g,b);g.length||(m.K(),e.I=m=p)})})};a.N.prototype.createChildContext=function(b,c,d){return new a.N(b,this,c,function(a,b){a.$parentContext=b;a.$parent=b.$data;a.$parents=(b.$parents||[]).slice(0);a.$parents.unshift(a.$parent);d&&d(a)})};a.N.prototype.extend=function(b){return new a.N(this.I||this.$data,this,null,function(c,d){c.$rawData=d.$rawData;a.a.extend(c,"function"==typeof b?b():b)})};var q=a.a.e.F(),n=a.a.e.F();a.Ob=function(b,c){if(2==arguments.length)a.a.e.set(b,n,c),
c.I&&c.I.Tb(b);else return a.a.e.get(b,n)};a.ra=function(b,c,d){1===b.nodeType&&a.f.Fb(b);return f(b,c,m(d),!0)};a.Wb=function(d,f,e){e=m(e);return a.ra(d,"function"===typeof f?c(f.bind(null,e,d)):a.a.na(f,b),e)};a.Ca=function(a,b){1!==b.nodeType&&8!==b.nodeType||g(m(a),b,!0)};a.pb=function(a,b){!w&&s.jQuery&&(w=s.jQuery);if(b&&1!==b.nodeType&&8!==b.nodeType)throw Error("ko.applyBindings: first parameter should be your view model; second parameter should be a DOM node");b=b||s.document.body;h(m(a),
b,!0)};a.Ha=function(b){switch(b.nodeType){case 1:case 8:var c=a.Ob(b);if(c)return c;if(b.parentNode)return a.Ha(b.parentNode)}return p};a.$b=function(b){return(b=a.Ha(b))?b.$data:p};a.b("bindingHandlers",a.d);a.b("applyBindings",a.pb);a.b("applyBindingsToDescendants",a.Ca);a.b("applyBindingAccessorsToNode",a.ra);a.b("applyBindingsToNode",a.Wb);a.b("contextFor",a.Ha);a.b("dataFor",a.$b)})();(function(b){function d(d,f){var e=g.hasOwnProperty(d)?g[d]:b,l;e||(e=g[d]=new a.P,c(d,function(a){h[d]=a;delete g[d];
l?e.notifySubscribers(a):setTimeout(function(){e.notifySubscribers(a)},0)}),l=!0);e.U(f)}function c(a,b){e("getConfig",[a],function(c){c?e("loadComponent",[a,c],function(a){b(a)}):b(null)})}function e(c,d,g,l){l||(l=a.g.loaders.slice(0));var h=l.shift();if(h){var n=h[c];if(n){var t=!1;if(n.apply(h,d.concat(function(a){t?g(null):null!==a?g(a):e(c,d,g,l)}))!==b&&(t=!0,!h.suppressLoaderExceptions))throw Error("Component loaders must supply values by invoking the callback, not by returning values synchronously.");
}else e(c,d,g,l)}else g(null)}var g={},h={};a.g={get:function(a,c){var e=h.hasOwnProperty(a)?h[a]:b;e?setTimeout(function(){c(e)},0):d(a,c)},tb:function(a){delete h[a]},jb:e};a.g.loaders=[];a.b("components",a.g);a.b("components.get",a.g.get);a.b("components.clearCachedDefinition",a.g.tb)})();(function(){function b(b,c,d,e){function k(){0===--u&&e(h)}var h={},u=2,r=d.template;d=d.viewModel;r?g(c,r,function(c){a.g.jb("loadTemplate",[b,c],function(a){h.template=a;k()})}):k();d?g(c,d,function(c){a.g.jb("loadViewModel",
[b,c],function(a){h[f]=a;k()})}):k()}function d(a,b,c){if("function"===typeof b)c(function(a){return new b(a)});else if("function"===typeof b[f])c(b[f]);else if("instance"in b){var e=b.instance;c(function(){return e})}else"viewModel"in b?d(a,b.viewModel,c):a("Unknown viewModel value: "+b)}function c(b){switch(a.a.t(b)){case "script":return a.a.ba(b.text);case "textarea":return a.a.ba(b.value);case "template":if(e(b.content))return a.a.ia(b.content.childNodes)}return a.a.ia(b.childNodes)}function e(a){return s.DocumentFragment?
a instanceof DocumentFragment:a&&11===a.nodeType}function g(a,b,c){"string"===typeof b.require?N||s.require?(N||s.require)([b.require],c):a("Uses require, but no AMD loader is present"):c(b)}function h(a){return function(b){throw Error("Component '"+a+"': "+b);}}var k={};a.g.tc=function(b,c){if(!c)throw Error("Invalid configuration for "+b);if(a.g.Qa(b))throw Error("Component "+b+" is already registered");k[b]=c};a.g.Qa=function(a){return a in k};a.g.wc=function(b){delete k[b];a.g.tb(b)};a.g.ub={getConfig:function(a,
b){b(k.hasOwnProperty(a)?k[a]:null)},loadComponent:function(a,c,d){var e=h(a);g(e,c,function(c){b(a,e,c,d)})},loadTemplate:function(b,d,f){b=h(b);if("string"===typeof d)f(a.a.ba(d));else if(d instanceof Array)f(d);else if(e(d))f(a.a.S(d.childNodes));else if(d.element)if(d=d.element,s.HTMLElement?d instanceof HTMLElement:d&&d.tagName&&1===d.nodeType)f(c(d));else if("string"===typeof d){var k=v.getElementById(d);k?f(c(k)):b("Cannot find element with ID "+d)}else b("Unknown element type: "+d);else b("Unknown template value: "+
d)},loadViewModel:function(a,b,c){d(h(a),b,c)}};var f="createViewModel";a.b("components.register",a.g.tc);a.b("components.isRegistered",a.g.Qa);a.b("components.unregister",a.g.wc);a.b("components.defaultLoader",a.g.ub);a.g.loaders.push(a.g.ub);a.g.Ub=k})();(function(){function b(b,e){var g=b.getAttribute("params");if(g){var g=d.parseBindingsString(g,e,b,{valueAccessors:!0,bindingParams:!0}),g=a.a.na(g,function(d){return a.s(d,null,{o:b})}),h=a.a.na(g,function(d){return d.Z()?a.s(function(){return a.a.c(d())},
null,{o:b}):d.v()});h.hasOwnProperty("$raw")||(h.$raw=g);return h}return{$raw:{}}}a.g.getComponentNameForNode=function(b){b=a.a.t(b);return a.g.Qa(b)&&b};a.g.mb=function(c,d,g,h){if(1===d.nodeType){var k=a.g.getComponentNameForNode(d);if(k){c=c||{};if(c.component)throw Error('Cannot use the "component" binding on a custom element matching a component');var f={name:k,params:b(d,g)};c.component=h?function(){return f}:f}}return c};var d=new a.J;9>a.a.L&&(a.g.register=function(a){return function(b){v.createElement(b);
return a.apply(this,arguments)}}(a.g.register),v.createDocumentFragment=function(b){return function(){var d=b(),g=a.g.Ub,h;for(h in g)g.hasOwnProperty(h)&&d.createElement(h);return d}}(v.createDocumentFragment))})();(function(){var b=0;a.d.component={init:function(d,c,e,g,h){function k(){var a=f&&f.dispose;"function"===typeof a&&a.call(f);m=null}var f,m;a.a.w.da(d,k);a.s(function(){var e=a.a.c(c()),g,n;"string"===typeof e?g=e:(g=a.a.c(e.name),n=a.a.c(e.params));if(!g)throw Error("No component name specified");
var t=m=++b;a.g.get(g,function(b){if(m===t){k();if(!b)throw Error("Unknown component '"+g+"'");var c=b.template;if(!c)throw Error("Component '"+g+"' has no template");c=a.a.ia(c);a.f.T(d,c);var c=n,e=b.createViewModel;b=e?e.call(b,c,{element:d}):c;c=h.createChildContext(b);f=b;a.Ca(c,d)}})},null,{o:d});return{controlsDescendantBindings:!0}}};a.f.Q.component=!0})();var Q={"class":"className","for":"htmlFor"};a.d.attr={update:function(b,d){var c=a.a.c(d())||{};a.a.G(c,function(c,d){d=a.a.c(d);var h=
!1===d||null===d||d===p;h&&b.removeAttribute(c);8>=a.a.L&&c in Q?(c=Q[c],h?b.removeAttribute(c):b[c]=d):h||b.setAttribute(c,d.toString());"name"===c&&a.a.Mb(b,h?"":d.toString())})}};(function(){a.d.checked={after:["value","attr"],init:function(b,d,c){function e(){var e=b.checked,k=q?h():e;if(!a.Y.ma()&&(!f||e)){var g=a.k.B(d);m?l!==k?(e&&(a.a.ea(g,k,!0),a.a.ea(g,l,!1)),l=k):a.a.ea(g,k,e):a.h.pa(g,c,"checked",k,!0)}}function g(){var c=a.a.c(d());b.checked=m?0<=a.a.m(c,h()):k?c:h()===c}var h=a.Ib(function(){return c.has("checkedValue")?
a.a.c(c.get("checkedValue")):c.has("value")?a.a.c(c.get("value")):b.value}),k="checkbox"==b.type,f="radio"==b.type;if(k||f){var m=k&&a.a.c(d())instanceof Array,l=m?h():p,q=f||m;f&&!b.name&&a.d.uniqueName.init(b,function(){return!0});a.s(e,null,{o:b});a.a.n(b,"click",e);a.s(g,null,{o:b})}}};a.h.V.checked=!0;a.d.checkedValue={update:function(b,d){b.value=a.a.c(d())}}})();a.d.css={update:function(b,d){var c=a.a.c(d());"object"==typeof c?a.a.G(c,function(c,d){d=a.a.c(d);a.a.Ba(b,c,d)}):(c=String(c||""),
a.a.Ba(b,b.__ko__cssValue,!1),b.__ko__cssValue=c,a.a.Ba(b,c,!0))}};a.d.enable={update:function(b,d){var c=a.a.c(d());c&&b.disabled?b.removeAttribute("disabled"):c||b.disabled||(b.disabled=!0)}};a.d.disable={update:function(b,d){a.d.enable.update(b,function(){return!a.a.c(d())})}};a.d.event={init:function(b,d,c,e,g){var h=d()||{};a.a.G(h,function(k){"string"==typeof k&&a.a.n(b,k,function(b){var h,l=d()[k];if(l){try{var q=a.a.S(arguments);e=g.$data;q.unshift(e);h=l.apply(e,q)}finally{!0!==h&&(b.preventDefault?
b.preventDefault():b.returnValue=!1)}!1===c.get(k+"Bubble")&&(b.cancelBubble=!0,b.stopPropagation&&b.stopPropagation())}})})}};a.d.foreach={Eb:function(b){return function(){var d=b(),c=a.a.Xa(d);if(!c||"number"==typeof c.length)return{foreach:d,templateEngine:a.O.Oa};a.a.c(d);return{foreach:c.data,as:c.as,includeDestroyed:c.includeDestroyed,afterAdd:c.afterAdd,beforeRemove:c.beforeRemove,afterRender:c.afterRender,beforeMove:c.beforeMove,afterMove:c.afterMove,templateEngine:a.O.Oa}}},init:function(b,
d){return a.d.template.init(b,a.d.foreach.Eb(d))},update:function(b,d,c,e,g){return a.d.template.update(b,a.d.foreach.Eb(d),c,e,g)}};a.h.ha.foreach=!1;a.f.Q.foreach=!0;a.d.hasfocus={init:function(b,d,c){function e(e){b.__ko_hasfocusUpdating=!0;var f=b.ownerDocument;if("activeElement"in f){var g;try{g=f.activeElement}catch(h){g=f.body}e=g===b}f=d();a.h.pa(f,c,"hasfocus",e,!0);b.__ko_hasfocusLastValue=e;b.__ko_hasfocusUpdating=!1}var g=e.bind(null,!0),h=e.bind(null,!1);a.a.n(b,"focus",g);a.a.n(b,"focusin",
g);a.a.n(b,"blur",h);a.a.n(b,"focusout",h)},update:function(b,d){var c=!!a.a.c(d());b.__ko_hasfocusUpdating||b.__ko_hasfocusLastValue===c||(c?b.focus():b.blur(),a.k.B(a.a.oa,null,[b,c?"focusin":"focusout"]))}};a.h.V.hasfocus=!0;a.d.hasFocus=a.d.hasfocus;a.h.V.hasFocus=!0;a.d.html={init:function(){return{controlsDescendantBindings:!0}},update:function(b,d){a.a.$a(b,d())}};I("if");I("ifnot",!1,!0);I("with",!0,!1,function(a,d){return a.createChildContext(d)});var K={};a.d.options={init:function(b){if("select"!==
a.a.t(b))throw Error("options binding applies only to SELECT elements");for(;0<b.length;)b.remove(0);return{controlsDescendantBindings:!0}},update:function(b,d,c){function e(){return a.a.ta(b.options,function(a){return a.selected})}function g(a,b,c){var d=typeof b;return"function"==d?b(a):"string"==d?a[b]:c}function h(c,d){if(q.length){var e=0<=a.a.m(q,a.i.q(d[0]));a.a.Nb(d[0],e);n&&!e&&a.k.B(a.a.oa,null,[b,"change"])}}var k=0!=b.length&&b.multiple?b.scrollTop:null,f=a.a.c(d()),m=c.get("optionsIncludeDestroyed");
d={};var l,q;q=b.multiple?a.a.Da(e(),a.i.q):0<=b.selectedIndex?[a.i.q(b.options[b.selectedIndex])]:[];f&&("undefined"==typeof f.length&&(f=[f]),l=a.a.ta(f,function(b){return m||b===p||null===b||!a.a.c(b._destroy)}),c.has("optionsCaption")&&(f=a.a.c(c.get("optionsCaption")),null!==f&&f!==p&&l.unshift(K)));var n=!1;d.beforeRemove=function(a){b.removeChild(a)};f=h;c.has("optionsAfterRender")&&(f=function(b,d){h(0,d);a.k.B(c.get("optionsAfterRender"),null,[d[0],b!==K?b:p])});a.a.Za(b,l,function(d,e,f){f.length&&
(q=f[0].selected?[a.i.q(f[0])]:[],n=!0);e=b.ownerDocument.createElement("option");d===K?(a.a.bb(e,c.get("optionsCaption")),a.i.ca(e,p)):(f=g(d,c.get("optionsValue"),d),a.i.ca(e,a.a.c(f)),d=g(d,c.get("optionsText"),f),a.a.bb(e,d));return[e]},d,f);a.k.B(function(){c.get("valueAllowUnset")&&c.has("value")?a.i.ca(b,a.a.c(c.get("value")),!0):(b.multiple?q.length&&e().length<q.length:q.length&&0<=b.selectedIndex?a.i.q(b.options[b.selectedIndex])!==q[0]:q.length||0<=b.selectedIndex)&&a.a.oa(b,"change")});
a.a.dc(b);k&&20<Math.abs(k-b.scrollTop)&&(b.scrollTop=k)}};a.d.options.Va=a.a.e.F();a.d.selectedOptions={after:["options","foreach"],init:function(b,d,c){a.a.n(b,"change",function(){var e=d(),g=[];a.a.u(b.getElementsByTagName("option"),function(b){b.selected&&g.push(a.i.q(b))});a.h.pa(e,c,"selectedOptions",g)})},update:function(b,d){if("select"!=a.a.t(b))throw Error("values binding applies only to SELECT elements");var c=a.a.c(d());c&&"number"==typeof c.length&&a.a.u(b.getElementsByTagName("option"),
function(b){var d=0<=a.a.m(c,a.i.q(b));a.a.Nb(b,d)})}};a.h.V.selectedOptions=!0;a.d.style={update:function(b,d){var c=a.a.c(d()||{});a.a.G(c,function(c,d){d=a.a.c(d);if(null===d||d===p||!1===d)d="";b.style[c]=d})}};a.d.submit={init:function(b,d,c,e,g){if("function"!=typeof d())throw Error("The value for a submit binding must be a function");a.a.n(b,"submit",function(a){var c,e=d();try{c=e.call(g.$data,b)}finally{!0!==c&&(a.preventDefault?a.preventDefault():a.returnValue=!1)}})}};a.d.text={init:function(){return{controlsDescendantBindings:!0}},
update:function(b,d){a.a.bb(b,d())}};a.f.Q.text=!0;(function(){if(s&&s.navigator)var b=function(a){if(a)return parseFloat(a[1])},d=s.opera&&s.opera.version&&parseInt(s.opera.version()),c=s.navigator.userAgent,e=b(c.match(/^(?:(?!chrome).)*version\/([^ ]*) safari/i)),g=b(c.match(/Firefox\/([^ ]*)/));if(10>a.a.L)var h=a.a.e.F(),k=a.a.e.F(),f=function(b){var c=this.activeElement;(c=c&&a.a.e.get(c,k))&&c(b)},m=function(b,c){var d=b.ownerDocument;a.a.e.get(d,h)||(a.a.e.set(d,h,!0),a.a.n(d,"selectionchange",
f));a.a.e.set(b,k,c)};a.d.textInput={init:function(b,c,f){function k(c,d){a.a.n(b,c,d)}function h(){var d=a.a.c(c());if(null===d||d===p)d="";v!==p&&d===v?setTimeout(h,4):b.value!==d&&(s=d,b.value=d)}function u(){y||(v=b.value,y=setTimeout(r,4))}function r(){clearTimeout(y);v=y=p;var d=b.value;s!==d&&(s=d,a.h.pa(c(),f,"textInput",d))}var s=b.value,y,v;10>a.a.L?(k("propertychange",function(a){"value"===a.propertyName&&r()}),8==a.a.L&&(k("keyup",r),k("keydown",r)),8<=a.a.L&&(m(b,r),k("dragend",u))):
(k("input",r),5>e&&"textarea"===a.a.t(b)?(k("keydown",u),k("paste",u),k("cut",u)):11>d?k("keydown",u):4>g&&(k("DOMAutoComplete",r),k("dragdrop",r),k("drop",r)));k("change",r);a.s(h,null,{o:b})}};a.h.V.textInput=!0;a.d.textinput={preprocess:function(a,b,c){c("textInput",a)}}})();a.d.uniqueName={init:function(b,d){if(d()){var c="ko_unique_"+ ++a.d.uniqueName.Zb;a.a.Mb(b,c)}}};a.d.uniqueName.Zb=0;a.d.value={after:["options","foreach"],init:function(b,d,c){if("input"!=b.tagName.toLowerCase()||"checkbox"!=
b.type&&"radio"!=b.type){var e=["change"],g=c.get("valueUpdate"),h=!1,k=null;g&&("string"==typeof g&&(g=[g]),a.a.ga(e,g),e=a.a.rb(e));var f=function(){k=null;h=!1;var e=d(),f=a.i.q(b);a.h.pa(e,c,"value",f)};!a.a.L||"input"!=b.tagName.toLowerCase()||"text"!=b.type||"off"==b.autocomplete||b.form&&"off"==b.form.autocomplete||-1!=a.a.m(e,"propertychange")||(a.a.n(b,"propertychange",function(){h=!0}),a.a.n(b,"focus",function(){h=!1}),a.a.n(b,"blur",function(){h&&f()}));a.a.u(e,function(c){var d=f;a.a.vc(c,
"after")&&(d=function(){k=a.i.q(b);setTimeout(f,0)},c=c.substring(5));a.a.n(b,c,d)});var m=function(){var e=a.a.c(d()),f=a.i.q(b);if(null!==k&&e===k)setTimeout(m,0);else if(e!==f)if("select"===a.a.t(b)){var g=c.get("valueAllowUnset"),f=function(){a.i.ca(b,e,g)};f();g||e===a.i.q(b)?setTimeout(f,0):a.k.B(a.a.oa,null,[b,"change"])}else a.i.ca(b,e)};a.s(m,null,{o:b})}else a.ra(b,{checkedValue:d})},update:function(){}};a.h.V.value=!0;a.d.visible={update:function(b,d){var c=a.a.c(d()),e="none"!=b.style.display;
c&&!e?b.style.display="":!c&&e&&(b.style.display="none")}};(function(b){a.d[b]={init:function(d,c,e,g,h){return a.d.event.init.call(this,d,function(){var a={};a[b]=c();return a},e,g,h)}}})("click");a.H=function(){};a.H.prototype.renderTemplateSource=function(){throw Error("Override renderTemplateSource");};a.H.prototype.createJavaScriptEvaluatorBlock=function(){throw Error("Override createJavaScriptEvaluatorBlock");};a.H.prototype.makeTemplateSource=function(b,d){if("string"==typeof b){d=d||v;var c=
d.getElementById(b);if(!c)throw Error("Cannot find template with ID "+b);return new a.r.l(c)}if(1==b.nodeType||8==b.nodeType)return new a.r.fa(b);throw Error("Unknown template type: "+b);};a.H.prototype.renderTemplate=function(a,d,c,e){a=this.makeTemplateSource(a,e);return this.renderTemplateSource(a,d,c)};a.H.prototype.isTemplateRewritten=function(a,d){return!1===this.allowTemplateRewriting?!0:this.makeTemplateSource(a,d).data("isRewritten")};a.H.prototype.rewriteTemplate=function(a,d,c){a=this.makeTemplateSource(a,
c);d=d(a.text());a.text(d);a.data("isRewritten",!0)};a.b("templateEngine",a.H);a.fb=function(){function b(b,c,d,k){b=a.h.Wa(b);for(var f=a.h.ha,m=0;m<b.length;m++){var l=b[m].key;if(f.hasOwnProperty(l)){var q=f[l];if("function"===typeof q){if(l=q(b[m].value))throw Error(l);}else if(!q)throw Error("This template engine does not support the '"+l+"' binding within its templates");}}d="ko.__tr_ambtns(function($context,$element){return(function(){return{ "+a.h.ya(b,{valueAccessors:!0})+" } })()},'"+d.toLowerCase()+
"')";return k.createJavaScriptEvaluatorBlock(d)+c}var d=/(<([a-z]+\d*)(?:\s+(?!data-bind\s*=\s*)[a-z0-9\-]+(?:=(?:\"[^\"]*\"|\'[^\']*\'))?)*\s+)data-bind\s*=\s*(["'])([\s\S]*?)\3/gi,c=/\x3c!--\s*ko\b\s*([\s\S]*?)\s*--\x3e/g;return{ec:function(b,c,d){c.isTemplateRewritten(b,d)||c.rewriteTemplate(b,function(b){return a.fb.nc(b,c)},d)},nc:function(a,g){return a.replace(d,function(a,c,d,e,l){return b(l,c,d,g)}).replace(c,function(a,c){return b(c,"\x3c!-- ko --\x3e","#comment",g)})},Xb:function(b,c){return a.D.Ua(function(d,
k){var f=d.nextSibling;f&&f.nodeName.toLowerCase()===c&&a.ra(f,b,k)})}}}();a.b("__tr_ambtns",a.fb.Xb);(function(){a.r={};a.r.l=function(a){this.l=a};a.r.l.prototype.text=function(){var b=a.a.t(this.l),b="script"===b?"text":"textarea"===b?"value":"innerHTML";if(0==arguments.length)return this.l[b];var d=arguments[0];"innerHTML"===b?a.a.$a(this.l,d):this.l[b]=d};var b=a.a.e.F()+"_";a.r.l.prototype.data=function(c){if(1===arguments.length)return a.a.e.get(this.l,b+c);a.a.e.set(this.l,b+c,arguments[1])};
var d=a.a.e.F();a.r.fa=function(a){this.l=a};a.r.fa.prototype=new a.r.l;a.r.fa.prototype.text=function(){if(0==arguments.length){var b=a.a.e.get(this.l,d)||{};b.gb===p&&b.Ga&&(b.gb=b.Ga.innerHTML);return b.gb}a.a.e.set(this.l,d,{gb:arguments[0]})};a.r.l.prototype.nodes=function(){if(0==arguments.length)return(a.a.e.get(this.l,d)||{}).Ga;a.a.e.set(this.l,d,{Ga:arguments[0]})};a.b("templateSources",a.r);a.b("templateSources.domElement",a.r.l);a.b("templateSources.anonymousTemplate",a.r.fa)})();(function(){function b(b,
c,d){var e;for(c=a.f.nextSibling(c);b&&(e=b)!==c;)b=a.f.nextSibling(e),d(e,b)}function d(c,d){if(c.length){var e=c[0],g=c[c.length-1],h=e.parentNode,n=a.J.instance,t=n.preprocessNode;if(t){b(e,g,function(a,b){var c=a.previousSibling,d=t.call(n,a);d&&(a===e&&(e=d[0]||b),a===g&&(g=d[d.length-1]||c))});c.length=0;if(!e)return;e===g?c.push(e):(c.push(e,g),a.a.ka(c,h))}b(e,g,function(b){1!==b.nodeType&&8!==b.nodeType||a.pb(d,b)});b(e,g,function(b){1!==b.nodeType&&8!==b.nodeType||a.D.Sb(b,[d])});a.a.ka(c,
h)}}function c(a){return a.nodeType?a:0<a.length?a[0]:null}function e(b,e,h,l,q){q=q||{};var n=b&&c(b),n=n&&n.ownerDocument,t=q.templateEngine||g;a.fb.ec(h,t,n);h=t.renderTemplate(h,l,q,n);if("number"!=typeof h.length||0<h.length&&"number"!=typeof h[0].nodeType)throw Error("Template engine must return an array of DOM nodes");n=!1;switch(e){case "replaceChildren":a.f.T(b,h);n=!0;break;case "replaceNode":a.a.Lb(b,h);n=!0;break;case "ignoreTargetNode":break;default:throw Error("Unknown renderMode: "+
e);}n&&(d(h,l),q.afterRender&&a.k.B(q.afterRender,null,[h,l.$data]));return h}var g;a.ab=function(b){if(b!=p&&!(b instanceof a.H))throw Error("templateEngine must inherit from ko.templateEngine");g=b};a.Ya=function(b,d,h,l,q){h=h||{};if((h.templateEngine||g)==p)throw Error("Set a template engine before calling renderTemplate");q=q||"replaceChildren";if(l){var n=c(l);return a.j(function(){var g=d&&d instanceof a.N?d:new a.N(a.a.c(d)),p=a.C(b)?b():"function"===typeof b?b(g.$data,g):b,g=e(l,q,p,g,h);
"replaceNode"==q&&(l=g,n=c(l))},null,{Ia:function(){return!n||!a.a.Ja(n)},o:n&&"replaceNode"==q?n.parentNode:n})}return a.D.Ua(function(c){a.Ya(b,d,h,c,"replaceNode")})};a.uc=function(b,c,g,h,q){function n(a,b){d(b,s);g.afterRender&&g.afterRender(b,a)}function t(c,d){s=q.createChildContext(c,g.as,function(a){a.$index=d});var f=a.C(b)?b():"function"===typeof b?b(c,s):b;return e(null,"ignoreTargetNode",f,s,g)}var s;return a.j(function(){var b=a.a.c(c)||[];"undefined"==typeof b.length&&(b=[b]);b=a.a.ta(b,
function(b){return g.includeDestroyed||b===p||null===b||!a.a.c(b._destroy)});a.k.B(a.a.Za,null,[h,b,t,g,n])},null,{o:h})};var h=a.a.e.F();a.d.template={init:function(b,c){var d=a.a.c(c());"string"==typeof d||d.name?a.f.ja(b):(d=a.f.childNodes(b),d=a.a.oc(d),(new a.r.fa(b)).nodes(d));return{controlsDescendantBindings:!0}},update:function(b,c,d,e,g){var n=c(),t;c=a.a.c(n);d=!0;e=null;"string"==typeof c?c={}:(n=c.name,"if"in c&&(d=a.a.c(c["if"])),d&&"ifnot"in c&&(d=!a.a.c(c.ifnot)),t=a.a.c(c.data));
"foreach"in c?e=a.uc(n||b,d&&c.foreach||[],c,b,g):d?(g="data"in c?g.createChildContext(t,c.as):g,e=a.Ya(n||b,g,c,b)):a.f.ja(b);g=e;(t=a.a.e.get(b,h))&&"function"==typeof t.K&&t.K();a.a.e.set(b,h,g&&g.Z()?g:p)}};a.h.ha.template=function(b){b=a.h.Wa(b);return 1==b.length&&b[0].unknown||a.h.lc(b,"name")?null:"This template engine does not support anonymous templates nested within its templates"};a.f.Q.template=!0})();a.b("setTemplateEngine",a.ab);a.b("renderTemplate",a.Ya);a.a.wb=function(a,d,c){if(a.length&&
d.length){var e,g,h,k,f;for(e=g=0;(!c||e<c)&&(k=a[g]);++g){for(h=0;f=d[h];++h)if(k.value===f.value){k.moved=f.index;f.moved=k.index;d.splice(h,1);e=h=0;break}e+=h}}};a.a.Fa=function(){function b(b,c,e,g,h){var k=Math.min,f=Math.max,m=[],l,q=b.length,n,p=c.length,s=p-q||1,u=q+p+1,r,v,w;for(l=0;l<=q;l++)for(v=r,m.push(r=[]),w=k(p,l+s),n=f(0,l-1);n<=w;n++)r[n]=n?l?b[l-1]===c[n-1]?v[n-1]:k(v[n]||u,r[n-1]||u)+1:n+1:l+1;k=[];f=[];s=[];l=q;for(n=p;l||n;)p=m[l][n]-1,n&&p===m[l][n-1]?f.push(k[k.length]={status:e,
value:c[--n],index:n}):l&&p===m[l-1][n]?s.push(k[k.length]={status:g,value:b[--l],index:l}):(--n,--l,h.sparse||k.push({status:"retained",value:c[n]}));a.a.wb(f,s,10*q);return k.reverse()}return function(a,c,e){e="boolean"===typeof e?{dontLimitMoves:e}:e||{};a=a||[];c=c||[];return a.length<=c.length?b(a,c,"added","deleted",e):b(c,a,"deleted","added",e)}}();a.b("utils.compareArrays",a.a.Fa);(function(){function b(b,d,g,h,k){var f=[],m=a.j(function(){var l=d(g,k,a.a.ka(f,b))||[];0<f.length&&(a.a.Lb(f,
l),h&&a.k.B(h,null,[g,l,k]));f.length=0;a.a.ga(f,l)},null,{o:b,Ia:function(){return!a.a.ob(f)}});return{$:f,j:m.Z()?m:p}}var d=a.a.e.F();a.a.Za=function(c,e,g,h,k){function f(b,d){x=q[d];r!==d&&(A[b]=x);x.Na(r++);a.a.ka(x.$,c);s.push(x);w.push(x)}function m(b,c){if(b)for(var d=0,e=c.length;d<e;d++)c[d]&&a.a.u(c[d].$,function(a){b(a,d,c[d].sa)})}e=e||[];h=h||{};var l=a.a.e.get(c,d)===p,q=a.a.e.get(c,d)||[],n=a.a.Da(q,function(a){return a.sa}),t=a.a.Fa(n,e,h.dontLimitMoves),s=[],u=0,r=0,v=[],w=[];e=
[];for(var A=[],n=[],x,B=0,D,F;D=t[B];B++)switch(F=D.moved,D.status){case "deleted":F===p&&(x=q[u],x.j&&x.j.K(),v.push.apply(v,a.a.ka(x.$,c)),h.beforeRemove&&(e[B]=x,w.push(x)));u++;break;case "retained":f(B,u++);break;case "added":F!==p?f(B,F):(x={sa:D.value,Na:a.p(r++)},s.push(x),w.push(x),l||(n[B]=x))}m(h.beforeMove,A);a.a.u(v,h.beforeRemove?a.R:a.removeNode);for(var B=0,l=a.f.firstChild(c),G;x=w[B];B++){x.$||a.a.extend(x,b(c,g,x.sa,k,x.Na));for(u=0;t=x.$[u];l=t.nextSibling,G=t,u++)t!==l&&a.f.Bb(c,
t,G);!x.ic&&k&&(k(x.sa,x.$,x.Na),x.ic=!0)}m(h.beforeRemove,e);m(h.afterMove,A);m(h.afterAdd,n);a.a.e.set(c,d,s)}})();a.b("utils.setDomNodeChildrenFromArrayMapping",a.a.Za);a.O=function(){this.allowTemplateRewriting=!1};a.O.prototype=new a.H;a.O.prototype.renderTemplateSource=function(b){var d=(9>a.a.L?0:b.nodes)?b.nodes():null;if(d)return a.a.S(d.cloneNode(!0).childNodes);b=b.text();return a.a.ba(b)};a.O.Oa=new a.O;a.ab(a.O.Oa);a.b("nativeTemplateEngine",a.O);(function(){a.Sa=function(){var a=this.kc=
function(){if(!w||!w.tmpl)return 0;try{if(0<=w.tmpl.tag.tmpl.open.toString().indexOf("__"))return 2}catch(a){}return 1}();this.renderTemplateSource=function(b,e,g){g=g||{};if(2>a)throw Error("Your version of jQuery.tmpl is too old. Please upgrade to jQuery.tmpl 1.0.0pre or later.");var h=b.data("precompiled");h||(h=b.text()||"",h=w.template(null,"{{ko_with $item.koBindingContext}}"+h+"{{/ko_with}}"),b.data("precompiled",h));b=[e.$data];e=w.extend({koBindingContext:e},g.templateOptions);e=w.tmpl(h,
b,e);e.appendTo(v.createElement("div"));w.fragments={};return e};this.createJavaScriptEvaluatorBlock=function(a){return"{{ko_code ((function() { return "+a+" })()) }}"};this.addTemplate=function(a,b){v.write("<script type='text/html' id='"+a+"'>"+b+"\x3c/script>")};0<a&&(w.tmpl.tag.ko_code={open:"__.push($1 || '');"},w.tmpl.tag.ko_with={open:"with($1) {",close:"} "})};a.Sa.prototype=new a.H;var b=new a.Sa;0<b.kc&&a.ab(b);a.b("jqueryTmplTemplateEngine",a.Sa)})()})})();})();

/**
 * bootbox.js [v4.3.0]
 *
 * http://bootboxjs.com/license.txt
 */

// @see https://github.com/makeusabrew/bootbox/issues/180
// @see https://github.com/makeusabrew/bootbox/issues/186
(function (root, factory) {

  "use strict";
  if (typeof define === "function" && define.amd) {
    // AMD. Register as an anonymous module.
    define(["jquery"], factory);
  } else if (typeof exports === "object") {
    // Node. Does not work with strict CommonJS, but
    // only CommonJS-like environments that support module.exports,
    // like Node.
    module.exports = factory(require("jquery"));
  } else {
    // Browser globals (root is window)
    root.bootbox = factory(root.jQuery);
  }

}(this, function init($, undefined) {

  "use strict";

  // the base DOM structure needed to create a modal
  var templates = {
    dialog:
      "<div class='bootbox modal' tabindex='-1' role='dialog'>" +
        "<div class='modal-dialog'>" +
          "<div class='modal-content'>" +
            "<div class='modal-body'><div class='bootbox-body'></div></div>" +
          "</div>" +
        "</div>" +
      "</div>",
    header:
      "<div class='modal-header'>" +
        "<h4 class='modal-title'></h4>" +
      "</div>",
    footer:
      "<div class='modal-footer'></div>",
    closeButton:
      "<button type='button' class='bootbox-close-button close' data-dismiss='modal' aria-hidden='true'>&times;</button>",
    form:
      "<form class='bootbox-form'></form>",
    inputs: {
      text:
        "<input class='bootbox-input bootbox-input-text form-control' autocomplete=off type=text />",
      textarea:
        "<textarea class='bootbox-input bootbox-input-textarea form-control'></textarea>",
      email:
        "<input class='bootbox-input bootbox-input-email form-control' autocomplete='off' type='email' />",
      select:
        "<select class='bootbox-input bootbox-input-select form-control'></select>",
      checkbox:
        "<div class='checkbox'><label><input class='bootbox-input bootbox-input-checkbox' type='checkbox' /></label></div>",
      date:
        "<input class='bootbox-input bootbox-input-date form-control' autocomplete=off type='date' />",
      time:
        "<input class='bootbox-input bootbox-input-time form-control' autocomplete=off type='time' />",
      number:
        "<input class='bootbox-input bootbox-input-number form-control' autocomplete=off type='number' />",
      password:
        "<input class='bootbox-input bootbox-input-password form-control' autocomplete='off' type='password' />"
    }
  };

  var defaults = {
    // default language
    locale: "en",
    // show backdrop or not
    backdrop: true,
    // animate the modal in/out
    animate: true,
    // additional class string applied to the top level dialog
    className: null,
    // whether or not to include a close button
    closeButton: true,
    // show the dialog immediately by default
    show: true,
    // dialog container
    container: "body"
  };

  // our public object; augmented after our private API
  var exports = {};

  /**
   * @private
   */
  function _t(key) {
    var locale = locales[defaults.locale];
    return locale ? locale[key] : locales.en[key];
  }

  function processCallback(e, dialog, callback) {
    e.stopPropagation();
    e.preventDefault();

    // by default we assume a callback will get rid of the dialog,
    // although it is given the opportunity to override this

    // so, if the callback can be invoked and it *explicitly returns false*
    // then we'll set a flag to keep the dialog active...
    var preserveDialog = $.isFunction(callback) && callback(e) === false;

    // ... otherwise we'll bin it
    if (!preserveDialog) {
      dialog.modal("hide");
    }
  }

  function getKeyLength(obj) {
    // @TODO defer to Object.keys(x).length if available?
    var k, t = 0;
    for (k in obj) {
      t ++;
    }
    return t;
  }

  function each(collection, iterator) {
    var index = 0;
    $.each(collection, function(key, value) {
      iterator(key, value, index++);
    });
  }

  function sanitize(options) {
    var buttons;
    var total;

    if (typeof options !== "object") {
      throw new Error("Please supply an object of options");
    }

    if (!options.message) {
      throw new Error("Please specify a message");
    }

    // make sure any supplied options take precedence over defaults
    options = $.extend({}, defaults, options);

    if (!options.buttons) {
      options.buttons = {};
    }

    // we only support Bootstrap's "static" and false backdrop args
    // supporting true would mean you could dismiss the dialog without
    // explicitly interacting with it
    options.backdrop = options.backdrop ? "static" : false;

    buttons = options.buttons;

    total = getKeyLength(buttons);

    each(buttons, function(key, button, index) {

      if ($.isFunction(button)) {
        // short form, assume value is our callback. Since button
        // isn't an object it isn't a reference either so re-assign it
        button = buttons[key] = {
          callback: button
        };
      }

      // before any further checks make sure by now button is the correct type
      if ($.type(button) !== "object") {
        throw new Error("button with key " + key + " must be an object");
      }

      if (!button.label) {
        // the lack of an explicit label means we'll assume the key is good enough
        button.label = key;
      }

      if (!button.className) {
        if (total <= 2 && index === total-1) {
          // always add a primary to the main option in a two-button dialog
          button.className = "btn-primary";
        } else {
          button.className = "btn-default";
        }
      }
    });

    return options;
  }

  /**
   * map a flexible set of arguments into a single returned object
   * if args.length is already one just return it, otherwise
   * use the properties argument to map the unnamed args to
   * object properties
   * so in the latter case:
   * mapArguments(["foo", $.noop], ["message", "callback"])
   * -> { message: "foo", callback: $.noop }
   */
  function mapArguments(args, properties) {
    var argn = args.length;
    var options = {};

    if (argn < 1 || argn > 2) {
      throw new Error("Invalid argument length");
    }

    if (argn === 2 || typeof args[0] === "string") {
      options[properties[0]] = args[0];
      options[properties[1]] = args[1];
    } else {
      options = args[0];
    }

    return options;
  }

  /**
   * merge a set of default dialog options with user supplied arguments
   */
  function mergeArguments(defaults, args, properties) {
    return $.extend(
      // deep merge
      true,
      // ensure the target is an empty, unreferenced object
      {},
      // the base options object for this type of dialog (often just buttons)
      defaults,
      // args could be an object or array; if it's an array properties will
      // map it to a proper options object
      mapArguments(
        args,
        properties
      )
    );
  }

  /**
   * this entry-level method makes heavy use of composition to take a simple
   * range of inputs and return valid options suitable for passing to bootbox.dialog
   */
  function mergeDialogOptions(className, labels, properties, args) {
    //  build up a base set of dialog properties
    var baseOptions = {
      className: "bootbox-" + className,
      buttons: createLabels.apply(null, labels)
    };

    // ensure the buttons properties generated, *after* merging
    // with user args are still valid against the supplied labels
    return validateButtons(
      // merge the generated base properties with user supplied arguments
      mergeArguments(
        baseOptions,
        args,
        // if args.length > 1, properties specify how each arg maps to an object key
        properties
      ),
      labels
    );
  }

  /**
   * from a given list of arguments return a suitable object of button labels
   * all this does is normalise the given labels and translate them where possible
   * e.g. "ok", "confirm" -> { ok: "OK, cancel: "Annuleren" }
   */
  function createLabels() {
    var buttons = {};

    for (var i = 0, j = arguments.length; i < j; i++) {
      var argument = arguments[i];
      var key = argument.toLowerCase();
      var value = argument.toUpperCase();

      buttons[key] = {
        label: _t(value)
      };
    }

    return buttons;
  }

  function validateButtons(options, buttons) {
    var allowedButtons = {};
    each(buttons, function(key, value) {
      allowedButtons[value] = true;
    });

    each(options.buttons, function(key) {
      if (allowedButtons[key] === undefined) {
        throw new Error("button key " + key + " is not allowed (options are " + buttons.join("\n") + ")");
      }
    });

    return options;
  }

  exports.alert = function() {
    var options;

    options = mergeDialogOptions("alert", ["ok"], ["message", "callback"], arguments);

    if (options.callback && !$.isFunction(options.callback)) {
      throw new Error("alert requires callback property to be a function when provided");
    }

    /**
     * overrides
     */
    options.buttons.ok.callback = options.onEscape = function() {
      if ($.isFunction(options.callback)) {
        return options.callback();
      }
      return true;
    };

    return exports.dialog(options);
  };

  exports.confirm = function() {
    var options;

    options = mergeDialogOptions("confirm", ["cancel", "confirm"], ["message", "callback"], arguments);

    /**
     * overrides; undo anything the user tried to set they shouldn't have
     */
    options.buttons.cancel.callback = options.onEscape = function() {
      return options.callback(false);
    };

    options.buttons.confirm.callback = function() {
      return options.callback(true);
    };

    // confirm specific validation
    if (!$.isFunction(options.callback)) {
      throw new Error("confirm requires a callback");
    }

    return exports.dialog(options);
  };

  exports.prompt = function() {
    var options;
    var defaults;
    var dialog;
    var form;
    var input;
    var shouldShow;
    var inputOptions;

    // we have to create our form first otherwise
    // its value is undefined when gearing up our options
    // @TODO this could be solved by allowing message to
    // be a function instead...
    form = $(templates.form);

    // prompt defaults are more complex than others in that
    // users can override more defaults
    // @TODO I don't like that prompt has to do a lot of heavy
    // lifting which mergeDialogOptions can *almost* support already
    // just because of 'value' and 'inputType' - can we refactor?
    defaults = {
      className: "bootbox-prompt",
      buttons: createLabels("cancel", "confirm"),
      value: "",
      inputType: "text"
    };

    options = validateButtons(
      mergeArguments(defaults, arguments, ["title", "callback"]),
      ["cancel", "confirm"]
    );

    // capture the user's show value; we always set this to false before
    // spawning the dialog to give us a chance to attach some handlers to
    // it, but we need to make sure we respect a preference not to show it
    shouldShow = (options.show === undefined) ? true : options.show;

    /**
     * overrides; undo anything the user tried to set they shouldn't have
     */
    options.message = form;

    options.buttons.cancel.callback = options.onEscape = function() {
      return options.callback(null);
    };

    options.buttons.confirm.callback = function() {
      var value;

      switch (options.inputType) {
        case "text":
        case "textarea":
        case "email":
        case "select":
        case "date":
        case "time":
        case "number":
        case "password":
          value = input.val();
          break;

        case "checkbox":
          var checkedItems = input.find("input:checked");

          // we assume that checkboxes are always multiple,
          // hence we default to an empty array
          value = [];

          each(checkedItems, function(_, item) {
            value.push($(item).val());
          });
          break;
      }

      return options.callback(value);
    };

    options.show = false;

    // prompt specific validation
    if (!options.title) {
      throw new Error("prompt requires a title");
    }

    if (!$.isFunction(options.callback)) {
      throw new Error("prompt requires a callback");
    }

    if (!templates.inputs[options.inputType]) {
      throw new Error("invalid prompt type");
    }

    // create the input based on the supplied type
    input = $(templates.inputs[options.inputType]);

    switch (options.inputType) {
      case "text":
      case "textarea":
      case "email":
      case "date":
      case "time":
      case "number":
      case "password":
        input.val(options.value);
        break;

      case "select":
        var groups = {};
        inputOptions = options.inputOptions || [];

        if (!inputOptions.length) {
          throw new Error("prompt with select requires options");
        }

        each(inputOptions, function(_, option) {

          // assume the element to attach to is the input...
          var elem = input;

          if (option.value === undefined || option.text === undefined) {
            throw new Error("given options in wrong format");
          }


          // ... but override that element if this option sits in a group

          if (option.group) {
            // initialise group if necessary
            if (!groups[option.group]) {
              groups[option.group] = $("<optgroup/>").attr("label", option.group);
            }

            elem = groups[option.group];
          }

          elem.append("<option value='" + option.value + "'>" + option.text + "</option>");
        });

        each(groups, function(_, group) {
          input.append(group);
        });

        // safe to set a select's value as per a normal input
        input.val(options.value);
        break;

      case "checkbox":
        var values   = $.isArray(options.value) ? options.value : [options.value];
        inputOptions = options.inputOptions || [];

        if (!inputOptions.length) {
          throw new Error("prompt with checkbox requires options");
        }

        if (!inputOptions[0].value || !inputOptions[0].text) {
          throw new Error("given options in wrong format");
        }

        // checkboxes have to nest within a containing element, so
        // they break the rules a bit and we end up re-assigning
        // our 'input' element to this container instead
        input = $("<div/>");

        each(inputOptions, function(_, option) {
          var checkbox = $(templates.inputs[options.inputType]);

          checkbox.find("input").attr("value", option.value);
          checkbox.find("label").append(option.text);

          // we've ensured values is an array so we can always iterate over it
          each(values, function(_, value) {
            if (value === option.value) {
              checkbox.find("input").prop("checked", true);
            }
          });

          input.append(checkbox);
        });
        break;
    }

    if (options.placeholder) {
      input.attr("placeholder", options.placeholder);
    }

    if(options.pattern){
      input.attr("pattern", options.pattern);
    }

    // now place it in our form
    form.append(input);

    form.on("submit", function(e) {
      e.preventDefault();
      // Fix for SammyJS (or similar JS routing library) hijacking the form post.
      e.stopPropagation();
      // @TODO can we actually click *the* button object instead?
      // e.g. buttons.confirm.click() or similar
      dialog.find(".btn-primary").click();
    });

    dialog = exports.dialog(options);

    // clear the existing handler focusing the submit button...
    dialog.off("shown.bs.modal");

    // ...and replace it with one focusing our input, if possible
    dialog.on("shown.bs.modal", function() {
      input.focus();
    });

    if (shouldShow === true) {
      dialog.modal("show");
    }

    return dialog;
  };

  exports.dialog = function(options) {
    options = sanitize(options);

    var dialog = $(templates.dialog);
    var innerDialog = dialog.find(".modal-dialog");
    var body = dialog.find(".modal-body");
    var buttons = options.buttons;
    var buttonStr = "";
    var callbacks = {
      onEscape: options.onEscape
    };

    each(buttons, function(key, button) {

      // @TODO I don't like this string appending to itself; bit dirty. Needs reworking
      // can we just build up button elements instead? slower but neater. Then button
      // can just become a template too
      buttonStr += "<button data-bb-handler='" + key + "' type='button' class='btn " + button.className + "'>" + button.label + "</button>";
      callbacks[key] = button.callback;
    });

    body.find(".bootbox-body").html(options.message);

    if (options.animate === true) {
      dialog.addClass("fade");
    }

    if (options.className) {
      dialog.addClass(options.className);
    }

    if (options.size === "large") {
      innerDialog.addClass("modal-lg");
    }

    if (options.size === "small") {
      innerDialog.addClass("modal-sm");
    }

    if (options.title) {
      body.before(templates.header);
    }

    if (options.closeButton) {
      var closeButton = $(templates.closeButton);

      if (options.title) {
        dialog.find(".modal-header").prepend(closeButton);
      } else {
        closeButton.css("margin-top", "-10px").prependTo(body);
      }
    }

    if (options.title) {
      dialog.find(".modal-title").html(options.title);
    }

    if (buttonStr.length) {
      body.after(templates.footer);
      dialog.find(".modal-footer").html(buttonStr);
    }


    /**
     * Bootstrap event listeners; used handle extra
     * setup & teardown required after the underlying
     * modal has performed certain actions
     */

    dialog.on("hidden.bs.modal", function(e) {
      // ensure we don't accidentally intercept hidden events triggered
      // by children of the current dialog. We shouldn't anymore now BS
      // namespaces its events; but still worth doing
      if (e.target === this) {
        dialog.remove();
      }
    });

    /*
    dialog.on("show.bs.modal", function() {
      // sadly this doesn't work; show is called *just* before
      // the backdrop is added so we'd need a setTimeout hack or
      // otherwise... leaving in as would be nice
      if (options.backdrop) {
        dialog.next(".modal-backdrop").addClass("bootbox-backdrop");
      }
    });
    */

    dialog.on("shown.bs.modal", function() {
      dialog.find(".btn-primary:first").focus();
    });

    /**
     * Bootbox event listeners; experimental and may not last
     * just an attempt to decouple some behaviours from their
     * respective triggers
     */

    dialog.on("escape.close.bb", function(e) {
      if (callbacks.onEscape) {
        processCallback(e, dialog, callbacks.onEscape);
      }
    });

    /**
     * Standard jQuery event listeners; used to handle user
     * interaction with our dialog
     */

    dialog.on("click", ".modal-footer button", function(e) {
      var callbackKey = $(this).data("bb-handler");

      processCallback(e, dialog, callbacks[callbackKey]);

    });

    dialog.on("click", ".bootbox-close-button", function(e) {
      // onEscape might be falsy but that's fine; the fact is
      // if the user has managed to click the close button we
      // have to close the dialog, callback or not
      processCallback(e, dialog, callbacks.onEscape);
    });

    dialog.on("keyup", function(e) {
      if (e.which === 27) {
        dialog.trigger("escape.close.bb");
      }
    });

    // the remainder of this method simply deals with adding our
    // dialogent to the DOM, augmenting it with Bootstrap's modal
    // functionality and then giving the resulting object back
    // to our caller

    $(options.container).append(dialog);

    dialog.modal({
      backdrop: options.backdrop,
      keyboard: false,
      show: false
    });

    if (options.show) {
      dialog.modal("show");
    }

    // @TODO should we return the raw element here or should
    // we wrap it in an object on which we can expose some neater
    // methods, e.g. var d = bootbox.alert(); d.hide(); instead
    // of d.modal("hide");

   /*
    function BBDialog(elem) {
      this.elem = elem;
    }

    BBDialog.prototype = {
      hide: function() {
        return this.elem.modal("hide");
      },
      show: function() {
        return this.elem.modal("show");
      }
    };
    */

    return dialog;

  };

  exports.setDefaults = function() {
    var values = {};

    if (arguments.length === 2) {
      // allow passing of single key/value...
      values[arguments[0]] = arguments[1];
    } else {
      // ... and as an object too
      values = arguments[0];
    }

    $.extend(defaults, values);
  };

  exports.hideAll = function() {
    $(".bootbox").modal("hide");

    return exports;
  };


  /**
   * standard locales. Please add more according to ISO 639-1 standard. Multiple language variants are
   * unlikely to be required. If this gets too large it can be split out into separate JS files.
   */
  var locales = {
    br : {
      OK      : "OK",
      CANCEL  : "Cancelar",
      CONFIRM : "Sim"
    },
    cs : {
      OK      : "OK",
      CANCEL  : "Zrušit",
      CONFIRM : "Potvrdit"
    },
    da : {
      OK      : "OK",
      CANCEL  : "Annuller",
      CONFIRM : "Accepter"
    },
    de : {
      OK      : "OK",
      CANCEL  : "Abbrechen",
      CONFIRM : "Akzeptieren"
    },
    el : {
      OK      : "Εντάξει",
      CANCEL  : "Ακύρωση",
      CONFIRM : "Επιβεβαίωση"
    },
    en : {
      OK      : "OK",
      CANCEL  : "Cancel",
      CONFIRM : "OK"
    },
    es : {
      OK      : "OK",
      CANCEL  : "Cancelar",
      CONFIRM : "Aceptar"
    },
    et : {
      OK      : "OK",
      CANCEL  : "Katkesta",
      CONFIRM : "OK"
    },
    fi : {
      OK      : "OK",
      CANCEL  : "Peruuta",
      CONFIRM : "OK"
    },
    fr : {
      OK      : "OK",
      CANCEL  : "Annuler",
      CONFIRM : "D'accord"
    },
    he : {
      OK      : "אישור",
      CANCEL  : "ביטול",
      CONFIRM : "אישור"
    },
    id : {
      OK      : "OK",
      CANCEL  : "Batal",
      CONFIRM : "OK"
    },
    it : {
      OK      : "OK",
      CANCEL  : "Annulla",
      CONFIRM : "Conferma"
    },
    ja : {
      OK      : "OK",
      CANCEL  : "キャンセル",
      CONFIRM : "確認"
    },
    lt : {
      OK      : "Gerai",
      CANCEL  : "Atšaukti",
      CONFIRM : "Patvirtinti"
    },
    lv : {
      OK      : "Labi",
      CANCEL  : "Atcelt",
      CONFIRM : "Apstiprināt"
    },
    nl : {
      OK      : "OK",
      CANCEL  : "Annuleren",
      CONFIRM : "Accepteren"
    },
    no : {
      OK      : "OK",
      CANCEL  : "Avbryt",
      CONFIRM : "OK"
    },
    pl : {
      OK      : "OK",
      CANCEL  : "Anuluj",
      CONFIRM : "Potwierdź"
    },
    pt : {
      OK      : "OK",
      CANCEL  : "Cancelar",
      CONFIRM : "Confirmar"
    },
    ru : {
      OK      : "OK",
      CANCEL  : "Отмена",
      CONFIRM : "Применить"
    },
    sv : {
      OK      : "OK",
      CANCEL  : "Avbryt",
      CONFIRM : "OK"
    },
    tr : {
      OK      : "Tamam",
      CANCEL  : "İptal",
      CONFIRM : "Onayla"
    },
    zh_CN : {
      OK      : "OK",
      CANCEL  : "取消",
      CONFIRM : "确认"
    },
    zh_TW : {
      OK      : "OK",
      CANCEL  : "取消",
      CONFIRM : "確認"
    }
  };

  exports.init = function(_$) {
    return init(_$ || $);
  };

  return exports;
}));

/* js-yaml 3.2.5 https://github.com/nodeca/js-yaml */
!function(e){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=e();else if("function"==typeof define&&define.amd)define([],e);else{var t;"undefined"!=typeof window?t=window:"undefined"!=typeof global?t=global:"undefined"!=typeof self&&(t=self),t.jsyaml=e()}}(function(){return function e(t,n,i){function r(a,s){if(!n[a]){if(!t[a]){var u="function"==typeof require&&require;if(!s&&u)return u(a,!0);if(o)return o(a,!0);var c=new Error("Cannot find module '"+a+"'");throw c.code="MODULE_NOT_FOUND",c}var l=n[a]={exports:{}};t[a][0].call(l.exports,function(e){var n=t[a][1][e];return r(n?n:e)},l,l.exports,e,t,n,i)}return n[a].exports}for(var o="function"==typeof require&&require,a=0;a<i.length;a++)r(i[a]);return r}({1:[function(e,t){"use strict";function n(e){return function(){throw new Error("Function "+e+" is deprecated and cannot be used.")}}var i=e("./js-yaml/loader"),r=e("./js-yaml/dumper");t.exports.Type=e("./js-yaml/type"),t.exports.Schema=e("./js-yaml/schema"),t.exports.FAILSAFE_SCHEMA=e("./js-yaml/schema/failsafe"),t.exports.JSON_SCHEMA=e("./js-yaml/schema/json"),t.exports.CORE_SCHEMA=e("./js-yaml/schema/core"),t.exports.DEFAULT_SAFE_SCHEMA=e("./js-yaml/schema/default_safe"),t.exports.DEFAULT_FULL_SCHEMA=e("./js-yaml/schema/default_full"),t.exports.load=i.load,t.exports.loadAll=i.loadAll,t.exports.safeLoad=i.safeLoad,t.exports.safeLoadAll=i.safeLoadAll,t.exports.dump=r.dump,t.exports.safeDump=r.safeDump,t.exports.YAMLException=e("./js-yaml/exception"),t.exports.MINIMAL_SCHEMA=e("./js-yaml/schema/failsafe"),t.exports.SAFE_SCHEMA=e("./js-yaml/schema/default_safe"),t.exports.DEFAULT_SCHEMA=e("./js-yaml/schema/default_full"),t.exports.scan=n("scan"),t.exports.parse=n("parse"),t.exports.compose=n("compose"),t.exports.addConstructor=n("addConstructor")},{"./js-yaml/dumper":3,"./js-yaml/exception":4,"./js-yaml/loader":5,"./js-yaml/schema":7,"./js-yaml/schema/core":8,"./js-yaml/schema/default_full":9,"./js-yaml/schema/default_safe":10,"./js-yaml/schema/failsafe":11,"./js-yaml/schema/json":12,"./js-yaml/type":13}],2:[function(e,t){"use strict";function n(e){return void 0===e||null===e}function i(e){return"object"==typeof e&&null!==e}function r(e){return Array.isArray(e)?e:n(e)?[]:[e]}function o(e,t){var n,i,r,o;if(t)for(o=Object.keys(t),n=0,i=o.length;i>n;n+=1)r=o[n],e[r]=t[r];return e}function a(e,t){var n,i="";for(n=0;t>n;n+=1)i+=e;return i}function s(e){return 0===e&&Number.NEGATIVE_INFINITY===1/e}t.exports.isNothing=n,t.exports.isObject=i,t.exports.toArray=r,t.exports.repeat=a,t.exports.isNegativeZero=s,t.exports.extend=o},{}],3:[function(e,t){"use strict";function n(e,t){var n,i,r,o,a,s,u;if(null===t)return{};for(n={},i=Object.keys(t),r=0,o=i.length;o>r;r+=1)a=i[r],s=String(t[a]),"!!"===a.slice(0,2)&&(a="tag:yaml.org,2002:"+a.slice(2)),u=e.compiledTypeMap[a],u&&C.call(u.styleAliases,s)&&(s=u.styleAliases[s]),n[a]=s;return n}function i(e){var t,n,i;if(t=e.toString(16).toUpperCase(),255>=e)n="x",i=2;else if(65535>=e)n="u",i=4;else{if(!(4294967295>=e))throw new v("code point within a string may not be greater than 0xFFFFFFFF");n="U",i=8}return"\\"+n+x.repeat("0",i-t.length)+t}function r(e){this.schema=e.schema||A,this.indent=Math.max(1,e.indent||2),this.skipInvalid=e.skipInvalid||!1,this.flowLevel=x.isNothing(e.flowLevel)?-1:e.flowLevel,this.styleMap=n(this.schema,e.styles||null),this.implicitTypes=this.schema.compiledImplicit,this.explicitTypes=this.schema.compiledExplicit,this.tag=null,this.result="",this.duplicates=[],this.usedDuplicates=null}function o(e,t){return"\n"+x.repeat(" ",e.indent*t)}function a(e,t){var n,i,r;for(n=0,i=e.implicitTypes.length;i>n;n+=1)if(r=e.implicitTypes[n],r.resolve(t))return!0;return!1}function s(e,t){var n,r,o,s,u,c;for(e.dump="",n=!1,r=0,c=t.charCodeAt(0)||0,-1!==Z.indexOf(t)?n=!0:0===t.length?n=!0:S===c||S===t.charCodeAt(t.length-1)?n=!0:(D===c||q===c)&&(n=!0),o=0,s=t.length;s>o;o+=1)u=t.charCodeAt(o),n||(k===u||j===u||I===u||L===u||R===u||H===u||B===u||V===u||F===u||_===u||M===u||O===u||G===u||Y===u||T===u||E===u||N===u||P===u||U===u||$===u)&&(n=!0),(W[u]||!(u>=32&&126>=u||133===u||u>=160&&55295>=u||u>=57344&&65533>=u||u>=65536&&1114111>=u))&&(e.dump+=t.slice(r,o),e.dump+=W[u]||i(u),r=o+1,n=!0);o>r&&(e.dump+=t.slice(r,o)),!n&&a(e,e.dump)&&(n=!0),n&&(e.dump='"'+e.dump+'"')}function u(e,t,n){var i,r,o="",a=e.tag;for(i=0,r=n.length;r>i;i+=1)d(e,t,n[i],!1,!1)&&(0!==i&&(o+=", "),o+=e.dump);e.tag=a,e.dump="["+o+"]"}function c(e,t,n,i){var r,a,s="",u=e.tag;for(r=0,a=n.length;a>r;r+=1)d(e,t+1,n[r],!0,!0)&&(i&&0===r||(s+=o(e,t)),s+="- "+e.dump);e.tag=u,e.dump=s||"[]"}function l(e,t,n){var i,r,o,a,s,u="",c=e.tag,l=Object.keys(n);for(i=0,r=l.length;r>i;i+=1)s="",0!==i&&(s+=", "),o=l[i],a=n[o],d(e,t,o,!1,!1)&&(e.dump.length>1024&&(s+="? "),s+=e.dump+": ",d(e,t,a,!1,!1)&&(s+=e.dump,u+=s));e.tag=c,e.dump="{"+u+"}"}function p(e,t,n,i){var r,a,s,u,c,l,p="",f=e.tag,h=Object.keys(n);for(r=0,a=h.length;a>r;r+=1)l="",i&&0===r||(l+=o(e,t)),s=h[r],u=n[s],d(e,t+1,s,!0,!0)&&(c=null!==e.tag&&"?"!==e.tag||e.dump&&e.dump.length>1024,c&&(l+=e.dump&&j===e.dump.charCodeAt(0)?"?":"? "),l+=e.dump,c&&(l+=o(e,t)),d(e,t+1,u,!0,c)&&(l+=e.dump&&j===e.dump.charCodeAt(0)?":":": ",l+=e.dump,p+=l));e.tag=f,e.dump=p||"{}"}function f(e,t,n){var i,r,o,a,s,u;for(r=n?e.explicitTypes:e.implicitTypes,o=0,a=r.length;a>o;o+=1)if(s=r[o],(s.instanceOf||s.predicate)&&(!s.instanceOf||"object"==typeof t&&t instanceof s.instanceOf)&&(!s.predicate||s.predicate(t))){if(e.tag=n?s.tag:"?",s.represent){if(u=e.styleMap[s.tag]||s.defaultStyle,"[object Function]"===w.call(s.represent))i=s.represent(t,u);else{if(!C.call(s.represent,u))throw new v("!<"+s.tag+'> tag resolver accepts not "'+u+'" style');i=s.represent[u](t,u)}e.dump=i}return!0}return!1}function d(e,t,n,i,r){e.tag=null,e.dump=n,f(e,n,!1)||f(e,n,!0);var o=w.call(e.dump);i&&(i=0>e.flowLevel||e.flowLevel>t),(null!==e.tag&&"?"!==e.tag||2!==e.indent&&t>0)&&(r=!1);var a,d,h="[object Object]"===o||"[object Array]"===o;if(h&&(a=e.duplicates.indexOf(n),d=-1!==a),d&&e.usedDuplicates[a])e.dump="*ref_"+a;else{if(h&&d&&!e.usedDuplicates[a]&&(e.usedDuplicates[a]=!0),"[object Object]"===o)i&&0!==Object.keys(e.dump).length?(p(e,t,e.dump,r),d&&(e.dump="&ref_"+a+(0===t?"\n":"")+e.dump)):(l(e,t,e.dump),d&&(e.dump="&ref_"+a+" "+e.dump));else if("[object Array]"===o)i&&0!==e.dump.length?(c(e,t,e.dump,r),d&&(e.dump="&ref_"+a+(0===t?"\n":"")+e.dump)):(u(e,t,e.dump),d&&(e.dump="&ref_"+a+" "+e.dump));else{if("[object String]"!==o){if(e.skipInvalid)return!1;throw new v("unacceptable kind of an object to dump "+o)}"?"!==e.tag&&s(e,e.dump)}null!==e.tag&&"?"!==e.tag&&(e.dump="!<"+e.tag+"> "+e.dump)}return!0}function h(e,t){var n,i,r=[],o=[];for(m(e,r,o),n=0,i=o.length;i>n;n+=1)t.duplicates.push(r[o[n]]);t.usedDuplicates=new Array(i)}function m(e,t,n){{var i,r,o;w.call(e)}if(null!==e&&"object"==typeof e)if(r=t.indexOf(e),-1!==r)-1===n.indexOf(r)&&n.push(r);else if(t.push(e),Array.isArray(e))for(r=0,o=e.length;o>r;r+=1)m(e[r],t,n);else for(i=Object.keys(e),r=0,o=i.length;o>r;r+=1)m(e[i[r]],t,n)}function g(e,t){t=t||{};var n=new r(t);return h(e,n),d(n,0,e,!0,!0)?n.dump+"\n":""}function y(e,t){return g(e,x.extend({schema:b},t))}var x=e("./common"),v=e("./exception"),A=e("./schema/default_full"),b=e("./schema/default_safe"),w=Object.prototype.toString,C=Object.prototype.hasOwnProperty,k=9,j=10,I=13,S=32,O=33,E=34,F=35,N=37,_=38,T=39,M=42,L=44,D=45,U=58,Y=62,q=63,P=64,R=91,H=93,$=96,B=123,G=124,V=125,W={};W[0]="\\0",W[7]="\\a",W[8]="\\b",W[9]="\\t",W[10]="\\n",W[11]="\\v",W[12]="\\f",W[13]="\\r",W[27]="\\e",W[34]='\\"',W[92]="\\\\",W[133]="\\N",W[160]="\\_",W[8232]="\\L",W[8233]="\\P";var Z=["y","Y","yes","Yes","YES","on","On","ON","n","N","no","No","NO","off","Off","OFF"];t.exports.dump=g,t.exports.safeDump=y},{"./common":2,"./exception":4,"./schema/default_full":9,"./schema/default_safe":10}],4:[function(e,t){"use strict";function n(e,t){this.name="YAMLException",this.reason=e,this.mark=t,this.message=this.toString(!1)}n.prototype.toString=function(e){var t;return t="JS-YAML: "+(this.reason||"(unknown reason)"),!e&&this.mark&&(t+=" "+this.mark.toString()),t},t.exports=n},{}],5:[function(e,t){"use strict";function n(e){return 10===e||13===e}function i(e){return 9===e||32===e}function r(e){return 9===e||32===e||10===e||13===e}function o(e){return 44===e||91===e||93===e||123===e||125===e}function a(e){var t;return e>=48&&57>=e?e-48:(t=32|e,t>=97&&102>=t?t-97+10:-1)}function s(e){return 120===e?2:117===e?4:85===e?8:0}function u(e){return e>=48&&57>=e?e-48:-1}function c(e){return 48===e?"\x00":97===e?"":98===e?"\b":116===e?"	":9===e?"	":110===e?"\n":118===e?"":102===e?"\f":114===e?"\r":101===e?"":32===e?" ":34===e?'"':47===e?"/":92===e?"\\":78===e?"":95===e?" ":76===e?"\u2028":80===e?"\u2029":""}function l(e,t){this.input=e,this.filename=t.filename||null,this.schema=t.schema||H,this.onWarning=t.onWarning||null,this.legacy=t.legacy||!1,this.implicitTypes=this.schema.compiledImplicit,this.typeMap=this.schema.compiledTypeMap,this.length=e.length,this.position=0,this.line=0,this.lineStart=0,this.lineIndent=0,this.documents=[]}function p(e,t){return new q(t,new P(e.filename,e.input,e.position,e.line,e.position-e.lineStart))}function f(e,t){throw p(e,t)}function d(e,t){var n=p(e,t);if(!e.onWarning)throw n;e.onWarning.call(null,n)}function h(e,t,n,i){var r,o,a,s;if(n>t){if(s=e.input.slice(t,n),i)for(r=0,o=s.length;o>r;r+=1)a=s.charCodeAt(r),9===a||a>=32&&1114111>=a||f(e,"expected valid JSON character");e.result+=s}}function m(e,t,n){var i,r,o,a;for(Y.isObject(n)||f(e,"cannot merge mappings; the provided source object is unacceptable"),i=Object.keys(n),o=0,a=i.length;a>o;o+=1)r=i[o],$.call(t,r)||(t[r]=n[r])}function g(e,t,n,i,r){var o,a;if(i=String(i),null===t&&(t={}),"tag:yaml.org,2002:merge"===n)if(Array.isArray(r))for(o=0,a=r.length;a>o;o+=1)m(e,t,r[o]);else m(e,t,r);else t[i]=r;return t}function y(e){var t;t=e.input.charCodeAt(e.position),10===t?e.position++:13===t?(e.position++,10===e.input.charCodeAt(e.position)&&e.position++):f(e,"a line break is expected"),e.line+=1,e.lineStart=e.position}function x(e,t,r){for(var o=0,a=e.input.charCodeAt(e.position);0!==a;){for(;i(a);)a=e.input.charCodeAt(++e.position);if(t&&35===a)do a=e.input.charCodeAt(++e.position);while(10!==a&&13!==a&&0!==a);if(!n(a))break;for(y(e),a=e.input.charCodeAt(e.position),o++,e.lineIndent=0;32===a;)e.lineIndent++,a=e.input.charCodeAt(++e.position)}return-1!==r&&0!==o&&e.lineIndent<r&&d(e,"deficient indentation"),o}function v(e){var t,n=e.position;return t=e.input.charCodeAt(n),45!==t&&46!==t||e.input.charCodeAt(n+1)!==t||e.input.charCodeAt(n+2)!==t||(n+=3,t=e.input.charCodeAt(n),0!==t&&!r(t))?!1:!0}function A(e,t){1===t?e.result+=" ":t>1&&(e.result+=Y.repeat("\n",t-1))}function b(e,t,a){var s,u,c,l,p,f,d,m,g,y=e.kind,b=e.result;if(g=e.input.charCodeAt(e.position),r(g)||o(g)||35===g||38===g||42===g||33===g||124===g||62===g||39===g||34===g||37===g||64===g||96===g)return!1;if((63===g||45===g)&&(u=e.input.charCodeAt(e.position+1),r(u)||a&&o(u)))return!1;for(e.kind="scalar",e.result="",c=l=e.position,p=!1;0!==g;){if(58===g){if(u=e.input.charCodeAt(e.position+1),r(u)||a&&o(u))break}else if(35===g){if(s=e.input.charCodeAt(e.position-1),r(s))break}else{if(e.position===e.lineStart&&v(e)||a&&o(g))break;if(n(g)){if(f=e.line,d=e.lineStart,m=e.lineIndent,x(e,!1,-1),e.lineIndent>=t){p=!0,g=e.input.charCodeAt(e.position);continue}e.position=l,e.line=f,e.lineStart=d,e.lineIndent=m;break}}p&&(h(e,c,l,!1),A(e,e.line-f),c=l=e.position,p=!1),i(g)||(l=e.position+1),g=e.input.charCodeAt(++e.position)}return h(e,c,l,!1),e.result?!0:(e.kind=y,e.result=b,!1)}function w(e,t){var i,r,o;if(i=e.input.charCodeAt(e.position),39!==i)return!1;for(e.kind="scalar",e.result="",e.position++,r=o=e.position;0!==(i=e.input.charCodeAt(e.position));)if(39===i){if(h(e,r,e.position,!0),i=e.input.charCodeAt(++e.position),39!==i)return!0;r=o=e.position,e.position++}else n(i)?(h(e,r,o,!0),A(e,x(e,!1,t)),r=o=e.position):e.position===e.lineStart&&v(e)?f(e,"unexpected end of the document within a single quoted scalar"):(e.position++,o=e.position);f(e,"unexpected end of the stream within a single quoted scalar")}function C(e,t){var i,r,o,u,c,l;if(l=e.input.charCodeAt(e.position),34!==l)return!1;for(e.kind="scalar",e.result="",e.position++,i=r=e.position;0!==(l=e.input.charCodeAt(e.position));){if(34===l)return h(e,i,e.position,!0),e.position++,!0;if(92===l){if(h(e,i,e.position,!0),l=e.input.charCodeAt(++e.position),n(l))x(e,!1,t);else if(256>l&&nt[l])e.result+=it[l],e.position++;else if((c=s(l))>0){for(o=c,u=0;o>0;o--)l=e.input.charCodeAt(++e.position),(c=a(l))>=0?u=(u<<4)+c:f(e,"expected hexadecimal character");e.result+=String.fromCharCode(u),e.position++}else f(e,"unknown escape sequence");i=r=e.position}else n(l)?(h(e,i,r,!0),A(e,x(e,!1,t)),i=r=e.position):e.position===e.lineStart&&v(e)?f(e,"unexpected end of the document within a double quoted scalar"):(e.position++,r=e.position)}f(e,"unexpected end of the stream within a double quoted scalar")}function k(e,t){var n,i,o,a,s,u,c,l,p,d,h,m=!0,y=e.tag,v=e.anchor;if(h=e.input.charCodeAt(e.position),91===h)a=93,c=!1,i=[];else{if(123!==h)return!1;a=125,c=!0,i={}}for(null!==e.anchor&&(e.anchorMap[e.anchor]=i),h=e.input.charCodeAt(++e.position);0!==h;){if(x(e,!0,t),h=e.input.charCodeAt(e.position),h===a)return e.position++,e.tag=y,e.anchor=v,e.kind=c?"mapping":"sequence",e.result=i,!0;m||f(e,"missed comma between flow collection entries"),p=l=d=null,s=u=!1,63===h&&(o=e.input.charCodeAt(e.position+1),r(o)&&(s=u=!0,e.position++,x(e,!0,t))),n=e.line,N(e,t,B,!1,!0),p=e.tag,l=e.result,x(e,!0,t),h=e.input.charCodeAt(e.position),!u&&e.line!==n||58!==h||(s=!0,h=e.input.charCodeAt(++e.position),x(e,!0,t),N(e,t,B,!1,!0),d=e.result),c?g(e,i,p,l,d):i.push(s?g(e,null,p,l,d):l),x(e,!0,t),h=e.input.charCodeAt(e.position),44===h?(m=!0,h=e.input.charCodeAt(++e.position)):m=!1}f(e,"unexpected end of the stream within a flow collection")}function j(e,t){var r,o,a,s,c=Z,l=!1,p=t,d=0,m=!1;if(s=e.input.charCodeAt(e.position),124===s)o=!1;else{if(62!==s)return!1;o=!0}for(e.kind="scalar",e.result="";0!==s;)if(s=e.input.charCodeAt(++e.position),43===s||45===s)Z===c?c=43===s?z:J:f(e,"repeat of a chomping mode identifier");else{if(!((a=u(s))>=0))break;0===a?f(e,"bad explicit indentation width of a block scalar; it cannot be less than one"):l?f(e,"repeat of an indentation width identifier"):(p=t+a-1,l=!0)}if(i(s)){do s=e.input.charCodeAt(++e.position);while(i(s));if(35===s)do s=e.input.charCodeAt(++e.position);while(!n(s)&&0!==s)}for(;0!==s;){for(y(e),e.lineIndent=0,s=e.input.charCodeAt(e.position);(!l||e.lineIndent<p)&&32===s;)e.lineIndent++,s=e.input.charCodeAt(++e.position);if(!l&&e.lineIndent>p&&(p=e.lineIndent),n(s))d++;else{if(e.lineIndent<p){c===z?e.result+=Y.repeat("\n",d):c===Z&&l&&(e.result+="\n");break}for(o?i(s)?(m=!0,e.result+=Y.repeat("\n",d+1)):m?(m=!1,e.result+=Y.repeat("\n",d+1)):0===d?l&&(e.result+=" "):e.result+=Y.repeat("\n",d):e.result+=l?Y.repeat("\n",d+1):Y.repeat("\n",d),l=!0,d=0,r=e.position;!n(s)&&0!==s;)s=e.input.charCodeAt(++e.position);h(e,r,e.position,!1)}}return!0}function I(e,t){var n,i,o,a=e.tag,s=e.anchor,u=[],c=!1;for(null!==e.anchor&&(e.anchorMap[e.anchor]=u),o=e.input.charCodeAt(e.position);0!==o&&45===o&&(i=e.input.charCodeAt(e.position+1),r(i));)if(c=!0,e.position++,x(e,!0,-1)&&e.lineIndent<=t)u.push(null),o=e.input.charCodeAt(e.position);else if(n=e.line,N(e,t,V,!1,!0),u.push(e.result),x(e,!0,-1),o=e.input.charCodeAt(e.position),(e.line===n||e.lineIndent>t)&&0!==o)f(e,"bad indentation of a sequence entry");else if(e.lineIndent<t)break;return c?(e.tag=a,e.anchor=s,e.kind="sequence",e.result=u,!0):!1}function S(e,t,n){var o,a,s,u,c=e.tag,l=e.anchor,p={},d=null,h=null,m=null,y=!1,v=!1;for(null!==e.anchor&&(e.anchorMap[e.anchor]=p),u=e.input.charCodeAt(e.position);0!==u;){if(o=e.input.charCodeAt(e.position+1),s=e.line,63!==u&&58!==u||!r(o)){if(!N(e,n,G,!1,!0))break;if(e.line===s){for(u=e.input.charCodeAt(e.position);i(u);)u=e.input.charCodeAt(++e.position);if(58===u)u=e.input.charCodeAt(++e.position),r(u)||f(e,"a whitespace character is expected after the key-value separator within a block mapping"),y&&(g(e,p,d,h,null),d=h=m=null),v=!0,y=!1,a=!1,d=e.tag,h=e.result;else{if(!v)return e.tag=c,e.anchor=l,!0;f(e,"can not read an implicit mapping pair; a colon is missed")}}else{if(!v)return e.tag=c,e.anchor=l,!0;f(e,"can not read a block mapping entry; a multiline key may not be an implicit key")}}else 63===u?(y&&(g(e,p,d,h,null),d=h=m=null),v=!0,y=!0,a=!0):y?(y=!1,a=!0):f(e,"incomplete explicit mapping pair; a key node is missed"),e.position+=1,u=o;if((e.line===s||e.lineIndent>t)&&(N(e,t,W,!0,a)&&(y?h=e.result:m=e.result),y||(g(e,p,d,h,m),d=h=m=null),x(e,!0,-1),u=e.input.charCodeAt(e.position)),e.lineIndent>t&&0!==u)f(e,"bad indentation of a mapping entry");else if(e.lineIndent<t)break}return y&&g(e,p,d,h,null),v&&(e.tag=c,e.anchor=l,e.kind="mapping",e.result=p),v}function O(e){var t,n,i,o,a=!1,s=!1;if(o=e.input.charCodeAt(e.position),33!==o)return!1;if(null!==e.tag&&f(e,"duplication of a tag property"),o=e.input.charCodeAt(++e.position),60===o?(a=!0,o=e.input.charCodeAt(++e.position)):33===o?(s=!0,n="!!",o=e.input.charCodeAt(++e.position)):n="!",t=e.position,a){do o=e.input.charCodeAt(++e.position);while(0!==o&&62!==o);e.position<e.length?(i=e.input.slice(t,e.position),o=e.input.charCodeAt(++e.position)):f(e,"unexpected end of the stream within a verbatim tag")}else{for(;0!==o&&!r(o);)33===o&&(s?f(e,"tag suffix cannot contain exclamation marks"):(n=e.input.slice(t-1,e.position+1),et.test(n)||f(e,"named tag handle cannot contain such characters"),s=!0,t=e.position+1)),o=e.input.charCodeAt(++e.position);i=e.input.slice(t,e.position),X.test(i)&&f(e,"tag suffix cannot contain flow indicator characters")}return i&&!tt.test(i)&&f(e,"tag name cannot contain such characters: "+i),a?e.tag=i:$.call(e.tagMap,n)?e.tag=e.tagMap[n]+i:"!"===n?e.tag="!"+i:"!!"===n?e.tag="tag:yaml.org,2002:"+i:f(e,'undeclared tag handle "'+n+'"'),!0}function E(e){var t,n;if(n=e.input.charCodeAt(e.position),38!==n)return!1;for(null!==e.anchor&&f(e,"duplication of an anchor property"),n=e.input.charCodeAt(++e.position),t=e.position;0!==n&&!r(n)&&!o(n);)n=e.input.charCodeAt(++e.position);return e.position===t&&f(e,"name of an anchor node must contain at least one character"),e.anchor=e.input.slice(t,e.position),!0}function F(e){{var t,n,i;e.length,e.input}if(i=e.input.charCodeAt(e.position),42!==i)return!1;for(i=e.input.charCodeAt(++e.position),t=e.position;0!==i&&!r(i)&&!o(i);)i=e.input.charCodeAt(++e.position);return e.position===t&&f(e,"name of an alias node must contain at least one character"),n=e.input.slice(t,e.position),e.anchorMap.hasOwnProperty(n)||f(e,'unidentified alias "'+n+'"'),e.result=e.anchorMap[n],x(e,!0,-1),!0}function N(e,t,n,i,r){var o,a,s,u,c,l,p,h,m=1,g=!1,y=!1;if(e.tag=null,e.anchor=null,e.kind=null,e.result=null,o=a=s=W===n||V===n,i&&x(e,!0,-1)&&(g=!0,e.lineIndent>t?m=1:e.lineIndent===t?m=0:e.lineIndent<t&&(m=-1)),1===m)for(;O(e)||E(e);)x(e,!0,-1)?(g=!0,s=o,e.lineIndent>t?m=1:e.lineIndent===t?m=0:e.lineIndent<t&&(m=-1)):s=!1;if(s&&(s=g||r),(1===m||W===n)&&(p=B===n||G===n?t:t+1,h=e.position-e.lineStart,1===m?s&&(I(e,h)||S(e,h,p))||k(e,p)?y=!0:(a&&j(e,p)||w(e,p)||C(e,p)?y=!0:F(e)?(y=!0,(null!==e.tag||null!==e.anchor)&&f(e,"alias node should not have any properties")):b(e,p,B===n)&&(y=!0,null===e.tag&&(e.tag="?")),null!==e.anchor&&(e.anchorMap[e.anchor]=e.result)):0===m&&(y=s&&I(e,h))),null!==e.tag&&"!"!==e.tag)if("?"===e.tag){for(u=0,c=e.implicitTypes.length;c>u;u+=1)if(l=e.implicitTypes[u],l.resolve(e.result)){e.result=l.construct(e.result),e.tag=l.tag,null!==e.anchor&&(e.anchorMap[e.anchor]=e.result);break}}else $.call(e.typeMap,e.tag)?(l=e.typeMap[e.tag],null!==e.result&&l.kind!==e.kind&&f(e,"unacceptable node kind for !<"+e.tag+'> tag; it should be "'+l.kind+'", not "'+e.kind+'"'),l.resolve(e.result)?(e.result=l.construct(e.result),null!==e.anchor&&(e.anchorMap[e.anchor]=e.result)):f(e,"cannot resolve a node with !<"+e.tag+"> explicit tag")):d(e,"unknown tag !<"+e.tag+">");return null!==e.tag||null!==e.anchor||y}function _(e){var t,o,a,s,u=e.position,c=!1;for(e.version=null,e.checkLineBreaks=e.legacy,e.tagMap={},e.anchorMap={};0!==(s=e.input.charCodeAt(e.position))&&(x(e,!0,-1),s=e.input.charCodeAt(e.position),!(e.lineIndent>0||37!==s));){for(c=!0,s=e.input.charCodeAt(++e.position),t=e.position;0!==s&&!r(s);)s=e.input.charCodeAt(++e.position);for(o=e.input.slice(t,e.position),a=[],o.length<1&&f(e,"directive name must not be less than one character in length");0!==s;){for(;i(s);)s=e.input.charCodeAt(++e.position);if(35===s){do s=e.input.charCodeAt(++e.position);while(0!==s&&!n(s));break}if(n(s))break;for(t=e.position;0!==s&&!r(s);)s=e.input.charCodeAt(++e.position);a.push(e.input.slice(t,e.position))}0!==s&&y(e),$.call(ot,o)?ot[o](e,o,a):d(e,'unknown document directive "'+o+'"')}return x(e,!0,-1),0===e.lineIndent&&45===e.input.charCodeAt(e.position)&&45===e.input.charCodeAt(e.position+1)&&45===e.input.charCodeAt(e.position+2)?(e.position+=3,x(e,!0,-1)):c&&f(e,"directives end mark is expected"),N(e,e.lineIndent-1,W,!1,!0),x(e,!0,-1),e.checkLineBreaks&&Q.test(e.input.slice(u,e.position))&&d(e,"non-ASCII line breaks are interpreted as content"),e.documents.push(e.result),e.position===e.lineStart&&v(e)?void(46===e.input.charCodeAt(e.position)&&(e.position+=3,x(e,!0,-1))):void(e.position<e.length-1&&f(e,"end of the stream or a document separator is expected"))}function T(e,t){e=String(e),t=t||{},0!==e.length&&10!==e.charCodeAt(e.length-1)&&13!==e.charCodeAt(e.length-1)&&(e+="\n");var n=new l(e,t);for(K.test(n.input)&&f(n,"the stream contains non-printable characters"),n.input+="\x00";32===n.input.charCodeAt(n.position);)n.lineIndent+=1,n.position+=1;for(;n.position<n.length-1;)_(n);return n.documents}function M(e,t,n){var i,r,o=T(e,n);for(i=0,r=o.length;r>i;i+=1)t(o[i])}function L(e,t){var n=T(e,t);if(0===n.length)return void 0;if(1===n.length)return n[0];throw new q("expected a single document in the stream, but found more")}function D(e,t,n){M(e,t,Y.extend({schema:R},n))}function U(e,t){return L(e,Y.extend({schema:R},t))}for(var Y=e("./common"),q=e("./exception"),P=e("./mark"),R=e("./schema/default_safe"),H=e("./schema/default_full"),$=Object.prototype.hasOwnProperty,B=1,G=2,V=3,W=4,Z=1,J=2,z=3,K=/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\x84\x86-\x9F\uD800-\uDFFF\uFFFE\uFFFF]/,Q=/[\x85\u2028\u2029]/,X=/[,\[\]\{\}]/,et=/^(?:!|!!|![a-z\-]+!)$/i,tt=/^(?:!|[^,\[\]\{\}])(?:%[0-9a-f]{2}|[0-9a-z\-#;\/\?:@&=\+\$,_\.!~\*'\(\)\[\]])*$/i,nt=new Array(256),it=new Array(256),rt=0;256>rt;rt++)nt[rt]=c(rt)?1:0,it[rt]=c(rt);var ot={YAML:function(e,t,n){var i,r,o;null!==e.version&&f(e,"duplication of %YAML directive"),1!==n.length&&f(e,"YAML directive accepts exactly one argument"),i=/^([0-9]+)\.([0-9]+)$/.exec(n[0]),null===i&&f(e,"ill-formed argument of the YAML directive"),r=parseInt(i[1],10),o=parseInt(i[2],10),1!==r&&f(e,"unacceptable YAML version of the document"),e.version=n[0],e.checkLineBreaks=2>o,1!==o&&2!==o&&d(e,"unsupported YAML version of the document")},TAG:function(e,t,n){var i,r;2!==n.length&&f(e,"TAG directive accepts exactly two arguments"),i=n[0],r=n[1],et.test(i)||f(e,"ill-formed tag handle (first argument) of the TAG directive"),$.call(e.tagMap,i)&&f(e,'there is a previously declared suffix for "'+i+'" tag handle'),tt.test(r)||f(e,"ill-formed tag prefix (second argument) of the TAG directive"),e.tagMap[i]=r}};t.exports.loadAll=M,t.exports.load=L,t.exports.safeLoadAll=D,t.exports.safeLoad=U},{"./common":2,"./exception":4,"./mark":6,"./schema/default_full":9,"./schema/default_safe":10}],6:[function(e,t){"use strict";function n(e,t,n,i,r){this.name=e,this.buffer=t,this.position=n,this.line=i,this.column=r}var i=e("./common");n.prototype.getSnippet=function(e,t){var n,r,o,a,s;if(!this.buffer)return null;for(e=e||4,t=t||75,n="",r=this.position;r>0&&-1==="\x00\r\n\u2028\u2029".indexOf(this.buffer.charAt(r-1));)if(r-=1,this.position-r>t/2-1){n=" ... ",r+=5;break}for(o="",a=this.position;a<this.buffer.length&&-1==="\x00\r\n\u2028\u2029".indexOf(this.buffer.charAt(a));)if(a+=1,a-this.position>t/2-1){o=" ... ",a-=5;break}return s=this.buffer.slice(r,a),i.repeat(" ",e)+n+s+o+"\n"+i.repeat(" ",e+this.position-r+n.length)+"^"},n.prototype.toString=function(e){var t,n="";return this.name&&(n+='in "'+this.name+'" '),n+="at line "+(this.line+1)+", column "+(this.column+1),e||(t=this.getSnippet(),t&&(n+=":\n"+t)),n},t.exports=n},{"./common":2}],7:[function(e,t){"use strict";function n(e,t,i){var r=[];return e.include.forEach(function(e){i=n(e,t,i)}),e[t].forEach(function(e){i.forEach(function(t,n){t.tag===e.tag&&r.push(n)}),i.push(e)}),i.filter(function(e,t){return-1===r.indexOf(t)})}function i(){function e(e){i[e.tag]=e}var t,n,i={};for(t=0,n=arguments.length;n>t;t+=1)arguments[t].forEach(e);return i}function r(e){this.include=e.include||[],this.implicit=e.implicit||[],this.explicit=e.explicit||[],this.implicit.forEach(function(e){if(e.loadKind&&"scalar"!==e.loadKind)throw new a("There is a non-scalar type in the implicit list of a schema. Implicit resolving of such types is not supported.")}),this.compiledImplicit=n(this,"implicit",[]),this.compiledExplicit=n(this,"explicit",[]),this.compiledTypeMap=i(this.compiledImplicit,this.compiledExplicit)}var o=e("./common"),a=e("./exception"),s=e("./type");r.DEFAULT=null,r.create=function(){var e,t;switch(arguments.length){case 1:e=r.DEFAULT,t=arguments[0];break;case 2:e=arguments[0],t=arguments[1];break;default:throw new a("Wrong number of arguments for Schema.create function")}if(e=o.toArray(e),t=o.toArray(t),!e.every(function(e){return e instanceof r}))throw new a("Specified list of super schemas (or a single Schema object) contains a non-Schema object.");if(!t.every(function(e){return e instanceof s}))throw new a("Specified list of YAML types (or a single Type object) contains a non-Type object.");return new r({include:e,explicit:t})},t.exports=r},{"./common":2,"./exception":4,"./type":13}],8:[function(e,t){"use strict";var n=e("../schema");t.exports=new n({include:[e("./json")]})},{"../schema":7,"./json":12}],9:[function(e,t){"use strict";var n=e("../schema");t.exports=n.DEFAULT=new n({include:[e("./default_safe")],explicit:[e("../type/js/undefined"),e("../type/js/regexp"),e("../type/js/function")]})},{"../schema":7,"../type/js/function":18,"../type/js/regexp":19,"../type/js/undefined":20,"./default_safe":10}],10:[function(e,t){"use strict";var n=e("../schema");t.exports=new n({include:[e("./core")],implicit:[e("../type/timestamp"),e("../type/merge")],explicit:[e("../type/binary"),e("../type/omap"),e("../type/pairs"),e("../type/set")]})},{"../schema":7,"../type/binary":14,"../type/merge":22,"../type/omap":24,"../type/pairs":25,"../type/set":27,"../type/timestamp":29,"./core":8}],11:[function(e,t){"use strict";var n=e("../schema");t.exports=new n({explicit:[e("../type/str"),e("../type/seq"),e("../type/map")]})},{"../schema":7,"../type/map":21,"../type/seq":26,"../type/str":28}],12:[function(e,t){"use strict";var n=e("../schema");t.exports=new n({include:[e("./failsafe")],implicit:[e("../type/null"),e("../type/bool"),e("../type/int"),e("../type/float")]})},{"../schema":7,"../type/bool":15,"../type/float":16,"../type/int":17,"../type/null":23,"./failsafe":11}],13:[function(e,t){"use strict";function n(e){var t={};return null!==e&&Object.keys(e).forEach(function(n){e[n].forEach(function(e){t[String(e)]=n})}),t}function i(e,t){if(t=t||{},Object.keys(t).forEach(function(t){if(-1===o.indexOf(t))throw new r('Unknown option "'+t+'" is met in definition of "'+e+'" YAML type.')}),this.tag=e,this.kind=t.kind||null,this.resolve=t.resolve||function(){return!0},this.construct=t.construct||function(e){return e},this.instanceOf=t.instanceOf||null,this.predicate=t.predicate||null,this.represent=t.represent||null,this.defaultStyle=t.defaultStyle||null,this.styleAliases=n(t.styleAliases||null),-1===a.indexOf(this.kind))throw new r('Unknown kind "'+this.kind+'" is specified for "'+e+'" YAML type.')}var r=e("./exception"),o=["kind","resolve","construct","instanceOf","predicate","represent","defaultStyle","styleAliases"],a=["scalar","sequence","mapping"];t.exports=i},{"./exception":4}],14:[function(e,t){"use strict";function n(e){if(null===e)return!1;var t,n,i=0,r=e.length,o=u;for(n=0;r>n;n++)if(t=o.indexOf(e.charAt(n)),!(t>64)){if(0>t)return!1;i+=6}return i%8===0}function i(e){var t,n,i=e.replace(/[\r\n=]/g,""),r=i.length,o=u,s=0,c=[];for(t=0;r>t;t++)t%4===0&&t&&(c.push(s>>16&255),c.push(s>>8&255),c.push(255&s)),s=s<<6|o.indexOf(i.charAt(t));return n=r%4*6,0===n?(c.push(s>>16&255),c.push(s>>8&255),c.push(255&s)):18===n?(c.push(s>>10&255),c.push(s>>2&255)):12===n&&c.push(s>>4&255),a?new a(c):c}function r(e){var t,n,i="",r=0,o=e.length,a=u;for(t=0;o>t;t++)t%3===0&&t&&(i+=a[r>>18&63],i+=a[r>>12&63],i+=a[r>>6&63],i+=a[63&r]),r=(r<<8)+e[t];return n=o%3,0===n?(i+=a[r>>18&63],i+=a[r>>12&63],i+=a[r>>6&63],i+=a[63&r]):2===n?(i+=a[r>>10&63],i+=a[r>>4&63],i+=a[r<<2&63],i+=a[64]):1===n&&(i+=a[r>>2&63],i+=a[r<<4&63],i+=a[64],i+=a[64]),i}function o(e){return a&&a.isBuffer(e)}var a=e("buffer").Buffer,s=e("../type"),u="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=\n\r";t.exports=new s("tag:yaml.org,2002:binary",{kind:"scalar",resolve:n,construct:i,predicate:o,represent:r})},{"../type":13,buffer:30}],15:[function(e,t){"use strict";function n(e){if(null===e)return!1;var t=e.length;return 4===t&&("true"===e||"True"===e||"TRUE"===e)||5===t&&("false"===e||"False"===e||"FALSE"===e)}function i(e){return"true"===e||"True"===e||"TRUE"===e}function r(e){return"[object Boolean]"===Object.prototype.toString.call(e)}var o=e("../type");t.exports=new o("tag:yaml.org,2002:bool",{kind:"scalar",resolve:n,construct:i,predicate:r,represent:{lowercase:function(e){return e?"true":"false"},uppercase:function(e){return e?"TRUE":"FALSE"},camelcase:function(e){return e?"True":"False"}},defaultStyle:"lowercase"})},{"../type":13}],16:[function(e,t){"use strict";function n(e){if(null===e)return!1;return u.test(e)?!0:!1}function i(e){var t,n,i,r;return t=e.replace(/_/g,"").toLowerCase(),n="-"===t[0]?-1:1,r=[],0<="+-".indexOf(t[0])&&(t=t.slice(1)),".inf"===t?1===n?Number.POSITIVE_INFINITY:Number.NEGATIVE_INFINITY:".nan"===t?0/0:0<=t.indexOf(":")?(t.split(":").forEach(function(e){r.unshift(parseFloat(e,10))}),t=0,i=1,r.forEach(function(e){t+=e*i,i*=60}),n*t):n*parseFloat(t,10)}function r(e,t){if(isNaN(e))switch(t){case"lowercase":return".nan";case"uppercase":return".NAN";case"camelcase":return".NaN"}else if(Number.POSITIVE_INFINITY===e)switch(t){case"lowercase":return".inf";case"uppercase":return".INF";case"camelcase":return".Inf"}else{if(Number.NEGATIVE_INFINITY!==e)return a.isNegativeZero(e)?"-0.0":e.toString(10);switch(t){case"lowercase":return"-.inf";case"uppercase":return"-.INF";case"camelcase":return"-.Inf"}}}function o(e){return"[object Number]"===Object.prototype.toString.call(e)&&(0!==e%1||a.isNegativeZero(e))}var a=e("../common"),s=e("../type"),u=new RegExp("^(?:[-+]?(?:[0-9][0-9_]*)\\.[0-9_]*(?:[eE][-+][0-9]+)?|\\.[0-9_]+(?:[eE][-+][0-9]+)?|[-+]?[0-9][0-9_]*(?::[0-5]?[0-9])+\\.[0-9_]*|[-+]?\\.(?:inf|Inf|INF)|\\.(?:nan|NaN|NAN))$");t.exports=new s("tag:yaml.org,2002:float",{kind:"scalar",resolve:n,construct:i,predicate:o,represent:r,defaultStyle:"lowercase"})},{"../common":2,"../type":13}],17:[function(e,t){"use strict";function n(e){return e>=48&&57>=e||e>=65&&70>=e||e>=97&&102>=e}function i(e){return e>=48&&55>=e}function r(e){return e>=48&&57>=e}function o(e){if(null===e)return!1;var t,o=e.length,a=0,s=!1;if(!o)return!1;if(t=e[a],("-"===t||"+"===t)&&(t=e[++a]),"0"===t){if(a+1===o)return!0;if(t=e[++a],"b"===t){for(a++;o>a;a++)if(t=e[a],"_"!==t){if("0"!==t&&"1"!==t)return!1;s=!0}return s}if("x"===t){for(a++;o>a;a++)if(t=e[a],"_"!==t){if(!n(e.charCodeAt(a)))return!1;s=!0}return s}for(;o>a;a++)if(t=e[a],"_"!==t){if(!i(e.charCodeAt(a)))return!1;
s=!0}return s}for(;o>a;a++)if(t=e[a],"_"!==t){if(":"===t)break;if(!r(e.charCodeAt(a)))return!1;s=!0}return s?":"!==t?!0:/^(:[0-5]?[0-9])+$/.test(e.slice(a)):!1}function a(e){var t,n,i=e,r=1,o=[];return-1!==i.indexOf("_")&&(i=i.replace(/_/g,"")),t=i[0],("-"===t||"+"===t)&&("-"===t&&(r=-1),i=i.slice(1),t=i[0]),"0"===i?0:"0"===t?"b"===i[1]?r*parseInt(i.slice(2),2):"x"===i[1]?r*parseInt(i,16):r*parseInt(i,8):-1!==i.indexOf(":")?(i.split(":").forEach(function(e){o.unshift(parseInt(e,10))}),i=0,n=1,o.forEach(function(e){i+=e*n,n*=60}),r*i):r*parseInt(i,10)}function s(e){return"[object Number]"===Object.prototype.toString.call(e)&&0===e%1&&!u.isNegativeZero(e)}var u=e("../common"),c=e("../type");t.exports=new c("tag:yaml.org,2002:int",{kind:"scalar",resolve:o,construct:a,predicate:s,represent:{binary:function(e){return"0b"+e.toString(2)},octal:function(e){return"0"+e.toString(8)},decimal:function(e){return e.toString(10)},hexadecimal:function(e){return"0x"+e.toString(16).toUpperCase()}},defaultStyle:"decimal",styleAliases:{binary:[2,"bin"],octal:[8,"oct"],decimal:[10,"dec"],hexadecimal:[16,"hex"]}})},{"../common":2,"../type":13}],18:[function(e,t){"use strict";function n(e){if(null===e)return!1;try{var t="("+e+")",n=a.parse(t,{range:!0});return"Program"!==n.type||1!==n.body.length||"ExpressionStatement"!==n.body[0].type||"FunctionExpression"!==n.body[0].expression.type?!1:!0}catch(i){return!1}}function i(e){var t,n="("+e+")",i=a.parse(n,{range:!0}),r=[];if("Program"!==i.type||1!==i.body.length||"ExpressionStatement"!==i.body[0].type||"FunctionExpression"!==i.body[0].expression.type)throw new Error("Failed to resolve function");return i.body[0].expression.params.forEach(function(e){r.push(e.name)}),t=i.body[0].expression.body.range,new Function(r,n.slice(t[0]+1,t[1]-1))}function r(e){return e.toString()}function o(e){return"[object Function]"===Object.prototype.toString.call(e)}var a;try{a=e("esprima")}catch(s){"undefined"!=typeof window&&(a=window.esprima)}var u=e("../../type");t.exports=new u("tag:yaml.org,2002:js/function",{kind:"scalar",resolve:n,construct:i,predicate:o,represent:r})},{"../../type":13,esprima:"esprima"}],19:[function(e,t){"use strict";function n(e){if(null===e)return!1;if(0===e.length)return!1;var t=e,n=/\/([gim]*)$/.exec(e),i="";if("/"===t[0]){if(n&&(i=n[1]),i.length>3)return!1;if("/"!==t[t.length-i.length-1])return!1;t=t.slice(1,t.length-i.length-1)}try{{new RegExp(t,i)}return!0}catch(r){return!1}}function i(e){var t=e,n=/\/([gim]*)$/.exec(e),i="";return"/"===t[0]&&(n&&(i=n[1]),t=t.slice(1,t.length-i.length-1)),new RegExp(t,i)}function r(e){var t="/"+e.source+"/";return e.global&&(t+="g"),e.multiline&&(t+="m"),e.ignoreCase&&(t+="i"),t}function o(e){return"[object RegExp]"===Object.prototype.toString.call(e)}var a=e("../../type");t.exports=new a("tag:yaml.org,2002:js/regexp",{kind:"scalar",resolve:n,construct:i,predicate:o,represent:r})},{"../../type":13}],20:[function(e,t){"use strict";function n(){return!0}function i(){return void 0}function r(){return""}function o(e){return"undefined"==typeof e}var a=e("../../type");t.exports=new a("tag:yaml.org,2002:js/undefined",{kind:"scalar",resolve:n,construct:i,predicate:o,represent:r})},{"../../type":13}],21:[function(e,t){"use strict";var n=e("../type");t.exports=new n("tag:yaml.org,2002:map",{kind:"mapping",construct:function(e){return null!==e?e:{}}})},{"../type":13}],22:[function(e,t){"use strict";function n(e){return"<<"===e||null===e}var i=e("../type");t.exports=new i("tag:yaml.org,2002:merge",{kind:"scalar",resolve:n})},{"../type":13}],23:[function(e,t){"use strict";function n(e){if(null===e)return!0;var t=e.length;return 1===t&&"~"===e||4===t&&("null"===e||"Null"===e||"NULL"===e)}function i(){return null}function r(e){return null===e}var o=e("../type");t.exports=new o("tag:yaml.org,2002:null",{kind:"scalar",resolve:n,construct:i,predicate:r,represent:{canonical:function(){return"~"},lowercase:function(){return"null"},uppercase:function(){return"NULL"},camelcase:function(){return"Null"}},defaultStyle:"lowercase"})},{"../type":13}],24:[function(e,t){"use strict";function n(e){if(null===e)return!0;var t,n,i,r,s,u=[],c=e;for(t=0,n=c.length;n>t;t+=1){if(i=c[t],s=!1,"[object Object]"!==a.call(i))return!1;for(r in i)if(o.call(i,r)){if(s)return!1;s=!0}if(!s)return!1;if(-1!==u.indexOf(r))return!1;u.push(r)}return!0}function i(e){return null!==e?e:[]}var r=e("../type"),o=Object.prototype.hasOwnProperty,a=Object.prototype.toString;t.exports=new r("tag:yaml.org,2002:omap",{kind:"sequence",resolve:n,construct:i})},{"../type":13}],25:[function(e,t){"use strict";function n(e){if(null===e)return!0;var t,n,i,r,a,s=e;for(a=new Array(s.length),t=0,n=s.length;n>t;t+=1){if(i=s[t],"[object Object]"!==o.call(i))return!1;if(r=Object.keys(i),1!==r.length)return!1;a[t]=[r[0],i[r[0]]]}return!0}function i(e){if(null===e)return[];var t,n,i,r,o,a=e;for(o=new Array(a.length),t=0,n=a.length;n>t;t+=1)i=a[t],r=Object.keys(i),o[t]=[r[0],i[r[0]]];return o}var r=e("../type"),o=Object.prototype.toString;t.exports=new r("tag:yaml.org,2002:pairs",{kind:"sequence",resolve:n,construct:i})},{"../type":13}],26:[function(e,t){"use strict";var n=e("../type");t.exports=new n("tag:yaml.org,2002:seq",{kind:"sequence",construct:function(e){return null!==e?e:[]}})},{"../type":13}],27:[function(e,t){"use strict";function n(e){if(null===e)return!0;var t,n=e;for(t in n)if(o.call(n,t)&&null!==n[t])return!1;return!0}function i(e){return null!==e?e:{}}var r=e("../type"),o=Object.prototype.hasOwnProperty;t.exports=new r("tag:yaml.org,2002:set",{kind:"mapping",resolve:n,construct:i})},{"../type":13}],28:[function(e,t){"use strict";var n=e("../type");t.exports=new n("tag:yaml.org,2002:str",{kind:"scalar",construct:function(e){return null!==e?e:""}})},{"../type":13}],29:[function(e,t){"use strict";function n(e){if(null===e)return!1;var t;return t=a.exec(e),null===t?!1:!0}function i(e){var t,n,i,r,o,s,u,c,l,p,f=0,d=null;if(t=a.exec(e),null===t)throw new Error("Date resolve error");if(n=+t[1],i=+t[2]-1,r=+t[3],!t[4])return new Date(Date.UTC(n,i,r));if(o=+t[4],s=+t[5],u=+t[6],t[7]){for(f=t[7].slice(0,3);f.length<3;)f+="0";f=+f}return t[9]&&(c=+t[10],l=+(t[11]||0),d=6e4*(60*c+l),"-"===t[9]&&(d=-d)),p=new Date(Date.UTC(n,i,r,o,s,u,f)),d&&p.setTime(p.getTime()-d),p}function r(e){return e.toISOString()}var o=e("../type"),a=new RegExp("^([0-9][0-9][0-9][0-9])-([0-9][0-9]?)-([0-9][0-9]?)(?:(?:[Tt]|[ \\t]+)([0-9][0-9]?):([0-9][0-9]):([0-9][0-9])(?:\\.([0-9]*))?(?:[ \\t]*(Z|([-+])([0-9][0-9]?)(?::([0-9][0-9]))?))?)?$");t.exports=new o("tag:yaml.org,2002:timestamp",{kind:"scalar",resolve:n,construct:i,instanceOf:Date,represent:r})},{"../type":13}],30:[function(){},{}],"/":[function(e,t){"use strict";var n=e("./lib/js-yaml.js");t.exports=n},{"./lib/js-yaml.js":1}]},{},[])("/")});
// modified version of Node.JS inspect
(function (exports) {

  exports.inspect = function(obj, showHidden, depth) {
    var ctx = {
      showHidden: showHidden,
      seen: [],
      stylize: function(str, styleType) { return str; }
    };
    return formatValue(ctx, obj, (typeof depth === 'undefined' ? 2 : depth));
  }

  function arrayToHash(array) {
    var hash = {};

    array.forEach(function(val, idx) {
      hash[val] = true;
    });

    return hash;
  }

  function formatValue(ctx, value, recurseTimes) {
    // Provide a hook for user-specified inspect functions.
    // Check that value is an object with an inspect function on it
    if (value && typeof value.inspect === 'function' &&
        // Filter out the util module, it's inspect function is special
        value.inspect !== exports.inspect &&
        // Also filter out any prototype objects using the circular check.
        !(value.constructor && value.constructor.prototype === value)) {
      return String(value.inspect(recurseTimes));
    }

    // Primitive types cannot have properties
    var primitive = formatPrimitive(ctx, value);
    if (primitive) {
      return primitive;
    }

    // Look up the keys of the object.
    var keys = Object.keys(value);
    var visibleKeys = arrayToHash(keys);

    if (ctx.showHidden) {
      keys = Object.getOwnPropertyNames(value);
    }

    // Some type of object without properties can be shortcutted.
    if (keys.length === 0) {
      if (typeof value === 'function') {
        var name = value.name ? ': ' + value.name : '';
        return ctx.stylize('[Function' + name + ']', 'special');
      }
      if (isRegExp(value)) {
        return ctx.stylize(RegExp.prototype.toString.call(value), 'regexp');
      }
      if (isDate(value)) {
        return ctx.stylize(Date.prototype.toString.call(value), 'date');
      }
      if (isError(value)) {
        return formatError(value);
      }
    }

    var base = '', array = false, braces = ['{', '}'];

    // Make Array say that they are Array
    if (isArray(value)) {
      array = true;
      braces = ['[', ']'];
    }

    // Make functions say that they are functions
    if (typeof value === 'function') {
      var n = value.name ? ': ' + value.name : '';
      base = ' [Function' + n + ']';
    }

    // Make RegExps say that they are RegExps
    if (isRegExp(value)) {
      base = ' ' + RegExp.prototype.toString.call(value);
    }

    // Make dates with properties first say the date
    if (isDate(value)) {
      base = ' ' + Date.prototype.toUTCString.call(value);
    }

    // Make error with message first say the error
    if (isError(value)) {
      base = ' ' + formatError(value);
    }

    if (keys.length === 0 && (!array || value.length == 0)) {
      return braces[0] + base + braces[1];
    }

    if (recurseTimes < 0) {
      if (isRegExp(value)) {
        return ctx.stylize(RegExp.prototype.toString.call(value), 'regexp');
      } else {
        return ctx.stylize('[Object]', 'special');
      }
    }

    ctx.seen.push(value);

    var output;
    if (array) {
      output = formatArray(ctx, value, recurseTimes, visibleKeys, keys);
    } else {
      output = keys.map(function(key) {
        return formatProperty(ctx, value, recurseTimes, visibleKeys, key, array);
      });
    }

    ctx.seen.pop();

    return reduceToSingleString(output, base, braces);
  }


  function formatPrimitive(ctx, value) {
    switch (typeof value) {
      case 'undefined':
        return ctx.stylize('undefined', 'undefined');

      case 'string':
        var simple = '\'' + JSON.stringify(value).replace(/^"|"$/g, '')
                                                 .replace(/'/g, "\\'")
                                                 .replace(/\\"/g, '"') + '\'';
        return ctx.stylize(simple, 'string');

      case 'number':
        return ctx.stylize('' + value, 'number');

      case 'boolean':
        return ctx.stylize('' + value, 'boolean');
    }
    // For some reason typeof null is "object", so special case here.
    if (value === null) {
      return ctx.stylize('null', 'null');
    }
  }


  function formatError(value) {
    return '[' + Error.prototype.toString.call(value) + ']';
  }


  function formatArray(ctx, value, recurseTimes, visibleKeys, keys) {
    var output = [];
    for (var i = 0, l = value.length; i < l; ++i) {
      if (Object.prototype.hasOwnProperty.call(value, String(i))) {
        output.push(formatProperty(ctx, value, recurseTimes, visibleKeys,
            String(i), true));
      } else {
        output.push('');
      }
    }
    keys.forEach(function(key) {
      if (!key.match(/^\d+$/)) {
        output.push(formatProperty(ctx, value, recurseTimes, visibleKeys,
            key, true));
      }
    });
    return output;
  }


  function formatProperty(ctx, value, recurseTimes, visibleKeys, key, array) {
    var name, str, desc;
    desc = Object.getOwnPropertyDescriptor(value, key) || { value: value[key] };
    if (desc.get) {
      if (desc.set) {
        str = ctx.stylize('[Getter/Setter]', 'special');
      } else {
        str = ctx.stylize('[Getter]', 'special');
      }
    } else {
      if (desc.set) {
        str = ctx.stylize('[Setter]', 'special');
      }
    }
    if (!visibleKeys.hasOwnProperty(key)) {
      name = '[' + key + ']';
    }
    if (!str) {
      if (ctx.seen.indexOf(desc.value) < 0) {
        if (recurseTimes === null) {
          str = formatValue(ctx, desc.value, null);
        } else {
          str = formatValue(ctx, desc.value, recurseTimes - 1);
        }
        if (str.indexOf('\n') > -1) {
          if (array) {
            str = str.split('\n').map(function(line) {
              return '  ' + line;
            }).join('\n').substr(2);
          } else {
            str = '\n' + str.split('\n').map(function(line) {
              return '   ' + line;
            }).join('\n');
          }
        }
      } else {
        str = ctx.stylize('[Circular]', 'special');
      }
    }
    if (typeof name === 'undefined') {
      if (array && key.match(/^\d+$/)) {
        return str;
      }
      name = JSON.stringify('' + key);
      if (name.match(/^"([a-zA-Z_][a-zA-Z_0-9]*)"$/)) {
        name = name.substr(1, name.length - 2);
        name = ctx.stylize(name, 'name');
      } else {
        name = name.replace(/'/g, "\\'")
                   .replace(/\\"/g, '"')
                   .replace(/(^"|"$)/g, "'");
        name = ctx.stylize(name, 'string');
      }
    }

    return name + ': ' + str;
  }


  function reduceToSingleString(output, base, braces) {
    var numLinesEst = 0;
    var length = output.reduce(function(prev, cur) {
      numLinesEst++;
      if (cur.indexOf('\n') >= 0) numLinesEst++;
      return prev + cur.length + 1;
    }, 0);

    if (length > 60) {
      return braces[0] +
             (base === '' ? '' : base + '\n ') +
             ' ' +
             output.join(',\n  ') +
             ' ' +
             braces[1];
    }

    return braces[0] + base + ' ' + output.join(', ') + ' ' + braces[1];
  }


  // NOTE: These type checking functions intentionally don't use `instanceof`
  // because it is fragile and can be easily faked with `Object.create()`.
  function isArray(ar) {
    return Array.isArray(ar) ||
           (typeof ar === 'object' && objectToString(ar) === '[object Array]');
  }

  function isRegExp(re) {
    return typeof re === 'object' && objectToString(re) === '[object RegExp]';
  }

  function isDate(d) {
    return typeof d === 'object' && objectToString(d) === '[object Date]';
  }

  function isError(e) {
    return typeof e === 'object' && objectToString(e) === '[object Error]';
  }

  function objectToString(o) {
    return Object.prototype.toString.call(o);
  }


}(window));

/*!
 * elFinder - file manager for web
 * Version 2.0.2 (2014-03-31)
 * http://elfinder.org
 * 
 * Copyright 2009-2013, Studio 42
 * Licensed under a 3 clauses BSD license
 */
!function(e){window.elFinder=function(t,n){this.time("load");var i,r,a,o=this,t=e(t),s=e("<div/>").append(t.contents()),l=t.attr("style"),d=t.attr("id")||"",c="elfinder-"+(d||Math.random().toString().substr(2,7)),p="mousedown."+c,u="keydown."+c,h="keypress."+c,f=!0,m=!0,g="",v={path:"",url:"",tmbUrl:"",disabled:[],separator:"/",archives:[],extract:[],copyOverwrite:!0,tmb:!1},b={},y=[],w={},x={},k=[],C=[],I=[],F=new o.command(o),T="auto",z=400,P=e(document.createElement("audio")).hide().appendTo("body")[0],A=function(t){if(t.init)b={};else for(var n in b)b.hasOwnProperty(n)&&"directory"!=b[n].mime&&b[n].phash==g&&-1===e.inArray(n,C)&&delete b[n];g=t.cwd.hash,O(t.files),b[g]||O([t.cwd]),o.lastDir(g)},O=function(e){for(var t,n=e.length;n--;)if(t=e[n],t.name&&t.hash&&t.mime){if(!t.phash){var i="volume_"+t.name,r=o.i18n(i);i!=r&&(t.i18=r)}b[t.hash]=t}},D=function(t){var n=t.keyCode,i=!(!t.ctrlKey&&!t.metaKey);f&&(e.each(x,function(e,r){r.type==t.type&&r.keyCode==n&&r.shiftKey==t.shiftKey&&r.ctrlKey==i&&r.altKey==t.altKey&&(t.preventDefault(),t.stopPropagation(),r.callback(t,o),o.debug("shortcut-exec",e+" : "+r.description))}),9!=n||e(t.target).is(":input")||t.preventDefault())},M=new Date;return this.api=null,this.newAPI=!1,this.oldAPI=!1,this.OS=-1!==navigator.userAgent.indexOf("Mac")?"mac":-1!==navigator.userAgent.indexOf("Win")?"win":"other",this.UA=function(){var e=!document.uniqueID&&!window.opera&&!window.sidebar&&window.localStorage&&"undefined"==typeof window.orientation;return{ltIE6:"undefined"==typeof window.addEventListener&&"undefined"==typeof document.documentElement.style.maxHeight,ltIE7:"undefined"==typeof window.addEventListener&&"undefined"==typeof document.querySelectorAll,ltIE8:"undefined"==typeof window.addEventListener&&"undefined"==typeof document.getElementsByClassName,IE:document.uniqueID,Firefox:window.sidebar,Opera:window.opera,Webkit:e,Chrome:e&&window.chrome,Safari:e&&!window.chrome,Mobile:"undefined"!=typeof window.orientation}}(),this.options=e.extend(!0,{},this._options,n||{}),n.ui&&(this.options.ui=n.ui),n.commands&&(this.options.commands=n.commands),n.uiOptions&&n.uiOptions.toolbar&&(this.options.uiOptions.toolbar=n.uiOptions.toolbar),e.extend(this.options.contextmenu,n.contextmenu),this.requestType=/^(get|post)$/i.test(this.options.requestType)?this.options.requestType.toLowerCase():"get",this.customData=e.isPlainObject(this.options.customData)?this.options.customData:{},this.id=d,this.uploadURL=n.urlUpload||n.url,this.namespace=c,this.lang=this.i18[this.options.lang]&&this.i18[this.options.lang].messages?this.options.lang:"en",a="en"==this.lang?this.i18.en:e.extend(!0,{},this.i18.en,this.i18[this.lang]),this.direction=a.direction,this.messages=a.messages,this.dateFormat=this.options.dateFormat||a.dateFormat,this.fancyFormat=this.options.fancyDateFormat||a.fancyDateFormat,this.today=new Date(M.getFullYear(),M.getMonth(),M.getDate()).getTime()/1e3,this.yesterday=this.today-86400,r=this.options.UTCDate?"UTC":"",this.getHours="get"+r+"Hours",this.getMinutes="get"+r+"Minutes",this.getSeconds="get"+r+"Seconds",this.getDate="get"+r+"Date",this.getDay="get"+r+"Day",this.getMonth="get"+r+"Month",this.getFullYear="get"+r+"FullYear",this.cssClass="ui-helper-reset ui-helper-clearfix ui-widget ui-widget-content ui-corner-all elfinder elfinder-"+("rtl"==this.direction?"rtl":"ltr")+" "+this.options.cssClass,this.storage=function(){try{return"localStorage"in window&&null!==window.localStorage?o.localStorage:o.cookie}catch(e){return o.cookie}}(),this.viewType=this.storage("view")||this.options.defaultView||"icons",this.sortType=this.storage("sortType")||this.options.sortType||"name",this.sortOrder=this.storage("sortOrder")||this.options.sortOrder||"asc",this.sortStickFolders=this.storage("sortStickFolders"),this.sortStickFolders=null===this.sortStickFolders?!!this.options.sortStickFolders:!!this.sortStickFolders,this.sortRules=e.extend(!0,{},this._sortRules,this.options.sortsRules),e.each(this.sortRules,function(e,t){"function"!=typeof t&&delete o.sortRules[e]}),this.compare=e.proxy(this.compare,this),this.notifyDelay=this.options.notifyDelay>0?parseInt(this.options.notifyDelay):500,this.draggable={appendTo:"body",addClasses:!0,delay:30,distance:8,revert:!0,refreshPositions:!0,cursor:"move",cursorAt:{left:50,top:47},drag:function(e,t){t.helper.data("locked")||t.helper.toggleClass("elfinder-drag-helper-plus",e.shiftKey||e.ctrlKey||e.metaKey)},start:function(t,n){var i,r,a=e.map(n.helper.data("files")||[],function(e){return e||null});for(i=a.length;i--;)if(r=a[i],b[r].locked){n.helper.addClass("elfinder-drag-helper-plus").data("locked",!0);break}},stop:function(){o.trigger("focus").trigger("dragstop")},helper:function(t){var n,i,r=this.id?e(this):e(this).parents("[id]:first"),a=e('<div class="elfinder-drag-helper"><span class="elfinder-drag-helper-icon-plus"/></div>'),s=function(e){return'<div class="elfinder-cwd-icon '+o.mime2class(e)+' ui-corner-all"/>'};return o.trigger("dragstart",{target:r[0],originalEvent:t}),n=r.is("."+o.res("class","cwdfile"))?o.selected():[o.navId2Hash(r.attr("id"))],a.append(s(b[n[0]].mime)).data("files",n).data("locked",!1),(i=n.length)>1&&a.append(s(b[n[i-1]].mime)+'<span class="elfinder-drag-num">'+i+"</span>"),a}},this.droppable={tolerance:"pointer",accept:".elfinder-cwd-file-wrapper,.elfinder-navbar-dir,.elfinder-cwd-file",hoverClass:this.res("class","adroppable"),drop:function(t,n){var i,r,a,s=e(this),l=e.map(n.helper.data("files")||[],function(e){return e||null}),d=[],c="class";for(s.is("."+o.res(c,"cwd"))?r=g:s.is("."+o.res(c,"cwdfile"))?r=s.attr("id"):s.is("."+o.res(c,"navdir"))&&(r=o.navId2Hash(s.attr("id"))),i=l.length;i--;)a=l[i],a!=r&&b[a].phash!=r&&d.push(a);d.length&&(n.helper.hide(),o.clipboard(d,!(t.ctrlKey||t.shiftKey||t.metaKey||n.helper.data("locked"))),o.exec("paste",r),o.trigger("drop",{files:l}))}},this.enabled=function(){return t.is(":visible")&&f},this.visible=function(){return t.is(":visible")},this.root=function(e){for(var t,n=b[e||g];n&&n.phash;)n=b[n.phash];if(n)return n.hash;for(;t in b&&b.hasOwnProperty(t);)if(n=b[t],!n.phash&&"directory"==!n.mime&&n.read)return n.hash;return""},this.cwd=function(){return b[g]||{}},this.option=function(e){return v[e]||""},this.file=function(e){return b[e]},this.files=function(){return e.extend(!0,{},b)},this.parents=function(e){for(var t,n=[];t=this.file(e);)n.unshift(t.hash),e=t.phash;return n},this.path2array=function(e,t){for(var n,i=[];e&&(n=b[e])&&n.hash;)i.unshift(t&&n.i18?n.i18:n.name),e=n.phash;return i},this.path=function(e,t){return b[e]&&b[e].path?b[e].path:this.path2array(e,t).join(v.separator)},this.url=function(t){var n=b[t];if(!n||!n.read)return"";if(n.url)return n.url;if(v.url)return v.url+e.map(this.path2array(t),function(e){return encodeURIComponent(e)}).slice(1).join("/");var i=e.extend({},this.customData,{cmd:"file",target:n.hash});return this.oldAPI&&(i.cmd="open",i.current=n.phash),this.options.url+(-1===this.options.url.indexOf("?")?"?":"&")+e.param(i,!0)},this.tmb=function(e){var t=b[e],n=t&&t.tmb&&1!=t.tmb?v.tmbUrl+t.tmb:"";return n&&(this.UA.Opera||this.UA.IE)&&(n+="?_="+(new Date).getTime()),n},this.selected=function(){return y.slice(0)},this.selectedFiles=function(){return e.map(y,function(t){return b[t]?e.extend({},b[t]):null})},this.fileByName=function(e,t){var n;for(n in b)if(b.hasOwnProperty(n)&&b[n].phash==t&&b[n].name==e)return b[n]},this.validResponse=function(e,t){return t.error||this.rules[this.rules[e]?e:"defaults"](t)},this.request=function(t){var n,i,r,a=this,o=this.options,s=e.Deferred(),l=e.extend({},o.customData,{mimes:o.onlyMimes},t.data||t),d=l.cmd,c=!(t.preventDefault||t.preventFail),p=!(t.preventDefault||t.preventDone),u=e.extend({},t.notify),h=!!t.raw,f=t.syncOnFail,t=e.extend({url:o.url,async:!0,type:this.requestType,dataType:"json",cache:!1,data:l},t.options||{}),m=function(t){t.warning&&a.error(t.warning),"open"==d&&A(e.extend(!0,{},t)),t.removed&&t.removed.length&&a.remove(t),t.added&&t.added.length&&a.add(t),t.changed&&t.changed.length&&a.change(t),a.trigger(d,t),t.sync&&a.sync()},g=function(e,t){var n;switch(t){case"abort":n=e.quiet?"":["errConnect","errAbort"];break;case"timeout":n=["errConnect","errTimeout"];break;case"parsererror":n=["errResponse","errDataNotJSON"];break;default:n=403==e.status?["errConnect","errAccess"]:404==e.status?["errConnect","errNotFound"]:"errConnect"}s.reject(n,e,t)},b=function(t){return h?s.resolve(t):t?e.isPlainObject(t)?t.error?s.reject(t.error,i):a.validResponse(d,t)?(t=a.normalize(t),a.api||(a.api=t.api||1,a.newAPI=a.api>=2,a.oldAPI=!a.newAPI),t.options&&(v=e.extend({},v,t.options)),t.netDrivers&&(a.netDrivers=t.netDrivers),s.resolve(t),void(t.debug&&a.debug("backend-debug",t.debug))):s.reject("errResponse",i):s.reject(["errResponse","errDataNotJSON"],i):s.reject(["errResponse","errDataEmpty"],i)};if(p&&s.done(m),s.fail(function(e){e&&(c?a.error(e):a.debug("error",a.i18n(e)))}),!d)return s.reject("errCmdReq");if(f&&s.fail(function(e){e&&a.sync()}),u.type&&u.cnt&&(n=setTimeout(function(){a.notify(u),s.always(function(){u.cnt=-(parseInt(u.cnt)||0),a.notify(u)})},a.notifyDelay),s.always(function(){clearTimeout(n)})),"open"==d)for(;r=I.pop();)"pending"==r.state()&&(r.quiet=!0,r.abort());return delete t.preventFail,i=this.transport.send(t).fail(g).done(b),"open"==d&&(I.unshift(i),s.always(function(){var t=e.inArray(i,I);-1!==t&&I.splice(t,1)})),s},this.diff=function(t){var n={},i=[],r=[],a=[],o=function(e){for(var t=a.length;t--;)if(a[t].hash==e)return!0};return e.each(t,function(e,t){n[t.hash]=t}),e.each(b,function(e){!n[e]&&r.push(e)}),e.each(n,function(t,n){var r=b[t];r?e.each(n,function(e){return n[e]!=r[e]?(a.push(n),!1):void 0}):i.push(n)}),e.each(r,function(t,i){var s=b[i],l=s.phash;l&&"directory"==s.mime&&-1===e.inArray(l,r)&&n[l]&&!o(l)&&a.push(n[l])}),{added:i,removed:r,changed:a}},this.sync=function(){var t=this,n=e.Deferred().done(function(){t.trigger("sync")}),i={data:{cmd:"open",init:1,target:g,tree:this.ui.tree?1:0},preventDefault:!0},r={data:{cmd:"tree",target:g==this.root()?g:this.file(g).phash},preventDefault:!0};return e.when(this.request(i),this.request(r)).fail(function(e){n.reject(e),e&&t.request({data:{cmd:"open",target:t.lastDir(""),tree:1,init:1},notify:{type:"open",cnt:1,hideCnt:!0},preventDefault:!0})}).done(function(e,i){var r=t.diff(e.files.concat(i&&i.tree?i.tree:[]));return r.added.push(e.cwd),r.removed.length&&t.remove(r),r.added.length&&t.add(r),r.changed.length&&t.change(r),n.resolve(r)}),n},this.upload=function(e){return this.transport.upload(e,this)},this.bind=function(e,t){var n;if("function"==typeof t)for(e=(""+e).toLowerCase().split(/\s+/),n=0;n<e.length;n++)void 0===w[e[n]]&&(w[e[n]]=[]),w[e[n]].push(t);return this},this.unbind=function(e,t){var n=w[(""+e).toLowerCase()]||[],i=n.indexOf(t);return i>-1&&n.splice(i,1),t=null,this},this.trigger=function(t,n){var i,t=t.toLowerCase(),r=w[t]||[];if(this.debug("event-"+t,n),r.length)for(t=e.Event(t),i=0;i<r.length;i++){t.data=e.extend(!0,{},n);try{if(r[i](t,this)===!1||t.isDefaultPrevented()){this.debug("event-stoped",t.type);break}}catch(a){window.console&&window.console.log&&window.console.log(a)}}return this},this.shortcut=function(t){var n,i,r,a,o;if(this.options.allowShortcuts&&t.pattern&&e.isFunction(t.callback))for(n=t.pattern.toUpperCase().split(/\s+/),a=0;a<n.length;a++)i=n[a],o=i.split("+"),r=1==(r=o.pop()).length?r>0?r:r.charCodeAt(0):e.ui.keyCode[r],r&&!x[i]&&(x[i]={keyCode:r,altKey:-1!=e.inArray("ALT",o),ctrlKey:-1!=e.inArray("CTRL",o),shiftKey:-1!=e.inArray("SHIFT",o),type:t.type||"keydown",callback:t.callback,description:t.description,pattern:i});return this},this.shortcuts=function(){var t=[];return e.each(x,function(e,n){t.push([n.pattern,o.i18n(n.description)])}),t},this.clipboard=function(t,n){var i=function(){return e.map(k,function(e){return e.hash})};return void 0!==t&&(k.length&&this.trigger("unlockfiles",{files:i()}),C=[],k=e.map(t||[],function(e){var t=b[e];return t?(C.push(e),{hash:e,phash:t.phash,name:t.name,mime:t.mime,read:t.read,locked:t.locked,cut:!!n}):null}),this.trigger("changeclipboard",{clipboard:k.slice(0,k.length)}),n&&this.trigger("lockfiles",{files:i()})),k.slice(0,k.length)},this.isCommandEnabled=function(t){return this._commands[t]?-1===e.inArray(t,v.disabled):!1},this.exec=function(t,n,i){return this._commands[t]&&this.isCommandEnabled(t)?this._commands[t].exec(n,i):e.Deferred().reject("No such command")},this.dialog=function(n,i){return e("<div/>").append(n).appendTo(t).elfinderdialog(i)},this.getUI=function(e){return this.ui[e]||t},this.command=function(e){return void 0===e?this._commands:this._commands[e]},this.resize=function(e,n){t.css("width",e).height(n).trigger("resize"),this.trigger("resize",{width:t.width(),height:t.height()})},this.restoreSize=function(){this.resize(T,z)},this.show=function(){t.show(),this.enable().trigger("show")},this.hide=function(){this.disable().trigger("hide"),t.hide()},this.destroy=function(){t&&t[0].elfinder&&(this.trigger("destroy").disable(),w={},x={},e(document).add(t).unbind("."+this.namespace),o.trigger=function(){},t.children().remove(),t.append(s.contents()).removeClass(this.cssClass).attr("style",l),t[0].elfinder=null,i&&clearInterval(i))},e.fn.selectable&&e.fn.draggable&&e.fn.droppable?t.length?this.options.url?(e.extend(e.ui.keyCode,{F1:112,F2:113,F3:114,F4:115,F5:116,F6:117,F7:118,F8:119,F9:120}),this.dragUpload=!1,this.xhrUpload=("undefined"!=typeof XMLHttpRequestUpload||"undefined"!=typeof XMLHttpRequestEventTarget)&&"undefined"!=typeof File&&"undefined"!=typeof FormData,this.transport={},"object"==typeof this.options.transport&&(this.transport=this.options.transport,"function"==typeof this.transport.init&&this.transport.init(this)),"function"!=typeof this.transport.send&&(this.transport.send=function(t){return e.ajax(t)}),"iframe"==this.transport.upload?this.transport.upload=e.proxy(this.uploads.iframe,this):"function"==typeof this.transport.upload?this.dragUpload=!!this.options.dragUploadAllow:this.xhrUpload&&this.options.dragUploadAllow?(this.transport.upload=e.proxy(this.uploads.xhr,this),this.dragUpload=!0):this.transport.upload=e.proxy(this.uploads.iframe,this),this.error=function(){var e=arguments[0];return 1==arguments.length&&"function"==typeof e?o.bind("error",e):o.trigger("error",{error:e})},e.each(["enable","disable","load","open","reload","select","add","remove","change","dblclick","getfile","lockfiles","unlockfiles","dragstart","dragstop","search","searchend","viewchange"],function(t,n){o[n]=function(){var t=arguments[0];return 1==arguments.length&&"function"==typeof t?o.bind(n,t):o.trigger(n,e.isPlainObject(t)?t:{})}}),this.enable(function(){!f&&o.visible()&&o.ui.overlay.is(":hidden")&&(f=!0,e("texarea:focus,input:focus,button").blur(),t.removeClass("elfinder-disabled"))}).disable(function(){m=f,f=!1,t.addClass("elfinder-disabled")}).open(function(){y=[]}).select(function(t){y=e.map(t.data.selected||t.data.value||[],function(e){return b[e]?e:null})}).error(function(t){var n={cssClass:"elfinder-dialog-error",title:o.i18n(o.i18n("error")),resizable:!1,destroyOnClose:!0,buttons:{}};n.buttons[o.i18n(o.i18n("btnClose"))]=function(){e(this).elfinderdialog("close")},o.dialog('<span class="elfinder-dialog-icon elfinder-dialog-icon-error"/>'+o.i18n(t.data.error),n)}).bind("tree parents",function(e){O(e.data.tree||[])}).bind("tmb",function(t){e.each(t.data.images||[],function(e,t){b[e]&&(b[e].tmb=t)})}).add(function(e){O(e.data.added||[])}).change(function(t){e.each(t.data.changed||[],function(t,n){var i=n.hash;(b[i].width&&!n.width||b[i].height&&!n.height)&&(b[i].width=void 0,b[i].height=void 0),b[i]=b[i]?e.extend(b[i],n):n})}).remove(function(t){for(var n=t.data.removed||[],i=n.length,r=function(t){var n=b[t];n&&("directory"==n.mime&&n.dirs&&e.each(b,function(e,n){n.phash==t&&r(e)}),delete b[t])};i--;)r(n[i])}).bind("search",function(e){O(e.data.files)}).bind("rm",function(){var t=P.canPlayType&&P.canPlayType('audio/wav; codecs="1"');t&&""!=t&&"no"!=t&&e(P).html('<source src="./sounds/rm.wav" type="audio/wav">')[0].play()}),e.each(this.options.handlers,function(e,t){o.bind(e,t)}),this.history=new this.history(this),"function"==typeof this.options.getFileCallback&&this.commands.getfile&&(this.bind("dblclick",function(e){e.preventDefault(),o.exec("getfile").fail(function(){o.exec("open")})}),this.shortcut({pattern:"enter",description:this.i18n("cmdgetfile"),callback:function(){o.exec("getfile").fail(function(){o.exec("mac"==o.OS?"rename":"open")})}}).shortcut({pattern:"ctrl+enter",description:this.i18n("mac"==this.OS?"cmdrename":"cmdopen"),callback:function(){o.exec("mac"==o.OS?"rename":"open")}})),this._commands={},e.isArray(this.options.commands)||(this.options.commands=[]),e.each(["open","reload","back","forward","up","home","info","quicklook","getfile","help"],function(t,n){-1===e.inArray(n,o.options.commands)&&o.options.commands.push(n)}),e.each(this.options.commands,function(t,n){var i=o.commands[n];e.isFunction(i)&&!o._commands[n]&&(i.prototype=F,o._commands[n]=new i,o._commands[n].setup(n,o.options.commandsOptions[n]||{}))}),t.addClass(this.cssClass).bind(p,function(){!f&&o.enable()}),this.ui={workzone:e("<div/>").appendTo(t).elfinderworkzone(this),navbar:e("<div/>").appendTo(t).elfindernavbar(this,this.options.uiOptions.navbar||{}),contextmenu:e("<div/>").appendTo(t).elfindercontextmenu(this),overlay:e("<div/>").appendTo(t).elfinderoverlay({show:function(){o.disable()},hide:function(){m&&o.enable()}}),cwd:e("<div/>").appendTo(t).elfindercwd(this,this.options.uiOptions.cwd||{}),notify:this.dialog("",{cssClass:"elfinder-dialog-notify",position:{top:"12px",right:"12px"},resizable:!1,autoOpen:!1,title:"&nbsp;",width:280}),statusbar:e('<div class="ui-widget-header ui-helper-clearfix ui-corner-bottom elfinder-statusbar"/>').hide().appendTo(t)},e.each(this.options.ui||[],function(n,i){var r="elfinder"+i,a=o.options.uiOptions[i]||{};!o.ui[i]&&e.fn[r]&&(o.ui[i]=e("<"+(a.tag||"div")+"/>").appendTo(t)[r](o,a))}),t[0].elfinder=this,this.options.resizable&&e.fn.resizable&&t.resizable({handles:"se",minWidth:300,minHeight:200}),this.options.width&&(T=this.options.width),this.options.height&&(z=parseInt(this.options.height)),o.resize(T,z),e(document).bind("click."+this.namespace,function(n){f&&!e(n.target).closest(t).length&&o.disable()}).bind(u+" "+h,D),o.options.useBrowserHistory&&e(window).on("popstate",function(t){var n=t.originalEvent.state&&t.originalEvent.state.thash;n&&!e.isEmptyObject(o.files())&&o.request({data:{cmd:"open",target:n,onhistory:1},notify:{type:"open",cnt:1,hideCnt:!0},syncOnFail:!0})}),this.trigger("init").request({data:{cmd:"open",target:o.startDir(),init:1,tree:this.ui.tree?1:0},preventDone:!0,notify:{type:"open",cnt:1,hideCnt:!0},freeze:!0}).fail(function(){o.trigger("fail").disable().lastDir(""),w={},x={},e(document).add(t).unbind("."+this.namespace),o.trigger=function(){}}).done(function(t){o.load().debug("api",o.api),t=e.extend(!0,{},t),A(t),o.trigger("open",t)}),void this.one("load",function(){t.trigger("resize"),o.options.sync>1e3&&(i=setInterval(function(){o.sync()},o.options.sync))})):alert(this.i18n("errURL")):alert(this.i18n("errNode")):alert(this.i18n("errJqui"))},elFinder.prototype={res:function(e,t){return this.resources[e]&&this.resources[e][t]},i18:{en:{translator:"",language:"English",direction:"ltr",dateFormat:"d.m.Y H:i",fancyDateFormat:"$1 H:i",messages:{}},months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"]},kinds:{unknown:"Unknown",directory:"Folder",symlink:"Alias","symlink-broken":"AliasBroken","application/x-empty":"TextPlain","application/postscript":"Postscript","application/vnd.ms-office":"MsOffice","application/vnd.ms-word":"MsWord","application/vnd.openxmlformats-officedocument.wordprocessingml.document":"MsWord","application/vnd.ms-word.document.macroEnabled.12":"MsWord","application/vnd.openxmlformats-officedocument.wordprocessingml.template":"MsWord","application/vnd.ms-word.template.macroEnabled.12":"MsWord","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":"MsWord","application/vnd.ms-excel":"MsExcel","application/vnd.ms-excel.sheet.macroEnabled.12":"MsExcel","application/vnd.openxmlformats-officedocument.spreadsheetml.template":"MsExcel","application/vnd.ms-excel.template.macroEnabled.12":"MsExcel","application/vnd.ms-excel.sheet.binary.macroEnabled.12":"MsExcel","application/vnd.ms-excel.addin.macroEnabled.12":"MsExcel","application/vnd.ms-powerpoint":"MsPP","application/vnd.openxmlformats-officedocument.presentationml.presentation":"MsPP","application/vnd.ms-powerpoint.presentation.macroEnabled.12":"MsPP","application/vnd.openxmlformats-officedocument.presentationml.slideshow":"MsPP","application/vnd.ms-powerpoint.slideshow.macroEnabled.12":"MsPP","application/vnd.openxmlformats-officedocument.presentationml.template":"MsPP","application/vnd.ms-powerpoint.template.macroEnabled.12":"MsPP","application/vnd.ms-powerpoint.addin.macroEnabled.12":"MsPP","application/vnd.openxmlformats-officedocument.presentationml.slide":"MsPP","application/vnd.ms-powerpoint.slide.macroEnabled.12":"MsPP","application/pdf":"PDF","application/xml":"XML","application/vnd.oasis.opendocument.text":"OO","application/vnd.oasis.opendocument.text-template":"OO","application/vnd.oasis.opendocument.text-web":"OO","application/vnd.oasis.opendocument.text-master":"OO","application/vnd.oasis.opendocument.graphics":"OO","application/vnd.oasis.opendocument.graphics-template":"OO","application/vnd.oasis.opendocument.presentation":"OO","application/vnd.oasis.opendocument.presentation-template":"OO","application/vnd.oasis.opendocument.spreadsheet":"OO","application/vnd.oasis.opendocument.spreadsheet-template":"OO","application/vnd.oasis.opendocument.chart":"OO","application/vnd.oasis.opendocument.formula":"OO","application/vnd.oasis.opendocument.database":"OO","application/vnd.oasis.opendocument.image":"OO","application/vnd.openofficeorg.extension":"OO","application/x-shockwave-flash":"AppFlash","application/flash-video":"Flash video","application/x-bittorrent":"Torrent","application/javascript":"JS","application/rtf":"RTF","application/rtfd":"RTF","application/x-font-ttf":"TTF","application/x-font-otf":"OTF","application/x-rpm":"RPM","application/x-web-config":"TextPlain","application/xhtml+xml":"HTML","application/docbook+xml":"DOCBOOK","application/x-awk":"AWK","application/x-gzip":"GZIP","application/x-bzip2":"BZIP","application/zip":"ZIP","application/x-zip":"ZIP","application/x-rar":"RAR","application/x-tar":"TAR","application/x-7z-compressed":"7z","application/x-jar":"JAR","text/plain":"TextPlain","text/x-php":"PHP","text/html":"HTML","text/javascript":"JS","text/css":"CSS","text/rtf":"RTF","text/rtfd":"RTF","text/x-c":"C","text/x-csrc":"C","text/x-chdr":"CHeader","text/x-c++":"CPP","text/x-c++src":"CPP","text/x-c++hdr":"CPPHeader","text/x-shellscript":"Shell","application/x-csh":"Shell","text/x-python":"Python","text/x-java":"Java","text/x-java-source":"Java","text/x-ruby":"Ruby","text/x-perl":"Perl","text/x-sql":"SQL","text/xml":"XML","text/x-comma-separated-values":"CSV","image/x-ms-bmp":"BMP","image/jpeg":"JPEG","image/gif":"GIF","image/png":"PNG","image/tiff":"TIFF","image/x-targa":"TGA","image/vnd.adobe.photoshop":"PSD","image/xbm":"XBITMAP","image/pxm":"PXM","audio/mpeg":"AudioMPEG","audio/midi":"AudioMIDI","audio/ogg":"AudioOGG","audio/mp4":"AudioMPEG4","audio/x-m4a":"AudioMPEG4","audio/wav":"AudioWAV","audio/x-mp3-playlist":"AudioPlaylist","video/x-dv":"VideoDV","video/mp4":"VideoMPEG4","video/mpeg":"VideoMPEG","video/x-msvideo":"VideoAVI","video/quicktime":"VideoMOV","video/x-ms-wmv":"VideoWM","video/x-flv":"VideoFlash","video/x-matroska":"VideoMKV","video/ogg":"VideoOGG"},rules:{defaults:function(t){return!t||t.added&&!e.isArray(t.added)||t.removed&&!e.isArray(t.removed)||t.changed&&!e.isArray(t.changed)?!1:!0},open:function(t){return t&&t.cwd&&t.files&&e.isPlainObject(t.cwd)&&e.isArray(t.files)},tree:function(t){return t&&t.tree&&e.isArray(t.tree)},parents:function(t){return t&&t.tree&&e.isArray(t.tree)},tmb:function(t){return t&&t.images&&(e.isPlainObject(t.images)||e.isArray(t.images))},upload:function(t){return t&&(e.isPlainObject(t.added)||e.isArray(t.added))},search:function(t){return t&&t.files&&e.isArray(t.files)}},commands:{},parseUploadData:function(t){var n;if(!e.trim(t))return{error:["errResponse","errDataEmpty"]};try{n=e.parseJSON(t)}catch(i){return{error:["errResponse","errDataNotJSON"]}}return this.validResponse("upload",n)?(n=this.normalize(n),n.removed=e.map(n.added||[],function(e){return e.hash}),n):{error:["errResponse"]}},iframeCnt:0,uploads:{iframe:function(t,n){var i,r,a,o,s=n?n:this,l=t.input,d=e.Deferred().fail(function(e){e&&s.error(e)}).done(function(e){e.warning&&s.error(e.warning),e.removed&&s.remove(e),e.added&&s.add(e),e.changed&&s.change(e),s.trigger("upload",e),e.sync&&s.sync()}),c="iframe-"+s.namespace+ ++s.iframeCnt,p=e('<form action="'+s.uploadURL+'" method="post" enctype="multipart/form-data" encoding="multipart/form-data" target="'+c+'" style="display:none"><input type="hidden" name="cmd" value="upload" /></form>'),u=this.UA.IE,h=function(){o&&clearTimeout(o),a&&clearTimeout(a),r&&s.notify({type:"upload",cnt:-i}),setTimeout(function(){u&&e('<iframe src="javascript:false;"/>').appendTo(p),p.remove(),f.remove()},100)},f=e('<iframe src="'+(u?"javascript:false;":"about:blank")+'" name="'+c+'" style="position:absolute;left:-1000px;top:-1000px" />').bind("load",function(){f.unbind("load").bind("load",function(){var e=s.parseUploadData(f.contents().text());h(),e.error?d.reject(e.error):d.resolve(e)}),a=setTimeout(function(){r=!0,s.notify({type:"upload",cnt:i})},s.options.notifyDelay),s.options.iframeTimeout>0&&(o=setTimeout(function(){h(),d.reject([errors.connect,errors.timeout])},s.options.iframeTimeout)),p.submit()});return l&&e(l).is(":file")&&e(l).val()?(p.append(l),i=l.files?l.files.length:1,p.append('<input type="hidden" name="'+(s.newAPI?"target":"current")+'" value="'+s.cwd().hash+'"/>').append('<input type="hidden" name="html" value="1"/>').append(e(l).attr("name","upload[]")),e.each(s.options.onlyMimes||[],function(e,t){p.append('<input type="hidden" name="mimes[]" value="'+t+'"/>')}),e.each(s.options.customData,function(e,t){p.append('<input type="hidden" name="'+e+'" value="'+t+'"/>')}),p.appendTo("body"),f.appendTo("body"),d):d.reject()},xhr:function(t,n){var i,r=n?n:this,a=e.Deferred().fail(function(e){e&&r.error(e)}).done(function(e){e.warning&&r.error(e.warning),e.removed&&r.remove(e),e.added&&r.add(e),e.changed&&r.change(e),r.trigger("upload",e),e.sync&&r.sync()}).always(function(){i&&clearTimeout(i),p&&r.notify({type:"upload",cnt:-d,progress:100*d})}),o=new XMLHttpRequest,s=new FormData,l=t.input?t.input.files:t.files,d=l.length,c=5,p=!1,u=function(){return setTimeout(function(){p=!0,r.notify({type:"upload",cnt:d,progress:c*d})},r.options.notifyDelay)};return d?(o.addEventListener("error",function(){a.reject("errConnect")},!1),o.addEventListener("abort",function(){a.reject(["errConnect","errAbort"])},!1),o.addEventListener("load",function(){var e,t=o.status;return t>500?a.reject("errResponse"):200!=t?a.reject("errConnect"):4!=o.readyState?a.reject(["errConnect","errTimeout"]):o.responseText?(e=r.parseUploadData(o.responseText),void(e.error?a.reject(e.error):a.resolve(e))):a.reject(["errResponse","errDataEmpty"])},!1),o.upload.addEventListener("progress",function(e){var t,n=c;e.lengthComputable&&(t=parseInt(100*e.loaded/e.total),t>0&&!i&&(i=u()),t-n>4&&(c=t,p&&r.notify({type:"upload",cnt:0,progress:(c-n)*d})))},!1),o.open("POST",r.uploadURL,!0),s.append("cmd","upload"),s.append(r.newAPI?"target":"current",r.cwd().hash),e.each(r.options.customData,function(e,t){s.append(e,t)}),e.each(r.options.onlyMimes,function(e,t){s.append("mimes["+e+"]",t)}),e.each(l,function(e,t){s.append("upload[]",t)}),o.onreadystatechange=function(){4==o.readyState&&0==o.status&&a.reject(["errConnect","errAbort"])},o.send(s),this.UA.Safari&&t.files||(i=u()),a):a.reject()}},one:function(t,n){var i=this,r=e.proxy(n,function(e){return setTimeout(function(){i.unbind(e.type,r)},3),n.apply(this,arguments)});return this.bind(t,r)},localStorage:function(e,t){var n=window.localStorage;if(e="elfinder-"+e+this.id,null===t)return console.log("remove",e),n.removeItem(e);if(void 0!==t)try{n.setItem(e,t)}catch(i){n.clear(),n.setItem(e,t)}return n.getItem(e)},cookie:function(t,n){var i,r,a,o;if(t="elfinder-"+t+this.id,void 0===n){if(document.cookie&&""!=document.cookie)for(a=document.cookie.split(";"),t+="=",o=0;o<a.length;o++)if(a[o]=e.trim(a[o]),a[o].substring(0,t.length)==t)return decodeURIComponent(a[o].substring(t.length));return""}return r=e.extend({},this.options.cookie),null===n&&(n="",r.expires=-1),"number"==typeof r.expires&&(i=new Date,i.setTime(i.getTime()+864e5*r.expires),r.expires=i),document.cookie=t+"="+encodeURIComponent(n)+"; expires="+r.expires.toUTCString()+(r.path?"; path="+r.path:"")+(r.domain?"; domain="+r.domain:"")+(r.secure?"; secure":""),n},startDir:function(){var e=window.location.hash;return e&&e.match(/^#elf_/)?e.replace(/^#elf_/,""):this.lastDir()},lastDir:function(e){return this.options.rememberLastDir?this.storage("lastdir",e):""},_node:e("<span/>"),escape:function(e){return this._node.text(e).html()},normalize:function(t){var n=function(e){return e&&e.hash&&e.name&&e.mime?("application/x-empty"==e.mime&&(e.mime="text/plain"),e):null};return t.files&&(t.files=e.map(t.files,n)),t.tree&&(t.tree=e.map(t.tree,n)),t.added&&(t.added=e.map(t.added,n)),t.changed&&(t.changed=e.map(t.changed,n)),t.api&&(t.init=!0),t},setSort:function(e,t,n){this.storage("sortType",this.sortType=this.sortRules[e]?e:"name"),this.storage("sortOrder",this.sortOrder=/asc|desc/.test(t)?t:"asc"),this.storage("sortStickFolders",(this.sortStickFolders=!!n)?1:""),this.trigger("sortchange")},_sortRules:{name:function(e,t){return e.name.toLowerCase().localeCompare(t.name.toLowerCase())},size:function(e,t){var n=parseInt(e.size)||0,i=parseInt(t.size)||0;return n==i?0:n>i?1:-1},kind:function(e,t){return e.mime.localeCompare(t.mime)},date:function(e,t){var n=e.ts||e.date,i=t.ts||t.date;return n==i?0:n>i?1:-1}},compare:function(e,t){var n,i=this,r=i.sortType,a="asc"==i.sortOrder,o=i.sortStickFolders,s=i.sortRules,l=s[r],d="directory"==e.mime,c="directory"==t.mime;if(o){if(d&&!c)return-1;if(!d&&c)return 1}return n=a?l(e,t):l(t,e),"name"!=r&&0==n?n=a?s.name(e,t):s.name(t,e):n},sortFiles:function(e){return e.sort(this.compare)},notify:function(t){var n,i,r,a=t.type,o=this.i18n(this.messages["ntf"+a]?"ntf"+a:"ntfsmth"),s=this.ui.notify,l=s.children(".elfinder-notify-"+a),d='<div class="elfinder-notify elfinder-notify-{type}"><span class="elfinder-dialog-icon elfinder-dialog-icon-{type}"/><span class="elfinder-notify-msg">{msg}</span> <span class="elfinder-notify-cnt"/><div class="elfinder-notify-progressbar"><div class="elfinder-notify-progress"/></div></div>',c=t.cnt,p=t.progress>=0&&t.progress<=100?t.progress:0;return a?(l.length||(l=e(d.replace(/\{type\}/g,a).replace(/\{msg\}/g,o)).appendTo(s).data("cnt",0),p&&l.data({progress:0,total:0})),n=c+parseInt(l.data("cnt")),n>0?(!t.hideCnt&&l.children(".elfinder-notify-cnt").text("("+n+")"),s.is(":hidden")&&s.elfinderdialog("open"),l.data("cnt",n),100>p&&(i=l.data("total"))>=0&&(r=l.data("progress"))>=0&&(i=c+parseInt(l.data("total")),r=p+r,p=parseInt(r/i),l.data({progress:r,total:i}),s.find(".elfinder-notify-progress").animate({width:(100>p?p:100)+"%"},20))):(l.remove(),!s.children().length&&s.elfinderdialog("close")),this):this},confirm:function(t){var n,i=!1,r={cssClass:"elfinder-dialog-confirm",modal:!0,resizable:!1,title:this.i18n(t.title||"confirmReq"),buttons:{},close:function(){!i&&t.cancel.callback(),e(this).elfinderdialog("destroy")
}},a=this.i18n("apllyAll");return t.reject&&(r.buttons[this.i18n(t.reject.label)]=function(){t.reject.callback(!(!n||!n.prop("checked"))),i=!0,e(this).elfinderdialog("close")}),r.buttons[this.i18n(t.accept.label)]=function(){t.accept.callback(!(!n||!n.prop("checked"))),i=!0,e(this).elfinderdialog("close")},r.buttons[this.i18n(t.cancel.label)]=function(){e(this).elfinderdialog("close")},t.all&&(t.reject&&(r.width=370),r.create=function(){n=e('<input type="checkbox" />'),e(this).next().children().before(e("<label>"+a+"</label>").prepend(n))},r.open=function(){var t=e(this).next(),n=parseInt(t.children(":first").outerWidth()+t.children(":last").outerWidth());n>parseInt(t.width())&&e(this).closest(".elfinder-dialog").width(n+30)}),this.dialog('<span class="elfinder-dialog-icon elfinder-dialog-icon-confirm"/>'+this.i18n(t.text),r)},uniqueName:function(e,t){var n,i,r=0,a="";if(e=this.i18n(e),t=t||this.cwd().hash,-1!=(n=e.indexOf(".txt"))&&(a=".txt",e=e.substr(0,n)),i=e+a,!this.fileByName(i,t))return i;for(;1e4>r;)if(i=e+" "+ ++r+a,!this.fileByName(i,t))return i;return e+Math.random()+a},i18n:function(){var t,n,i,r=this,a=this.messages,o=[],s=[],l=function(e){var t;return 0===e.indexOf("#")&&(t=r.file(e.substr(1)))?t.name:e};for(t=0;t<arguments.length;t++)if(i=arguments[t],"string"==typeof i)o.push(l(i));else if(e.isArray(i))for(n=0;n<i.length;n++)"string"==typeof i[n]&&o.push(l(i[n]));for(t=0;t<o.length;t++)-1===e.inArray(t,s)&&(i=o[t],i=a[i]||i,i=i.replace(/\$(\d+)/g,function(e,n){return n=t+parseInt(n),n>0&&o[n]&&s.push(n),o[n]||""}),o[t]=i);return e.map(o,function(t,n){return-1===e.inArray(n,s)?t:null}).join("<br>")},mime2class:function(e){var t="elfinder-cwd-icon-";return e=e.split("/"),t+e[0]+("image"!=e[0]&&e[1]?" "+t+e[1].replace(/(\.|\+)/g,"-"):"")},mime2kind:function(e){var t,n="object"==typeof e?e.mime:e;return t=e.alias?"Alias":this.kinds[n]?this.kinds[n]:0===n.indexOf("text")?"Text":0===n.indexOf("image")?"Image":0===n.indexOf("audio")?"Audio":0===n.indexOf("video")?"Video":0===n.indexOf("application")?"App":n,this.messages["kind"+t]?this.i18n("kind"+t):n;var n,t},formatDate:function(e,t){var n,i,r,a,o,s,l,d,c,p,u,h=this,t=t||e.ts,f=h.i18;return h.options.clientFormatDate&&t>0?(n=new Date(1e3*t),d=n[h.getHours](),c=d>12?d-12:d,p=n[h.getMinutes](),u=n[h.getSeconds](),a=n[h.getDate](),o=n[h.getDay](),s=n[h.getMonth]()+1,l=n[h.getFullYear](),i=t>=this.yesterday?this.fancyFormat:this.dateFormat,r=i.replace(/[a-z]/gi,function(e){switch(e){case"d":return a>9?a:"0"+a;case"j":return a;case"D":return h.i18n(f.daysShort[o]);case"l":return h.i18n(f.days[o]);case"m":return s>9?s:"0"+s;case"n":return s;case"M":return h.i18n(f.monthsShort[s-1]);case"F":return h.i18n(f.months[s-1]);case"Y":return l;case"y":return(""+l).substr(2);case"H":return d>9?d:"0"+d;case"G":return d;case"g":return c;case"h":return c>9?c:"0"+c;case"a":return d>12?"pm":"am";case"A":return d>12?"PM":"AM";case"i":return p>9?p:"0"+p;case"s":return u>9?u:"0"+u}return e}),t>=this.yesterday?r.replace("$1",this.i18n(t>=this.today?"Today":"Yesterday")):r):e.date?e.date.replace(/([a-z]+)\s/i,function(e,t){return h.i18n(t)+" "}):h.i18n("dateUnknown")},perms2class:function(e){var t="";return e.read||e.write?e.read?e.write||(t="elfinder-ro"):t="elfinder-wo":t="elfinder-na",t},formatPermissions:function(e){var t=[];return e.read&&t.push(this.i18n("read")),e.write&&t.push(this.i18n("write")),t.length?t.join(" "+this.i18n("and")+" "):this.i18n("noaccess")},formatSize:function(e){var t=1,n="b";return"unknown"==e?this.i18n("unknown"):(e>1073741824?(t=1073741824,n="GB"):e>1048576?(t=1048576,n="MB"):e>1024&&(t=1024,n="KB"),e/=t,(e>0?t>=1048576?e.toFixed(2):Math.round(e):0)+" "+n)},navHash2Id:function(e){return"nav-"+e},navId2Hash:function(e){return"string"==typeof e?e.substr(4):!1},log:function(e){return window.console&&window.console.log&&window.console.log(e),this},debug:function(t,n){var i=this.options.debug;return("all"==i||i===!0||e.isArray(i)&&-1!=e.inArray(t,i))&&window.console&&window.console.log&&window.console.log("elfinder debug: ["+t+"] ["+this.id+"]",n),this},time:function(e){window.console&&window.console.time&&window.console.time(e)},timeEnd:function(e){window.console&&window.console.timeEnd&&window.console.timeEnd(e)}},elFinder.prototype.version="2.0.2",e.fn.elfinder=function(e){return"instance"==e?this.getElFinder():this.each(function(){var t="string"==typeof e?e:"";switch(this.elfinder||new elFinder(this,"object"==typeof e?e:{}),t){case"close":case"hide":this.elfinder.hide();break;case"open":case"show":this.elfinder.show();break;case"destroy":this.elfinder.destroy()}})},e.fn.getElFinder=function(){var e;return this.each(function(){return this.elfinder?(e=this.elfinder,!1):void 0}),e},elFinder.prototype._options={url:"",requestType:"get",transport:{},urlUpload:"",dragUploadAllow:"auto",iframeTimeout:0,customData:{},handlers:{},lang:"en",cssClass:"",commands:["open","reload","home","up","back","forward","getfile","quicklook","download","rm","duplicate","rename","mkdir","mkfile","upload","copy","cut","paste","edit","extract","archive","search","info","view","help","resize","sort","netmount"],commandsOptions:{getfile:{onlyURL:!1,multiple:!1,folders:!1,oncomplete:""},upload:{ui:"uploadbutton"},quicklook:{autoplay:!0,jplayer:"extensions/jplayer"},edit:{mimes:[],editors:[]},info:{nullUrlDirLinkSelf:!0},help:{view:["about","shortcuts","help"]}},getFileCallback:null,defaultView:"icons",ui:["toolbar","tree","path","stat"],uiOptions:{toolbar:[["back","forward"],["netmount"],["mkdir","mkfile","upload"],["open","download","getfile"],["info"],["quicklook"],["copy","cut","paste"],["rm"],["duplicate","rename","edit","resize"],["extract","archive"],["search"],["view","sort"],["help"]],tree:{openRootOnLoad:!0,syncTree:!0},navbar:{minWidth:150,maxWidth:500},cwd:{oldSchool:!1}},onlyMimes:[],sortRules:{},sortType:"name",sortOrder:"asc",sortStickFolders:!0,clientFormatDate:!0,UTCDate:!1,dateFormat:"",fancyDateFormat:"",width:"auto",height:400,resizable:!0,notifyDelay:500,allowShortcuts:!0,rememberLastDir:!0,useBrowserHistory:!0,showFiles:30,showThreshold:50,validName:!1,sync:0,loadTmbs:5,cookie:{expires:30,domain:"",path:"/",secure:!1},contextmenu:{navbar:["open","|","copy","cut","paste","duplicate","|","rm","|","info"],cwd:["reload","back","|","upload","mkdir","mkfile","paste","|","sort","|","info"],files:["getfile","|","open","quicklook","|","download","|","copy","cut","paste","duplicate","|","rm","|","edit","rename","resize","|","archive","extract","|","info"]},debug:["error","warning","event-destroy"]},elFinder.prototype.history=function(t){var n,i=this,r=!0,a=[],o=function(){a=[t.cwd().hash],n=0,r=!0},s=t.options.useBrowserHistory&&window.history&&window.history.pushState?window.history:null,l=function(s){return s&&i.canForward()||!s&&i.canBack()?(r=!1,t.exec("open",a[s?++n:--n]).fail(o)):e.Deferred().reject()};this.canBack=function(){return n>0},this.canForward=function(){return n<a.length-1},this.back=l,this.forward=function(){return l(!0)},t.open(function(){var e=a.length,i=t.cwd().hash;r&&(n>=0&&e>n+1&&a.splice(n+1),a[a.length-1]!=i&&a.push(i),n=a.length-1),r=!0,s&&(s.state?s.state.thash!=i&&s.pushState({thash:i},null,location.pathname+location.search+"#elf_"+i):s.replaceState({thash:i},null,location.pathname+location.search+"#elf_"+i))}).reload(o)},elFinder.prototype.command=function(t){this.fm=t,this.name="",this.title="",this.state=-1,this.alwaysEnabled=!1,this._disabled=!1,this.disableOnSearch=!1,this.updateOnSelect=!0,this._handlers={enable:function(){this.update(void 0,this.value)},disable:function(){this.update(-1,this.value)},"open reload load":function(){this._disabled=!(this.alwaysEnabled||this.fm.isCommandEnabled(this.name)),this.update(void 0,this.value),this.change()}},this.handlers={},this.shortcuts=[],this.options={ui:"button"},this.setup=function(t,n){var i,r,a=this,o=this.fm;for(this.name=t,this.title=o.messages["cmd"+t]?o.i18n("cmd"+t):t,this.options=e.extend({},this.options,n),this.listeners=[],this.updateOnSelect&&(this._handlers.select=function(){this.update(void 0,this.value)}),e.each(e.extend({},a._handlers,a.handlers),function(t,n){o.bind(t,e.proxy(n,a))}),i=0;i<this.shortcuts.length;i++)r=this.shortcuts[i],r.callback=e.proxy(r.callback||function(){this.exec()},this),!r.description&&(r.description=this.title),o.shortcut(r);this.disableOnSearch&&o.bind("search searchend",function(e){a._disabled="search"==e.type,a.update(void 0,a.value)}),this.init()},this.init=function(){},this.exec=function(){return e.Deferred().reject()},this.disabled=function(){return this.state<0},this.enabled=function(){return this.state>-1},this.active=function(){return this.state>0},this.getstate=function(){return-1},this.update=function(e,t){var n=this.state,i=this.value;this.state=this._disabled?-1:void 0!==e?e:this.getstate(),this.value=t,(n!=this.state||i!=this.value)&&this.change()},this.change=function(e){var t,n;if("function"==typeof e)this.listeners.push(e);else for(n=0;n<this.listeners.length;n++){t=this.listeners[n];try{t(this.state,this.value)}catch(i){this.fm.debug("error",i)}}return this},this.hashes=function(n){return n?e.map(e.isArray(n)?n:[n],function(e){return t.file(e)?e:null}):t.selected()},this.files=function(t){var n=this.fm;return t?e.map(e.isArray(t)?t:[t],function(e){return n.file(e)||null}):n.selectedFiles()}},elFinder.prototype.resources={"class":{hover:"ui-state-hover",active:"ui-state-active",disabled:"ui-state-disabled",draggable:"ui-draggable",droppable:"ui-droppable",adroppable:"elfinder-droppable-active",cwdfile:"elfinder-cwd-file",cwd:"elfinder-cwd",tree:"elfinder-tree",treeroot:"elfinder-navbar-root",navdir:"elfinder-navbar-dir",navdirwrap:"elfinder-navbar-dir-wrapper",navarrow:"elfinder-navbar-arrow",navsubtree:"elfinder-navbar-subtree",navcollapse:"elfinder-navbar-collapsed",navexpand:"elfinder-navbar-expanded",treedir:"elfinder-tree-dir",placedir:"elfinder-place-dir",searchbtn:"elfinder-button-search"},tpl:{perms:'<span class="elfinder-perms"/>',symlink:'<span class="elfinder-symlink"/>',navicon:'<span class="elfinder-nav-icon"/>',navspinner:'<span class="elfinder-navbar-spinner"/>',navdir:'<div class="elfinder-navbar-wrapper"><span id="{id}" class="ui-corner-all elfinder-navbar-dir {cssclass}"><span class="elfinder-navbar-arrow"/><span class="elfinder-navbar-icon"/>{symlink}{permissions}{name}</span><div class="elfinder-navbar-subtree"/></div>'},mimes:{text:["application/x-empty","application/javascript","application/xhtml+xml","audio/x-mp3-playlist","application/x-web-config","application/docbook+xml","application/x-php","application/x-perl","application/x-awk","application/x-config","application/x-csh","application/xml"]},mixin:{make:function(){var t=this.fm,n=this.name,i=t.getUI("cwd"),r=e.Deferred().fail(function(e){i.trigger("unselectall"),e&&t.error(e)}).always(function(){c.remove(),d.remove(),t.enable()}),a="tmp_"+parseInt(1e5*Math.random()),o=t.cwd().hash,s=new Date,l={hash:a,name:t.uniqueName(this.prefix),mime:this.mime,read:!0,write:!0,date:"Today "+s.getHours()+":"+s.getMinutes()},d=i.trigger("create."+t.namespace,l).find("#"+a),c=e('<input type="text"/>').keydown(function(t){t.stopImmediatePropagation(),t.keyCode==e.ui.keyCode.ESCAPE?r.reject():t.keyCode==e.ui.keyCode.ENTER&&c.blur()}).mousedown(function(e){e.stopPropagation()}).blur(function(){var i=e.trim(c.val()),s=c.parent();if(s.length){if(!i)return r.reject("errInvName");if(t.fileByName(i,o))return r.reject(["errExists",i]);s.html(t.escape(i)),t.lockfiles({files:[a]}),t.request({data:{cmd:n,name:i,target:o},notify:{type:n,cnt:1},preventFail:!0,syncOnFail:!0}).fail(function(e){r.reject(e)}).done(function(e){r.resolve(e)})}});return this.disabled()||!d.length?r.reject():(t.disable(),d.find(".elfinder-cwd-filename").empty("").append(c.val(l.name)),c.select().focus(),c[0].setSelectionRange&&c[0].setSelectionRange(0,l.name.replace(/\..+$/,"").length),r)}}},e.fn.dialogelfinder=function(t){var n="elfinderPosition",i="elfinderDestroyOnClose";if(this.not(".elfinder").each(function(){{var r=(e(document),e('<div class="ui-widget-header dialogelfinder-drag ui-corner-top">'+(t.title||"Files")+"</div>")),a=(e('<a href="#" class="dialogelfinder-drag-close ui-corner-all"><span class="ui-icon ui-icon-closethick"/></a>').appendTo(r).click(function(e){e.preventDefault(),a.dialogelfinder("close")}),e(this).addClass("dialogelfinder").css("position","absolute").hide().appendTo("body").draggable({handle:".dialogelfinder-drag",containment:"window"}).elfinder(t).prepend(r));a.elfinder("instance")}a.width(parseInt(a.width())||840).data(i,!!t.destroyOnClose).find(".elfinder-toolbar").removeClass("ui-corner-top"),t.position&&a.data(n,t.position),t.autoOpen!==!1&&e(this).dialogelfinder("open")}),"open"==t){var r=e(this),a=r.data(n)||{top:parseInt(e(document).scrollTop()+(e(window).height()<r.height()?2:(e(window).height()-r.height())/2)),left:parseInt(e(document).scrollLeft()+(e(window).width()<r.width()?2:(e(window).width()-r.width())/2))},o=100;r.is(":hidden")&&(e("body").find(":visible").each(function(){var t,n=e(this);this!==r[0]&&"absolute"==n.css("position")&&(t=parseInt(n.zIndex()))>o&&(o=t+1)}),r.zIndex(o).css(a).show().trigger("resize"),setTimeout(function(){r.trigger("resize").mousedown()},200))}else if("close"==t){var r=e(this);r.is(":visible")&&(r.data(i)?r.elfinder("destroy").remove():r.elfinder("close"))}else if("instance"==t)return e(this).getElFinder();return this},elFinder&&elFinder.prototype&&"object"==typeof elFinder.prototype.i18&&(elFinder.prototype.i18.en={translator:"Troex Nevelin &lt;troex@fury.scancode.ru&gt;",language:"English",direction:"ltr",dateFormat:"M d, Y h:i A",fancyDateFormat:"$1 h:i A",messages:{error:"Error",errUnknown:"Unknown error.",errUnknownCmd:"Unknown command.",errJqui:"Invalid jQuery UI configuration. Selectable, draggable and droppable components must be included.",errNode:"elFinder requires DOM Element to be created.",errURL:"Invalid elFinder configuration! URL option is not set.",errAccess:"Access denied.",errConnect:"Unable to connect to backend.",errAbort:"Connection aborted.",errTimeout:"Connection timeout.",errNotFound:"Backend not found.",errResponse:"Invalid backend response.",errConf:"Invalid backend configuration.",errJSON:"PHP JSON module not installed.",errNoVolumes:"Readable volumes not available.",errCmdParams:'Invalid parameters for command "$1".',errDataNotJSON:"Data is not JSON.",errDataEmpty:"Data is empty.",errCmdReq:"Backend request requires command name.",errOpen:'Unable to open "$1".',errNotFolder:"Object is not a folder.",errNotFile:"Object is not a file.",errRead:'Unable to read "$1".',errWrite:'Unable to write into "$1".',errPerm:"Permission denied.",errLocked:'"$1" is locked and can not be renamed, moved or removed.',errExists:'File named "$1" already exists.',errInvName:"Invalid file name.",errFolderNotFound:"Folder not found.",errFileNotFound:"File not found.",errTrgFolderNotFound:'Target folder "$1" not found.',errPopup:"Browser prevented opening popup window. To open file enable it in browser options.",errMkdir:'Unable to create folder "$1".',errMkfile:'Unable to create file "$1".',errRename:'Unable to rename "$1".',errCopyFrom:'Copying files from volume "$1" not allowed.',errCopyTo:'Copying files to volume "$1" not allowed.',errUpload:"Upload error.",errUploadFile:'Unable to upload "$1".',errUploadNoFiles:"No files found for upload.",errUploadTotalSize:"Data exceeds the maximum allowed size.",errUploadFileSize:"File exceeds maximum allowed size.",errUploadMime:"File type not allowed.",errUploadTransfer:'"$1" transfer error.',errNotReplace:'Object "$1" already exists at this location and can not be replaced by object with another type.',errReplace:'Unable to replace "$1".',errSave:'Unable to save "$1".',errCopy:'Unable to copy "$1".',errMove:'Unable to move "$1".',errCopyInItself:'Unable to copy "$1" into itself.',errRm:'Unable to remove "$1".',errRmSrc:"Unable remove source file(s).",errExtract:'Unable to extract files from "$1".',errArchive:"Unable to create archive.",errArcType:"Unsupported archive type.",errNoArchive:"File is not archive or has unsupported archive type.",errCmdNoSupport:"Backend does not support this command.",errReplByChild:"The folder “$1” can’t be replaced by an item it contains.",errArcSymlinks:"For security reason denied to unpack archives contains symlinks or files with not allowed names.",errArcMaxSize:"Archive files exceeds maximum allowed size.",errResize:'Unable to resize "$1".',errResizeDegree:"Invalid rotate degree.",errResizeRotate:"Image dose not rotated.",errResizeSize:"Invalid image size.",errResizeNoChange:"Image size not changed.",errUsupportType:"Unsupported file type.",errNotUTF8Content:'File "$1" is not in UTF-8 and cannot be edited.',errNetMount:'Unable to mount "$1".',errNetMountNoDriver:"Unsupported protocol.",errNetMountFailed:"Mount failed.",errNetMountHostReq:"Host required.",errSessionExpires:"Your session has expired due to inactivity.",errCreatingTempDir:'Unable to create temporary directory: "$1"',errFtpDownloadFile:'Unable to download file from FTP: "$1"',errFtpUploadFile:'Unable to upload file to FTP: "$1"',errFtpMkdir:'Unable to create remote directory on FTP: "$1"',errArchiveExec:'Error while archiving files: "$1"',errExtractExec:'Error while extracting files: "$1"',cmdarchive:"Create archive",cmdback:"Back",cmdcopy:"Copy",cmdcut:"Cut",cmddownload:"Download",cmdduplicate:"Duplicate",cmdedit:"Edit file",cmdextract:"Extract files from archive",cmdforward:"Forward",cmdgetfile:"Select files",cmdhelp:"About this software",cmdhome:"Home",cmdinfo:"Get info",cmdmkdir:"New folder",cmdmkfile:"New text file",cmdopen:"Open",cmdpaste:"Paste",cmdquicklook:"Preview",cmdreload:"Reload",cmdrename:"Rename",cmdrm:"Delete",cmdsearch:"Find files",cmdup:"Go to parent directory",cmdupload:"Upload files",cmdview:"View",cmdresize:"Resize & Rotate",cmdsort:"Sort",cmdnetmount:"Mount network volume",btnClose:"Close",btnSave:"Save",btnRm:"Remove",btnApply:"Apply",btnCancel:"Cancel",btnNo:"No",btnYes:"Yes",btnMount:"Mount",ntfopen:"Open folder",ntffile:"Open file",ntfreload:"Reload folder content",ntfmkdir:"Creating directory",ntfmkfile:"Creating files",ntfrm:"Delete files",ntfcopy:"Copy files",ntfmove:"Move files",ntfprepare:"Prepare to copy files",ntfrename:"Rename files",ntfupload:"Uploading files",ntfdownload:"Downloading files",ntfsave:"Save files",ntfarchive:"Creating archive",ntfextract:"Extracting files from archive",ntfsearch:"Searching files",ntfresize:"Resizing images",ntfsmth:"Doing something",ntfloadimg:"Loading image",ntfnetmount:"Mounting network volume",ntfdim:"Acquiring image dimension",dateUnknown:"unknown",Today:"Today",Yesterday:"Yesterday",Jan:"Jan",Feb:"Feb",Mar:"Mar",Apr:"Apr",May:"May",Jun:"Jun",Jul:"Jul",Aug:"Aug",Sep:"Sep",Oct:"Oct",Nov:"Nov",Dec:"Dec",sortname:"by name",sortkind:"by kind",sortsize:"by size",sortdate:"by date",sortFoldersFirst:"Folders first",confirmReq:"Confirmation required",confirmRm:"Are you sure you want to remove files?<br/>This cannot be undone!",confirmRepl:"Replace old file with new one?",apllyAll:"Apply to all",name:"Name",size:"Size",perms:"Permissions",modify:"Modified",kind:"Kind",read:"read",write:"write",noaccess:"no access",and:"and",unknown:"unknown",selectall:"Select all files",selectfiles:"Select file(s)",selectffile:"Select first file",selectlfile:"Select last file",viewlist:"List view",viewicons:"Icons view",places:"Places",calc:"Calculate",path:"Path",aliasfor:"Alias for",locked:"Locked",dim:"Dimensions",files:"Files",folders:"Folders",items:"Items",yes:"yes",no:"no",link:"Link",searcresult:"Search results",selected:"selected items",about:"About",shortcuts:"Shortcuts",help:"Help",webfm:"Web file manager",ver:"Version",protocolver:"protocol version",homepage:"Project home",docs:"Documentation",github:"Fork us on Github",twitter:"Follow us on twitter",facebook:"Join us on facebook",team:"Team",chiefdev:"chief developer",developer:"developer",contributor:"contributor",maintainer:"maintainer",translator:"translator",icons:"Icons",dontforget:"and don't forget to take your towel",shortcutsof:"Shortcuts disabled",dropFiles:"Drop files here",or:"or",selectForUpload:"Select files to upload",moveFiles:"Move files",copyFiles:"Copy files",rmFromPlaces:"Remove from places",aspectRatio:"Aspect ratio",scale:"Scale",width:"Width",height:"Height",resize:"Resize",crop:"Crop",rotate:"Rotate","rotate-cw":"Rotate 90 degrees CW","rotate-ccw":"Rotate 90 degrees CCW",degree:"°",netMountDialogTitle:"Mount network volume",protocol:"Protocol",host:"Host",port:"Port",user:"User",pass:"Password",kindUnknown:"Unknown",kindFolder:"Folder",kindAlias:"Alias",kindAliasBroken:"Broken alias",kindApp:"Application",kindPostscript:"Postscript document",kindMsOffice:"Microsoft Office document",kindMsWord:"Microsoft Word document",kindMsExcel:"Microsoft Excel document",kindMsPP:"Microsoft Powerpoint presentation",kindOO:"Open Office document",kindAppFlash:"Flash application",kindPDF:"Portable Document Format (PDF)",kindTorrent:"Bittorrent file",kind7z:"7z archive",kindTAR:"TAR archive",kindGZIP:"GZIP archive",kindBZIP:"BZIP archive",kindZIP:"ZIP archive",kindRAR:"RAR archive",kindJAR:"Java JAR file",kindTTF:"True Type font",kindOTF:"Open Type font",kindRPM:"RPM package",kindText:"Text document",kindTextPlain:"Plain text",kindPHP:"PHP source",kindCSS:"Cascading style sheet",kindHTML:"HTML document",kindJS:"Javascript source",kindRTF:"Rich Text Format",kindC:"C source",kindCHeader:"C header source",kindCPP:"C++ source",kindCPPHeader:"C++ header source",kindShell:"Unix shell script",kindPython:"Python source",kindJava:"Java source",kindRuby:"Ruby source",kindPerl:"Perl script",kindSQL:"SQL source",kindXML:"XML document",kindAWK:"AWK source",kindCSV:"Comma separated values",kindDOCBOOK:"Docbook XML document",kindImage:"Image",kindBMP:"BMP image",kindJPEG:"JPEG image",kindGIF:"GIF Image",kindPNG:"PNG Image",kindTIFF:"TIFF image",kindTGA:"TGA image",kindPSD:"Adobe Photoshop image",kindXBITMAP:"X bitmap image",kindPXM:"Pixelmator image",kindAudio:"Audio media",kindAudioMPEG:"MPEG audio",kindAudioMPEG4:"MPEG-4 audio",kindAudioMIDI:"MIDI audio",kindAudioOGG:"Ogg Vorbis audio",kindAudioWAV:"WAV audio",AudioPlaylist:"MP3 playlist",kindVideo:"Video media",kindVideoDV:"DV movie",kindVideoMPEG:"MPEG movie",kindVideoMPEG4:"MPEG-4 movie",kindVideoAVI:"AVI movie",kindVideoMOV:"Quick Time movie",kindVideoWM:"Windows Media movie",kindVideoFlash:"Flash movie",kindVideoMKV:"Matroska movie",kindVideoOGG:"Ogg movie"}}),e.fn.elfinderbutton=function(t){return this.each(function(){var n,i="class",r=t.fm,a=r.res(i,"disabled"),o=r.res(i,"active"),s=r.res(i,"hover"),l="elfinder-button-menu-item",d="elfinder-button-menu-item-selected",c=e(this).addClass("ui-state-default elfinder-button").attr("title",t.title).append('<span class="elfinder-button-icon elfinder-button-icon-'+t.name+'"/>').hover(function(e){!c.is("."+a)&&c["mouseleave"==e.type?"removeClass":"addClass"](s)}).click(function(e){c.is("."+a)||(n&&t.variants.length>1?(n.is(":hidden")&&t.fm.getUI().click(),e.stopPropagation(),n.slideToggle(100)):t.exec())}),p=function(){n.hide()};e.isArray(t.variants)&&(c.addClass("elfinder-menubutton"),n=e('<div class="ui-widget ui-widget-content elfinder-button-menu ui-corner-all"/>').hide().appendTo(c).zIndex(12+c.zIndex()).delegate("."+l,"hover",function(){e(this).toggleClass(s)}).delegate("."+l,"click",function(n){n.preventDefault(),n.stopPropagation(),c.removeClass(s),t.exec(t.fm.selected(),e(this).data("value"))}),t.fm.bind("disable select",p).getUI().click(p),t.change(function(){n.html(""),e.each(t.variants,function(i,r){n.append(e('<div class="'+l+'">'+r[1]+"</div>").data("value",r[0]).addClass(r[0]==t.value?d:""))})})),t.change(function(){t.disabled()?c.removeClass(o+" "+s).addClass(a):(c.removeClass(a),c[t.active()?"addClass":"removeClass"](o))}).change()})},e.fn.elfindercontextmenu=function(t){return this.each(function(){var n=e(this).addClass("ui-helper-reset ui-widget ui-state-default ui-corner-all elfinder-contextmenu elfinder-contextmenu-"+t.direction).hide().appendTo("body").delegate(".elfinder-contextmenu-item","mouseenter mouseleave",function(){e(this).toggleClass("ui-state-hover")}),i="ltr"==t.direction?"left":"right",r=e.extend({},t.options.contextmenu),a='<div class="elfinder-contextmenu-item"><span class="elfinder-button-icon {icon} elfinder-contextmenu-icon"/><span>{label}</span></div>',o=function(t,n,i){return e(a.replace("{icon}",n?"elfinder-button-icon-"+n:"").replace("{label}",t)).click(function(e){e.stopPropagation(),e.stopPropagation(),i()})},s=function(r,a){var o=e(window),s=n.outerWidth(),l=n.outerHeight(),d=o.width(),c=o.height(),p=o.scrollTop(),u=o.scrollLeft(),h={top:(c>a+l?a:a-l>0?a-l:a)+p,left:(d>r+s?r:r-s)+u,"z-index":100+t.getUI("workzone").zIndex()};n.css(h).show(),h={"z-index":h["z-index"]+10},h[i]=parseInt(n.width()),n.find(".elfinder-contextmenu-sub").css(h)},l=function(){n.hide().empty()},d=function(i,a){var s=!1;e.each(r[i]||[],function(i,r){var d,c,p;if("|"==r&&s)return n.append('<div class="elfinder-contextmenu-separator"/>'),void(s=!1);if(d=t.command(r),d&&-1!=d.getstate(a)){if(d.variants){if(!d.variants.length)return;c=o(d.title,d.name,function(){}),p=e('<div class="ui-corner-all elfinder-contextmenu-sub"/>').appendTo(c.append('<span class="elfinder-contextmenu-arrow"/>')),c.addClass("elfinder-contextmenu-group").hover(function(){p.toggle()}),e.each(d.variants,function(t,n){p.append(e('<div class="elfinder-contextmenu-item"><span>'+n[1]+"</span></div>").click(function(e){e.stopPropagation(),l(),d.exec(a,n[0])}))})}else c=o(d.title,d.name,function(){l(),d.exec(a)});n.append(c),s=!0}})},c=function(t){e.each(t,function(e,t){var i;t.label&&"function"==typeof t.callback&&(i=o(t.label,t.icon,function(){l(),t.callback()}),n.append(i))})};t.one("load",function(){t.bind("contextmenu",function(e){var t=e.data;l(),t.type&&t.targets?d(t.type,t.targets):t.raw&&c(t.raw),n.children().length&&s(t.x,t.y)}).one("destroy",function(){n.remove()}).bind("disable select",l).getUI().click(l)})})},e.fn.elfindercwd=function(t,n){return this.not(".elfinder-cwd").each(function(){var i="list"==t.viewType,r="select."+t.namespace,a="unselect."+t.namespace,o="disable."+t.namespace,s="enable."+t.namespace,l="class",d=t.res(l,"cwdfile"),c="."+d,p="ui-selected",u=t.res(l,"disabled"),h=t.res(l,"draggable"),f=t.res(l,"droppable"),m=t.res(l,"hover"),g=t.res(l,"adroppable"),v=d+"-tmp",b=t.options.loadTmbs>0?t.options.loadTmbs:5,y="",w=[],x={icon:'<div id="{hash}" class="'+d+' {permsclass} {dirclass} ui-corner-all" title="{tooltip}"><div class="elfinder-cwd-file-wrapper ui-corner-all"><div class="elfinder-cwd-icon {mime} ui-corner-all" unselectable="on" {style}/>{marker}</div><div class="elfinder-cwd-filename" title="{name}">{name}</div></div>',row:'<tr id="{hash}" class="'+d+' {permsclass} {dirclass}" title="{tooltip}"><td><div class="elfinder-cwd-file-wrapper"><span class="elfinder-cwd-icon {mime}"/>{marker}<span class="elfinder-cwd-filename">{name}</span></div></td><td>{perms}</td><td>{date}</td><td>{size}</td><td>{kind}</td></tr>'},k=t.res("tpl","perms"),C=t.res("tpl","symlink"),I={permsclass:function(e){return t.perms2class(e)},perms:function(e){return t.formatPermissions(e)},dirclass:function(e){return"directory"==e.mime?"directory":""},mime:function(e){return t.mime2class(e.mime)},size:function(e){return t.formatSize(e.size)},date:function(e){return t.formatDate(e)},kind:function(e){return t.mime2kind(e)},marker:function(e){return(e.alias||"symlink-broken"==e.mime?C:"")+(e.read&&e.write?"":k)},tooltip:function(e){var n=t.formatDate(e)+(e.size>0?" ("+t.formatSize(e.size)+")":"");return e.tooltip?t.escape(e.tooltip).replace(/"/g,"&quot;").replace(/\r/g,"&#13;")+"&#13;"+n:n}},F=function(e){return e.name=t.escape(e.name),x[i?"row":"icon"].replace(/\{([a-z]+)\}/g,function(t,n){return I[n]?I[n](e):e[n]?e[n]:""})},T=!1,z=function(t,n){function o(e,t){return e[t+"All"]("[id]:not(."+u+"):not(.elfinder-cwd-parent):first")}var s,l,d,c,h,f=e.ui.keyCode,m=t==f.LEFT||t==f.UP,g=K.find("[id]."+p);if(g.length)if(s=g.filter(m?":first":":last"),d=o(s,m?"prev":"next"),d.length)if(i||t==f.LEFT||t==f.RIGHT)l=d;else if(c=s.position().top,h=s.position().left,l=s,m){do l=l.prev("[id]");while(l.length&&!(l.position().top<c&&l.position().left<=h));l.is("."+u)&&(l=o(l,"next"))}else{do l=l.next("[id]");while(l.length&&!(l.position().top>c&&l.position().left>=h));l.is("."+u)&&(l=o(l,"prev")),l.length||(d=K.find("[id]:not(."+u+"):last"),d.position().top>c&&(l=d))}else l=s;else l=K.find("[id]:not(."+u+"):not(.elfinder-cwd-parent):"+(m?"last":"first"));l&&l.length&&!l.is(".elfinder-cwd-parent")&&(n?l=s.add(s[m?"prevUntil":"nextUntil"]("#"+l.attr("id"))).add(l):g.trigger(a),l.trigger(r),S(l.filter(m?":first":":last")),M())},P=[],A=function(e){K.find("#"+e).trigger(r)},O=function(){var n=t.cwd().hash;K.find("[id]:not(."+p+"):not(.elfinder-cwd-parent)").trigger(r),P=e.map(t.files(),function(e){return e.phash==n?e.hash:null}),M()},D=function(){P=[],K.find("[id]."+p).trigger(a),M()},M=function(){t.trigger("select",{selected:P})},S=function(e){var t=e.position().top,n=e.outerHeight(!0),i=G.scrollTop(),r=G.innerHeight();t+n>i+r?G.scrollTop(parseInt(t+n-r)):i>t&&G.scrollTop(t)},E=[],j=function(e){for(var t=E.length;t--;)if(E[t].hash==e)return t;return-1},U="scroll."+t.namespace,R=function(){var n,a=[],o=!1,s=[],l={},d=K.find("[id]:last"),c=!d.length,u=i?K.children("table").children("tbody"):K;if(!E.length)return G.unbind(U);for(;(!d.length||d.position().top<=G.height()+G.scrollTop()+t.options.showThreshold)&&(n=E.splice(0,t.options.showFiles)).length;)a=e.map(n,function(e){return e.hash&&e.name?("directory"==e.mime&&(o=!0),e.tmb&&(1===e.tmb?s.push(e.hash):l[e.hash]=e.tmb),F(e)):null}),u.append(a.join("")),d=K.find("[id]:last"),c&&K.scrollTop(0);L(l),s.length&&N(s),o&&H(),P.length&&u.find("[id]:not(."+p+"):not(.elfinder-cwd-parent)").each(function(){var t=this.id;-1!==e.inArray(t,P)&&e(this).trigger(r)})},q=e.extend({},t.droppable,{over:function(n,i){var r=t.cwd().hash;e.each(i.helper.data("files"),function(e,n){return t.file(n).phash==r?(K.removeClass(g),!1):void 0})}}),H=function(){setTimeout(function(){K.find(".directory:not(."+f+",.elfinder-na,.elfinder-ro)").droppable(t.droppable)},20)},L=function(n){var i,r=t.option("tmbUrl"),a=!0;return e.each(n,function(t,n){var o=K.find("#"+t);o.length?!function(t,n){e("<img/>").load(function(){t.find(".elfinder-cwd-icon").css("background","url('"+n+"') center center no-repeat")}).attr("src",n)}(o,r+n):(a=!1,-1!=(i=j(t))&&(E[i].tmb=n))}),a},N=function(e){var n=[];return t.oldAPI?void t.request({data:{cmd:"tmb",current:t.cwd().hash},preventFail:!0}).done(function(e){L(e.images||[])&&e.tmb&&N()}):(n=n=e.splice(0,b),void(n.length&&t.request({data:{cmd:"tmb",targets:n},preventFail:!0}).done(function(t){L(t.images||[])&&N(e)})))},_=function(e){for(var n,r,a,o,s=i?K.find("tbody"):K,l=e.length,d=[],c={},p=!1,u=function(e){for(var n,i=K.find("[id]:first");i.length;){if(n=t.file(i.attr("id")),!i.is(".elfinder-cwd-parent")&&n&&t.compare(e,n)<0)return i;i=i.next("[id]")}},h=function(e){var n,i=E.length;for(n=0;i>n;n++)if(t.compare(e,E[n])<0)return n;return i||-1};l--;)n=e[l],r=n.hash,K.find("#"+r).length||((a=u(n))&&a.length?a.before(F(n)):(o=h(n))>=0?E.splice(o,0,n):s.append(F(n)),K.find("#"+r).length&&("directory"==n.mime?p=!0:n.tmb&&(1===n.tmb?d.push(r):c[r]=n.tmb)));L(c),d.length&&N(d),p&&H()},V=function(e){for(var n,i,r,a=e.length;a--;)if(n=e[a],(i=K.find("#"+n)).length)try{i.detach()}catch(o){t.debug("error",o)}else-1!=(r=j(n))&&E.splice(r,1)},W={name:t.i18n("name"),perm:t.i18n("perms"),mod:t.i18n("modify"),size:t.i18n("size"),kind:t.i18n("kind")},B=function(r,a){var o=t.cwd().hash;D();try{K.children("table,"+c).remove()}catch(s){K.html("")}if(K.removeClass("elfinder-cwd-view-icons elfinder-cwd-view-list").addClass("elfinder-cwd-view-"+(i?"list":"icons")),G[i?"addClass":"removeClass"]("elfinder-cwd-wrapper-list"),i&&K.html('<table><thead><tr class="ui-state-default"><td >'+W.name+"</td><td>"+W.perm+"</td><td>"+W.mod+"</td><td>"+W.size+"</td><td>"+W.kind+"</td></tr></thead><tbody/></table>"),E=e.map(r,function(e){return a||e.phash==o?e:null
}),E=t.sortFiles(E),G.bind(U,R).trigger(U),o=t.cwd().phash,n.oldSchool&&o&&!y){var l=e.extend(!0,{},t.file(o),{name:"..",mime:"directory"});l=e(F(l)).addClass("elfinder-cwd-parent").bind("mousedown click mouseup touchstart touchmove touchend dblclick mouseenter",function(e){e.preventDefault(),e.stopPropagation()}).dblclick(function(){t.exec("open",this.id)}),(i?K.find("tbody"):K).prepend(l)}},K=e(this).addClass("ui-helper-clearfix elfinder-cwd").attr("unselectable","on").delegate(c,"click."+t.namespace,function(n){var i,o=this.id?e(this):e(this).parents("[id]:first"),s=o.prevAll("."+p+":first"),l=o.nextAll("."+p+":first"),d=s.length,c=l.length;n.stopImmediatePropagation(),n.shiftKey&&(d||c)?(i=d?o.prevUntil("#"+s.attr("id")):o.nextUntil("#"+l.attr("id")),i.add(o).trigger(r)):n.ctrlKey||n.metaKey?o.trigger(o.is("."+p)?a:r):e(this).data("touching")&&o.is("."+p)?(e(this).data("touching",null),t.dblclick({file:this.id}),D()):(D(),o.trigger(r)),M()}).delegate(c,"dblclick."+t.namespace,function(){t.dblclick({file:this.id})}).delegate(c,"touchstart."+t.namespace,function(){e(this).data("touching",!0);var t=this.id?e(this):e(this).parents("[id]:first"),n=t.prevAll("."+p+":first").length+t.nextAll("."+p+":first").length;e(this).data("longtap",setTimeout(function(){t.trigger(t.is("."+p)?a:r),M(),0==n&&t.is("."+p)&&(t.trigger("click"),M())},500))}).delegate(c,"touchmove."+t.namespace+" touchend."+t.namespace,function(){clearTimeout(e(this).data("longtap"))}).delegate(c,"mouseenter."+t.namespace,function(){var n=e(this),r=i?n:n.children();n.is("."+v)||r.is("."+h+",."+u)||r.draggable(t.draggable)}).delegate(c,r,function(){var t=e(this),n=t.attr("id");T||t.is("."+u)||(t.addClass(p).children().addClass(m),-1===e.inArray(n,P)&&P.push(n))}).delegate(c,a,function(){var t,n=e(this),i=n.attr("id");T||(e(this).removeClass(p).children().removeClass(m),t=e.inArray(i,P),-1!==t&&P.splice(t,1))}).delegate(c,o,function(){var t=e(this).removeClass(p).addClass(u),n=(i?t:t.children()).removeClass(m);t.is("."+f)&&t.droppable("disable"),n.is("."+h)&&n.draggable("disable"),!i&&n.removeClass(u)}).delegate(c,s,function(){var t=e(this).removeClass(u),n=i?t:t.children();t.is("."+f)&&t.droppable("enable"),n.is("."+h)&&n.draggable("enable")}).delegate(c,"scrolltoview",function(){S(e(this))}).delegate(c,"mouseenter."+t.namespace+" mouseleave."+t.namespace,function(n){t.trigger("hover",{hash:e(this).attr("id"),type:n.type}),e(this).toggleClass("ui-state-hover")}).bind("contextmenu."+t.namespace,function(n){var i=e(n.target).closest("."+d);i.length&&(n.stopPropagation(),n.preventDefault(),i.is("."+u)||(i.is("."+p)||(D(),i.trigger(r),M()),t.trigger("contextmenu",{type:"files",targets:t.selected(),x:n.clientX,y:n.clientY})))}).bind("click."+t.namespace,function(e){!e.shiftKey&&!e.ctrlKey&&!e.metaKey&&D()}).selectable({filter:c,stop:M,delay:250,selected:function(t,n){e(n.selected).trigger(r)},unselected:function(t,n){e(n.unselected).trigger(a)}}).droppable(q).bind("create."+t.namespace,function(t,n){var r=i?K.find("tbody"):K,a=r.find(".elfinder-cwd-parent"),n=e(F(n)).addClass(v);D(),a.length?a.after(n):r.prepend(n),K.scrollTop(0)}).bind("unselectall",D).bind("selectfile",function(e,t){K.find("#"+t).trigger(r),M()}),G=e('<div class="elfinder-cwd-wrapper"/>').bind("contextmenu",function(e){e.preventDefault(),t.trigger("contextmenu",{type:"cwd",targets:[t.cwd().hash],x:e.clientX,y:e.clientY})}),$=function(){var t=0;G.siblings(".elfinder-panel:visible").each(function(){t+=e(this).outerHeight(!0)}),G.height(X.height()-t)},J=e(this).parent().resize($),X=J.children(".elfinder-workzone").append(G.append(this));t.dragUpload&&(G[0].addEventListener("dragenter",function(e){e.preventDefault(),e.stopPropagation(),G.addClass(g)},!1),G[0].addEventListener("dragleave",function(e){e.preventDefault(),e.stopPropagation(),e.target==K[0]&&G.removeClass(g)},!1),G[0].addEventListener("dragover",function(e){e.preventDefault(),e.stopPropagation()},!1),G[0].addEventListener("drop",function(e){e.preventDefault(),G.removeClass(g),e.dataTransfer&&e.dataTransfer.files&&e.dataTransfer.files.length&&t.exec("upload",{files:e.dataTransfer.files})},!1)),t.bind("open",function(e){B(e.data.files)}).bind("search",function(e){w=e.data.files,B(w,!0)}).bind("searchend",function(){w=[],y&&(y="",B(t.files()))}).bind("searchstart",function(e){y=e.data.query}).bind("sortchange",function(){B(y?w:t.files(),!!y)}).bind("viewchange",function(){var n=t.selected(),r="list"==t.storage("view");r!=i&&(i=r,B(t.files()),e.each(n,function(e,t){A(t)}),M()),$()}).add(function(n){var i=t.cwd().hash,r=y?e.map(n.data.added||[],function(e){return-1===e.name.indexOf(y)?null:e}):e.map(n.data.added||[],function(e){return e.phash==i?e:null});_(r)}).change(function(n){var i=t.cwd().hash,r=t.selected();y?e.each(n.data.changed||[],function(t,n){V([n.hash]),-1!==n.name.indexOf(y)&&(_([n]),-1!==e.inArray(n.hash,r)&&A(n.hash))}):e.each(e.map(n.data.changed||[],function(e){return e.phash==i?e:null}),function(t,n){V([n.hash]),_([n]),-1!==e.inArray(n.hash,r)&&A(n.hash)}),M()}).remove(function(e){V(e.data.removed||[]),M()}).bind("open add search searchend",function(){K.css("height","auto"),K.outerHeight(!0)<G.height()&&K.height(G.height()-(K.outerHeight(!0)-K.height())-2)}).dragstart(function(t){var n=e(t.data.target),i=t.data.originalEvent;n.is(c)&&(n.is("."+p)||(!(i.ctrlKey||i.metaKey||i.shiftKey)&&D(),n.trigger(r),M()),K.droppable("disable")),K.selectable("disable").removeClass(u),T=!0}).dragstop(function(){K.selectable("enable").droppable("enable"),T=!1}).bind("lockfiles unlockfiles",function(e){for(var t="lockfiles"==e.type?o:s,n=e.data.files||[],i=n.length;i--;)K.find("#"+n[i]).trigger(t);M()}).bind("mkdir mkfile duplicate upload rename archive extract",function(n){var i=t.cwd().hash;D(),e.each(n.data.added||[],function(e,t){t&&t.phash==i&&A(t.hash)}),M()}).shortcut({pattern:"ctrl+a",description:"selectall",callback:O}).shortcut({pattern:"left right up down shift+left shift+right shift+up shift+down",description:"selectfiles",type:"keydown",callback:function(e){z(e.keyCode,e.shiftKey)}}).shortcut({pattern:"home",description:"selectffile",callback:function(){D(),S(K.find("[id]:first").trigger(r)),M()}}).shortcut({pattern:"end",description:"selectlfile",callback:function(){D(),S(K.find("[id]:last").trigger(r)),M()}})}),this},e.fn.elfinderdialog=function(t){var n;return"string"==typeof t&&(n=this.closest(".ui-dialog")).length&&("open"==t?"none"==n.css("display")&&n.fadeIn(120,function(){n.trigger("open")}):"close"==t?"none"!=n.css("display")&&n.hide().trigger("close"):"destroy"==t?n.hide().remove():"toTop"==t&&n.trigger("totop")),t=e.extend({},e.fn.elfinderdialog.defaults,t),this.filter(":not(.ui-dialog-content)").each(function(){var n,i=e(this).addClass("ui-dialog-content ui-widget-content"),r=i.parent(),a="elfinder-dialog-active",o="elfinder-dialog",s="elfinder-dialog-notify",l="ui-state-hover",d=parseInt(1e6*Math.random()),c=r.children(".elfinder-overlay"),p=e('<div class="ui-dialog-buttonset"/>'),u=e('<div class=" ui-helper-clearfix ui-dialog-buttonpane ui-widget-content"/>').append(p),h=e('<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable std42-dialog  '+o+" "+t.cssClass+'"/>').hide().append(i).appendTo(r).draggable({handle:".ui-dialog-titlebar",containment:"document"}).css({width:t.width,height:t.height}).mousedown(function(t){t.stopPropagation(),e(document).mousedown(),h.is("."+a)||(r.find("."+o+":visible").removeClass(a),h.addClass(a).zIndex(f()+1))}).bind("open",function(){h.trigger("totop"),"function"==typeof t.open&&e.proxy(t.open,i[0])(),h.is("."+s)||r.find("."+o+":visible").not("."+s).each(function(){var t=e(this),n=parseInt(t.css("top")),i=parseInt(t.css("left")),r=parseInt(h.css("top")),a=parseInt(h.css("left"));t[0]==h[0]||n!=r&&i!=a||h.css({top:n+10+"px",left:i+10+"px"})})}).bind("close",function(){var n=r.find(".elfinder-dialog:visible"),a=f();e(this).data("modal")&&c.elfinderoverlay("hide"),n.length?n.each(function(){var t=e(this);return t.zIndex()>=a?(t.trigger("totop"),!1):void 0}):setTimeout(function(){r.mousedown().click()},10),"function"==typeof t.close?e.proxy(t.close,i[0])():t.destroyOnClose&&h.hide().remove()}).bind("totop",function(){e(this).mousedown().find(".ui-button:first").focus().end().find(":text:first").focus(),e(this).data("modal")&&c.elfinderoverlay("show"),c.zIndex(e(this).zIndex())}).data({modal:t.modal}),f=function(){var t=r.zIndex()+10;return r.find("."+o+":visible").each(function(){var n;this!=h[0]&&(n=e(this).zIndex(),n>t&&(t=n))}),t};t.position||(n=parseInt((r.height()-h.outerHeight())/2-42),t.position={top:(n>0?n:0)+"px",left:parseInt((r.width()-h.outerWidth())/2)+"px"}),h.css(t.position),t.closeOnEscape&&e(document).bind("keyup."+d,function(t){t.keyCode==e.ui.keyCode.ESCAPE&&h.is("."+a)&&(i.elfinderdialog("close"),e(document).unbind("keyup."+d))}),h.prepend(e('<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">'+t.title+"</div>").prepend(e('<a href="#" class="ui-dialog-titlebar-close ui-corner-all"><span class="ui-icon ui-icon-closethick"/></a>').mousedown(function(e){e.preventDefault(),i.elfinderdialog("close")}))),e.each(t.buttons,function(t,n){var r=e('<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">'+t+"</span></button>").click(e.proxy(n,i[0])).hover(function(t){e(this)["mouseenter"==t.type?"focus":"blur"]()}).focus(function(){e(this).addClass(l)}).blur(function(){e(this).removeClass(l)}).keydown(function(t){var n;t.keyCode==e.ui.keyCode.ENTER?e(this).click():t.keyCode==e.ui.keyCode.TAB&&(n=e(this).next(".ui-button"),n.length?n.focus():e(this).parent().children(".ui-button:first").focus())});p.append(r)}),p.children().length&&h.append(u),t.resizable&&e.fn.resizable&&h.resizable({minWidth:t.minWidth,minHeight:t.minHeight,alsoResize:this}),"function"==typeof t.create&&e.proxy(t.create,this)(),t.autoOpen&&i.elfinderdialog("open")}),this},e.fn.elfinderdialog.defaults={cssClass:"",title:"",modal:!1,resizable:!0,autoOpen:!0,closeOnEscape:!0,destroyOnClose:!1,buttons:{},position:null,width:320,height:"auto",minWidth:200,minHeight:110},e.fn.elfindernavbar=function(t,n){return this.not(".elfinder-navbar").each(function(){var i,r=e(this).addClass("ui-state-default elfinder-navbar"),a=r.parent().resize(function(){r.height(o.height()-s)}),o=a.children(".elfinder-workzone").append(r),s=r.outerHeight()-r.height(),l="ltr"==t.direction;e.fn.resizable&&(i=r.resizable({handles:l?"e":"w",minWidth:n.minWidth||150,maxWidth:n.maxWidth||500}).bind("resize scroll",function(){var e=t.UA.Opera&&r.scrollLeft()?20:2;i.css({top:parseInt(r.scrollTop())+"px",left:l?"auto":parseInt(r.scrollLeft()+e),right:l?-1*parseInt(r.scrollLeft()-e):"auto"})}).find(".ui-resizable-handle").zIndex(r.zIndex()+10),l||r.resize(function(){r.css("left",null).css("right",0)}),t.one("open",function(){setTimeout(function(){r.trigger("resize")},150)}))}),this},e.fn.elfinderoverlay=function(t){if(this.filter(":not(.elfinder-overlay)").each(function(){t=e.extend({},t),e(this).addClass("ui-widget-overlay elfinder-overlay").hide().mousedown(function(e){e.preventDefault(),e.stopPropagation()}).data({cnt:0,show:"function"==typeof t.show?t.show:function(){},hide:"function"==typeof t.hide?t.hide:function(){}})}),"show"==t){var n=this.eq(0),i=n.data("cnt")+1,r=n.data("show");n.data("cnt",i),n.is(":hidden")&&(n.zIndex(n.parent().zIndex()+1),n.show(),r())}if("hide"==t){var n=this.eq(0),i=n.data("cnt")-1,a=n.data("hide");n.data("cnt",i),0==i&&n.is(":visible")&&(n.hide(),a())}return this},e.fn.elfinderpanel=function(t){return this.each(function(){var n=e(this).addClass("elfinder-panel ui-state-default ui-corner-all"),i="margin-"+("ltr"==t.direction?"left":"right");t.one("load",function(){var e=t.getUI("navbar");n.css(i,parseInt(e.outerWidth(!0))),e.bind("resize",function(){n.is(":visible")&&n.css(i,parseInt(e.outerWidth(!0)))})})})},e.fn.elfinderpath=function(t){return this.each(function(){var n=e(this).addClass("elfinder-path").html("&nbsp;").delegate("a","click",function(n){var i=e(this).attr("href").substr(1);n.preventDefault(),i!=t.cwd().hash&&t.exec("open",i)}).prependTo(t.getUI("statusbar").show());t.bind("open searchend",function(){var i=[];e.each(t.parents(t.cwd().hash),function(e,n){i.push('<a href="#'+n+'">'+t.escape(t.file(n).name)+"</a>")}),n.html(i.join(t.option("separator")))}).bind("search",function(){n.html(t.i18n("searcresult"))})})},e.fn.elfinderplaces=function(t,n){return this.each(function(){var i=[],r="class",a=t.res(r,"navdir"),o=t.res(r,"navcollapse"),s=t.res(r,"navexpand"),l=(t.res(r,"hover"),t.res(r,"treeroot")),d=t.res("tpl","navdir"),c=t.res("tpl","perms"),p=e(t.res("tpl","navspinner")),u=function(e){return e.substr(6)},h=function(e){return"place-"+e},f=function(){t.storage("places",i.join(","))},m=function(n){return e(d.replace(/\{id\}/,h(n.hash)).replace(/\{name\}/,t.escape(n.name)).replace(/\{cssclass\}/,t.perms2class(n)).replace(/\{permissions\}/,n.read&&n.write?"":c).replace(/\{symlink\}/,""))},g=function(n){var r=m(n);w.children().length&&e.each(w.children(),function(){var t=e(this);return n.name.localeCompare(t.children("."+a).text())<0?!r.insertBefore(t):void 0}),i.push(n.hash),!r.parent().length&&w.append(r),y.addClass(o),r.draggable({appendTo:"body",revert:!1,helper:function(){var n=e(this);return n.children().removeClass("ui-state-hover"),e('<div class="elfinder-place-drag elfinder-'+t.direction+'"/>').append(n.clone()).data("hash",u(n.children(":first").attr("id")))},start:function(){e(this).hide()},stop:function(t,n){var i=x.offset().top,r=x.offset().left,a=x.width(),o=x.height(),s=t.clientX,l=t.clientY;s>r&&r+a>s&&l>i&&l+o>l?e(this).show():(v(n.helper.data("hash")),f())}})},v=function(t){var n=e.inArray(t,i);-1!==n&&(i.splice(n,1),w.find("#"+h(t)).parent().remove(),!w.children().length&&y.removeClass(o+" "+s))},b=m({hash:"root-"+t.namespace,name:t.i18n(n.name,"places"),read:!0,write:!0}),y=b.children("."+a).addClass(l).click(function(){y.is("."+o)&&(x.toggleClass(s),w.slideToggle(),t.storage("placesState",x.is("."+s)?1:0))}),w=b.children("."+t.res(r,"navsubtree")),x=e(this).addClass(t.res(r,"tree")+" elfinder-places ui-corner-all").hide().append(b).appendTo(t.getUI("navbar")).delegate("."+a,"hover",function(){e(this).toggleClass("ui-state-hover")}).delegate("."+a,"click",function(){t.exec("open",e(this).attr("id").substr(6))}).delegate("."+a+":not(."+l+")","contextmenu",function(n){var i=e(this).attr("id").substr(6);n.preventDefault(),t.trigger("contextmenu",{raw:[{label:t.i18n("rmFromPlaces"),icon:"rm",callback:function(){v(i),f()}}],x:n.clientX,y:n.clientY})}).droppable({tolerance:"pointer",accept:".elfinder-cwd-file-wrapper,.elfinder-tree-dir,.elfinder-cwd-file",hoverClass:t.res("class","adroppable"),drop:function(n,r){var a=!0;e.each(r.helper.data("files"),function(n,r){var o=t.file(r);o&&"directory"==o.mime&&-1===e.inArray(o.hash,i)?g(o):a=!1}),f(),a&&r.helper.hide()}});t.one("load",function(){t.oldAPI||(x.show().parent().show(),i=e.map(t.storage("places").split(","),function(e){return e||null}),i.length&&(y.prepend(p),t.request({data:{cmd:"info",targets:i},preventDefault:!0}).done(function(n){i=[],e.each(n.files,function(e,t){"directory"==t.mime&&g(t)}),f(),t.storage("placesState")>0&&y.click()}).always(function(){p.remove()})),t.remove(function(t){e.each(t.data.removed,function(e,t){v(t)}),f()}).change(function(t){e.each(t.data.changed,function(t,n){-1!==e.inArray(n.hash,i)&&(v(n.hash),"directory"==n.mime&&g(n))}),f()}).bind("sync",function(){i.length&&(y.prepend(p),t.request({data:{cmd:"info",targets:i},preventDefault:!0}).done(function(t){e.each(t.files||[],function(t,n){-1===e.inArray(n.hash,i)&&v(n.hash)}),f()}).always(function(){p.remove()}))}))})})},e.fn.elfindersearchbutton=function(t){return this.each(function(){var n=!1,i=e(this).hide().addClass("ui-widget-content elfinder-button "+t.fm.res("class","searchbtn")),r=function(){var i=e.trim(o.val());i?t.exec(i).done(function(){n=!0,o.focus()}):t.fm.trigger("searchend")},a=function(){o.val(""),n&&(n=!1,t.fm.trigger("searchend"))},o=e('<input type="text" size="42"/>').appendTo(i).keypress(function(e){e.stopPropagation()}).keydown(function(e){e.stopPropagation(),13==e.keyCode&&r(),27==e.keyCode&&(e.preventDefault(),a())});e('<span class="ui-icon ui-icon-search" title="'+t.title+'"/>').appendTo(i).click(r),e('<span class="ui-icon ui-icon-close"/>').appendTo(i).click(a),setTimeout(function(){if(i.parent().detach(),t.fm.getUI("toolbar").prepend(i.show()),t.fm.UA.ltIE7){var e=i.children("ltr"==t.fm.direction?".ui-icon-close":".ui-icon-search");e.css({right:"",left:parseInt(i.width())-e.outerWidth(!0)})}},200),t.fm.error(function(){o.unbind("keydown")}).select(function(){o.blur()}).bind("searchend",function(){o.val("")}).viewchange(a).shortcut({pattern:"ctrl+f f3",description:t.title,callback:function(){o.select().focus()}})})},e.fn.elfindersortbutton=function(t){return this.each(function(){var n=t.fm,i=t.name,r="class",a=n.res(r,"disabled"),o=n.res(r,"hover"),s="elfinder-button-menu-item",l=s+"-selected",d=l+"-asc",c=l+"-desc",p=e(this).addClass("ui-state-default elfinder-button elfinder-menubutton elfiner-button-"+i).attr("title",t.title).append('<span class="elfinder-button-icon elfinder-button-icon-'+i+'"/>').hover(function(){!p.is("."+a)&&p.toggleClass(o)}).click(function(e){p.is("."+a)||(e.stopPropagation(),u.is(":hidden")&&t.fm.getUI().click(),u.slideToggle(100))}),u=e('<div class="ui-widget ui-widget-content elfinder-button-menu ui-corner-all"/>').hide().appendTo(p).zIndex(12+p.zIndex()).delegate("."+s,"hover",function(){e(this).toggleClass(o)}).delegate("."+s,"click",function(e){e.preventDefault(),e.stopPropagation(),f()}),h=function(){u.children(":not(:last)").removeClass(l+" "+d+" "+c).filter('[rel="'+n.sortType+'"]').addClass(l+" "+("asc"==n.sortOrder?d:c)),u.children(":last").toggleClass(l,n.sortStickFolders)},f=function(){u.hide()};e.each(n.sortRules,function(t){u.append(e('<div class="'+s+'" rel="'+t+'"><span class="ui-icon ui-icon-arrowthick-1-n"/><span class="ui-icon ui-icon-arrowthick-1-s"/>'+n.i18n("sort"+t)+"</div>").data("type",t))}),u.children().click(function(){var i=e(this).attr("rel");t.exec([],{type:i,order:i==n.sortType?"asc"==n.sortOrder?"desc":"asc":n.sortOrder,stick:n.sortStickFolders})}),e('<div class="'+s+" "+s+'-separated"><span class="ui-icon ui-icon-check"/>'+n.i18n("sortFoldersFirst")+"</div>").appendTo(u).click(function(){t.exec([],{type:n.sortType,order:n.sortOrder,stick:!n.sortStickFolders})}),n.bind("disable select",f).getUI().click(f),n.bind("sortchange",h),u.children().length>1?t.change(function(){p.toggleClass(a,t.disabled()),h()}).change():p.addClass(a)})},e.fn.elfinderstat=function(t){return this.each(function(){var n=e(this).addClass("elfinder-stat-size"),i=e('<div class="elfinder-stat-selected"/>'),r=t.i18n("size").toLowerCase(),a=t.i18n("items").toLowerCase(),o=t.i18n("selected"),s=function(i,o){var s=0,l=0;e.each(i,function(e,t){o&&t.phash!=o||(s++,l+=parseInt(t.size)||0)}),n.html(a+": "+s+", "+r+": "+t.formatSize(l))};t.getUI("statusbar").prepend(n).append(i).show(),t.bind("open reload add remove change searchend",function(){s(t.files(),t.cwd().hash)}).search(function(e){s(e.data.files)}).select(function(){var n=0,a=0,s=t.selectedFiles();return 1==s.length?(n=s[0].size,void i.html(t.escape(s[0].name)+(n>0?", "+t.formatSize(n):""))):(e.each(s,function(e,t){a++,n+=parseInt(t.size)||0}),void i.html(a?o+": "+a+", "+r+": "+t.formatSize(n):"&nbsp;"))})})},e.fn.elfindertoolbar=function(t,n){return this.not(".elfinder-toolbar").each(function(){var i,r,a,o,s=t._commands,l=e(this).addClass("ui-helper-clearfix ui-widget-header ui-corner-top elfinder-toolbar"),d=n||[],c=d.length;for(l.prev().length&&l.parent().prepend(this);c--;)if(d[c]){for(a=e('<div class="ui-widget-content ui-corner-all elfinder-buttonset"/>'),i=d[c].length;i--;)(r=s[d[c][i]])&&(o="elfinder"+r.options.ui,e.fn[o]&&a.prepend(e("<div/>")[o](r)));a.children().length&&l.prepend(a),a.children(":gt(0)").before('<span class="ui-widget-content elfinder-toolbar-button-separator"/>')}l.children().length&&l.show()}),this},e.fn.elfindertree=function(t,n){var i=t.res("class","tree");return this.not("."+i).each(function(){var r="class",a=t.res(r,"treeroot"),o=n.openRootOnLoad,s=t.res(r,"navsubtree"),l=t.res(r,"treedir"),d=t.res(r,"navcollapse"),c=t.res(r,"navexpand"),p="elfinder-subtree-loaded",u=t.res(r,"navarrow"),h=t.res(r,"active"),f=t.res(r,"adroppable"),m=t.res(r,"hover"),g=t.res(r,"disabled"),v=t.res(r,"draggable"),b=t.res(r,"droppable"),y=function(e){var t=U.offset().left;return e>=t&&e<=t+U.width()},w=t.droppable.drop,x=e.extend(!0,{},t.droppable,{over:function(t){var n=e(this),i=m+" "+f;y(t.clientX)?(n.addClass(i),n.is("."+d+":not(."+c+")")&&setTimeout(function(){n.is("."+f)&&n.children("."+u).click()},500)):n.removeClass(i)},out:function(){e(this).removeClass(m+" "+f)},drop:function(e,t){y(e.clientX)&&w.call(this,e,t)}}),k=e(t.res("tpl","navspinner")),C=t.res("tpl","navdir"),I=t.res("tpl","perms"),F=t.res("tpl","symlink"),T={id:function(e){return t.navHash2Id(e.hash)},cssclass:function(e){return(e.phash?"":a)+" "+l+" "+t.perms2class(e)+" "+(e.dirs&&!e.link?d:"")},permissions:function(e){return e.read&&e.write?"":I},symlink:function(e){return e.alias?F:""}},z=function(e){return e.name=t.escape(e.i18||e.name),C.replace(/(?:\{([a-z]+)\})/gi,function(t,n){return e[n]||(T[n]?T[n](e):"")})},P=function(t){return e.map(t||[],function(e){return"directory"==e.mime?e:null})},A=function(e){return e?j.find("#"+t.navHash2Id(e)).next("."+s):j},O=function(n,i){for(var r,a=n.children(":first");a.length;){if(r=t.file(t.navId2Hash(a.children("[id]").attr("id"))),(r=t.file(t.navId2Hash(a.children("[id]").attr("id"))))&&i.name.toLowerCase().localeCompare(r.name.toLowerCase())<0)return a;a=a.next()}return e("")},D=function(e){for(var n,i,r,a,o=e.length,s=[],l=e.length,d=!0;l--;)n=e[l],j.find("#"+t.navHash2Id(n.hash)).length||((r=A(n.phash)).length?(i=z(n),n.phash&&(a=O(r,n)).length?a.before(i):(r[d||n.phash?"append":"prepend"](i),d=!1)):s.push(n));return s.length&&s.length<o?D(s):void setTimeout(function(){S()},10)},M=function(){var e,i,r=t.cwd().hash,d=j.find("#"+t.navHash2Id(r));if(o&&(e=j.find("#"+t.navHash2Id(t.root())),e.is("."+p)&&e.addClass(c).next("."+s).show(),o=!1),d.is("."+h)||(j.find("."+l+"."+h).removeClass(h),d.addClass(h)),n.syncTree){if(d.length)return d.parentsUntil("."+a).filter("."+s).show().prev("."+l).addClass(c);if(t.newAPI){if(i=t.file(r),i&&i.phash&&j.find("#"+t.navHash2Id(i.phash)).length)return D([i]),M();t.request({data:{cmd:"parents",target:r},preventFail:!0}).done(function(e){var n=P(e.tree);D(n),E(n,p),r==t.cwd().hash&&M(!0)})}}},S=function(){j.find("."+l+":not(."+b+",.elfinder-ro,.elfinder-na)").droppable(x)},E=function(n,i){var r=i==p?"."+d+":not(."+p+")":":not(."+d+")";e.each(n,function(n,a){j.find("#"+t.navHash2Id(a.phash)+r).filter(function(){return e(this).next("."+s).children().length>0}).addClass(i)})},j=e(this).addClass(i).delegate("."+l,"hover",function(n){var i=e(this),r="mouseenter"==n.type;i.is("."+f+" ,."+g)||(r&&!i.is("."+a+",."+v+",.elfinder-na,.elfinder-wo")&&i.draggable(t.draggable),i.toggleClass(m,r))}).delegate("."+l,"dropover dropout drop",function(t){e(this)["dropover"==t.type?"addClass":"removeClass"](f+" "+m)}).delegate("."+l,"click",function(){var n=e(this),i=t.navId2Hash(n.attr("id")),r=t.file(i);t.trigger("searchend"),i==t.cwd().hash||n.is("."+g)?n.is("."+d)&&n.children("."+u).click():t.exec("open",r.thash||i)}).delegate("."+l+"."+d+" ."+u,"click",function(n){var i=e(this),r=i.parent("."+l),a=r.next("."+s);n.stopPropagation(),r.is("."+p)?(r.toggleClass(c),a.slideToggle()):(k.insertBefore(i),r.removeClass(d),t.request({cmd:"tree",target:t.navId2Hash(r.attr("id"))}).done(function(e){D(P(e.tree)),a.children().length&&(r.addClass(d+" "+c),a.slideDown()),M()}).always(function(){k.remove(),r.addClass(p)}))}).delegate("."+l,"contextmenu",function(n){n.preventDefault(),t.trigger("contextmenu",{type:"navbar",targets:[t.navId2Hash(e(this).attr("id"))],x:n.clientX,y:n.clientY})}),U=t.getUI("navbar").append(j).show();t.open(function(e){var t=e.data,n=P(t.files);t.init&&j.empty(),n.length&&(D(n),E(n,p)),M()}).add(function(e){var t=P(e.data.added);t.length&&(D(t),E(t,d))}).change(function(n){for(var i,r,a,o,d,u,h,f,m,g=P(n.data.changed),v=g.length;v--;)if(i=g[v],(r=j.find("#"+t.navHash2Id(i.hash))).length){if(i.phash){if(o=r.closest("."+s),d=A(i.phash),u=r.parent().next(),h=O(d,i),!d.length)continue;(d[0]!==o[0]||u.get(0)!==h.get(0))&&(h.length?h.before(r):d.append(r))}f=r.is("."+c),m=r.is("."+p),a=e(z(i)),r.replaceWith(a.children("."+l)),i.dirs&&(f||m)&&(r=j.find("#"+t.navHash2Id(i.hash)))&&r.next("."+s).children().length&&(f&&r.addClass(c),m&&r.addClass(p))}M(),S()}).remove(function(e){for(var n,i,r=e.data.removed,a=r.length;a--;)(n=j.find("#"+t.navHash2Id(r[a]))).length&&(i=n.closest("."+s),n.parent().detach(),i.children().length||i.hide().prev("."+l).removeClass(d+" "+c+" "+p))}).bind("search searchend",function(e){j.find("#"+t.navHash2Id(t.cwd().hash))["search"==e.type?"removeClass":"addClass"](h)}).bind("lockfiles unlockfiles",function(n){var i="lockfiles"==n.type,r=i?"disable":"enable",a=e.map(n.data.files||[],function(e){var n=t.file(e);return n&&"directory"==n.mime?e:null});e.each(a,function(e,n){var a=j.find("#"+t.navHash2Id(n));a.length&&(a.is("."+v)&&a.draggable(r),a.is("."+b)&&a.droppable(h),a[i?"addClass":"removeClass"](g))})})}),this},e.fn.elfinderuploadbutton=function(t){return this.each(function(){var n=e(this).elfinderbutton(t).unbind("click"),i=e("<form/>").appendTo(n),r=e('<input type="file" multiple="true"/>').change(function(){var n=e(this);n.val()&&(t.exec({input:n.remove()[0]}),r.clone(!0).appendTo(i))});i.append(r.clone(!0)),t.change(function(){i[t.disabled()?"hide":"show"]()}).change()})},e.fn.elfinderviewbutton=function(t){return this.each(function(){var n=e(this).elfinderbutton(t),i=n.children(".elfinder-button-icon");t.change(function(){var e="icons"==t.value;i.toggleClass("elfinder-button-icon-view-list",e),n.attr("title",t.fm.i18n(e?"viewlist":"viewicons"))})})},e.fn.elfinderworkzone=function(){var t="elfinder-workzone";return this.not("."+t).each(function(){var n=e(this).addClass(t),i=n.outerHeight(!0)-n.height(),r=n.parent();r.add(window).bind("resize",function(){var a=r.height();r.children(":visible:not(."+t+")").each(function(){var t=e(this);"absolute"!=t.css("position")&&(a-=t.outerHeight(!0))}),n.height(a-i)})}),this},elFinder.prototype.commands.archive=function(){var t=this,n=t.fm,i=[];this.variants=[],this.disableOnSearch=!0,n.bind("open reload",function(){t.variants=[],e.each(i=n.option("archivers").create||[],function(e,i){t.variants.push([i,n.mime2kind(i)])}),t.change()}),this.getstate=function(){return!this._disabled&&i.length&&n.selected().length&&n.cwd().write?0:-1},this.exec=function(t,r){var a,o=this.files(t),s=o.length,l=r||i[0],d=n.cwd(),c=["errArchive","errPerm","errCreatingTempDir","errFtpDownloadFile","errFtpUploadFile","errFtpMkdir","errArchiveExec","errExtractExec","errRm"],p=e.Deferred().fail(function(e){e&&n.error(e)});if(!(this.enabled()&&s&&i.length&&-1!==e.inArray(l,i)))return p.reject();if(!d.write)return p.reject(c);for(a=0;s>a;a++)if(!o[a].read)return p.reject(c);return n.request({data:{cmd:"archive",targets:this.hashes(t),type:l},notify:{type:"archive",cnt:1},syncOnFail:!0})}},elFinder.prototype.commands.back=function(){this.alwaysEnabled=!0,this.updateOnSelect=!1,this.shortcuts=[{pattern:"ctrl+left backspace"}],this.getstate=function(){return this.fm.history.canBack()?0:-1},this.exec=function(){return this.fm.history.back()}},elFinder.prototype.commands.copy=function(){this.shortcuts=[{pattern:"ctrl+c ctrl+insert"}],this.getstate=function(t){var t=this.files(t),n=t.length;return n&&e.map(t,function(e){return e.phash&&e.read?e:null}).length==n?0:-1},this.exec=function(t){var n=this.fm,i=e.Deferred().fail(function(e){n.error(e)});return e.each(this.files(t),function(e,t){return t.read&&t.phash?void 0:!i.reject(["errCopy",t.name,"errPerm"])}),"rejected"==i.state()?i:i.resolve(n.clipboard(this.hashes(t)))}},elFinder.prototype.commands.cut=function(){this.shortcuts=[{pattern:"ctrl+x shift+insert"}],this.getstate=function(t){var t=this.files(t),n=t.length;return n&&e.map(t,function(e){return e.phash&&e.read&&!e.locked?e:null}).length==n?0:-1},this.exec=function(t){var n=this.fm,i=e.Deferred().fail(function(e){n.error(e)});return e.each(this.files(t),function(e,t){return t.read&&t.phash?t.locked?!i.reject(["errLocked",t.name]):void 0:!i.reject(["errCopy",t.name,"errPerm"])}),"rejected"==i.state()?i:i.resolve(n.clipboard(this.hashes(t),!0))}},elFinder.prototype.commands.download=function(){var t=this,n=this.fm,i=function(n){return e.map(t.files(n),function(e){return"directory"==e.mime?null:e})};this.shortcuts=[{pattern:"shift+enter"}],this.getstate=function(){var e=this.fm.selected(),t=e.length;return this._disabled||!t||n.UA.IE&&1!=t||t!=i(e).length?-1:0},this.exec=function(t){var n,r=this.fm,a=r.options.url,o=i(t),s=e.Deferred(),l="",d="";if(this.disabled())return s.reject();if(r.oldAPI)return r.error("errCmdNoSupport"),s.reject();for(d=e.param(r.options.customData||{}),d&&(d="&"+d),a+=-1===a.indexOf("?")?"?":"&",n=0;n<o.length;n++)l+='<iframe class="downloader" id="downloader-'+o[n].hash+'" style="display:none" src="'+a+"cmd=file&target="+o[n].hash+"&download=1"+d+'"/>';return e(l).appendTo("body").ready(function(){setTimeout(function(){e(l).each(function(){e("#"+e(this).attr("id")).remove()})},r.UA.Firefox?2e4+1e4*n:1e3)}),r.trigger("download",{files:o}),s.resolve(t)}},elFinder.prototype.commands.duplicate=function(){var t=this.fm;this.getstate=function(n){var n=this.files(n),i=n.length;return!this._disabled&&i&&t.cwd().write&&e.map(n,function(e){return e.phash&&e.read?e:null}).length==i?0:-1},this.exec=function(t){var n=this.fm,i=this.files(t),r=i.length,a=e.Deferred().fail(function(e){e&&n.error(e)});return!r||this._disabled?a.reject():(e.each(i,function(e,t){return t.read&&n.file(t.phash).write?void 0:!a.reject(["errCopy",t.name,"errPerm"])}),"rejected"==a.state()?a:n.request({data:{cmd:"duplicate",targets:this.hashes(t)},notify:{type:"copy",cnt:r}}))}},elFinder.prototype.commands.edit=function(){var t=this,n=this.fm,i=n.res("mimes","text")||[],r=function(n){return e.map(n,function(n){return 0!==n.mime.indexOf("text/")&&-1===e.inArray(n.mime,i)||!n.mime.indexOf("text/rtf")||t.onlyMimes.length&&-1===e.inArray(n.mime,t.onlyMimes)||!n.read||!n.write?null:n})},a=function(i,r,a){var o=e.Deferred(),s=e('<textarea class="elfinder-file-edit" rows="20" id="'+i+'-ta">'+n.escape(a)+"</textarea>"),l=function(){s.editor&&s.editor.save(s[0],s.editor.instance),o.resolve(s.getContent()),s.elfinderdialog("close")},d=function(){o.reject(),s.elfinderdialog("close")},c={title:r.name,width:t.options.dialogWidth||450,buttons:{},close:function(){s.editor&&s.editor.close(s[0],s.editor.instance),e(this).elfinderdialog("destroy")},open:function(){n.disable(),s.focus(),s[0].setSelectionRange&&s[0].setSelectionRange(0,0),s.editor&&s.editor.load(s[0])}};return s.getContent=function(){return s.val()},e.each(t.options.editors||[],function(t,n){return-1!==e.inArray(r.mime,n.mimes||[])&&"function"==typeof n.load&&"function"==typeof n.save?(s.editor={load:n.load,save:n.save,close:"function"==typeof n.close?n.close:function(){},instance:null},!1):void 0}),s.editor||s.keydown(function(e){var t,n,i=e.keyCode;e.stopPropagation(),9==i&&(e.preventDefault(),this.setSelectionRange&&(t=this.value,n=this.selectionStart,this.value=t.substr(0,n)+"	"+t.substr(this.selectionEnd),n+=1,this.setSelectionRange(n,n))),(e.ctrlKey||e.metaKey)&&((81==i||87==i)&&(e.preventDefault(),d()),83==i&&(e.preventDefault(),l()))}),c.buttons[n.i18n("Save")]=l,c.buttons[n.i18n("Cancel")]=d,n.dialog(s,c).attr("id",i),o.promise()},o=function(t){var i,r=t.hash,o=(n.options,e.Deferred()),s=(n.url(r)||n.options.url,"edit-"+n.namespace+"-"+t.hash),l=n.getUI().find("#"+s);
return l.length?(l.elfinderdialog("toTop"),o.resolve()):t.read&&t.write?(n.request({data:{cmd:"get",target:r},notify:{type:"openfile",cnt:1},syncOnFail:!0}).done(function(e){a(s,t,e.content).done(function(e){n.request({options:{type:"post"},data:{cmd:"put",target:r,content:e},notify:{type:"save",cnt:1},syncOnFail:!0}).fail(function(e){o.reject(e)}).done(function(e){e.changed&&e.changed.length&&n.change(e),o.resolve(e)})})}).fail(function(e){o.reject(e)}),o.promise()):(i=["errOpen",t.name,"errPerm"],n.error(i),o.reject(i))};this.shortcuts=[{pattern:"ctrl+e"}],this.init=function(){this.onlyMimes=this.options.mimes||[]},this.getstate=function(e){var e=this.files(e),t=e.length;return!this._disabled&&t&&r(e).length==t?0:-1},this.exec=function(t){var n,i=r(this.files(t)),a=[];if(this.disabled())return e.Deferred().reject();for(;n=i.shift();)a.push(o(n));return a.length?e.when.apply(null,a):e.Deferred().reject()}},elFinder.prototype.commands.extract=function(){var t=this,n=t.fm,i=[],r=function(t){return e.map(t,function(t){return t.read&&-1!==e.inArray(t.mime,i)?t:null})};this.disableOnSearch=!0,n.bind("open reload",function(){i=n.option("archivers").extract||[],t.change()}),this.getstate=function(e){var e=this.files(e),t=e.length;return!this._disabled&&t&&this.fm.cwd().write&&r(e).length==t?0:-1},this.exec=function(t){var r,a,o,s=this.files(t),l=e.Deferred(),d=s.length,c=!1,p=!1,u=e.map(n.files(t),function(e){return e.name}),h={};e.map(n.files(t),function(e){h[e.name]=e});var f=function(e){switch(e){case"overwrite_all":c=!0;break;case"omit_all":p=!0}},m=function(t){t.read&&n.file(t.phash).write?-1===e.inArray(t.mime,i)?(a=["errExtract",t.name,"errNoArchive"],n.error(a),l.reject(a)):n.request({data:{cmd:"extract",target:t.hash},notify:{type:"extract",cnt:1},syncOnFail:!0}).fail(function(e){"rejected"!=l.state()&&l.reject(e)}).done(function(){}):(a=["errExtract",t.name,"errPerm"],n.error(a),l.reject(a))},g=function(t,i){var a=t[i],s=a.name.replace(/\.((tar\.(gz|bz|bz2|z|lzo))|cpio\.gz|ps\.gz|xcf\.(gz|bz2)|[a-z0-9]{1,4})$/gi,""),v=e.inArray(s,u)>=0;v&&"directory"!=h[s].mime?n.confirm({title:n.i18n("ntfextract"),text:n.i18n(["errExists",s,"confirmRepl"]),accept:{label:"btnYes",callback:function(e){if(o=e?"overwrite_all":"overwrite",f(o),c||p){if(c){for(r=0;d>r;r++)m(t[r]);l.resolve()}}else"overwrite"==o&&m(a),d>i+1?g(t,i+1):l.resolve()}},reject:{label:"btnNo",callback:function(e){o=e?"omit_all":"omit",f(o),!c&&!p&&d>i+1?g(t,i+1):p&&l.resolve()}},cancel:{label:"btnCancel",callback:function(){l.resolve()}},all:d>1}):(m(a),d>i+1?g(t,i+1):l.resolve())};return this.enabled()&&d&&i.length?(d>0&&g(s,0),l):l.reject()}},elFinder.prototype.commands.forward=function(){this.alwaysEnabled=!0,this.updateOnSelect=!0,this.shortcuts=[{pattern:"ctrl+right"}],this.getstate=function(){return this.fm.history.canForward()?0:-1},this.exec=function(){return this.fm.history.forward()}},elFinder.prototype.commands.getfile=function(){var t=this,n=this.fm,i=function(n){var i=t.options;return n=e.map(n,function(e){return"directory"!=e.mime||i.folders?e:null}),i.multiple||1==n.length?n:[]};this.alwaysEnabled=!0,this.callback=n.options.getFileCallback,this._disabled="function"==typeof this.callback,this.getstate=function(e){var e=this.files(e),t=e.length;return this.callback&&t&&i(e).length==t?0:-1},this.exec=function(n){var i,r,a,o=this.fm,s=this.options,l=this.files(n),d=l.length,c=o.option("url"),p=o.option("tmbUrl"),u=e.Deferred().done(function(e){o.trigger("getfile",{files:e}),t.callback(e,o),"close"==s.oncomplete?o.hide():"destroy"==s.oncomplete&&o.destroy()}),h=function(){return s.onlyURL?s.multiple?e.map(l,function(e){return e.url}):l[0].url:s.multiple?l:l[0]},f=[];if(-1==this.getstate())return u.reject();for(i=0;d>i;i++){if(r=l[i],"directory"==r.mime&&!s.folders)return u.reject();r.baseUrl=c,r.url=o.url(r.hash),r.path=o.path(r.hash),r.tmb&&1!=r.tmb&&(r.tmb=p+r.tmb),r.width||r.height||(r.dim?(a=r.dim.split("x"),r.width=a[0],r.height=a[1]):-1!==r.mime.indexOf("image")&&f.push(o.request({data:{cmd:"dim",target:r.hash},notify:{type:"dim",cnt:1,hideCnt:!0},preventDefault:!0}).done(function(e){if(e.dim){var t=e.dim.split("x"),n=o.file(this.hash);n.width=this.width=t[0],n.height=this.height=t[1]}}.bind(r))))}return f.length?(e.when.apply(null,f).always(function(){u.resolve(h(l))}),u):u.resolve(h(l))}},elFinder.prototype.commands.help=function(){var t,n=this.fm,i=this,r='<div class="elfinder-help-link"> <a href="{url}" target="_blank">{link}</a></div>',a='<div class="elfinder-help-team"><div>{author}</div>{work}</div>',o=/\{url\}/,s=/\{link\}/,l=/\{author\}/,d=/\{work\}/,c="replace",p="ui-priority-primary",u="ui-priority-secondary",h="elfinder-help-license",f='<li class="ui-state-default ui-corner-top"><a href="#{id}">{title}</a></li>',m=['<div class="ui-tabs ui-widget ui-widget-content ui-corner-all elfinder-help">','<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">'],g='<div class="elfinder-help-shortcut"><div class="elfinder-help-shortcut-pattern">{pattern}</div> {descrip}</div>',v='<div class="elfinder-help-separator"/>',b=function(){m.push('<div id="about" class="ui-tabs-panel ui-widget-content ui-corner-bottom"><div class="elfinder-help-logo"/>'),m.push("<h3>elFinder</h3>"),m.push('<div class="'+p+'">'+n.i18n("webfm")+"</div>"),m.push('<div class="'+u+'">'+n.i18n("ver")+": "+n.version+", "+n.i18n("protocolver")+': <span id="apiver"></span></div>'),m.push('<div class="'+u+'">jQuery/jQuery UI: '+e().jquery+"/"+e.ui.version+"</div>"),m.push(v),m.push(r[c](o,"http://elfinder.org/")[c](s,n.i18n("homepage"))),m.push(r[c](o,"https://github.com/Studio-42/elFinder/wiki")[c](s,n.i18n("docs"))),m.push(r[c](o,"https://github.com/Studio-42/elFinder")[c](s,n.i18n("github"))),m.push(r[c](o,"http://twitter.com/elrte_elfinder")[c](s,n.i18n("twitter"))),m.push(v),m.push('<div class="'+p+'">'+n.i18n("team")+"</div>"),m.push(a[c](l,'Dmitry "dio" Levashov &lt;dio@std42.ru&gt;')[c](d,n.i18n("chiefdev"))),m.push(a[c](l,"Troex Nevelin &lt;troex@fury.scancode.ru&gt;")[c](d,n.i18n("maintainer"))),m.push(a[c](l,"Alexey Sukhotin &lt;strogg@yandex.ru&gt;")[c](d,n.i18n("contributor"))),m.push(a[c](l,"Naoki Sawada &lt;hypweb@gmail.com&gt;")[c](d,n.i18n("contributor"))),n.i18[n.lang].translator&&m.push(a[c](l,n.i18[n.lang].translator)[c](d,n.i18n("translator")+" ("+n.i18[n.lang].language+")")),m.push(v),m.push('<div class="'+h+'">'+n.i18n("icons")+': Pixelmixer, <a href="http://p.yusukekamiyamane.com" target="_blank">Fugue</a></div>'),m.push(v),m.push('<div class="'+h+'">Licence: BSD Licence</div>'),m.push('<div class="'+h+'">Copyright © 2009-2011, Studio 42</div>'),m.push('<div class="'+h+'">„ …'+n.i18n("dontforget")+" ”</div>"),m.push("</div>")},y=function(){var t=n.shortcuts();m.push('<div id="shortcuts" class="ui-tabs-panel ui-widget-content ui-corner-bottom">'),t.length?(m.push('<div class="ui-widget-content elfinder-help-shortcuts">'),e.each(t,function(e,t){m.push(g.replace(/\{pattern\}/,t[0]).replace(/\{descrip\}/,t[1]))}),m.push("</div>")):m.push('<div class="elfinder-help-disabled">'+n.i18n("shortcutsof")+"</div>"),m.push("</div>")},w=function(){m.push('<div id="help" class="ui-tabs-panel ui-widget-content ui-corner-bottom">'),m.push('<a href="http://elfinder.org/forum/" target="_blank" class="elfinder-dont-panic"><span>DON\'T PANIC</span></a>'),m.push("</div>")};this.alwaysEnabled=!0,this.updateOnSelect=!1,this.state=0,this.shortcuts=[{pattern:"f1",description:this.title}],setTimeout(function(){var r=i.options.view||["about","shortcuts","help"];e.each(r,function(e,t){m.push(f[c](/\{id\}/,t)[c](/\{title\}/,n.i18n(t)))}),m.push("</ul>"),-1!==e.inArray("about",r)&&b(),-1!==e.inArray("shortcuts",r)&&y(),-1!==e.inArray("help",r)&&w(),m.push("</div>"),t=e(m.join("")),n.one("load",function(){t.find("#apiver").text(n.api)}),t.find(".ui-tabs-nav li").hover(function(){e(this).toggleClass("ui-state-hover")}).children().click(function(n){var i=e(this);n.preventDefault(),n.stopPropagation(),i.is(".ui-tabs-selected")||(i.parent().addClass("ui-tabs-selected ui-state-active").siblings().removeClass("ui-tabs-selected").removeClass("ui-state-active"),t.find(".ui-tabs-panel").hide().filter(i.attr("href")).show())}).filter(":first").click()},200),this.getstate=function(){return 0},this.exec=function(){this.dialog||(this.dialog=this.fm.dialog(t,{title:this.title,width:530,autoOpen:!1,destroyOnClose:!1})),this.dialog.elfinderdialog("open").find(".ui-tabs-nav li a:first").click()}},elFinder.prototype.commands.home=function(){this.title="Home",this.alwaysEnabled=!0,this.updateOnSelect=!1,this.shortcuts=[{pattern:"ctrl+home ctrl+shift+up",description:"Home"}],this.getstate=function(){var e=this.fm.root(),t=this.fm.cwd().hash;return e&&t&&e!=t?0:-1},this.exec=function(){return this.fm.exec("open",this.fm.root())}},elFinder.prototype.commands.info=function(){var t=this.fm,n="elfinder-info-spinner",i={calc:t.i18n("calc"),size:t.i18n("size"),unknown:t.i18n("unknown"),path:t.i18n("path"),aliasfor:t.i18n("aliasfor"),modify:t.i18n("modify"),perms:t.i18n("perms"),locked:t.i18n("locked"),dim:t.i18n("dim"),kind:t.i18n("kind"),files:t.i18n("files"),folders:t.i18n("folders"),items:t.i18n("items"),yes:t.i18n("yes"),no:t.i18n("no"),link:t.i18n("link")};this.tpl={main:'<div class="ui-helper-clearfix elfinder-info-title"><span class="elfinder-cwd-icon {class} ui-corner-all"/>{title}</div><table class="elfinder-info-tb">{content}</table>',itemTitle:'<strong>{name}</strong><span class="elfinder-info-kind">{kind}</span>',groupTitle:"<strong>{items}: {num}</strong>",row:"<tr><td>{label} : </td><td>{value}</td></tr>",spinner:'<span>{text}</span> <span class="'+n+'"/>'},this.alwaysEnabled=!0,this.updateOnSelect=!1,this.shortcuts=[{pattern:"ctrl+i"}],this.init=function(){e.each(i,function(e,n){i[e]=t.i18n(n)})},this.getstate=function(){return 0},this.exec=function(t){var r=this.files(t);r.length||(r=this.files([this.fm.cwd().hash]));var a,o,s,l,d,c=this.fm,p=this.options,u=this.tpl,h=u.row,f=r.length,m=[],g=u.main,v="{label}",b="{value}",y={title:this.title,width:"auto",close:function(){e(this).elfinderdialog("destroy")}},w=[],x=function(e){C.find("."+n).parent().text(e)},k=c.namespace+"-info-"+e.map(r,function(e){return e.hash}).join("-"),C=c.getUI().find("#"+k);if(!f)return e.Deferred().reject();if(C.length)return C.elfinderdialog("toTop"),e.Deferred().resolve();if(1==f){if(s=r[0],g=g.replace("{class}",c.mime2class(s.mime)),l=u.itemTitle.replace("{name}",c.escape(s.i18||s.name)).replace("{kind}",c.mime2kind(s)),s.tmb&&(o=c.option("tmbUrl")+s.tmb),s.read?"directory"!=s.mime||s.alias?a=c.formatSize(s.size):(a=u.spinner.replace("{text}",i.calc),w.push(s.hash)):a=i.unknown,m.push(h.replace(v,i.size).replace(b,a)),s.alias&&m.push(h.replace(v,i.aliasfor).replace(b,s.alias)),m.push(h.replace(v,i.path).replace(b,c.escape(c.path(s.hash,!0)))),s.read){var I;if(p.nullUrlDirLinkSelf&&"directory"==s.mime&&null===s.url){var F=window.location;I=F.pathname+F.search+"#elf_"+s.hash}else I=c.url(s.hash);m.push(h.replace(v,i.link).replace(b,'<a href="'+I+'" target="_blank">'+s.name+"</a>"))}s.dim?m.push(h.replace(v,i.dim).replace(b,s.dim)):-1!==s.mime.indexOf("image")&&(s.width&&s.height?m.push(h.replace(v,i.dim).replace(b,s.width+"x"+s.height)):(m.push(h.replace(v,i.dim).replace(b,u.spinner.replace("{text}",i.calc))),c.request({data:{cmd:"dim",target:s.hash},preventDefault:!0}).fail(function(){x(i.unknown)}).done(function(e){if(x(e.dim||i.unknown),e.dim){var t=e.dim.split("x"),n=c.file(s.hash);n.width=t[0],n.height=t[1]}}))),m.push(h.replace(v,i.modify).replace(b,c.formatDate(s))),m.push(h.replace(v,i.perms).replace(b,c.formatPermissions(s))),m.push(h.replace(v,i.locked).replace(b,s.locked?i.yes:i.no))}else g=g.replace("{class}","elfinder-cwd-icon-group"),l=u.groupTitle.replace("{items}",i.items).replace("{num}",f),d=e.map(r,function(e){return"directory"==e.mime?1:null}).length,d?(m.push(h.replace(v,i.kind).replace(b,d==f?i.folders:i.folders+" "+d+", "+i.files+" "+(f-d))),m.push(h.replace(v,i.size).replace(b,u.spinner.replace("{text}",i.calc))),w=e.map(r,function(e){return e.hash})):(a=0,e.each(r,function(e,t){var n=parseInt(t.size);n>=0&&a>=0?a+=n:a="unknown"}),m.push(h.replace(v,i.kind).replace(b,i.files)),m.push(h.replace(v,i.size).replace(b,c.formatSize(a))));g=g.replace("{title}",l).replace("{content}",m.join("")),C=c.dialog(g,y),C.attr("id",k),o&&e("<img/>").load(function(){C.find(".elfinder-cwd-icon").css("background",'url("'+o+'") center center no-repeat')}).attr("src",o),w.length&&c.request({data:{cmd:"size",targets:w},preventDefault:!0}).fail(function(){x(i.unknown)}).done(function(e){var t=parseInt(e.size);x(t>=0?c.formatSize(t):i.unknown)})}},elFinder.prototype.commands.mkdir=function(){this.disableOnSearch=!0,this.updateOnSelect=!1,this.mime="directory",this.prefix="untitled folder",this.exec=e.proxy(this.fm.res("mixin","make"),this),this.shortcuts=[{pattern:"ctrl+shift+n"}],this.getstate=function(){return!this._disabled&&this.fm.cwd().write?0:-1}},elFinder.prototype.commands.mkfile=function(){this.disableOnSearch=!0,this.updateOnSelect=!1,this.mime="text/plain",this.prefix="untitled file.txt",this.exec=e.proxy(this.fm.res("mixin","make"),this),this.getstate=function(){return!this._disabled&&this.fm.cwd().write?0:-1}},elFinder.prototype.commands.netmount=function(){var t=this;this.alwaysEnabled=!0,this.updateOnSelect=!1,this.drivers=[],this.handlers={load:function(){this.drivers=this.fm.netDrivers}},this.getstate=function(){return this.drivers.length?0:-1},this.exec=function(){var n=t.fm,i=e.Deferred(),r=function(){var r={protocol:e("<select/>"),host:e('<input type="text"/>'),port:e('<input type="text"/>'),path:e('<input type="text" value="/"/>'),user:e('<input type="text"/>'),pass:e('<input type="password"/>')},a={title:n.i18n("netMountDialogTitle"),resizable:!1,modal:!0,destroyOnClose:!0,close:function(){delete t.dialog,"pending"==i.state()&&i.reject()},buttons:{}},o=e('<table class="elfinder-info-tb elfinder-netmount-tb"/>');return e.each(t.drivers,function(e,t){r.protocol.append('<option value="'+t+'">'+n.i18n(t)+"</option>")}),e.each(r,function(t,i){"protocol"!=t&&i.addClass("ui-corner-all"),o.append(e("<tr/>").append(e("<td>"+n.i18n(t)+"</td>")).append(e("<td/>").append(i)))}),a.buttons[n.i18n("btnMount")]=function(){var n={cmd:"netmount"};return e.each(r,function(t,i){var r=e.trim(i.val());r&&(n[t]=r)}),n.host?(t.fm.request({data:n,notify:{type:"netmount",cnt:1}}).done(function(){i.resolve()}).fail(function(e){i.reject(e)}),void t.dialog.elfinderdialog("close")):t.fm.trigger("error",{error:"errNetMountHostReq"})},a.buttons[n.i18n("btnCancel")]=function(){t.dialog.elfinderdialog("close")},n.dialog(o,a)};return t.dialog||(t.dialog=r()),i.promise()}},elFinder.prototype.commands.open=function(){this.alwaysEnabled=!0,this._handlers={dblclick:function(e){e.preventDefault(),this.exec()},"select enable disable reload":function(e){this.update("disable"==e.type?-1:void 0)}},this.shortcuts=[{pattern:"ctrl+down numpad_enter"+("mac"!=this.fm.OS&&" enter")}],this.getstate=function(t){var t=this.files(t),n=t.length;return 1==n?0:n&&e.map(t,function(e){return"directory"==e.mime?null:e}).length==n?0:-1},this.exec=function(t){var n,i,r,a,o=this.fm,s=e.Deferred().fail(function(e){e&&o.error(e)}),l=this.files(t),d=l.length;if(!d)return s.reject();if(1==d&&(n=l[0])&&"directory"==n.mime)return n&&!n.read?s.reject(["errOpen",n.name,"errPerm"]):o.request({data:{cmd:"open",target:n.thash||n.hash},notify:{type:"open",cnt:1,hideCnt:!0},syncOnFail:!0});if(l=e.map(l,function(e){return"directory"!=e.mime?e:null}),d!=l.length)return s.reject();for(d=l.length;d--;){if(n=l[d],!n.read)return s.reject(["errOpen",n.name,"errPerm"]);(i=o.url(n.hash))||(i=o.options.url,i=i+(-1===i.indexOf("?")?"?":"&")+(o.oldAPI?"cmd=open&current="+n.phash:"cmd=file")+"&target="+n.hash),n.dim?(r=n.dim.split("x"),a="width="+(parseInt(r[0])+20)+",height="+(parseInt(r[1])+20)):a="width="+parseInt(2*e(window).width()/3)+",height="+parseInt(2*e(window).height()/3);var c=window.open("","new_window",a+",top=50,left=50,scrollbars=yes,resizable=yes");if(!c)return s.reject("errPopup");var p=document.createElement("form");p.action=o.options.url,p.method="POST",p.target="new_window",p.style.display="none";var u=e.extend({},o.options.customData,{cmd:"file",target:n.hash});e.each(u,function(e,t){var n=document.createElement("input");n.name=e,n.value=t,p.appendChild(n)}),document.body.appendChild(p),p.submit()}return s.resolve(t)}},elFinder.prototype.commands.paste=function(){this.updateOnSelect=!1,this.handlers={changeclipboard:function(){this.update()}},this.shortcuts=[{pattern:"ctrl+v shift+insert"}],this.getstate=function(t){if(this._disabled)return-1;if(t){if(e.isArray(t)){if(1!=t.length)return-1;t=this.fm.file(t[0])}}else t=this.fm.cwd();return this.fm.clipboard().length&&"directory"==t.mime&&t.write?0:-1},this.exec=function(t){var n,i,r=this,a=r.fm,t=t?this.files(t)[0]:a.cwd(),o=a.clipboard(),s=o.length,l=s?o[0].cut:!1,d=l?"errMove":"errCopy",c=[],p=[],u=e.Deferred().fail(function(e){e&&a.error(e)}),h=function(t){return t.length&&a._commands.duplicate?a.exec("duplicate",t):e.Deferred().resolve()},f=function(n){var i=e.Deferred(),o=[],s=function(t,n){for(var i=[],r=t.length;r--;)-1!==e.inArray(t[r].name,n)&&i.unshift(r);return i},d=function(e){var t=o[e],r=n[t],s=e==o.length-1;r&&a.confirm({title:a.i18n(l?"moveFiles":"copyFiles"),text:a.i18n(["errExists",r.name,"confirmRepl"]),all:!s,accept:{label:"btnYes",callback:function(t){s||t?p(n):d(++e)}},reject:{label:"btnNo",callback:function(t){var i;if(t)for(i=o.length;e<i--;)n[o[i]].remove=!0;else n[o[e]].remove=!0;s||t?p(n):d(++e)}},cancel:{label:"btnCancel",callback:function(){i.resolve()}}})},c=function(e){o=s(n,e),o.length?d(0):p(n)},p=function(n){var r,n=e.map(n,function(e){return e.remove?null:e}),o=n.length;return o?(r=n[0].phash,n=e.map(n,function(e){return e.hash}),void a.request({data:{cmd:"paste",dst:t.hash,targets:n,cut:l?1:0,src:r},notify:{type:l?"move":"copy",cnt:o}}).always(function(){i.resolve(),a.unlockfiles({files:n})})):i.resolve()};return r._disabled||!n.length?i.resolve():(a.oldAPI?p(n):a.option("copyOverwrite")?t.hash==a.cwd().hash?c(e.map(a.files(),function(e){return e.phash==t.hash?e.name:null})):a.request({data:{cmd:"ls",target:t.hash},notify:{type:"prepare",cnt:1,hideCnt:!0},preventFail:!0}).always(function(e){c(e.list||[])}):p(n),i)};return s&&t&&"directory"==t.mime?t.write?(n=a.parents(t.hash),e.each(o,function(r,s){return s.read?l&&s.locked?!u.reject(["errLocked",s.name]):-1!==e.inArray(s.hash,n)?!u.reject(["errCopyInItself",s.name]):(i=a.parents(s.hash),i.pop(),-1!==e.inArray(t.hash,i)&&e.map(i,function(e){var n=a.file(e);return n.phash==t.hash&&n.name==s.name?n:null}).length?!u.reject(["errReplByChild",s.name]):void(s.phash==t.hash?p.push(s.hash):c.push({hash:s.hash,phash:s.phash,name:s.name}))):!u.reject([d,o[0].name,"errPerm"])}),"rejected"==u.state()?u:e.when(h(p),f(c)).always(function(){l&&a.clipboard([])})):u.reject([d,o[0].name,"errPerm"]):u.reject()}},elFinder.prototype.commands.quicklook=function(){var t,n,i,r,a=this,o=a.fm,s=0,l=1,d=2,c=s,p="elfinder-quicklook-navbar-icon",u="elfinder-quicklook-fullscreen",h=function(t){e(document).trigger(e.Event("keydown",{keyCode:t,ctrlKey:!1,shiftKey:!1,altKey:!1,metaKey:!1}))},f=function(e){return{opacity:0,width:20,height:"list"==o.view?1:20,top:e.offset().top+"px",left:e.offset().left+"px"}},m=function(){var i=e(window);return{opacity:1,width:t,height:n,top:parseInt((i.height()-n)/2+i.scrollTop()),left:parseInt((i.width()-t)/2+i.scrollLeft())}},g=function(e){var t=document.createElement(e.substr(0,e.indexOf("/"))),n=!1;try{n=t.canPlayType&&t.canPlayType(e)}catch(i){}return n&&""!==n&&"no"!=n},v=e('<div class="elfinder-quicklook-title"/>'),b=e("<div/>"),y=e('<div class="elfinder-quicklook-info"/>'),w=e('<div class="'+p+" "+p+'-fullscreen"/>').mousedown(function(t){var n=a.window,r=n.is("."+u),s="scroll."+o.namespace,l=e(window);t.stopPropagation(),r?(n.css(n.data("position")).unbind("mousemove"),l.unbind(s).trigger(a.resize).unbind(a.resize),x.unbind("mouseenter").unbind("mousemove")):(n.data("position",{left:n.css("left"),top:n.css("top"),width:n.width(),height:n.height()}).css({width:"100%",height:"100%"}),e(window).bind(s,function(){n.css({left:parseInt(e(window).scrollLeft())+"px",top:parseInt(e(window).scrollTop())+"px"})}).bind(a.resize,function(){a.preview.trigger("changesize")}).trigger(s).trigger(a.resize),n.bind("mousemove",function(){x.stop(!0,!0).show().delay(3e3).fadeOut("slow")}).mousemove(),x.mouseenter(function(){x.stop(!0,!0).show()}).mousemove(function(e){e.stopPropagation()})),x.attr("style","").draggable(r?"destroy":{}),n.toggleClass(u),e(this).toggleClass(p+"-fullscreen-off"),e.fn.resizable&&i.add(n).resizable(r?"enable":"disable").removeClass("ui-state-disabled")}),x=e('<div class="elfinder-quicklook-navbar"/>').append(e('<div class="'+p+" "+p+'-prev"/>').mousedown(function(){h(37)})).append(w).append(e('<div class="'+p+" "+p+'-next"/>').mousedown(function(){h(39)})).append('<div class="elfinder-quicklook-navbar-separator"/>').append(e('<div class="'+p+" "+p+'-close"/>').mousedown(function(){a.window.trigger("close")}));this.resize="resize."+o.namespace,this.info=e('<div class="elfinder-quicklook-info-wrapper"/>').append(b).append(y),this.preview=e('<div class="elfinder-quicklook-preview ui-helper-clearfix"/>').bind("change",function(){a.info.attr("style","").hide(),b.removeAttr("class").attr("style",""),y.html("")}).bind("update",function(t){var n,i=a.fm,r=(a.preview,t.file),o='<div class="elfinder-quicklook-info-data">{value}</div>';r?(!r.read&&t.stopImmediatePropagation(),a.window.data("hash",r.hash),a.preview.unbind("changesize").trigger("change").children().remove(),v.html(i.escape(r.name)),y.html(o.replace(/\{value\}/,r.name)+o.replace(/\{value\}/,i.mime2kind(r))+("directory"==r.mime?"":o.replace(/\{value\}/,i.formatSize(r.size)))+o.replace(/\{value\}/,i.i18n("modify")+": "+i.formatDate(r))),b.addClass("elfinder-cwd-icon ui-corner-all "+i.mime2class(r.mime)),r.tmb&&e("<img/>").hide().appendTo(a.preview).load(function(){b.css("background",'url("'+n+'") center center no-repeat'),e(this).remove()}).attr("src",n=i.tmb(r.hash)),a.info.delay(100).fadeIn(10)):t.stopImmediatePropagation()}),this.window=e('<div class="ui-helper-reset ui-widget elfinder-quicklook" style="position:absolute"/>').click(function(e){e.stopPropagation()}).append(e('<div class="elfinder-quicklook-titlebar"/>').append(v).append(e('<span class="ui-icon ui-icon-circle-close"/>').mousedown(function(e){e.stopPropagation(),a.window.trigger("close")}))).append(this.preview.add(x)).append(a.info.hide()).draggable({handle:"div.elfinder-quicklook-titlebar"}).bind("open",function(){var e,t=a.window,n=a.value;a.closed()&&n&&(e=r.find("#"+n.hash)).length&&(x.attr("style",""),c=l,e.trigger("scrolltoview"),t.css(f(e)).show().animate(m(),550,function(){c=d,a.update(1,a.value)}))}).bind("close",function(){var e=a.window,t=a.preview.trigger("change"),n=(a.value,r.find("#"+e.data("hash"))),i=function(){c=s,e.hide(),t.children().remove(),a.update(0,a.value)};a.opened()&&(c=l,e.is("."+u)&&w.mousedown(),n.length?e.animate(f(n),500,i):i())}),this.alwaysEnabled=!0,this.value=null,this.handlers={select:function(){this.update(void 0,this.fm.selectedFiles()[0])},error:function(){a.window.is(":visible")&&a.window.data("hash","").trigger("close")},"searchshow searchhide":function(){this.opened()&&this.window.trigger("close")}},this.shortcuts=[{pattern:"space"}],this.support={audio:{ogg:g('audio/ogg; codecs="vorbis"'),mp3:g("audio/mpeg;"),wav:g('audio/wav; codecs="1"'),m4a:g("audio/x-m4a;")||g("audio/aac;")},video:{ogg:g('video/ogg; codecs="theora"'),webm:g('video/webm; codecs="vp8, vorbis"'),mp4:g('video/mp4; codecs="avc1.42E01E"')||g('video/mp4; codecs="avc1.42E01E, mp4a.40.2"')}},this.closed=function(){return c==s},this.opened=function(){return c==d},this.init=function(){var s=this.options,l=this.window,d=this.preview;t=s.width>0?parseInt(s.width):450,n=s.height>0?parseInt(s.height):300,o.one("load",function(){i=o.getUI(),r=o.getUI("cwd"),l.appendTo("body").zIndex(100+i.zIndex()),e(document).keydown(function(e){27==e.keyCode&&a.opened()&&l.trigger("close")}),e.fn.resizable&&l.resizable({handles:"se",minWidth:350,minHeight:120,resize:function(){d.trigger("changesize")}}),a.change(function(){a.opened()&&(a.value?d.trigger(e.Event("update",{file:a.value})):l.trigger("close"))}),e.each(o.commands.quicklook.plugins||[],function(e,t){"function"==typeof t&&new t(a)}),d.bind("update",function(){a.info.show()})})},this.getstate=function(){return 1==this.fm.selected().length?c==d?1:0:-1},this.exec=function(){this.enabled()&&this.window.trigger(this.opened()?"close":"open")},this.hideinfo=function(){this.info.stop(!0).hide()}},elFinder.prototype.commands.quicklook.plugins=[function(t){var n=["image/jpeg","image/png","image/gif"],i=t.preview;e.each(navigator.mimeTypes,function(t,i){var r=i.type;0===r.indexOf("image/")&&e.inArray(r,n)&&n.push(r)}),i.bind("update",function(r){var a,o=r.file;-1!==e.inArray(o.mime,n)&&(r.stopImmediatePropagation(),a=e("<img/>").hide().appendTo(i).load(function(){setTimeout(function(){var e=(a.width()/a.height()).toFixed(2);i.bind("changesize",function(){var t,n,r=parseInt(i.width()),o=parseInt(i.height());e<(r/o).toFixed(2)?(n=o,t=Math.floor(n*e)):(t=r,n=Math.floor(t/e)),a.width(t).height(n).css("margin-top",o>n?Math.floor((o-n)/2):0)}).trigger("changesize"),t.hideinfo(),a.fadeIn(100)},1)}).attr("src",t.fm.url(o.hash)))})},function(t){var n=["text/html","application/xhtml+xml"],i=t.preview,r=t.fm;i.bind("update",function(a){var o,s=a.file;-1!==e.inArray(s.mime,n)&&(a.stopImmediatePropagation(),i.one("change",function(){"pending"==o.state()&&o.reject()}),o=r.request({data:{cmd:"get",target:s.hash,current:s.phash},preventDefault:!0}).done(function(n){t.hideinfo(),doc=e('<iframe class="elfinder-quicklook-preview-html"/>').appendTo(i)[0].contentWindow.document,doc.open(),doc.write(n.content),doc.close()}))})},function(t){var n=t.fm,i=n.res("mimes","text"),r=t.preview;r.bind("update",function(a){var o,s=a.file,l=s.mime;(0===l.indexOf("text/")||-1!==e.inArray(l,i))&&(a.stopImmediatePropagation(),r.one("change",function(){"pending"==o.state()&&o.reject()}),o=n.request({data:{cmd:"get",target:s.hash},preventDefault:!0}).done(function(i){t.hideinfo(),e('<div class="elfinder-quicklook-preview-text-wrapper"><pre class="elfinder-quicklook-preview-text">'+n.escape(i.content)+"</pre></div>").appendTo(r)}))})},function(t){var n=t.fm,i="application/pdf",r=t.preview,a=!1;n.UA.Safari&&"mac"==n.OS||n.UA.IE?a=!0:e.each(navigator.plugins,function(t,n){e.each(n,function(e,t){return t.type==i?!(a=!0):void 0})}),a&&r.bind("update",function(a){var o,s=a.file;s.mime==i&&(a.stopImmediatePropagation(),r.one("change",function(){o.unbind("load").remove()}),o=e('<iframe class="elfinder-quicklook-preview-pdf"/>').hide().appendTo(r).load(function(){t.hideinfo(),o.show()}).attr("src",n.url(s.hash)))})},function(t){var n=t.fm,i="application/x-shockwave-flash",r=t.preview,a=!1;e.each(navigator.plugins,function(t,n){e.each(n,function(e,t){return t.type==i?!(a=!0):void 0})}),a&&r.bind("update",function(a){var o,s=a.file;s.mime==i&&(a.stopImmediatePropagation(),t.hideinfo(),r.append(o=e('<embed class="elfinder-quicklook-preview-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" src="'+n.url(s.hash)+'" quality="high" type="application/x-shockwave-flash" />')))})},function(t){var n,i=t.preview,r=!!t.options.autoplay,a={"audio/mpeg":"mp3","audio/mpeg3":"mp3","audio/mp3":"mp3","audio/x-mpeg3":"mp3","audio/x-mp3":"mp3","audio/x-wav":"wav","audio/wav":"wav","audio/x-m4a":"m4a","audio/aac":"m4a","audio/mp4":"m4a","audio/x-mp4":"m4a","audio/ogg":"ogg"};i.bind("update",function(o){var s=o.file,l=a[s.mime];t.support.audio[l]&&(o.stopImmediatePropagation(),n=e('<audio class="elfinder-quicklook-preview-audio" controls preload="auto" autobuffer><source src="'+t.fm.url(s.hash)+'" /></audio>').appendTo(i),r&&n[0].play())}).bind("change",function(){n&&n.parent().length&&(n[0].pause(),n.remove(),n=null)})},function(t){var n,i=t.preview,r=!!t.options.autoplay,a={"video/mp4":"mp4","video/x-m4v":"mp4","video/ogg":"ogg","application/ogg":"ogg","video/webm":"webm"};i.bind("update",function(o){var s=o.file,l=a[s.mime];t.support.video[l]&&(o.stopImmediatePropagation(),t.hideinfo(),n=e('<video class="elfinder-quicklook-preview-video" controls preload="auto" autobuffer><source src="'+t.fm.url(s.hash)+'" /></video>').appendTo(i),r&&n[0].play())}).bind("change",function(){n&&n.parent().length&&(n[0].pause(),n.remove(),n=null)})},function(t){var n,i=t.preview,r=[];e.each(navigator.plugins,function(t,n){e.each(n,function(e,t){(0===t.type.indexOf("audio/")||0===t.type.indexOf("video/"))&&r.push(t.type)})}),i.bind("update",function(a){var o,s=a.file,l=s.mime;-1!==e.inArray(s.mime,r)&&(a.stopImmediatePropagation(),(o=0===l.indexOf("video/"))&&t.hideinfo(),n=e('<embed src="'+t.fm.url(s.hash)+'" type="'+l+'" class="elfinder-quicklook-preview-'+(o?"video":"audio")+'"/>').appendTo(i))}).bind("change",function(){n&&n.parent().length&&(n.remove(),n=null)})}],elFinder.prototype.commands.reload=function(){this.alwaysEnabled=!0,this.updateOnSelect=!0,this.shortcuts=[{pattern:"ctrl+shift+r f5"}],this.getstate=function(){return 0},this.exec=function(){var e=this.fm,t=e.sync(),n=setTimeout(function(){e.notify({type:"reload",cnt:1,hideCnt:!0}),t.always(function(){e.notify({type:"reload",cnt:-1})})},e.notifyDelay);return t.always(function(){clearTimeout(n),e.trigger("reload")})}},elFinder.prototype.commands.rename=function(){this.shortcuts=[{pattern:"f2"+("mac"==this.fm.OS?" enter":"")}],this.getstate=function(){var e=this.fm.selectedFiles();return this._disabled||1!=e.length||!e[0].phash||e[0].locked?-1:0},this.exec=function(){var t=this.fm,n=t.getUI("cwd"),i=t.selected(),r=i.length,a=t.file(i.shift()),o=".elfinder-cwd-filename",s=e.Deferred().fail(function(e){var i=l.parent(),r=t.escape(a.name);i.length?(l.remove(),i.html(r)):(n.find("#"+a.hash).find(o).html(r),setTimeout(function(){n.find("#"+a.hash).click()},50)),e&&t.error(e)}).always(function(){t.enable()}),l=e('<input type="text"/>').keydown(function(t){t.stopPropagation(),t.stopImmediatePropagation(),t.keyCode==e.ui.keyCode.ESCAPE?s.reject():t.keyCode==e.ui.keyCode.ENTER&&l.blur()}).mousedown(function(e){e.stopPropagation()}).dblclick(function(e){e.stopPropagation(),e.preventDefault()}).blur(function(){var n=e.trim(l.val()),i=l.parent();if(i.length){if(l[0].setSelectionRange&&l[0].setSelectionRange(0,0),n==a.name)return s.reject();if(!n)return s.reject("errInvName");if(t.fileByName(n,a.phash))return s.reject(["errExists",n]);i.html(t.escape(n)),t.lockfiles({files:[a.hash]}),t.request({data:{cmd:"rename",target:a.hash,name:n},notify:{type:"rename",cnt:1}}).fail(function(){s.reject(),t.sync()}).done(function(e){s.resolve(e)}).always(function(){t.unlockfiles({files:[a.hash]})})}}),d=n.find("#"+a.hash).find(o).empty().append(l.val(a.name)),c=l.val().replace(/\.((tar\.(gz|bz|bz2|z|lzo))|cpio\.gz|ps\.gz|xcf\.(gz|bz2)|[a-z0-9]{1,4})$/gi,"");return this.disabled()?s.reject():!a||r>1||!d.length?s.reject("errCmdParams",this.title):a.locked?s.reject(["errLocked",a.name]):(t.one("select",function(){l.parent().length&&a&&-1===e.inArray(a.hash,t.selected())&&l.blur()}),l.select().focus(),l[0].setSelectionRange&&l[0].setSelectionRange(0,c.length),s)}},elFinder.prototype.commands.resize=function(){this.updateOnSelect=!1,this.getstate=function(){var e=this.fm.selectedFiles();return!this._disabled&&1==e.length&&e[0].read&&e[0].write&&-1!==e[0].mime.indexOf("image/")?0:-1},this.exec=function(t){var n,i,r=this.fm,a=this.files(t),o=e.Deferred(),s=function(t,n){var i=e('<div class="elfinder-dialog-resize"/>'),a='<input type="text" size="5"/>',s='<div class="elfinder-resize-row"/>',l='<div class="elfinder-resize-label"/>',d=e('<div class="elfinder-resize-control"/>'),c=e('<div class="elfinder-resize-preview"/>'),p=e('<div class="elfinder-resize-spinner">'+r.i18n("ntfloadimg")+"</div>"),u=e('<div class="elfinder-resize-handle"/>'),h=e('<div class="elfinder-resize-handle"/>'),f=e('<div class="elfinder-resize-uiresize"/>'),m=e('<div class="elfinder-resize-uicrop"/>'),g='<div class="ui-widget-content ui-corner-all elfinder-buttonset"/>',v='<div class="ui-state-default elfinder-button"/>',b='<span class="ui-widget-content elfinder-toolbar-button-separator"/>',y=e('<div class="elfinder-resize-rotate"/>'),w=e(v).attr("title",r.i18n("rotate-cw")).append(e('<span class="elfinder-button-icon elfinder-button-icon-rotate-l"/>').click(function(){W-=90,Q.update(W)
})),x=e(v).attr("title",r.i18n("rotate-ccw")).append(e('<span class="elfinder-button-icon elfinder-button-icon-rotate-r"/>').click(function(){W+=90,Q.update(W)})),k=e("<span />"),C=e('<div class="ui-state-default ui-corner-all elfinder-resize-reset"><span class="ui-icon ui-icon-arrowreturnthick-1-w"/></div>'),I=e('<div class="elfinder-resize-type"/>').append('<input type="radio" name="type" id="'+n+'-resize" value="resize" checked="checked" /><label for="'+n+'-resize">'+r.i18n("resize")+"</label>").append('<input type="radio" name="type" id="'+n+'-crop" value="crop" /><label for="'+n+'-crop">'+r.i18n("crop")+"</label>").append('<input type="radio" name="type" id="'+n+'-rotate" value="rotate" /><label for="'+n+'-rotate">'+r.i18n("rotate")+"</label>"),F=e("input",I).attr("disabled","disabled").change(function(){var t=e("input:checked",I).val();X(),et(!0),tt(!0),nt(!0),"resize"==t?(f.show(),y.hide(),m.hide(),et()):"crop"==t?(y.hide(),f.hide(),m.show(),tt()):"rotate"==t&&(f.hide(),m.hide(),y.show(),nt())}),T=e('<input type="checkbox" checked="checked"/>').change(function(){H=!!T.prop("checked"),Y.fixHeight(),et(!0),et()}),z=e(a).change(function(){var e=parseInt(z.val()),t=parseInt(H?Math.round(e/j):P.val());e>0&&t>0&&(Y.updateView(e,t),P.val(t))}),P=e(a).change(function(){var e=parseInt(P.val()),t=parseInt(H?Math.round(e*j):z.val());t>0&&e>0&&(Y.updateView(t,e),z.val(t))}),A=e(a).change(function(){Z.updateView()}),O=e(a).change(function(){Z.updateView()}),D=e(a).change(function(){Z.updateView()}),M=e(a).change(function(){Z.updateView()}),S=e('<input type="text" size="3" maxlength="3" value="0" />').change(function(){Q.update()}),E=e('<div class="elfinder-resize-rotate-slider"/>').slider({min:0,max:359,value:S.val(),animate:!0,change:function(e,t){t.value!=E.slider("value")&&Q.update(t.value)},slide:function(e,t){Q.update(t.value,!1)}}),j=1,U=1,R=0,q=0,H=!0,L=0,N=0,_=0,V=0,W=0,B=e("<img/>").load(function(){p.remove(),R=B.width(),q=B.height(),j=R/q,Y.updateView(R,q),u.append(B.show()).show(),z.val(R),P.val(q);var t=Math.min(L,N)/Math.sqrt(Math.pow(R,2)+Math.pow(q,2));_=R*t,V=q*t,F.button("enable"),d.find("input,select").removeAttr("disabled").filter(":text").keydown(function(t){var n,i=t.keyCode;return t.stopPropagation(),i>=37&&40>=i||i==e.ui.keyCode.BACKSPACE||i==e.ui.keyCode.DELETE||65==i&&(t.ctrlKey||t.metaKey)||27==i?void 0:(9==i&&(n=e(this).parent()[t.shiftKey?"prev":"next"](".elfinder-resize-row").children(":text"),n.length?n.focus():e(this).parent().parent().find(":text:"+(t.shiftKey?"last":"first")).focus()),13==i?void r.confirm({title:e("input:checked",I).val(),text:"confirmReq",accept:{label:"btnApply",callback:function(){it()}},cancel:{label:"btnCancel",callback:function(){}}}):void(i>=48&&57>=i||i>=96&&105>=i||t.preventDefault()))}).filter(":first").focus(),et(),C.hover(function(){C.toggleClass("ui-state-hover")}).click(X)}).error(function(){p.text("Unable to load image").css("background","transparent")}),K=e("<div/>"),G=e("<img/>"),$=e("<div/>"),J=e("<img/>"),X=function(){z.val(R),P.val(q),Y.updateView(R,q)},Y={update:function(){z.val(Math.round(B.width()/U)),P.val(Math.round(B.height()/U))},updateView:function(e,t){e>L||t>N?e/L>t/N?(U=L/e,B.width(L).height(Math.ceil(t*U))):(U=N/t,B.height(N).width(Math.ceil(e*U))):B.width(e).height(t),U=B.width()/e,k.text("1 : "+(1/U).toFixed(2)),Y.updateHandle()},updateHandle:function(){u.width(B.width()).height(B.height())},fixWidth:function(){var e,t;H&&(t=P.val(),t=Math.round(t*j),Y.updateView(e,t),z.val(e))},fixHeight:function(){var e,t;H&&(e=z.val(),t=Math.round(e/j),Y.updateView(e,t),P.val(t))}},Z={update:function(){D.val(Math.round((h.data("w")||h.width())/U)),M.val(Math.round((h.data("h")||h.height())/U)),A.val(Math.round(((h.data("x")||h.offset().left)-G.offset().left)/U)),O.val(Math.round(((h.data("y")||h.offset().top)-G.offset().top)/U))},updateView:function(){var e=parseInt(A.val())*U+G.offset().left,t=parseInt(O.val())*U+G.offset().top,n=D.val()*U,i=M.val()*U;h.data({x:e,y:t,w:n,h:i}),h.width(Math.round(n)),h.height(Math.round(i)),$.width(h.width()),$.height(h.height()),h.offset({left:Math.round(e),top:Math.round(t)})},resize_update:function(){h.data({w:null,h:null}),Z.update(),$.width(h.width()),$.height(h.height())},drag_update:function(){h.data({x:null,y:null}),Z.update()}},Q={mouseStartAngle:0,imageStartAngle:0,imageBeingRotated:!1,update:function(e,t){"undefined"==typeof e&&(W=e=parseInt(S.val())),"undefined"==typeof t&&(t=!0),!t||r.UA.Opera||r.UA.ltIE8?J.rotate(e):J.animate({rotate:e+"deg"}),e%=360,0>e&&(e+=360),S.val(parseInt(e)),E.slider("value",S.val())},execute:function(e){if(Q.imageBeingRotated){var t=Q.getCenter(J),n=e.pageX-t[0],i=e.pageY-t[1],r=Math.atan2(i,n),a=r-Q.mouseStartAngle+Q.imageStartAngle;return a=Math.round(180*parseFloat(a)/Math.PI),e.shiftKey&&(a=15*Math.round((a+6)/15)),J.rotate(a),a%=360,0>a&&(a+=360),S.val(a),E.slider("value",S.val()),!1}},start:function(t){Q.imageBeingRotated=!0;var n=Q.getCenter(J),i=t.pageX-n[0],r=t.pageY-n[1];return Q.mouseStartAngle=Math.atan2(r,i),Q.imageStartAngle=parseFloat(J.rotate())*Math.PI/180,e(document).mousemove(Q.execute),!1},stop:function(){return Q.imageBeingRotated?(e(document).unbind("mousemove",Q.execute),setTimeout(function(){Q.imageBeingRotated=!1},10),!1):void 0},getCenter:function(){var e=J.rotate();J.rotate(0);var t=J.offset(),n=t.left+J.width()/2,i=t.top+J.height()/2;return J.rotate(e),Array(n,i)}},et=function(t){e.fn.resizable&&(t?(u.filter(":ui-resizable").resizable("destroy"),u.hide()):(u.show(),u.resizable({alsoResize:B,aspectRatio:H,resize:Y.update,stop:Y.fixHeight})))},tt=function(t){e.fn.draggable&&e.fn.resizable&&(t?(h.filter(":ui-resizable").resizable("destroy"),h.filter(":ui-draggable").draggable("destroy"),K.hide()):(G.width(B.width()).height(B.height()),$.width(B.width()).height(B.height()),h.width(G.width()).height(G.height()).offset(G.offset()).resizable({containment:K,resize:Z.resize_update,handles:"all"}).draggable({handle:$,containment:G,drag:Z.drag_update}),K.show().width(B.width()).height(B.height()),Z.update()))},nt=function(t){e.fn.draggable&&e.fn.resizable&&(t?J.hide():J.show().width(_).height(V).css("margin-top",(N-V)/2+"px").css("margin-left",(L-_)/2+"px"))},it=function(){var n,a,s,l,d,c=e("input:checked",I).val();if("resize"==c)n=parseInt(z.val())||0,a=parseInt(P.val())||0;else if("crop"==c)n=parseInt(D.val())||0,a=parseInt(M.val())||0,s=parseInt(A.val())||0,l=parseInt(O.val())||0;else if("rotate"==c){if(n=R,a=q,d=parseInt(S.val())||0,0>d||d>360)return r.error("Invalid rotate degree");if(0==d||360==d)return r.error("Image dose not rotated")}if("rotate"!=c){if(0>=n||0>=a)return r.error("Invalid image size");if(n==R&&a==q)return r.error("Image size not changed")}i.elfinderdialog("close"),r.request({data:{cmd:"resize",target:t.hash,width:n,height:a,x:s,y:l,degree:d,mode:c},notify:{type:"resize",cnt:1}}).fail(function(e){o.reject(e)}).done(function(){o.resolve()})},rt={},at="elfinder-resize-handle-hline",ot="elfinder-resize-handle-vline",st="elfinder-resize-handle-point",lt=r.url(t.hash);J.mousedown(Q.start),e(document).mouseup(Q.stop),f.append(e(s).append(e(l).text(r.i18n("width"))).append(z).append(C)).append(e(s).append(e(l).text(r.i18n("height"))).append(P)).append(e(s).append(e("<label/>").text(r.i18n("aspectRatio")).prepend(T))).append(e(s).append(r.i18n("scale")+" ").append(k)),m.append(e(s).append(e(l).text("X")).append(A)).append(e(s).append(e(l).text("Y")).append(O)).append(e(s).append(e(l).text(r.i18n("width"))).append(D)).append(e(s).append(e(l).text(r.i18n("height"))).append(M)),y.append(e(s).append(e(l).text(r.i18n("rotate"))).append(e('<div style="float:left; width: 130px;">').append(e('<div style="float:left;">').append(S).append(e("<span/>").text(r.i18n("degree")))).append(e(g).append(w).append(e(b)).append(x))).append(E)),i.append(I),d.append(e(s)).append(f).append(m.hide()).append(y.hide()).find("input,select").attr("disabled","disabled"),u.append('<div class="'+at+" "+at+'-top"/>').append('<div class="'+at+" "+at+'-bottom"/>').append('<div class="'+ot+" "+ot+'-left"/>').append('<div class="'+ot+" "+ot+'-right"/>').append('<div class="'+st+" "+st+'-e"/>').append('<div class="'+st+" "+st+'-se"/>').append('<div class="'+st+" "+st+'-s"/>'),c.append(p).append(u.hide()).append(B.hide()),h.css("position","absolute").append('<div class="'+at+" "+at+'-top"/>').append('<div class="'+at+" "+at+'-bottom"/>').append('<div class="'+ot+" "+ot+'-left"/>').append('<div class="'+ot+" "+ot+'-right"/>').append('<div class="'+st+" "+st+'-n"/>').append('<div class="'+st+" "+st+'-e"/>').append('<div class="'+st+" "+st+'-s"/>').append('<div class="'+st+" "+st+'-w"/>').append('<div class="'+st+" "+st+'-ne"/>').append('<div class="'+st+" "+st+'-se"/>').append('<div class="'+st+" "+st+'-sw"/>').append('<div class="'+st+" "+st+'-nw"/>'),c.append(K.css("position","absolute").hide().append(G).append(h.append($))),c.append(J.hide()),c.css("overflow","hidden"),i.append(c).append(d),rt[r.i18n("btnApply")]=it,rt[r.i18n("btnCancel")]=function(){i.elfinderdialog("close")},r.dialog(i,{title:t.name,width:650,resizable:!1,destroyOnClose:!0,buttons:rt,open:function(){c.zIndex(1+e(this).parent().zIndex())}}).attr("id",n),r.UA.ltIE8&&e(".elfinder-dialog").css("filter",""),C.css("left",z.position().left+z.width()+12),$.css({opacity:.2,"background-color":"#fff",position:"absolute"}),h.css("cursor","move"),h.find(".elfinder-resize-handle-point").css({"background-color":"#fff",opacity:.5,"border-color":"#000"}),J.css("cursor","pointer"),I.buttonset(),L=c.width()-(u.outerWidth()-u.width()),N=c.height()-(u.outerHeight()-u.height()),B.attr("src",lt+(-1===lt.indexOf("?")?"?":"&")+"_="+Math.random()),G.attr("src",B.attr("src")),J.attr("src",B.attr("src"))};return a.length&&-1!==a[0].mime.indexOf("image/")?(n="resize-"+r.namespace+"-"+a[0].hash,i=r.getUI().find("#"+n),i.length?(i.elfinderdialog("toTop"),o.resolve()):(s(a[0],n),o)):o.reject()}},function(e){var t=function(e,t){var n=0;for(n in t)if("undefined"!=typeof e[t[n]])return t[n];return e[t[n]]="",t[n]};if(e.cssHooks.rotate={get:function(t){return e(t).rotate()},set:function(t,n){return e(t).rotate(n),n}},e.cssHooks.transform={get:function(e){var n=t(e.style,["WebkitTransform","MozTransform","OTransform","msTransform","transform"]);return e.style[n]},set:function(e,n){var i=t(e.style,["WebkitTransform","MozTransform","OTransform","msTransform","transform"]);return e.style[i]=n,n}},e.fn.rotate=function(e){if("undefined"==typeof e){if(window.opera){var t=this.css("transform").match(/rotate\((.*?)\)/);return t&&t[1]?Math.round(180*parseFloat(t[1])/Math.PI):0}var t=this.css("transform").match(/rotate\((.*?)\)/);return t&&t[1]?parseInt(t[1]):0}return this.css("transform",this.css("transform").replace(/none|rotate\(.*?\)/,"")+"rotate("+parseInt(e)+"deg)"),this},e.fx.step.rotate=function(t){0==t.state&&(t.start=e(t.elem).rotate(),t.now=t.start),e(t.elem).rotate(t.now)},"undefined"==typeof window.addEventListener&&"undefined"==typeof document.getElementsByClassName){var n=function(e){for(var t=e,n=t.offsetLeft,i=t.offsetTop;t.offsetParent&&(t=t.offsetParent,t==document.body||"static"==t.currentStyle.position);)t!=document.body&&t!=document.documentElement&&(n-=t.scrollLeft,i-=t.scrollTop),n+=t.offsetLeft,i+=t.offsetTop;return{x:n,y:i}},i=function(e){if("static"==e.currentStyle.position){var t=n(e);e.style.position="absolute",e.style.left=t.x+"px",e.style.top=t.y+"px"}},r=function(e,t){var n,r=1,a=1,o=1,s=1;if("undefined"!=typeof e.style.msTransform)return!0;i(e),n=t.match(/rotate\((.*?)\)/);var l=n&&n[1]?parseInt(n[1]):0;l%=360,0>l&&(l=360+l);var d=l*Math.PI/180,c=Math.cos(d),p=Math.sin(d);r*=c,a*=-p,o*=p,s*=c,e.style.filter=(e.style.filter||"").replace(/progid:DXImageTransform\.Microsoft\.Matrix\([^)]*\)/,"")+("progid:DXImageTransform.Microsoft.Matrix(M11="+r+",M12="+a+",M21="+o+",M22="+s+",FilterType='bilinear',sizingMethod='auto expand')");var u=parseInt(e.style.width||e.width||0),h=parseInt(e.style.height||e.height||0),d=l*Math.PI/180,f=Math.abs(Math.cos(d)),m=Math.abs(Math.sin(d)),g=(u-(u*f+h*m))/2,v=(h-(u*m+h*f))/2;return e.style.marginLeft=Math.floor(g)+"px",e.style.marginTop=Math.floor(v)+"px",!0},a=e.cssHooks.transform.set;e.cssHooks.transform.set=function(e,t){return a.apply(this,[e,t]),r(e,t),t}}}(jQuery),elFinder.prototype.commands.rm=function(){this.shortcuts=[{pattern:"delete ctrl+backspace"}],this.getstate=function(t){var n=this.fm;return t=t||n.selected(),!this._disabled&&t.length&&e.map(t,function(e){var t=n.file(e);return t&&t.phash&&!t.locked?e:null}).length==t.length?0:-1},this.exec=function(t){var n=this,i=this.fm,r=e.Deferred().fail(function(e){e&&i.error(e)}),a=this.files(t),o=a.length,s=i.cwd().hash,l=!1;return!o||this._disabled?r.reject():(e.each(a,function(e,t){return t.phash?t.locked?!r.reject(["errLocked",t.name]):void(t.hash==s&&(l=i.root(t.hash))):!r.reject(["errRm",t.name,"errPerm"])}),"pending"==r.state()&&(a=this.hashes(t),i.confirm({title:n.title,text:"confirmRm",accept:{label:"btnRm",callback:function(){i.lockfiles({files:a}),i.request({data:{cmd:"rm",targets:a},notify:{type:"rm",cnt:o},preventFail:!0}).fail(function(e){r.reject(e)}).done(function(e){r.done(e),l&&i.exec("open",l)}).always(function(){i.unlockfiles({files:a})})}},cancel:{label:"btnCancel",callback:function(){r.reject()}}})),r)}},elFinder.prototype.commands.search=function(){this.title="Find files",this.options={ui:"searchbutton"},this.alwaysEnabled=!0,this.updateOnSelect=!1,this.getstate=function(){return 0},this.exec=function(t){var n=this.fm;return"string"==typeof t&&t?(n.trigger("searchstart",{query:t}),n.request({data:{cmd:"search",q:t},notify:{type:"search",cnt:1,hideCnt:!0}})):(n.getUI("toolbar").find("."+n.res("class","searchbtn")+" :text").focus(),e.Deferred().reject())}},elFinder.prototype.commands.sort=function(){this.options={ui:"sortbutton"},this.getstate=function(){return 0},this.exec=function(t,n){var i=this.fm,n=e.extend({type:i.sortType,order:i.sortOrder,stick:i.sortStickFolders},n);return this.fm.setSort(n.type,n.order,n.stick),e.Deferred().resolve()}},elFinder.prototype.commands.up=function(){this.alwaysEnabled=!0,this.updateOnSelect=!1,this.shortcuts=[{pattern:"ctrl+up"}],this.getstate=function(){return this.fm.cwd().phash?0:-1},this.exec=function(){return this.fm.cwd().phash?this.fm.exec("open",this.fm.cwd().phash):e.Deferred().reject()}},elFinder.prototype.commands.upload=function(){var t=this.fm.res("class","hover");this.disableOnSearch=!0,this.updateOnSelect=!1,this.shortcuts=[{pattern:"ctrl+u"}],this.getstate=function(){return!this._disabled&&this.fm.cwd().write?0:-1},this.exec=function(n){var i,r,a,o,s,l=this.fm,d=function(e){r.elfinderdialog("close"),l.upload(e).fail(function(e){i.reject(e)}).done(function(e){i.resolve(e)})};return this.disabled()?e.Deferred().reject():n&&(n.input||n.files)?l.upload(n):(i=e.Deferred(),a=e('<input type="file" multiple="true"/>').change(function(){d({input:a[0]})}),o=e('<div class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">'+l.i18n("selectForUpload")+"</span></div>").append(e("<form/>").append(a)).hover(function(){o.toggleClass(t)}),r=e('<div class="elfinder-upload-dialog-wrapper"/>').append(o),l.dragUpload&&(s=e('<div class="ui-corner-all elfinder-upload-dropbox">'+l.i18n("dropFiles")+"</div>").prependTo(r).after('<div class="elfinder-upload-dialog-or">'+l.i18n("or")+"</div>")[0],s.addEventListener("dragenter",function(n){n.stopPropagation(),n.preventDefault(),e(s).addClass(t)},!1),s.addEventListener("dragleave",function(n){n.stopPropagation(),n.preventDefault(),e(s).removeClass(t)},!1),s.addEventListener("dragover",function(e){e.stopPropagation(),e.preventDefault()},!1),s.addEventListener("drop",function(e){e.stopPropagation(),e.preventDefault(),d({files:e.dataTransfer.files})},!1)),l.dialog(r,{title:this.title,modal:!0,resizable:!1,destroyOnClose:!0}),i)}},elFinder.prototype.commands.view=function(){this.value=this.fm.viewType,this.alwaysEnabled=!0,this.updateOnSelect=!1,this.options={ui:"viewbutton"},this.getstate=function(){return 0},this.exec=function(){var e=this.fm.storage("view","list"==this.value?"icons":"list");this.fm.viewchange(),this.update(void 0,e)}}}(jQuery);
!function(e){"undefined"!=typeof exports?e(exports):(window.hljs=e({}),"function"==typeof define&&define.amd&&define([],function(){return window.hljs}))}(function(e){function n(e){return e.replace(/&/gm,"&amp;").replace(/</gm,"&lt;").replace(/>/gm,"&gt;")}function t(e){return e.nodeName.toLowerCase()}function r(e,n){var t=e&&e.exec(n);return t&&0==t.index}function a(e){var n=(e.className+" "+(e.parentNode?e.parentNode.className:"")).split(/\s+/);return n=n.map(function(e){return e.replace(/^lang(uage)?-/,"")}),n.filter(function(e){return N(e)||/no(-?)highlight/.test(e)})[0]}function o(e,n){var t={};for(var r in e)t[r]=e[r];if(n)for(var r in n)t[r]=n[r];return t}function i(e){var n=[];return function r(e,a){for(var o=e.firstChild;o;o=o.nextSibling)3==o.nodeType?a+=o.nodeValue.length:1==o.nodeType&&(n.push({event:"start",offset:a,node:o}),a=r(o,a),t(o).match(/br|hr|img|input/)||n.push({event:"stop",offset:a,node:o}));return a}(e,0),n}function c(e,r,a){function o(){return e.length&&r.length?e[0].offset!=r[0].offset?e[0].offset<r[0].offset?e:r:"start"==r[0].event?e:r:e.length?e:r}function i(e){function r(e){return" "+e.nodeName+'="'+n(e.value)+'"'}l+="<"+t(e)+Array.prototype.map.call(e.attributes,r).join("")+">"}function c(e){l+="</"+t(e)+">"}function u(e){("start"==e.event?i:c)(e.node)}for(var s=0,l="",f=[];e.length||r.length;){var g=o();if(l+=n(a.substr(s,g[0].offset-s)),s=g[0].offset,g==e){f.reverse().forEach(c);do u(g.splice(0,1)[0]),g=o();while(g==e&&g.length&&g[0].offset==s);f.reverse().forEach(i)}else"start"==g[0].event?f.push(g[0].node):f.pop(),u(g.splice(0,1)[0])}return l+n(a.substr(s))}function u(e){function n(e){return e&&e.source||e}function t(t,r){return RegExp(n(t),"m"+(e.cI?"i":"")+(r?"g":""))}function r(a,i){if(!a.compiled){if(a.compiled=!0,a.k=a.k||a.bK,a.k){var c={},u=function(n,t){e.cI&&(t=t.toLowerCase()),t.split(" ").forEach(function(e){var t=e.split("|");c[t[0]]=[n,t[1]?Number(t[1]):1]})};"string"==typeof a.k?u("keyword",a.k):Object.keys(a.k).forEach(function(e){u(e,a.k[e])}),a.k=c}a.lR=t(a.l||/\b[A-Za-z0-9_]+\b/,!0),i&&(a.bK&&(a.b="\\b("+a.bK.split(" ").join("|")+")\\b"),a.b||(a.b=/\B|\b/),a.bR=t(a.b),a.e||a.eW||(a.e=/\B|\b/),a.e&&(a.eR=t(a.e)),a.tE=n(a.e)||"",a.eW&&i.tE&&(a.tE+=(a.e?"|":"")+i.tE)),a.i&&(a.iR=t(a.i)),void 0===a.r&&(a.r=1),a.c||(a.c=[]);var s=[];a.c.forEach(function(e){e.v?e.v.forEach(function(n){s.push(o(e,n))}):s.push("self"==e?a:e)}),a.c=s,a.c.forEach(function(e){r(e,a)}),a.starts&&r(a.starts,i);var l=a.c.map(function(e){return e.bK?"\\.?("+e.b+")\\.?":e.b}).concat([a.tE,a.i]).map(n).filter(Boolean);a.t=l.length?t(l.join("|"),!0):{exec:function(){return null}}}}r(e)}function s(e,t,a,o){function i(e,n){for(var t=0;t<n.c.length;t++)if(r(n.c[t].bR,e))return n.c[t]}function c(e,n){return r(e.eR,n)?e:e.eW?c(e.parent,n):void 0}function f(e,n){return!a&&r(n.iR,e)}function g(e,n){var t=x.cI?n[0].toLowerCase():n[0];return e.k.hasOwnProperty(t)&&e.k[t]}function p(e,n,t,r){var a=r?"":E.classPrefix,o='<span class="'+a,i=t?"":"</span>";return o+=e+'">',o+n+i}function d(){if(!w.k)return n(y);var e="",t=0;w.lR.lastIndex=0;for(var r=w.lR.exec(y);r;){e+=n(y.substr(t,r.index-t));var a=g(w,r);a?(B+=a[1],e+=p(a[0],n(r[0]))):e+=n(r[0]),t=w.lR.lastIndex,r=w.lR.exec(y)}return e+n(y.substr(t))}function h(){if(w.sL&&!R[w.sL])return n(y);var e=w.sL?s(w.sL,y,!0,L[w.sL]):l(y);return w.r>0&&(B+=e.r),"continuous"==w.subLanguageMode&&(L[w.sL]=e.top),p(e.language,e.value,!1,!0)}function v(){return void 0!==w.sL?h():d()}function b(e,t){var r=e.cN?p(e.cN,"",!0):"";e.rB?(M+=r,y=""):e.eB?(M+=n(t)+r,y=""):(M+=r,y=t),w=Object.create(e,{parent:{value:w}})}function m(e,t){if(y+=e,void 0===t)return M+=v(),0;var r=i(t,w);if(r)return M+=v(),b(r,t),r.rB?0:t.length;var a=c(w,t);if(a){var o=w;o.rE||o.eE||(y+=t),M+=v();do w.cN&&(M+="</span>"),B+=w.r,w=w.parent;while(w!=a.parent);return o.eE&&(M+=n(t)),y="",a.starts&&b(a.starts,""),o.rE?0:t.length}if(f(t,w))throw new Error('Illegal lexeme "'+t+'" for mode "'+(w.cN||"<unnamed>")+'"');return y+=t,t.length||1}var x=N(e);if(!x)throw new Error('Unknown language: "'+e+'"');u(x);for(var w=o||x,L={},M="",k=w;k!=x;k=k.parent)k.cN&&(M=p(k.cN,"",!0)+M);var y="",B=0;try{for(var C,j,I=0;;){if(w.t.lastIndex=I,C=w.t.exec(t),!C)break;j=m(t.substr(I,C.index-I),C[0]),I=C.index+j}m(t.substr(I));for(var k=w;k.parent;k=k.parent)k.cN&&(M+="</span>");return{r:B,value:M,language:e,top:w}}catch(A){if(-1!=A.message.indexOf("Illegal"))return{r:0,value:n(t)};throw A}}function l(e,t){t=t||E.languages||Object.keys(R);var r={r:0,value:n(e)},a=r;return t.forEach(function(n){if(N(n)){var t=s(n,e,!1);t.language=n,t.r>a.r&&(a=t),t.r>r.r&&(a=r,r=t)}}),a.language&&(r.second_best=a),r}function f(e){return E.tabReplace&&(e=e.replace(/^((<[^>]+>|\t)+)/gm,function(e,n){return n.replace(/\t/g,E.tabReplace)})),E.useBR&&(e=e.replace(/\n/g,"<br>")),e}function g(e,n,t){var r=n?x[n]:t,a=[e.trim()];return e.match(/(\s|^)hljs(\s|$)/)||a.push("hljs"),r&&a.push(r),a.join(" ").trim()}function p(e){var n=a(e);if(!/no(-?)highlight/.test(n)){var t;E.useBR?(t=document.createElementNS("http://www.w3.org/1999/xhtml","div"),t.innerHTML=e.innerHTML.replace(/\n/g,"").replace(/<br[ \/]*>/g,"\n")):t=e;var r=t.textContent,o=n?s(n,r,!0):l(r),u=i(t);if(u.length){var p=document.createElementNS("http://www.w3.org/1999/xhtml","div");p.innerHTML=o.value,o.value=c(u,i(p),r)}o.value=f(o.value),e.innerHTML=o.value,e.className=g(e.className,n,o.language),e.result={language:o.language,re:o.r},o.second_best&&(e.second_best={language:o.second_best.language,re:o.second_best.r})}}function d(e){E=o(E,e)}function h(){if(!h.called){h.called=!0;var e=document.querySelectorAll("pre code");Array.prototype.forEach.call(e,p)}}function v(){addEventListener("DOMContentLoaded",h,!1),addEventListener("load",h,!1)}function b(n,t){var r=R[n]=t(e);r.aliases&&r.aliases.forEach(function(e){x[e]=n})}function m(){return Object.keys(R)}function N(e){return R[e]||R[x[e]]}var E={classPrefix:"hljs-",tabReplace:null,useBR:!1,languages:void 0},R={},x={};return e.highlight=s,e.highlightAuto=l,e.fixMarkup=f,e.highlightBlock=p,e.configure=d,e.initHighlighting=h,e.initHighlightingOnLoad=v,e.registerLanguage=b,e.listLanguages=m,e.getLanguage=N,e.inherit=o,e.IR="[a-zA-Z][a-zA-Z0-9_]*",e.UIR="[a-zA-Z_][a-zA-Z0-9_]*",e.NR="\\b\\d+(\\.\\d+)?",e.CNR="(\\b0[xX][a-fA-F0-9]+|(\\b\\d+(\\.\\d*)?|\\.\\d+)([eE][-+]?\\d+)?)",e.BNR="\\b(0b[01]+)",e.RSR="!|!=|!==|%|%=|&|&&|&=|\\*|\\*=|\\+|\\+=|,|-|-=|/=|/|:|;|<<|<<=|<=|<|===|==|=|>>>=|>>=|>=|>>>|>>|>|\\?|\\[|\\{|\\(|\\^|\\^=|\\||\\|=|\\|\\||~",e.BE={b:"\\\\[\\s\\S]",r:0},e.ASM={cN:"string",b:"'",e:"'",i:"\\n",c:[e.BE]},e.QSM={cN:"string",b:'"',e:'"',i:"\\n",c:[e.BE]},e.PWM={b:/\b(a|an|the|are|I|I'm|isn't|don't|doesn't|won't|but|just|should|pretty|simply|enough|gonna|going|wtf|so|such)\b/},e.CLCM={cN:"comment",b:"//",e:"$",c:[e.PWM]},e.CBCM={cN:"comment",b:"/\\*",e:"\\*/",c:[e.PWM]},e.HCM={cN:"comment",b:"#",e:"$",c:[e.PWM]},e.NM={cN:"number",b:e.NR,r:0},e.CNM={cN:"number",b:e.CNR,r:0},e.BNM={cN:"number",b:e.BNR,r:0},e.CSSNM={cN:"number",b:e.NR+"(%|em|ex|ch|rem|vw|vh|vmin|vmax|cm|mm|in|pt|pc|px|deg|grad|rad|turn|s|ms|Hz|kHz|dpi|dpcm|dppx)?",r:0},e.RM={cN:"regexp",b:/\//,e:/\/[gimuy]*/,i:/\n/,c:[e.BE,{b:/\[/,e:/\]/,r:0,c:[e.BE]}]},e.TM={cN:"title",b:e.IR,r:0},e.UTM={cN:"title",b:e.UIR,r:0},e});hljs.registerLanguage("coffeescript",function(e){var c={keyword:"in if for while finally new do return else break catch instanceof throw try this switch continue typeof delete debugger super then unless until loop of by when and or is isnt not",literal:"true false null undefined yes no on off",reserved:"case default function var void with const let enum export import native __hasProp __extends __slice __bind __indexOf",built_in:"npm require console print module global window document"},n="[A-Za-z$_][0-9A-Za-z$_]*",t={cN:"subst",b:/#\{/,e:/}/,k:c},r=[e.BNM,e.inherit(e.CNM,{starts:{e:"(\\s*/)?",r:0}}),{cN:"string",v:[{b:/'''/,e:/'''/,c:[e.BE]},{b:/'/,e:/'/,c:[e.BE]},{b:/"""/,e:/"""/,c:[e.BE,t]},{b:/"/,e:/"/,c:[e.BE,t]}]},{cN:"regexp",v:[{b:"///",e:"///",c:[t,e.HCM]},{b:"//[gim]*",r:0},{b:/\/(?![ *])(\\\/|.)*?\/[gim]*(?=\W|$)/}]},{cN:"property",b:"@"+n},{b:"`",e:"`",eB:!0,eE:!0,sL:"javascript"}];t.c=r;var i=e.inherit(e.TM,{b:n}),s="(\\(.*\\))?\\s*\\B[-=]>",o={cN:"params",b:"\\([^\\(]",rB:!0,c:[{b:/\(/,e:/\)/,k:c,c:["self"].concat(r)}]};return{aliases:["coffee","cson","iced"],k:c,i:/\/\*/,c:r.concat([{cN:"comment",b:"###",e:"###",c:[e.PWM]},e.HCM,{cN:"function",b:"^\\s*"+n+"\\s*=\\s*"+s,e:"[-=]>",rB:!0,c:[i,o]},{b:/[:\(,=]\s*/,r:0,c:[{cN:"function",b:s,e:"[-=]>",rB:!0,c:[o]}]},{cN:"class",bK:"class",e:"$",i:/[:="\[\]]/,c:[{bK:"extends",eW:!0,i:/[:="\[\]]/,c:[i]},i]},{cN:"attribute",b:n+":",e:":",rB:!0,rE:!0,r:0}])}});hljs.registerLanguage("apache",function(e){var r={cN:"number",b:"[\\$%]\\d+"};return{aliases:["apacheconf"],cI:!0,c:[e.HCM,{cN:"tag",b:"</?",e:">"},{cN:"keyword",b:/\w+/,r:0,k:{common:"order deny allow setenv rewriterule rewriteengine rewritecond documentroot sethandler errordocument loadmodule options header listen serverroot servername"},starts:{e:/$/,r:0,k:{literal:"on off all"},c:[{cN:"sqbracket",b:"\\s\\[",e:"\\]$"},{cN:"cbracket",b:"[\\$%]\\{",e:"\\}",c:["self",r]},r,e.QSM]}}],i:/\S/}});hljs.registerLanguage("http",function(){return{i:"\\S",c:[{cN:"status",b:"^HTTP/[0-9\\.]+",e:"$",c:[{cN:"number",b:"\\b\\d{3}\\b"}]},{cN:"request",b:"^[A-Z]+ (.*?) HTTP/[0-9\\.]+$",rB:!0,e:"$",c:[{cN:"string",b:" ",e:" ",eB:!0,eE:!0}]},{cN:"attribute",b:"^\\w",e:": ",eE:!0,i:"\\n|\\s|=",starts:{cN:"string",e:"$"}},{b:"\\n\\n",starts:{sL:"",eW:!0}}]}});hljs.registerLanguage("cs",function(e){var r="abstract as base bool break byte case catch char checked const continue decimal default delegate do double else enum event explicit extern false finally fixed float for foreach goto if implicit in int interface internal is lock long null object operator out override params private protected public readonly ref sbyte sealed short sizeof stackalloc static string struct switch this true try typeof uint ulong unchecked unsafe ushort using virtual volatile void while async protected public private internal ascending descending from get group into join let orderby partial select set value var where yield",t=e.IR+"(<"+e.IR+">)?";return{aliases:["csharp"],k:r,i:/::/,c:[{cN:"comment",b:"///",e:"$",rB:!0,c:[{cN:"xmlDocTag",v:[{b:"///",r:0},{b:"<!--|-->"},{b:"</?",e:">"}]}]},e.CLCM,e.CBCM,{cN:"preprocessor",b:"#",e:"$",k:"if else elif endif define undef warning error line region endregion pragma checksum"},{cN:"string",b:'@"',e:'"',c:[{b:'""'}]},e.ASM,e.QSM,e.CNM,{bK:"class namespace interface",e:/[{;=]/,i:/[^\s:]/,c:[e.TM,e.CLCM,e.CBCM]},{bK:"new return throw await",r:0},{cN:"function",b:"("+t+"\\s+)+"+e.IR+"\\s*\\(",rB:!0,e:/[{;=]/,eE:!0,k:r,c:[{b:e.IR+"\\s*\\(",rB:!0,c:[e.TM],r:0},{cN:"params",b:/\(/,e:/\)/,k:r,r:0,c:[e.ASM,e.QSM,e.CNM,e.CBCM]},e.CLCM,e.CBCM]}]}});hljs.registerLanguage("java",function(e){var a=e.UIR+"(<"+e.UIR+">)?",t="false synchronized int abstract float private char boolean static null if const for true while long strictfp finally protected import native final void enum else break transient catch instanceof byte super volatile case assert short package default double public try this switch continue throws protected public private",c="(\\b(0b[01_]+)|\\b0[xX][a-fA-F0-9_]+|(\\b[\\d_]+(\\.[\\d_]*)?|\\.[\\d_]+)([eE][-+]?\\d+)?)[lLfF]?",r={cN:"number",b:c,r:0};return{aliases:["jsp"],k:t,i:/<\//,c:[{cN:"javadoc",b:"/\\*\\*",e:"\\*/",r:0,c:[{cN:"javadoctag",b:"(^|\\s)@[A-Za-z]+"}]},e.CLCM,e.CBCM,e.ASM,e.QSM,{cN:"class",bK:"class interface",e:/[{;=]/,eE:!0,k:"class interface",i:/[:"\[\]]/,c:[{bK:"extends implements"},e.UTM]},{bK:"new throw return",r:0},{cN:"function",b:"("+a+"\\s+)+"+e.UIR+"\\s*\\(",rB:!0,e:/[{;=]/,eE:!0,k:t,c:[{b:e.UIR+"\\s*\\(",rB:!0,r:0,c:[e.UTM]},{cN:"params",b:/\(/,e:/\)/,k:t,r:0,c:[e.ASM,e.QSM,e.CNM,e.CBCM]},e.CLCM,e.CBCM]},r,{cN:"annotation",b:"@[A-Za-z]+"}]}});hljs.registerLanguage("sql",function(e){var t={cN:"comment",b:"--",e:"$"};return{cI:!0,i:/[<>]/,c:[{cN:"operator",bK:"begin end start commit rollback savepoint lock alter create drop rename call delete do handler insert load replace select truncate update set show pragma grant merge describe use explain help declare prepare execute deallocate savepoint release unlock purge reset change stop analyze cache flush optimize repair kill install uninstall checksum restore check backup",e:/;/,eW:!0,k:{keyword:"abs absolute acos action add adddate addtime aes_decrypt aes_encrypt after aggregate all allocate alter analyze and any are as asc ascii asin assertion at atan atan2 atn2 authorization authors avg backup before begin benchmark between bin binlog bit_and bit_count bit_length bit_or bit_xor both by cache call cascade cascaded case cast catalog ceil ceiling chain change changed char_length character_length charindex charset check checksum checksum_agg choose close coalesce coercibility collate collation collationproperty column columns columns_updated commit compress concat concat_ws concurrent connect connection connection_id consistent constraint constraints continue contributors conv convert convert_tz corresponding cos cot count count_big crc32 create cross cume_dist curdate current current_date current_time current_timestamp current_user cursor curtime data database databases datalength date_add date_format date_sub dateadd datediff datefromparts datename datepart datetime2fromparts datetimeoffsetfromparts day dayname dayofmonth dayofweek dayofyear deallocate declare decode default deferrable deferred degrees delayed delete des_decrypt des_encrypt des_key_file desc describe descriptor diagnostics difference disconnect distinct distinctrow div do domain double drop dumpfile each else elt enclosed encode encrypt end end-exec engine engines eomonth errors escape escaped event eventdata events except exception exec execute exists exp explain export_set extended external extract fast fetch field fields find_in_set first first_value floor flush for force foreign format found found_rows from from_base64 from_days from_unixtime full function get get_format get_lock getdate getutcdate global go goto grant grants greatest group group_concat grouping grouping_id gtid_subset gtid_subtract handler having help hex high_priority hosts hour ident_current ident_incr ident_seed identified identity if ifnull ignore iif ilike immediate in index indicator inet6_aton inet6_ntoa inet_aton inet_ntoa infile initially inner innodb input insert install instr intersect into is is_free_lock is_ipv4 is_ipv4_compat is_ipv4_mapped is_not is_not_null is_used_lock isdate isnull isolation join key kill language last last_day last_insert_id last_value lcase lead leading least leaves left len lenght level like limit lines ln load load_file local localtime localtimestamp locate lock log log10 log2 logfile logs low_priority lower lpad ltrim make_set makedate maketime master master_pos_wait match matched max md5 medium merge microsecond mid min minute mod mode module month monthname mutex name_const names national natural nchar next no no_write_to_binlog not now nullif nvarchar oct octet_length of old_password on only open optimize option optionally or ord order outer outfile output pad parse partial partition password patindex percent_rank percentile_cont percentile_disc period_add period_diff pi plugin position pow power pragma precision prepare preserve primary prior privileges procedure procedure_analyze processlist profile profiles public publishingservername purge quarter query quick quote quotename radians rand read references regexp relative relaylog release release_lock rename repair repeat replace replicate reset restore restrict return returns reverse revoke right rlike rollback rollup round row row_count rows rpad rtrim savepoint schema scroll sec_to_time second section select serializable server session session_user set sha sha1 sha2 share show sign sin size slave sleep smalldatetimefromparts snapshot some soname soundex sounds_like space sql sql_big_result sql_buffer_result sql_cache sql_calc_found_rows sql_no_cache sql_small_result sql_variant_property sqlstate sqrt square start starting status std stddev stddev_pop stddev_samp stdev stdevp stop str str_to_date straight_join strcmp string stuff subdate substr substring subtime subtring_index sum switchoffset sysdate sysdatetime sysdatetimeoffset system_user sysutcdatetime table tables tablespace tan temporary terminated tertiary_weights then time time_format time_to_sec timediff timefromparts timestamp timestampadd timestampdiff timezone_hour timezone_minute to to_base64 to_days to_seconds todatetimeoffset trailing transaction translation trigger trigger_nestlevel triggers trim truncate try_cast try_convert try_parse ucase uncompress uncompressed_length unhex unicode uninstall union unique unix_timestamp unknown unlock update upgrade upped upper usage use user user_resources using utc_date utc_time utc_timestamp uuid uuid_short validate_password_strength value values var var_pop var_samp variables variance varp version view warnings week weekday weekofyear weight_string when whenever where with work write xml xor year yearweek zon",literal:"true false null",built_in:"array bigint binary bit blob boolean char character date dec decimal float int integer interval number numeric real serial smallint varchar varying int8 serial8 text"},c:[{cN:"string",b:"'",e:"'",c:[e.BE,{b:"''"}]},{cN:"string",b:'"',e:'"',c:[e.BE,{b:'""'}]},{cN:"string",b:"`",e:"`",c:[e.BE]},e.CNM,e.CBCM,t]},e.CBCM,t]}});hljs.registerLanguage("nginx",function(e){var r={cN:"variable",v:[{b:/\$\d+/},{b:/\$\{/,e:/}/},{b:"[\\$\\@]"+e.UIR}]},b={eW:!0,l:"[a-z/_]+",k:{built_in:"on off yes no true false none blocked debug info notice warn error crit select break last permanent redirect kqueue rtsig epoll poll /dev/poll"},r:0,i:"=>",c:[e.HCM,{cN:"string",c:[e.BE,r],v:[{b:/"/,e:/"/},{b:/'/,e:/'/}]},{cN:"url",b:"([a-z]+):/",e:"\\s",eW:!0,eE:!0,c:[r]},{cN:"regexp",c:[e.BE,r],v:[{b:"\\s\\^",e:"\\s|{|;",rE:!0},{b:"~\\*?\\s+",e:"\\s|{|;",rE:!0},{b:"\\*(\\.[a-z\\-]+)+"},{b:"([a-z\\-]+\\.)+\\*"}]},{cN:"number",b:"\\b\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}(:\\d{1,5})?\\b"},{cN:"number",b:"\\b\\d+[kKmMgGdshdwy]*\\b",r:0},r]};return{aliases:["nginxconf"],c:[e.HCM,{b:e.UIR+"\\s",e:";|{",rB:!0,c:[{cN:"title",b:e.UIR,starts:b}],r:0}],i:"[^\\s\\}]"}});hljs.registerLanguage("xml",function(){var t="[A-Za-z0-9\\._:-]+",e={b:/<\?(php)?(?!\w)/,e:/\?>/,sL:"php",subLanguageMode:"continuous"},c={eW:!0,i:/</,r:0,c:[e,{cN:"attribute",b:t,r:0},{b:"=",r:0,c:[{cN:"value",c:[e],v:[{b:/"/,e:/"/},{b:/'/,e:/'/},{b:/[^\s\/>]+/}]}]}]};return{aliases:["html","xhtml","rss","atom","xsl","plist"],cI:!0,c:[{cN:"doctype",b:"<!DOCTYPE",e:">",r:10,c:[{b:"\\[",e:"\\]"}]},{cN:"comment",b:"<!--",e:"-->",r:10},{cN:"cdata",b:"<\\!\\[CDATA\\[",e:"\\]\\]>",r:10},{cN:"tag",b:"<style(?=\\s|>|$)",e:">",k:{title:"style"},c:[c],starts:{e:"</style>",rE:!0,sL:"css"}},{cN:"tag",b:"<script(?=\\s|>|$)",e:">",k:{title:"script"},c:[c],starts:{e:"</script>",rE:!0,sL:"javascript"}},e,{cN:"pi",b:/<\?\w+/,e:/\?>/,r:10},{cN:"tag",b:"</?",e:"/?>",c:[{cN:"title",b:/[^ \/><\n\t]+/,r:0},c]}]}});hljs.registerLanguage("diff",function(){return{aliases:["patch"],c:[{cN:"chunk",r:10,v:[{b:/^\@\@ +\-\d+,\d+ +\+\d+,\d+ +\@\@$/},{b:/^\*\*\* +\d+,\d+ +\*\*\*\*$/},{b:/^\-\-\- +\d+,\d+ +\-\-\-\-$/}]},{cN:"header",v:[{b:/Index: /,e:/$/},{b:/=====/,e:/=====$/},{b:/^\-\-\-/,e:/$/},{b:/^\*{3} /,e:/$/},{b:/^\+\+\+/,e:/$/},{b:/\*{5}/,e:/\*{5}$/}]},{cN:"addition",b:"^\\+",e:"$"},{cN:"deletion",b:"^\\-",e:"$"},{cN:"change",b:"^\\!",e:"$"}]}});hljs.registerLanguage("javascript",function(r){return{aliases:["js"],k:{keyword:"in if for while finally var new function do return void else break catch instanceof with throw case default try this switch continue typeof delete let yield const class",literal:"true false null undefined NaN Infinity",built_in:"eval isFinite isNaN parseFloat parseInt decodeURI decodeURIComponent encodeURI encodeURIComponent escape unescape Object Function Boolean Error EvalError InternalError RangeError ReferenceError StopIteration SyntaxError TypeError URIError Number Math Date String RegExp Array Float32Array Float64Array Int16Array Int32Array Int8Array Uint16Array Uint32Array Uint8Array Uint8ClampedArray ArrayBuffer DataView JSON Intl arguments require module console window document"},c:[{cN:"pi",r:10,v:[{b:/^\s*('|")use strict('|")/},{b:/^\s*('|")use asm('|")/}]},r.ASM,r.QSM,r.CLCM,r.CBCM,r.CNM,{b:"("+r.RSR+"|\\b(case|return|throw)\\b)\\s*",k:"return throw case",c:[r.CLCM,r.CBCM,r.RM,{b:/</,e:/>;/,r:0,sL:"xml"}],r:0},{cN:"function",bK:"function",e:/\{/,eE:!0,c:[r.inherit(r.TM,{b:/[A-Za-z$_][0-9A-Za-z$_]*/}),{cN:"params",b:/\(/,e:/\)/,c:[r.CLCM,r.CBCM],i:/["'\(]/}],i:/\[|%/},{b:/\$[(.]/},{b:"\\."+r.IR,r:0}]}});hljs.registerLanguage("bash",function(e){var t={cN:"variable",v:[{b:/\$[\w\d#@][\w\d_]*/},{b:/\$\{(.*?)\}/}]},s={cN:"string",b:/"/,e:/"/,c:[e.BE,t,{cN:"variable",b:/\$\(/,e:/\)/,c:[e.BE]}]},a={cN:"string",b:/'/,e:/'/};return{aliases:["sh","zsh"],l:/-?[a-z\.]+/,k:{keyword:"if then else elif fi for while in do done case esac function",literal:"true false",built_in:"break cd continue eval exec exit export getopts hash pwd readonly return shift test times trap umask unset alias bind builtin caller command declare echo enable help let local logout mapfile printf read readarray source type typeset ulimit unalias set shopt autoload bg bindkey bye cap chdir clone comparguments compcall compctl compdescribe compfiles compgroups compquote comptags comptry compvalues dirs disable disown echotc echoti emulate fc fg float functions getcap getln history integer jobs kill limit log noglob popd print pushd pushln rehash sched setcap setopt stat suspend ttyctl unfunction unhash unlimit unsetopt vared wait whence where which zcompile zformat zftp zle zmodload zparseopts zprof zpty zregexparse zsocket zstyle ztcp",operator:"-ne -eq -lt -gt -f -d -e -s -l -a"},c:[{cN:"shebang",b:/^#![^\n]+sh\s*$/,r:10},{cN:"function",b:/\w[\w\d_]*\s*\(\s*\)\s*\{/,rB:!0,c:[e.inherit(e.TM,{b:/\w[\w\d_]*/})],r:0},e.HCM,e.NM,s,a,t]}});hljs.registerLanguage("objectivec",function(e){var t={keyword:"int float while char export sizeof typedef const struct for union unsigned long volatile static bool mutable if do return goto void enum else break extern asm case short default double register explicit signed typename this switch continue wchar_t inline readonly assign readwrite self @synchronized id typeof nonatomic super unichar IBOutlet IBAction strong weak copy in out inout bycopy byref oneway __strong __weak __block __autoreleasing @private @protected @public @try @property @end @throw @catch @finally @autoreleasepool @synthesize @dynamic @selector @optional @required",literal:"false true FALSE TRUE nil YES NO NULL",built_in:"NSString NSData NSDictionary CGRect CGPoint UIButton UILabel UITextView UIWebView MKMapView NSView NSViewController NSWindow NSWindowController NSSet NSUUID NSIndexSet UISegmentedControl NSObject UITableViewDelegate UITableViewDataSource NSThread UIActivityIndicator UITabbar UIToolBar UIBarButtonItem UIImageView NSAutoreleasePool UITableView BOOL NSInteger CGFloat NSException NSLog NSMutableString NSMutableArray NSMutableDictionary NSURL NSIndexPath CGSize UITableViewCell UIView UIViewController UINavigationBar UINavigationController UITabBarController UIPopoverController UIPopoverControllerDelegate UIImage NSNumber UISearchBar NSFetchedResultsController NSFetchedResultsChangeType UIScrollView UIScrollViewDelegate UIEdgeInsets UIColor UIFont UIApplication NSNotFound NSNotificationCenter NSNotification UILocalNotification NSBundle NSFileManager NSTimeInterval NSDate NSCalendar NSUserDefaults UIWindow NSRange NSArray NSError NSURLRequest NSURLConnection NSURLSession NSURLSessionDataTask NSURLSessionDownloadTask NSURLSessionUploadTask NSURLResponseUIInterfaceOrientation MPMoviePlayerController dispatch_once_t dispatch_queue_t dispatch_sync dispatch_async dispatch_once"},o=/[a-zA-Z@][a-zA-Z0-9_]*/,a="@interface @class @protocol @implementation";return{aliases:["m","mm","objc","obj-c"],k:t,l:o,i:"</",c:[e.CLCM,e.CBCM,e.CNM,e.QSM,{cN:"string",v:[{b:'@"',e:'"',i:"\\n",c:[e.BE]},{b:"'",e:"[^\\\\]'",i:"[^\\\\][^']"}]},{cN:"preprocessor",b:"#",e:"$",c:[{cN:"title",v:[{b:'"',e:'"'},{b:"<",e:">"}]}]},{cN:"class",b:"("+a.split(" ").join("|")+")\\b",e:"({|$)",eE:!0,k:a,l:o,c:[e.UTM]},{cN:"variable",b:"\\."+e.UIR,r:0}]}});hljs.registerLanguage("markdown",function(){return{aliases:["md","mkdown","mkd"],c:[{cN:"header",v:[{b:"^#{1,6}",e:"$"},{b:"^.+?\\n[=-]{2,}$"}]},{b:"<",e:">",sL:"xml",r:0},{cN:"bullet",b:"^([*+-]|(\\d+\\.))\\s+"},{cN:"strong",b:"[*_]{2}.+?[*_]{2}"},{cN:"emphasis",v:[{b:"\\*.+?\\*"},{b:"_.+?_",r:0}]},{cN:"blockquote",b:"^>\\s+",e:"$"},{cN:"code",v:[{b:"`.+?`"},{b:"^( {4}|	)",e:"$",r:0}]},{cN:"horizontal_rule",b:"^[-\\*]{3,}",e:"$"},{b:"\\[.+?\\][\\(\\[].*?[\\)\\]]",rB:!0,c:[{cN:"link_label",b:"\\[",e:"\\]",eB:!0,rE:!0,r:0},{cN:"link_url",b:"\\]\\(",e:"\\)",eB:!0,eE:!0},{cN:"link_reference",b:"\\]\\[",e:"\\]",eB:!0,eE:!0}],r:10},{b:"^\\[.+\\]:",rB:!0,c:[{cN:"link_reference",b:"\\[",e:"\\]:",eB:!0,eE:!0,starts:{cN:"link_url",e:"$"}}]}]}});hljs.registerLanguage("json",function(e){var t={literal:"true false null"},i=[e.QSM,e.CNM],l={cN:"value",e:",",eW:!0,eE:!0,c:i,k:t},c={b:"{",e:"}",c:[{cN:"attribute",b:'\\s*"',e:'"\\s*:\\s*',eB:!0,eE:!0,c:[e.BE],i:"\\n",starts:l}],i:"\\S"},n={b:"\\[",e:"\\]",c:[e.inherit(l,{cN:null})],i:"\\S"};return i.splice(i.length,0,c,n),{c:i,k:t,i:"\\S"}});hljs.registerLanguage("python",function(e){var r={cN:"prompt",b:/^(>>>|\.\.\.) /},b={cN:"string",c:[e.BE],v:[{b:/(u|b)?r?'''/,e:/'''/,c:[r],r:10},{b:/(u|b)?r?"""/,e:/"""/,c:[r],r:10},{b:/(u|r|ur)'/,e:/'/,r:10},{b:/(u|r|ur)"/,e:/"/,r:10},{b:/(b|br)'/,e:/'/},{b:/(b|br)"/,e:/"/},e.ASM,e.QSM]},l={cN:"number",r:0,v:[{b:e.BNR+"[lLjJ]?"},{b:"\\b(0o[0-7]+)[lLjJ]?"},{b:e.CNR+"[lLjJ]?"}]},c={cN:"params",b:/\(/,e:/\)/,c:["self",r,l,b]};return{aliases:["py","gyp"],k:{keyword:"and elif is global as in if from raise for except finally print import pass return exec else break not with class assert yield try while continue del or def lambda nonlocal|10 None True False",built_in:"Ellipsis NotImplemented"},i:/(<\/|->|\?)/,c:[r,l,b,e.HCM,{v:[{cN:"function",bK:"def",r:10},{cN:"class",bK:"class"}],e:/:/,i:/[${=;\n]/,c:[e.UTM,c]},{cN:"decorator",b:/@/,e:/$/},{b:/\b(print|exec)\(/}]}});hljs.registerLanguage("ruby",function(e){var b="[a-zA-Z_]\\w*[!?=]?|[-+~]\\@|<<|>>|=~|===?|<=>|[<>]=?|\\*\\*|[-/+%^&*~`|]|\\[\\]=?",r="and false then defined module in return redo if BEGIN retry end for true self when next until do begin unless END rescue nil else break undef not super class case require yield alias while ensure elsif or include attr_reader attr_writer attr_accessor",c={cN:"yardoctag",b:"@[A-Za-z]+"},a={cN:"value",b:"#<",e:">"},s={cN:"comment",v:[{b:"#",e:"$",c:[c]},{b:"^\\=begin",e:"^\\=end",c:[c],r:10},{b:"^__END__",e:"\\n$"}]},n={cN:"subst",b:"#\\{",e:"}",k:r},t={cN:"string",c:[e.BE,n],v:[{b:/'/,e:/'/},{b:/"/,e:/"/},{b:/`/,e:/`/},{b:"%[qQwWx]?\\(",e:"\\)"},{b:"%[qQwWx]?\\[",e:"\\]"},{b:"%[qQwWx]?{",e:"}"},{b:"%[qQwWx]?<",e:">"},{b:"%[qQwWx]?/",e:"/"},{b:"%[qQwWx]?%",e:"%"},{b:"%[qQwWx]?-",e:"-"},{b:"%[qQwWx]?\\|",e:"\\|"},{b:/\B\?(\\\d{1,3}|\\x[A-Fa-f0-9]{1,2}|\\u[A-Fa-f0-9]{4}|\\?\S)\b/}]},i={cN:"params",b:"\\(",e:"\\)",k:r},d=[t,a,s,{cN:"class",bK:"class module",e:"$|;",i:/=/,c:[e.inherit(e.TM,{b:"[A-Za-z_]\\w*(::\\w+)*(\\?|\\!)?"}),{cN:"inheritance",b:"<\\s*",c:[{cN:"parent",b:"("+e.IR+"::)?"+e.IR}]},s]},{cN:"function",bK:"def",e:" |$|;",r:0,c:[e.inherit(e.TM,{b:b}),i,s]},{cN:"constant",b:"(::)?(\\b[A-Z]\\w*(::)?)+",r:0},{cN:"symbol",b:e.UIR+"(\\!|\\?)?:",r:0},{cN:"symbol",b:":",c:[t,{b:b}],r:0},{cN:"number",b:"(\\b0[0-7_]+)|(\\b0x[0-9a-fA-F_]+)|(\\b[1-9][0-9_]*(\\.[0-9_]+)?)|[0_]\\b",r:0},{cN:"variable",b:"(\\$\\W)|((\\$|\\@\\@?)(\\w+))"},{b:"("+e.RSR+")\\s*",c:[a,s,{cN:"regexp",c:[e.BE,n],i:/\n/,v:[{b:"/",e:"/[a-z]*"},{b:"%r{",e:"}[a-z]*"},{b:"%r\\(",e:"\\)[a-z]*"},{b:"%r!",e:"![a-z]*"},{b:"%r\\[",e:"\\][a-z]*"}]}],r:0}];n.c=d,i.c=d;var l="[>?]>",u="[\\w#]+\\(\\w+\\):\\d+:\\d+>",N="(\\w+-)?\\d+\\.\\d+\\.\\d(p\\d+)?[^>]+>",o=[{b:/^\s*=>/,cN:"status",starts:{e:"$",c:d}},{cN:"prompt",b:"^("+l+"|"+u+"|"+N+")",starts:{e:"$",c:d}}];return{aliases:["rb","gemspec","podspec","thor","irb"],k:r,c:[s].concat(o).concat(d)}});hljs.registerLanguage("cpp",function(t){var i={keyword:"false int float while private char catch export virtual operator sizeof dynamic_cast|10 typedef const_cast|10 const struct for static_cast|10 union namespace unsigned long volatile static protected bool template mutable if public friend do goto auto void enum else break extern using true class asm case typeid short reinterpret_cast|10 default double register explicit signed typename try this switch continue wchar_t inline delete alignof char16_t char32_t constexpr decltype noexcept nullptr static_assert thread_local restrict _Bool complex _Complex _Imaginaryintmax_t uintmax_t int8_t uint8_t int16_t uint16_t int32_t uint32_t  int64_t uint64_tint_least8_t uint_least8_t int_least16_t uint_least16_t int_least32_t uint_least32_tint_least64_t uint_least64_t int_fast8_t uint_fast8_t int_fast16_t uint_fast16_t int_fast32_tuint_fast32_t int_fast64_t uint_fast64_t intptr_t uintptr_t atomic_bool atomic_char atomic_scharatomic_uchar atomic_short atomic_ushort atomic_int atomic_uint atomic_long atomic_ulong atomic_llongatomic_ullong atomic_wchar_t atomic_char16_t atomic_char32_t atomic_intmax_t atomic_uintmax_tatomic_intptr_t atomic_uintptr_t atomic_size_t atomic_ptrdiff_t atomic_int_least8_t atomic_int_least16_tatomic_int_least32_t atomic_int_least64_t atomic_uint_least8_t atomic_uint_least16_t atomic_uint_least32_tatomic_uint_least64_t atomic_int_fast8_t atomic_int_fast16_t atomic_int_fast32_t atomic_int_fast64_tatomic_uint_fast8_t atomic_uint_fast16_t atomic_uint_fast32_t atomic_uint_fast64_t",built_in:"std string cin cout cerr clog stringstream istringstream ostringstream auto_ptr deque list queue stack vector map set bitset multiset multimap unordered_set unordered_map unordered_multiset unordered_multimap array shared_ptr abort abs acos asin atan2 atan calloc ceil cosh cos exit exp fabs floor fmod fprintf fputs free frexp fscanf isalnum isalpha iscntrl isdigit isgraph islower isprint ispunct isspace isupper isxdigit tolower toupper labs ldexp log10 log malloc memchr memcmp memcpy memset modf pow printf putchar puts scanf sinh sin snprintf sprintf sqrt sscanf strcat strchr strcmp strcpy strcspn strlen strncat strncmp strncpy strpbrk strrchr strspn strstr tanh tan vfprintf vprintf vsprintf"};return{aliases:["c","h","c++","h++"],k:i,i:"</",c:[t.CLCM,t.CBCM,t.QSM,{cN:"string",b:"'\\\\?.",e:"'",i:"."},{cN:"number",b:"\\b(\\d+(\\.\\d*)?|\\.\\d+)(u|U|l|L|ul|UL|f|F)"},t.CNM,{cN:"preprocessor",b:"#",e:"$",k:"if else elif endif define undef warning error line pragma",c:[{b:'include\\s*[<"]',e:'[>"]',k:"include",i:"\\n"},t.CLCM]},{cN:"stl_container",b:"\\b(deque|list|queue|stack|vector|map|set|bitset|multiset|multimap|unordered_map|unordered_set|unordered_multiset|unordered_multimap|array)\\s*<",e:">",k:i,c:["self"]},{b:t.IR+"::"},{bK:"new throw return",r:0},{cN:"function",b:"("+t.IR+"\\s+)+"+t.IR+"\\s*\\(",rB:!0,e:/[{;=]/,eE:!0,k:i,c:[{b:t.IR+"\\s*\\(",rB:!0,c:[t.TM],r:0},{cN:"params",b:/\(/,e:/\)/,k:i,r:0,c:[t.CBCM]},t.CLCM,t.CBCM]}]}});hljs.registerLanguage("css",function(e){var c="[a-zA-Z-][a-zA-Z0-9_-]*",a={cN:"function",b:c+"\\(",rB:!0,eE:!0,e:"\\("};return{cI:!0,i:"[=/|']",c:[e.CBCM,{cN:"id",b:"\\#[A-Za-z0-9_-]+"},{cN:"class",b:"\\.[A-Za-z0-9_-]+",r:0},{cN:"attr_selector",b:"\\[",e:"\\]",i:"$"},{cN:"pseudo",b:":(:)?[a-zA-Z0-9\\_\\-\\+\\(\\)\\\"\\']+"},{cN:"at_rule",b:"@(font-face|page)",l:"[a-z-]+",k:"font-face page"},{cN:"at_rule",b:"@",e:"[{;]",c:[{cN:"keyword",b:/\S+/},{b:/\s/,eW:!0,eE:!0,r:0,c:[a,e.ASM,e.QSM,e.CSSNM]}]},{cN:"tag",b:c,r:0},{cN:"rules",b:"{",e:"}",i:"[^\\s]",r:0,c:[e.CBCM,{cN:"rule",b:"[^\\s]",rB:!0,e:";",eW:!0,c:[{cN:"attribute",b:"[A-Z\\_\\.\\-]+",e:":",eE:!0,i:"[^\\s]",starts:{cN:"value",eW:!0,eE:!0,c:[a,e.CSSNM,e.QSM,e.ASM,e.CBCM,{cN:"hexcolor",b:"#[0-9A-Fa-f]+"},{cN:"important",b:"!important"}]}}]}]}]}});hljs.registerLanguage("php",function(e){var c={cN:"variable",b:"\\$+[a-zA-Z_-ÿ][a-zA-Z0-9_-ÿ]*"},i={cN:"preprocessor",b:/<\?(php)?|\?>/},a={cN:"string",c:[e.BE,i],v:[{b:'b"',e:'"'},{b:"b'",e:"'"},e.inherit(e.ASM,{i:null}),e.inherit(e.QSM,{i:null})]},n={v:[e.BNM,e.CNM]};return{aliases:["php3","php4","php5","php6"],cI:!0,k:"and include_once list abstract global private echo interface as static endswitch array null if endwhile or const for endforeach self var while isset public protected exit foreach throw elseif include __FILE__ empty require_once do xor return parent clone use __CLASS__ __LINE__ else break print eval new catch __METHOD__ case exception default die require __FUNCTION__ enddeclare final try switch continue endfor endif declare unset true false trait goto instanceof insteadof __DIR__ __NAMESPACE__ yield finally",c:[e.CLCM,e.HCM,{cN:"comment",b:"/\\*",e:"\\*/",c:[{cN:"phpdoc",b:"\\s@[A-Za-z]+"},i]},{cN:"comment",b:"__halt_compiler.+?;",eW:!0,k:"__halt_compiler",l:e.UIR},{cN:"string",b:"<<<['\"]?\\w+['\"]?$",e:"^\\w+;",c:[e.BE]},i,c,{b:/->+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/},{cN:"function",bK:"function",e:/[;{]/,eE:!0,i:"\\$|\\[|%",c:[e.UTM,{cN:"params",b:"\\(",e:"\\)",c:["self",c,e.CBCM,a,n]}]},{cN:"class",bK:"class interface",e:"{",eE:!0,i:/[:\(\$"]/,c:[{bK:"extends implements"},e.UTM]},{bK:"namespace",e:";",i:/[\.']/,c:[e.UTM]},{bK:"use",e:";",c:[e.UTM]},{b:"=>"},a,n]}});hljs.registerLanguage("perl",function(e){var t="getpwent getservent quotemeta msgrcv scalar kill dbmclose undef lc ma syswrite tr send umask sysopen shmwrite vec qx utime local oct semctl localtime readpipe do return format read sprintf dbmopen pop getpgrp not getpwnam rewinddir qqfileno qw endprotoent wait sethostent bless s|0 opendir continue each sleep endgrent shutdown dump chomp connect getsockname die socketpair close flock exists index shmgetsub for endpwent redo lstat msgctl setpgrp abs exit select print ref gethostbyaddr unshift fcntl syscall goto getnetbyaddr join gmtime symlink semget splice x|0 getpeername recv log setsockopt cos last reverse gethostbyname getgrnam study formline endhostent times chop length gethostent getnetent pack getprotoent getservbyname rand mkdir pos chmod y|0 substr endnetent printf next open msgsnd readdir use unlink getsockopt getpriority rindex wantarray hex system getservbyport endservent int chr untie rmdir prototype tell listen fork shmread ucfirst setprotoent else sysseek link getgrgid shmctl waitpid unpack getnetbyname reset chdir grep split require caller lcfirst until warn while values shift telldir getpwuid my getprotobynumber delete and sort uc defined srand accept package seekdir getprotobyname semop our rename seek if q|0 chroot sysread setpwent no crypt getc chown sqrt write setnetent setpriority foreach tie sin msgget map stat getlogin unless elsif truncate exec keys glob tied closedirioctl socket readlink eval xor readline binmode setservent eof ord bind alarm pipe atan2 getgrent exp time push setgrent gt lt or ne m|0 break given say state when",r={cN:"subst",b:"[$@]\\{",e:"\\}",k:t},s={b:"->{",e:"}"},n={cN:"variable",v:[{b:/\$\d/},{b:/[\$\%\@](\^\w\b|#\w+(\:\:\w+)*|{\w+}|\w+(\:\:\w*)*)/},{b:/[\$\%\@][^\s\w{]/,r:0}]},o={cN:"comment",b:"^(__END__|__DATA__)",e:"\\n$",r:5},i=[e.BE,r,n],c=[n,e.HCM,o,{cN:"comment",b:"^\\=\\w",e:"\\=cut",eW:!0},s,{cN:"string",c:i,v:[{b:"q[qwxr]?\\s*\\(",e:"\\)",r:5},{b:"q[qwxr]?\\s*\\[",e:"\\]",r:5},{b:"q[qwxr]?\\s*\\{",e:"\\}",r:5},{b:"q[qwxr]?\\s*\\|",e:"\\|",r:5},{b:"q[qwxr]?\\s*\\<",e:"\\>",r:5},{b:"qw\\s+q",e:"q",r:5},{b:"'",e:"'",c:[e.BE]},{b:'"',e:'"'},{b:"`",e:"`",c:[e.BE]},{b:"{\\w+}",c:[],r:0},{b:"-?\\w+\\s*\\=\\>",c:[],r:0}]},{cN:"number",b:"(\\b0[0-7_]+)|(\\b0x[0-9a-fA-F_]+)|(\\b[1-9][0-9_]*(\\.[0-9_]+)?)|[0_]\\b",r:0},{b:"(\\/\\/|"+e.RSR+"|\\b(split|return|print|reverse|grep)\\b)\\s*",k:"split return print reverse grep",r:0,c:[e.HCM,o,{cN:"regexp",b:"(s|tr|y)/(\\\\.|[^/])*/(\\\\.|[^/])*/[a-z]*",r:10},{cN:"regexp",b:"(m|qr)?/",e:"/[a-z]*",c:[e.BE],r:0}]},{cN:"sub",bK:"sub",e:"(\\s*\\(.*?\\))?[;{]",r:5},{cN:"operator",b:"-\\w\\b",r:0}];return r.c=c,s.c=c,{aliases:["pl"],k:t,c:c}});hljs.registerLanguage("ini",function(e){return{cI:!0,i:/\S/,c:[{cN:"comment",b:";",e:"$"},{cN:"title",b:"^\\[",e:"\\]"},{cN:"setting",b:"^[a-z0-9\\[\\]_-]+[ \\t]*=[ \\t]*",e:"$",c:[{cN:"value",eW:!0,k:"on off true false yes no",c:[e.QSM,e.NM],r:0}]}]}});hljs.registerLanguage("makefile",function(e){var a={cN:"variable",b:/\$\(/,e:/\)/,c:[e.BE]};return{aliases:["mk","mak"],c:[e.HCM,{b:/^\w+\s*\W*=/,rB:!0,r:0,starts:{cN:"constant",e:/\s*\W*=/,eE:!0,starts:{e:/$/,r:0,c:[a]}}},{cN:"title",b:/^[\w]+:\s*$/},{cN:"phony",b:/^\.PHONY:/,e:/$/,k:".PHONY",l:/[\.\w]+/},{b:/^\t+/,e:/$/,r:0,c:[e.QSM,a]}]}});
// Released under MIT license
// Copyright (c) 2009-2010 Dominic Baggott
// Copyright (c) 2009-2010 Ash Berlin
// Copyright (c) 2011 Christoph Dorn <christoph@christophdorn.com> (http://www.christophdorn.com)
// Date: 2013-09-15T16:12Z

(function(expose) {




  var MarkdownHelpers = {};

  // For Spidermonkey based engines
  function mk_block_toSource() {
    return "Markdown.mk_block( " +
            uneval(this.toString()) +
            ", " +
            uneval(this.trailing) +
            ", " +
            uneval(this.lineNumber) +
            " )";
  }

  // node
  function mk_block_inspect() {
    var util = require("util");
    return "Markdown.mk_block( " +
            util.inspect(this.toString()) +
            ", " +
            util.inspect(this.trailing) +
            ", " +
            util.inspect(this.lineNumber) +
            " )";

  }

  MarkdownHelpers.mk_block = function(block, trail, line) {
    // Be helpful for default case in tests.
    if ( arguments.length === 1 )
      trail = "\n\n";

    // We actually need a String object, not a string primitive
    /* jshint -W053 */
    var s = new String(block);
    s.trailing = trail;
    // To make it clear its not just a string
    s.inspect = mk_block_inspect;
    s.toSource = mk_block_toSource;

    if ( line !== undefined )
      s.lineNumber = line;

    return s;
  };


  var isArray = MarkdownHelpers.isArray = Array.isArray || function(obj) {
    return Object.prototype.toString.call(obj) === "[object Array]";
  };

  // Don't mess with Array.prototype. Its not friendly
  if ( Array.prototype.forEach ) {
    MarkdownHelpers.forEach = function forEach( arr, cb, thisp ) {
      return arr.forEach( cb, thisp );
    };
  }
  else {
    MarkdownHelpers.forEach = function forEach(arr, cb, thisp) {
      for (var i = 0; i < arr.length; i++)
        cb.call(thisp || arr, arr[i], i, arr);
    };
  }

  MarkdownHelpers.isEmpty = function isEmpty( obj ) {
    for ( var key in obj ) {
      if ( hasOwnProperty.call( obj, key ) )
        return false;
    }
    return true;
  };

  MarkdownHelpers.extract_attr = function extract_attr( jsonml ) {
    return isArray(jsonml)
        && jsonml.length > 1
        && typeof jsonml[ 1 ] === "object"
        && !( isArray(jsonml[ 1 ]) )
        ? jsonml[ 1 ]
        : undefined;
  };




 /**
   *  class Markdown
   *
   *  Markdown processing in Javascript done right. We have very particular views
   *  on what constitutes 'right' which include:
   *
   *  - produces well-formed HTML (this means that em and strong nesting is
   *    important)
   *
   *  - has an intermediate representation to allow processing of parsed data (We
   *    in fact have two, both as [JsonML]: a markdown tree and an HTML tree).
   *
   *  - is easily extensible to add new dialects without having to rewrite the
   *    entire parsing mechanics
   *
   *  - has a good test suite
   *
   *  This implementation fulfills all of these (except that the test suite could
   *  do with expanding to automatically run all the fixtures from other Markdown
   *  implementations.)
   *
   *  ##### Intermediate Representation
   *
   *  *TODO* Talk about this :) Its JsonML, but document the node names we use.
   *
   *  [JsonML]: http://jsonml.org/ "JSON Markup Language"
   **/
  var Markdown = function(dialect) {
    switch (typeof dialect) {
    case "undefined":
      this.dialect = Markdown.dialects.Gruber;
      break;
    case "object":
      this.dialect = dialect;
      break;
    default:
      if ( dialect in Markdown.dialects )
        this.dialect = Markdown.dialects[dialect];
      else
        throw new Error("Unknown Markdown dialect '" + String(dialect) + "'");
      break;
    }
    this.em_state = [];
    this.strong_state = [];
    this.debug_indent = "";
  };

  /**
   * Markdown.dialects
   *
   * Namespace of built-in dialects.
   **/
  Markdown.dialects = {};




  // Imported functions
  var mk_block = Markdown.mk_block = MarkdownHelpers.mk_block,
      isArray = MarkdownHelpers.isArray;

  /**
   *  parse( markdown, [dialect] ) -> JsonML
   *  - markdown (String): markdown string to parse
   *  - dialect (String | Dialect): the dialect to use, defaults to gruber
   *
   *  Parse `markdown` and return a markdown document as a Markdown.JsonML tree.
   **/
  Markdown.parse = function( source, dialect ) {
    // dialect will default if undefined
    var md = new Markdown( dialect );
    return md.toTree( source );
  };

  function count_lines( str ) {
    var n = 0,
        i = -1;
    while ( ( i = str.indexOf("\n", i + 1) ) !== -1 )
      n++;
    return n;
  }

  // Internal - split source into rough blocks
  Markdown.prototype.split_blocks = function splitBlocks( input ) {
    input = input.replace(/(\r\n|\n|\r)/g, "\n");
    // [\s\S] matches _anything_ (newline or space)
    // [^] is equivalent but doesn't work in IEs.
    var re = /([\s\S]+?)($|\n#|\n(?:\s*\n|$)+)/g,
        blocks = [],
        m;

    var line_no = 1;

    if ( ( m = /^(\s*\n)/.exec(input) ) !== null ) {
      // skip (but count) leading blank lines
      line_no += count_lines( m[0] );
      re.lastIndex = m[0].length;
    }

    while ( ( m = re.exec(input) ) !== null ) {
      if (m[2] === "\n#") {
        m[2] = "\n";
        re.lastIndex--;
      }
      blocks.push( mk_block( m[1], m[2], line_no ) );
      line_no += count_lines( m[0] );
    }

    return blocks;
  };

  /**
   *  Markdown#processBlock( block, next ) -> undefined | [ JsonML, ... ]
   *  - block (String): the block to process
   *  - next (Array): the following blocks
   *
   * Process `block` and return an array of JsonML nodes representing `block`.
   *
   * It does this by asking each block level function in the dialect to process
   * the block until one can. Succesful handling is indicated by returning an
   * array (with zero or more JsonML nodes), failure by a false value.
   *
   * Blocks handlers are responsible for calling [[Markdown#processInline]]
   * themselves as appropriate.
   *
   * If the blocks were split incorrectly or adjacent blocks need collapsing you
   * can adjust `next` in place using shift/splice etc.
   *
   * If any of this default behaviour is not right for the dialect, you can
   * define a `__call__` method on the dialect that will get invoked to handle
   * the block processing.
   */
  Markdown.prototype.processBlock = function processBlock( block, next ) {
    var cbs = this.dialect.block,
        ord = cbs.__order__;

    if ( "__call__" in cbs )
      return cbs.__call__.call(this, block, next);

    for ( var i = 0; i < ord.length; i++ ) {
      //D:this.debug( "Testing", ord[i] );
      var res = cbs[ ord[i] ].call( this, block, next );
      if ( res ) {
        //D:this.debug("  matched");
        if ( !isArray(res) || ( res.length > 0 && !( isArray(res[0]) ) ) )
          this.debug(ord[i], "didn't return a proper array");
        //D:this.debug( "" );
        return res;
      }
    }

    // Uhoh! no match! Should we throw an error?
    return [];
  };

  Markdown.prototype.processInline = function processInline( block ) {
    return this.dialect.inline.__call__.call( this, String( block ) );
  };

  /**
   *  Markdown#toTree( source ) -> JsonML
   *  - source (String): markdown source to parse
   *
   *  Parse `source` into a JsonML tree representing the markdown document.
   **/
  // custom_tree means set this.tree to `custom_tree` and restore old value on return
  Markdown.prototype.toTree = function toTree( source, custom_root ) {
    var blocks = source instanceof Array ? source : this.split_blocks( source );

    // Make tree a member variable so its easier to mess with in extensions
    var old_tree = this.tree;
    try {
      this.tree = custom_root || this.tree || [ "markdown" ];

      blocks_loop:
      while ( blocks.length ) {
        var b = this.processBlock( blocks.shift(), blocks );

        // Reference blocks and the like won't return any content
        if ( !b.length )
          continue blocks_loop;

        this.tree.push.apply( this.tree, b );
      }
      return this.tree;
    }
    finally {
      if ( custom_root )
        this.tree = old_tree;
    }
  };

  // Noop by default
  Markdown.prototype.debug = function () {
    var args = Array.prototype.slice.call( arguments);
    args.unshift(this.debug_indent);
    if ( typeof print !== "undefined" )
      print.apply( print, args );
    if ( typeof console !== "undefined" && typeof console.log !== "undefined" )
      console.log.apply( null, args );
  };

  Markdown.prototype.loop_re_over_block = function( re, block, cb ) {
    // Dont use /g regexps with this
    var m,
        b = block.valueOf();

    while ( b.length && (m = re.exec(b) ) !== null ) {
      b = b.substr( m[0].length );
      cb.call(this, m);
    }
    return b;
  };

  // Build default order from insertion order.
  Markdown.buildBlockOrder = function(d) {
    var ord = [];
    for ( var i in d ) {
      if ( i === "__order__" || i === "__call__" )
        continue;
      ord.push( i );
    }
    d.__order__ = ord;
  };

  // Build patterns for inline matcher
  Markdown.buildInlinePatterns = function(d) {
    var patterns = [];

    for ( var i in d ) {
      // __foo__ is reserved and not a pattern
      if ( i.match( /^__.*__$/) )
        continue;
      var l = i.replace( /([\\.*+?|()\[\]{}])/g, "\\$1" )
               .replace( /\n/, "\\n" );
      patterns.push( i.length === 1 ? l : "(?:" + l + ")" );
    }

    patterns = patterns.join("|");
    d.__patterns__ = patterns;
    //print("patterns:", uneval( patterns ) );

    var fn = d.__call__;
    d.__call__ = function(text, pattern) {
      if ( pattern !== undefined )
        return fn.call(this, text, pattern);
      else
        return fn.call(this, text, patterns);
    };
  };




  var extract_attr = MarkdownHelpers.extract_attr;

  /**
   *  renderJsonML( jsonml[, options] ) -> String
   *  - jsonml (Array): JsonML array to render to XML
   *  - options (Object): options
   *
   *  Converts the given JsonML into well-formed XML.
   *
   *  The options currently understood are:
   *
   *  - root (Boolean): wether or not the root node should be included in the
   *    output, or just its children. The default `false` is to not include the
   *    root itself.
   */
  Markdown.renderJsonML = function( jsonml, options ) {
    options = options || {};
    // include the root element in the rendered output?
    options.root = options.root || false;

    var content = [];

    if ( options.root ) {
      content.push( render_tree( jsonml ) );
    }
    else {
      jsonml.shift(); // get rid of the tag
      if ( jsonml.length && typeof jsonml[ 0 ] === "object" && !( jsonml[ 0 ] instanceof Array ) )
        jsonml.shift(); // get rid of the attributes

      while ( jsonml.length )
        content.push( render_tree( jsonml.shift() ) );
    }

    return content.join( "\n\n" );
  };


  /**
   *  toHTMLTree( markdown, [dialect] ) -> JsonML
   *  toHTMLTree( md_tree ) -> JsonML
   *  - markdown (String): markdown string to parse
   *  - dialect (String | Dialect): the dialect to use, defaults to gruber
   *  - md_tree (Markdown.JsonML): parsed markdown tree
   *
   *  Turn markdown into HTML, represented as a JsonML tree. If a string is given
   *  to this function, it is first parsed into a markdown tree by calling
   *  [[parse]].
   **/
  Markdown.toHTMLTree = function toHTMLTree( input, dialect , options ) {

    // convert string input to an MD tree
    if ( typeof input === "string" )
      input = this.parse( input, dialect );

    // Now convert the MD tree to an HTML tree

    // remove references from the tree
    var attrs = extract_attr( input ),
        refs = {};

    if ( attrs && attrs.references )
      refs = attrs.references;

    var html = convert_tree_to_html( input, refs , options );
    merge_text_nodes( html );
    return html;
  };

  /**
   *  toHTML( markdown, [dialect]  ) -> String
   *  toHTML( md_tree ) -> String
   *  - markdown (String): markdown string to parse
   *  - md_tree (Markdown.JsonML): parsed markdown tree
   *
   *  Take markdown (either as a string or as a JsonML tree) and run it through
   *  [[toHTMLTree]] then turn it into a well-formated HTML fragment.
   **/
  Markdown.toHTML = function toHTML( source , dialect , options ) {
    var input = this.toHTMLTree( source , dialect , options );

    return this.renderJsonML( input );
  };


  function escapeHTML( text ) {
    return text.replace( /&/g, "&amp;" )
               .replace( /</g, "&lt;" )
               .replace( />/g, "&gt;" )
               .replace( /"/g, "&quot;" )
               .replace( /'/g, "&#39;" );
  }

  function render_tree( jsonml ) {
    // basic case
    if ( typeof jsonml === "string" )
      return escapeHTML( jsonml );

    var tag = jsonml.shift(),
        attributes = {},
        content = [];

    if ( jsonml.length && typeof jsonml[ 0 ] === "object" && !( jsonml[ 0 ] instanceof Array ) )
      attributes = jsonml.shift();

    while ( jsonml.length )
      content.push( render_tree( jsonml.shift() ) );

    var tag_attrs = "";
    for ( var a in attributes )
      tag_attrs += " " + a + '="' + escapeHTML( attributes[ a ] ) + '"';

    // be careful about adding whitespace here for inline elements
    if ( tag === "img" || tag === "br" || tag === "hr" )
      return "<"+ tag + tag_attrs + "/>";
    else
      return "<"+ tag + tag_attrs + ">" + content.join( "" ) + "</" + tag + ">";
  }

  function convert_tree_to_html( tree, references, options ) {
    var i;
    options = options || {};

    // shallow clone
    var jsonml = tree.slice( 0 );

    if ( typeof options.preprocessTreeNode === "function" )
      jsonml = options.preprocessTreeNode(jsonml, references);

    // Clone attributes if they exist
    var attrs = extract_attr( jsonml );
    if ( attrs ) {
      jsonml[ 1 ] = {};
      for ( i in attrs ) {
        jsonml[ 1 ][ i ] = attrs[ i ];
      }
      attrs = jsonml[ 1 ];
    }

    // basic case
    if ( typeof jsonml === "string" )
      return jsonml;

    // convert this node
    switch ( jsonml[ 0 ] ) {
    case "header":
      jsonml[ 0 ] = "h" + jsonml[ 1 ].level;
      delete jsonml[ 1 ].level;
      break;
    case "bulletlist":
      jsonml[ 0 ] = "ul";
      break;
    case "numberlist":
      jsonml[ 0 ] = "ol";
      break;
    case "listitem":
      jsonml[ 0 ] = "li";
      break;
    case "para":
      jsonml[ 0 ] = "p";
      break;
    case "markdown":
      jsonml[ 0 ] = "html";
      if ( attrs )
        delete attrs.references;
      break;
    case "code_block":
      jsonml[ 0 ] = "pre";
      i = attrs ? 2 : 1;
      var code = [ "code" ];
      code.push.apply( code, jsonml.splice( i, jsonml.length - i ) );
      jsonml[ i ] = code;
      break;
    case "inlinecode":
      jsonml[ 0 ] = "code";
      break;
    case "img":
      jsonml[ 1 ].src = jsonml[ 1 ].href;
      delete jsonml[ 1 ].href;
      break;
    case "linebreak":
      jsonml[ 0 ] = "br";
      break;
    case "link":
      jsonml[ 0 ] = "a";
      break;
    case "link_ref":
      jsonml[ 0 ] = "a";

      // grab this ref and clean up the attribute node
      var ref = references[ attrs.ref ];

      // if the reference exists, make the link
      if ( ref ) {
        delete attrs.ref;

        // add in the href and title, if present
        attrs.href = ref.href;
        if ( ref.title )
          attrs.title = ref.title;

        // get rid of the unneeded original text
        delete attrs.original;
      }
      // the reference doesn't exist, so revert to plain text
      else {
        return attrs.original;
      }
      break;
    case "img_ref":
      jsonml[ 0 ] = "img";

      // grab this ref and clean up the attribute node
      var ref = references[ attrs.ref ];

      // if the reference exists, make the link
      if ( ref ) {
        delete attrs.ref;

        // add in the href and title, if present
        attrs.src = ref.href;
        if ( ref.title )
          attrs.title = ref.title;

        // get rid of the unneeded original text
        delete attrs.original;
      }
      // the reference doesn't exist, so revert to plain text
      else {
        return attrs.original;
      }
      break;
    }

    // convert all the children
    i = 1;

    // deal with the attribute node, if it exists
    if ( attrs ) {
      // if there are keys, skip over it
      for ( var key in jsonml[ 1 ] ) {
        i = 2;
        break;
      }
      // if there aren't, remove it
      if ( i === 1 )
        jsonml.splice( i, 1 );
    }

    for ( ; i < jsonml.length; ++i ) {
      jsonml[ i ] = convert_tree_to_html( jsonml[ i ], references, options );
    }

    return jsonml;
  }


  // merges adjacent text nodes into a single node
  function merge_text_nodes( jsonml ) {
    // skip the tag name and attribute hash
    var i = extract_attr( jsonml ) ? 2 : 1;

    while ( i < jsonml.length ) {
      // if it's a string check the next item too
      if ( typeof jsonml[ i ] === "string" ) {
        if ( i + 1 < jsonml.length && typeof jsonml[ i + 1 ] === "string" ) {
          // merge the second string into the first and remove it
          jsonml[ i ] += jsonml.splice( i + 1, 1 )[ 0 ];
        }
        else {
          ++i;
        }
      }
      // if it's not a string recurse
      else {
        merge_text_nodes( jsonml[ i ] );
        ++i;
      }
    }
  };



  var DialectHelpers = {};
  DialectHelpers.inline_until_char = function( text, want ) {
    var consumed = 0,
        nodes = [];

    while ( true ) {
      if ( text.charAt( consumed ) === want ) {
        // Found the character we were looking for
        consumed++;
        return [ consumed, nodes ];
      }

      if ( consumed >= text.length ) {
        // No closing char found. Abort.
        return null;
      }

      var res = this.dialect.inline.__oneElement__.call(this, text.substr( consumed ) );
      consumed += res[ 0 ];
      // Add any returned nodes.
      nodes.push.apply( nodes, res.slice( 1 ) );
    }
  };

  // Helper function to make sub-classing a dialect easier
  DialectHelpers.subclassDialect = function( d ) {
    function Block() {}
    Block.prototype = d.block;
    function Inline() {}
    Inline.prototype = d.inline;

    return { block: new Block(), inline: new Inline() };
  };




  var forEach = MarkdownHelpers.forEach,
      extract_attr = MarkdownHelpers.extract_attr,
      mk_block = MarkdownHelpers.mk_block,
      isEmpty = MarkdownHelpers.isEmpty,
      inline_until_char = DialectHelpers.inline_until_char;

  /**
   * Gruber dialect
   *
   * The default dialect that follows the rules set out by John Gruber's
   * markdown.pl as closely as possible. Well actually we follow the behaviour of
   * that script which in some places is not exactly what the syntax web page
   * says.
   **/
  var Gruber = {
    block: {
      atxHeader: function atxHeader( block, next ) {
        var m = block.match( /^(#{1,6})\s*(.*?)\s*#*\s*(?:\n|$)/ );

        if ( !m )
          return undefined;

        var header = [ "header", { level: m[ 1 ].length } ];
        Array.prototype.push.apply(header, this.processInline(m[ 2 ]));

        if ( m[0].length < block.length )
          next.unshift( mk_block( block.substr( m[0].length ), block.trailing, block.lineNumber + 2 ) );

        return [ header ];
      },

      setextHeader: function setextHeader( block, next ) {
        var m = block.match( /^(.*)\n([-=])\2\2+(?:\n|$)/ );

        if ( !m )
          return undefined;

        var level = ( m[ 2 ] === "=" ) ? 1 : 2,
            header = [ "header", { level : level }, m[ 1 ] ];

        if ( m[0].length < block.length )
          next.unshift( mk_block( block.substr( m[0].length ), block.trailing, block.lineNumber + 2 ) );

        return [ header ];
      },

      code: function code( block, next ) {
        // |    Foo
        // |bar
        // should be a code block followed by a paragraph. Fun
        //
        // There might also be adjacent code block to merge.

        var ret = [],
            re = /^(?: {0,3}\t| {4})(.*)\n?/;

        // 4 spaces + content
        if ( !block.match( re ) )
          return undefined;

        block_search:
        do {
          // Now pull out the rest of the lines
          var b = this.loop_re_over_block(
                    re, block.valueOf(), function( m ) { ret.push( m[1] ); } );

          if ( b.length ) {
            // Case alluded to in first comment. push it back on as a new block
            next.unshift( mk_block(b, block.trailing) );
            break block_search;
          }
          else if ( next.length ) {
            // Check the next block - it might be code too
            if ( !next[0].match( re ) )
              break block_search;

            // Pull how how many blanks lines follow - minus two to account for .join
            ret.push ( block.trailing.replace(/[^\n]/g, "").substring(2) );

            block = next.shift();
          }
          else {
            break block_search;
          }
        } while ( true );

        return [ [ "code_block", ret.join("\n") ] ];
      },

      horizRule: function horizRule( block, next ) {
        // this needs to find any hr in the block to handle abutting blocks
        var m = block.match( /^(?:([\s\S]*?)\n)?[ \t]*([-_*])(?:[ \t]*\2){2,}[ \t]*(?:\n([\s\S]*))?$/ );

        if ( !m )
          return undefined;

        var jsonml = [ [ "hr" ] ];

        // if there's a leading abutting block, process it
        if ( m[ 1 ] ) {
          var contained = mk_block( m[ 1 ], "", block.lineNumber );
          jsonml.unshift.apply( jsonml, this.toTree( contained, [] ) );
        }

        // if there's a trailing abutting block, stick it into next
        if ( m[ 3 ] )
          next.unshift( mk_block( m[ 3 ], block.trailing, block.lineNumber + 1 ) );

        return jsonml;
      },

      // There are two types of lists. Tight and loose. Tight lists have no whitespace
      // between the items (and result in text just in the <li>) and loose lists,
      // which have an empty line between list items, resulting in (one or more)
      // paragraphs inside the <li>.
      //
      // There are all sorts weird edge cases about the original markdown.pl's
      // handling of lists:
      //
      // * Nested lists are supposed to be indented by four chars per level. But
      //   if they aren't, you can get a nested list by indenting by less than
      //   four so long as the indent doesn't match an indent of an existing list
      //   item in the 'nest stack'.
      //
      // * The type of the list (bullet or number) is controlled just by the
      //    first item at the indent. Subsequent changes are ignored unless they
      //    are for nested lists
      //
      lists: (function( ) {
        // Use a closure to hide a few variables.
        var any_list = "[*+-]|\\d+\\.",
            bullet_list = /[*+-]/,
            // Capture leading indent as it matters for determining nested lists.
            is_list_re = new RegExp( "^( {0,3})(" + any_list + ")[ \t]+" ),
            indent_re = "(?: {0,3}\\t| {4})";

        // TODO: Cache this regexp for certain depths.
        // Create a regexp suitable for matching an li for a given stack depth
        function regex_for_depth( depth ) {

          return new RegExp(
            // m[1] = indent, m[2] = list_type
            "(?:^(" + indent_re + "{0," + depth + "} {0,3})(" + any_list + ")\\s+)|" +
            // m[3] = cont
            "(^" + indent_re + "{0," + (depth-1) + "}[ ]{0,4})"
          );
        }
        function expand_tab( input ) {
          return input.replace( / {0,3}\t/g, "    " );
        }

        // Add inline content `inline` to `li`. inline comes from processInline
        // so is an array of content
        function add(li, loose, inline, nl) {
          if ( loose ) {
            li.push( [ "para" ].concat(inline) );
            return;
          }
          // Hmmm, should this be any block level element or just paras?
          var add_to = li[li.length -1] instanceof Array && li[li.length - 1][0] === "para"
                     ? li[li.length -1]
                     : li;

          // If there is already some content in this list, add the new line in
          if ( nl && li.length > 1 )
            inline.unshift(nl);

          for ( var i = 0; i < inline.length; i++ ) {
            var what = inline[i],
                is_str = typeof what === "string";
            if ( is_str && add_to.length > 1 && typeof add_to[add_to.length-1] === "string" )
              add_to[ add_to.length-1 ] += what;
            else
              add_to.push( what );
          }
        }

        // contained means have an indent greater than the current one. On
        // *every* line in the block
        function get_contained_blocks( depth, blocks ) {

          var re = new RegExp( "^(" + indent_re + "{" + depth + "}.*?\\n?)*$" ),
              replace = new RegExp("^" + indent_re + "{" + depth + "}", "gm"),
              ret = [];

          while ( blocks.length > 0 ) {
            if ( re.exec( blocks[0] ) ) {
              var b = blocks.shift(),
                  // Now remove that indent
                  x = b.replace( replace, "");

              ret.push( mk_block( x, b.trailing, b.lineNumber ) );
            }
            else
              break;
          }
          return ret;
        }

        // passed to stack.forEach to turn list items up the stack into paras
        function paragraphify(s, i, stack) {
          var list = s.list;
          var last_li = list[list.length-1];

          if ( last_li[1] instanceof Array && last_li[1][0] === "para" )
            return;
          if ( i + 1 === stack.length ) {
            // Last stack frame
            // Keep the same array, but replace the contents
            last_li.push( ["para"].concat( last_li.splice(1, last_li.length - 1) ) );
          }
          else {
            var sublist = last_li.pop();
            last_li.push( ["para"].concat( last_li.splice(1, last_li.length - 1) ), sublist );
          }
        }

        // The matcher function
        return function( block, next ) {
          var m = block.match( is_list_re );
          if ( !m )
            return undefined;

          function make_list( m ) {
            var list = bullet_list.exec( m[2] )
                     ? ["bulletlist"]
                     : ["numberlist"];

            stack.push( { list: list, indent: m[1] } );
            return list;
          }


          var stack = [], // Stack of lists for nesting.
              list = make_list( m ),
              last_li,
              loose = false,
              ret = [ stack[0].list ],
              i;

          // Loop to search over block looking for inner block elements and loose lists
          loose_search:
          while ( true ) {
            // Split into lines preserving new lines at end of line
            var lines = block.split( /(?=\n)/ );

            // We have to grab all lines for a li and call processInline on them
            // once as there are some inline things that can span lines.
            var li_accumulate = "", nl = "";

            // Loop over the lines in this block looking for tight lists.
            tight_search:
            for ( var line_no = 0; line_no < lines.length; line_no++ ) {
              nl = "";
              var l = lines[line_no].replace(/^\n/, function(n) { nl = n; return ""; });


              // TODO: really should cache this
              var line_re = regex_for_depth( stack.length );

              m = l.match( line_re );
              //print( "line:", uneval(l), "\nline match:", uneval(m) );

              // We have a list item
              if ( m[1] !== undefined ) {
                // Process the previous list item, if any
                if ( li_accumulate.length ) {
                  add( last_li, loose, this.processInline( li_accumulate ), nl );
                  // Loose mode will have been dealt with. Reset it
                  loose = false;
                  li_accumulate = "";
                }

                m[1] = expand_tab( m[1] );
                var wanted_depth = Math.floor(m[1].length/4)+1;
                //print( "want:", wanted_depth, "stack:", stack.length);
                if ( wanted_depth > stack.length ) {
                  // Deep enough for a nested list outright
                  //print ( "new nested list" );
                  list = make_list( m );
                  last_li.push( list );
                  last_li = list[1] = [ "listitem" ];
                }
                else {
                  // We aren't deep enough to be strictly a new level. This is
                  // where Md.pl goes nuts. If the indent matches a level in the
                  // stack, put it there, else put it one deeper then the
                  // wanted_depth deserves.
                  var found = false;
                  for ( i = 0; i < stack.length; i++ ) {
                    if ( stack[ i ].indent !== m[1] )
                      continue;

                    list = stack[ i ].list;
                    stack.splice( i+1, stack.length - (i+1) );
                    found = true;
                    break;
                  }

                  if (!found) {
                    //print("not found. l:", uneval(l));
                    wanted_depth++;
                    if ( wanted_depth <= stack.length ) {
                      stack.splice(wanted_depth, stack.length - wanted_depth);
                      //print("Desired depth now", wanted_depth, "stack:", stack.length);
                      list = stack[wanted_depth-1].list;
                      //print("list:", uneval(list) );
                    }
                    else {
                      //print ("made new stack for messy indent");
                      list = make_list(m);
                      last_li.push(list);
                    }
                  }

                  //print( uneval(list), "last", list === stack[stack.length-1].list );
                  last_li = [ "listitem" ];
                  list.push(last_li);
                } // end depth of shenegains
                nl = "";
              }

              // Add content
              if ( l.length > m[0].length )
                li_accumulate += nl + l.substr( m[0].length );
            } // tight_search

            if ( li_accumulate.length ) {
              add( last_li, loose, this.processInline( li_accumulate ), nl );
              // Loose mode will have been dealt with. Reset it
              loose = false;
              li_accumulate = "";
            }

            // Look at the next block - we might have a loose list. Or an extra
            // paragraph for the current li
            var contained = get_contained_blocks( stack.length, next );

            // Deal with code blocks or properly nested lists
            if ( contained.length > 0 ) {
              // Make sure all listitems up the stack are paragraphs
              forEach( stack, paragraphify, this);

              last_li.push.apply( last_li, this.toTree( contained, [] ) );
            }

            var next_block = next[0] && next[0].valueOf() || "";

            if ( next_block.match(is_list_re) || next_block.match( /^ / ) ) {
              block = next.shift();

              // Check for an HR following a list: features/lists/hr_abutting
              var hr = this.dialect.block.horizRule( block, next );

              if ( hr ) {
                ret.push.apply(ret, hr);
                break;
              }

              // Make sure all listitems up the stack are paragraphs
              forEach( stack, paragraphify, this);

              loose = true;
              continue loose_search;
            }
            break;
          } // loose_search

          return ret;
        };
      })(),

      blockquote: function blockquote( block, next ) {
        if ( !block.match( /^>/m ) )
          return undefined;

        var jsonml = [];

        // separate out the leading abutting block, if any. I.e. in this case:
        //
        //  a
        //  > b
        //
        if ( block[ 0 ] !== ">" ) {
          var lines = block.split( /\n/ ),
              prev = [],
              line_no = block.lineNumber;

          // keep shifting lines until you find a crotchet
          while ( lines.length && lines[ 0 ][ 0 ] !== ">" ) {
            prev.push( lines.shift() );
            line_no++;
          }

          var abutting = mk_block( prev.join( "\n" ), "\n", block.lineNumber );
          jsonml.push.apply( jsonml, this.processBlock( abutting, [] ) );
          // reassemble new block of just block quotes!
          block = mk_block( lines.join( "\n" ), block.trailing, line_no );
        }


        // if the next block is also a blockquote merge it in
        while ( next.length && next[ 0 ][ 0 ] === ">" ) {
          var b = next.shift();
          block = mk_block( block + block.trailing + b, b.trailing, block.lineNumber );
        }

        // Strip off the leading "> " and re-process as a block.
        var input = block.replace( /^> ?/gm, "" ),
            old_tree = this.tree,
            processedBlock = this.toTree( input, [ "blockquote" ] ),
            attr = extract_attr( processedBlock );

        // If any link references were found get rid of them
        if ( attr && attr.references ) {
          delete attr.references;
          // And then remove the attribute object if it's empty
          if ( isEmpty( attr ) )
            processedBlock.splice( 1, 1 );
        }

        jsonml.push( processedBlock );
        return jsonml;
      },

      referenceDefn: function referenceDefn( block, next) {
        var re = /^\s*\[(.*?)\]:\s*(\S+)(?:\s+(?:(['"])(.*?)\3|\((.*?)\)))?\n?/;
        // interesting matches are [ , ref_id, url, , title, title ]

        if ( !block.match(re) )
          return undefined;

        // make an attribute node if it doesn't exist
        if ( !extract_attr( this.tree ) )
          this.tree.splice( 1, 0, {} );

        var attrs = extract_attr( this.tree );

        // make a references hash if it doesn't exist
        if ( attrs.references === undefined )
          attrs.references = {};

        var b = this.loop_re_over_block(re, block, function( m ) {

          if ( m[2] && m[2][0] === "<" && m[2][m[2].length-1] === ">" )
            m[2] = m[2].substring( 1, m[2].length - 1 );

          var ref = attrs.references[ m[1].toLowerCase() ] = {
            href: m[2]
          };

          if ( m[4] !== undefined )
            ref.title = m[4];
          else if ( m[5] !== undefined )
            ref.title = m[5];

        } );

        if ( b.length )
          next.unshift( mk_block( b, block.trailing ) );

        return [];
      },

      para: function para( block ) {
        // everything's a para!
        return [ ["para"].concat( this.processInline( block ) ) ];
      }
    },

    inline: {

      __oneElement__: function oneElement( text, patterns_or_re, previous_nodes ) {
        var m,
            res;

        patterns_or_re = patterns_or_re || this.dialect.inline.__patterns__;
        var re = new RegExp( "([\\s\\S]*?)(" + (patterns_or_re.source || patterns_or_re) + ")" );

        m = re.exec( text );
        if (!m) {
          // Just boring text
          return [ text.length, text ];
        }
        else if ( m[1] ) {
          // Some un-interesting text matched. Return that first
          return [ m[1].length, m[1] ];
        }

        var res;
        if ( m[2] in this.dialect.inline ) {
          res = this.dialect.inline[ m[2] ].call(
                    this,
                    text.substr( m.index ), m, previous_nodes || [] );
        }
        // Default for now to make dev easier. just slurp special and output it.
        res = res || [ m[2].length, m[2] ];
        return res;
      },

      __call__: function inline( text, patterns ) {

        var out = [],
            res;

        function add(x) {
          //D:self.debug("  adding output", uneval(x));
          if ( typeof x === "string" && typeof out[out.length-1] === "string" )
            out[ out.length-1 ] += x;
          else
            out.push(x);
        }

        while ( text.length > 0 ) {
          res = this.dialect.inline.__oneElement__.call(this, text, patterns, out );
          text = text.substr( res.shift() );
          forEach(res, add );
        }

        return out;
      },

      // These characters are intersting elsewhere, so have rules for them so that
      // chunks of plain text blocks don't include them
      "]": function () {},
      "}": function () {},

      __escape__ : /^\\[\\`\*_{}\[\]()#\+.!\-]/,

      "\\": function escaped( text ) {
        // [ length of input processed, node/children to add... ]
        // Only esacape: \ ` * _ { } [ ] ( ) # * + - . !
        if ( this.dialect.inline.__escape__.exec( text ) )
          return [ 2, text.charAt( 1 ) ];
        else
          // Not an esacpe
          return [ 1, "\\" ];
      },

      "![": function image( text ) {

        // Unlike images, alt text is plain text only. no other elements are
        // allowed in there

        // ![Alt text](/path/to/img.jpg "Optional title")
        //      1          2            3       4         <--- captures
        var m = text.match( /^!\[(.*?)\][ \t]*\([ \t]*([^")]*?)(?:[ \t]+(["'])(.*?)\3)?[ \t]*\)/ );

        if ( m ) {
          if ( m[2] && m[2][0] === "<" && m[2][m[2].length-1] === ">" )
            m[2] = m[2].substring( 1, m[2].length - 1 );

          m[2] = this.dialect.inline.__call__.call( this, m[2], /\\/ )[0];

          var attrs = { alt: m[1], href: m[2] || "" };
          if ( m[4] !== undefined)
            attrs.title = m[4];

          return [ m[0].length, [ "img", attrs ] ];
        }

        // ![Alt text][id]
        m = text.match( /^!\[(.*?)\][ \t]*\[(.*?)\]/ );

        if ( m ) {
          // We can't check if the reference is known here as it likely wont be
          // found till after. Check it in md tree->hmtl tree conversion
          return [ m[0].length, [ "img_ref", { alt: m[1], ref: m[2].toLowerCase(), original: m[0] } ] ];
        }

        // Just consume the '!['
        return [ 2, "![" ];
      },

      "[": function link( text ) {

        var orig = String(text);
        // Inline content is possible inside `link text`
        var res = inline_until_char.call( this, text.substr(1), "]" );

        // No closing ']' found. Just consume the [
        if ( !res )
          return [ 1, "[" ];

        var consumed = 1 + res[ 0 ],
            children = res[ 1 ],
            link,
            attrs;

        // At this point the first [...] has been parsed. See what follows to find
        // out which kind of link we are (reference or direct url)
        text = text.substr( consumed );

        // [link text](/path/to/img.jpg "Optional title")
        //                 1            2       3         <--- captures
        // This will capture up to the last paren in the block. We then pull
        // back based on if there a matching ones in the url
        //    ([here](/url/(test))
        // The parens have to be balanced
        var m = text.match( /^\s*\([ \t]*([^"']*)(?:[ \t]+(["'])(.*?)\2)?[ \t]*\)/ );
        if ( m ) {
          var url = m[1];
          consumed += m[0].length;

          if ( url && url[0] === "<" && url[url.length-1] === ">" )
            url = url.substring( 1, url.length - 1 );

          // If there is a title we don't have to worry about parens in the url
          if ( !m[3] ) {
            var open_parens = 1; // One open that isn't in the capture
            for ( var len = 0; len < url.length; len++ ) {
              switch ( url[len] ) {
              case "(":
                open_parens++;
                break;
              case ")":
                if ( --open_parens === 0) {
                  consumed -= url.length - len;
                  url = url.substring(0, len);
                }
                break;
              }
            }
          }

          // Process escapes only
          url = this.dialect.inline.__call__.call( this, url, /\\/ )[0];

          attrs = { href: url || "" };
          if ( m[3] !== undefined)
            attrs.title = m[3];

          link = [ "link", attrs ].concat( children );
          return [ consumed, link ];
        }

        // [Alt text][id]
        // [Alt text] [id]
        m = text.match( /^\s*\[(.*?)\]/ );

        if ( m ) {

          consumed += m[ 0 ].length;

          // [links][] uses links as its reference
          attrs = { ref: ( m[ 1 ] || String(children) ).toLowerCase(),  original: orig.substr( 0, consumed ) };

          link = [ "link_ref", attrs ].concat( children );

          // We can't check if the reference is known here as it likely wont be
          // found till after. Check it in md tree->hmtl tree conversion.
          // Store the original so that conversion can revert if the ref isn't found.
          return [ consumed, link ];
        }

        // [id]
        // Only if id is plain (no formatting.)
        if ( children.length === 1 && typeof children[0] === "string" ) {

          attrs = { ref: children[0].toLowerCase(),  original: orig.substr( 0, consumed ) };
          link = [ "link_ref", attrs, children[0] ];
          return [ consumed, link ];
        }

        // Just consume the "["
        return [ 1, "[" ];
      },


      "<": function autoLink( text ) {
        var m;

        if ( ( m = text.match( /^<(?:((https?|ftp|mailto):[^>]+)|(.*?@.*?\.[a-zA-Z]+))>/ ) ) !== null ) {
          if ( m[3] )
            return [ m[0].length, [ "link", { href: "mailto:" + m[3] }, m[3] ] ];
          else if ( m[2] === "mailto" )
            return [ m[0].length, [ "link", { href: m[1] }, m[1].substr("mailto:".length ) ] ];
          else
            return [ m[0].length, [ "link", { href: m[1] }, m[1] ] ];
        }

        return [ 1, "<" ];
      },

      "`": function inlineCode( text ) {
        // Inline code block. as many backticks as you like to start it
        // Always skip over the opening ticks.
        var m = text.match( /(`+)(([\s\S]*?)\1)/ );

        if ( m && m[2] )
          return [ m[1].length + m[2].length, [ "inlinecode", m[3] ] ];
        else {
          // TODO: No matching end code found - warn!
          return [ 1, "`" ];
        }
      },

      "  \n": function lineBreak() {
        return [ 3, [ "linebreak" ] ];
      }

    }
  };

  // Meta Helper/generator method for em and strong handling
  function strong_em( tag, md ) {

    var state_slot = tag + "_state",
        other_slot = tag === "strong" ? "em_state" : "strong_state";

    function CloseTag(len) {
      this.len_after = len;
      this.name = "close_" + md;
    }

    return function ( text ) {

      if ( this[state_slot][0] === md ) {
        // Most recent em is of this type
        //D:this.debug("closing", md);
        this[state_slot].shift();

        // "Consume" everything to go back to the recrusion in the else-block below
        return[ text.length, new CloseTag(text.length-md.length) ];
      }
      else {
        // Store a clone of the em/strong states
        var other = this[other_slot].slice(),
            state = this[state_slot].slice();

        this[state_slot].unshift(md);

        //D:this.debug_indent += "  ";

        // Recurse
        var res = this.processInline( text.substr( md.length ) );
        //D:this.debug_indent = this.debug_indent.substr(2);

        var last = res[res.length - 1];

        //D:this.debug("processInline from", tag + ": ", uneval( res ) );

        var check = this[state_slot].shift();
        if ( last instanceof CloseTag ) {
          res.pop();
          // We matched! Huzzah.
          var consumed = text.length - last.len_after;
          return [ consumed, [ tag ].concat(res) ];
        }
        else {
          // Restore the state of the other kind. We might have mistakenly closed it.
          this[other_slot] = other;
          this[state_slot] = state;

          // We can't reuse the processed result as it could have wrong parsing contexts in it.
          return [ md.length, md ];
        }
      }
    }; // End returned function
  }

  Gruber.inline["**"] = strong_em("strong", "**");
  Gruber.inline["__"] = strong_em("strong", "__");
  Gruber.inline["*"]  = strong_em("em", "*");
  Gruber.inline["_"]  = strong_em("em", "_");

  Markdown.dialects.Gruber = Gruber;
  Markdown.buildBlockOrder ( Markdown.dialects.Gruber.block );
  Markdown.buildInlinePatterns( Markdown.dialects.Gruber.inline );



  var Maruku = DialectHelpers.subclassDialect( Gruber ),
      extract_attr = MarkdownHelpers.extract_attr,
      forEach = MarkdownHelpers.forEach;

  Maruku.processMetaHash = function processMetaHash( meta_string ) {
    var meta = split_meta_hash( meta_string ),
        attr = {};

    for ( var i = 0; i < meta.length; ++i ) {
      // id: #foo
      if ( /^#/.test( meta[ i ] ) )
        attr.id = meta[ i ].substring( 1 );
      // class: .foo
      else if ( /^\./.test( meta[ i ] ) ) {
        // if class already exists, append the new one
        if ( attr["class"] )
          attr["class"] = attr["class"] + meta[ i ].replace( /./, " " );
        else
          attr["class"] = meta[ i ].substring( 1 );
      }
      // attribute: foo=bar
      else if ( /\=/.test( meta[ i ] ) ) {
        var s = meta[ i ].split( /\=/ );
        attr[ s[ 0 ] ] = s[ 1 ];
      }
    }

    return attr;
  };

  function split_meta_hash( meta_string ) {
    var meta = meta_string.split( "" ),
        parts = [ "" ],
        in_quotes = false;

    while ( meta.length ) {
      var letter = meta.shift();
      switch ( letter ) {
      case " " :
        // if we're in a quoted section, keep it
        if ( in_quotes )
          parts[ parts.length - 1 ] += letter;
        // otherwise make a new part
        else
          parts.push( "" );
        break;
      case "'" :
      case '"' :
        // reverse the quotes and move straight on
        in_quotes = !in_quotes;
        break;
      case "\\" :
        // shift off the next letter to be used straight away.
        // it was escaped so we'll keep it whatever it is
        letter = meta.shift();
        /* falls through */
      default :
        parts[ parts.length - 1 ] += letter;
        break;
      }
    }

    return parts;
  }

  Maruku.block.document_meta = function document_meta( block ) {
    // we're only interested in the first block
    if ( block.lineNumber > 1 )
      return undefined;

    // document_meta blocks consist of one or more lines of `Key: Value\n`
    if ( ! block.match( /^(?:\w+:.*\n)*\w+:.*$/ ) )
      return undefined;

    // make an attribute node if it doesn't exist
    if ( !extract_attr( this.tree ) )
      this.tree.splice( 1, 0, {} );

    var pairs = block.split( /\n/ );
    for ( var p in pairs ) {
      var m = pairs[ p ].match( /(\w+):\s*(.*)$/ ),
          key = m[ 1 ].toLowerCase(),
          value = m[ 2 ];

      this.tree[ 1 ][ key ] = value;
    }

    // document_meta produces no content!
    return [];
  };

  Maruku.block.block_meta = function block_meta( block ) {
    // check if the last line of the block is an meta hash
    var m = block.match( /(^|\n) {0,3}\{:\s*((?:\\\}|[^\}])*)\s*\}$/ );
    if ( !m )
      return undefined;

    // process the meta hash
    var attr = this.dialect.processMetaHash( m[ 2 ] ),
        hash;

    // if we matched ^ then we need to apply meta to the previous block
    if ( m[ 1 ] === "" ) {
      var node = this.tree[ this.tree.length - 1 ];
      hash = extract_attr( node );

      // if the node is a string (rather than JsonML), bail
      if ( typeof node === "string" )
        return undefined;

      // create the attribute hash if it doesn't exist
      if ( !hash ) {
        hash = {};
        node.splice( 1, 0, hash );
      }

      // add the attributes in
      for ( var a in attr )
        hash[ a ] = attr[ a ];

      // return nothing so the meta hash is removed
      return [];
    }

    // pull the meta hash off the block and process what's left
    var b = block.replace( /\n.*$/, "" ),
        result = this.processBlock( b, [] );

    // get or make the attributes hash
    hash = extract_attr( result[ 0 ] );
    if ( !hash ) {
      hash = {};
      result[ 0 ].splice( 1, 0, hash );
    }

    // attach the attributes to the block
    for ( var a in attr )
      hash[ a ] = attr[ a ];

    return result;
  };

  Maruku.block.definition_list = function definition_list( block, next ) {
    // one or more terms followed by one or more definitions, in a single block
    var tight = /^((?:[^\s:].*\n)+):\s+([\s\S]+)$/,
        list = [ "dl" ],
        i, m;

    // see if we're dealing with a tight or loose block
    if ( ( m = block.match( tight ) ) ) {
      // pull subsequent tight DL blocks out of `next`
      var blocks = [ block ];
      while ( next.length && tight.exec( next[ 0 ] ) )
        blocks.push( next.shift() );

      for ( var b = 0; b < blocks.length; ++b ) {
        var m = blocks[ b ].match( tight ),
            terms = m[ 1 ].replace( /\n$/, "" ).split( /\n/ ),
            defns = m[ 2 ].split( /\n:\s+/ );

        // print( uneval( m ) );

        for ( i = 0; i < terms.length; ++i )
          list.push( [ "dt", terms[ i ] ] );

        for ( i = 0; i < defns.length; ++i ) {
          // run inline processing over the definition
          list.push( [ "dd" ].concat( this.processInline( defns[ i ].replace( /(\n)\s+/, "$1" ) ) ) );
        }
      }
    }
    else {
      return undefined;
    }

    return [ list ];
  };

  // splits on unescaped instances of @ch. If @ch is not a character the result
  // can be unpredictable

  Maruku.block.table = function table ( block ) {

    var _split_on_unescaped = function( s, ch ) {
      ch = ch || '\\s';
      if ( ch.match(/^[\\|\[\]{}?*.+^$]$/) )
        ch = '\\' + ch;
      var res = [ ],
          r = new RegExp('^((?:\\\\.|[^\\\\' + ch + '])*)' + ch + '(.*)'),
          m;
      while ( ( m = s.match( r ) ) ) {
        res.push( m[1] );
        s = m[2];
      }
      res.push(s);
      return res;
    };

    var leading_pipe = /^ {0,3}\|(.+)\n {0,3}\|\s*([\-:]+[\-| :]*)\n((?:\s*\|.*(?:\n|$))*)(?=\n|$)/,
        // find at least an unescaped pipe in each line
        no_leading_pipe = /^ {0,3}(\S(?:\\.|[^\\|])*\|.*)\n {0,3}([\-:]+\s*\|[\-| :]*)\n((?:(?:\\.|[^\\|])*\|.*(?:\n|$))*)(?=\n|$)/,
        i,
        m;
    if ( ( m = block.match( leading_pipe ) ) ) {
      // remove leading pipes in contents
      // (header and horizontal rule already have the leading pipe left out)
      m[3] = m[3].replace(/^\s*\|/gm, '');
    } else if ( ! ( m = block.match( no_leading_pipe ) ) ) {
      return undefined;
    }

    var table = [ "table", [ "thead", [ "tr" ] ], [ "tbody" ] ];

    // remove trailing pipes, then split on pipes
    // (no escaped pipes are allowed in horizontal rule)
    m[2] = m[2].replace(/\|\s*$/, '').split('|');

    // process alignment
    var html_attrs = [ ];
    forEach (m[2], function (s) {
      if (s.match(/^\s*-+:\s*$/))
        html_attrs.push({align: "right"});
      else if (s.match(/^\s*:-+\s*$/))
        html_attrs.push({align: "left"});
      else if (s.match(/^\s*:-+:\s*$/))
        html_attrs.push({align: "center"});
      else
        html_attrs.push({});
    });

    // now for the header, avoid escaped pipes
    m[1] = _split_on_unescaped(m[1].replace(/\|\s*$/, ''), '|');
    for (i = 0; i < m[1].length; i++) {
      table[1][1].push(['th', html_attrs[i] || {}].concat(
        this.processInline(m[1][i].trim())));
    }

    // now for body contents
    forEach (m[3].replace(/\|\s*$/mg, '').split('\n'), function (row) {
      var html_row = ['tr'];
      row = _split_on_unescaped(row, '|');
      for (i = 0; i < row.length; i++)
        html_row.push(['td', html_attrs[i] || {}].concat(this.processInline(row[i].trim())));
      table[2].push(html_row);
    }, this);

    return [table];
  };

  Maruku.inline[ "{:" ] = function inline_meta( text, matches, out ) {
    if ( !out.length )
      return [ 2, "{:" ];

    // get the preceeding element
    var before = out[ out.length - 1 ];

    if ( typeof before === "string" )
      return [ 2, "{:" ];

    // match a meta hash
    var m = text.match( /^\{:\s*((?:\\\}|[^\}])*)\s*\}/ );

    // no match, false alarm
    if ( !m )
      return [ 2, "{:" ];

    // attach the attributes to the preceeding element
    var meta = this.dialect.processMetaHash( m[ 1 ] ),
        attr = extract_attr( before );

    if ( !attr ) {
      attr = {};
      before.splice( 1, 0, attr );
    }

    for ( var k in meta )
      attr[ k ] = meta[ k ];

    // cut out the string and replace it with nothing
    return [ m[ 0 ].length, "" ];
  };


  Markdown.dialects.Maruku = Maruku;
  Markdown.dialects.Maruku.inline.__escape__ = /^\\[\\`\*_{}\[\]()#\+.!\-|:]/;
  Markdown.buildBlockOrder ( Markdown.dialects.Maruku.block );
  Markdown.buildInlinePatterns( Markdown.dialects.Maruku.inline );


// Include all our depndencies and;
  expose.Markdown = Markdown;
  expose.parse = Markdown.parse;
  expose.toHTML = Markdown.toHTML;
  expose.toHTMLTree = Markdown.toHTMLTree;
  expose.renderJsonML = Markdown.renderJsonML;

})(function() {
  window.markdown = {};
  return window.markdown;
}());

/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

function executeAjax(url, data, successCallback, failureCallback, completeCallback)
{
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        beforeSend: function() {
            $('.rkcms-saving-progress').show();
        },
        success: function(response) {
            if (null != successCallback) {
                successCallback(response);
            }
        },
        error: function(error) {
            if (null != failureCallback) {
                failureCallback(error);

                return;
            }

            alertDialog(error.responseText, null, 'danger');
        },
        complete: function() {
            if (null != completeCallback) {
                completeCallback();
            }
            $('.rkcms-saving-progress').hide();
        }
    });
}
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

function confirmDialog(message, confirmCallback, cancelCallback, icon){
    if (null == icon) {
        icon = 'question';
    }
    message = formatDialogMessage(icon, message);

    bootbox.dialog({
        message: message,
        title: "RedKite CMS",
        animate: false,
        buttons: {
            cancel: {
                label: "Cancel",
                callback: cancelCallback
            },
            confirm: {
                label: "OK",
                className: "btn-primary",
                callback: confirmCallback
            }
        }
    });
}

function alertDialog(message, confirmCallback, icon, customTextClass){
    message = formatDialogMessage(icon, message);

    if (customTextClass != null) {
        textClass = customTextClass;
    }

    bootbox.dialog({
        message: message,
        title: "RedKite CMS",
        animate: false,
        buttons: {
            confirm: {
                label: "OK",
                className: "btn-" + textClass,
                callback: confirmCallback
            }
        }
    });
}

function formatDialogMessage(icon, message){
    switch (icon) {
        case "info":
            icon = 'fa-exclamation-circle';
            textClass = 'primary';
            break;
        case "warning":
            icon = 'fa-exclamation-triangle';
            textClass = 'warning';
            break;
        case "danger":
            icon = 'fa-close';
            textClass = 'danger';

            break;
        case "question":
            icon = 'fa-question-circle';
            textClass = 'primary';
            break;
        default:
            icon = null;
            textClass = 'default';
            break;
    }

    if (null !== icon) {
        message = '<div><i class="fa ' + icon + ' fa-3x text-' + textClass + ' pull-left"></i><p>' + message + '</p></div>';
    }

    return message;
}
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

function DockableModel()
{
    var self = this;
    self.dockStatus = ko.observable();

    _dockStatusFromElement = function(target)
    {
        var element = $(target);
        var position = element.offset();
        if(position == undefined) {
            return null;
        }

        var dockStatus = null;
        if (position.left < $('#rkcms-blocks-editor-panel').width()){
            dockStatus = "right";
        }

        if (screen.width - position.left + element.width() < $('#rkcms-blocks-editor-panel').width()){
            dockStatus = "left";
        }

        return dockStatus;
    }.bind(self);
}

DockableModel.prototype.dock = function()
{
    var self = this;

    var dockStatus = _dockStatusFromElement(self.activeModel.target());

    if (null == dockStatus) {
        self.dockLeft();

        return;
    }

    if (dockStatus == 'right') {
        self.dockRight();

        return;
    }

    self.dockLeft();
};

DockableModel.prototype.dockLeft = function ()
{
    var dockStatus = 'left';
    this.dockStatus(dockStatus);
    $('.rkcms-blocks-panel').addClass('rkcms-dock-left').removeClass('rkcms-dock-right');
    $('body').data('rkcms.dock-status', dockStatus);
    this.dockStatus('left');
};

DockableModel.prototype.dockRight = function ()
{
    var dockStatus = 'right';
    this.dockStatus(dockStatus);
    $('.rkcms-blocks-panel').addClass('rkcms-dock-right').removeClass('rkcms-dock-left');
    $('body').data('rkcms.dock-status', dockStatus);
    this.dockStatus('right');
};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var HighlightableModel = function ()
{
    var self = this;
    self.active = false;
    self.target = ko.observable(null);
};

HighlightableModel.prototype.highlight = function()
{
    $(this.target()).highlight('highlight');
};

HighlightableModel.prototype.hide = function ()
{
    $(this.target()).highlight('hide');
};

HighlightableModel.prototype.activate = function(value)
{
    this.active = value;
};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var slotEditorModel;
var blockEditorModel;

$(document).ready(function()
{
    slotEditorModel = new SlotEditorModel();
    ko.applyBindings(slotEditorModel, document.getElementById('rkcms-slots-editor-panel'));

    blockEditorModel = new BlockEditorModel();
    ko.applyBindings(blockEditorModel, document.getElementById('rkcms-blocks-editor-panel'));

    $('.rkcms-slot').each(function(){
        var element = $(this);
        var blocks = ko.utils.parseJson(decodeURIComponent(element.attr("data-slot")));
        var slotName = element.attr("data-slotname");
        var slot = new PageModel(blocks, slotName, blockEditorModel);
        ko.applyBindings(slot, this);
    });

    $('.rkcms-warning-btn').popover();
    $('#rkcms-control-panel-btn')
        .popover()
        .on('shown.bs.popover', function () {
            var status = $('body').data('rkcms.editor-button-status');
            var moveMode = $('body').data('rkcms.editor-move-mode');
            var model = new ControlPanelModel(status, moveMode);
            ko.applyBindings(model, document.getElementById('rkcms-control-panel-body'));
        })
        .on('hide.bs.popover', function () {
            $('.rkcms-edit').each(function(){
                ko.cleanNode(this);
            });
        })
    ;

    var seoModel = new SeoModel($('#rkcms-seo').attr("data-seo"));
    ko.applyBindings(seoModel, document.getElementById('rkcms-seo'));

    RunHolder();
    Highlight();
});

function RunHolder()
{
    window.setTimeout(function(){
        Holder.run();
    }, 1);
}

function Highlight()
{
    window.setTimeout(function(){
        $('pre code').each(function(i, block) {
            hljs.highlightBlock(block);
        });
    }, 1);
}

function mediaLibrary(url, callback)
{
    $('<div/>').dialogelfinder({
        url : url,
        lang : $('#al_available_languages option:selected').val(),
        width : 840,
        destroyOnClose : true,
        commandsOptions : {
            getfile: {
                oncomplete: 'destroy'
            }
        },
        getFileCallback : function(file, fm) {
            callback(file, fm);
        }
    }).dialogelfinder('instance');
}
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

function BaseEditorModel()
{
    var self = this;
    DockableModel.call(self);

    self.activeModel = null;
    self.showEditor = ko.observable(false);
    self.showToolbar = ko.observable(false);
    self.showSlotsPanel = ko.observable(false);
    self.mode = ko.observable('inline');
    self.isEditing = ko.observable(true);

    self.availableBlocksPanel = ko.observable(false);
    self.isModelChanged = ko.observable(false);
    var _keepHighlighted = false;
}

BaseEditorModel.prototype = Object.create(DockableModel.prototype);
BaseEditorModel.prototype.constructor = BaseEditorModel;

BaseEditorModel.prototype.keepHighlighted = function()
{
    _keepHighlighted = true;

    return this;
};

BaseEditorModel.prototype.openEditor = function()
{
    if (this.showEditor() || !this.isEditing()) {
        this.closeEditor();

        if (!this.isModelChanged()){
            return true;
        }
    }

    this.showEditor(true);
    this.showToolbar(true);
    this.showSlotsPanel(true);
    $(this.activeModel.target()).highlight('activate');
};

BaseEditorModel.prototype.closeEditor = function()
{
    if (this.activeModel != null && ! this._keepHighlighted) {
        $(this.activeModel.target()).highlight('close');
    }

    this.availableBlocksPanel(false);
    this.showEditor(false);
    this.showToolbar(false);
    this.showSlotsPanel(false);
    this.mode('inline');
    this.isEditing(true);
};

BaseEditorModel.prototype.setModel = function(model)
{

    this.isModelChanged(false);
    if (this.activeModel != null && this.activeModel != model) {
        this.isModelChanged(true);
    }

    this.activeModel = model;

    return this;
};

BaseEditorModel.prototype.selectBlock = function (view, event)
{
    $(event.target).selectOnce('toggle',{
        cssClass: 'rkcms-selected-block'
    });
};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var BlockEditorModel = function ()
{
    var self = this;
    BaseEditorModel.call(self);

    self.historyPanel = ko.observable(false);
    self.transactionAddTop = ko.observable(false);
    self.transactionAddBottom = ko.observable(false);
    self.transactionRemove = ko.observable(false);
    self.dockStatus = ko.observable();
    self.history = ko.observableArray();
    self.source = ko.observable();
    self.error = ko.observable();
    self.toolbar = ko.observableArray();
    self.isHistoryEmpty = ko.computed(function(){
        return self.history().length == 0;
    });

    _add = function(direction, transactionIcon, type, position)
    {
        var model = self.activeModel;
        $(document).trigger("rkcms.event.adding_block", [ model ]);

        transactionIcon(true);
        var block = model.block;
        var blocks = model.parent.blocks;

        if(type == null) {
            type = $('.rkcms-available-blocks .rkcms-selected-block').attr('data-type');
            if (null == type) {
                type = "Text";
            }
        }

        if(position == null) {
            position = blocks.indexOf(block);
            if (direction == "bottom") {
                position += 1;
            }
        }

        var url = frontcontroller + '/backend/block/add';
        var data = {
            'type': type,
            'slot': block.slot_name,
            'position': position,
            'page':  page,
            'language': language,
            'country': country,
            'direction': direction
        };

        executeAjax(url, data,
            function(response)
            {
                response.slot_name = block.slot_name;
                response.is_new = true;
                blocks.splice(position, 0, response);
                model.resize();

                $(document).trigger("rkcms.event.block_added", [ model, response ]);
            },
            null,
            function()
            {
                transactionIcon(false);
            }
        );
    }.bind(self);

    _remove = function(event, transactionRemove)
    {
        var model = self.activeModel;
        $(document).trigger("rkcms.event.removing_block", [ model, event ]);

        transactionRemove(true);
        model.parent.blocks.remove(model.block);

        var url = frontcontroller + '/backend/block/remove';
        var data = {
            'name': model.name,
            'slot': model.slotName,
            'page':  page,
            'language': language,
            'country': country
        };
        executeAjax(url, data,
            function(response)
            {
                self.closeEditor();
                $(document).trigger("rkcms.event.block_removed", [ model, event ]);
            },
            null,
            function()
            {
                transactionRemove(false);
            }
        );
    }.bind(self);

    _closeAvailableBlocksPanel = function()
    {
        self.availableBlocksPanel(false);
    }.bind(self);


    _closeHistoryPanel = function()
    {
        self.undoRestoration();
        self.historyPanel(false);
    }.bind(self);

    _closePanels = function()
    {
        _closeAvailableBlocksPanel();
        _closeHistoryPanel();
    }.bind(self);

    _storageName = function()
    {
        return language + "-" + country  + "-" + page + "-" + self.activeModel.slotName + "-" + self.activeModel.name;
    };

    _changeEditorMode = function()
    {


    };
};

BlockEditorModel.prototype = Object.create(BaseEditorModel.prototype);
BlockEditorModel.prototype.constructor = BlockEditorModel;

BlockEditorModel.prototype.setModel = function(model)
{
    BaseEditorModel.prototype.setModel.call(this, model);

    var history = [];
    var source = "";
    if(this.activeModel != null) {
        this.toolbar(this.activeModel.toolbar());
        history = this.activeModel.history();
        source = this.activeModel.source;
    }
    this.history(history);
    this.source(source);

    return this;
};

BlockEditorModel.prototype.openEditor = function()
{
    slotEditorModel.closeEditor();
    _closePanels();
    BaseEditorModel.prototype.openEditor.call(this);

    var savedMode = localStorage.getItem(_storageName());
    if(savedMode != null) {
        this.mode(savedMode);
    }
    if (this.mode() == 'inplace'){
        $(this.activeModel.target()).highlight('close');
    }
};

BlockEditorModel.prototype.closeEditor = function(keepHighlighted)
{
    BaseEditorModel.prototype.closeEditor.call(this, keepHighlighted);
    _closeHistoryPanel();
};

BlockEditorModel.prototype.openAddPanel = function()
{
    this._keepHighlighted = true;
    this.closeEditor();
    this._keepHighlighted = false;

    this.availableBlocksPanel(true);
    this.dock();
    $('.rkcms-available-blocks').fullHeight();
};

BlockEditorModel.prototype.openHistoryPanel = function()
{
    this._keepHighlighted = true;
    this.closeEditor();
    this._keepHighlighted = false;

    this.historyPanel(true);
    this.dock();
    $('.rkcms-block-history').fullHeight();
};

BlockEditorModel.prototype.closePanel = function()
{
    _closePanels();

    if (this.activeModel != null) {
        this.openEditor();
    }
};

BlockEditorModel.prototype.addTop = function()
{
    _add('top', this.transactionAddTop);
};

BlockEditorModel.prototype.addBottom = function(view, event)
{
    _add('bottom', this.transactionAddBottom);
};

BlockEditorModel.prototype.edit = function()
{
    var self = this;
    var model = this.activeModel;
    $(document).trigger("rkcms.event.editing_block", [ model ]);

    var url = frontcontroller + '/backend/block/edit';
    var data = {
        'name': model.name,
        'type': model.type,
        'slot': model.slotName,
        'page':  page,
        'language': language,
        'country': country,
        'data': model.blockToJson()
    };
    executeAjax(url, data,
        function(response)
        {
            model.initHistory(response.history, model);

            self.history(model.history());

            $(document).trigger("rkcms.event.block_edited", [ model, response ]);
        }
    );
};

BlockEditorModel.prototype.remove = function(view, event)
{
    var self = this;
    var message = redkitecmsDomain.frontend_confirm_block_remove;
    confirmDialog(message, function(){
        _remove(event, self.transactionRemove);
    });
};

BlockEditorModel.prototype.undoRestoration = function ()
{
    if ( ! this.historyPanel()) {
        return;
    }

    var clonedBlock = this.activeModel.cloneBlock;
    if (clonedBlock == null) {
        return false;
    }

    clonedBlock.restore(clonedBlock.block);
    this.activeModel.resize();
    $('.rkcms-selected-block').removeClass('rkcms-selected-block');
    this.activeModel.activeListItemBlock.isDirty = false;
};

BlockEditorModel.prototype.confirmRestoration = function ()
{
    this.activeModel.restoreBlock();
};

BlockEditorModel.prototype.changeMode = function()
{
    var mode = this.mode() == 'inline' ? 'inplace' : 'inline';
    localStorage.setItem(_storageName(), mode);
    this.mode(mode);

    var $target = $(this.activeModel.target());
    if (mode == 'inline') {
        $target.highlight('activate');
    } else {
        $target.highlight('close');
    }
    $(".rkcms-ace-editor:visible").aceEditor('place');
};

BlockEditorModel.prototype.toggleEditor = function(a, event)
{
    this.isEditing(!this.isEditing());

    var target = this.activeModel.target();
    if (this.isEditing()) {
        this.showEditor(true);
        $(target).highlight('close').trigger("rkcms.event.in_place_editing", [ target ]);
    } else {
        this.showEditor(false);
        $('.rkcms-preview-toolbar').position({
            of: target,
            my: "right top",
            at: "right top",
            collision: "none"
        });
        $(target).highlight('activate').trigger("rkcms.event.in_place_preview", [ target ]);
    }
};

BlockEditorModel.prototype.insertPermalink = function()
{
    this.activeModel.editor.insert($('.rkcms-pages-selector:visible option:selected').val());
};

BlockEditorModel.prototype.openImagesSelector  = function()
{
    var self = this;

    var url = frontcontroller + '/backend/elfinder/media/connect';
    mediaLibrary(url, function(file, fm){
        self.activeModel.editor.insert(file.url);
    });
};

BlockEditorModel.prototype.insertLink = function()
{
    var link = '  item' + (this.activeModel.children().length + 1) + ':\n' +
        '    value: \'Displayed value\'\n' +
        '    tags:\n' +
        '      href: \'#\'\n' +
        '    type: Link\n';
    this.activeModel.editor.insert(link);
};

BlockEditorModel.prototype.insertIcon = function()
{
    var icon = '  item' + (this.activeModel.children().length + 1) + ':\n' +
        '    value: \'Displayed value\'\n' +
        '    tags:\n' +
        '      class: \'fa fa-cog\'\n' +
        '    type: Icon\n';

    this.activeModel.editor.insert(icon);
};

BlockEditorModel.prototype.insertIconLinked = function()
{
    var stackedIcon = '  item' + (this.activeModel.children().length + 1) + ':\n' +
        '    children:\n' +
        '      item1:\n' +
        '        value: \'Linked icon\'\n' +
        '        tags:\n' +
        '          class: \'fa fa-caret-right\'\n' +
        '        type: Icon\n' +
        '    tags:\n' +
        '      href: \'#\'\n' +
        '    type: IconLinked\n';
    this.activeModel.editor.insert(stackedIcon);
};

BlockEditorModel.prototype.insertIconStacked = function()
{
    var stackedIcon = '  item' + (this.activeModel.children().length + 1) + ':\n' +
        '    children:\n' +
        '      item1:\n' +
        '        tags:\n' +
        '          class: \'fa fa-circle-o fa-stack-2x\'\n' +
        '        type: Icon\n' +
        '      item2:\n' +
        '        tags:\n' +
        '          class: \'fa fa-cog fa-stack-1x\'\n' +
        '        type: Icon\n' +
        '    tags:\n' +
        '      class: \'fa-stack fa-lg\'\n' +
        '    value: \'Stacked icon\'\n' +
        '    type: IconStacked\n';
    this.activeModel.editor.insert(stackedIcon);
};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var ListItemBlock = function (parent, block)
{
    var self = this;

    self.parent = parent;
    self.block = block;
    self.isDirty = false;
};

ListItemBlock.prototype.restore = function(view, event)
{
    $(event.target)
        .unbind("rkcms.event.list_element_selected")
        .unbind("rkcms.event.list_element_deselected")
        .on("rkcms.event.list_element_selected", function(){
            view.isDirty = true;
            view.parent.cloneBlock = view.parent;
            view.parent.activeListItemBlock = view;
            view.parent.restore(view.block);
            view.parent.resize();
        })
        .on("rkcms.event.list_element_deselected", function(){
            blockEditorModel.undoRestoration();
        })
        .selectOnce('toggle', {
            cssClass: 'rkcms-selected-block'
        })
    ;
};

var ArchiveBlock = function (parent, block)
{
    var self = this;
    ListItemBlock.call(self, parent, block);

    self.historyName = '';
    if (null != block.history_name) {
        self.historyName = block.history_name;
    }
};

ArchiveBlock.prototype = Object.create(ListItemBlock.prototype);
ArchiveBlock.prototype.constructor = ArchiveBlock;

var Block = function (params)
{
    var self = this;
    HighlightableModel.call(self);

    self.parent = params.parent;
    self.block = params.block;
    self.target = ko.observable(null);
    self.type = self.block.type;
    self.name = self.block.name;
    self.slotName = self.block.slot_name;
    self.cssClass = ko.observable(self.block.cssClass);
    self.cloneBlock = null;
    self.activeListItemBlock = null;
    self.history = ko.observableArray();
    self.toolbar = ko.observableArray();
    self.isRemoved = ko.computed(function(){
        return params.block.isRemoved != undefined && params.block.isRemoved;
    });

    // Initialization
    // ==============
    self.initHistory(self.block.history);
};

Block.prototype = Object.create(HighlightableModel.prototype);
Block.prototype.constructor = Block;
Block.prototype.startBlockEditing = function()
{
    if (!this.parent.active) {
        return true;
    }

    blockEditorModel
        .setModel(this)
        .openEditor()
    ;

    $(document).data('rkcms-active-model', this);
};

Block.prototype.highlight = function()
{
    if ( ! this.parent.active)
    {
        return true;
    }

    HighlightableModel.prototype.highlight.call(this);
};


Block.prototype.initHistory = function(values, model)
{
    if (null == values) {
        return;
    }

    if (null == model) {
        model = this;
    }

    model.history([]);
    ko.utils.arrayForEach(values, function(block){
        if (typeof block == 'string') {
            block = ko.utils.parseJson(block);
            if (typeof block != 'object') {
                return false;
            }
        }

        model.history.push(new ArchiveBlock(model, block));
    });
};

Block.prototype.blockToJson = function()
{
    alertDialog(redkitecmsDomain.frontend_missing_block_to_json_method_implementation, null, 'danger');
};

Block.prototype.restore = function(archivedBlock)
{
    alertDialog(redkitecmsDomain.frontend_missing_restore_method_implementation, null, 'danger');
};

Block.prototype.resize  = function()
{
    var self = this;
    if (self.target() != null && $('.rkcms-block-menu').is(':visible')) {
        window.setTimeout(function(){
            $(self.target()).highlight('resize');
        }, 10);
    }

    return true;
};

Block.prototype.restoreBlock = function()
{
    self = this;
    if (self.activeListItemBlock == null) {
        return;
    }

    $(document).trigger("rkcms.event.restoring_block", [ self ]);

    var url = frontcontroller + '/backend/block/restore';
    var data = {
        'name': self.name,
        'type': self.type,
        'slot': self.slotName,
        'archiveFile': self.activeListItemBlock.historyName,
        'page':  page,
        'language': language,
        'country': country,
        'data': self.blockToJson()
    };
    executeAjax(url, data,
        function(response)
        {
            self.history().length = 0;
            self.initHistory(response, self);
            blockEditorModel.history(self.history());
            $(document).trigger("rkcms.event.block_restored", [ self ]);
        }
    );
};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var ControlPanelModel = function (status, moveMode) {
    var self = this;    
    if (null == status) {
        status = 'off';
    }

    if (null == moveMode) {
        moveMode = 'off';
    }

    self.editorStatus = ko.observable(status);
    self.moveMode = ko.observable(moveMode);
    self.isTheme = ko.observable(isTheme);

    _toggleButtonPressed = function(element, pressed)
    {
        if(!pressed) {
            element.addClass('btn-default');
            element.addClass('active');

            return;
        }

        element.removeClass('btn-default');
        element.removeClass('active');
    }.bind(self);

    _closeControlPanel = function()
    {
        $('#rkcms-control-panel-btn').popover('hide');
    }.bind(self);

    _stopEditor = function(view, event)
    {
        blockEditorModel.closeEditor();
        slotEditorModel.closeEditor();

        $('.rkcms-slot').each(function(){
            var slot = ko.dataFor(this);
            slot.activate(false);
        });

        $('#rkcms-control-panel-btn').removeClass('btn-success');
        _updateStatus('off');

        $(document).trigger("rkcms.event.blocks_editor_stopped", [ self, event ]);
    }.bind(self);
    
    _stopMoving = function()
    {
        $(".rkcms-sortable").sortable("destroy");
        $('#rkcms-control-panel-btn').removeClass('btn-warning');
        _updateMoveMode('off');
    }.bind(self);
    
    _updateStatus = function(status)
    {
        self.editorStatus(status);
        
        // we must save the current status outside the model because the popover destroys
        // its content when it is closed
        $('body').data('rkcms.editor-button-status', status);
    }.bind(self);

    _updateMoveMode = function(status)
    {
        self.moveMode(status);

        // we must save the current status outside the model because the popover destroys
        // its content when it is closed
        $('body').data('rkcms.editor-move-mode', status);
    }.bind(self);

    _toggleButtonPressed($('#rkcms-page-published'), !pagePublished);
};

ControlPanelModel.prototype.startEdit = function (view, event)
{
    var self = this;
    if (self.moveMode() == 'on') {
        _stopMoving();
    }

    $('.rkcms-slot').each(function(){
        var slot = ko.dataFor(this);
        slot.activate(true);
    });

    _updateStatus('on');
    $('#rkcms-control-panel-btn').addClass('btn-success');
    _closeControlPanel();

    $(document).trigger("rkcms.event.blocks_editor_started", [ self, event ]);
};

ControlPanelModel.prototype.stopEdit = function (view, event)
{
    _stopEditor(view, event);
    _closeControlPanel();
};

ControlPanelModel.prototype.dashboard = function()
{
    location.href = frontcontroller + '/backend/dashboard';
};

ControlPanelModel.prototype.seoPanel = function (view, event)
{
    var seoModel = ko.dataFor(document.getElementById('rkcms-seo'));
    var element = $(event.target).parent();
    _toggleButtonPressed(element, seoModel.toggleBlocksEditor());

    _closeControlPanel();

    seoModel.dockRight();
    seoModel.toggle();
};

ControlPanelModel.prototype.startMove = function (view, event)
{
    _stopEditor(view, event);

    $('#rkcms-control-panel-btn').addClass('btn-warning');
    _closeControlPanel();
    _updateMoveMode('on');
    $('.rkcms-sortable').blocksmover();
};

ControlPanelModel.prototype.stopMove = function ()
{
    _stopMoving();
};

ControlPanelModel.prototype.savePage = function (view, event)
{
    var self = this;
    if (self.isTheme() == 'on'){
        alertDialog(redkitecmsDomain.frontend_save_page_disable_in_theme_mode, null, 'info');

        return;
    }

    var message = redkitecmsDomain.frontend_confirm_save_page;
    confirmDialog(message, function(){
        $(document).trigger("rkcms.event.saving_page", [ self, event ]);

        var url = frontcontroller + '/backend/page/save';
        var data = {
            'page':  page,
            'language': language,
            'country': country
        };
        executeAjax(url, data, function(){
            alertDialog(redkitecmsDomain.frontend_page_saved, null, 'info');
        });
    });
};

ControlPanelModel.prototype.saveAllPages = function ()
{
    var self = this;
    if (self.isTheme() == 'on'){
        alertDialog(redkitecmsDomain.frontend_save_site_disable_in_theme_mode, null, 'info');

        return;
    }

    var message = redkitecmsDomain.frontend_confirm_save_site;
    confirmDialog(message, function(){
        var url = frontcontroller + '/backend/page/collection/save-all';
        var data = {
            'page':  page,
            'language': language,
            'country': country
        };
        executeAjax(url, data, function(){
            alertDialog(redkitecmsDomain.frontend_site_saved, null, 'info');
        });
    });
};

ControlPanelModel.prototype.saveTheme = function ()
{
    var message = redkitecmsDomain.frontend_confirm_save_theme;
    confirmDialog(message, function(){
        var url = frontcontroller + '/backend/theme/save';
        var data = {
            'page':  page,
            'language': language,
            'country': country
        };
        executeAjax(url, data, function(){
            alertDialog(redkitecmsDomain.frontend_theme_saved, null, 'info');
        });
    });
};

ControlPanelModel.prototype.pagePublished = function (view, event)
{
    var message = redkitecmsDomain.frontend_confirm_publish_page;
    var url = frontcontroller + '/backend/page/publish';
    if (pagePublished) {
        message = redkitecmsDomain.frontend_confirm_hide_page;
        url =  frontcontroller + '/backend/page/hide';
    }

    var element = $(event.target).parent();
    confirmDialog(message, function(){
        var data = {
            'page':  page,
            'language': language,
            'country': country
        };
        executeAjax(url, data, function(){
            _toggleButtonPressed(element, pagePublished);
            pagePublished = !pagePublished;
        });
    },function(){
        _toggleButtonPressed(element, !pagePublished);
    });
};

ControlPanelModel.prototype.logout = function()
{
    var message = redkitecmsDomain.frontend_confirm_logout;
    confirmDialog(message, function(){
        location.href = frontcontroller + '/backend/logout';
    });
};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

ko.bindingHandlers.dynamicAttr = {
    update: function(element, valueAccessor, allBindings) {
        var value = valueAccessor();
        var attributes = ko.isObservable(value) ? value() : value;
        for (var key in attributes) {
            if (attributes.hasOwnProperty(key)) {
                var propertyValue = attributes[key];
                if (typeof propertyValue == 'string') {
                    element.setAttribute(key, propertyValue);
                }
            }
        }
    }
};

ko.bindingHandlers.element = {
    init: function(element, valueAccessor) {
        var target = valueAccessor();
        target(element);
    }
};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var Extendable = function (params)
{
    var self = this;
    Block.call(self, params);
    
    self.value = ko.observable(self.block.value);
    self.tags = ko.observable(self.block.tags);
    self.source = self.block.source;
    self.error = ko.observable();
    self.editor = null;
};

Extendable.prototype = Object.create(Block.prototype);
Extendable.prototype.constructor = Extendable;
Extendable.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    $(".rkcms-ace-editor:visible").aceEditor('open');
};

Extendable.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.value = self.value();
    block.tags = self.tags();
    block.source = self.source;

    return ko.toJSON(block);
};

Extendable.prototype.update = function(newValue, source)
{
    var self = this;
    self.value(newValue.value);
    self.tags(newValue.tags);
    self.source = source;
};

Extendable.prototype.restore = function(archivedBlock)
{
    var self = this;
    self.value(archivedBlock.value);
    self.tags(archivedBlock.tags);
    self.source = archivedBlock.source;
};

var ExtendableCollection = function (params)
{
    var self = this;
    Extendable.call(self, params);

    var children = params.block.children;
    if(children != undefined && children.constructor !== Array) {
        children = Object.keys(children).map(function (key) {return children[key]});
    }
    self.children = ko.observableArray(children);
};

ExtendableCollection.prototype = Object.create(Extendable.prototype);
ExtendableCollection.prototype.constructor = ExtendableCollection;

ExtendableCollection.prototype.update = function(newValue, source)
{
    var self = this;
    var save = true;
    var children = [];
    for (var key in newValue.children) {
        if (newValue.children.hasOwnProperty(key)) {
            var child = newValue.children[key];
            if ( ! child.hasOwnProperty('type')) {
                msg = '<p>The property "type: [block type]" is mandatory for each child in a collection block: please add that property to fix the issue. The block was not saved.</p>';
                alertDialog(msg, null, 'warning');
                save = false;

                break;
            }

            children.push(child);
        }
    }

    if (!save) {
        return false;
    }

    self.value(newValue.value);
    self.tags(newValue.tags);
    self.children(children);
    self.source = source;

    return true;
};

ExtendableCollection.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.value = self.value();
    block.tags = self.tags();
    block.children = self.children();
    block.source = self.source;

    return ko.toJSON(block);
};

ExtendableCollection.prototype.restore = function(archivedBlock)
{
    var self = this;
    self.value(archivedBlock.value);
    self.tags(archivedBlock.tags);
    self.source = archivedBlock.source;
    self.children(archivedBlock.children);
};

/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

(function($){
    "use strict";

    var activeBlock = false;
    
    var Highlighter = function(element, options) {
        this.$element    = $(element);
        this.options     = $.extend({}, Highlighter.DEFAULTS, options);
    };
    
    Highlighter.DEFAULTS = {
        elements: {
            "top" : '.rkcms-block-menu-top',
            "bottom" : '.rkcms-block-menu-bottom',
            "left" : '.rkcms-block-menu-left',
            "right" : '.rkcms-block-menu-right'
        },
        activeElements: {
            "top" : '.rkcms-active-block-menu-top',
            "bottom" : '.rkcms-active-block-menu-bottom',
            "left" : '.rkcms-active-block-menu-left',
            "right" : '.rkcms-active-block-menu-right'
        }
    };

    Highlighter.prototype.highlight = function ()
    {
        _render(this.$element, this.options.elements);
    };
    
    Highlighter.prototype.activate = function ()
    {
        if (activeBlock != null && activeBlock == this.$element) {

            return;
        }

        $('.rkcms-block-menu-wrapper').hide();
        $('.rkcms-block-menu-active').show();
        _activate(this, this.$element);

        activeBlock = this.$element;
    };
    
    Highlighter.prototype.resize = function (target)
    {
        _activate(this, this.$element);
    };

    Highlighter.prototype.hide = function (force)
    {
        $('.rkcms-block-menu-wrapper').hide();
    };
    
    Highlighter.prototype.close = function (force)
    {
        $('.rkcms-block-menu').hide();
        activeBlock = null;
    };

    function _activate(self, target)
    {
        _render(target, self.options.activeElements);
    }
    
    function _render(target, elements)
    {
        var position = target.offset();
        var blockWidth = target.outerWidth() + 4;
        var blockHeight = target.outerHeight() + 6;

        $(elements['top'])
            .width(blockWidth)
            .css('top', position.top - 2 + 'px')
            .css('left', position.left - 2 + 'px')
            .show()
        ;

        $(elements['bottom'])
            .width(blockWidth)
            .css('top', position.top - 4 + blockHeight + 'px')
            .css('left', position.left - 2 + 'px')
            .show()
        ;

        $(elements['left'])
            .height(blockHeight)
            .css('top', position.top - 2  + 'px')
            .css('left', position.left - 4 + 'px')
            .show()
        ;

        $(elements['right'])
            .height(blockHeight)
            .css('top', position.top - 2 + 'px')
            .css('left', position.left - 2 + blockWidth + 'px')
            .show()
        ;
    }
    
    
    // HIGHLIGHTER EDITOR PLUGIN DEFINITION
    // =================================
    var old = $.fn.selectOnce;

    $.fn.highlight = function (command, options) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('rkcms.highlighter');
            var parsedOptions = $.extend({}, Highlighter.DEFAULTS, typeof options == 'object' && options);
            if (!data) {
                $this.data('rkcms.highlighter', (data = new Highlighter(this, parsedOptions)));
            }

            if (typeof command == 'string') {
                data[command]();

                if (command == 'destroy') {
                    $this.removeData('rkcms.highlighter');
                }
            }
        });
    };

    $.fn.highlight.Constructor = Highlighter;

    // HIGHLIGHTER EDITOR NO CONFLICT
    // ===========================
    $.fn.highlight.noConflict = function () {
        $.fn.highlight = old;

        return this;
    };
})(jQuery);

(function($){
    "use strict";
    
    var ListElementSelector = function(element, options){
        this.$element    = $(element);
        this.options     = $.extend({}, ListElementSelector.DEFAULTS, options);
    };
    
    ListElementSelector.DEFAULTS = {
        cssClass: null
    };

    ListElementSelector.prototype.toggle = function(){   
        if (null == this.options.cssClass) {
            return false;
        }
        
        var isActiveElementSelected = $(this.$element).hasClass(this.options.cssClass);
        this.clean();
        if (isActiveElementSelected) {
            $(this.$element).trigger("rkcms.event.list_element_deselected", []);
            
            return false;
        }
        
        $(this.$element).addClass(this.options.cssClass);
        $(this.$element).trigger("rkcms.event.list_element_selected", []);
        
        return true;
    };

    ListElementSelector.prototype.clean = function(){
        $('.' + this.options.cssClass).removeClass(this.options.cssClass);
    };


        // ELEMENTSELECTOR EDITOR PLUGIN DEFINITION
    // =================================
    var old = $.fn.selectOnce;

    $.fn.selectOnce = function (command, options){
        return this.each(function(){
            var $this = $(this);
            var selector = $this.data('rkcms.elements_selector');
            var parsedOptions = $.extend({}, ListElementSelector.DEFAULTS, typeof options == 'object' && options);
            if (!selector) {
                $this.data('rkcms.elements_selector', (selector = new ListElementSelector(this, parsedOptions)));
            }

            //selector.toggle();
            if (typeof command == 'string') {
                selector[command]();
            }
        });
    };

    $.fn.selectOnce.Constructor = ListElementSelector;

    // ELEMENTSELECTOR EDITOR NO CONFLICT
    // ===========================
    $.fn.selectOnce.noConflict = function (){
        $.fn.selectOnce = old;

        return this;
    };
})(jQuery);

(function($){
    "use strict";

    var BlocksMover = function(element, options) {
        this.$element    = $(element);
        this.options     = $.extend({}, BlocksMover.DEFAULTS, options);
    };

    BlocksMover.DEFAULTS = {
        connectWith: ".rkcms-sortable",
        placeholder: "rkcms-sortable-placeholder",
        cursor: 'pointer',
        cursorAt: { cursor: "move", top: 10, left: 10 },
        receive: function(event, ui)
        {
            _moveBlockToAnotherSlot(ui, this);
        },
        start: function(event, ui)
        {
            _startMoving(ui, this);
        },
        stop: function(event, ui)
        {
            _moveBlockToSameSlot(ui, this);
        }
    };

    BlocksMover.prototype.start = function ()
    {
        this.$element.sortable(BlocksMover.DEFAULTS);
    };

    function _startMoving(ui, sortable)
    {
        var data = ko.dataFor(ui.item[0]);
        $('body').data('rkcms-target-list', null);
        $('body').data('rkcms-sorting-element-index', _getIndex(sortable, data["name"]));
    }

    function _moveBlockToSameSlot(ui, sortable)
    {
        // Checks when dragging to another slot
        if (null !== $('body').data('rkcms-target-list')) {
            return;
        }

        // Checks when sorting on the same slot and the position is the same
        // which means that nothing has been done
        var data = ko.dataFor(ui.item[0]);
        var index = _getIndex(sortable, data["name"]);
        if($('body').data('rkcms-sorting-element-index') == index) {
            return;
        }

        // Adds the block
        var slot = _getSlot(data);
        slot.blocks.remove(data);
        slot.blocks().splice(index, 0, data);

        var url = frontcontroller + '/backend/block/move';
        var ajaxData = {
            'sourceSlot': data["slot_name"],
            'name': data["name"],
            'position': index,
            'page':  page,
            'language': language,
            'country': country
        };

        executeAjax(url, ajaxData);
    }

    function _moveBlockToAnotherSlot(ui, sortable)
    {
        $('body').data('rkcms-target-list', sortable);

        var slot = ko.dataFor(sortable);
        var targetSlotName = slot.slotName;

        // Adds the block to target slot
        var block = ko.dataFor(ui.item[0]);
        block.slotName = targetSlotName;
        block.slot_name = targetSlotName;
        var index = _getIndex(sortable, block["name"]);

        // When dragging an element to an empty slot we must refer to ko observable
        // otherwise, when adding to a not empty slot, we must refer to ko raw array
        // because, if we would refer to observable for this situation, ko refreshes
        // the slot and the dragged element is displayed twice
        if (slot.blocks().length == 0) {
            slot.blocks.splice(index, 0, block);
        }else {
            slot.blocks().splice(index, 0, block);

            // Here we must manually refresh the data-slot-name attribute
            $($(ui.item[0]).children()[0])
                .attr('data-slot-name', targetSlotName)
            ;
        }

        // Removes the block from source slot
        var sourceSlot = ko.dataFor(ui.sender[0]);
        sourceSlot.blocks.remove(block);
        
        var url = frontcontroller + '/backend/block/move';
        var ajaxData = {
            'sourceSlot': sourceSlot.slotName,
            'targetSlot': targetSlotName,
            'name': block.name,
            'position': index,
            'page':  page,
            'language': language,
            'country': country
        };

        executeAjax(url, ajaxData,
            function(response)
            {
                // Dragging a block to another slot chamges the block name, so we must update the block and
                // find the dom elememt which represents the block itself and change the data-name attribute
                // manually
                var element = $("[data-slot-name='" + targetSlotName + "'][data-name='" + block["name"] + "']");
                block.name = response.name;
                $(element).attr('data-name', block.name);
            }
        );
    }

    function _getIndex(element, name)
    {
        var index = 0;
        $(element).children().each(function(){
            var children = $(this).children();
            if($(children[0]).attr('data-name') == name) {
                return false;
            }
            index++;
        });

        return index;
    }
    
    function _getSlot(slotName)
    {
        var slot = null;
        $('.rkcms-slot').each(function(){
            slot = ko.dataFor(this);
            if (slot.slotName == slotName){
                return false;
            }
        });
        
        return slot;
    }

    // BLOCKSMOVER EDITOR PLUGIN DEFINITION
    // =================================
    var old = $.fn.blocksmover;

    $.fn.blocksmover = function (options) {
        if (null == options) {
            options = {};
        }
        
        return this.each(function () {
            var $this = $(this);
            var blocksMover = $this.data('rkcms.blocks_mover');
            if (!blocksMover) {
                $this.data('rkcms.blocks_mover', (blocksMover = new BlocksMover(this, options)));
            }

            blocksMover.start();
        });
    };

    $.fn.blocksmover.Constructor = BlocksMover;

    // BLOCKSMOVER EDITOR NO CONFLICT
    // ===========================
    $.fn.blocksmover.noConflict = function () {
        $.fn.blocksmover = old;

        return this;
    };
})(jQuery);

(function($){
    "use strict";

    $.fn.fullHeight = function (adjust) {
        if (adjust == undefined) {
            adjust = 0;
        }

        return this.each(function () {
            var $this = $(this);
            var visiblePortion = window.innerHeight||document.documentElement.clientHeight||document.body.clientHeight||0;
            var height = visiblePortion - $('.rkcms-block-attributes-title:visible').outerHeight() - $('.rkcms-block-panel:visible').outerHeight() - $('.rkcms-link-extra-panel:visible').outerHeight() - 80; // - 80 - adjust;
            $this.css('height', height + 'px');
        });
    };
})(jQuery);
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var SeoModel = function (seo)
{
    var self = this;
    DockableModel.call(self);

    var seoData = ko.utils.parseJson(seo);
    self.permalink = seoData.permalink;
    self.title = ko.observable(seoData.title);
    self.description = ko.observable(seoData.description);
    self.keywords = ko.observable(seoData.keywords);
    self.sitemapFrequency = ko.observable(seoData.sitemap_frequency);
    self.sitemapPriority = ko.observable(seoData.sitemap_priority);
    self.currentPermalink = seoData.current_permalink;
    self.changedPermalinks = seoData.changed_permalinks;
    self.toggleBlocksEditor = ko.observable(false);

    _prepareSeoData = function()
    {
        return {
            "permalink": self.permalink,
            "title": self.title,
            "description": self.description,
            "keywords": self.keywords,
            "sitemap_frequency": self.sitemapFrequency,
            "sitemap_priority": self.sitemapPriority,
            "language": language + '_' + country,
            "current_permalink": self.currentPermalink,
            "changed_permalinks": self.changedPermalinks
        }
    }
};

SeoModel.prototype = Object.create(DockableModel.prototype);
SeoModel.prototype.constructor = SeoModel;

SeoModel.prototype.toggle = function()
{
    this.toggleBlocksEditor(!this.toggleBlocksEditor());
};

SeoModel.prototype.editSeo = function()
{
    var url = frontcontroller + '/backend/page/edit';
    var data = {
        'page-name': page,
        'seo-data': _prepareSeoData()
    };

    executeAjax(url, data);
};

SeoModel.prototype.approve = function()
{
    var url = frontcontroller + '/backend/page/approve';
    var data = {
        'pageName': page,
        'seo-data': _prepareSeoData()
    };

    var message = redkitecmsDomain.frontend_confirm_seo_approved;
    confirmDialog(message, function(){
        executeAjax(url, data);
    });


};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var PagesSelector = function (params)
{
    var self = this;
    Block.call(self, params);
    self.selected = ko.observable();
    var firstNotifySkipped = false;

    self.selected.subscribe(function(newval){
        if (!firstNotifySkipped) {
            firstNotifySkipped = true;
            
            return;
        }

        self.url(newval);
        self.edit();
    });
};

PagesSelector.prototype = Object.create(Block.prototype);
PagesSelector.prototype.constructor = PagesSelector;
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

function SlotEditorModel()
{
    var self = this;
    BaseEditorModel.call(self);

    self.transactionIcon = ko.observable(false);
}

SlotEditorModel.prototype = Object.create(BaseEditorModel.prototype);
SlotEditorModel.prototype.constructor = SlotEditorModel;
SlotEditorModel.prototype.openEditor = function()
{
    blockEditorModel.closeEditor();
    BaseEditorModel.prototype.openEditor.call(this);

    this.availableBlocksPanel(true);
    this.dock();
    $('.rkcms-available-blocks').fullHeight();
};

SlotEditorModel.prototype.addBlock = function ()
{
    var self = this;
    self.transactionIcon(true);
    var blocks = self.activeModel.blocks;
    var type = $('.rkcms-available-blocks .rkcms-selected-block').attr('data-type');
    if (null == type) {
        type = "Text";
    }
    var position = 1;

    var slotName = self.activeModel.slotName;
    var url = frontcontroller + '/backend/block/add';
    var data = {
        'type': type,
        'slot': slotName,
        'position': position,
        'page':  page,
        'language': language,
        'country': country,
        'direction': "top"
    };

    executeAjax(url, data,
        function(response)
        {
            response.slot_name = slotName;
            response.is_new = true;
            blocks.splice(position, 0, response);
            self.closeEditor();

            $(document).trigger("rkcms.event.block_added", [ self, response ]);
        },
        null,
        function()
        {
            self.transactionIcon(false);
            self.closeEditor();
        }
    );
};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */


var PageModel = function (blocks, slotName)
{
    var self = this;
    HighlightableModel.call(self);

    self.slotName = slotName;
    self.blocks = ko.observableArray(blocks);
    self.transactionIcon = ko.observable(false);
    self.addBlockPanel = ko.observable(false);
    self.empty = ko.computed(function() {
        return self.blocks() == 0
    });
};

PageModel.prototype = Object.create(HighlightableModel.prototype);
PageModel.prototype.constructor = PageModel;
PageModel.prototype.highlight = function()
{
    if ( ! this.active)
    {
        return true;
    }

    HighlightableModel.prototype.highlight.call(this);
};

PageModel.prototype.showPanel = function()
{
    if ( ! this.active) {
        return true;
    }

    $(document).data('kcms-active-model', this);
    slotEditorModel
        .setModel(this)
        .openEditor()
    ;
};
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

(function($){
    "use strict";
    
    var AceEditor = function(element, options)
    {
        var self = this;
        self._element = element;
        self._options = $.extend({}, AceEditor.DEFAULTS, options);
        self._editor = null;
        self._forceEditorInit = false;
        self._timer1 = null;
        self._timer2 = null;
        self._model = null;
        self._visible = true;

        this._placeEditor = function()
        {
            window.setTimeout(function()
            {
                var width, height;
                var target = blockEditorModel.activeModel.target();
                var $target = $(target);

                if (blockEditorModel.mode() == 'inline') {
                    width = self._options.width;
                    height = self._options.height;
                    _resize(width, height);

                    $(".rkcms-blocks-editor:visible").position({
                        of: $target,
                        my: "left-4 top+4",
                        at: "left bottom",
                        collision: "none"
                    })
                    .width(width + 20);

                    return;
                }

                $(".rkcms-blocks-editor:visible").position({
                    of: $target,
                    my: "left top",
                    at: "left top",
                    collision: "none"
                })
                .width($target.width())
                .height($target.height());

                width = $target.width() - 20;
                height = $target.height() - $('.rkcms-blocks-editor-toolbar:visible').height() - 20;
                if (height < 300) {
                    height = self._options.height;
                }
                _resize(width, height);
            }, 1);
        };

        function _resize(width, height)
        {
            var $editor = $(self._element);
            $editor
                .width(width)
                .height(height)
            ;
            self._editor.resize();
        }
    };
    
    AceEditor.DEFAULTS = {
        theme: 'twilight',
        mode: 'yaml',
        width: 450,
        height: 150
    };

    AceEditor.prototype.open = function()
    {
        if(!this._forceEditorInit && this._model != null && this._model == $(document).data('rkcms-active-model')) {
            return;
        }

        $(this._element)
            .width(this._options.width)
            .height(this._options.height)
        ;
        $('.rkcms-ace-editor-error').width(this._options.width);


        if(this._editor != null) {
            this._editor.destroy();
        }

        this._model = blockEditorModel.activeModel;
        if (this._model == null) {
            return;
        }

        var self = this;
        self._editor = ace.edit(self._element);
        self._editor.setTheme("ace/theme/" + self._options.theme);
        self._editor.setFontSize(14);
        self._editor.getSession().setMode("ace/mode/" + self._options.mode);
        self._editor.getSession().setUseWrapMode(true);
        self._editor.on("change", function(event, editor) {
            window.clearTimeout(self._timer1);
            var content = editor.getValue();
            if (self._options.mode != 'yaml')
            {
                self._timer1 = window.setTimeout(_update, 500, content, content);

                return;
            }
            self._timer1 = window.setTimeout(_parseYaml, 500, content);
        });
        self._model.editor = self._editor;
        this._placeEditor(this._editor);

        function _update(content, source)
        {
            var model = self._model;
            window.clearTimeout(self._timer2);
            if (model.source != content) {
                self._forceEditorInit = true;
                if (model.update(content, source) == false){
                    return;
                }
                model.resize();
                self._placeEditor();
                self._timer2 = window.setTimeout(_save, 1500);
            }
        }

        function _parseYaml(content)
        {
            var RkCmsYamlType = new jsyaml.Type('!rkcms', {
                kind: 'sequence',
                construct: function (data) {
                    return data.map(function (string) { return 'rkcms ' + string; });
                }
            });
            var RKCMS_SCHEMA = jsyaml.Schema.create([ RkCmsYamlType ]);

            var msg = "";
            try {
                var obj = jsyaml.load(content, { schema: RKCMS_SCHEMA });
                inspect(obj, false, 10);

                _update(obj, content);
            } catch (err) {
                window.clearTimeout(self._timer2);
                msg = '<p>The yml code you entered is malformed:<br />' + err.message + '</p>';
            }

            blockEditorModel.error(msg);
        }

        function _save()
        {
            blockEditorModel.edit();
        }
    };

    AceEditor.prototype.close = function()
    {
        this._editor.destroy();
    };

    AceEditor.prototype.place = function()
    {
        this._placeEditor();
    };

    AceEditor.prototype.toggle = function()
    {
        $(".rkcms-blocks-editor:visible").toggle();
    };




    
    // YMLEDITOR EDITOR PLUGIN DEFINITION
    // ==================================
    var old = $.fn.aceEditor;

    $.fn.aceEditor = function (command, options) {
        return this.each(function () {
            var $this = $(this);

            var data = $this.data('rkcms.ace_editor');
            var parsedOptions = $.extend({}, AceEditor.DEFAULTS, typeof options == 'object' && options);

            // Rebuilds ace editor only when an editor should be opened and the model has been changed,
            // because just destroying the editor before opening it causes a wrong behavior
            if (data && blockEditorModel.isModelChanged() && command == 'open') {
                data.close();

                // Forces the plugin to rebuild the editor
                data = false;
            }

            if (!data) {
                $this.data('rkcms.ace_editor', (data = new AceEditor(this, parsedOptions)));
            }

            if (typeof command == 'string') {
                data[command]();

                if (command == 'destroy') {
                    $this.removeData('rkcms.ace_editor');
                }
            }
        });
    };

    $.fn.aceEditor.Constructor = AceEditor;

    // YMLEDITOR EDITOR NO CONFLICT
    // ============================
    $.fn.aceEditor.noConflict = function () {
        $.fn.aceEditor = old;

        return this;
    };
    
})(jQuery);
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var IconStacked = function (params)
{
    var self = this;
    ExtendableCollection.call(self, params);

    initIconStackedEditor = function(){
        $(".rkcms-ace-editor:visible").aceEditor('open', { height: '350px' });
    };
};

IconStacked.prototype = Object.create(ExtendableCollection.prototype);
IconStacked.prototype.constructor = IconStacked;
IconStacked.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    initIconStackedEditor();
};
IconStacked.prototype.doClosePanel = function(self)
{
    Block.prototype.doClosePanel(self);

    if ($(document).data('rkcms-active-model') != null) {
        initIconStackedEditor();
    }
};

ko.components.register('rkcms-icon-stacked', {
    viewModel: IconStacked,
    template: { element: 'rkcms-icon-stacked-editor' }
});


/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var Menu = function (params)
{
    var self = this;
    ExtendableCollection.call(self, params);
    self.toolbar.push("permalinks", "link-button", "icon-button", "icon-linked-button", "icon-stacked-button");

    _initMenuEditor = function(){
        $(".rkcms-ace-editor:visible").aceEditor('open', { height: '350px' });
    };
};

Menu.prototype = Object.create(ExtendableCollection.prototype);
Menu.prototype.constructor = Menu;
Menu.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)) {
        return true;
    }

    _initMenuEditor();
};

ko.components.register('rkcms-menu', {
    viewModel: Menu,
    template: { element: 'rkcms-menu-editor' }
});
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var Image = function (params)
{
    var self = this;
    Extendable.call(self, params);
    self.href = ko.observable(params.block.href);
    self.isLinked = ko.computed(function(){
        return self.href() != "";
    });    
    self.selectedImage = ko.observable('');
    self.toolbar.push("image-button", "permalinks");

    _initImageEditor = function(){
        $(".rkcms-ace-editor:visible").aceEditor('open', { width: 600 });
    };
};

Image.prototype = Object.create(Extendable.prototype);
Image.prototype.constructor = Image;
Image.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    _initImageEditor();
};
Image.prototype.doClosePanel = function(view)
{
    Block.prototype.doClosePanel(view);

    if ($(document).data('rkcms-active-model') != null) {
        _initImageEditor();
    }
};
Image.prototype.update = function(newValue, source)
{
    var self = this;
    self.value(newValue.value);

    if (newValue.tags.src != "") {
        newValue.tags["data-src"] = "";
        $(self.target())
            .css("width", "auto")
            .css("height", "auto")
        ;
    }

    if (newValue.tags.src == "") {
        newValue.tags["data-src"] = "holder.js/260x180";

    }

    self.tags(newValue.tags);
    self.href(newValue.href);
    self.source = source;

    RunHolder();
    self.resize();
    window.setTimeout(function(){
        $(".rkcms-ace-editor:visible").aceEditor('place');
    }, 100);
};

Image.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.value = self.value();
    block.tags = self.tags();
    block.href = self.href();
    block.source = self.source;

    return ko.toJSON(block);
};

$(document).on("rkcms.event.block_added", function(){
    RunHolder();
});

ko.components.register('rkcms-image', {
    viewModel: Image,
    template: { element: 'rkcms-image-editor' }
});
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */
;var Text = function (params)
{
    var self = this;
    Block.call(self, params);
    self.html = ko.observable(self.block.html);
    self.highlightable = true;
    self.isActive = false;

    _save = function(element, content)
    {
        element.html(content);
        blockEditorModel.edit();
    };
    
    _init = function (element, obj)
    {
        var selector = '[data-slot-name="' + element.slotName + '"][data-name="' + element.name + '"]';

        tinymce.init({
            selector: selector,
            inline: true,
            image_advtab: true,
            convert_urls: false,
            relative_urls: true,
            menubar: false,
            extended_valid_elements: "i[*],span[class=fa-stack],span[class=glyphicon]",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code ",
                "insertdatetime media table contextmenu save"
            ],
            toolbar: "save | styleselect | bold italic | bullist numlist | link image table | code",
            link_list : frontcontroller + '/backend/page/permalinks',
            init_instance_callback : function(editor)
            {
                // Forces to display the editor
                editor.focus();
            },
            setup: function(editor) {
                editor.on("focus", function(event)
                {
                    event.stopPropagation();
                    // Saves current editor
                    $(document).data('rkcms-active-mce-editor', editor);
                    self.isActive = true;
                });
                editor.on("blur", function(event)
                {
                    // Saves when any change has been made
                    if (!editor.isNotDirty) {
                        _save(element, editor.getContent());
                    }
                });                
                editor.on("dblclick", function(event)
                {
                    if (!editor.isNotDirty) {
                        _save(element, editor.getContent());
                    }
                    
                    editor.remove();
                    blockEditorModel.closeEditor();
                });
            },
            save_onsavecallback: function(editor)
            {
                _save(element, editor.getContent());
            },
            file_browser_callback : function (id, value, type, win)
            {
                var url = frontcontroller + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderMediaConnect';
                mediaLibrary(url, function (url)
                {
                    var fieldElm = win.document.getElementById(id);
                    fieldElm.value = url.url;
                });
            }
        });
    };
};

Text.prototype = Object.create(Block.prototype);
Text.prototype.constructor = Text;
Text.prototype.startBlockEditing = function()
{
    var self = this;

    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    self.highlightable = false;
    self.isActive = true;
    _init(self, self.target());
    blockEditorModel.showEditor(false);
    $(self.target()).highlight('close');
};

Text.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.html = self.html();

    return ko.toJSON(block);
};

Text.prototype.restore = function(archivedBlock)
{
    this.html(archivedBlock.html);
};

// Highlights the block only when the editor is not active
Text.prototype.highlight = function (view, event)
{
    var self = this;
    if ( ! self.parent.active)
    {
        return true;
    }

    if (self.highlightable) {
        $(self.target()).highlight('highlight');
    }
};

ko.components.register('rkcms-text', {
    viewModel: Text,
    template: { element: 'rkcms-text-editor' }
});

/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var Link = function (params)
{
    var self = this;
    Extendable.call(self, params);

    self.insertPermalink = function(){
        self.editor.insert($('.rkcms-pages-selector:visible option:selected').val());
    };

    self.toolbar.push("permalinks");
};

Link.prototype = Object.create(Extendable.prototype);
Link.prototype.constructor = Link;

ko.components.register('rkcms-link', {
    viewModel: Link,
    template: { element: 'rkcms-link-editor' }
});
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var Icon = function (params)
{
    var self = this;
    Extendable.call(self, params);
};

Icon.prototype = Object.create(Extendable.prototype);
Icon.prototype.constructor = Icon;

ko.components.register('rkcms-icon', {
    viewModel: Icon,
    template: { element: 'rkcms-icon-editor' }
});


/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var Markdown = function (params)
{
    var self = this;
    Extendable.call(self, params);
    self.html = ko.observable(self.block.html);
    self.markdown = ko.observable(self.block.markdown);
    self.toolbar.push("image-button", "permalinks", "preview", "toggle-editor");

    _initMarkdownEditor = function()
    {
        $(self.target()).unbind("rkcms.event.in_place_preview").on("rkcms.event.in_place_preview", function(){
            Highlight();
        });

        $(".rkcms-ace-editor:visible").aceEditor('open', { mode: 'markdown', width: 650, height: 500 });
    };
};


Markdown.prototype = Object.create(Extendable.prototype);
Markdown.prototype.constructor = Markdown;
Markdown.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    _initMarkdownEditor();
};

Markdown.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.html = self.html();
    block.source = self.source;
    block.markdown = self.markdown;

    Highlight();
    self.resize();
    window.setTimeout(function(){
        $(".rkcms-ace-editor:visible").aceEditor('place');
    }, 100);

    return ko.toJSON(block);
};

Markdown.prototype.restore = function(archivedBlock)
{
    this.html(archivedBlock.html);
};

Markdown.prototype.update = function(newValue, source)
{
    var self = this;
    self.html(markdown.toHTML(source));
    self.source = source;
    self.markdown(source);
};

ko.components.register('rkcms-markdown', {
    viewModel: Markdown,
    template: { element: 'rkcms-markdown-editor' }
});



var IconLinked = function (params)
{
    var self = this;
    ExtendableCollection.call(self, params);
};

IconLinked.prototype = Object.create(ExtendableCollection.prototype);
IconLinked.prototype.constructor = IconLinked;

ko.components.register('rkcms-icon-linked', {
    viewModel: IconLinked,
    template: { element: 'rkcms-icon-linked-editor' }
});


/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var Script = function (params)
{
    var self = this;
    Extendable.call(self, params);
    self.html = ko.observable(self.block.html);
    self.isScriptExecuted = ko.observable(params.block.is_new != undefined ? false : true);

    _initScriptEditor = function(){
        $(".rkcms-ace-editor:visible").aceEditor('open', { mode: 'html' });
    };

    $(document).on("rkcms.event.blocks_editor_started", function(){
        self.isScriptExecuted(false);
    });

    $(document).on("rkcms.event.blocks_editor_stopped", function(){
        self.isScriptExecuted(true);
    });
};

Script.prototype = Object.create(Extendable.prototype);
Script.prototype.constructor = Script;
Script.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    _initScriptEditor();
};

Script.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.html = self.html();
    block.source = self.source;

    return ko.toJSON(block);
};

Script.prototype.restore = function(archivedBlock)
{
    this.html(archivedBlock.html);
};

Script.prototype.update = function(newValue, source)
{
    var self = this;
    self.html(source);
    self.source = source;
};

ko.components.register('rkcms-script', {
    viewModel: Script,
    template: { element: 'rkcms-script-editor' }
});

