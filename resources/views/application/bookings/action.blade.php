@if ($book_status!=3)
  <div class="btn-group btn-group-toggle" data-toggle="buttons">
    <label class="btn bg-olive">
      <input type="radio" name="options" id="option_b1" autocomplete="off" checked="" onclick="guestName('{{Crypt::encrypt($quotaion_id)}}')"> Guest name
    </label>
    <label class="btn bg-olive">
      <input type="radio" name="options" id="option_b1" autocomplete="off" checked="" onclick="viewData('{{Crypt::encrypt($booking_id)}}')"> View
    </label>
  </div>
@endif