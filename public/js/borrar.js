var loginForm = $("#loginform");
loginForm.submit(function(e){
    e.preventDefault();
    var formData = loginForm.serialize();
    $.ajax({
        url:'clientes',
        type:'POST',
        data:formData,
        success:function(data){
            console.log(data);
        },
        error: function (data) {
            console.log(data);
        }
    });
});
