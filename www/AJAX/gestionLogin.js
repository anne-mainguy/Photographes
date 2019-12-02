$(function(){
    
    $('#submitLogin').click(function(e){
        e.preventDefault();
        $.post(
            $('#formLogin').attr('data-traitement'),
            {
                username : $('#pseudo').val(),
                password : $('#mdp').val()
            },
            function(data){
                if(data == 'success'){
                    $('#mess_error_login').addClass('invisibility');
                    document.location.href = "/CONTROLLER/monEspace.php";
                }else{ 
                    $('#mess_error_login').removeClass('invisibility');
                }
            },
            'text'
        );
    });
    
});
