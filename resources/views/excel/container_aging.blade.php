<table>
    
        <tr>
          <th style="font-weight:bold;">Container No.</th> 
          <th style="font-weight:bold;">Size</th>
          <th style="font-weight:bold;">Type</th>
          <th style="font-weight:bold;">Status</th>
          <th style="font-weight:bold;">Client</th>
          <th style="font-weight:bold;">Class</th>
          <th style="font-weight:bold;">Date IN</th>
          <th style="font-weight:bold;">Consignee</th>
          <th style="font-weight:bold;">Date OUT</th>
          <th style="font-weight:bold;">Consignee</th>
          <th style="font-weight:bold;">Booking</th>
          <th style="font-weight:bold;">Seal</th>
          <th style="font-weight:bold;">Days</th>
          <th style="font-weight:bold;">Damages</th>
          <th style="font-weight:bold;">Remarks</th>
        </tr>
        @foreach($data as $key => $item)
        <tr>
          <td>{{ $item->container_no }}</td>
          <td>{{ $item->sizeType->size??'' }}</td>
          <td>{{ $item->type->code??'' }}</td>
          <td>{{ $item->status??'' }}</td>
          <td>{{ $item->client->code??'' }}</td>
          <td>{{ $item->containerClass->class_code??'' }}</td>
          <td>{{ is_null($item->receiving)?'':Carbon\Carbon::parse($item->receiving->inspected_date)->format('Y-m-d') }}</td>
          <td>{{ $item->receiving->consignee??'' }}</td>
          <td>{{ is_null($item->releasing)?'':Carbon\Carbon::parse($item->releasing->inspected_date)->format('Y-m-d') }}</td>
          <td>{{ $item->releasing->consignee??'' }}</td>
          <td>{{ $item->releasing->booking_no??'' }}</td>
          <td>{{ $item->releasing->seal_no??'' }}</td>
          <td>{{ $item->total_no_days }}</td>
          <td>
              @foreach($item->receiving->damages as $key=> $dmg)
              <div>
                {{ $key + 1 }}.) {{ $dmg->description }}<br>
              </div>
              @endforeach
          </td>
          <td>{{ $item->receiving->remarks??'' }}</td>
        </tr>
      @endforeach
</table>
<table>
<tr>
    <th style="font-weight:bold;">Van Count : {{ $count }}</th> 
    <th style="font-weight:bold;">IN : {{ $in }}</th> 
    <th style="font-weight:bold;">OUT : {{ $out }}</th> 
</tr>
</table>