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
    public function dailyContainerIn($type,$sizeType,$client,$class,$status,$from,$to)
    {
        $now = Carbon::now()->format('Y-m-d');
        return Excel::download(new DailyContainerIn($type,$sizeType,$client,$class,$status,$from,$to), 'Daily_Container_In_'.$now.'.xlsx');
    }

    public function dailyContainerOut($type,$sizeType,$client,$class,$status,$from,$to)
    {
        $now = Carbon::now()->format('Y-m-d');
        return Excel::download(new DailyContainerOut($type,$sizeType,$client,$class,$status,$from,$to), 'Daily_Container_Out_'.$now.'.xlsx');
    }

    public function containerAging($type,$sizeType,$client,$class,$date_in_from,$date_in_to,$date_out_from,$date_out_to,$option,$status)
    {
        $now = Carbon::now()->format('Y-m-d');
        return Excel::download(new ContainerAging($type,$sizeType,$client,$class,$date_in_from,$date_in_to,$date_out_from,$date_out_to,$option,$status), 'Container_Aging_'.$now.'.xlsx');
    }
}
