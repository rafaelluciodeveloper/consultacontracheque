<?php
header('Content-Type: text/html; charset=utf-8');

include_once '../conf/conexao.php';
include_once 'helpers.php';

if (!isset($_POST['codempresa']) && !isset($_POST['cpf']) && !isset($_POST['senha']) && !isset($_POST['mes']) && !isset($_POST['ano']) && !isset($_POST['tipo'])) {
    header("location:../index.php");
}


$codigoempresa = $_POST['codempresa'];
$cpf = soNumero($_POST['cpf']);
$senha = $_POST['senha'];
$mes = $_POST['mes'];
$ano = $_POST['ano'];
$tipo = $_POST['tipo'];




switch ($mes) {
    case '1' :
        $referencia = "Janeiro";
        break;
    case '2' :
        $referencia = "Fevereiro";
        break;
    case '3' :
        $referencia = "Março";
        break;
    case '4' :
        $referencia = "Abril";
        break;
    case '5' :
        $referencia = "Maio";
        break;
    case '6' :
        $referencia = "Junho";
        break;
    case '7' :
        $referencia = "Julho";
        break;
    case '8' :
        $referencia = "Agosto";
        break;
    case '9' :
        $referencia = "Setembro";
        break;
    case '10' :
        $referencia = "Outubro";
        break;
    case '11' :
        $referencia = "Novembro";
        break;
    case '12' :
        $referencia = "Dezembro";
        break;
}

switch ($tipo) {
    case '1':
        $reftipo = "Adiantamento Quinzenal";
        break;
    case '2':
        $reftipo = "Folha Mensal";
        break;
    case '3':
        $reftipo = "Adiantamento 13°";
        break;
    case '4':
        $reftipo = "13° Parcela Final";
        break;
}

$empresa = mysqli_fetch_assoc(mysqli_query($conexao, "select * from Empresas where CodigoEmpresa='$codigoempresa'"));

$header = mysqli_fetch_assoc(mysqli_query($conexao, "select * from HeaderContraCheque where Mes='$mes' and Ano='$ano' and TipoPeriodo='$tipo' && CodigoEmpresa='$codigoempresa'"));

$header_num = mysqli_query($conexao, "select * from HeaderContraCheque where Mes='$mes' and Ano='$ano' and TipoPeriodo='$tipo' && CodigoEmpresa='$codigoempresa'");

$hash_senha = md5($senha);

$usuario_num = mysqli_query($conexao, "select *  from TrabalhadoresSenhas where Cpf='$cpf' and Senha='$hash_senha'");

$dados_usuario = mysqli_fetch_assoc($usuario_num);

$parametro1 = $header['CodigoContraCheque'];
$parametro2 = $dados_usuario['Cpf'];

$contracheque_funcionario_num = mysqli_query($conexao, "select * from CabecalhoContraCheque where CodigoContraCheque='$parametro1' && Cpf='$parametro2'");

if (mysqli_num_rows($usuario_num) <= 0) {
    header("location:../conf/erro_login.php");
}
if (mysqli_num_rows($header_num) <= 0) {
    header("location:../conf/erro.php");
}

if (mysqli_num_rows($contracheque_funcionario_num) <= 0) {
    header("location:../conf/erro_contra_not_found.php");
}



$codigocontra = $header['CodigoContraCheque'];


$intervalo = 4;
$str_formatada = wordwrap(md5($codigocontra), $intervalo, '.', true);

$cabecalho = mysqli_fetch_assoc(mysqli_query($conexao, "select * from CabecalhoContraCheque where Cpf='$cpf' and CodigoContraCheque='$codigocontra'"));

$matricula = $cabecalho['MatriculaTrabalhador'];

$proventos_select = mysqli_query($conexao, "select * from ItensContraCheque where CodigoContraCheque='$codigocontra' and MatriculaTrabalhador='$matricula' and Tipo='1'");
$proventos = array();

$descontos_select = mysqli_query($conexao, "select * from ItensContraCheque where CodigoContraCheque='$codigocontra' and MatriculaTrabalhador='$matricula' and Tipo='2'");
$descontos = array();

$rodape = mysqli_fetch_assoc(mysqli_query($conexao, "select * from RodapeContraCheque where CodigoContraCheque='$codigocontra' and MatriculaTrabalhador='$matricula'"));
?>
<html>
    <head>
        <title>Contra Cheque</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../css/contrachequecss.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="tudo">
            <table width="70%" height="100"  bgcolor="#FFFFFF" style="border : 1px solid black;" >
                <tr>
                    <td style="width: 60px;height: 40px;" valgin="top" rowspan="2"><center><img src="../img/logo_contra_cheque.jpg" alt="" width="60px" height="60px"/></center></td>
                <td colspan="5" style="width:500px;" rowspan="2" >
                    <h4><?php echo $empresa['CodigoEmpresa'] . " - " . $empresa['RazaoSocial']; ?></h4>
                    <div style="font-family: arial;font-size: 10pt;"><?php echo $empresa['Endereco']; ?> <br>
                        <?php echo "Cnpj : " . mask($empresa['Cnpj'], '##.###.###/####-##'); ?> <br></div>
                </td>
                <td valign="top" colspan="1" style="text-align: right; font-size: 18px;font-family: Impact; width: 290px">Demonstrativo de Pagamento Salario</td>
                <tr>
                    <td  valign="middle" style="text-align: right; font-size: 15px;font-weight: bold;width: 100px"><?php echo $reftipo . " " . $referencia . "/" . $ano; ?></td>
                </tr>
            </table>
            <table  width="70%" style="border : 1px solid black;" id="padrao">
                <tr style="font-family: Arial;font-size: 10pt;">
                    <td height="17%" colspan="3">Trabalhador:  <b><?php echo $cabecalho['MatriculaTrabalhador'] . " / " . $cabecalho['NumeroRegistroTrabalhador'] . " - " . $cabecalho['NomeTrabalhador']; ?> <br></b></td>
                    <td width="17%">Cpf: <b><?php echo mask($cabecalho['Cpf'], '###.###.###-##'); ?> </b></td>
                    <td width="19%" colspan="1">Admissão:  <b><?php echo $cabecalho['DataAdmissao'] ?></b></td>
                </tr>
            </table>
            <table  width="70%" style="border : 1px solid black;" id="padrao1">
                <tr>
                    <td width="23%" height="23" colspan="1">Cargo: <?php echo $cabecalho['Cargo']; ?></td>
                    <td width="29%" height="23" colspan="4">Setor: <?php echo utf8_encode($cabecalho['Setor']); ?></td>
                    <td width="11%" height="23" colspan="1">Pis: <?php echo $cabecalho['Pis']; ?> </td>
                </tr>
            </table>
            <table width="70%" style="border : 1px solid black;" >
                <tr style="font-weight: bold;text-align: center;">
                    <td height="23" bgcolor="#CCCCCC" style="width: 40px;" id="cabecalho">Código</td>
                    <td height="23" bgcolor="#CCCCCC" style="width: 280px;text-align: left;" id="cabecalho">Descrição</td>
                    <td height="23" bgcolor="#CCCCCC" style="width: 60px;" id="cabecalho">Referência</td>
                    <td height="23" bgcolor="#CCCCCC" style="width: 100px;" id="cabecalho">Provento</td>
                    <td height="23" bgcolor="#CCCCCC" style="width: 100px;" id="cabecalho">Desconto</td>
                </tr>
            </table>
            <table width="70%" style="border-right:1px solid black;border-left: 1px solid black;background-image: url('http://consultacapela.sistemaspadrao.kinghost.net/img/back.jpg');background-size:150px 150px;background-repeat: no-repeat; background-position: center;" id="proventosdescontos">
                <?php while ($proventos = $proventos_select->fetch_assoc()) : ?>
                    <?php //foreach ($proventos as $key => $valor) : ?>
                    <tr>
                        <td style="width: 40px;" ><?php echo $proventos['CodigoVerba']; ?></td>
                        <td style="width: 280px;"><?php echo utf8_encode($proventos['Descricao']); ?></td>
                        <td style="width: 60px;"><?php echo utf8_encode($proventos['Referencia']); ?></td>
                        <td style="width: 100px;"><center><?php echo $proventos['Valor']; ?></center></td>
                    <td style="width: 100px;"></td>
                    </tr>
                <?php endwhile; ?>
                <?php //endforeach; ?>
                <?php while ($descontos = $descontos_select->fetch_assoc()) : ?>
                    <?php //foreach ($descontos as $key => $valor) : ?>
                    <tr> 
                        <td style="width: 40px;" ><?php echo $descontos['CodigoVerba'] ?></td>
                        <td style="width: 300px;" ><?php echo utf8_encode($descontos['Descricao']); ?></td>
                        <td style="width: 40px;" ><?php echo $descontos['Referencia']; ?></td>
                        <td style="width: 100px;" ></td>
                        <td style="width: 100px;" ><center><?php echo $descontos['Valor']; ?></center></td>
                    </tr>
                <?php endwhile; ?>
                <?php //endforeach; ?>
                <tr style="height: 40px;">
                    <td></td>
                </tr>
            </table>
            <table width="70%" style="border : 1px solid black;border-spacing:0 " id="padrao1" border="1">
                <tr>
                    <td colspan="4" style="" rowspan="1" valign="bottom" >
                        <?php echo utf8_encode($rodape['Mensagem1']); ?><br/>
                        <?php echo utf8_encode($rodape['Mensagem2']); ?><br/>
                        <?php echo utf8_encode($rodape['Mensagem3']); ?>
                    </td>
                    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><b><center>Total Proventos<br/> <?php echo $rodape['TotalProventos']; ?></center></b></td>
                    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF" colspan="1"><b><center>Total Descontos<br/> <?php echo $rodape['TotalDescontos']; ?></center></b></td>
                </tr>
            </table>
            <table width="70%"  style="text-align: right;border : 1px solid black;">
                <tr>
                    <td style="width: 40px;"></td>
                    <td style="width: 300px;"></td>
                    <td style="width: 40px;"></td>
                    <td style="width: 100px;"><b>Liquido ==></b></td>
                    <td style="width: 100px;"><b><center><?php echo $rodape['LiquidoReceber']; ?></center></b></td>
                </tr>
            </table>
            <table width="70%" style="border : 1px solid black;" id="cabecalho">
                <tr>
                    <td width="18%" height="20" bgcolor="#CCCCCC"><div align="center"><strong>SalárioBase</strong></div></td>
                    <td width="22%" height="20" bgcolor="#CCCCCC"><div align="center"><strong>Slr Contr.Previdência</strong></div></td>
                    <td height="20" bgcolor="#CCCCCC" colspan="2"><div align="center"><strong>Base Calc . FGTS</strong></div></td>
                    <td height="20" bgcolor="#CCCCCC" ><div align="center"><strong>Valor FGTS</strong></div></td>
                    <td height="20" bgcolor="#CCCCCC" ><div align="center"><strong>Base Calc IRRF</strong></div></td>
                </tr>
                <tr style="text-align: center;" id="padrao">
                    <td height="20" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><?php echo $rodape['SalarioBase']; ?></td>
                    <td height="20" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><?php echo $rodape['SlrContrPrevidencia']; ?></td>
                    <td height="20" bordercolor="#FFFFFF" bgcolor="#FFFFFF" colspan="2" ><?php echo $rodape['BaseCalculoFgts']; ?></td>
                    <td height="20" bordercolor="#FFFFFF" bgcolor="#FFFFFF" ><?php echo $rodape['ValorFgts']; ?></td>
                    <td height="20" bordercolor="#FFFFFF" bgcolor="#FFFFFF" ><?php echo $rodape['BaseCalculoIrpf']; ?></td>

                </tr>
            </table>
            <table width="70%" style="border : 1px solid black;">
                <tr>
                    <td colspan="6" bgcolor="#FFFFFF"  style="text-align: right;height: 50px;"><b>Em : ___/___/____ Assinatura ______________________________________</b></td>
                </tr>
            </table>
            <table width="70%" style="border : 1px solid black;">
                <tr>
                    <td colspan="6" bgcolor="#FFFFFF"  style="text-align: center;height: 50px;"><b>Código Contra-Cheque</b></td>
                    <td colspan="6" bgcolor="#FFFFFF"  style="text-align: center;height: 50px;font-family: courier"><b><?php echo $str_formatada; ?></b></td>
                </tr>
            </table>
            <div>
                <button type="button" onclick="window.print();" class="no-print">Imprimir</button>
                <a href="../index.php" class="no-print">Voltar</a>
            </div>

        </div>
    </body>
</html>

<?php
mysqli_close($conexao);


