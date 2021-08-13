$('#searchfor').on("change", function() {
    if($('#searchfor :selected').text() === "Groups"){
        $("#platform_div").show();
        $("#type_div").show();
        $("#privacy_div").show();
    } else {
        $("#platform_div").hide();
        $("#type_div").hide();
        $("#privacy_div").hide();                  
    }
});