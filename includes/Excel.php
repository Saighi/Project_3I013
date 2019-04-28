<?php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


function getExcel($clusters,$fname)
{
    $randomBorder = [
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHDOT,
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR,
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUMDASHED,
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_SLANTDASHDOT,
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
    ];
    $colorFile = file('../colors.txt');

    $domainProps = [];


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $cursorX = $cursorY = 1;
    foreach ($clusters as $indice => $class) {
        $classSize = count($class);
        $sheet->setCellValueByColumnAndRow($cursorX, $cursorY, "Groupe ".($indice+1)." ($classSize " . (($classSize == 1) ? 'protéine' : 'protéines') . ")");
        $sheet->getStyleByColumnAndRow($cursorX, $cursorY)->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'rgb' => '729FCF',
                ]
            ]
        ]);


        # $cursorX++;
        $cursorY++;
        foreach ($class as $prot) {
            if ($prot) {
                $sheet->setCellValueByColumnAndRow($cursorX, $cursorY, $prot->getId());


                $sheet->setCellValueByColumnAndRow(2, $cursorY, $prot->getTaille());
                $x = 3;
                foreach ($prot->getDomains() as $domaine) {
                    $sheet->setCellValueByColumnAndRow($x, $cursorY, $domaine->getId());

                    if (!isset($domainProps[$domaine->getId()])) {
                        $domainProps[$domaine->getId()] = [

                            "bgColor" => $colorFile[( rand(0, 147)*substr($domaine->getId(),2)  ) % 147],
                            "borderColor" => $colorFile[rand(0, 147)],
                            "border" => $randomBorder[rand(0, 7)],
                            "bold" => (rand(0, 3) == 0),
                            "italic" => (rand(0, 3) == 0),
                            "underline" => (rand(0, 3) == 0),


                        ];
                    }
                    $styleArray = [
                        'font' => [
                            'bold' => $domainProps[$domaine->getId()]["bold"],
                            'italic' => $domainProps[$domaine->getId()]["italic"],
                            'underline' => $domainProps[$domaine->getId()]["underline"],
                            'color' => [
                                'rgb' => dechex(hexdec($domainProps[$domaine->getId()]["bgColor"])*(-1)),
                            ]
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => $domainProps[$domaine->getId()]['border'],
                                'color' => [
                                    'rgb' => $domainProps[$domaine->getId()]['borderColor']
                                ]
                            ]
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => [
                                'rgb' => $domainProps[$domaine->getId()]['bgColor']
                            ]
                        ],
                    ];

                    $sheet->getStyleByColumnAndRow($x, $cursorY)->applyFromArray($styleArray);
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
    # si on veut passer deux lignes entre chaque groupe décommenter :
    #   $cursorY++;
    }

    foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }






    $writer = new Xlsx($spreadsheet);
    $writer->save('./excels/'.$fname.'.xlsx');
    
}

