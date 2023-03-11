<div class="btn-group btn-group-toggle" data-toggle="buttons">
    <label class="btn bg-olive">
      <input type="radio" name="options" id="option_b1" autocomplete="off" checked="" onclick="editData('{{Crypt::encrypt($id)}}')"> Edit
    </label>
    <label class="btn bg-warning">
      <input type="radio" name="options" id="option_b2" autocomplete="off" onclick="deleteData('{{Crypt::encrypt($id)}}')"> Delete
    </label>
</div>