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
        <div style="width: 100%; text-align: center;">
          <img src = "{{ asset('/images/kudos.png') }}" width="150px" /><br>
          <small><b>Container Daily Out Report</b></small><br>
          <small>Print Date: 01/22/22</small>
        </div>
        <table style="margin-top: 20px">
          <tr style="border-top: 2px solid; border-bottom: 2px solid">
            <th">Container No.</th>
            <th">EIR No.</th> 
            <th">Size</th>
            <th">Type</th>
            <th">Client</th>
            <th">Consignee</th>
            <th">Plate No.</th>
            <th">Trucker</th>
            <th">Class</th>
            <th">Remarks</th>
            <th">Date OUT</th>
          </tr>
          @foreach($data as $key => $item)
          <tr>
            <td>{{ $item->container_no }}</td>
            <td>{{ $item->container->eirNoOut->eir_no??'' }}</td>
            <td>{{ $item->container->sizeType->size??'' }}</td>
            <td>{{ $item->container->type->code??'' }}</td>
            <td>{{ $item->container->client->code??'' }}</td>
            <td>{{ $item->consignee }}</td>
            <td>{{ $item->plate_no }}</td>
            <td>{{ $item->hauler }}</td>
            <td>{{ $item->container->containerClass->class_code??'' }}</td>
            <td>{{ $item->remarks }}</td>
            <td>{{ Carbon\Carbon::parse($item->inspected_date)->format('Y-m-d') }}</td>
        </table>
        <div style="border-top: 2px solid; display: flex; font-size: 14px; font-weight: 700; padding-top: 10px">
        </div>
      </div>
    </div>
    <!-- END OF HEADER -->
  </body>
</html>