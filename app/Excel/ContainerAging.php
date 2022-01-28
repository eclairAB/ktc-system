<?php

namespace App\Excel;
use App\Models\Container;
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
    protected $type,$sizeType,$client,$class,$date_in_from,$date_in_to,$date_out_from,$date_out_to,$option,$status;

    public function __construct($type,$sizeType,$client,$class,$date_in_from,$date_in_to,$date_out_from,$date_out_to,$option,$status)
    {
        $this->type = $type;
        $this->sizeType = $sizeType;
        $this->client = $client;
        $this->class = $class;
        $this->date_in_from = $date_in_from;
        $this->date_in_to = $date_in_to;
        $this->date_out_from = $date_out_from;
        $this->date_out_to = $date_out_to;
        $this->option = $option;
        $this->status = $status;
    }

    public function view(): View
    {
        if($this->option == 'IN')
        {
            $data = Container::when($this->type != 'NA', function ($q)  {
                return $q->where('type_id',$this->type);
            })->when($this->sizeType != 'NA', function ($q) {
                return $q->where('size_type',$this->sizeType);
            })->when($this->client != 'NA', function ($q) {
                return $q->where('client_id',$this->client);
            })->when($this->class != 'NA', function ($q) {
                return $q->where('class',$this->class);
            })->whereHas('receiving',function( $query ) {
                $query->when($this->date_in_from != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','>=',$this->date_in_from);
                })->when($this->date_in_to != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','<=',$this->date_in_to);
                })->when($this->status != 'NA', function ($q) {
                    return $q->where('empty_loaded',$this->status);
                });
            })->whereNull('releasing_id')->with('client','sizeType','containerClass','type','receiving')->orderBy('container_no','ASC')->get();
    
            foreach($data as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }
    
            return view('excel.container_aging',compact('data'));
        }
        else if($this->option == 'OUT')
        {
            $data = Container::when($this->type != 'NA', function ($q)  {
                return $q->where('type_id',$this->type);
            })->when($this->sizeType != 'NA', function ($q) {
                return $q->where('size_type',$this->sizeType);
            })->when($this->client != 'NA', function ($q) {
                return $q->where('client_id',$this->client);
            })->when($this->class != 'NA', function ($q) {
                return $q->where('class',$this->class);
            })->whereHas('releasing',function( $query ) {
                $query->when($this->date_out_from != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','>=',$this->date_out_from);
                })->when($this->date_out_to != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','<=',$this->date_out_to);
                });
            })->whereHas('receiving',function( $query ) {
                $query->when($this->status != 'NA', function ($q) {
                    return $q->where('empty_loaded',$this->status);
                });
            })->whereNotNull('receiving_id')->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('container_no','ASC')->get();
    
            foreach($data as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays($res->releasing->inspected_date);
                $res->total_no_days = $diff_days;
            }
    
            return view('excel.container_aging',compact('data'));
        }
        else if($this->option == 'ALL')
        {
            $data = Container::when($this->type != 'NA', function ($q)  {
                return $q->where('type_id',$this->type);
            })->when($this->sizeType != 'NA', function ($q) {
                return $q->where('size_type',$this->sizeType);
            })->when($this->client != 'NA', function ($q) {
                return $q->where('client_id',$this->client);
            })->when($this->class != 'NA', function ($q) {
                return $q->where('class',$this->class);
            })->whereHas('receiving',function( $query ) {
                $query->when($this->date_in_from != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','>=',$this->date_in_from);
                })->when($this->date_in_to != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','<=',$this->date_in_to);
                })->when($this->status != 'NA', function ($q) {
                    return $q->where('empty_loaded',$this->status);
                });
            })->orWhereHas('releasing',function( $query ) {
                $query->when($this->date_out_from != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','>=',$this->date_out_from);
                })->when($this->date_out_to != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','<=',$this->date_out_to);
                });
            })->whereNotNull('receiving_id')->whereNotNull('releasing_id')->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('container_no','ASC')->get();
    
            foreach($data as $res)
            {
                $diff_days = isset($res->releasing)?Carbon::parse($res->receiving->inspected_date)->diffInDays($res->releasing->inspected_date):Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }
    
            return view('excel.container_aging',compact('data'));
        }

        
    }
}
