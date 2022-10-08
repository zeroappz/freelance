<?php
require_once('../includes.php');
require_once('../../plugins/banner-admanager/admin.php');
include("../footer.php");
?>

<script>
    $(function($) {
        function getsubcatToCatid() {
            var catid = $('.getsubcatToCatid').data('catid');
            if(catid == 0){
                $("#sub_category").closest('.form-group').hide();
            }else{
                $("#sub_category").closest('.form-group').show();
            }
            var selectid = $('.getsubcatToCatid').data('selectid');
            var action = $('.getsubcatToCatid').data('ajax-action');
            var data = { action: action, catid: catid, selectid : selectid };
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                success: function(result){
                    $("#sub_category").html('<option value="0"> Any Sub category</option>'+result);
                }
            });
        }

        function getcountryToState() {
            var countryid = $('.getcountryToState').data('countryid');
            if(countryid == 0){
                $("#state").closest('.form-group').hide();
                $("#city").closest('.form-group').hide();
            }else{
                $("#state").closest('.form-group').show();
            }
            var selectid = $('.getcountryToState').data('selectid');
            var action = $('.getcountryToState').data('ajax-action');
            var data = {action: action, id: countryid, selectid: selectid};
            console.log(data);
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                success: function (result) {
                    $("#state").html('<option value="0"> Any region</option>'+result);
                }
            });
        }

        function getstateToCity() {
            var stateid = $('.getstateToCity').data('stateid');
            if(stateid == 0){
                $("#city").closest('.form-group').hide();
            }else{
                $("#city").closest('.form-group').show();
            }
            var selectid = $('.getstateToCity').data('selectid');
            var action = $('.getstateToCity').data('ajax-action');
            var data = {action: action, id: stateid, selectid: selectid};
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                success: function (result) {
                    $("#city").html('<option value="0"> Any city</option>'+result);
                }
            });
        }
        getsubcatToCatid();
        getcountryToState();
        getstateToCity();
    });

    $(function()
    {
        // Init page helpers (Table Tools helper)
        App.initHelpers('select2');
    });
</script>
</body>
</html>

