<?php
namespace app\tests;

use PHPUnit\Framework\TestCase;
use app\arquivo\Arquivo;

class ArquivoTest extends TestCase{
    
    public function testeConverteArquivoEmArray(): void
    {
        $localArquivo = "../app/uploads/legenda.srt";
        
        $arq = new Arquivo;
        $this->assertIsArray(
            $arq->converteArquivoEmArray($localArquivo)
        );
    }
    
    public function testePreparaConteudoArquivo(): void
    {
        $localArquivo = "../app/uploads/legenda.srt";
        $tempoTimelapse = 1;
        $tipoTempo = "S";
        
        $arq = new Arquivo;
        $this->assertIsArray(
            $arq->preparaConteudoArquivo($localArquivo, $tempoTimelapse, $tipoTempo)
        );
    }
    
    public function testeAdicionaTimeLapse(): void
    {
        $tempoLegenda = "00:01:48";
        $tempoTimelapse = 1;
        $tipoTempo = "M";
        
        $arq = new Arquivo;
        $this->assertEquals(
            (new \DateTime($tempoLegenda))->add(new \DateInterval('PT0H'.$tempoTimelapse.'M0S'))->format('H:i:s'),
            $arq->adicionaTimelapse($tempoLegenda, $tempoTimelapse, $tipoTempo)
        );
    }
    
}
