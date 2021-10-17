<?php

namespace App\Http\Controllers;
use App\Models\ContainerClass;
use App\Models\ContainerHeight;
use App\Models\ContainerSizeType;
use App\Models\ContainerReceiving;
use App\Models\ContainerReleasing;
use App\Models\Client;
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

    public function getClient(Request $request)
    {
        $client = Client::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code_name', 'ilike', '%'.$request->keyword.'%');
        })->get();

        return $client;
    }

    public function prntReleasing($id)
    {
        $releasing = ContainerReleasing::where('id',$id)->first();
        return view('print_releasing')->with('releasing', $releasing);;
    }

    public function prntReceiving($id)
    {
        $receiving = ContainerReceiving::where('id',$id)->first();
        return view('print_receiving')->with('receiving', $receiving);
    }
}
