<form method="GET" action="" id="filterForm">
    <input type='hidden' name='status' value='{{ request()->status }}' />
    <div class="row mb-3">
        <div class="col-md-2">
            <label>Category</label>
            <select class="form-control" name="category_id" id='category_id'>
                <option value="">All</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $category_id==$cat->id?'selected':'' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Sub Category</label>
            <select class="form-control" name="sub_category_id" id='sub_category_id'>
            </select>
        </div>

        <div class="col-md-2">
            <label>Sub Sub Category</label>
            <select class="form-control" name="sub_sub_category_id" id='sub_sub_category_id'>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end mt-2">
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </div>
    </div>
</form>