<div class="publication-also">
    @if ($publication->image_middle_id)
        <div class="publication-also__media">
            <img src="{{ $publication->image_middle_id->pathCache }}"
                 width="{{ $publication->image_middle_id->width }}"
                 height="{{ $publication->image_middle_id->height }}"
                 alt="{{ $publication->header }}"
                 title="{{ $publication->header }}"
                 class="publication-also__image"/>
        </div>
    @endif
    <div class="publication-also__content">
        <div class="publication-also__call">
            Читайте также:
        </div>
        <div class="publication-also__title">
            <a href="https://courselandia.ru/blog/{{ $publication->link }}"
               target="_blank"
               class="publication-also__link">
                {{ $publication->header }}
            </a>
        </div>
    </div>
</div>