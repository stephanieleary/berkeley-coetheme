jQuery(function($){var t={"footer-sidebar-1":3,"footer-sidebar-2":3,"footer-sidebar-3":3},e=$("#widgets-right div.widgets-sortables"),i=$("#widget-list").children(".widget"),o=function(o,s){var r=o.id;if(void 0!==t[r]){var l=$(o).sortable("toArray");$(o).toggleClass("sidebar-full",t[r]<=l.length+(s||0)),$(o).toggleClass("sidebar-morethanfull",t[r]<l.length+(s||0));var n=$("div.widgets-sortables").not(".sidebar-full");i.draggable("option","connectToSortable",n),e.sortable("option","connectWith",n)}};e.map(function(){o(this)}),e.bind("sortreceive sortremove",function(t,e){o(this)}),e.bind("sortstop",function(t,e){e.item.hasClass("deleting")&&o(this,-1)}),$("a.widget-control-remove").live("click",function(){o($(this).closest("div.widgets-sortables")[0],-1)})});