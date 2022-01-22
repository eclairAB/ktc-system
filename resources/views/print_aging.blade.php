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
          <b>{{ $data->client->code }}</b>
          <span>Print Date: {{ Carbon\Carbon::now()->format('m-d-Y') }}</span>
        </div>
        <table style="margin-top: 20px">
          <tr style="border-top: 2px solid; border-bottom: 2px solid">
            <th scope="col">Container No.</th>
            <th scope="col">Size</th>
            <th scope="col">Type</th>
            <th scope="col">Status</th>
            <th scope="col">Date In</th>
            <th scope="col">Date Out</th>
            <th scope="col">Days</th>
          </tr>
          <tr>
            <td>{{ $data->container_no }}</td>
            <td>{{ $data->sizeType->size??'' }}</td>
            <td>{{ $data->type->code??'' }}</td>
            <td>{{ $data->receiving->empty_loaded??'' }}</td>
            <td>{{ is_null($data->receiving)?'':Carbon\Carbon::parse($data->receiving->inspected_date)->format('Y-m-d') }} }}</td>
            <td>{{ is_null($data->releasing)?'':Carbon\Carbon::parse($data->releasing->inspected_date)->format('Y-m-d') }} }}</td>
            <td>1</td>
          </tr>
        </table>
        <div style="border-top: 2px solid; display: flex; font-size: 14px; font-weight: 700; padding-top: 10px">
          <div>Container Count</div>
          <div style="margin-left: 40px;">{{ $count }}</div>
        </div>
      </div>
    </div>
    <!-- END OF HEADER -->
  </body>
</html>