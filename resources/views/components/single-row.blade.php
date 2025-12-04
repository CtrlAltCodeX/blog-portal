@php
$showDocs = $showDocs ?? false;
$p = $page ?? null;
@endphp

<tr>
    <td>
        <select name="rows[{{ $index }}][category_id]" class="form-control category_id" required>
            <option value="">Select</option>

            @foreach($categories as $c)
            <option value="{{ $c->id }}"
                @if($p && $p->category_id == $c->id) selected @endif>
                {{ $c->name }}
            </option>
            @endforeach
        </select>
    </td>

    <td>
        <select name="rows[{{ $index }}][sub_category_id]" class="form-control sub_category_id" required>
            @if($p)
            <option value="{{ $p->sub_category_id }}">{{ $p->subCategory->name }}</option>
            @else
            <option value="">Select</option>
            @endif
        </select>
    </td>

    <td>
        <select name="rows[{{ $index }}][sub_sub_category_id]" class="form-control sub_sub_category_id" required>
            @if($p)
            <option value="{{ $p->sub_sub_category_id }}">{{ $p->subSubCategory->name }}</option>
            @else
            <option value="">Select</option>
            @endif
        </select>
    </td>
    <td>
        <input type="text" name="rows[{{ $index }}][title]"
            class="form-control"
            value="{{ $p->title ?? '' }}"
            required>
    </td>
    <td>
        <textarea name="rows[{{ $index }}][brief_description]" class="form-control">{{ $p->description ?? '' }}</textarea>
    </td>

    <td>
        <select name="rows[{{ $index }}][any_preferred_date]" class="form-control">
            <option value="No">No</option>
            <option value="Yes">Yes</option>
        </select>
    </td>

    <td>
        <input type="date" name="rows[{{ $index }}][preferred_date]" class="form-control">
    </td>

    <td>
        <input type="file" name="rows[{{ $index }}][attach_image]" class="form-control">
    </td>

    @if($showDocs)
    <td>
        <input type="file" name="rows[{{ $index }}][attach_docs]" class="form-control">
    </td>
    @endif

    <td>
        <input type="text" name="rows[{{ $index }}][attach_url]" class="form-control" placeholder="https://...">
    </td>

    <td>
        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
    </td>
</tr>