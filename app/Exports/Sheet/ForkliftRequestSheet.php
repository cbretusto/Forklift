<?php

namespace App\Exports\Sheet;

use Illuminate\Contracts\View\View;

Use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

// use Maatwebsite\Excel\Concerns\WithMultipleSheets;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use PhpOffice\PhpSpreadsheet\Style\Alignment;



class ForkliftRequestSheet implements FromView, ShouldAutoSize, WithEvents, WithTitle
{
    use Exportable;
    protected $forkliftRequestDetails;

    //
    function __construct(
    $forkliftRequestDetails
    ){
        $this->forkliftRequestDetails = $forkliftRequestDetails;
    }

    public function view(): View {
        return view('exports.forkliftrequest', ['forkliftRequestDetails' => $this->forkliftRequestDetails]);
    }

    public function title(): string{
        return 'Forklift Request Report';
    }

    //for designs
    public function registerEvents(): array{
        $forkliftRequestDetails = $this->forkliftRequestDetails;

        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $text_align_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ]
        );

        $text_align_left = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'wrap' => TRUE
            ]
        );

        $font_9_arial = array(
            'font' => [
                'name'      =>  'Arial',
                'size'      =>  9,
            ]
        );

        $font_9_arial_bold = array(
            'font' => [
                'name'      =>  'Arial',
                'size'      =>  9,
                'bold'      =>  true,
            ]
        );

        $font_12_arial_bold = array(
            'font' => [
                'name'      =>  'Arial',
                'size'      =>  12,
                'bold'      =>  true,
            ]
        );

        $font_14_arial_bold = array(
            'font' => [
                'name'      =>  'Arial',
                'size'      =>  14,
                'bold'      =>  true,
            ]
        );

        return[AfterSheet::class => function(AfterSheet $event) use(
            $forkliftRequestDetails, 
            $border, 
            $text_align_center, 
            $text_align_left, 
            $font_9_arial, 
            $font_9_arial_bold, 
            $font_12_arial_bold, 
            $font_14_arial_bold
        ){
            //==================== Excel Format =========================
            $event->sheet->getDelegate()->getStyle('A1:I2')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('B7D8FF');

            $event->sheet->getDelegate()->getStyle('A3:I4')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('70ECF9');

            $event->sheet->getDelegate()->getStyle('A1:I4')->applyFromArray($border);

            $event->sheet->getColumnDimension('A')->setWidth(20);
            $event->sheet->getColumnDimension('B')->setWidth(20);
            $event->sheet->getColumnDimension('C')->setWidth(20);
            $event->sheet->getColumnDimension('D')->setWidth(20);
            $event->sheet->getColumnDimension('E')->setWidth(20);
            $event->sheet->getColumnDimension('F')->setWidth(20);
            $event->sheet->getColumnDimension('G')->setWidth(20);
            $event->sheet->getColumnDimension('H')->setWidth(20);
            $event->sheet->getColumnDimension('I')->setWidth(20);

            $event->sheet->getDelegate()->mergeCells('A1:I2');
            $event->sheet->getDelegate()->mergeCells('A3:A4');
            $event->sheet->getDelegate()->mergeCells('B3:B4');
            $event->sheet->getDelegate()->mergeCells('C3:C4');
            $event->sheet->getDelegate()->mergeCells('D3:D4');
            $event->sheet->getDelegate()->mergeCells('E3:E4');
            $event->sheet->getDelegate()->mergeCells('F3:F4');
            $event->sheet->getDelegate()->mergeCells('G3:G4');
            $event->sheet->getDelegate()->mergeCells('H3:H4');
            $event->sheet->getDelegate()->mergeCells('I3:I4');

            $event->sheet
                ->getDelegate()
                ->getStyle('A1')
                ->applyFromArray($text_align_center)
                ->applyFromArray($font_14_arial_bold)
                ->getAlignment()
                ->setWrapText(true);

            $event->sheet
                ->getDelegate()
                ->getStyle('A3:I3')
                ->applyFromArray($text_align_center)
                ->applyFromArray($font_9_arial_bold)
                ->getAlignment()
                ->setWrapText(true);
            
            $event->sheet->setCellValue('A1',"Forklift Request Report");

            $event->sheet->setCellValue('A3',"Request No.");
            $event->sheet->setCellValue('B3',"Requestor");
            $event->sheet->setCellValue('C3',"Department");
            $event->sheet->setCellValue('D3',"Date Needed");
            $event->sheet->setCellValue('E3',"Time");
            $event->sheet->setCellValue('F3',"Pick-up From");
            $event->sheet->setCellValue('G3',"Delivery To");
            $event->sheet->setCellValue('H3',"Package Commodity");
            $event->sheet->setCellValue('I3',"Volume of Trips");

            $start_column = 5;
            for ($i=0; $i < count($forkliftRequestDetails); $i++) { 
                $event->sheet
                    ->getDelegate()
                    ->getStyle('A'.$start_column.':I'.$start_column)
                    ->applyFromArray($border)
                    ->applyFromArray($font_9_arial)
                    ->applyFromArray($text_align_left)
                    ->getAlignment()
                    ->setWrapText(true);

                $event->sheet->setCellValue('A'.$start_column,$forkliftRequestDetails[$i]->request_no);
                $event->sheet->setCellValue('B'.$start_column,$forkliftRequestDetails[$i]->forklift_request_approver_info->requestor_approver_info->name);
                $event->sheet->setCellValue('C'.$start_column,$forkliftRequestDetails[$i]->department);
                $event->sheet->setCellValue('D'.$start_column,$forkliftRequestDetails[$i]->date_needed);
                $event->sheet->setCellValue('E'.$start_column,$forkliftRequestDetails[$i]->time);
                $event->sheet->setCellValue('F'.$start_column,$forkliftRequestDetails[$i]->pick_up_from);
                $event->sheet->setCellValue('G'.$start_column,$forkliftRequestDetails[$i]->delivery_to);
                $event->sheet->setCellValue('H'.$start_column,$forkliftRequestDetails[$i]->package_commodity);
                $event->sheet->setCellValue('I'.$start_column,$forkliftRequestDetails[$i]->volume_of_trips);
                $start_column++;
            }
        }];
    }
}
