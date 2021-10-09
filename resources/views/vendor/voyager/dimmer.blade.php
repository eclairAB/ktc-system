{{-- <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('{{ $image }}');">
    <div class="dimmer"></div>
    <div class="panel-content">
        @if (isset($icon))<i class='{{ $icon }}'></i>@endif
        <h4>{!! $title !!}</h4>
        <p>{!! $text !!}</p>
        <a href="{{ $button['link'] }}" class="btn btn-primary">{!! $button['text'] !!}</a>
    </div>
</div> --}}

<div class="panel widget right bgimage" style="margin-bottom:0;overflow:hidden; padding:0px; background-color: {{ $color }}">
{{--    <div class="dimmer"></div>--}}
    <div class="" style="margin: 4px; padding: 4px;">
{{--        @if (isset($icon))<i class='{{ $icon }}'></i>@endif--}}
        <h4 style="margin: 4px;">{!! $title !!}</h4>
        <p style="margin: 4px;">{!! $text !!}</p>
        <p style="margin: 4px;">{!! $count !!}</p>
{{--        <a style="margin: 4px;" href="{{ $button['link'] }}" class="btn btn-primary">{!! $button['text'] !!}</a>--}}
    </div>
</div>