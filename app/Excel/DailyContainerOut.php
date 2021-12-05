<?php

namespace App\Excel;
use App\Models\ContainerReleasing;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// for applying style sheet
// use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
Use \Maatwebsite\Excel\Sheet;

class DailyContainerOut implements  FromView, ShouldAutoSize
{
    protected $type,$sizeType,$client,$container_no,$booking_no,$from,$to;

    public function __construct($type,$sizeType,$client,$container_no,$booking_no,$from,$to)
    {
        $this->type = $type;
        $this->sizeType = $sizeType;
        $this->client = $client;
        $this->container_no = $container_no;
        $this->booking_no = $booking_no;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $data = ContainerReleasing::when($this->container_no != 'NA', function ($q){
            return $q->where('container_no',$this->container_no);
        })->when($this->booking_no != 'NA', function ($q){
            return $q->where('booking_no',$this->booking_no);
        })->when($this->from != 'NA', function ($q){
            return $q->whereDate('inspected_date','>=',$this->from);
        })->when($this->to != 'NA', function ($q){
            return $q->whereDate('inspected_date','<=',$this->to);
        })->whereHas('container',function( $query ) {
            $query->where('container_no',$this->container_no)->where('client_id',$this->client)
                ->where('size_type',$this->sizeType)->whereNotNull('date_released')->latest('created_at');
        })->whereHas('receiving',function( $query ) {
            $query->where('container_no',$this->container_no)->where('type_id',$this->type);
        })->with('container.client','container.sizeType','inspector','container.containerClass','receiving','receiving.type')->get();

        return view('excel.daily_container_out',compact('data'));
    }
}
