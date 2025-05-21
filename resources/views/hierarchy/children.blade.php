<ul>
    @foreach ($children as $child)
        <li>
            {{ $child->full_name }}
            @if ($child->children->isNotEmpty())
                @include('hierarchy.children', ['children' => $child->children])
            @endif
        </li>
    @endforeach
</ul>
