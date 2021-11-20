<table>
    
        <tr>
          <th style="font-weight:bold;">EIR No.</th> 
          <th style="font-weight:bold;">Container No.</th> 
          <th style="font-weight:bold;">Size/Type</th>
          <th style="font-weight:bold;">Date IN</th>
          <th style="font-weight:bold;">Shipping Line</th>
          <th style="font-weight:bold;">Truckers</th>
          <th style="font-weight:bold;">Plate No.</th>
          <th style="font-weight:bold;">Inspected By</th>
          <th style="font-weight:bold;">Class</th>
          <th style="font-weight:bold;">Manufactured Date</th>
          <th style="font-weight:bold;">Status</th>
          <th style="font-weight:bold;">Remarks</th>
        </tr>
        @foreach($data as $key => $item)
        <tr>
          <td>{{ $item->id }}</td>
          <td>{{ $item->container_no }}</td>
          <td>{{ $item->sizeType->code }} - {{ $item->sizeType->name }}</td>
          <td>{{ Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}</td>
          <td>{{ $item->client->code_name }}</td>
          <td>{{ $item->hauler }}</td>
          <td>{{ $item->plate_no }}</td>
          <td>{{ $item->inspector->name }}</td>
          <td>{{ $item->containerClass->class_code }}</td>
          <td>{{ Carbon\Carbon::parse($item->manufactured_date)->format('F d, Y') }}</td>
          <td>Received</td>
          <td>{{ $item->remarks }}</td>
        </tr>
      @endforeach
</table>