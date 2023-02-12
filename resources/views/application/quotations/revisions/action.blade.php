<div class="btn-group btn-group-toggle" data-toggle="buttons">
  <label class="btn bg-olive">
    @if ($revision_count>0)
      <input type="radio" name="options" id="option_b1" autocomplete="off" checked="" onclick="gotoViewRevision('{{$id}}')"> 
      View
    @else
      <input type="radio" name="options" id="option_b1" autocomplete="off" checked="" onclick="gotoCalculations('{{$id}}')"> 
      Package
    @endif
  </label>
  <label class="btn bg-olive">
    <input type="radio" name="options" id="option_b1" autocomplete="off" checked="" onclick="askAvailability('{{$id}}')"> 
      Request availability
  </label>
</div>