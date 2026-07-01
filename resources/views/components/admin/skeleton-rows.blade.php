@props(['rows' => 8, 'columns' => 7])

@for ($i = 0; $i < $rows; $i++)
    <tr class="animate-pulse">
        @for ($c = 0; $c < $columns; $c++)
            <td class="px-4 py-4">
                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded {{ $c === 0 ? 'w-8' : 'w-full max-w-[10rem]' }}"></div>
            </td>
        @endfor
    </tr>
@endfor
