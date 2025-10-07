jQuery(document).ready(function($){

    // detects the start of an ajax request being made
    $(document).on("sf:ajaxstart", ".searchandfilter", function(){
        $(".loading").removeClass("hidden");
        $(".container").addClass("blur-xs");
    });


    // detects the end of an ajax request being made
    $(document).on("sf:ajaxfinish", ".searchandfilter", function(){
        $(".loading").addClass("hidden");
        $(".container").removeClass("blur-xs");
    });

});
