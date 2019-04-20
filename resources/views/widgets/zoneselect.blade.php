<div class="sidebar-form">
    <select class="form-control" id="__ZONES_SELECT__">
        @foreach($group['options'] as $select => $option)
            <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
        @endforeach
    </select>
</div>