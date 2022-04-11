jQuery(".listofUsers").click(function () {
    var user_id = jQuery(this).attr("data-id");
    var userObj = {
        init: function () {
            userObj.callAjaxMethod();
        },
        callAjaxMethod:function () {
            var data = {
                'userId': user_id
            };

            jQuery.ajax({
                url: users.ajaxurl,
                type: 'POST',
                data: data,
                success: function (response) {
                    jQuery(".userDetailSection").html(response);
                }
            });
        }
    }
    userObj.init();
});