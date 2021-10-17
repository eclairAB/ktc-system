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
use DB;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function createUser($params, $role)
    {
        $credentials = [
            'name' => strtoupper($params->firstname . ' ' . $params->lastname),
            // 'email' => 
            'role_id' => DB::table('roles')->where('name', $role)->pluck('id')[0],
        ];
        if(isset($params->password)) {
            $credentials['password'] = bcrypt($params->password);
        }
        else {
            $credentials['password'] = bcrypt('password');
        }
        if(isset($params->email)) {
            $credentials['email'] = $params->email;
        }
        else {
            $client = Client::latest('created_at')->first();
            if(is_null($client)) $client = 1;
            else $client = intval($client->pluck('id')[0])+1;
            $credentials['email'] = $role . $client . '@kudostrucking.com';
        }

        return User::create($credentials)->id;
    }

    public function createClient(ValidateClientField $request)
    {
        $account_id = $this->createUser($request, 'client');

        $params = [
            'account_id' => $account_id,
            'code_name' => $request->code_name,
            'contact_no' => $request->contact_no,
            'user_id' => $request->user_id,
        ];

        return Client::create($params);
    }

    public function createStaff(ValidateStaffField $request)
    {
        $account_id = $this->createUser($request, 'staff');

        $params = [
            'account_id' => $account_id,
            'id_no' => $request->id_no,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_id' => $request->user_id,
            'user_type' => $request->user_type,
            'contact_no' => $request->contact_no,
        ];

        return Staff::create($params);
    }

    public function createReleasing(ValidateContainerReleasing $request)
    {
        $releasing = $request->validated();
        $file_sig = $releasing['signature'];
        $file_photo = $releasing['signature'];
        $path_sig  = $file->store('uploads/releasing/signatures', 'public');
        $path_photo  = $file->store('uploads/releasing/photos', 'public');
        $releasing['upload_photo'] = $path_photo;
        $releasing['signature'] = $path_sig;

        return  ContainerReleasing::create($releasing);
    }

    public function createReceiving(ValidateContainerReceiving $request)
    {
        $receiving = $request->validated();
        $file_sig = $receiving['signature'];
        $file_photo = $receiving['signature'];
        $path_sig  = $file->store('uploads/receiving/signatures', 'public');
        $path_photo  = $file->store('uploads/receiving/photos', 'public');
        $receiving['upload_photo'] = $path_photo;
        $receiving['signature'] = $path_sig;
        return  ContainerReceiving::create($receiving); 
    }
}
