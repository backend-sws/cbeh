
{{--<div class="row" style="justify-content: space-around;">
    <label>
        <input type="radio" name="allocate_number" value="allocate_number" onclick="togglePayment('allocate_number')"> Allocate Numbers 
    </label>
    <br>
    <label>
        <input type="radio" name="allocate_number" value="allocate_number_by_range" onclick="togglePayment('allocate_number_by_range')"> Allocate Numbers By Range
    </label>
</div>--}}

<div class="form-group">
    <label for="status">Users</label>
    <select class="form-control" id="status" name="user_id" require>
       @foreach(\App\Models\User::GetClient('client') as $user)
          <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
    </select>
</div>

<input type="hidden" name="allocated_by" value="" id="allocated_by">
<input type="hidden" name="numbers" value="" id="numbers">

<div id="allocate_number_by_range" class="hidden allocate_number_by_range">
<div class="form-group">
    <label for="status">Range</label>
    <select class="form-control" id="status" name="range_id">
        @foreach(\App\Models\Range::ablable_range() as $range)
        <option value="{{$range->id}}">{{$range->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="status">Total Number</label>
    <input type="number" class="form-control" id="status" name="count_number" />
</div>
</div>