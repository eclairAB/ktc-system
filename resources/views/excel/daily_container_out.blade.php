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
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">remarks</th>
          <th style="font-weight:bold; font-size: 10px; font-family: Arial;">Date Time</th>
        </tr>
        @foreach($datus as $key => $item)
        <tr>
         <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->container_no }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->eirNoIn->eir_no??'0' }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->eir??'0' }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->type->code??'' }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->sizeType->size??'' }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->status??'' }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->containerClass->class_code??'' }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->client->name??'' }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->releasing->consignee }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ Carbon\Carbon::parse($item->releasing->inspected_date)->format('d/m/Y') }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ Carbon\Carbon::parse($item->releasing->inspected_date)->format('H:i') }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->releasing->plate_no }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->releasing->hauler }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ $item->releasing->remarks }}</td>
          <td style="width:50px; text-align:left; font-size: 10px; font-family: Arial;">{{ Carbon\Carbon::parse($item->releasing->inspected_date)->format('d/m/Y H:i') }}</td>
        </tr>
      @endforeach
</table>