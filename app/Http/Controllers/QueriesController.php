<?php

namespace App\Http\Controllers;
use App\Models\ContainerClass;
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
        $container_receiving = ContainerReceiving::where('id', $id)->with('client', 'sizeType', 'containerClass', 'yardLocation', 'inspector.staff', 'inspector.checker', 'photos')->first();
        $container_receiving->signature = [$this->imageEncode($container_receiving->signature)];
        foreach ($container_receiving->photos as $key => $value) {
            $container_receiving->photos[$key]->encoded = [$this->imageEncode($container_receiving->photos[$key]->storage_path)];
        }
        return $container_receiving;
    }

    public function getReleasingById($id)
    {
        $container_releasing = ContainerReleasing::where('id', $id)->with('inspector.staff', 'inspector.checker', 'photos')->first();
        $container_releasing->signature = [$this->imageEncode($container_releasing->signature)];
        foreach ($container_releasing->photos as $key => $value) {
            $container_releasing->photos[$key]->encoded = [$this->imageEncode($container_releasing->photos[$key]->storage_path)];
        }
        return $container_releasing;
    }

    public function geDetailsForUpdate(Request $request)
    {
        return ContainerReceiving::where('container_no',$request->container_no)
        ->with('client:id,code_name','sizeType:id,code,name','containerClass:id,class_code,class_name')
        ->select(
            'id',
            'client_id',
            'size_type',
            'containerClass',
            'empty_loaded',
            'manufactured_date')->latest('created_at')->first();
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
                return ContainerReceiving::where('container_no',$request->container_no)
                ->with('client:id,code_name','sizeType:id,code,name','containerClass:id,class_code,class_name')
                ->select(
                    'id',
                    'client_id',
                    'size_type',
                    'containerClass',
                    'empty_loaded',
                    'manufactured_date')->latest('created_at')->first();
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
        $size_types = ContainerSizeType::get();
        return view('print_releasing')->with(compact('releasing', 'receiving_details', 'size_types'));
    }

    public function prntReceiving($id)
    {
        $receiving = ContainerReceiving::where('id',$id)->with('sizeType:id,code')->first();
        $damages = ReceivingDamage::where('receiving_id',$id)->get();
        $size_types = ContainerSizeType::get();
        return view('print_receiving')->with(compact('receiving', 'damages', 'size_types'));
    }

    public function getReceivingDamage($receiving_id)
    {
        return ReceivingDamage::where('receiving_id',$receiving_id)->with('damage','component','repair')->get();
    }

    function imageEncode($path) {
        $imageUrl = file_get_contents( storage_path() . $path );
        $image = base64_encode($imageUrl);
        return 'data:image/png;base64,'.$image;
    }
}
