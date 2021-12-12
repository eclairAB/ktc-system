<?php

namespace App\Excel;
use App\Models\ContainerReceiving;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// for applying style sheet
// use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
Use \Maatwebsite\Excel\Sheet;

class ContainerAging implements  FromView, ShouldAutoSize
{
    protected $type,$sizeType,$client,$class,$date_as_of;

    public function __construct($type,$sizeType,$client,$class,$date_as_of)
    {
        $this->type = $type;
        $this->sizeType = $sizeType;
        $this->client = $client;
        $this->class = $class;
        $this->date_as_of = $date_as_of;
    }

    public function view(): View
    {
        $data = ContainerReceiving::when($this->type != 'NA', function ($q)  {
            return $q->where('type_id',$this->type);
        })->when($this->sizeType != 'NA', function ($q) {
            return $q->where('size_type',$this->sizeType);
        })->when($this->client != 'NA', function ($q) {
            return $q->where('client_id',$this->client);
        })->when($this->class != 'NA', function ($q) {
            return $q->where('class',$this->class);
        })->when($this->date_as_of != 'NA', function ($q) {
            return $q->whereDate('inspected_date','=',$this->date_as_of);
        })->whereHas('container',function( $query ) {
            $query->where('client_id',$this->client)
                ->where('size_type',$this->sizeType)->whereNull('releasing_id')->latest('created_at');
        })->with('client','sizeType','yardLocation','containerClass','type')->get();
        // ->whereHas('container',function( $query ) {
        //     $query->where('container_no',$this->container_no)->where('client_id',$this->client)
        //         ->where('class',$this->class)->where('size_type',$this->sizeType)->whereNull('releasing_id');
        // })->with('client','sizeType','yardLocation','inspector','containerClass','container')->get();

        foreach($data as $res)
        {
            $diff_days = Carbon::parse($res->inspected_date)->diffInDays('now');
            $res->total_no_days = $diff_days;
        }

        return view('excel.container_aging',compact('data'));
    }
}
