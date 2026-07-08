@props(['headers' => [], 'embedded' => false])

@if($embedded)
    <div class="table-responsive">
        <table class="table-custom">
            @if(!empty($headers))
                <thead><tr>@foreach($headers as $h)<th>{{ $h }}</th>@endforeach</tr></thead>
            @elseif(isset($head))
                <thead>{{ $head }}</thead>
            @endif
            <tbody>{{ $slot }}</tbody>
        </table>
    </div>
@else
<div {{ $attributes->merge(['class' => 'data-table-container']) }}>
    <div class="table-responsive">
        <table class="table-custom">
            @if(!empty($headers))
                <thead><tr>@foreach($headers as $h)<th>{{ $h }}</th>@endforeach</tr></thead>
            @elseif(isset($head))
                <thead>{{ $head }}</thead>
            @endif
            <tbody>{{ $slot }}</tbody>
        </table>
    </div>
</div>
@endif
