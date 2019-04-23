<div class="sidebar-form">
    <select class="form-control">
        @foreach($group['options'] as $select => $option)
            <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
        @endforeach
    </select>
</div>