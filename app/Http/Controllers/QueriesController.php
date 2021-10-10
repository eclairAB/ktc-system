<?php

namespace App\Http\Controllers;
use App\Models\ContainerClass;
use App\Models\ContainerHeight;
use App\Models\ContainerSizeType;
use Illuminate\Http\Request;

class QueriesController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    //
    public function getContainterClass(Request $request)
    {
        $class = ContainerClass::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('class_code', 'ilike', '%'.$request->keyword.'%')
            ->orwhere('class_name', 'ilike', '%'.$request->keyword.'%');
        })->get();

        return $class;
    }

    public function getContainterHeight(Request $request)
    {
        $height = ContainerHeight::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('height_code', 'ilike', '%'.$request->keyword.'%')
            ->orwhere('height_name', 'ilike', '%'.$request->keyword.'%');
        })->get();

        return $height;
    }

    public function getContainterSizeType(Request $request)
    {
        $sizetype = ContainerSizeType::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%')
            ->orwhere('name', 'ilike', '%'.$request->keyword.'%');
        })->get();

        return $sizetype;
    }
}
