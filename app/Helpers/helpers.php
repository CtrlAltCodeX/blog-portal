<?php


/**
 * Render nested category dropdown options recursively.
 *
 * @param \Illuminate\Support\Collection|array $categories
 * @param string $prefix
 * @param int|null $selectedId
 * @return string
 */
function renderCategoryOptions($categories, $prefix = '', $selectedId = null)
{
    $html = '';

    foreach ($categories as $category) {
        $selected = $selectedId == $category->id ? 'selected' : '';
        $html .= '<option value="' . $category->id . '" ' . $selected . '>' . $prefix . $category->name . '</option>';

        if ($category->children && $category->children->count()) {
            $html .= renderCategoryOptions($category->children, $prefix . '— ', $selectedId);
        }
    }

    return $html;
}


function preferences()
{
    return [
        1 => 'High',
        2 => 'Medium',
        3 => 'Low',
    ];
}

function status($key = null)
{
    $status = [
        1 => 'pending',
        2 => 'approved',
        3 => 'denied',
    ];

    return $key ? $status[$key] : $status;
}
