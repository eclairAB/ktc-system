<?php

namespace App\Excel;
use App\Models\Container;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// for applying style sheet
// use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
Use \Maatwebsite\Excel\Sheet;

class DailyContainerIn implements  FromView, ShouldAutoSize
{
    protected $type,$sizeType,$client,$class,$status,$from,$to;

    public function __construct($type,$sizeType,$client,$class,$status,$from,$to)
    {
        $this->type = $type;
        $this->sizeType = $sizeType;
        $this->client = $client;
        $this->class = $class;
        $this->status = $status;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $data = Container::when($this->type != 'NA', function ($q){
            return $q->where('type_id',$this->type);
        })->when($this->sizeType != 'NA', function ($q){
            return $q->where('size_type',$this->sizeType);
        })->when($this->client != 'NA', function ($q){
            return $q->where('client_id',$this->client);
        })->when($this->class != 'NA', function ($q){
            return $q->where('class',$this->class);
        })->when($this->status != 'NA', function ($q){
            return $q->where('status',$this->status);
        })->whereHas('receiving',function( $query ){
            $query->when($this->from != 'NA', function ($q){
                return $q->whereDate('inspected_date','>=',$this->from);
            })->when($this->to != 'NA', function ($q){
                return $q->whereDate('inspected_date','<=',$this->to);
            });
        })->whereNotNull('receiving_id')->whereNull('releasing_id')->with('client','sizeType','containerClass','eirNoIn','type','receiving.damages')->orderBy('container_no','ASC')->get();

        return view('excel.daily_container_in',compact('data'));
    }
}
