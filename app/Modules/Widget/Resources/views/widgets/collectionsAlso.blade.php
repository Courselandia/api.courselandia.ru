<div class="collection-also">
    @if ($collection->image_middle_id)
        <div class="collection-also__media">
            <img src="{{ $collection->image_middle_id->pathCache }}"
                 width="{{ $collection->image_middle_id->width }}"
                 height="{{ $collection->image_middle_id->height }}"
                 alt="{{ $collection->name }}"
                 title="{{ $collection->name }}"
                 class="collection-also__image"/>
        </div>
    @endif
    <div class="collection-also__content">
        <div class="collection-also__call">
            Смотрите подборку курсов:
        </div>
        <div class="collection-also__title">
            <a href="https://courselandia.ru/collections/{{ $collection->link }}"
               target="_blank"
               class="collection-also__link">
                {{ $collection->name }}
            </a>
        </div>
    </div>
</div>