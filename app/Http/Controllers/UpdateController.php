<?php

namespace App\Http\Controllers;
use App\Models\Checker;
use App\Models\Client;
use App\Models\ContainerReceiving;
use App\Models\ContainerReleasing;
use App\Models\ContainerSizeType;
use App\Models\ReceivingDamage;
use App\Models\Staff;
use App\Models\Type;
use App\Models\ContainerPhoto;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    //

    public function updateType(Request $request)
    {
        $validate_type = $request->all();
        $type = Type::where('id',$request->id)->first();
        $type->update($validate_type);

        return $sizeType;
    }

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

    public function updateChecker(Request $request)
    {
        $validate_checker = $request->all();
        $checker = Checker::where('id',$request->id)->first();
        $checker->update($validate_checker);
        
        return $checker;
    }

    public function updateReceiving(Request $request)
    {
        $receiving = $request->all();
        $rec = ContainerReceiving::where('id',$request->id)->first();
        $rec->update($receiving);
        
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
        // $receive = ContainerReceiving::create($receiving)->photos()->createMany($container_photo);

        foreach($container_photo as $key => $value) {
            $path = storage_path() . '/app/public/uploads/receiving/container/' . $receive[0]->container_id . '/';
            array_map('unlink', glob($path."*.png"));
            $this->imageUpload($value['params'], $value['base64_file'], $receive[0]->container_id);
        }

        return $receiving;
    }

    public function updateReleasing(Request $request)
    {
        $releasing = $request->all();
        $rel = ContainerReleasing::where('id',$request->id)->first();
        $rel->update($releasing);
        
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
        // $release = ContainerReleasing::create($releasing)->photos()->createMany($container_photo);

        foreach($container_photo as $key => $value) {
            $path = storage_path() . '/app/public/uploads/releasing/container/' . $release[0]->container_id . '/';
            array_map('unlink', glob($path."*.png"));
            $this->imageUpload($value['params'], $value['base64_file'],  $release[0]->container_id);
        }

        return $releasing;
    }

    // function selectAction($payload)
    // {
    //     if($payload['deleted']) {

    //         return "delete";
    //     }
    //     elseif(isset($payload['base64'])) {
    //         return "update";
    //     }
    //     else {
    //         return "retain";
    //     }
    // }

    function imageUpload($payload, $photo)
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
            file_put_contents( $the_path . 'releasing/container/' . $payload['file_name'] . $extension, $decode);
        }
        elseif($payload['type'] == 'receiving')
        {
            file_put_contents( $the_path . 'receiving/container/' . $payload['file_name'] . $extension, $decode);
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