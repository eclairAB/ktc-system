<?php

namespace App\Http\Controllers;
use App\Models\ContainerClass;
// use App\Models\ContainerHeight;
use App\Models\Containers;
use App\Models\ContainerSizeType;
use App\Models\ContainerComponent;
use App\Models\ContainerDamage;
use App\Models\ContainerRepair;
use App\Models\ContainerReceiving;
use App\Models\ContainerReleasing;
use App\Models\Client;
use App\Models\Staff;
use App\Models\ReceivingDamage;
use App\Models\YardLocation;
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

    // public function getContainterHeight(Request $request)
    // {
    //     $height = ContainerHeight::when(!empty($request->keyword), function ($q) use ($request){
    //         return $q->where('height_code', 'ilike', '%'.$request->keyword.'%')
    //         ->orwhere('height_name', 'ilike', '%'.$request->keyword.'%');
    //     })->get();

    //     return $height;
    // }

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

    public function getYardLocation(Request $request)
    {
        $yardloc = YardLocation::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('name', 'ilike', '%'.$request->keyword.'%');
        })->get();

        return $yardloc;
    }

    public function getContainerDamage(Request $request)
    {
        $dmgs = ContainerDamage::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%');
        })->get();

        return $dmgs;
    }

    public function getContainerRepair(Request $request)
    {
        $repairs = ContainerRepair::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%');
        })->get();

        return $repairs;
    }

    public function getContainerComponent(Request $request)
    {
        $comp = ContainerComponent::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%');
        })->get();

        return $comp;
    }

    public function getClientById($id)
    {
        return Client::where('id', $id)->first();
    }

    public function getSizeTypeById($id)
    {
        return ContainerSizeType::where('id', $id)->first();
    }

    public function getStaffById($id)
    {
        return Staff::where('id', $id)->first();
    }

    public function getReceivingById($id)
    {
        return ContainerReceiving::where('id', $id)->with('client','sizeType','class','yardLocation')->first();
    }

    public function getReleasingById($id)
    {
        return ContainerReleasing::where('id', $id)->first();
    }

    public function getReceivingDetails(Request $request)
    {
        $contReleasing = Containers::where('container_no',$request->container_no)->whereNotNull('date_released')->whereNull('date_received')->latest('created_at')->first();
        $contRecieving = Containers::where('container_no',$request->container_no)->whereNull('date_released')->whereNotNull('date_received')->latest('created_at')->first();

        if($request->type == "receiving")
        {
            if($request->type == "receiving" && $contReleasing)
            {
                $message = 'Container '.$request->container_no.' is not in the yard';
                $status = 'error';
                return response()->json(compact('message','status'),404);
            }
            
            else
            {
                return null;
            }
        }
        else{
            if($request->type == "releasing" && $contRecieving)
            {
                $details = ContainerReceiving::where('container_no',$request->container_no)
                ->with('client:id,code_name','sizeType:id,code,name','class:id,class_code,class_name')
                ->select(
                    'id',
                    'client_id',
                    'size_type',
                    'class',
                    'empty_loaded',
                    'manufactured_date')->latest('created_at')->first();
    
                // if($details)
                // {
                   
                // }
                // else{
                //     $message = 'Container '.$request->container_no.' is not in the yard';
                //     $status = 'error';
                //     return response()->json(compact('message','status'),404);
                // }
                return $details;
            }
            else
            {
                $message = 'Container '.$request->container_no.' is not in the yard';
                $status = 'error';
                return response()->json(compact('message','status'),404);
            }
        }
    }

    public function prntReleasing($id)
    {
        $releasing = ContainerReleasing::where('id',$id)->first();
        $receiving_details = ContainerReceiving::where('container_no',$releasing->container_no)->with('sizeType:id,code')->latest('created_at')->first();
        return view('print_releasing')->with(compact('releasing', 'receiving_details'));
    }

    public function prntReceiving($id)
    {
        $receiving = ContainerReceiving::where('id',$id)->with('sizeType:id,code')->first();
        return view('print_receiving')->with('receiving', $receiving);
    }

    public function getReceivingDamage($receiving_id)
    {
        return ReceivingDamage::where('receiving_id',$receiving_id)->with('damage','component','repair')->get();
    }
}
