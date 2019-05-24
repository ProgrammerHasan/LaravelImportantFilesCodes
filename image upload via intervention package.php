<div class="col-md-6">
  <div class="form-group">
    <label>{{ __('Tenant Photo') }}</label>
    <input name="tenant_photo" id="tenant_photo" type="file" class="form-control" accept="image/x-png, image/jpeg" onchange="readURL(this);">
    <img class="hidden" id="tenant_photo_viewer" src="#" alt="your image" />
    <script>
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#tenant_photo_viewer').attr('src', e.target.result).width(150).height(195);
        };
        $('#tenant_photo_viewer').removeClass('hidden');
        reader.readAsDataURL(input.files[0]);
      }
    }
    </script>
  </div>
</div>

#upload the photo at directory and storing the informations at database

// Image Upload system
if($request->hasFile('product_image'))
{
  $this->validate($request, ['product_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',]);
  $product_photo_upload = $request->product_image;
  $filename = $last_inserted_id.".".$product_photo_upload->getClientOriginalExtension();
  Image::make($product_photo_upload)->resize(400,450)->save(public_path('public/uploads/product_photos/'.$filename), 20);
  Product::find($last_inserted_id)->update([
    'product_image' => $filename
  ]);
}


<!-- Delete Image  -->
  unlink(base_path('public/uploads/product_photos/'.$delete_this_photo));

    unlink(public_path('uploads/product_photos/'.$delete_this_photo));
