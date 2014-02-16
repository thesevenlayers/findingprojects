$(document).ready(function(){
    $("#mulidelete_btn").attr("disabled", "disabled");

    $("#master_delete_checkbox").on("click", function(){

        $(".delete_checkbox").each(function(){
            $(this).parent().toggleClass("checked");
        })
//        var checked = $(".delete_checkbox:checked");
//        if(checked.length == 0)
//        {
//            alert("disabled")
//            alert(checked.length)
//            $("#mulidelete_btn").attr("disabled", "disabled");
//        } else {
//            alert("Not disabled")
//            $("#mulidelete_btn").removeAttr("disabled");
//        }
    })

    $(".delete_checkbox").on("click", function(){
        $("#mulidelete_btn").removeAttr("disabled");
        var checked = $(".delete_checkbox:checked");
        if(checked.length == 0)
        {
            $("#mulidelete_btn").attr("disabled", "disabled");
        }
    });


    $("#mulidelete_btn").on("click", function(e){
        e.preventDefault();
        var form = $(e.target).parent();
        var inner_ids = [];
        $(".delete_checkbox").each(function(){
            if($(this).parent().hasClass("checked"))
            {
                inner_ids.push($(this).val());
            }
        });
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: {inner_ids: inner_ids}
        }).done(function(){
            for(var i = 0; i < inner_ids.length; i++)
            {
                $(".delete_checkbox[value="+inner_ids[i]+"]").parentsUntil("tbody").fadeOut(600).remove();
            }
            $("#master_delete_checkbox").remove("checked")
        })
    })
})