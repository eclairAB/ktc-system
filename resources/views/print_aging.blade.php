<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 14px;
    }
    td, th {
      text-align: left;
      padding: 4px;
    }
    </style>
  <body style="font-family: sans-serif;">
    <!-- HEADER -->
    <div style="width: 100%; display: flex;">
      <div style="text-align: left; width: 100%;">
        <img src = "{{ asset('/images/kudos.png') }}" width="150px" /><br>
        <h4 style="margin-bottom: 1px;">KUDOS TRUCKING CORPORATION</h4>
        <small>#15 KM9 OLD AIRPORT, DAVAO CITY</small><br>
        <div style="font-size: 14px; margin-top: 10px; margin-bottom: 3px;"><b>Container Inventory</b></div>
        <div style="font-size: 14px; margin-left: 40px; margin-bottom: 3px; display: flex; justify-content: space-between;">
          <span>to</span>
          <span>Printed Record: <b style="font-size: 16px;">{{ $option }}</b></span>
        </div>
        <div style="font-size: 14px; display: flex; justify-content: space-between;">
          <b>{{ isset($client_details)?$client_details->code:'' }}</b>
          <span>Print Date: {{ Carbon\Carbon::now()->format('Y-m-d') }}</span>
        </div>
        <table style="margin-top: 20px">
          <tr style="border-top: 2px solid; border-bottom: 2px solid">
            <th scope="col">Van #</th>
            <th scope="col">Type</th>
            <th scope="col">Size</th>
            <th scope="col">Status</th>
            <th scope="col">Date IN</th>
            <th scope="col">Date OUT</th>
            <th scope="col">Days</th>
          </tr>
          @foreach($data as $key => $item)
          <tr>
            <td>{{ $item->container_no }}</td>
            <td>{{ $item->sizeType->size??'' }}</td>
            <td>{{ $item->type->code??'' }}</td>
            <td>{{ $item->status??'' }}</td>
            <td>{{ is_null($item->receiving)?'':Carbon\Carbon::parse($item->receiving->inspected_date)->format('Y-m-d') }}</td>
            <td>{{ is_null($item->releasing)?'':Carbon\Carbon::parse($item->releasing->inspected_date)->format('Y-m-d') }}</td>
            <td>{{ isset($item->total_no_days)?$item->total_no_days:0 }}</td>
          </tr>
          @endforeach
        </table>
        
        <div style="border-top: 2px solid; display: flex; font-size: 14px; font-weight: 700; padding-top: 10px"></div>

        <div style="display: flex; font-size: 14px;">
          <div style="font-weight:bold; display: flex; margin-right: 20px; align-items: center;">
            Van Count: 
            <div style="margin-left: 10px; padding: 0 3px; border: 1px solid; background: white; width: 70px; text-align: right;">{{ $count }}</div>
          </div>
          <div style="font-weight:bold; display: flex; margin-right: 20px; align-items: center;">
            IN: {{ $in }}
            <div style="margin-left: 10px; padding: 0 3px; border: 1px solid; background: white; width: 70px; text-align: right;">{{ $in }}</div>
          </div>
          <div style="font-weight:bold; display: flex; margin-right: 20px; align-items: center;">
            OUT: {{ $out }}
            <div style="margin-left: 10px; padding: 0 3px; border: 1px solid; background: white; width: 70px; text-align: right;">{{ $out }}</div>
          </div>
        </div>

      </div>
    </div>
    <!-- END OF HEADER -->
  </body>
</html>