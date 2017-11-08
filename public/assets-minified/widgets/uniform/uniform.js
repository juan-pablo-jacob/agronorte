!function(a,b){"use strict";function c(a){var b=Array.prototype.slice.call(arguments,1);return a.prop?a.prop.apply(a,b):a.attr.apply(a,b)}function d(a,b,c){var d,e;for(d in c)c.hasOwnProperty(d)&&(e=d.replace(/ |$/g,b.eventNamespace),a.bind(e,c[d]))}function e(a,b,c){d(a,c,{focus:function(){b.addClass(c.focusClass)},blur:function(){b.removeClass(c.focusClass),b.removeClass(c.activeClass)},mouseenter:function(){b.addClass(c.hoverClass)},mouseleave:function(){b.removeClass(c.hoverClass),b.removeClass(c.activeClass)},"mousedown touchbegin":function(){a.is(":disabled")||b.addClass(c.activeClass)},"mouseup touchend":function(){b.removeClass(c.activeClass)}})}function f(a,b){a.removeClass(b.hoverClass+" "+b.focusClass+" "+b.activeClass)}function g(a,b,c){c?a.addClass(b):a.removeClass(b)}function h(a,b,c){var d="checked",e=b.is(":"+d);b.prop?b.prop(d,e):e?b.attr(d,d):b.removeAttr(d),g(a,c.checkedClass,e)}function i(a,b,c){g(a,c.disabledClass,b.is(":disabled"))}function j(a,b,c){switch(c){case"after":return a.after(b),a.next();case"before":return a.before(b),a.prev();case"wrap":return a.wrap(b),a.parent()}return null}function k(b,d,e){var f,g,h;return e||(e={}),e=a.extend({bind:{},divClass:null,divWrap:"wrap",spanClass:null,spanHtml:null,spanWrap:"wrap"},e),f=a("<div />"),g=a("<span />"),d.autoHide&&b.is(":hidden")&&"none"===b.css("display")&&f.hide(),e.divClass&&f.addClass(e.divClass),d.wrapperClass&&f.addClass(d.wrapperClass),e.spanClass&&g.addClass(e.spanClass),h=c(b,"id"),d.useID&&h&&c(f,"id",d.idPrefix+"-"+h),e.spanHtml&&g.html(e.spanHtml),f=j(b,f,e.divWrap),g=j(b,g,e.spanWrap),i(f,b,d),{div:f,span:g}}function l(b,c){var d;return c.wrapperClass?(d=a("<span />").addClass(c.wrapperClass),d=j(b,d,"wrap")):null}function m(){var b,c,d,e;return e="rgb(120,2,153)",c=a('<div style="width:0;height:0;color:'+e+'">'),a("body").append(c),d=c.get(0),b=window.getComputedStyle?window.getComputedStyle(d,"").color:(d.currentStyle||d.style||{}).color,c.remove(),b.replace(/ /g,"")!==e}function n(b){return b?a("<span />").text(b).html():""}function o(){return navigator.cpuClass&&!navigator.product}function p(){return"undefined"!=typeof window.XMLHttpRequest}function q(a){var b;return a[0].multiple?!0:(b=c(a,"size"),b&&!(1>=b))}function r(){return!1}function s(a,b){var c="none";d(a,b,{"selectstart dragstart mousedown":r}),a.css({MozUserSelect:c,msUserSelect:c,webkitUserSelect:c,userSelect:c})}function t(a,b,c){var d=a.val();""===d?d=c.fileDefaultHtml:(d=d.split(/[\/\\]+/),d=d[d.length-1]),b.text(d)}function u(a,b,c){var d,e;for(d=[],a.each(function(){var a;for(a in b)Object.prototype.hasOwnProperty.call(b,a)&&(d.push({el:this,name:a,old:this.style[a]}),this.style[a]=b[a])}),c();d.length;)e=d.pop(),e.el.style[e.name]=e.old}function v(a,b){var c;c=a.parents(),c.push(a[0]),c=c.not(":visible"),u(c,{visibility:"hidden",display:"block",position:"absolute"},b)}function w(a,b){return function(){a.unwrap().unwrap().unbind(b.eventNamespace)}}var x=!0,y=!1,z=[{match:function(a){return a.is("a, button, :submit, :reset, input[type='button']")},apply:function(a,b){var g,h,j,l,m;return h=b.submitDefaultHtml,a.is(":reset")&&(h=b.resetDefaultHtml),l=a.is("a, button")?function(){return a.html()||h}:function(){return n(c(a,"value"))||h},j=k(a,b,{divClass:b.buttonClass,spanHtml:l()}),g=j.div,e(a,g,b),m=!1,d(g,b,{"click touchend":function(){var b,d,e,f;m||a.is(":disabled")||(m=!0,a[0].dispatchEvent?(b=document.createEvent("MouseEvents"),b.initEvent("click",!0,!0),d=a[0].dispatchEvent(b),a.is("a")&&d&&(e=c(a,"target"),f=c(a,"href"),e&&"_self"!==e?window.open(f,e):document.location.href=f)):a.click(),m=!1)}}),s(g,b),{remove:function(){return g.after(a),g.remove(),a.unbind(b.eventNamespace),a},update:function(){f(g,b),i(g,a,b),a.detach(),j.span.html(l()).append(a)}}}},{match:function(a){return a.is(":checkbox")},apply:function(a,b){var c,g,j;return c=k(a,b,{divClass:b.checkboxClass}),g=c.div,j=c.span,e(a,g,b),d(a,b,{"click touchend":function(){h(j,a,b)}}),h(j,a,b),{remove:w(a,b),update:function(){f(g,b),j.removeClass(b.checkedClass),h(j,a,b),i(g,a,b)}}}},{match:function(a){return a.is(":file")},apply:function(b,g){function h(){t(b,n,g)}var l,m,n,p;return l=k(b,g,{divClass:g.fileClass,spanClass:g.fileButtonClass,spanHtml:g.fileButtonHtml,spanWrap:"after"}),m=l.div,p=l.span,n=a("<span />").html(g.fileDefaultHtml),n.addClass(g.filenameClass),n=j(b,n,"after"),c(b,"size")||c(b,"size",m.width()/10),e(b,m,g),h(),o()?d(b,g,{click:function(){b.trigger("change"),setTimeout(h,0)}}):d(b,g,{change:h}),s(n,g),s(p,g),{remove:function(){return n.remove(),p.remove(),b.unwrap().unbind(g.eventNamespace)},update:function(){f(m,g),t(b,n,g),i(m,b,g)}}}},{match:function(a){if(a.is("input")){var b=(" "+c(a,"type")+" ").toLowerCase(),d=" color date datetime datetime-local email month number password search tel text time url week ";return d.indexOf(b)>=0}return!1},apply:function(a,b){var d,f;return d=c(a,"type"),a.addClass(b.inputClass),f=l(a,b),e(a,a,b),b.inputAddTypeAsClass&&a.addClass(d),{remove:function(){a.removeClass(b.inputClass),b.inputAddTypeAsClass&&a.removeClass(d),f&&a.unwrap()},update:r}}},{match:function(a){return a.is(":radio")},apply:function(b,g){var j,l,m;return j=k(b,g,{divClass:g.radioClass}),l=j.div,m=j.span,e(b,l,g),d(b,g,{"click touchend":function(){a.uniform.update(a(':radio[name="'+c(b,"name")+'"]'))}}),h(m,b,g),{remove:w(b,g),update:function(){f(l,g),h(m,b,g),i(l,b,g)}}}},{match:function(a){return!(!a.is("select")||q(a))},apply:function(b,c){var g,h,j,l;return c.selectAutoWidth&&v(b,function(){l=b.width()}),g=k(b,c,{divClass:c.selectClass,spanHtml:(b.find(":selected:first")||b.find("option:first")).html(),spanWrap:"before"}),h=g.div,j=g.span,c.selectAutoWidth?v(b,function(){u(a([j[0],h[0]]),{display:"block"},function(){var a;a=j.outerWidth()-j.width(),h.width(l+a),j.width(l)})}):h.addClass("fixedWidth"),e(b,h,c),d(b,c,{change:function(){j.html(b.find(":selected").html()),h.removeClass(c.activeClass)},"click touchend":function(){var a=b.find(":selected").html();j.html()!==a&&b.trigger("change")},keyup:function(){j.html(b.find(":selected").html())}}),s(j,c),{remove:function(){return j.remove(),b.unwrap().unbind(c.eventNamespace),b},update:function(){c.selectAutoWidth?(a.uniform.restore(b),b.uniform(c)):(f(h,c),j.html(b.find(":selected").html()),i(h,b,c))}}}},{match:function(a){return!(!a.is("select")||!q(a))},apply:function(a,b){var c;return a.addClass(b.selectMultiClass),c=l(a,b),e(a,a,b),{remove:function(){a.removeClass(b.selectMultiClass),c&&a.unwrap()},update:r}}},{match:function(a){return a.is("textarea")},apply:function(a,b){var c;return a.addClass(b.textareaClass),c=l(a,b),e(a,a,b),{remove:function(){a.removeClass(b.textareaClass),c&&a.unwrap()},update:r}}}];o()&&!p()&&(x=!1),a.uniform={defaults:{activeClass:"active",autoHide:!0,buttonClass:"button",checkboxClass:"checker",checkedClass:"checked",disabledClass:"disabled",eventNamespace:".uniform",fileButtonClass:"action",fileButtonHtml:"Choose File",fileClass:"uploader",fileDefaultHtml:"No file selected",filenameClass:"filename",focusClass:"focus",hoverClass:"hover",idPrefix:"uniform",inputAddTypeAsClass:!0,inputClass:"uniform-input",radioClass:"radio",resetDefaultHtml:"Reset",resetSelector:!1,selectAutoWidth:!0,selectClass:"selector",selectMultiClass:"uniform-multiselect",submitDefaultHtml:"Submit",textareaClass:"uniform",useID:!0,wrapperClass:null},elements:[]},a.fn.uniform=function(b){var c=this;return b=a.extend({},a.uniform.defaults,b),y||(y=!0,m()&&(x=!1)),x?(b.resetSelector&&a(b.resetSelector).mouseup(function(){window.setTimeout(function(){a.uniform.update(c)},10)}),this.each(function(){var c,d,e,f=a(this);if(f.data("uniformed"))return void a.uniform.update(f);for(c=0;c<z.length;c+=1)if(d=z[c],d.match(f,b))return e=d.apply(f,b),f.data("uniformed",e),void a.uniform.elements.push(f.get(0))})):this},a.uniform.restore=a.fn.uniform.restore=function(c){c===b&&(c=a.uniform.elements),a(c).each(function(){var b,c,d=a(this);c=d.data("uniformed"),c&&(c.remove(),b=a.inArray(this,a.uniform.elements),b>=0&&a.uniform.elements.splice(b,1),d.removeData("uniformed"))})},a.uniform.update=a.fn.uniform.update=function(c){c===b&&(c=a.uniform.elements),a(c).each(function(){var b,c=a(this);b=c.data("uniformed"),b&&b.update(c,b.options)})}}(jQuery);