//reCAPTCHA
grecaptcha.ready(function () {
    grecaptcha.execute('key-goes-here', { action: 'contact' }).then(function (token) {
        var recaptchaResponse = document.getElementById('recaptchaResponse');
        recaptchaResponse.value = token;
    });
});

//query_btn onclick event
$(".query_btn").on("click", function(){
    event.preventDefault();
    if ($(".alert").length > 0) { $(".alert").remove(); }
    if ($("input").css("border", "1px solid #f2645c").length > 1 ) { $("input").css("border", "1px solid var(--lite)");  }
    $(this).html("Processing...");
    $(this).css("pointer-events","none");
    $.ajax({
        type:"POST",
        url:"root/",
        data:$(this).parent().parent().serialize() + "&FormType=Query&recaptcha_response="+recaptchaResponse.value,
        success:function(result){
            $(".query_btn").html("Send Query");
            $(".query_btn").css("pointer-events","auto");
            if (result.match(/notice:/g)){
                if (result.match(/gender/g)){
                    popup("failure: Choose your gender");
                } else {
                    $("input[name="+result.replace(/notice:/g," ")+"]").css("border","1px solid #f2645c");
                    $("input[name="+result.replace(/notice:/g," ")+"]").parent().append('<sub class="alert">Required</sub>');
                    popup("failure: Details required");
                }
            } else {
                popup(result);
            }
        }
    });
});



//Popup messages
function popup(message){
    var popup = document.createElement("div");
    popup.setAttribute("id","popup");
    popup.setAttribute("class","show");
    if (message.match(/success:/g)){ 
        popup.innerHTML = message.replace(/success:/g,"<i class='fa fa-check-circle-o'></i>");
        popup.classList.add("success");
    } else {
        popup.innerHTML = message.replace(/failure:/g,"<i class='fa fa-times-circle-o'></i>");
        popup.classList.add("failure");
    }
    document.body.appendChild(popup);
    setTimeout(function(){
            popup.className = popup.className.replace("show", "");
            popup.remove();
            }, 5000);
    return 1;
}


