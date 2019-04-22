<?php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


function getExcel($clusters)
{


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $cursorX = $cursorY = 1;
    foreach ($clusters as $indice => $class) {
        $classSize = count($class);
        $sheet->setCellValueByColumnAndRow($cursorX, $cursorY, "Groupe $indice ($classSize protéines)");
       # $cursorX++;
        $cursorY++;
        foreach ($class as $prot) {
            $sheet->setCellValueByColumnAndRow($cursorX, $cursorY, $prot->getId());
            
           
            $sheet->setCellValueByColumnAndRow(2, $cursorY, $prot->getTaille());
            $x=3;
            foreach($prot->getDomains() as $domaine) {
                $sheet->setCellValueByColumnAndRow($x, $cursorY, $domaine->getId());
                $x++;

                $sheet->setCellValueByColumnAndRow($x, $cursorY, $domaine->getConfiance());
                $x++;

                $sheet->setCellValueByColumnAndRow($x, $cursorY, $domaine->getFirst());
                $x++;

                $sheet->setCellValueByColumnAndRow($x, $cursorY, $domaine->getLast());
                $x++;
            }

            #$cursorX++;
            $cursorY++;
        }
    }
    $writer = new Xlsx($spreadsheet);
    $writer->save('test.xlsx');
}

