<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        @if(config('admin.show_environment'))
            <strong>Env</strong>&nbsp;&nbsp; {!! env('APP_ENV') !!}
        @endif

        &nbsp;&nbsp;&nbsp;&nbsp;

        @if(config('admin.show_version'))
        <strong>Version</strong>&nbsp;&nbsp; {!! env('APP_VERSION') !!}
        @endif

    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href="#">Mole Corporation</a>.</strong> All rights
    reserved.
</footer>