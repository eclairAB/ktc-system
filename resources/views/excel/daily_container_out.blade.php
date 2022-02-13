<table>
    
        <tr>
          <th style="font-weight:bold;">Container No.</th>
          <th style="font-weight:bold;">EIR No.</th> 
          <th style="font-weight:bold;">Cus. EIR</th> 
          <th style="font-weight:bold;">Size</th>
          <th style="font-weight:bold;">Type</th>
          <th style="font-weight:bold;">Client</th>
          <th style="font-weight:bold;">Date Time</th>
          <th style="font-weight:bold;">Class</th>
          <th style="font-weight:bold;">Remarks</th>
          <th style="font-weight:bold;">Consignee</th>
          <th style="font-weight:bold;">Plate No.</th>
          <th style="font-weight:bold;">Trucker</th>
          <th style="font-weight:bold;">Date Out</th>
          <th style="font-weight:bold;">Time</th>
        </tr>
        @foreach($data as $key => $item)
        <tr>
          <td>{{ $item->container_no }}</td>
          <td>{{ $item->eirNoOut->eir_no??'' }}</td>
          <td>{{ ' ' }}</td>
          <td>{{ $item->sizeType->size??'' }}</td>
          <td>{{ $item->type->code??'' }}</td>
          <td>{{ $item->client->code??'' }}</td>
          <td>{{ Carbon\Carbon::parse($item->releasing->inspected_date)->format('Y-m-d h:i:s A') }}</td>
          <td>{{ $item->containerClass->class_code??'' }}</td>
          <td>{{ $item->releasing->remarks }}</td>
          <td>{{ $item->releasing->consignee }}</td>
          <td>{{ $item->releasing->plate_no }}</td>
          <td>{{ $item->releasing->hauler }}</td>
          <td>{{ Carbon\Carbon::parse($item->releasing->inspected_date)->format('Y-m-d') }}</td>
          <td>{{ Carbon\Carbon::parse($item->releasing->inspected_date)->format('h:i:s A') }}</td>
        </tr>
      @endforeach
</table>