<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body style="font-family: sans-serif;">
    <!-- HEADER -->
    <div style="width: 100%; display: flex;">
      <div style="width: 20%; padding: 10 px; text-align: center; display: flex; align-items: flex-end;">
        <div style="display: flex; width: 100%;">
          <div style="width: 50%; border: 1px solid; font-size: 16px; padding: 14px 0; color: red;">
            IN
          </div>
          <div style="width: 50%; border: 1px solid; font-size: 16px; padding: 14px 0; ">
            OUT
          </div>
        </div>
      </div>
      <div style="width: 60%;text-align: center;">
        <h3 style="margin-bottom: 5px;">EQUIPMENT INTERCHANGE REPORT</h3>
        <small>Kudos Trucking Corporation Container Terminal</small><br>
        <small>(082) 235-8234 235-8235</small>
      </div>
      <div style="width: 20%; padding: 10px;display: flex; align-items:center; font-size: 22px; color: red;">
        <div style="margin-right: 10px;">NO.</div>
        <div>{{ $receiving->id }}</div>
      </div>
    </div>
    <!-- END OF HEADER -->

    <!-- DATE TIME -->
    <div style="width: 100%; display: flex; margin-top: 20px; font-size: 10px;">
      <div style="width: 45%; display: flex;">
        <div style="width: 15%; border: 1px solid; padding: 10px 5px">
          <div>DATE: </div>
        </div>
        <div style="width: 40%; border: 1px solid; border-left: 0px !important; padding: 10px 5px; text-align: center;">
          <b>{{ Carbon\Carbon::parse($receiving->created_at)->format('M. d, Y') }}</b>
        </div>
        <div style="width: 15%; border: 1px solid; border-left: 0px !important; padding: 10px 5px">
          TIME:
        </div>
        <div style="width: 30%; border: 1px solid; border-left: 0px !important; padding: 10px 5px; text-align: center;">
          <b>{{ date('h:i A', strtotime($receiving->created_at)) }}</b>
        </div>
      </div>
      <div style="width: 55%; display: flex; border: 1px solid; align-items: center; padding: 0 5px; font-size: 10px;">
        <div style="margin-right: 10px; display: flex; align-items:center;">
        @if($receiving->empty_loaded == "Empty")
        <div style="margin-right: 10px; display: flex; align-items:center;">
          <span style="font-size: 25px">
            <!-- &#9744; --> <!-- Unchecked -->
            &#9745;          <!-- Checked -->
          </span>
          <span>EMPTY</span>
        </div>
        <div style="margin-right: 10px; display: flex; align-items:center;">
          <span style="font-size: 25px">
            &#9744; <!-- Unchecked -->
            <!-- &#9745; --> <!-- Checked -->
          </span>
          <span>LOADED</span>
        </div>
        @else
        <div style="margin-right: 10px; display: flex; align-items:center;">
          <span style="font-size: 25px">
            <!-- &#9744; --> <!-- Unchecked -->
            <!-- &#9745;          Checked -->
            &#9744;
          </span>
          <span>EMPTY</span>
        </div>
        <div style="margin-right: 10px; display: flex; align-items:center;">
          <span style="font-size: 25px">
            <!-- &#9744; Unchecked -->
            <!-- &#9745; --> <!-- Checked -->
            &#9745;
          </span>
          <span>LOADED</span>
        </div>
        @endif
        </div>

        @if($size_types ?? '')
          @foreach($size_types as $value)

            <div style="margin-right: 10px; display: flex; align-items:center;">
              <span style="font-size: 25px">
                @if($receiving->sizeType->code == $value->code)
                &#9745;
                @else
                &#9744;
                @endif
              </span>
              <span>{{ $value->code }}</span>
            </div>

          @endforeach
        @endif
        
      </div>
    </div>
    <!-- END OF DATE TIME -->

    <!-- BASIC INFO -->
    <div style="width: 100%; display: flex; font-size: 10px;">
      <div style="width: 25%; padding: 2px 5px; border-right: 1px solid; border-bottom: 1px solid; border-left: 1px solid;">
        HAULER / TRUCKER
        <div style="padding: 10px 0 5px 0; font-size: 12px;">
          <b>{{ $receiving->hauler }}</b>
        </div>
      </div>
      <div style="width: 25%; padding: 2px 5px; border-right: 1px solid; border-bottom: 1px solid;">
        PLATE NO. / TRUCK NO.
        <div style="padding: 10px 0 0 0; font-size: 12px;">
          <b>{{ $receiving->plate_no }}</b>
        </div>
      </div>
      <div style="width: 50%; padding: 2px 5px; border-right: 1px solid; border-bottom: 1px solid;">
        ORIGIN / CONSIGNEE'S NAME & ADDRESS
        <div style="padding: 10px 0 5px 0; font-size: 12px;">
          <b>{{ $receiving->consignee }}</b>
        </div>
      </div>
    </div>
    <!-- END OF BASIC INFO -->

    <!-- CONTAINER INFO -->
    <div style="width: 100%; display: flex; font-size: 10px;">
      <div style="text-align: center;width: 100%; padding: 15px; border: 1px solid; border-top: 0 !important;">
        <div style="font-size: 25px; border: 1px solid; width: fit-content; margin: auto; padding: 3px 8px; margin-bottom: 5px;">
        {{ $receiving->container_no }}
        </div>
        <b>CONTAINER NO.</b><br><br><br><br><br><br><br><br>
        @foreach($damages as $key => $item)
         <span style="font-size:12px;font-weight:bold;"> {{ $item->description }}</span>
        @endforeach
      </div>
    </div>
    <!-- END OF CONTAINER INFO -->

    <!-- ABBRV INFO -->
    <div style="width: 100%; font-weight: bold; font-size: 10px;">
      <div style="text-align: center; padding: 5px 15px; border: 1px solid; border-top: 0 !important; display: flex; justify-content: space-between;">
        <div>D - Dent</div> 
        <div>B - Bent</div>
        <div>S - Scratches</div>
        <div>H - Hole</div>
        <div>PI - Pushed In</div>
        <div>PO - Pushed Out</div>
        <div>PR - Previous Repair</div>
        <div>M - Missing</div>
        <div>R - Rusty</div>
      </div>
    </div>
    <!-- END OF ABBRV INFO -->

    <!-- REMARKS -->
    <div style="width: 100%; display: flex; font-size: 10px;">
      <div style="width: 70%; padding: 2px 5px; border-right: 1px solid; border-bottom: 1px solid; border-left: 1px solid;">
        REMARKS
        <div style="padding: 10px 0 5px 0; font-size: 12px;">
          <b>{{ $receiving->remarks }}</b>
        </div>
      </div>
      <div style="width: 30%; padding: 2px 5px; border-right: 1px solid; border-bottom: 1px solid;">
        SEAL NO.
        <div style="padding: 10px 0 0 0; font-size: 12px;">
          <b></b>
        </div>
      </div>
    </div>
    <!-- END OF REMARKS -->

    <!-- REMARKS -->
    <div style="width: 100%; display: flex; font-size: 10px;">
      <div style="display: flex;width: 50%; padding: 10px; border-right: 1px solid; border-bottom: 1px solid; border-left: 1px solid;">
        <div style="width: 30%;">
          DELIVERING PARTY / TRUCK DRIVER
        </div>
        <div style="width: 70%; text-align: center;">
          <div>
            <!-- <img style="width: 100px; height: 50px;" src="{{ $image }}"> -->
          </div>
          <div style="border-top: 1px solid;">
            SIGNATURE OVER PRINTED NAME
          </div>
        </div>
      </div>
      <div style="display: flex;width: 50%; padding: 10px; border-right: 1px solid; border-bottom: 1px solid;">
        <div style="width: 30%;">
          This Container is received in apparent good condition except as noted above by:
        </div>
        <div style="width: 70%; text-align: center;">
          <img style="width: 100px; height: 50px;" src="{{ $image }}">
          {{ $receiving->inspector->name }}
          <div style="border-top: 1px solid;">
            SIGNATURE OVER PRINTED NAME
          </div>
        </div>
      </div>
    </div>
    <!-- END OF REMARKS -->

  </body>
</html>