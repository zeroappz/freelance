<div class="preloader"><div class="cssload-speeding-wheel"></div></div>
<script src="../assets/js/plugins/tinymce/tinymce.min.js"></script>
<header class="slidePanel-header overlay">
    <div class="overlay-panel overlay-background vertical-align">
        <div class="service-heading">
            <h2>Add Page</h2>
        </div>
        <div class="slidePanel-actions">
            <div class="btn-group-flat">
                <button type="button" class="btn btn-floating btn-warning btn-sm waves-effect waves-float waves-light margin-right-10" id="post_sidePanel_data"><i class="icon ion-android-done" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-pure btn-inverse slidePanel-close icon ion-android-close font-size-20" aria-hidden="true"></button>
            </div>
        </div>
    </div>
</header>
<div class="slidePanel-inner">
    <div class="panel-body">
        <!-- /.row -->
        <div class="row">
            <div class="col-sm-12">

                <div class="white-box">
                    <div id="post_error"></div>
                    <form name="form2"  class="form form-horizontal" method="post" data-ajax-action="addStaticPage" id="sidePanel_form">
                        <div class="form-body">

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Slug:</label>
                                    <input name="slug" type="text" class="form-control" placeholder="Enter Page ID">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Name:</label>
                                    <input name="name" type="text" class="form-control"  placeholder="Enter Page Title">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Title:</label>
                                    <input name="title" type="text" class="form-control"  placeholder="Enter Page Title">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Type:</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="0" selected>Standard</option>
                                        <option value="1">Logged In Only</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="css-input switch switch-sm switch-success">
                                        <strong>Active</strong> <input  name="active" type="checkbox" value="1" checked=""/><span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Content:</label>
                                    <textarea name="content" rows="6" class="form-control" id="pageContent" placeholder="Enter Page Content"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="submit">

                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</div>

<script>
    $(document).ready(function() {
        tinymce.init({
            selector: '#pageContent',
            plugins: 'quickbars image lists code table codesample',
            toolbar: 'blocks | forecolor backcolor | bold italic underline strikethrough | link image blockquote codesample | align bullist numlist | code ',
        });
    });
</script>





