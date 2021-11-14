<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Excel\DailyContainerIn;
use App\Excel\DailyContainerOut;
use Carbon\Carbon;

class ExcelController extends Controller
{
    //
    public function dailyContainerIn($sizeType,$client,$container_no,$loc,$from,$to)
    {
        $now = Carbon::now()->format('Y-m-d');
        return Excel::download(new DailyContainerIn($sizeType,$client,$container_no,$loc,$from,$to), 'Daily_Container_In_'.$now.'.xlsx');
    }

    public function dailyContainerOut($sizeType,$client,$container_no,$booking_no,$from,$to)
    {
        $now = Carbon::now()->format('Y-m-d');
        return Excel::download(new DailyContainerOut($sizeType,$client,$container_no,$booking_no,$from,$to), 'Daily_Container_Out_'.$now.'.xlsx');
    }

}
