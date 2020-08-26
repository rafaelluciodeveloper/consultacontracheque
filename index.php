<!DOCTYPE html>

<?php
include_once 'conf/conexao.php';
$resultado = mysqli_query($conexao, "select * from Empresas");

$resultado2 = mysqli_query($conexao, "SELECT DISTINCT Ano FROM HeaderContraCheque");

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Capela Consulta Contra-Cheque</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/indexcss.css" rel="stylesheet" type="text/css"/>
        <link href="css/animate.css" rel="stylesheet" type="text/css"/>
        <link href="css/sweetalert.css" rel="stylesheet" type="text/css"/>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/sweetalert.min.js" type="text/javascript"></script>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script>
            $(function () {
                $("#modalsenha").dialog({
                    autoOpen: false,
                    height: 350,
                    width: 500,
                    modal: true,
                    show: {
                        effect: "fade",
                        duration: 500
                    },
                    hide: {
                        effect: "fade",
                        duration: 500
                    }
                });
                $("#btnmodal").click(function () {
                    $("#modalsenha").dialog("open");
                });
            });
        </script>
    </head>
    <body>
        <div class="container animated fadeInDown" id="tudo">
            <div class="well" style="text-align: center">
                <img src="img/holerite_online_logo_2.jpg" alt="" id="imglogo"/>
                <form action="actions/montar.php" method="POST" class="form-inline" id="formprincipal">
                    <label>Empresa</label>
                    <select name="codempresa" class="input-xxlarge">
                        <?php while ($linha = mysqli_fetch_assoc($resultado)) : ?>
                            <option value="<?php echo $linha['CodigoEmpresa']; ?>"><?php echo $linha['RazaoSocial']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <br/>
                    <br/>
                    <label>Cpf</label>
                    <input type="text" name="cpf" required />
                    <label>Senha</label>
                    <input type="password" name="senha" required/>
                    <br/>
                    <br/>
                    <label>Mês</label>
                    <select name="mes" required >
                        <option value="1">Janeiro</option>
                        <option value="2">Fevereiro</option>
                        <option value="3">Março</option>
                        <option value="4">Abril</option>
                        <option value="5">Maio</option>
                        <option value="6">Junho</option>
                        <option value="7">Julho</option>
                        <option value="8">Agosto</option>
                        <option value="9">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                        <option value="13">13 Salario</option>
                    </select>
                    <label>Tipo Periodo</label>
                    <select name="tipo" required >
                        <option value="1">Adt. Quinzenal</option>
                        <option value="2">Folha Mês</option>
                        <option value="3">Adt 13 Salario</option>
                        <option value="4">13 Parcela Final</option>
                    </select>
                    <label>Ano</label>
                    <select name="ano" class="input-medium" required>
                        <?php while ($linha2 = mysqli_fetch_assoc($resultado2)) : ?>
                            <option value="<?php echo $linha2['Ano']; ?>"><?php echo $linha2['Ano']; ?></option>
                        <?php endwhile; ?>
                    </select><br><br>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-search icon-white"></i>
                        Consultar</button>
                </form>
                <button class="btn btn-warning" id="btnmodal"><i class="icon-lock icon-white"></i> Alterar Senha</button>
                <br/>
                <br/>
                <p style="font-size: 12px;font-style: italic">Desenvolvido por Sistemas Padrão © - www.sistemaspadrao.com.br - Versão 1.0</p>
            </div>
            <div id="modalsenha" title="Alterar Senha">

                <form >
                    <label>CPF</label>
                    <input type="text" name="cpf" id="cpf" />
                    <label>Senha Antiga</label>
                    <input type="password" name="senhaantiga" id="senhaantiga" />
                    <label>Nova Senha</label>
                    <input type="password" name="senhanova" id="senhanova" />
                    <label>Confirma Senha</label>
                    <input type="password" name="senhaconfirma" id="senhaconfirma"/><br/>
                    <button type="button" id="btnAlterar" class="btn btn-success pull-right" ><i class="icon-ok-circle icon-white"></i> Alterar Senha</button>
                    
                </form>
            </div>
            <script src="js/confsenha.js" type="text/javascript"></script>
    </body>
</html>
