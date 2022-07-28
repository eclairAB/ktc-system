<table>
    
        <tr>
          <th style="font-weight:bold;">Container No.</th> 
          <th style="font-weight:bold;">EIR No.</th> 
          <th style="font-weight:bold;">Cus. EIR</th> 
          <th style="font-weight:bold;">Size</th>
          <th style="font-weight:bold;">Type</th>
          <th style="font-weight:bold;">status</th>
          <th style="font-weight:bold;">class</th>
          <th style="font-weight:bold;">Client</th>
          <th style="font-weight:bold;">Consignee</th>
          <th style="font-weight:bold;">date</th>
          <th style="font-weight:bold;">time</th>
          <th style="font-weight:bold;">t_plate</th>
          <th style="font-weight:bold;">trucker</th>
          <th style="font-weight:bold;">damage</th>
          <th style="font-weight:bold;">remarks</th>
          <th style="font-weight:bold;">Date Time</th>
        </tr>
        @foreach($datus as $key => $item)
        <tr>
          <td style="width:50px;">{{ $item->container_no }}</td>
          <td style="width:50px;">{{ $item->eirNoIn->eir_no??'' }}</td>
          <td style="width:50px;">{{ $item->eir??'' }}</td>
          <td style="width:50px;">{{ $item->sizeType->size??'' }}</td>
          <td style="width:50px;">{{ $item->type->code??'' }}</td>
          <td style="width:50px;">{{ $item->status??'' }}</td>
          <td style="width:50px;">{{ $item->containerClass->class_code??'' }}</td>
          <td style="width:50px;">{{ $item->client->name??'' }}</td>
          <td style="width:50px;">{{ $item->receiving->consignee }}</td>
          <td style="width:50px;">{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('Y-m-d') }}</td>
          <td style="width:50px;">{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('h:i:s') }}</td>
          <td style="width:50px;">{{ $item->receiving->plate_no }}</td>
          <td style="width:50px;">{{ $item->receiving->hauler }}</td>
          <td style="width:50px; overflow: hidden;">
              @foreach($item->receiving->damages as $key=> $dmg)
              <div>
                {{ $key + 1 }}.) {{ $dmg->description }}<br>
              </div>
              @endforeach
          </td>
          <td style="width:50px;">{{ $item->receiving->remarks }}</td>
          <td style="width:50px;">{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('Y-m-d h:i:s') }}</td>
        </tr>
      @endforeach
</table>