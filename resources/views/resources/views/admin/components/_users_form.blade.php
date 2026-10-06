<div class="card-body">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="hidden" name="role" value="{{$select_datas}}">
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
    </div>
    <div class="form-group">
        <label for="mail">Email</label>
        <input type="email" class="form-control" name="email" id="mail" placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Number">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status">
            <option value="1">Active</option>
            <option value="2">Inactive</option>
            <option value="3">Pending</option>
        </select>
    </div>
</div>