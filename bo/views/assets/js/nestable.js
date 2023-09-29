function saveMenu(id, el) {
	var nest = $(el).parents(".nestableList").eq(0);
	var serialize=$(nest).nestable('serialize');
	var state = $(nest).data("state");
	
	
	$.ajax({
		type: 'POST',
		url: '?controller=webmenus&action=updateOrder',
		data: {"menus":serialize, "idupdate":id, "state":state, "service":1},
		dataType: 'json',
		cache: false,
		success: function(result) {
			console.log(result);
		},
		error: function () {
			Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			return false;
		}
	});
}


var UINestable = function () {
    var runNestable = function () {
		if($(".nestableList").length>0) {
			
			$(".nestableList").each(function() {
				var maxDepth = 1; var minDepth = 0;
				if(typeof $(this).data("maxdepth") !== "undefined") {
					maxDepth = $(this).data("maxdepth");
				}
				if(typeof $(this).data("mindepth") !== "undefined") {
					minDepth = $(this).data("mindepth");
				}
				
				$(this).nestable({
					group: 1,
					maxDepth: maxDepth,
					minDepth: minDepth
				}).on('change', '.dd-item', function(e) {
					e.stopPropagation();
					var id = $(this).data('id');
					saveMenu(id, this);
				});
			});
		}
		
		$('.nestable-menu.ativos .btnAction').on('click', function (e) {
          
            var action = $(this).data('action');
            var target = $(this).data('target');
            if (action === 'expand-all') {
                $('#'+target).nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('#'+target).nestable('collapseAll');
            }
        });
		
		
        /*
		$('#nestable').nestable({
			maxItems: 2,
			maxDepth: 1,
            group: 1
        }).on('change', '.dd-item', function(e) {
			e.stopPropagation();
			var id = $(this).data('id');
			saveMenu(id);
		});	
		
        $('#nestable-menu.ativos').on('click', function (e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('#nestable2').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('#nestable2').nestable('collapseAll');
            }
        });*/
		
    };
    return {
        //main function to initiate template pages
        init: function () {
            runNestable();
        }
    };
}();

jQuery(document).ready(function() {
	UINestable.init();
});

