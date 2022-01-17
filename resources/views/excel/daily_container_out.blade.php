<table>
    
        <tr>
          <th style="font-weight:bold;">Container No.</th>
          <th style="font-weight:bold;">EIR No.</th> 
          <th style="font-weight:bold;">Size</th>
          <th style="font-weight:bold;">Type</th>
          <th style="font-weight:bold;">Client</th>
          <th style="font-weight:bold;">Consignee</th>
          <th style="font-weight:bold;">Plate No.</th>
          <th style="font-weight:bold;">Trucker</th>
          <th style="font-weight:bold;">Class</th>
          <th style="font-weight:bold;">Remarks</th>
          <th style="font-weight:bold;">Date OUT</th>
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
        </tr>
      @endforeach
</table>