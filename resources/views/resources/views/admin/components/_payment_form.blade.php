<div class="row justify-content-around mb-3">
    <label>
        <input type="radio" name="payment_method" value="bank" onclick="togglePayment('bank')"> Bank Account Details
    </label>
    <label>
        <input type="radio" name="payment_method" value="crypto" onclick="togglePayment('crypto')"> Crypto Payment
    </label>
</div>

<div class="bank hidden">
    <div class="form-group ">
        <label>Beneficiary Name </label>
        <input class="form-control" type="text" name="name" id="label" value="">
    </div>
    <div class="form-group ">
        <label>Acc/IBAN No </label>
        <input class="form-control" type="text" name="acc_no" id="label" value="">
    </div>

    <div class="form-group ">
        <label>SWIFT</label>
        <input class="form-control" type="text" name="swift" id="label" value="">
    </div>
    <div class="form-group ">
        <label>Bank Name</label>
        <input class="form-control" type="text" name="bank_name" id="label" value="">
    </div>

    <div class="form-group ">
        <label>Bank Country</label>
        <input class="form-control" type="text" name="bank_name" id="label" value="">
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

<div class="crypto hidden">
    <div class="form-group ">
        <label>Crypto Payment Type</label>
        <div class="row justify-content-around mb-3">
            <label>
                <input type="radio" name="crypto_type" value="bank"> Bitcoin 
            </label>
            <label>
                <input type="radio" name="crypto_type" value="crypto" >Ethereum 
            </label>
            <label>
                <input type="radio" name="crypto_type" value="crypto" >Litecoin  
            </label>
        </div>
    </div>
    <div class="form-group ">
        <label>Wallet Address</label>
        <input class="form-control" type="text" name="acc_no" id="label" value="">
    </div>
    <div class="form-group ">
        <label>Network Type</label>
        <input class="form-control" type="text" name="acc_no" id="label" value="">
    </div>
</div>