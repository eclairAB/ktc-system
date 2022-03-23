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

class DailyContainerOut implements  FromView, ShouldAutoSize
{
    protected $type,$sizeType,$client,$class,$status,$from,$to,$param,$order;

    public function __construct($type,$sizeType,$client,$class,$status,$from,$to,$param,$order)
    {
        $this->type = $type;
        $this->sizeType = $sizeType;
        $this->client = $client;
        $this->class = $class;
        $this->status = $status;
        $this->from = $from;
        $this->to = $to;
        $this->param = $param;
        $this->order = $order;
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
        })->whereHas('releasing',function( $query ){
            $query->when($this->from != 'NA', function ($q){
                return $q->whereDate('inspected_date','>=',$this->from);
            })->when($this->to != 'NA', function ($q){
                return $q->whereDate('inspected_date','<=',$this->to);
            });
        })->whereNotNull('releasing_id')->whereNotNull('receiving_id')->with('client','sizeType','containerClass','type','releasing','eirNoOut')->orderBy('container_no','ASC')->get();

        if($this->param == 'container_no'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_no');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_no');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'eir_no'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('eirNoOut.eir_no');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('eirNoOut.eir_no');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'client'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('client.code');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('client.code');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'type'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('type.code');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('type.code');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'size_type'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('size_type.size');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('size_type.size');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'container_class'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_class.class_code');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_class.class_code');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'inspected_date'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.inspected_date');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'remarks'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.remarks');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.remarks');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'consignee'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.consignee');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.consignee');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'plate_no'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.plate_no');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.plate_no');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }else if($this->param == 'hauler'){
            $tobesorted = collect($data);
            if($this->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.hauler');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.hauler');
                $datus = $sorted->values()->all();
                return view('excel.daily_container_in',compact('datus'));
            }
        }
    }
}
