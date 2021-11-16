<html>
    <head>
        <title>Projeto Legenda</title>
    </head>
    <body>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="arquivo">Selecione um arquivo:</label>
            <input type="file" name="arquivo" id="arquivo" required><br/><br/>
            <label for="tempoTimelapse">Escolha um intervalo de tempo:</label>
            <input type="number" id="tempoTimelapse" name="tempoTimelapse" min="1" max="100" required><br/><br/>
            <label for="tempoTimelapse">Selecione o tipo do intervalo:</label>
            <select name="tipoTempo" id="tipoTempo" required>
                <option value="">--Selecione--</option>
                <option value="H">Hora</option>
                <option value="M">Minuto</option>
                <option value="S">Segundo</option>
            </select><br/><br/>
            
            <input type="submit" name="enviar" id="enviar" value="Enviar" >
        </form>
    </body>
</html>
<?php

use app\arquivo\Arquivo;

require_once 'vendor/autoload.php';

if(isset($_POST["enviar"])) {
  
    if (isset($_FILES['arquivo'])) {
        $arquivoSrt = $_FILES;
    } else {
        alert("Preencha o campo arquivo");
    }
    
    if(isset($_POST['tempoTimelapse']) && is_numeric($_POST['tempoTimelapse'])){
        $tempoTimelapse = $_POST['tempoTimelapse'];
    }
    
    if(isset($_POST['tipoTempo']) && is_string($_POST['tipoTempo'])){
        $tipoTempo = $_POST['tipoTempo'];
    }
  
    $arquivo = new Arquivo();
    $localArquivo = $arquivo->uploadArquivo($arquivoSrt);
  
    $arrArquivoPreparado = $arquivo->preparaConteudoArquivo($localArquivo, $tempoTimelapse, $tipoTempo);
  
    $arquivo->gravaTimelapseArquivo($localArquivo, $arrArquivoPreparado);
    
    echo "<a href='$localArquivo'>Baixar arquivo com timelapse aplicado</a>";
  
}
?>
