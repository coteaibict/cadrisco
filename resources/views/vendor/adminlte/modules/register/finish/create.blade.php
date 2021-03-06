@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.users') }}
@endsection


@section('main-content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Inserir Solicitação</h3>
                <div class="box-tools pull-right">
                    <a href="/documents" class="btn btn-box-tool"  title="Voltar">
                        <i class="fa fa-times"></i>
                    </a>
                </div>

            </div>
            <div class="box-body">
                <fieldset class="form-group">
                    {!! form($form->add('incluir','submit',[
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ],
                    'label' => Icon::create('floppy-disk').'&nbsp;&nbsp;Inserir'
                ])) !!}
                </fieldset>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#state_id').on('change', function(e){
                var state_id = e.target.value;
                $.get('/county/ajax?state_id=' + state_id, function(data) {
                    $('#county_id').empty();
                    $('#county_id').append("<option value=''>Selecione</option>");
                    var sorted = _.sortBy(data,'name');
                    $.each(sorted, function(key,value){
                        $('#county_id').append("<option value='"+ value.id +"'>" + value.name + "</option>");
                    });
                });
            });
        });

    </script>
@endpush
    <!-- /.box -->
    {{--@include('adminlte::newuserform')--}}
    {{--@include('adminlte::mod.admin.deletemodal')--}}
@endsection