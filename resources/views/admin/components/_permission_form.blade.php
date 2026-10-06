<div class="form-group ">
    <label>Label</label>
    <input class="form-control" type="text" name="label" id="label" value="">
</div>
<div class="form-group ">
    <label>Access Key</label>
    <input class="form-control" type="text" name="name" id="name" value="">
</div>
@if(!empty($select_datas))
<div class="form-group ">
    {{-- html()->label('Parent', 'parent') --}}
    <select name="parent_id" class="form-control">
        <option value="" disabled selected>Select Menu Type</option>
        @foreach($select_datas as $select_data)
        <option value="{{ $select_data->id }}">{{ $select_data->name }}</option>
        @endforeach
    </select>
</div>
@endif