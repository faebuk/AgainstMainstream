jQuery&&function(c){function d(b){if((b=b?c(b.target).parents().addBack():null)&&b.is(".dropdown"))if(b.is(".dropdown-menu")){if(!b.is("A"))return}else return;c(document).find(".dropdown:visible").each(function(){var a=c(this);a.hide().removeData("dropdown-trigger").trigger("hide",{dropdown:a})});c(document).find(".dropdown-open").removeClass("dropdown-open")}function f(){var b=c(".dropdown:visible").eq(0),a=b.data("dropdown-trigger"),e=a?parseInt(a.attr("data-horizontal-offset")||0,10):null,d=a?
parseInt(a.attr("data-vertical-offset")||0,10):null;0!==b.length&&a&&b.css({left:b.hasClass("dropdown-anchor-right")?a.offset().left-(b.outerWidth()-a.outerWidth())+e:a.offset().left+e,top:a.offset().top+a.outerHeight()+d})}c.extend(c.fn,{dropdown:function(b,a){switch(b){case "hide":return d(),c(this);case "attach":return c(this).attr("data-dropdown",a);case "detach":return d(),c(this).removeAttr("data-dropdown");case "disable":return c(this).addClass("dropdown-disabled");case "enable":return d(),
c(this).removeClass("dropdown-disabled")}}});c(document).on("click.dropdown","[data-dropdown]",function(b){var a=c(this),e=c(a.attr("data-dropdown")),g=a.hasClass("dropdown-open");a!==b.target&&c(b.target).hasClass("dropdown-ignore")||(b.preventDefault(),b.stopPropagation(),d(),!g&&!a.hasClass("dropdown-disabled")&&(a.addClass("dropdown-open"),e.data("dropdown-trigger",a).show(),f(),e.trigger("show",{dropdown:e,trigger:a})))});c(document).on("click.dropdown",d);c(window).on("resize",f)}(jQuery);