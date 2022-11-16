$("#frmAcceso").on('submit', function (e) {
    e.preventDefault();
    logina = $("#logina").val();
    clavea = $("#clavea").val();
    
    $.post("../controller/usuario.php?op=verificar",
        { "logina": logina, "clavea": clavea },
        function (data) {}).done(function(data) {
            if (data == 2) {
                Swal.fire("Mensaje de Error", "Credenciales Incorrectas", "error");
                 //$(location).attr("href", "home.php");
                
                }else if(data == 1){
                
                    $(location).attr("href", "./escritorio.php");
                }else{
                    Swal.fire("Mensaje de Error", "Ocurrio un error en el sistema", "error");
                }
            })
            .fail(function(error) {
                    Swal.fire("Mensaje de Error", "Ocurrio un error en el sistema", "error");
            })
        
        ;
}) 
