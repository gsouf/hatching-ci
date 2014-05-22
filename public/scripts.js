$(function(){

    $("[data-rebuild]").click(function(){

        $.post("/build/" + ($(this).attr("data-rebuild"))).always(function(){
            setTimeout(function(){location.reload();},500);
        });

    });

});