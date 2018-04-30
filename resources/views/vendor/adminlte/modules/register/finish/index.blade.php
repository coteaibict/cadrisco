@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.users') }}
@endsection


@section('main-content')

    <!-- Default box -->
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Listagem de Usuários</h3>
        </div>
        <div class="box-body">
                    {!! $grid !!}
        </div>


        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    {{--@include('adminlte::newuserform')--}}
    {{--@include('adminlte::mod.admin.deletemodal')--}}
    <!-- dependencies e.g jquery, pjax, bootstrap js come here -->

    <!-- load the grid's javascipt -->
    <script src="{{ asset('vendor/leantony/grid/js/grid.js') }}"></script>
    <script>
        // setup ajax. This is required, so that the CSRF token is sent during AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <!-- the initialization javascript for any of the grid(s) you create will be injected here. Having this will ensure that the script is executed after it's dependencies have been loaded. -->
    @stack('grid_js')
@endsection