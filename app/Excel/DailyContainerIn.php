<?php

namespace App\Excel;
use App\Models\ContainerReceiving;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// for applying style sheet
// use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
Use \Maatwebsite\Excel\Sheet;

class DailyContainerIn implements  FromView, ShouldAutoSize
{
    protected $type,$sizeType,$client,$container_no,$loc,$from,$to;

    public function __construct($type,$sizeType,$client,$container_no,$loc,$from,$to)
    {
        $this->type = $type;
        $this->sizeType = $sizeType;
        $this->client = $client;
        $this->container_no = $container_no;
        $this->loc = $loc;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $data = ContainerReceiving::when($this->type != 'NA', function ($q){
            return $q->where('type_id',$this->type);
        })->when($this->sizeType != 'NA', function ($q){
            return $q->where('size_type',$this->sizeType);
        })->when($this->client != 'NA', function ($q){
            return $q->where('client_id',$this->client);
        })->when($this->container_no != 'NA', function ($q){
            return $q->where('container_no',$this->container_no);
        })->when($this->loc != 'NA', function ($q){
            return $q->where('yard_location',$this->loc);
        })->when($this->from != 'NA', function ($q){
            return $q->whereDate('inspected_date','>=',$this->from);
        })->when($this->to != 'NA', function ($q){
            return $q->whereDate('inspected_date','<=',$this->to);
        })->whereHas('container',function( $query ) {
            $query->where('container_no',$this->container_no)->where('client_id',$this->client)->where('size_type',$this->sizeType)->whereNull('date_released');
        })->with('client','sizeType','yardLocation','inspector','containerClass','container','type')->get();

        return view('excel.daily_container_in',compact('data'));
    }
}
