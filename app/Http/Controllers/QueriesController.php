<?php

namespace App\Http\Controllers;
use App\Models\ContainerClass;
use App\Models\ContainerHeight;
use App\Models\ContainerSizeType;
use Illuminate\Http\Request;

class QueriesController extends VoyagerBaseController
{
    //
    public function getContainterClass(Request $request)
    {
        $class = ContainerClass::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('class_code', 'ilike', '%'.$request->keyword.'%')
            ->orwhere('class_name', 'ilike', '%'.$request->keyword.'%');
        })->get();
    }

    public function getContainterHeight(Request $request)
    {
        $class = ContainerHeight::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('height_code', 'ilike', '%'.$request->keyword.'%')
            ->orwhere('height_name', 'ilike', '%'.$request->keyword.'%');
        })->get();
    }

    public function getContainterSizeType(Request $request)
    {
        $class = ContainerSizeType::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%')
            ->orwhere('name', 'ilike', '%'.$request->keyword.'%');
        })->get();
    }
}
