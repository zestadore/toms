@if ($type=='password')
    <div class="form-group">
        <label for="{{$name}}">{{$title}} @if($required=="True")<span style="color:red;"> *</span>@endif</label>
        <input id="{{$id}}" type="{{$type}}" class="{{$class}}" name="{{$name}}" value="{{ old($name) }}" placeholder="{{$title}}" @if($required=="True") required @endif autocomplete="{{$name}}" autofocus>
        @error($name)
            <span class="error mt-2 text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
@elseif($type=='textarea')
    <div class="form-group">
        <label for="{{$name}}">{{$title}} @if($required=="True")<span style="color:red;"> *</span>@endif</label>
        <textarea id="{{$id}}" cols="30" rows="5" class="{{$class}}" name="{{$name}}" value="{{ old($name) }}" placeholder="{{$title}}" @if($required=="True") required @endif autocomplete="{{$name}}" autofocus></textarea>
        @error($name)
            <span class="error mt-2 text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
@elseif($type=='number')
    <div class="form-group">
        <label for="{{$name}}">{{$title}} @if($required=="True")<span style="color:red;"> *</span>@endif</label>
        <input id="{{$id}}" type="{{$type}}" class="{{$class}}" name="{{$name}}" value="{{ old($name) }}" placeholder="{{$title}}" @if($required=="True") required @endif autocomplete="{{$name}}" autofocus pattern="^[0-9]">
        @error($name)
            <span class="error mt-2 text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
@elseif($type=='file')
    <div class="form-group">
        <label for="{{$name}}">{{$title}} @if($required=="True")<span style="color:red;"> *</span>@endif</label>
        <div class="input-group">
            <div class="custom-file">
                <input type="file" class="{{$class}}" id="{{$id}}" name="{{$name}}"  @if($required=="True") required @endif>
                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
            </div>
        </div>
        @error($name)
            <span class="error mt-2 text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
@else
    <div class="form-group">
        <label for="{{$name}}">{{$title}} @if($required=="True")<span style="color:red;"> *</span>@endif</label>
        <input id="{{$id}}" type="{{$type}}" class="{{$class}}" name="{{$name}}" value="{{ old($name) }}" placeholder="{{$title}}" @if($required=="True") required @endif autocomplete="{{$name}}" autofocus>
        @error($name)
            <span class="error mt-2 text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
@endif