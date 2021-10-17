<?php

namespace App\Http\Controllers;
use App\Http\Requests\ValidateClientField;
use App\Http\Requests\ValidateStaffField;
use App\Models\Client;
use App\Models\Staff;
use App\Models\User;
use DB;
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
}
