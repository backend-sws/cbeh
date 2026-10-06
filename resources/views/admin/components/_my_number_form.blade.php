<div class="form-group">
    <label for="dropdown-to">Add Numbers</label>
    <select class="form-control" name="name" id="dropdown-to" onchange="show_selected_div(this,'number_div')">
        <option value="series">Series</option>
        <option value="individual">Individual</option>
    </select>
</div>
<div class="series_div row number_div">
    <div class="form-group col-md-6">
        <label for="name">Number Starting From</label>
        <input type="tel" class="form-control" id="start_number" name="start_number">
    </div>
    <div class="form-group col-md-6">
        <label for="name">Number End Till</label>
        <input type="tel" class="form-control" id="name" name="end_number">
    </div>
</div>
<div class="individual_div number_div" style="display: none;">
    <div class="form-group">
        <label for="name">Enter Individual Numbers (comma or new line separated)</label>
        <textarea class="form-control" name="numbers"></textarea>
    </div>
</div>

<div class="form-group">
    <label for="dropdown-to">Select Or Create Range Name</label>
    <select class="form-control" name="range_id" id="dropdown-to" onchange="show_selected_div(this,'selected_div')">
        <option value="">Select option</option>
        <option value="new_range">Add New Range</option>
        @foreach(App\Models\Range::all() as $range)
        <option value="{{$range->id}}">{{$range->name}} ({{$range->rate}})</option>
        @endforeach
    </select>
</div>
<div class="new_range_div row selected_div" style="display: none;">
    <div class="form-group col-md-6">
        <label for="name">New Range Name</label>
        <input type="text" class="form-control" id="name" name="range_name">
    </div>
    <div class="form-group col-md-6">
        <label for="name">Sale Rate Per Minute</label>
        <input type="text" class="form-control" id="name" name="range_rate">
    </div>
    <div class="form-group col-md-6">
        <label for="name">By Rate Per Minute</label>
        <input type="text" class="form-control" id="name" name="by_rate">
    </div>
</div>
<div class="form-group">
    <label for="dropdown-to">Select Supplier</label>
    <select class="form-control" name="supplier_id" id="dropdown-to">
        <option value="">Select an option</option>
        @foreach(App\Models\Partner::all() as $partner)
        <option value="{{$partner->id}}">{{$partner->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="dropdown-to">Select Route</label>
    <select class="form-control" name="ivr_route_id" id="dropdown-to">
        <option value="">Select option</option>
        @foreach(App\Models\IVRRoute::all() as $ivr_route)
        <option value="{{$ivr_route->id}}">{{$ivr_route->name}} </option>
        @endforeach
    </select>
</div>