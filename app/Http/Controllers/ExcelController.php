<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Excel\DailyContainerIn;
use App\Excel\DailyContainerOut;
use App\Excel\ContainerAging;
use Carbon\Carbon;

class ExcelController extends Controller
{
    //
    public function dailyContainerIn($type,$sizeType,$client,$class,$status,$from,$to,$param,$order)
    {
        $now = Carbon::now()->format('dmY');
        return Excel::download(new DailyContainerIn($type,$sizeType,$client,$class,$status,$from,$to,$param,$order), 'Daily_Container_In'.$now.'.xlsx');
    }

    public function dailyContainerOut($type,$sizeType,$client,$class,$status,$from,$to,$param,$order)
    {
        $now = Carbon::now()->format('dmY');
        return Excel::download(new DailyContainerOut($type,$sizeType,$client,$class,$status,$from,$to,$param,$order), 'Daily_Container_Out'.$now.'.xlsx');
    }

    public function containerAging($type,$sizeType,$client,$class,$date_in_from,$date_in_to,$date_out_from,$date_out_to,$option,$status,$param,$order)
    {
        $now = Carbon::now()->format('dmY');
        return Excel::download(new ContainerAging($type,$sizeType,$client,$class,$date_in_from,$date_in_to,$date_out_from,$date_out_to,$option,$status,$param,$order), 'Container_Aging'.$now.'('.$option.').xlsx');
    }
}
