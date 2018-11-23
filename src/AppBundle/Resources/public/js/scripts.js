
//inputName = :radio
function ajaxSubmitReloadNextByRadioBox(formRootClass, inputType, inputRootName, inputName, targetInputName, previousInput) {
    var $form = $("." + formRootClass);
    //businessbundle_businesscase[customerType]
    $form.on("ifChecked change", inputType + "[name='" + inputRootName + "[" + inputName + "]']", function (event) {
        console.log("CHANGE : Call by " + inputName + " for " + targetInputName + " list");
        var dataInfo = {
            name: inputName,
            type: inputType,
            value: event.target,
            params: previousInput,
            nextStep: false,
            target: [{name: targetInputName}],
            targetType: "select"
        };
        ajaxCall(dataInfo);
    });

    function ajaxCall(dataConf) {
        var data = {};
        //for radioboxes
        var $value = $(dataConf["type"] + "[name='"+inputRootName+"[" + dataConf["name"] + "]']:checked");

        //For select
        if(typeof $value.val() === "undefined"){
            $value = $(dataConf["type"] + "[name='"+inputRootName+"[" + dataConf["name"] + "]']");
        }
        var $form = $value.closest('form');
        data[$value.attr('name')] = $value.val();

        /**
         * Getting all previous chosen elements to submit again in order to keep them !! MANDATORY TO MAKE children post_submit work !!
         */
        console.log(dataConf.params);
        $.each(dataConf.params, function () {
            console.log(this);
            var $val = $(this.type + "[name='"+inputRootName+"[" + this.name + "]']:checked");
            if(typeof $val.val() === "undefined"){
                $val = $(this.type + "[name='"+inputRootName+"[" + this.name + "]']");
            }
            data[$val.attr('name')] = $val.val();
        });

        console.log(data);
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            dataType: "html", //response format
            data: data,
            beforeSend: function () {
                $('#ajax-spinner').show();
            },
            complete: function () {
                $('#ajax-spinner').hide("slow");
            },
            success: function (html) {
                console.log("Replacement by " + dataConf.name);
                // Replace current next field ...
                $.each(dataConf.target, function () {
                    var element = this;
                    $(".render-" + element.name + "-list").fadeOut("slow", function () {
                        var newContent = $(html).find(".render-" + element.name + "-list").hide();
                        $(this).replaceWith(newContent);
                        $("."+formRootClass).find("#"+inputRootName+"_" + element.name).select2({width: "100%"});
                        $(".render-" + element.name + "-list").fadeIn("slow").promise().then(function () {
                        });
                    });
                });
            }
        });
    };

}
/**
 * Hide next (children) fields because of grand-parent change
 */
function cleanChildren(parent, disabling, children){
    $.each(children, function(index, value){
        $("#"+parent+value).fadeOut(500, function(){
            $(this).empty().show();
        });
        // $("#"+parent + element.name).parent(".row").siblings("h3").addClass("hide-title");
    });
};

jQuery(document).ready(function() {

    $('.color-picker').colorpicker();
    /*
        Fullscreen background
    */
    //  $.backstretch("../bundles/app/img/blurry-ourcompany.jpg");

    /*
        Form validation
    */
    $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function() {
        $(this).removeClass('input-error');
    });

    $('.login-form').on('submit', function(e) {

        $(this).find('input[type="text"], input[type="password"], textarea').each(function(){
            if( $(this).val() == "" ) {
                e.preventDefault();
                $(this).addClass('input-error');
            }
            else {
                $(this).removeClass('input-error');
            }
        });

    });

});
