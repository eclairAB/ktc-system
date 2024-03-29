<?php

namespace App\Http\Controllers;
use App\Http\Requests\ValidateContainerAging;
use App\Models\Checker;
use App\Models\Client;
use App\Models\Container;
use App\Models\ContainerClass;
use App\Models\ContainerComponent;
use App\Models\ContainerDamage;
use App\Models\ContainerReceiving;
use App\Models\ContainerReleasing;
use App\Models\ContainerRepair;
use App\Models\ContainerSizeType;
use App\Models\EirNumber;
use App\Models\ReceivingDamage;
use App\Models\Staff;
use App\Models\Type;
use App\Models\YardLocation;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Http\Request;
use ZipArchive;

class QueriesController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    //
    public function getContainterClass(Request $request)
    {
        $class = ContainerClass::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('class_code', 'ilike', '%'.$request->keyword.'%')
            ->orwhere('class_name', 'ilike', '%'.$request->keyword.'%');
        })->orderBy('class_name','ASC')->get();

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
            return $q->where('size', 'ilike', '%'.$request->keyword.'%');
            // ->orwhere('name', 'ilike', '%'.$request->keyword.'%');
        })->orderBy('size','ASC')->get();

        return $sizetype;
    }
    public function getClient(Request $request)
    {
        $client = Client::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%') 
            ->orwhere('name', 'ilike', '%'.$request->keyword.'%');
        })->orderBy('name','ASC')->get();

        return $client;
    }

    public function getClientByDateIn(Request $request)
    {
        $recs = ContainerReceiving::when($request->from != 'NA', function ($q) use ($request){
            return $q->whereDate('inspected_date','>=',$request->from);
        })->when($request->to != 'NA', function ($q) use ($request){
            return $q->whereDate('inspected_date','<=',$request->to);
        })->pluck('client_id');

        $client = Client::whereIn('id',$recs)->get();
        return $client;
    }

    public function getClientByDateOut(Request $request)
    {
        $rels = ContainerReleasing::when($request->from != 'NA', function ($q) use ($request){
            return $q->whereDate('inspected_date','>=',$request->from);
        })->when($request->to != 'NA', function ($q) use ($request){
            return $q->whereDate('inspected_date','<=',$request->to);
        })->pluck('id');
        $conts = Container::whereIn('releasing_id',$rels)->pluck('client_id');

        $client = Client::whereIn('id',$conts)->get();
        return $client;
    }

    public function getYardLocation(Request $request)
    {
        $yardloc = YardLocation::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('name', 'ilike', '%'.$request->keyword.'%');
        })->orderBy('name','ASC')->get();

        return $yardloc;
    }

    public function getContainerDamage(Request $request)
    {
        $dmgs = ContainerDamage::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%');
        })->orderBy('code','ASC')->get();

        return $dmgs;
    }

    public function getContainerRepair(Request $request)
    {
        $repairs = ContainerRepair::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%');
        })->orderBy('code','ASC')->get();

        return $repairs;
    }

    public function getContainerComponent(Request $request)
    {
        $comp = ContainerComponent::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%');
        })->orderBy('code','ASC')->get();

        return $comp;
    }
    public function getComponentsById($id)
    {
        return ContainerComponent::where('id', $id)->first();
    }
    public function getRepairsById($id)
    {
        return ContainerRepair::where('id', $id)->first();
    }
    public function getDamagesById($id)
    {
        return ContainerDamage::where('id', $id)->first();
    }
    public function getClassById($id)
    {
        return ContainerClass::where('id', $id)->first();
    }
    public function getYardById($id)
    {
        return YardLocation::where('id', $id)->first();
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

    public function getCheckerById($id)
    {
        return Checker::where('id', $id)->first();
    }

    public function getReceivingById($id)
    {
        $container_receiving = ContainerReceiving::where('id', $id)->with('client', 'sizeType', 'containerClass', 'yardLocation', 'inspector.staff', 'inspector.checker', 'photos', 'type')->first();
        foreach ($container_receiving->photos as $key => $value) {
            $container_receiving->photos[$key]->encoded = [$this->imageEncode('/app/public/uploads/receiving/container/' . $container_receiving->id . '/' . $value['photo'])];
        }
        return $container_receiving;
    }

    public function getReleasingById($id)
    {
        $container_releasing = ContainerReleasing::where('id', $id)->with('inspector.staff', 'inspector.checker', 'photos')->first();
        foreach ($container_releasing->photos as $key => $value) {
            $container_releasing->photos[$key]->encoded = [$this->imageEncode('/app/public/uploads/releasing/container/' . $container_releasing->id . '/' . $value['photo'])];
        }
        return $container_releasing;
    }

    public function geDetailsForUpdate(Request $request)
    {
        return ContainerReceiving::where('container_no',$request->container_no)
        ->with('client:id,code,name','sizeType:id,size','containerClass:id,class_code,class_name','type:id,code,name')
        ->select(
            'id',
            'client_id',
            'size_type',
            'class',
            'empty_loaded',
            'manufactured_date',
            'type_id')->latest('created_at')->first();
    }

    public function getReceivingDetails(Request $request)
    {
        $contReleasing = Container::where('container_no',$request->container_no)->whereNotNull('releasing_id')->whereNull('receiving_id')->latest('created_at')->first();
        $contRecieving = Container::where('container_no',$request->container_no)->whereNull('releasing_id')->whereNotNull('receiving_id')->latest('created_at')->first();

        if($request->type == "receiving")
        {
            if($request->type == "receiving" && $contReleasing)
            {
                $message = 'Container '.$request->container_no.' is not in the yard';
                $status = 'error';
                return response()->json(compact('message','status'),404);
            }
            else if($request->type == "receiving" && $contRecieving)
            {
                $message = 'Container '.$request->container_no.' is already in the yard';
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
                ->with('client:id,code,name','sizeType:id,size','type:id,code,name','containerClass:id,class_code,class_name')
                ->select(
                    'id',
                    'client_id',
                    'size_type',
                    'class',
                    'empty_loaded',
                    'manufactured_date','type_id')->latest('created_at')->first();
            }
            else
            {
                $message = 'Container '.$request->container_no.' is not in the yard';
                $status = 'error';
                return response()->json(compact('message','status'),404);
            }
        }
    }

    public function getReceivingDamage($receiving_id)
    {
        return ReceivingDamage::where('receiving_id',$receiving_id)->with('damage','component','repair')->get();
    }

    function imageEncode($path) {
        if(file_exists( storage_path($path) )) {
            $imageUrl = file_get_contents( storage_path($path) );
            $image = base64_encode($imageUrl);
            return 'data:image/png;base64,'.$image;
        }
        else {
            return null;
        }
    }

    public function getSizeTypeByAll()
    {
        return ContainerSizeType::get();
    }

    public function getContainerNos()
    {
        return ContainerReceiving::distinct('container_no')->pluck('container_no');
    }

    public function getBookingNos()
    {
        return ContainerReleasing::distinct('booking_no')->pluck('booking_no');
    }

    public function getContainerNosByBookingNo(Request $request)
    {
        return ContainerReleasing::where('booking_no',$request->booking_no)->distinct('container_no')->pluck('container_no');
    }

    public function getDailyIn(Request $request)
    {   
        $data = Container::when($request->type != 'NA', function ($q)  use($request){
            return $q->where('type_id',$request->type);
        })->when($request->sizeType != 'NA', function ($q) use($request){
            return $q->where('size_type',$request->sizeType);
        })->when($request->client != 'NA', function ($q) use($request){
            return $q->where('client_id',$request->client);
        })->when($request->class != 'NA', function ($q) use($request){
            return $q->where('class',$request->class);
        })->when($request->status != 'NA', function ($q) use($request){
            return $q->where('status',$request->status);
        })->whereHas('receiving',function( $query ) use($request){
            $query->when($request->from != 'NA', function ($q) use($request){
                return $q->whereDate('inspected_date','>=',$request->from);
            })->when($request->to != 'NA', function ($q) use($request){
                return $q->whereDate('inspected_date','<=',$request->to);
            });
        })->whereNotNull('receiving_id')->whereNull('releasing_id')->with('client','sizeType','containerClass','eirNoIn','type','receiving.damages')->get();

        if($request->param == 'container_no'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'eir_no'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('eirNoIn.eir_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('eirNoIn.eir_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'client'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('client.code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('client.code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'type'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('type.code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('type.code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'size_type'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('size_type.size');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('size_type.size');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'container_class'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_class.class_code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_class.class_code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'inspected_date'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.inspected_date');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'remarks'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.remarks');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.remarks');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'consignee'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.consignee');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.consignee');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'plate_no'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.plate_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.plate_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'hauler'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.hauler');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.hauler');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }
    }

    public function getDailyOut(Request $request)
    {
        $data = Container::when($request->type != 'NA', function ($q)  use($request){
            return $q->where('type_id',$request->type);
        })->when($request->sizeType != 'NA', function ($q) use($request){
            return $q->where('size_type',$request->sizeType);
        })->when($request->client != 'NA', function ($q) use($request){
            return $q->where('client_id',$request->client);
        })->when($request->class != 'NA', function ($q) use($request){
            return $q->where('class',$request->class);
        })->when($request->status != 'NA', function ($q) use($request){
            return $q->where('status',$request->status);
        })->whereHas('releasing',function( $query ) use($request){
            $query->when($request->from != 'NA', function ($q) use($request){
                return $q->whereDate('inspected_date','>=',$request->from);
            })->when($request->to != 'NA', function ($q) use($request){
                return $q->whereDate('inspected_date','<=',$request->to);
            });
        })->whereNotNull('releasing_id')->whereNotNull('receiving_id')->with('client','sizeType','containerClass','type','receiving','releasing','eirNoOut')->get();

        if($request->param == 'container_no'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'eir_no'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('eirNoOut.eir_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('eirNoOut.eir_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'client'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('client.code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('client.code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'type'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('type.code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('type.code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'size_type'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('size_type.size');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('size_type.size');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'container_class'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_class.class_code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_class.class_code');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'inspected_date'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.inspected_date');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'remarks'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.remarks');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.remarks');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'consignee'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.consignee');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.consignee');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'plate_no'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.plate_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.plate_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'hauler'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.hauler');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.hauler');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'booking_no'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.booking_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }else if($request->param == 'seal_no'){
            $tobesorted = collect($data);
            if($request->order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.seal_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                $data = $sorted->values()->all();
                $count = count($data);
                return response()->json(compact('data','count'));
            }
        }
    }

    public function prntDailyIn($type,$sizeType,$client,$class,$status,$from,$to,$param,$order)
    {
        $data = Container::when($type != 'NA', function ($q)  use($type){
            return $q->where('type_id',$type);
        })->when($sizeType != 'NA', function ($q) use($sizeType){
            return $q->where('size_type',$sizeType);
        })->when($client != 'NA', function ($q) use($client){
            return $q->where('client_id',$client);
        })->when($class != 'NA', function ($q) use($class){
            return $q->where('class',$class);
        })->when($status != 'NA', function ($q) use($status){
            return $q->where('status',$status);
        })->whereHas('receiving',function( $query ) use($from,$to){
            $query->when($from != 'NA', function ($q) use($from){
                return $q->whereDate('inspected_date','>=',$from);
            })->when($to != 'NA', function ($q) use($to){
                return $q->whereDate('inspected_date','<=',$to);
            });
        })->whereNotNull('receiving_id')->whereNull('releasing_id')->with('client','sizeType','containerClass','eirNoIn','type','receiving.damages')->get();

        if($param == 'container_no'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_no');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_no');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'eir_no'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('eirNoIn.eir_no');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('eirNoIn.eir_no');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'client'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('client.code');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('client.code');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'type'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('type.code');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('type.code');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'size_type'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('size_type.size');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('size_type.size');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'container_class'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_class.class_code');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_class.class_code');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'inspected_date'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.inspected_date');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'remarks'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.remarks');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.remarks');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'consignee'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.consignee');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.consignee');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'plate_no'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.plate_no');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.plate_no');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }else if($param == 'hauler'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('receiving.hauler');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('receiving.hauler');
                $datus = $sorted->values()->all();
                return view('print_container_in')->with(compact('datus'));
            }
        }   
    }

    public function prntDailyOut($type,$sizeType,$client,$class,$status,$from,$to,$param,$order)
    {
        $data = Container::when($type != 'NA', function ($q)  use($type){
            return $q->where('type_id',$type);
        })->when($sizeType != 'NA', function ($q) use($sizeType){
            return $q->where('size_type',$sizeType);
        })->when($client != 'NA', function ($q) use($client){
            return $q->where('client_id',$client);
        })->when($class != 'NA', function ($q) use($class){
            return $q->where('class',$class);
        })->when($status != 'NA', function ($q) use($status){
            return $q->where('status',$status);
        })->whereHas('releasing',function( $query ) use($from,$to){
            $query->when($from != 'NA', function ($q) use($from){
                return $q->whereDate('inspected_date','>=',$from);
            })->when($to != 'NA', function ($q) use($to){
                return $q->whereDate('inspected_date','<=',$to);
            });
        })->whereNotNull('releasing_id')->whereNotNull('receiving_id')->with('client','sizeType','containerClass','type','receiving','releasing','eirNoOut')->get();

        if($param == 'container_no'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'eir_no'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('eirNoOut.eir_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('eirNoOut.eir_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'client'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('client.code');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('client.code');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'type'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('type.code');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('type.code');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'size_type'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('size_type.size');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('size_type.size');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'container_class'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('container_class.class_code');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('container_class.class_code');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'inspected_date'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.inspected_date');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'remarks'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.remarks');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.remarks');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'consignee'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.consignee');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.consignee');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'plate_no'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.plate_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.plate_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'hauler'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.hauler');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.hauler');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'booking_no'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.booking_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }else if($param == 'seal_no'){
            $tobesorted = collect($data);
            if($order == 'ASC'){
                $sorted = $tobesorted->sortBy('releasing.seal_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }else{
                $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                $datus = $sorted->values()->all();
                return view('print_container_out')->with(compact('datus'));
            }
        }
    }

    public function prntAging($type,$sizeType,$client,$class,$date_in_from,$date_in_to,$date_out_from,$date_out_to,$option,$status,$param,$order)
    {
        if($option == 'IN')
        {
            $datus = Container::when($type != 'NA', function ($q)  use($type){
                return $q->where('type_id',$type);
            })->when($sizeType != 'NA', function ($q) use($sizeType){
                return $q->where('size_type',$sizeType);
            })->when($client != 'NA', function ($q) use($client){
                return $q->where('client_id',$client);
            })->when($class != 'NA', function ($q) use($class){
                return $q->where('class',$class);
            })->when($status != 'NA', function ($q) use($status){
                return $q->where('status',$status);
            })->whereHas('receiving',function( $query ) use($date_in_from,$date_in_to){
                $query->when($date_in_from != 'NA', function ($q) use($date_in_from){
                    return $q->whereDate('inspected_date','>=',$date_in_from);
                })->when($date_in_to != 'NA', function ($q) use($date_in_to){
                    return $q->whereDate('inspected_date','<=',$date_in_to);
                });
            })->whereNotNull('receiving_id')->whereNull('releasing_id')->with('client','sizeType','containerClass','type','receiving')->get();
    
            foreach($datus as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days + 1;
            }

            if($param == 'container_no'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'status'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('status');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('status');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'client'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('client.code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('client.code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'type'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('type.code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('type.code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'size_type'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('size_type.size');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('size_type.size');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'container_class'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_class.class_code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_class.class_code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'receiving_inspected_date'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'receiving_consignee'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.consignee');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.consignee');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'remarks'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.remarks');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.remarks');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'releasing_inspected_date'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'releasing_consignee'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.consignee');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.consignee');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'booking_no'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'seal_no'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'total_no_days'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('total_no_days');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('total_no_days');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = count($data);
                    $out = 0;
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }
        }
        else if($option == 'OUT')
        {
            $datus = Container::when($type != 'NA', function ($q)  use($type){
                return $q->where('type_id',$type);
            })->when($sizeType != 'NA', function ($q) use($sizeType){
                return $q->where('size_type',$sizeType);
            })->when($client != 'NA', function ($q) use($client){
                return $q->where('client_id',$client);
            })->when($class != 'NA', function ($q) use($class){
                return $q->where('class',$class);
            })->when($status != 'NA', function ($q) use($status){
                return $q->where('status',$status);
            })->whereHas('releasing',function( $query ) use($date_out_from,$date_out_to){
                $query->when($date_out_from != 'NA', function ($q) use($date_out_from){
                    return $q->whereDate('inspected_date','>=',$date_out_from);
                })->when($date_out_to != 'NA', function ($q) use($date_out_to){
                    return $q->whereDate('inspected_date','<=',$date_out_to);
                });
            })->whereNotNull('releasing_id')->whereNotNull('receiving_id')->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('container_no','ASC')->get();
    
            foreach($datus as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays($res->releasing->inspected_date);
                $res->total_no_days = $diff_days + 1;
            }

            if($param == 'container_no'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_no');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_no');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'status'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('status');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('status');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'client'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('client.code');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('client.code');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'type'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('type.code');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('type.code');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'size_type'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('size_type.size');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('size_type.size');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'container_class'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_class.class_code');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_class.class_code');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'receiving_inspected_date'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.inspected_date');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'receiving_consignee'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.consignee');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.consignee');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'remarks'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.remarks');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.remarks');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'releasing_inspected_date'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.inspected_date');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'releasing_consignee'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.consignee');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.consignee');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'booking_no'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.booking_no');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'seal_no'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.seal_no');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }else if($param == 'total_no_days'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('total_no_days');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('total_no_days');
                    $data = $sorted->values()->all();
                     $client_id = $client != 'NA'?$client:null;
                    $client = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = 0;
                    $out = count($data);
                    return view('print_aging')->with(compact('data','count','option','client','in','out'));
                }
            }
        }
        else if($option == 'ALL')
        {
            $datus = Container::when($type != 'NA', function ($q)  use($type){
                return $q->where('type_id',$type);
            })->when($sizeType != 'NA', function ($q) use($sizeType){
                return $q->where('size_type',$sizeType);
            })->when($client != 'NA', function ($q) use($client){
                return $q->where('client_id',$client);
            })->when($class != 'NA', function ($q) use($class){
                return $q->where('class',$class);
            })->when($status != 'NA', function ($q) use($status){
                return $q->where('status',$status);
            })->where(function($q) {
                $q->has('receiving')->orHas('releasing');
            })->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('container_no','ASC')->get();
    
            foreach($datus as $res)
            {
                $diff_days = isset($res->releasing)?Carbon::parse($res->receiving->inspected_date)->diffInDays($res->releasing->inspected_date):Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days + 1;
            }

            if($param == 'container_no'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'status'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('status');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('status');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'client'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('client.code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('client.code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'type'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('type.code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('type.code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'size_type'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('size_type.size');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('size_type.size');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'container_class'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_class.class_code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_class.class_code');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'receiving_inspected_date'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'receiving_consignee'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.consignee');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.consignee');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'remarks'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.remarks');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.remarks');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'releasing_inspected_date'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'releasing_consignee'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.consignee');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.consignee');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'booking_no'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'seal_no'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }else if($param == 'total_no_days'){
                $tobesorted = collect($datus);
                if($order == 'ASC'){
                    $sorted = $tobesorted->sortBy('total_no_days');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }else{
                    $sorted = $tobesorted->sortByDesc('total_no_days');
                    $data = $sorted->values()->all();
                    $client_id = $client != 'NA'?$client:null;
                    $client_details = Client::where('id',$client_id)->first();
                    $count = count($data);
                    $in = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($type != 'NA', function ($q)  use($type){
                        return $q->where('type_id',$type);
                    })->when($sizeType != 'NA', function ($q) use($sizeType){
                        return $q->where('size_type',$sizeType);
                    })->when($client != 'NA', function ($q) use($client){
                        return $q->where('client_id',$client);
                    })->when($class != 'NA', function ($q) use($class){
                        return $q->where('class',$class);
                    })->when($status != 'NA', function ($q) use($status){
                        return $q->where('status',$status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return view('print_aging')->with(compact('data','in','out','count','option','client_details'));
                }
            }
        }
       
    }

    public function getContainerAging(Request $request)
    {
        if($request->option == 'IN')
        {
            $datus = Container::when($request->type != 'NA', function ($q)  use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            })->when($request->status != 'NA', function ($q) use($request){
                return $q->where('status',$request->status);
            })->whereHas('receiving',function( $query ) use($request){
                $query->when($request->date_in_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_in_from);
                })->when($request->date_in_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_in_to);
                });
            })->whereNotNull('receiving_id')->whereNull('releasing_id')->with('client','sizeType','containerClass','type','receiving.damages')->get();
    
            foreach($datus as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days + 1;
            }

            if($request->param == 'container_no'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'status'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('status');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('status');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'client'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('client.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('client.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'type'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('type.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('type.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'size_type'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('size_type.size');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('size_type.size');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'container_class'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_class.class_code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_class.class_code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'receiving_inspected_date'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'receiving_consignee'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'remarks'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.remarks');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.remarks');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'releasing_inspected_date'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'releasing_consignee'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'booking_no'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'seal_no'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'total_no_days'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('total_no_days');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('total_no_days');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = count($data);
                    $out = 0;
                    return response()->json(compact('data','van_count','in','out'));
                }
            }
        }
        else if($request->option == 'OUT')
        {
            $datus = Container::when($request->type != 'NA', function ($q)  use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            })->when($request->status != 'NA', function ($q) use($request){
                return $q->where('status',$request->status);
            })->whereHas('releasing',function( $query ) use($request){
                $query->when($request->date_out_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_out_from);
                })->when($request->date_out_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_out_to);
                });
            })->whereNotNull('releasing_id')->whereNotNull('receiving_id')->with('client','sizeType','containerClass','type','receiving.damages','releasing')->orderBy('container_no','ASC')->get();
    
            foreach($datus as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays($res->releasing->inspected_date);
                $res->total_no_days = $diff_days + 1;
            }
            
            if($request->param == 'container_no'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'status'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('status');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('status');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'client'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('client.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('client.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'type'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('type.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('type.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'size_type'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('size_type.size');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('size_type.size');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'container_class'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_class.class_code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_class.class_code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'receiving_inspected_date'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'receiving_consignee'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'remarks'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.remarks');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.remarks');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'releasing_inspected_date'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'releasing_consignee'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'booking_no'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'seal_no'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'total_no_days'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('total_no_days');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('total_no_days');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = 0;
                    $out = count($data);
                    return response()->json(compact('data','van_count','in','out'));
                }
            }
        }
        else if($request->option == 'ALL')
        {
            $datus = Container::when($request->type != 'NA', function ($q)  use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            })->when($request->status != 'NA', function ($q) use($request){
                return $q->where('status',$request->status);
            })->where(function($q) {
                $q->has('receiving')->orHas('releasing');
            })->with('client','sizeType','containerClass','type','receiving.damages','releasing')->orderBy('container_no','ASC')->get();
    
            foreach($datus as $res)
            {
                $diff_days = isset($res->releasing)?Carbon::parse($res->receiving->inspected_date)->diffInDays($res->releasing->inspected_date):Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days + 1;
            }

            if($request->param == 'container_no'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'status'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('status');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('status');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'client'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('client.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('client.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'type'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('type.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('type.code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'size_type'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('size_type.size');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('size_type.size');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'container_class'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('container_class.class_code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('container_class.class_code');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'receiving_inspected_date'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'receiving_consignee'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'remarks'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('receiving.remarks');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('receiving.remarks');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'releasing_inspected_date'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.inspected_date');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'releasing_consignee'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.consignee');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'booking_no'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.booking_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'seal_no'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('releasing.seal_no');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }else if($request->param == 'total_no_days'){
                $tobesorted = collect($datus);
                if($request->order == 'ASC'){
                    $sorted = $tobesorted->sortBy('total_no_days');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }else{
                    $sorted = $tobesorted->sortByDesc('total_no_days');
                    $data = $sorted->values()->all();
                    $van_count = count($data);
                    $in = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('receiving')->whereNotNull('receiving_id')->whereNull('releasing_id')->count();
                    $out = Container::when($request->type != 'NA', function ($q)  use($request){
                        return $q->where('type_id',$request->type);
                    })->when($request->sizeType != 'NA', function ($q) use($request){
                        return $q->where('size_type',$request->sizeType);
                    })->when($request->client != 'NA', function ($q) use($request){
                        return $q->where('client_id',$request->client);
                    })->when($request->class != 'NA', function ($q) use($request){
                        return $q->where('class',$request->class);
                    })->when($request->status != 'NA', function ($q) use($request){
                        return $q->where('status',$request->status);
                    })->has('releasing')->whereNotNull('receiving_id')->whereNotNull('releasing_id')->count();
                    return response()->json(compact('data','van_count','in','out'));
                }
            }
        }
    }

    public function containerInquiry(Request $request, $container_no)
    {
        if($container_no == 'browse')
        {
            $q = Container::select(
                'id',
                DB::raw('container_no'),
                'client_id',
                'size_type',
                'class',
                'receiving_id',
                'releasing_id',
                'type_id',
                'status'
            );
            // $q->select(
            //     DB::raw('container_no'),
            //     'client_id',
            //     'size_type',
            //     'class',
            //     'receiving_id',
            //     'releasing_id',
            // );
            if ( isset($request->search_input)) {
                $q->where('container_no', 'ilike', '%' . $request->search_input . '%');
            }
            $q->with('containerClass','sizeType','receiving','releasing','client','eirNoIn','eirNoOut','type','receiving.damages');
            $q->orderBy('id','DESC');
            $containers = $q->paginate(15);
            return view('vendor.voyager.container-inquiry.browse', ['containers' => $containers]);
        }
        // else 
        // {
        //     $receiving = ContainerReceiving::where('container_no', $container_no)
        //         ->with('client', 'inspector', 'photos', 'sizeType', 'type', 'yardLocation', 'containerClass','damages')
        //         ->orderBy('id','DESC')
        //         ->paginate(
        //             $perPage = 15, $columns = ['*'], $pageName = 'receiving_page'
        //         );

        //     $releasing = ContainerReleasing::where('container_no', $container_no)
        //         ->with('inspector', 'photos', 'container.receiving.client')
        //         ->orderBy('id','DESC')
        //         ->paginate(
        //             $perPage = 15, $columns = ['*'], $pageName = 'releasing_page'
        //         );

        //     return view('vendor.voyager.container-inquiry.read', ['receiving' => $receiving, 'releasing' => $releasing]);
        // }
    }

    public function prntReleasing($id)
    {
        $releasing = ContainerReleasing::where('id',$id)->first();
        $receiving_details = ContainerReceiving::where('container_no',$releasing->container_no)->with('sizeType:id,size','type:id,code','container')->latest('created_at')->first();
        $eirNumber = EirNumber::where('eir_no','ilike','%O-%')->where('container_id',$receiving_details->container->id)->first();
        return view('print_releasing')->with(compact('releasing', 'receiving_details','eirNumber'));
    }

    public function prntReceiving($id)
    {
        $receiving = ContainerReceiving::where('id',$id)->with('sizeType:id,size','type:id,code','container')->first();
        $damages = ReceivingDamage::where('receiving_id',$id)->get();
        $eirNumber = EirNumber::where('eir_no','ilike','%I-%')->where('container_id',$receiving->container->id)->first();
        return view('print_receiving')->with(compact('receiving', 'damages','eirNumber'));
    }

    public function saveImages($record_type, $container_id)
    {
        $now = Carbon::now()->format('Y-m-d');
        
        if($record_type == 'receiving')
        {
            $path = storage_path() . '/app/public/uploads/receiving/container/' . $container_id . '/';
            $receiving = ContainerReceiving::where('id',$container_id)->select('container_no')->first();
        }
        else
        {
            $path = storage_path() . '/app/public/uploads/releasing/container/' . $container_id . '/';
            $releasing = ContainerReleasing::where('id',$container_id)->select('container_no')->first();
        }

        array_map('unlink', glob($path."*.zip"));

        $zip = new ZipArchive;
        $fileName = $record_type == 'receiving'?$receiving->container_no.'(IN)('.$now.').zip':$releasing->container_no.'(OUT)('.$now.').zip';
        if ($zip->open($path . $fileName, ZipArchive::CREATE) === TRUE) {
            $files = File::files($path);
            foreach ($files as $key => $value) {
                $relativeName = basename($value);
                $zip->addFile($value, $relativeName);
            }
            $zip->close();
        }
        return response()->download($path . $fileName);
    }

    public function getType(Request $request)
    {
        $type = Type::when(!empty($request->keyword), function ($q) use ($request){
            return $q->where('code', 'ilike', '%'.$request->keyword.'%')
            ->orwhere('name', 'ilike', '%'.$request->keyword.'%');
        })->orderBy('id','ASC')->get();

        return $type;
    }

    public function getTypeById($id)
    {
        return Type::where('id', $id)->first();
    }

    public function getTypeByAll()
    {
        return Type::get();
    }

    public function getEmptyLoaded(Request $request)
    {
        $path = file_get_contents(resource_path()."/js/mixins/emptyloaded.json");
        $emptyloaded = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $path), true );

        return $emptyloaded;
    }
}
