<?php
namespace app\arquivo;

class Arquivo {
    
    /**
     * Método reponsável pelo Upload do Arquivo SRT
     * 
     * @param string $arquivo 
     * 
     * @return string Caminho e nome do arquivo que foi enviado
     * @return bool Retorna false em caso do Upload não ser executado
     */
    public function uploadArquivo($arquivo) {
        
        $diretorio = "app/uploads/";
        $diretorioArquivo = $diretorio . basename($arquivo["arquivo"]["name"]);

        if (move_uploaded_file($arquivo["arquivo"]["tmp_name"], $diretorioArquivo)) {
            
            return $diretorioArquivo;
        } else {
            
            return false;
        }
    }
    
    /**
     * Método reponsável por converter o arquivo em array
     * 
     * @param string $localArquivo Caminho do arquivo após upload
     * 
     * @return array Conteúdo do arquivo convertido em Array
     */
    public function converteArquivoEmArray($localArquivo) {
        
        $linhas = file($localArquivo);
        
        return $linhas;
    }
    
    /**
     * Método reponsável por estruturar o conteúdo do arquivo e devolve-lo no 
     * formado de array mas com o timelapse já adicionado
     * 
     * @param string $localArquivo Caminho do arquivo após upload
     * @param string $tempoTimelapse Intervalo de tempo escolhido para o timelapse
     * @param string $tipoTempo Tipo do intervalor Hora, Minuto ou Segundo
     * 
     * @return array Conteúdo do arquivo com timelapse aplicado
     */
    public function preparaConteudoArquivo($localArquivo, $tempoTimelapse, $tipoTempo) {
        
        $arrArquivoConvertido = $this->converteArquivoEmArray($localArquivo);

        foreach ($arrArquivoConvertido as $key => $value) {

            if (str_contains($value, '-->')) {
                $exp = explode(' --> ', $value);

                foreach ($exp as $tempo) {
                    $explodeTempo = explode(',', $tempo);
                    $tempoLegenda = $this->adicionaTimelapse($explodeTempo[0], $tempoTimelapse, $tipoTempo);
                    $implodeTempo[] = implode(',', [$tempoLegenda, $explodeTempo[1]]);
                }

                $montaTempo = implode(" --> ", $implodeTempo); 
                unset($implodeTempo);

                $arrArquivoPreparado[$key] = $montaTempo;

            } else {
                $arrArquivoPreparado[$key] = $value;
            }
        }
        
        return $arrArquivoPreparado;
    }
    
    /**
     * Método reponsável por estruturar o conteúdo do arquivo e devolve-lo no 
     * formado de array mas com o timelapse já adicionado
     * 
     * @param string $tempoLegenda Dado da legenda no qual será acrescido o timelapse
     * @param string $tempoTimelapse Intervalo de tempo escolhido para o timelapse
     * @param string $tipoTempo Tipo do intervalor Hora, Minuto ou Segundo
     * 
     * @return datetime Dado da legenda já acrescido do timelapse em formato de tempo
     */
    public function adicionaTimelapse($tempoLegenda, $tempoTimelapse, $tipoTempo){
        
        $tempoLegendaDatetime = new \DateTime($tempoLegenda);
        switch ($tipoTempo) {
            case "H":
                $intervalo = 'PT'.$tempoTimelapse.'H0M0S';
                break;
            case "M":
                $intervalo = 'PT0H'.$tempoTimelapse.'M0S';
                break;
            case "S":
                $intervalo = 'PT0H0M'.$tempoTimelapse.'S';
                break;
        }
        $tempoLegendaDatetime->add(new \DateInterval($intervalo));
        
        return $tempoLegendaDatetime->format('H:i:s');
    }
    
    /**
     * Método reponsável por estruturar o conteúdo do arquivo e devolve-lo no 
     * formado de array mas com o timelapse já adicionado
     * 
     * @param string $localArquivo Caminho do arquivo após upload
     * @param string $arrArquivoPreparado Conteúdo do arquivo com timelapse aplicado
     * 
     * @return bool Retorna true se o arquivo foi gravado e false se não
     */
    public function gravaTimelapseArquivo($localArquivo, $arrArquivoPreparado){
        
        $arquivoAberto = fopen($localArquivo, 'w');

        foreach ($arrArquivoPreparado as $value){
            fwrite($arquivoAberto, $value);
        }
        
        if(fclose($arquivoAberto)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    
}
