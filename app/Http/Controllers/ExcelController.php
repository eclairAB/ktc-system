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
    public function dailyContainerIn($type,$sizeType,$client,$container_no,$loc,$from,$to)
    {
        $now = Carbon::now()->format('Y-m-d');
        return Excel::download(new DailyContainerIn($type,$sizeType,$client,$container_no,$loc,$from,$to), 'Daily_Container_In_'.$now.'.xlsx');
    }

    public function dailyContainerOut($type,$sizeType,$client,$container_no,$booking_no,$from,$to)
    {
        $now = Carbon::now()->format('Y-m-d');
        return Excel::download(new DailyContainerOut($type,$sizeType,$client,$container_no,$booking_no,$from,$to), 'Daily_Container_Out_'.$now.'.xlsx');
    }

    public function containerAging($type,$sizeType,$client,$class,$date_as_of)
    {
        $now = Carbon::now()->format('Y-m-d');
        return Excel::download(new ContainerAging($type,$sizeType,$client,$class,$date_as_of), 'Container_Aging_'.$now.'.xlsx');
    }
}
