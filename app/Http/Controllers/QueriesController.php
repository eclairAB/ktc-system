<?php

namespace App\Http\Controllers;
use App\Models\ContainerClass;
use App\Models\Container;
use App\Models\ContainerSizeType;
use App\Models\ContainerComponent;
use App\Models\ContainerDamage;
use App\Models\ContainerRepair;
use App\Models\ContainerReceiving;
use App\Models\ContainerReleasing;
use App\Models\Client;
use DB;
use App\Models\Staff;
use App\Models\Checker;
use App\Models\Type;
use App\Models\ReceivingDamage;
use App\Models\YardLocation;
use App\Models\EirNumber;
use Carbon\Carbon;
use File;
use ZipArchive;
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
        })->get();

        return $client;
    }

    public function getClientByDateIn(Request $request)
    {
        $recs = ContainerReceiving::when($request->from, function ($q) use ($request){
            return $q->whereDate('inspected_date','>=',$request->from);
        })->when($request->to, function ($q) use ($request){
            return $q->whereDate('inspected_date','<=',$request->to);
        })->pluck('client_id');

        $client = Client::whereIn('id',$recs)->get();
        return $client;
    }

    public function getClientByDateOut(Request $request)
    {
        $rels = ContainerReleasing::when($request->from, function ($q) use ($request){
            return $q->whereDate('inspected_date','>=',$request->from);
        })->when($request->to, function ($q) use ($request){
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
        $imageUrl = file_get_contents( storage_path($path) );
        $image = base64_encode($imageUrl);
        return 'data:image/png;base64,'.$image;
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
        $data = ContainerReceiving::when($request->type != 'NA', function ($q) use($request){
            return $q->where('type_id',$request->type);
        })->when($request->sizeType != 'NA', function ($q) use($request){
            return $q->where('size_type',$request->sizeType);
        })->when($request->client != 'NA', function ($q) use($request){
            return $q->where('client_id',$request->client);
        })->when($request->class != 'NA', function ($q) use($request){
            return $q->where('class',$request->class);
        })->when($request->status != 'NA', function ($q) use($request){
            return $q->where('empty_loaded',$request->status);
        })->when($request->from != 'NA', function ($q) use($request){
            return $q->whereDate('inspected_date','>=',$request->from);
        })->when($request->to != 'NA', function ($q) use($request){
            return $q->whereDate('inspected_date','<=',$request->to);
        })->whereHas('container',function( $query ) use($request){
            $query->where('type_id',$request->type)->where('client_id',$request->client)->where('size_type',$request->sizeType);
        })->with('client','sizeType','containerClass','container.eirNoIn','type')->get();

        return $data;
    }

    public function getDailyOut(Request $request)
    {
        $data = ContainerReleasing::when($request->from != 'NA', function ($q) use($request){
            return $q->whereDate('inspected_date','>=',$request->from);
        })->when($request->to != 'NA', function ($q) use($request){
            return $q->whereDate('inspected_date','<=',$request->to);
        })->whereHas('container',function( $query ) use($request){
            $query->when($request->type != 'NA', function ($q) use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            });
        })->whereHas('receiving',function( $query ) use($request){
            $query->when($request->status != 'NA', function ($q) use($request){
                return $q->where('empty_loaded',$request->status);
            });
        })->with('container.client','container.eirNoOut','container.sizeType','container.type','container.containerClass','receiving')->get();

        return $data;
    }

    public function prntDailyIn(Request $request)
    {
        $data = ContainerReceiving::when($request->type != 'NA', function ($q) use($request){
            return $q->where('type_id',$request->type);
        })->when($request->sizeType != 'NA', function ($q) use($request){
            return $q->where('size_type',$request->sizeType);
        })->when($request->client != 'NA', function ($q) use($request){
            return $q->where('client_id',$request->client);
        })->when($request->class != 'NA', function ($q) use($request){
            return $q->where('class',$request->class);
        })->when($request->status != 'NA', function ($q) use($request){
            return $q->where('empty_loaded',$request->status);
        })->when($request->from != 'NA', function ($q) use($request){
            return $q->whereDate('inspected_date','>=',$request->from);
        })->when($request->to != 'NA', function ($q) use($request){
            return $q->whereDate('inspected_date','<=',$request->to);
        })->whereHas('container',function( $query ) use($request){
            $query->where('type_id',$request->type)->where('client_id',$request->client)->where('size_type',$request->sizeType);
        })->with('client','sizeType','containerClass','container.eirNoIn','type')->get();

        return view('print_container_in')->with(compact('data'));
    }

    public function prntDailyOut(Request $request)
    {
        $data = ContainerReleasing::when($request->from != 'NA', function ($q) use($request){
            return $q->whereDate('inspected_date','>=',$request->from);
        })->when($request->to != 'NA', function ($q) use($request){
            return $q->whereDate('inspected_date','<=',$request->to);
        })->whereHas('container',function( $query ) use($request){
            $query->when($request->type != 'NA', function ($q) use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            });
        })->whereHas('receiving',function( $query ) use($request){
            $query->when($request->status != 'NA', function ($q) use($request){
                return $q->where('empty_loaded',$request->status);
            });
        })->with('container.client','container.eirNoOut','container.sizeType','container.type','container.containerClass','receiving')->get();

        return view('print_container_out')->with(compact('data'));
    }

    public function prntAging(Request $request)
    {
        if($request->option == 'IN')
        {
            $data = Container::when($request->type != 'NA', function ($q)  use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            })->whereHas('receiving',function( $query ) use($request){
                $query->when($request->date_in_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_in_from);
                })->when($request->date_in_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_in_to);
                })->when($request->status != 'NA', function ($q) use($request){
                    return $q->where('empty_loaded',$request->status);
                });
            })->with('client','sizeType','containerClass','type','receiving')->orderBy('created_at','ASC')->get();
    
            foreach($data as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }
    
            $option = $request->option;
            $count = count($data);
            return view('print_aging')->with(compact('data','count','option'));
        }
        else if($request->option == 'OUT')
        {
            $data = Container::when($request->type != 'NA', function ($q)  use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            })->whereHas('releasing',function( $query ) use($request){
                $query->when($request->date_out_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_out_from);
                })->when($request->date_out_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_out_to);
                });
            })->whereHas('receiving',function( $query ) use($request){
                $query->when($request->status != 'NA', function ($q) use($request){
                    return $q->where('empty_loaded',$request->status);
                });
            })->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('created_at','ASC')->get();
    
            foreach($data as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }
    
            $option = $request->option;
            $count = count($data);
            return view('print_aging')->with(compact('data','count','option'));
        }
        else if($request->option == 'ALL')
        {
            $data = Container::when($request->type != 'NA', function ($q)  use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            })->whereHas('receiving',function( $query ) use($request){
                $query->when($request->date_in_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_in_from);
                })->when($request->date_in_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_in_to);
                })->when($request->status != 'NA', function ($q) use($request){
                    return $q->where('empty_loaded',$request->status);
                });
            })->whereHas('releasing',function( $query ) use($request){
                $query->when($request->date_out_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_out_from);
                })->when($request->date_out_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_out_to);
                });
            })->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('created_at','ASC')->get();
    
            foreach($data as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }

            $option = $request->option;
            $count = count($data);
            return view('print_aging')->with(compact('data','count','option'));
        }
       
    }

    public function getContainerAging(Request $request)
    {
        if($request->option == 'IN')
        {
            $data = Container::when($request->type != 'NA', function ($q)  use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            })->whereHas('receiving',function( $query ) use($request){
                $query->when($request->date_in_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_in_from);
                })->when($request->date_in_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_in_to);
                })->when($request->status != 'NA', function ($q) use($request){
                    return $q->where('empty_loaded',$request->status);
                });
            })->with('client','sizeType','containerClass','type','receiving')->orderBy('created_at','ASC')->get();
    
            foreach($data as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }
    
            return $data;
        }
        else if($request->option == 'OUT')
        {
            $data = Container::when($request->type != 'NA', function ($q)  use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            })->whereHas('releasing',function( $query ) use($request){
                $query->when($request->date_out_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_out_from);
                })->when($request->date_out_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_out_to);
                });
            })->whereHas('receiving',function( $query ) use($request){
                $query->when($request->status != 'NA', function ($q) use($request){
                    return $q->where('empty_loaded',$request->status);
                });
            })->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('created_at','ASC')->get();
    
            foreach($data as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }
    
            return $data;
        }
        else if($request->option == 'ALL')
        {
            $data = Continer::when($request->type != 'NA', function ($q)  use($request){
                return $q->where('type_id',$request->type);
            })->when($request->sizeType != 'NA', function ($q) use($request){
                return $q->where('size_type',$request->sizeType);
            })->when($request->client != 'NA', function ($q) use($request){
                return $q->where('client_id',$request->client);
            })->when($request->class != 'NA', function ($q) use($request){
                return $q->where('class',$request->class);
            })->whereHas('receiving',function( $query ) use($request){
                $query->when($request->date_in_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_in_from);
                })->when($request->date_in_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_in_to);
                })->when($request->status != 'NA', function ($q) use($request){
                    return $q->where('empty_loaded',$request->status);
                });
            })->whereHas('releasing',function( $query ) use($request){
                $query->when($request->date_out_from != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','>=',$request->date_out_from);
                })->when($request->date_out_to != 'NA', function ($q) use($request){
                    return $q->whereDate('inspected_date','<=',$request->date_out_to);
                });
            })->with('client','sizeType','containerClass','type','receiving','releasing')->orderBy('created_at','ASC')->get();
    
            foreach($data as $res)
            {
                $diff_days = Carbon::parse($res->receiving->inspected_date)->diffInDays('now');
                $res->total_no_days = $diff_days;
            }
    
            return $data;
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
            $q->with('containerClass','sizeType','receiving','releasing','client','eirNoIn','eirNoOut','type');
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

    public function saveImages($record_type, $container_no)
    {
        $path = storage_path() . '/app/public/uploads/receiving/container/' . $container_no . '/';

        array_map('unlink', glob($path."*.zip"));

        $zip = new ZipArchive;
        $fileName = 'container_photos.zip';
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
