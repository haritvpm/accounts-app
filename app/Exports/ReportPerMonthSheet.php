<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
//use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
//use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class ReportPerMonthSheet implements FromCollection, WithTitle, WithHeadings, WithEvents
{
    private $monthname;
    private $taxentries;
    private $adminEntries;
    private $adminentryheading;

    public function __construct( $monthname, $taxentries, $adminEntries)
    {
        $this->monthname = $monthname;
        $this->taxentries  = $taxentries;
        $this->adminEntries  = $adminEntries;
        $this->adminentryheading = -1;
    }

    public function collection()
    {
        $tdscombined = new Collection();

        
        $slno = 1;
        $tdstotal = 0;

        foreach ($this->taxentries as $taxentry) {

            $tds = $taxentry->dateTds()->get();
            
            foreach ($tds as $cols){
                                          
                $items = [];
                array_push($items, $slno++);
                array_push($items, $cols->pan);
                array_push($items, $cols->pen);
                array_push($items, $cols->name);
                array_push($items, $cols->gross);
                array_push($items, $cols->tds);
                array_push($items, $taxentry->date );
                $tdscombined->push($items) ;
                $tdstotal += $cols->tds;
            }
                                  
        }

        if( $slno > 1 ) { //has entries
            $tdscombined->push( [ '', '', '','', 'Total', $tdstotal, ''] );
            $slno++;
        }


        if($this->adminEntries->count()){
            $tdscombined->push( ['26Q'] );
            $this->adminentryheading  = ++$slno;
        }

        
        $slno = 1;
        $tdstotal = 0;

        foreach ($this->adminEntries as $taxentry) {

            $tds = $taxentry->dateTds()->get();
            
            foreach ($tds as $cols){
                                          
                $items = [];
                array_push($items, $slno++);
                array_push($items, $cols->pan);
                array_push($items, $cols->pen);
                array_push($items, $cols->name);
                array_push($items, $cols->gross);
                array_push($items, $cols->tds);
                array_push($items, $taxentry->date );
                $tdscombined->push( $items );
                $tdstotal += $cols->tds;
            }
                                  
        }

        if( $slno > 1 ){
            $tdscombined->push( [ '', '', '','', 'Total', $tdstotal, ''] );
        } 


        return $tdscombined;
    }

   
    /**
     * @return string
     */
    public function title(): string
    {
        return $this->monthname;
    }
    public function headings(): array
    {
        return [
            'Sl.No', 'PAN of the deductee', 'PEN of the deductee', 
            'Name of the deductee', 'Amount paid/credited', 'TDS', 'Date of credit'
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        
        return [
            // Handle by a closure.
           
            AfterSheet::class => function(AfterSheet $event) {

                $t =  $this->adminentryheading  ;
                if(  $t != -1)
                {
                    $workSheet = $event
                        ->sheet
                        ->getDelegate()
                        ->mergeCells(
                        
                            'A' . $t . ':G' . $t
                    );
                        
                    $headers =  $event->getSheet()->getDelegate()->getStyle('A' . $t . ':G' . $t);

                    $headers
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                }
                
            },
            
            // Array callable, refering to a static method.
           // AfterSheet::class => [self::class, 'afterSheet'],
            
           
        ];
    }

    /**
     * After sheet handler
     *
     * @param  AfterSheet  $event
     * @return void
     * @throws Exception
     */
    public static function afterSheet(AfterSheet $event)
    {

        /*$sheet->mergeCells( 'A' . $slno . ':G' . $slno); 
        $style = array(
            'alignment' => array(
                'horizontal' =>'center',
            )
        );
        
        $sheet->getStyle('A' . $slno . ':G' . $slno)->applyFromArray($style);
        */

        try {


            if(  $this->adminentryheading != -1)
            {
                $workSheet = $event
                    ->sheet
                    ->getDelegate()
                    ->mergeCells([
                       
                        'A2:D2'
                    ]);
                    
                $headers = $workSheet->getStyle('A' . $this->adminentryheading . ':G' . $this->adminentryheading);

                $headers
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
            }

            //$headers->getFont()->setBold(true);

            
        } catch (Exception $exception) {
            throw $exception;
        }
    }

}