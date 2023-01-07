<div class="btn-group btn-group-toggle" data-toggle="buttons">
    <label class="btn bg-olive">
      <input type="radio" name="options" id="option_b3" autocomplete="off" checked="" onclick="gotoRooms('{{$id}}')"> Rooms
    </label>
    <label class="btn bg-olive">
      <input type="radio" name="options" id="option_b3" autocomplete="off" checked="" onclick="gotoAddons('{{$id}}')"> Addons
    </label>
    <label class="btn bg-olive">
      <input type="radio" name="options" id="option_b3" autocomplete="off" checked="" onclick="gotoDatePlans('{{$id}}')"> Rates
    </label>
    <label class="btn bg-olive">
      <input type="radio" name="options" id="option_b1" autocomplete="off" checked="" onclick="editData('{{$id}}')"> Edit
    </label>
    <label class="btn bg-warning">
      <input type="radio" name="options" id="option_b2" autocomplete="off" onclick="deleteData('{{$id}}')"> Delete
    </label>
</div>