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
          <th style="font-weight:bold;">Damages</th>
          <th style="font-weight:bold;">Remarks</th>
          <th style="font-weight:bold;">Consignee</th>
          <th style="font-weight:bold;">Plate No.</th>
          <th style="font-weight:bold;">Trucker</th>
          <th style="font-weight:bold;">Date IN</th>
          <th style="font-weight:bold;">Time</th>
        </tr>
        @foreach($data as $key => $item)
        <tr>
          <td>{{ $item->container_no }}</td>
          <td>{{ $item->eirNoIn->eir_no??'' }}</td>
          <td>{{ ' ' }}</td>
          <td>{{ $item->sizeType->size??'' }}</td>
          <td>{{ $item->type->code??'' }}</td>
          <td>{{ $item->client->code??'' }}</td>
          <td>{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('Y-m-d h:i:s A') }}</td>
          <td>{{ $item->containerClass->class_code??'' }}</td>
          <td>
              @foreach($item->receiving->damages as $key=> $dmg)
              <div>
                {{ $key + 1 }}.) {{ $dmg->description }}<br>
              </div>
              @endforeach
          </td>
          <td>{{ $item->remarks }}</td>
          <td>{{ $item->consignee }}</td>
          <td>{{ $item->plate_no }}</td>
          <td>{{ $item->hauler }}</td>
          <td>{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('Y-m-d') }}</td>
          <td>{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('h:i:s A') }}</td>
        </tr>
      @endforeach
</table>