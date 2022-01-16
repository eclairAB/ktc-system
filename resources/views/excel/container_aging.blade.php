<table>
    
        <tr>
          <th style="font-weight:bold;">Container No.</th> 
          <th style="font-weight:bold;">Size</th>
          <th style="font-weight:bold;">Type</th>
          <th style="font-weight:bold;">Status</th>
          <th style="font-weight:bold;">Client</th>
          <th style="font-weight:bold;">Date IN</th>
          <th style="font-weight:bold;">Consignee</th>
          <th style="font-weight:bold;">Date OUT</th>
          <th style="font-weight:bold;">Booking</th>
          <th style="font-weight:bold;">Seal</th>
          <th style="font-weight:bold;">Days</th>
        </tr>
        @foreach($data as $key => $item)
        <tr>
          <td>{{ $item->client->code }}</td>
          <td>{{ $item->container_no }}</td>
          <td>{{ $item->sizeType->code }}</td>
          <td>{{ $item->type->code }}</td>
          <td>{{ Carbon\Carbon::parse($item->manufactured_date)->format('F d, Y') }}</td>
          <td>{{ $item->containerClass->class_code }}</td>
          <td>{{ Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}</td>
          <td>{{ $item->total_no_days }}</td>
          <td>{{ $item->empty_loaded }}</td>
          <td>Received</td>
          <td>{{ $item->consignee }}</td>
          <td>{{ $item->remarks }}</td>
        </tr>
      @endforeach
</table>