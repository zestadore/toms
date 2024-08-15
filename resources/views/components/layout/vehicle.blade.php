@php
    use Carbon\Carbon;
@endphp
<div class="info-box bg-secondary">
    <div class="info-box-content">
        <span class="info-box-number">
            <div class="row" style="width: 100%">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" id="dates{{$key}}">
                    {{Carbon::parse($checkInDate)->format('d-M-Y')}}
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <span id="directRates{{$key}}"></span>
                </div>
            </div>
        </span>
        <div class="progress">
          <div class="progress-bar" style="width: 100%"></div>
        </div>
        <span class="info-box-text">
            <div class="row container">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="destinations">Destination <span style="color:red;">*</span></label>
                        <select name="destinations[]" id="destinations" class="form-control select-destination" data-key="{{$key}}" data-date="{{$checkInDate}}">
                            <option value="">Select a destination</option>
                            @foreach ($destinations as $destination)
                                <option value="{{$destination->id}}">{{$destination->destination}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="itinerary">Itinerary <span style="color:red;">*</span></label>
                        <select name="itinerary[]" id="itinerary" class="form-control select-itinerary" data-key="{{$key}}" data-date="{{$checkInDate}}">
                            <option value="">Select an itinerary</option>
                        </select>
                    </div>
                </div>
            </div><br>
        </span>
      </div>
</div>