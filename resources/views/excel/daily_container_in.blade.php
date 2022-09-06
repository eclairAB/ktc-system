<table>
    
        <tr>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">Container No.</th> 
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">EIR No.</th> 
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">Cus. EIR</th> 
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">Type</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">Size</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">status</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">class</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">Client</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">Consignee</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">date</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">time</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">t_plate</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">trucker</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">damage</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">remarks</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">Date Time</th>
        </tr>
        @foreach($datus as $key => $item)
        <tr>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->container_no }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->eirNoIn->eir_no??'0' }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->receiving->eir??'0' }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->type->code??'' }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->sizeType->size??'' }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->status??'' }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->containerClass->class_code??'' }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->client->name??'' }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->receiving->consignee }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('d/m/Y') }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('H:i') }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->receiving->plate_no }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">{{ $item->receiving->hauler }}</td>
          <td style="text-align:left; font-size: 10px; font-family: Arial;">
            @foreach($item->receiving->damages as $key => $item)
              <span>
              {{ $key + 1 }}.){{ $item['description'] }}&nbsp;
              </span>
            @endforeach
          </td>
          <td style="font-size: 10px; font-family: Arial;">{{ $item->receiving->remarks }}</td>
          <td style="font-size: 10px; font-family: Arial;">{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('d/m/Y H:i') }}</td>
        </tr>
      @endforeach
</table>