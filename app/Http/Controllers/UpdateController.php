<?php

namespace App\Http\Controllers;
use App\Http\Requests\ValidateClientField;
use App\Http\Requests\ValidateContainerReceiving;
use App\Http\Requests\ValidateContainerReleasing;
use App\Http\Requests\ValidateSizeType;
use App\Http\Requests\ValidateStaffField;
use App\Models\Client;
use App\Models\ContainerReceiving;
use App\Models\ContainerReleasing;
use App\Models\ContainerSizeType;
use App\Models\Staff;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    //

    public function updateSizeType(ValidateSizeType $request)
    {
        $validate_sizeType = $request->validated();
        $sizeType = ContainerSizeType::where('id',$request->id)->first();
        $sizeType->update($validate_sizeType);

        return $sizeType;
    }

    public function updateClient(ValidateClientField $request)
    {
        $validate_client = $request->validated();
        $client = Client::where('id',$request->id)->first();
        $client->update($validate_client);
        
        return $client;
    }

    public function updateStaff(ValidateStaffField $request)
    {
        $validate_staff = $request->validated();
        $staff = Staff::where('id',$request->id)->first();
        $staff->update($validate_staff);
        
        return $staff;
    }

    public function updateReceiving(ValidateContainerReceiving $request)
    {
        $validate_receiving = $request->validated();
        $receiving = ContainerReceiving::where('id',$request->id)->first();
        $receiving->update($validate_receiving);
        
        return $receiving;
    }

    public function updateReleasing(ValidateContainerReleasing $request)
    {
        $validate_releasing = $request->validated();
        $releasing = ContainerReleasing::where('id',$request->id)->first();
        $releasing->update($validate_releasing);
        
        return $releasing;
    }
}
