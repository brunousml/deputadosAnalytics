<?php

namespace Tests\Builder\Presenca;

use DA\Util\Registry;

/**
 * @backupGlobals disabled
 */
class ComissaoTest extends \PHPUnit_Framework_TestCase
{
    
    private $app;
    
    /**
     *
     * @var DA\Builder\Deputado
     */
    private $builder;
    
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject 
     */
    private $scrapperMock;
    
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject 
     */
    private $repoMock;
    
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject 
     */
    private $repoLegislaturaMock;
    
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject 
     */
    private $repoDeputadoMock;
    
    protected function setUp() {
        parent::setUp();        
        $this->app      = Registry::get("app");
        
        $this->scrapperMock = $this->getMockBuilder('DA\Scrapper\Presenca\Comissao')
                                ->setConstructorArgs(array($this->app))
                                ->getMock(); 
        
        $this->repoMock = $this->getMockBuilder('DA\Repository\Presenca\Comissao')
                                ->setConstructorArgs(array($this->app))
                                ->getMock(); 
        
        $this->repoLegislaturaMock = $this->getMockBuilder('DA\Repository\Legislatura')
                                ->setConstructorArgs(array($this->app))
                                ->getMock(); 
        
        $this->repoDeputadoMock = $this->getMockBuilder('DA\Repository\Deputado')
                                ->setConstructorArgs(array($this->app))
                                ->getMock(); 
        
        $this->builder = new \DA\Builder\Presenca\Comissao($this->app,$this->scrapperMock,$this->repoMock, $this->repoLegislaturaMock, $this->repoDeputadoMock);
    }
    
    public function testAtualizarPresencas()
    {
        $mesEntrada = '1';
        $mes = str_pad($mesEntrada, 2, '0', STR_PAD_LEFT);
        $ano = date('Y');
        $dataInicio = "01/$mes/$ano";
        $dataFim = "31/$mes/$ano";
        
        $legislatura = array('id' => '1', 'numero' => '54', 'atual' => '1', 'data' => '2010-01-01');
        $this->repoLegislaturaMock->expects($this->once())
                ->method('getLegislaturaAtual')
                ->will($this->returnValue($legislatura));
        
        $deputadosBD = array(
            array('id'=> 1, 'matricula' => 1, 'nome' => strtoupper('Jaca Rato'), 'identificacao' => 1, 'numero' => 1, 'estado' => 'PA', 'partido' => 'PPPPP'),
            array('id'=> 2, 'matricula' => 2, 'nome' => strtoupper('Jaca Paladium'), 'identificacao' => 2, 'numero' => 2, 'estado' => 'PA', 'partido' => 'PPPPP'),
            //array('id'=> 3, 'matricula' => 3, 'nome' => strtoupper('Jacaré do  É o Tchan'), 'identificacao' => 3, 'numero' => 3, 'estado' => 'PA', 'partido' => 'PPPPP')
        );
        $this->repoDeputadoMock->expects($this->once())
                ->method('getDeputadosAtuais')
                ->will($this->returnValue($deputadosBD));
        
        $presencas = array( "1" =>
                        array(
                            array(
                                'deputado_id'    => 1,
                                'data'          => date('d/m/Y'),
                                'titulo'        => 'Titular - CCTCI - CIÃŠNCIA E TECNOLOGIA',
                                'tipo'          => 'Reunião Deliberativa',
                                'comportamento' => 'Presença'
                            )
                        ),
                        "2" => array(
                            array(
                                'deputado_id'    => 2,
                                'data'          => date('d/m/Y'),
                                'titulo'        => 'Titular - CCTCI - CIÃŠNCIA E TECNOLOGIA',
                                'tipo'          => 'Reunião Deliberativa',
                                'comportamento' => 'Ausência não justificada'
                            )
                        ),
                         "3" => array(
                            array(
                                'deputado_id'    => 3,
                                'data'          => date('d/m/Y'),
                                'titulo'        => 'Titular - CCTCI - CIÃŠNCIA E TECNOLOGIA',
                                'tipo'          => 'Reunião Deliberativa',
                                'comportamento' => 'Ausência justificada'
                            )
                        )
                    );

        foreach ($deputadosBD as $deputado) {

            $urlParams = array(
                'legislatura'    => $legislatura['numero'],
                'last3Matricula' => substr($deputado['matricula'], -3),
                'dataInicio'     => $dataInicio,
                'dataFim'        => $dataFim,
                'numero'         => $deputado['numero']
            );

            $this->scrapperMock->expects($this->at($deputado['id']-1))
                    ->method('getPresencas')
                    ->with($deputado['id'], $urlParams)
                    ->will($this->returnValue($presencas[$deputado['id']]));
            
            $this->repoMock->expects($this->at($deputado['id']-1))
                    ->method('savePresencas')
                    ->with($presencas[$deputado['id']])
                    ->will($this->returnValue(true));
        }
        
        $this->builder->atualizarPresencas($mes);
    }
}