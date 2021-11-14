<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\ContainerReceiving;
use App\Models\ContainerReleasing;
use App\Models\ContainerSizeType;
use App\Models\ReceivingDamage;
use App\Models\Staff;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    //

    public function updateSizeType(Request $request)
    {
        $validate_sizeType = $request->all();
        $sizeType = ContainerSizeType::where('id',$request->id)->first();
        $sizeType->update($validate_sizeType);

        return $sizeType;
    }

    public function updateClient(Request $request)
    {
        $validate_client = $request->all();
        $client = Client::where('id',$request->id)->first();
        $client->update($validate_client);
        
        return $client;
    }

    public function updateStaff(Request $request)
    {
        $validate_staff = $request->all();
        $staff = Staff::where('id',$request->id)->first();
        $staff->update($validate_staff);
        
        return $staff;
    }

    public function updateReceiving(Request $request)
    {
        $validate_receiving = $request->all();
        $receiving = ContainerReceiving::where('id',$request->id)->first();
        $receiving->update($validate_receiving);
        
        return $receiving;
    }

    public function updateReleasing(Request $request)
    {
        $releasing = ContainerReleasing::where('id',$request->id)->update($request);
        return $releasing;

        // testing
        foreach ($request->containers as $key => $value) {
            $zxc[] = [
                $this->selectAction($value)
            ];
        }
        return $zxc;
    }

    function selectAction($payload)
    {
        if($payload['deleted']) {

            return "delete";
        }
        elseif(isset($payload['base64'])) {
            return "update";
        }
        else {
            return "retain";
        }
    }

    function imageUpload($payload, $photo, $isSignature)
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

    public function ReceivingDamageUpdate(Request $request)
    {
        $updated_data = $request->all();
        $dmg = ReceivingDamage::where('id',$request->id)->first();
        $dmg->update($updated_data);
        return $dmg;
    }

    public function ReceivingDamageDelete($id)
    {
        return ReceivingDamage::where('id',$id)->delete();
    }
}