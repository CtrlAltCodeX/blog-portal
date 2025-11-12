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
