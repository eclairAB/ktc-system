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
          <td style="width:50px; text-align:left;">{{ $item->container_no }}</td>
          <td style="width:50px; text-align:left;">{{ $item->eirNoIn->eir_no??'0' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->eir??'0' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->sizeType->size??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->type->code??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->status??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->containerClass->class_code??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->client->name??'' }}</td>
          <td style="width:50px; text-align:left;">{{ $item->receiving->consignee }}</td>
          <td style="width:50px; text-align:left;">{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('Y-m-d') }}</td>
          <td style="width:50px; text-align:left;">{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('h:i') }}</td>
          <td style="width:50px; text-align:left;">{{ $item->receiving->plate_no }}</td>
          <td style="width:50px; text-align:left;">{{ $item->receiving->hauler }}</td>
          <td style="width:50px; text-align:left;">
            @foreach($item->receiving->damages as $key => $item)
              <span>
              {{ $key + 1 }}.){{ $item['description'] }}&nbsp;
              </span>
            @endforeach
          </td>
          <td style="width:50px;">{{ $item->receiving->remarks }}</td>
          <td style="width:50px;">{{ Carbon\Carbon::parse($item->receiving->inspected_date)->format('Y-m-d h:i') }}</td>
        </tr>
      @endforeach
</table>