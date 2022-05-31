<div id="div_id_{{ $name }}" class="form-group @error($name)has-error @enderror">
    <label for="id_{{ $name }}" class="control-label @if($required)requiredField @endif">{{ $displayName }}@if($required)<span class="asteriskField">*</span>@endif</label>
    <div class="controls">
        <input type="{{ $type }}" name="{{ $name }}" maxlength="{{ $length }}" class="textinput textInput form-control @error($name)form-control-danger @enderror" id="id_{{ $name }}" @if($required)required @endif value="{{ $value }}">
        @error($name)
        <ul class="help-block">
            @foreach($errors->get($name) as $error)
                <li><strong>{{ $error }}</strong></li>
            @endforeach
        </ul>
        @enderror
        @if ($description)
            <div id="hint_id_{{ $name }}" class="help-block">{!! $description !!}</div>
        @endif
    </div>
</div>
