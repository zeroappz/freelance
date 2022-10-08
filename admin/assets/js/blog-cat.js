jQuery(function($) {
    "use strict";
    var $new_type_popover = $('.new-blog-cat'),
        $new_type_form = $('#new-blog-cat-form'),
        $new_type_name = $('#new-cat-name');

    // Cancel button.
    $new_type_form.on('click', '#cancel-button', function (e) {
        $new_type_popover.popover('hide');
    });

    $new_type_popover.popover({
        html: true,
        placement: 'bottom',
        template: '<div class="popover" role="tooltip"><div class="popover-arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        content: $new_type_form.show().detach(),
        trigger: 'manual'
    }).on('click', function () {
        $(this).popover('toggle');
    }).on('shown.bs.popover', function () {
        // focus input
        $new_type_name.focus();
    }).on('hidden.bs.popover', function () {
        //clear input
        $new_type_name.val('');
    });

    // Save new category.
    $new_type_form.on('submit', function() {
        $('#new-type-form .confirm').addClass('bookme-progress');

        var data = $(this).serialize();


        $.post(ajaxurl+'?action=addBlogCat', data, function(response) {
            if(response != 0){
                var $data = JSON.parse(response);
                var $name = $data.name;
                var $id = $data.id;
                var slug = $data.slug;
                var appendtpl = '<div class="panel panel-default quickad-js-collapse" data-cat-id="'+$id+'"> <div class="panel-heading" role="tab" id="s_'+$id+'"> <div class="row"> <div class="col-sm-8 col-xs-10"> <div class="quickad-flexbox"> <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%"> <i class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move ui-sortable-handle" title="Reorder"></i> </div> <div class="quickad-flex-cell quickad-vertical-middle"> <a role="button" class="panel-title collapsed quickad-js-service-title" data-toggle="collapse" data-parent="#services_list"  href="#service_'+$id+'" aria-expanded="false" aria-controls="service_'+$id+'">'+$name+' </a> </div> </div> </div> <div class="col-sm-4 col-xs-2"> <div class="quickad-flexbox"> <div class="quickad-flex-cell quickad-vertical-middle text-right" style="width: 10%"><label class="css-input css-checkbox css-checkbox-default m-t-0 m-b-0"><input type="checkbox" id="checkbox'+$id+'" name="check-all" value="'+$id+'" class="service-checker"><span></span></label></div> </div> </div> </div> </div> <div id="service_'+$id+'" class="panel-collapse collapse" role="tabpanel"style="height: 0"> <div class="panel-body"> <form method="post" id="'+$id+'"> <div class="row"> <div class="col-md-6 col-sm-12"> <div class="form-group"> <label for="title_'+$id+'">Title</label> <input name="title" value="'+$name+'" id="title_'+$id+'" class="form-control" type="text"> <input name="id" value="'+$id+'" type="hidden"> </div> </div><div class="col-md-6 col-sm-12"><div class="form-group"><label for="slug_'+$id+'">Slug</label><input name="slug" value="'+slug+'" id="title_'+$id+'" class="form-control" type="text"></div></div><div class="col-md-6 col-sm-12"><div class="form-group">  <label for="status_'+$id+'">Enable/Disable</label><select name="status" id="status_'+$id+'" class="form-control"><option value="1">Enable</option><option value="0">Disable</option></select></div> </div>  </div> <div class="panel-footer"><button type="button" class="btn btn-lg btn-success ladda-button ajax-subcat-edit" data-style="zoom-in" data-spinner-size="40" onclick="editBlogCat('+$id+');"><span class="ladda-label">Save</span></button> <button class="btn btn-lg btn-default js-reset" type="reset">Reset </button> </div> </form> </div> </div> </div>';
                $('#services_list').append(appendtpl);
                quickadAlert({success: ['Successfully created.']});
                $('#new-type-form .confirm').removeClass('bookme-progress');
                $new_type_popover.popover('hide');
            }else{
                quickadAlert({error: ['Problem in saving, Please try again.']});
                $('#new-type-form .confirm').removeClass('bookme-progress');
                $new_type_popover.popover('hide');
            }

        });
        return false;
    });

    // Services list delegated events.
    $('#quickad-services-wrapper').on('click', '#quickad-delete', function(e) {
        if (confirm('Are you sure?')) {
            $('#quickad-delete').addClass('bookme-progress');
            var $for_delete = $('.service-checker:checked'),
                data = {},
                services = [],
                $panels = [];

            $for_delete.each(function(){
                var panel = $(this).parents('.quickad-js-collapse');
                $panels.push(panel);
                services.push(this.value);
            });
            data['ids[]'] = services;
            $.post(ajaxurl+'?action=delBlogCat', data, function(response) {
                if(response != 0) {
                    $.each($panels.reverse(), function (index) {
                        $(this).delay(500 * index).fadeOut(200, function () {
                            $(this).remove();
                        });
                    });
                    quickadAlert({success: ['Successfully deleted.']});
                }else{
                    quickadAlert({error: ['Problem in deleting, Please try again.']});
                }
                $('#quickad-delete').removeClass('bookme-progress');
            });
        }
    });


    function makeSortable() {

        var $services = $('#services_list'),
            fixHelper = function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            };
        $services.sortable({
            helper : fixHelper,
            axis   : 'y',
            handle : '.quickad-js-handle',
            update : function( event, ui ) {
                var data = [];
                $services.children('div').each(function() {
                    data.push($(this).data('cat-id'));
                });
                $.ajax({
                    type : 'POST',
                    url  : ajaxurl,
                    data : { action: 'quickad_update_blog_cat_position', position: data },
                    success: function (response, textStatus, jqXHR) {
                        // Remove Ads item from DOM.
                        if(response != 0) {
                            quickadAlert({success: ['Order Changed Successfully.']});
                        }else{
                            quickadAlert({error: ['Problem in Reorder, Please try again.']});
                        }
                    }
                });
            }
        });

    }
    makeSortable();
});
