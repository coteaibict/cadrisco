@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.users') }}
@endsection


@section('main-content')

    <!-- Default box -->
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Minhas de Solicitações</h3>
        </div>
        <div class="box-body">
            <div class="box-tools">
                {!! Button::primary('Nova solicitação')->asLinkTo(route('documents.create')) !!}
            </div>
            <br/>
            <div class="col-sm-12">
                @include('vendor.adminlte.table.table')
            </div>
        </div>


        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    {{--@include('adminlte::newuserform')--}}
    {{--@include('adminlte::mod.admin.deletemodal')--}}
    <!-- dependencies e.g jquery, pjax, bootstrap js come here -->

@endsection