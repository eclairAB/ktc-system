<?php

namespace App\Http\Controllers;
use App\Http\Requests\ValidateClientField;
use App\Http\Requests\ValidateStaffField;
use App\Http\Requests\ValidateCheckerField;
use App\Http\Requests\ValidateContainerReleasing;
use App\Http\Requests\ValidateContainerReceiving;
use App\Http\Requests\ValidateSizeType;
use App\Http\Requests\ValidateReceivingDamage;
use App\Http\Requests\ValidateEmptyReceivingDamage;
use App\Http\Requests\ValidateType;
use App\Http\Requests\ValidateComponents;
use App\Http\Requests\ValidateDamages;
use App\Http\Requests\ValidateRepairs;
use App\Http\Requests\ValidateContainerClass;
use App\Http\Requests\ValidateYardLocation;
use App\Models\ContainerSizeType;
use App\Models\Client;
use App\Models\Checker;
use App\Models\Staff;
use App\Models\User;
use App\Models\ContainerReleasing;
use App\Models\ContainerReceiving;
use App\Models\Container;
use App\Models\ContainerRemark;
use App\Models\ReceivingDamage;
use App\Models\ContainerComponent;
use App\Models\ContainerDamage;
use App\Models\ContainerRepair;
use App\Models\ContainerClass;
use App\Models\YardLocation;
use App\Models\Type;
use App\Models\EirNumber;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    protected function createUser($params, $role)
    {
        if ($this->userExists($params->user_id)) return 'exists';

        $dbrole = DB::table('roles')->where('name', $role)->pluck('id');
        if(!isset($dbrole[0])) return 'no_role';
        $credentials = [
            'name' => $this->setName($params),
            'role_id' => $dbrole[0],
        ];
        if(isset($params->email)) {
            $credentials['email'] = $params->email;
        }
        else {
            $dbuser = User::latest('id')->first();
            if(is_null($dbuser)) $dbuser = 1;
            else $dbuser = $dbuser->id+1;
            $credentials['email'] = $role . $dbuser . '@kudostrucking.com';
        }
        if(isset($params->password)) {
            $credentials['password'] = bcrypt($params->password);
        }
        else {
            $credentials['password'] = bcrypt('password');
        }

        return User::create($credentials);
    }

    protected function setName($params)
    {
        if(isset($params->code_name))
        {
            return $params->code_name;
        }
        else
        {
            return strtoupper($params->firstname . ' ' . $params->lastname);
        }
    }

    protected function userExists($user_id)
    {
        $staff = Staff::where('user_id', $user_id)->pluck('user_id');
        if(count($staff) > 0) return true;
        
        $checker = Checker::where('user_id', $user_id)->pluck('user_id');
        if(count($checker) > 0) return true;

        // $client = Client::where('user_id', $user_id)->pluck('user_id');
        // if(count($client) > 0) return true;
        else return false;
    }

    public function createClient(ValidateClientField $request)
    {
        // $account = $this->createUser($request, 'client');
        // if( $account=='exists') return response('Username already in use.', 400);
        // elseif ($account=='no_role') return response('Role "client" does not exist!', 400);
        $validate_client = $request->validated();
        $params = [
            // 'account_id' => $account->id,
            'code' => $validate_client['code'],
            'name' => $validate_client['name'],
            // 'contact_no' => $request->contact_no,
            // 'user_id' => $request->user_id,
        ];

        return Client::create($params);
        // $account['client'] = $client;
    }

    public function createStaff(ValidateStaffField $request)
    {
        $account = $this->createUser($request, 'staff');
        if( $account=='exists') return response('Username already in use.', 400);
        elseif ($account=='no_role') return response('Role "staff" does not exist!', 400);

        $params = [
            'account_id' => $account->id,
            'id_no' => $request->id_no,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_id' => $request->user_id,
            // 'contact_no' => $request->contact_no,
        ];

        $staff = Staff::create($params);
        $account['staff'] = $staff;
        return $account;
    }

    public function createChecker(ValidateCheckerField $request)
    {
        $account = $this->createUser($request, 'checker');
        if( $account=='exists') return response('Username already in use.', 400);
        elseif ($account=='no_role') return response('Role "checker" does not exist!', 400);

        $params = [
            'account_id' => $account->id,
            'id_no' => $request->id_no,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_id' => $request->user_id,
            // 'contact_no' => $request->contact_no,
        ];

        $checker = Checker::create($params);
        $account['checker'] = $checker;
        return $account;
    }

    public function createReleasing(ValidateContainerReleasing $request)
    {
        $releasing = $request->validated();

        $receiving = ContainerReceiving::where('container_no',$releasing['container_no'])->first();
        if($receiving)
        {
            if($releasing['container_photo']) {
                foreach ($releasing['container_photo'] as $key => $value) {
                    $params = [];
                    $params['file_name'] = Str::random(32);
                    $params['type'] = 'releasing';
                    $container_photo[] = array(
                        'container_type' => $params['type'],
                        'photo' => $params['file_name'] . '.png',
                        'params' => $params,
                        'base64_file' => $releasing['container_photo'][$key]['storage_path'],
                    );
                }
                $release = ContainerReleasing::create($releasing)->photos()->createMany($container_photo);
                $rel_id = $release[0]->container_id;

                foreach($container_photo as $key => $value) {
                    $this->imageUpload($value['params'], $value['base64_file'],  $release[0]->container_id);
                }
            }
            else {
                $release = ContainerReleasing::create($releasing);
                $rel_id = $release->id;
            }

            if($release)
            {
                $dataCont = Container::where('container_no',$releasing['container_no'])->whereNotNull('receiving_id')->latest('created_at')->first();
                $dataCont->releasing_id = $rel_id;
                $dataCont->save();

                $dataContRemark = [
                    'status'=>'Released',
                    'container_id'=>$dataCont->id,
                    'remarks'=>$request->remarks,
                ];
                ContainerRemark::create($dataContRemark);
                $this->createEir(false, $dataCont->id);
            }

            return $release;
        }
        else{
            $message = 'Container '.$request->container_no.' is not in the yard';
            $status = 'error';
            return response()->json(compact('message','status'),404);
        }
    }

    public function createReceiving(ValidateContainerReceiving $request)
    {
        $receiving = $request->validated();
        if($receiving['container_photo']) {
            foreach ($receiving['container_photo'] as $key => $value) {
                $params = [];
                $params['file_name'] = Str::random(32);
                $params['type'] = 'receiving';
                $container_photo[] = array(
                    'container_type' => $params['type'],
                    'photo' => $params['file_name'] . '.png',
                    'params' => $params,
                    'base64_file' => $receiving['container_photo'][$key]['storage_path'],
                );
            }
        }
        $receiving['inspected_by'] = Auth::user()->id;

        if($receiving['container_photo']) {
            $receive = ContainerReceiving::create($receiving)->photos()->createMany($container_photo);
            $rec_id = $receive[0]->container_id;

            foreach($container_photo as $key => $value) {
                $this->imageUpload($value['params'], $value['base64_file'], $receive[0]->container_id);
            }
        }
        else {
            $receive = ContainerReceiving::create($receiving);
            $rec_id = $receive->id;
        }

        if($receive)
        {
            $dataCont = [
                'container_no'=>$receiving['container_no'],
                'client_id'=>$receiving['client_id'],
                'size_type'=>$receiving['size_type'],
                'type_id'=>$receiving['type_id'],
                'class'=>$receiving['class'],
                'status'=>$receiving['empty_loaded'],
                'receiving_id'=>$rec_id,
            ];
            $cont = Container::create($dataCont);

            $dataContRemark = [
                'status'=>'Received',
                'container_id'=>$cont->id,
                'remarks'=>$request->remarks,
            ];
            ContainerRemark::create($dataContRemark);
            $this->createEir(true, $cont->id);
        }

        return $receive;
    }

    public function createYard(ValidateYardLocation $request)
    {
        $yard = $request->validated();
        $dataYard = [
            'name'=>$yard['name'],
        ];
        return YardLocation::create($dataYard);
    }

    public function createClass(ValidateContainerClass $request)
    {
        $class = $request->validated();
        $dataClass = [
            'class_code'=>$class['class_code'],
            'class_name'=>$class['class_name'],
        ];
        return ContainerClass::create($dataClass);
    }

    public function createSizeType(ValidateSizeType $request)
    {
        $sizeType = $request->validated();
        $dataSizeT = [
            // 'code'=>$sizeType['code'],
            // 'name'=>$sizeType['name'],
            'size'=>$sizeType['size'],
            // 'type'=>$sizeType['type']
        ];
        return ContainerSizeType::create($dataSizeT);
    }

    public function createComponents(ValidateComponents $request)
    {
        $comp = $request->validated();
        $dataComp = [
            'code'=>$comp['code'],
            'name'=>$comp['name'],
        ];
        return ContainerComponent::create($dataComp);
    }

    public function createDamages(ValidateDamages $request)
    {
        $dmgs = $request->validated();
        $dataDmg = [
            'code'=>$dmgs['code'],
            'name'=>$dmgs['name'],
        ];
        return ContainerDamage::create($dataDmg);
    }

    public function createRepairs(ValidateRepairs $request)
    {
        $reps = $request->validated();
        $dataRep = [
            'code'=>$reps['code'],
            'name'=>$reps['name'],
        ];
        return ContainerRepair::create($dataRep);
    }

    protected function imageUpload($payload, $photo, $id=null)
    {
        $exploded = explode(',', $photo);
        $decode = base64_decode($exploded[1]);
        $extension = '.png';

        $the_path = storage_path() . '/app/public/uploads/';
        !is_dir( storage_path() . '/app/public/uploads/' ) && mkdir(storage_path() . '/app/public/uploads/');
        !is_dir( storage_path() . '/app/public/uploads/releasing/') && mkdir(storage_path() . '/app/public/uploads/releasing/');
        !is_dir( storage_path() . '/app/public/uploads/receiving/') && mkdir(storage_path() . '/app/public/uploads/receiving/');
        !is_dir( storage_path() . '/app/public/uploads/releasing/container/') && mkdir(storage_path() . '/app/public/uploads/releasing/container/');
        !is_dir( storage_path() . '/app/public/uploads/receiving/container/') && mkdir(storage_path() . '/app/public/uploads/receiving/container/');

        if($payload['type'] == 'releasing')
        {
            !is_dir( storage_path() . '/app/public/uploads/releasing/container/' . $id) && mkdir(storage_path() . '/app/public/uploads/releasing/container/' . $id);
            file_put_contents( $the_path . 'releasing/container/' . $id . '/' . $payload['file_name'] . $extension, $decode);
        }
        elseif($payload['type'] == 'receiving')
        {
            !is_dir( storage_path() . '/app/public/uploads/receiving/container/' . $id) && mkdir(storage_path() . '/app/public/uploads/receiving/container/' . $id);
            file_put_contents( $the_path . 'receiving/container/' . $id . '/' . $payload['file_name'] . $extension, $decode);
        }
    }

    public function ReceivingDamage(ValidateReceivingDamage $request)
    {
        $data = $request->validated();
        return ReceivingDamage::create($data);
    }

    public function ReceivingDamageChecker(ValidateEmptyReceivingDamage $request)
    {
        return 'success';
    }

    public function createType(ValidateType $request)
    {
        $type = $request->validated();
        $data = [
            'code'=>$type['code'],
            'name'=>$type['name'],
        ];
        return Type::create($data);
    }

    public function createEir($is_container_in, $container_id)
    {
        # accepts: eir_no IF already created eir_no, container_id, type (in or out)

        $type = $is_container_in ? 'I-' : 'O-';
        $eir = EirNumber::where('eir_no', 'ilike', '%' . $type . '%')
            ->latest('id')
            ->first('eir_no');

        if( is_null($eir) ) {
            return EirNumber::insertGetId(['eir_no' => $type . '000001', 'container_id' => $container_id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        }

        $array = explode('-', $eir->eir_no);
        
        $x = intval($array[1]) + 1;
        while ( strlen(strval($x)) < 6 ) {
            $x = '0' . strval($x);
        }

        return EirNumber::insertGetId(['eir_no' => $type . $x, 'container_id' => $container_id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
    }
}
