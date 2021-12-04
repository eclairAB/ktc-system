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

        $client = Client::where('user_id', $user_id)->pluck('user_id');
        if(count($client) > 0) return true;
        else return false;
    }

    public function createClient(ValidateClientField $request)
    {
        $account = $this->createUser($request, 'client');
        if( $account=='exists') return 'Username already in use.';
        elseif ($account=='no_role') return 'Role "client" does not exist!';

        $params = [
            'account_id' => $account->id,
            'code_name' => $request->code_name,
            'contact_no' => $request->contact_no,
            'user_id' => $request->user_id,
        ];

        $client = Client::create($params);
        $account['client'] = $client;
        return $account;
    }

    public function createStaff(ValidateStaffField $request)
    {
        $account = $this->createUser($request, 'staff');
        if( $account=='exists') return 'Username already in use.';
        elseif ($account=='no_role') return 'Role "staff" does not exist!';

        $params = [
            'account_id' => $account->id,
            'id_no' => $request->id_no,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_id' => $request->user_id,
            'contact_no' => $request->contact_no,
        ];

        $staff = Staff::create($params);
        $account['staff'] = $staff;
        return $account;
    }

    public function createChecker(ValidateCheckerField $request)
    {
        $account = $this->createUser($request, 'checker');
        if( $account=='exists') return 'Username already in use.';
        elseif ($account=='no_role') return 'Role "checker" does not exist!';

        $params = [
            'account_id' => $account->id,
            'id_no' => $request->id_no,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_id' => $request->user_id,
            'contact_no' => $request->contact_no,
        ];

        $checker = Checker::create($params);
        $account['checker'] = $checker;
        return $account;
    }

    public function createReleasing(ValidateContainerReleasing $request)
    {
        $releasing = $request->validated();
        $signature = $releasing['signature'];

        $receiving = ContainerReceiving::where('container_no',$releasing['container_no'])->first();
        if($receiving)
        {
            $signature_params = [];
            $signature_params['file_name'] = Str::random(32);
            $signature_params['type'] = 'releasing';
            $this->imageUpload($signature_params, $signature, true);
            foreach ($releasing['container_photo'] as $key => $value) {
                $params = [];
                $params['file_name'] = Str::random(32);
                $params['type'] = 'releasing';
                $container_photo[] = array(
                    'container_type' => $params['type'],
                    'storage_path' => '/app/public/uploads/releasing/container/' . $params['file_name'] . '.png',
                );
                $this->imageUpload($params, $releasing['container_photo'][$key]['storage_path'], false);
            }
            $releasing['signature'] = '/app/public/uploads/releasing/signature/' . $signature_params['file_name'] . '.png';
            $release = ContainerReleasing::create($releasing)->photos()->createMany($container_photo);

            if($release)
            {
                $dataCont = Container::where('container_no',$releasing['container_no'])->whereNotNull('receiving_id')->latest('created_at')->first();
                $dataCont->releasing_id = $release->id;
                $dataCont->save();

                $dataContRemark = [
                    'status'=>'Released',
                    'container_id'=>$dataCont->id,
                    'remarks'=>$request->remarks,
                ];
                ContainerRemark::create($dataContRemark);
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
        $signature = $receiving['signature'];

        $signature_params = [];
        $signature_params['file_name'] = Str::random(32);
        $signature_params['type'] = 'receiving';
        $this->imageUpload($signature_params, $signature, true);
        foreach ($receiving['container_photo'] as $key => $value) {
                $params = [];
                $params['file_name'] = Str::random(32);
                $params['type'] = 'receiving';
                $container_photo[] = array(
                    'container_type' => $params['type'],
                    'storage_path' => '/app/public/uploads/receiving/container/' . $params['file_name'] . '.png',
                );
                $this->imageUpload($params, $receiving['container_photo'][$key]['storage_path'], false);
            }
        $receiving['signature'] = '/app/public/uploads/receiving/signature/' . $signature_params['file_name'] . '.png';
        $receiving['inspected_by'] = Auth::user()->id;
        $receive = ContainerReceiving::create($receiving)->photos()->createMany($container_photo);

        if($receive)
        {
            $dataCont = [
                'container_no'=>$receiving['container_no'],
                'client_id'=>$receiving['client_id'],
                'size_type'=>$receiving['size_type'],
                'class'=>$receiving['class'],
                'receiving_id'=>$receive->id,
            ];
            $cont = Container::create($dataCont);

            $dataContRemark = [
                'status'=>'Received',
                'container_id'=>$cont->id,
                'remarks'=>$request->remarks,
            ];
            ContainerRemark::create($dataContRemark);
        }
        return $receive;
    }

    public function createSizeType(ValidateSizeType $request)
    {
        $sizeType = $request->validated();
        $dataSizeT = [
            'code'=>$sizeType['code'],
            'name'=>$sizeType['name'],
            'size'=>$sizeType['size'],
            'type'=>$sizeType['type']
        ];
        return ContainerSizeType::create($dataSizeT);
    }

    protected function imageUpload($payload, $photo, $isSignature)
    {
        $exploded = explode(',', $photo);
        $decode = base64_decode($exploded[1]);
        $extension = '.png';

        $the_path = storage_path() . '/app/public/uploads/';
        !is_dir( storage_path() . '/app/public/uploads/' ) && mkdir(storage_path() . '/app/public/uploads/');
        !is_dir( storage_path() . '/app/public/uploads/releasing/') && mkdir(storage_path() . '/app/public/uploads/releasing/');
        !is_dir( storage_path() . '/app/public/uploads/receiving/') && mkdir(storage_path() . '/app/public/uploads/receiving/');
        !is_dir( storage_path() . '/app/public/uploads/releasing/signature/') && mkdir(storage_path() . '/app/public/uploads/releasing/signature/');
        !is_dir( storage_path() . '/app/public/uploads/releasing/container/') && mkdir(storage_path() . '/app/public/uploads/releasing/container/');
        !is_dir( storage_path() . '/app/public/uploads/receiving/signature/') && mkdir(storage_path() . '/app/public/uploads/receiving/signature/');
        !is_dir( storage_path() . '/app/public/uploads/receiving/container/') && mkdir(storage_path() . '/app/public/uploads/receiving/container/');

        if($payload['type'] == 'releasing')
        {
            if($isSignature) file_put_contents( $the_path . 'releasing/signature/' . $payload['file_name'] . $extension, $decode);
            else file_put_contents( $the_path . 'releasing/container/' . $payload['file_name'] . $extension, $decode);
        }
        elseif($payload['type'] == 'receiving')
        {
            if($isSignature) file_put_contents( $the_path . 'receiving/signature/' . $payload['file_name'] . $extension, $decode);
            else file_put_contents( $the_path . 'receiving/container/' . $payload['file_name'] . $extension, $decode);
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
}
