!function(a){"use strict";var b=function(){var b={bcClass:"sf-breadcrumb",menuClass:"sf-js-enabled",anchorClass:"sf-with-ul",menuArrowClass:"sf-arrows"},c=function(){var b=/iPhone|iPad|iPod/i.test(navigator.userAgent);return b&&a(window).load(function(){a("body").children().on("click",a.noop)}),b}(),d=function(){var a=document.documentElement.style;return"behavior"in a&&"fill"in a&&/iemobile/i.test(navigator.userAgent)}(),e=function(a,c){var d=b.menuClass;c.cssArrows&&(d+=" "+b.menuArrowClass),a.toggleClass(d)},f=function(c,d){return c.find("li."+d.pathClass).slice(0,d.pathLevels).addClass(d.hoverClass+" "+b.bcClass).filter(function(){return a(this).children(d.popUpSelector).hide().show().length}).removeClass(d.pathClass)},g=function(a){a.children("a").toggleClass(b.anchorClass)},h=function(a){var b=a.css("ms-touch-action");b="pan-y"===b?"auto":"pan-y",a.css("ms-touch-action",b)},i=function(b,e){var f="li:has("+e.popUpSelector+")";a.fn.hoverIntent&&!e.disableHI?b.hoverIntent(k,l,f):b.on("mouseenter.superfish",f,k).on("mouseleave.superfish",f,l);var g="MSPointerDown.superfish";c||(g+=" touchend.superfish"),d&&(g+=" mousedown.superfish"),b.on("focusin.superfish","li",k).on("focusout.superfish","li",l).on(g,"a",e,j)},j=function(b){var c=a(this),d=c.siblings(b.data.popUpSelector);d.length>0&&d.is(":hidden")&&(c.one("click.superfish",!1),"MSPointerDown"===b.type?c.trigger("focus"):a.proxy(k,c.parent("li"))())},k=function(){var b=a(this),c=o(b);clearTimeout(c.sfTimer),b.siblings().superfish("hide").end().superfish("show")},l=function(){var b=a(this),d=o(b);c?a.proxy(m,b,d)():(clearTimeout(d.sfTimer),d.sfTimer=setTimeout(a.proxy(m,b,d),d.delay))},m=function(b){b.retainPath=a.inArray(this[0],b.$path)>-1,this.superfish("hide"),this.parents("."+b.hoverClass).length||(b.onIdle.call(n(this)),b.$path.length&&a.proxy(k,b.$path)())},n=function(a){return a.closest("."+b.menuClass)},o=function(a){return n(a).data("sf-options")};return{hide:function(b){if(this.length){var c=this,d=o(c);if(!d)return this;var e=d.retainPath===!0?d.$path:"",f=c.find("li."+d.hoverClass).add(this).not(e).removeClass(d.hoverClass).children(d.popUpSelector),g=d.speedOut;b&&(f.show(),g=0),d.retainPath=!1,d.onBeforeHide.call(f),f.stop(!0,!0).animate(d.animationOut,g,function(){var b=a(this);d.onHide.call(b)})}return this},show:function(){var a=o(this);if(!a)return this;var b=this.addClass(a.hoverClass),c=b.children(a.popUpSelector);return a.onBeforeShow.call(c),c.stop(!0,!0).animate(a.animation,a.speed,function(){a.onShow.call(c)}),this},destroy:function(){return this.each(function(){var c,d=a(this),f=d.data("sf-options");return f?(c=d.find(f.popUpSelector).parent("li"),clearTimeout(f.sfTimer),e(d,f),g(c),h(d),d.off(".superfish").off(".hoverIntent"),c.children(f.popUpSelector).attr("style",function(a,b){return b.replace(/display[^;]+;?/g,"")}),f.$path.removeClass(f.hoverClass+" "+b.bcClass).addClass(f.pathClass),d.find("."+f.hoverClass).removeClass(f.hoverClass),f.onDestroy.call(d),void d.removeData("sf-options")):!1})},init:function(c){return this.each(function(){var d=a(this);if(d.data("sf-options"))return!1;var j=a.extend({},a.fn.superfish.defaults,c),k=d.find(j.popUpSelector).parent("li");j.$path=f(d,j),d.data("sf-options",j),e(d,j),g(k),h(d),i(d,j),k.not("."+b.bcClass).superfish("hide",!0),j.onInit.call(this)})}}}();a.fn.superfish=function(c,d){return b[c]?b[c].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof c&&c?a.error("Method "+c+" does not exist on jQuery.fn.superfish"):b.init.apply(this,arguments)},a.fn.superfish.defaults={popUpSelector:"ul,.sf-mega",hoverClass:"sfHover",pathClass:"overrideThisToUse",pathLevels:1,delay:800,animation:{opacity:"show"},animationOut:{opacity:"hide"},speed:"normal",speedOut:"fast",cssArrows:!0,disableHI:!1,onInit:a.noop,onBeforeShow:a.noop,onShow:a.noop,onBeforeHide:a.noop,onHide:a.noop,onIdle:a.noop,onDestroy:a.noop},a.fn.extend({hideSuperfishUl:b.hide,showSuperfishUl:b.show})}(jQuery);