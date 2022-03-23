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
    protected $type,$sizeType,$client,$class,$date_in_from,$date_in_to,$date_out_from,$date_out_to,$option,$status,$param,$order;

    public function __construct($type,$sizeType,$client,$class,$date_in_from,$date_in_to,$date_out_from,$date_out_to,$option,$status,$param,$order)
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
        $this->param = $param;
        $this->order = $order;
    }

    public function view(): View
    {
        if($this->option == 'IN')
        {
            $datus = Container::when($this->type != 'NA', function ($q)  {
                return $q->where('type_id',$this->type);
            })->when($this->sizeType != 'NA', function ($q) {
                return $q->where('size_type',$this->sizeType);
            })->when($this->client != 'NA', function ($q) {
                return $q->where('client_id',$this->client);
            })->when($this->class != 'NA', function ($q) {
                return $q->where('class',$this->class);
            })->when($this->status != 'NA', function ($q) {
                return $q->where('status',$this->status);
            })->whereHas('receiving',function( $query ) {
                $query->when($this->date_in_from != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','>=',$this->date_in_from);
                })->when($this->date_in_to != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','<=',$this->date_in_to);
                });
            })->whereNotNull('receiving_id')->whereNull('releasing_id')->with('client','sizeType','containerClass','type','receiving')->get();
    
            foreach($datus as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }

            if($this->param == 'container_no'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_no');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_no');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'status'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('status');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('status');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'client'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('client.code');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('client.code');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'type'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('type.code');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('type.code');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'size_type'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('size_type.size');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('size_type.size');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'container_class'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_class.class_code');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_class.class_code');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'receiving_inspected_date'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.inspected_date');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'receiving_consignee'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.consignee');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.consignee');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'releasing_inspected_date'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.inspected_date');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'releasing_consignee'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.consignee');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.consignee');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'booking_no'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.booking_no');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'seal_no'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.seal_no');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'total_no_days'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('total_no_days');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('total_no_days');
                    $data = $sorted->values()->all();
                     $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }
        }
        else if($this->option == 'OUT')
        {
            $datus = Container::when($this->type != 'NA', function ($q)  {
                return $q->where('type_id',$this->type);
            })->when($this->sizeType != 'NA', function ($q) {
                return $q->where('size_type',$this->sizeType);
            })->when($this->client != 'NA', function ($q) {
                return $q->where('client_id',$this->client);
            })->when($this->class != 'NA', function ($q) {
                return $q->where('class',$this->class);
            })->when($this->status != 'NA', function ($q) {
                return $q->where('status',$this->status);
            })->whereHas('releasing',function( $query ) {
                $query->when($this->date_out_from != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','>=',$this->date_out_from);
                })->when($this->date_out_to != 'NA', function ($q) {
                    return $q->whereDate('inspected_date','<=',$this->date_out_to);
                });
            })->whereNotNull('releasing_id')->whereNotNull('receiving_id')->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('container_no','ASC')->get();
    
            foreach($datus as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays($res->releasing->inspected_date);
                $res->total_no_days = $diff_days;
            }

            if($this->param == 'container_no'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'status'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('status');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('status');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'client'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('client.code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('client.code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'type'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('type.code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('type.code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'size_type'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('size_type.size');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('size_type.size');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'container_class'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_class.class_code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_class.class_code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'receiving_inspected_date'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'receiving_consignee'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.consignee');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.consignee');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'releasing_inspected_date'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'releasing_consignee'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.consignee');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.consignee');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'booking_no'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'seal_no'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'total_no_days'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('total_no_days');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('total_no_days');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }
        }
        else if($this->option == 'ALL')
        {
            $datus = Container::when($this->type != 'NA', function ($q)  {
                return $q->where('type_id',$this->type);
            })->when($this->sizeType != 'NA', function ($q) {
                return $q->where('size_type',$this->sizeType);
            })->when($this->client != 'NA', function ($q) {
                return $q->where('client_id',$this->client);
            })->when($this->class != 'NA', function ($q) {
                return $q->where('class',$this->class);
            })->when($this->status != 'NA', function ($q) {
                return $q->where('status',$this->status);
            })->where(function($q) {
                $q->has('receiving')->orHas('releasing');
            })->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('container_no','ASC')->get();
    
            foreach($datus as $res)
            {
                $diff_days = isset($res->releasing)?Carbon::parse($res->receiving->inspected_date)->diffInDays($res->releasing->inspected_date):Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }

            if($this->param == 'container_no'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'status'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('status');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('status');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'client'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('client.code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('client.code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'type'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('type.code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('type.code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'size_type'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('size_type.size');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('size_type.size');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'container_class'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_class.class_code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_class.class_code');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'receiving_inspected_date'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'receiving_consignee'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.consignee');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.consignee');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'releasing_inspected_date'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'releasing_consignee'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.consignee');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.consignee');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'booking_no'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'seal_no'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }else if($this->param == 'total_no_days'){
                $tobesorted = collect($datus);
                if($this->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('total_no_days');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('total_no_days');
                    $data = $sorted->values()->all();
                    $count = count($data);
                    $in = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($this->type != 'NA', function ($q)  {
                        return $q->where('type_id',$this->type);
                    })->when($this->sizeType != 'NA', function ($q) {
                        return $q->where('size_type',$this->sizeType);
                    })->when($this->client != 'NA', function ($q) {
                        return $q->where('client_id',$this->client);
                    })->when($this->class != 'NA', function ($q) {
                        return $q->where('class',$this->class);
                    })->when($this->status != 'NA', function ($q) {
                        return $q->where('status',$this->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('excel.container_aging',compact('data','count','in','out'));
                }
            }
        }

        
    }
}
