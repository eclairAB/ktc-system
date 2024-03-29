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
          <small><b>Container Daily In Report</b></small><br>
          <small>Print Date: {{ Carbon\Carbon::now()->format('d/m/Y') }}</small>
        </div>
        <table style="margin-top: 20px">
          <tr style="border-top: 2px solid; border-bottom: 2px solid">
            <th scope="col">Container No.</th> 
            <th scope="col">EIR</th>
            <th scope="col">Type</th>
            <th scope="col">Size</th>
            <th scope="col">Client</th>
            <th scope="col">Date Time</th>
            <th scope="col">Class</th>
            <th scope="col">Damages</th>
            <th scope="col">Remarks</th>
            <th scope="col">Consignee</th>
            <th scope="col">Plate No.</th>
            <th scope="col">Trucker</th>
            <th scope="col">Date In</th>
            <th scope="col">Time</th>
          </tr>
          @foreach($datus as $key => $item)
            <tr>
              <td>{{ $item->container_no }}</td>
              <td>{{ $item->eirNoIn->eir_no??'' }}</td>
              <td>{{ $item->type->code??'' }}</td>
              <td>{{ $item->sizeType->size??'' }}</td>
              <td>{{ $item->client->code??'' }}</td>
              <td>{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('d/m/Y H:i') }}</td>
              <td>{{ $item->containerClass->class_code??'' }}</td>
              <td>
                @foreach($item->receiving->damages as $key=> $dmg)
                <div>
                  {{ $key + 1 }}.) {{ $dmg->description }}
                </div>
                @endforeach
            </td>
            <td>{{ $item->receiving->remarks }}</td>
            <td>{{ $item->receiving->consignee }}</td>
            <td>{{ $item->receiving->plate_no }}</td>
            <td>{{ $item->receiving->hauler }}</td>
            <td>{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('d/m/Y') }}</td>
            <td>{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('H:i') }}</td>
            </tr>
          @endforeach
        </table>
        <div style="border-top: 2px solid; display: flex; font-size: 14px; font-weight: 700; padding-top: 10px">
        </div>
      </div>
    </div>
    <!-- END OF HEADER -->
  </body>
</html>