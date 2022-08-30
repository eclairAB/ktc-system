<table>
    
        <tr>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Container No.</th> 
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Type</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Size</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Status</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Client</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Class</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Date IN</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Consignee</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Date OUT</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Consignee</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Booking</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Seal</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Days</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Damages</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Remarks</th>
          <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial; color:white;">Skeks</th>
        </tr>
        @foreach($data as $key => $item)
        <tr>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->container_no }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->type->code??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->sizeType->size??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->status??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->client->code??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->containerClass->class_code??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ is_null($item->receiving)?'':Carbon\Carbon::parse($item->receiving->inspected_date)->format('d/m/Y') }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->receiving->consignee??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ is_null($item->releasing)?'':Carbon\Carbon::parse($item->releasing->inspected_date)->format('d/m/Y') }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->releasing->consignee??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->releasing->booking_no??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->releasing->seal_no??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->total_no_days }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">
            @foreach($item->receiving->damages as $key => $item)
              <span>
              {{ $key + 1 }}.){{ $item['description'] }}&nbsp;
              </span>
            @endforeach
          </td>
          <td style="width:50px; font-size: 10px; font-family: Arial;">{{ $item->receiving->remarks??'' }}</td>
          <td style="width:50px; font-size: 10px; font-family: Arial;  color:white;">skeks</td>
        </tr>
      @endforeach
</table>
<table>
<tr>
    <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">Van Count : {{ $count }}</th> 
    <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">IN : {{ $in }}</th> 
    <th style="width:50px; font-weight:bold; font-size: 10px; font-family: Arial;">OUT : {{ $out }}</th> 
</tr>
</table>