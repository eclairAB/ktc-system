<table>
    
        <tr>
          <th style="font-weight:bold;">Container No.</th> 
          <th style="font-weight:bold;">EIR No.</th> 
          <th style="font-weight:bold;">Cus. EIR</th> 
          <th style="font-weight:bold;">Type</th>
          <th style="font-weight:bold;">Size</th>
          <th style="font-weight:bold;">status</th>
          <th style="font-weight:bold;">class</th>
          <th style="font-weight:bold;">Client</th>
          <th style="font-weight:bold;">Consignee</th>
          <th style="font-weight:bold;">date</th>
          <th style="font-weight:bold;">time</th>
          <th style="font-weight:bold;">t_plate</th>
          <th style="font-weight:bold;">trucker</th>
          <th style="font-weight:bold;">remarks</th>
          <th style="font-weight:bold;">Date Time</th>
        </tr>
        @foreach($datus as $key => $item)
        <tr>
         <td style="width:50px; text-align:left;">{{ $item->container_no }}</td>
          <td style="width:50px; text-align:left;">{{ $item->eirNoIn->eir_no??'0' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->eir??'0' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->type->code??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->sizeType->size??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->status??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->containerClass->class_code??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->client->name??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->releasing->consignee }}</td>
          <td style="width:50px; text-align:left;">{{ Carbon\Carbon::parse($item->releasing->inspected_date)->format('d/m/Y') }}</td>
          <td style="width:50px; text-align:left;">{{ Carbon\Carbon::parse($item->releasing->inspected_date)->format('H:i') }}</td>
          <td style="width:50px; text-align:left;">{{ $item->releasing->plate_no }}</td>
          <td style="width:50px; text-align:left;">{{ $item->releasing->hauler }}</td>
          <td style="width:50px; text-align:left;">{{ $item->releasing->remarks }}</td>
          <td style="width:50px; text-align:left;">{{ Carbon\Carbon::parse($item->releasing->inspected_date)->format('d/m/Y H:i') }}</td>
        </tr>
      @endforeach
</table>