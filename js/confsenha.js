$(document).ready(function () {

    $("#btnAlterar").click(function () {

        var senhanova = $('#senhanova').val();
        var senhaconfirma = $('#senhaconfirma').val();

        if (senhanova !== senhaconfirma) {
            alert('Nova senha e confimação de senha diferentes,ambas devem ser identicas.');
        } else {
            $.ajax({
                type: "POST",
                url: "http://consultacapela.sistemaspadrao.kinghost.net/actions/mudarsenha.php",
                data: {'cpf': $('#cpf').val(), 'senhaantiga': $('#senhaantiga').val(), 'senhanova': $('#senhanova').val()},
                dataType: "json",
                success: function (json) {
                    console.log('Teste' + json);
                    swal(json);
                    $('#senhanova').val('');
                    $('#senhaantiga').val('');
                    $('#cpf').val('');
                    $('#senhaconfirma').val('');
                }
            });
        }

    });

});