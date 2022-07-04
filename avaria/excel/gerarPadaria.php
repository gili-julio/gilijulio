<?php 
session_start();
include_once('../../secundario/config.php');
require __DIR__.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;




//INSTÂNCIA PRINCIPAL DA PLANILHA
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load("Modelo.xlsx");

//OBTÉM A ABA ATIVA DENTRO DO ARQUIVO DO EXCEL
    $sheet = $spreadsheet->getActiveSheet();

// DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Sao_Paulo');
//DADOS INICIAIS E TEMPORAL
    $hoje = date('d/m/Y');
    $hoje2 = date('d_m_Y');
    $sheet->setCellValue('A1', 'AVARIAS PADARIA, RESTAURANTE E AÇOUGUE - '.$hoje);
//PARA SALVAR O NOME DO ARQUIVO
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
        $dataLocal = date('H_i_s', time());
        $nome = 'Avaria Destinos Dia '.$hoje2.' Hora '.$dataLocal;
    
    $sheet->setCellValue('D2', 'DESTINO');

//INSERIR OS DADOS DA TABELA AVARIA
    $sqlbusca = "SELECT * FROM avaria_padaria";
    $resultbusca = $conexao->query($sqlbusca);
    $contador = 3;
    while($tabela = mysqli_fetch_assoc($resultbusca)){
        $sheet->setCellValue('A'.$contador, $tabela['codigo_barras']);
        $sheet->setCellValue('B'.$contador, $tabela['descricao']);
        $sheet->setCellValue('C'.$contador, $tabela['quantidade']);
        $sheet->setCellValue('D'.$contador, $tabela['destino']);
        $contador = $contador+1;
    }
    $sheet->mergeCells('A'.$contador.':D'.$contador);
    $contador = $contador+1;
    $sheet->mergeCells('A'.$contador.':D'.$contador);
    $sheet->setCellValue('A'.$contador, 'OBSERVAÇÕES:');
    $styles = [
        'font' =>[
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        ]
    ];
    $styles2 = [
        'font' =>[
            'bold' => true,
        ]
    ];
    $styles3 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
    ];
    $sheet->getStyle('A'.$contador)->applyFromArray($styles);
    $contador = $contador+1;
    $sheet->mergeCells('A'.$contador.':D'.$contador);
    $contador = $contador+1;
    $sheet->mergeCells('A'.$contador.':D'.$contador);
    $sheet->setCellValue('A'.$contador, 'ASSINATURA DO CONFERENTE');
    $sheet->getStyle('A'.$contador)->applyFromArray($styles2);
    $sheet->getStyle('A1:D'.$contador)->applyFromArray($styles3);



//ESCREVE O ARQUIVO NO DISCO COM O FORMATO XLSX
    $writer = new Xlsx($spreadsheet);
    $writer->save($nome.'.xlsx');

header("Content-disposition: attachment; filename=$nome.xlsx");
header("Content-type: application/xlsx");
readfile($nome.'.xlsx');
unlink($nome.'.xlsx');
?>