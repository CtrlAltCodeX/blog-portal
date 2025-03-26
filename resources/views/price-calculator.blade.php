<div class="card">
    <div class="card-body" style="background-color: antiquewhite;">
        <h4>PRICE CALCULATION</h4>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="pub_name" class="form-label">{{ __('Publication') }}</label>
                <select class="searchable_dropdown form-control" name="pub_name" id="pub_name">
                    <option value="">--Publication--</option>
                    @foreach($publications as $pub)
                    <option value="{{ $pub->id }}">{{ $pub->pub_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label for="book_name" class="form-label">{{ __('Book Type') }}</label>
                <select class="form-control" name="book_name" id="book_name">
                    <option value="">-- Select Book --</option>
                </select>
            </div>


            <div class="form-group col-md-3 text-danger">
                <label class="form-label">{{ __('Selling Prices ( + 45 )') }}</label>
                <div class="selling-prices">
                    <strong>Min Selling Price:</strong> <span id="selling_price1">--</span> <br>
                    <strong>Max Selling Price:</strong> <span id="selling_price2">--</span>
                </div>
            </div>

            <div class="form-group col-md-2 text-success">
                <label class="form-label">{{ __('Selling Prices ( - 45 )') }}</label>
                <div class="selling-prices">
                    <strong>Min Selling Price:</strong> <span id="selling_price_minus1">--</span> <br>
                    <strong>Max Selling Price:</strong> <span id="selling_price_minus2">--</span>
                </div>
            </div>
            <div class="col-md-1">
                <strong>Weight:</strong> <span id="weight">--</span> <br>
            </div>
        </div>
    </div>
</div>