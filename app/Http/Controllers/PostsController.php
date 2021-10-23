<?php

namespace App\Http\Controllers;
use App\Http\Requests\ValidateClientField;
use App\Http\Requests\ValidateStaffField;
use App\Http\Requests\ValidateContainerReleasing;
use App\Http\Requests\ValidateContainerReceiving;
use App\Models\Client;
use App\Models\Staff;
use App\Models\User;
use App\Models\ContainerReleasing;
use App\Models\ContainerReceiving;
use App\Models\Containers;
use App\Models\ContainerRemark;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function createUser($params, $role)
    {
        $dbrole = DB::table('roles')->where('name', $role)->pluck('id');
        if(!isset($dbrole[0])) return response('Role "'. $role . '" does not exist.', 404);
        $credentials = [
            'name' => strtoupper($params->firstname . ' ' . $params->lastname),
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

    public function createClient(ValidateClientField $request)
    {
        $account = $this->createUser($request, 'client');

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

        $params = [
            'account_id' => $account->id,
            'id_no' => $request->id_no,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_id' => $request->user_id,
            'user_type' => $request->user_type,
            'contact_no' => $request->contact_no,
        ];

        $staff = Staff::create($params);
        $account['staff'] = $staff;
        return $account;
    }

    public function createReleasing(ValidateContainerReleasing $request)
    {
        $releasing = $request->validated();
        $container = $releasing['upload_photo'];
        $signature = $releasing['signature'];
        $params = [];
        $params['file_name'] = Str::random(32);
        $params['type'] = 'releasing';

        $receiving = ContainerReceiving::where('container_no',$releasing['container_no'])->first();
        if($receiving)
        {

            $this->imageUpload($params, $signature, true);
            $this->imageUpload($params, $container, false);
            $releasing['upload_photo'] = storage_path() . '/app/public/uploads/releasing/signature/' . $params['file_name'];
            $releasing['signature'] = storage_path() . '/app/public/uploads/releasing/container/' . $params['file_name'];
            $release =   ContainerReleasing::create($releasing);

            if($release)
            {
                $dataCont = [
                    'container_no'=>$releasing['container_no'],
                    'client_id'=>$receiving->client_id,
                    'size_type'=>$receiving->size_type,
                    'class'=>$receiving->class,
                    'date_received'=>$release->created_at,
                    'date_released'=>null,
                ];
                $cont = Containers::create($dataCont);

                $dataContRemark = [
                    'status'=>'Released',
                    'container_id'=>$cont->id,
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
        $container = $receiving['upload_photo'];
        $signature = $receiving['signature'];
        $params = [];
        $params['file_name'] = Str::random(32);
        $params['type'] = 'receiving';

        $this->imageUpload($params, $signature, true);
        $this->imageUpload($params, $container, false);
        $receiving['upload_photo'] = storage_path() . '/app/public/uploads/receiving/signature/' . $params['file_name'];
        $receiving['signature'] = storage_path() . '/app/public/uploads/receiving/container/' . $params['file_name'];
        $receiving['inspected_by'] = Auth::user()->id;
        $receive =   ContainerReceiving::create($receiving);

        if($receive)
        {
            $dataCont = [
                'container_no'=>$receiving['container_no'],
                'client_id'=>$receiving['client_id'],
                'size_type'=>$receiving['size_type'],
                'class'=>$receiving['class'],
                'date_received'=>$receive->created_at,
                'date_released'=>null,
            ];
            $cont = Containers::create($dataCont);

            $dataContRemark = [
                'status'=>'Received',
                'container_id'=>$cont->id,
                'remarks'=>$request->remarks,
            ];
            ContainerRemark::create($dataContRemark);
        }

        return $receive;
    }

    public function imageUpload($payload, $photo, $isSignature)
    {
        $exploded = explode(',', $photo);
        $decode = base64_decode($exploded[1]);
        if(str_contains($exploded[0], 'jpeg', 'jpg')) $extension = '.jpg';
        elseif(str_contains($exploded[0], 'svg')) $extension = '.svg';
        else $extension = '.png';

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
}
