@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.users') }}
@endsection


@section('main-content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Visualizar Solicitação</h3>
                <div class="box-tools pull-right">
                    <a href="/documents" class="btn btn-box-tool"  title="Voltar">
                        <i class="fa fa-times"></i>
                    </a>
                </div>

            </div>
            <div class="box-body">
                @php
                        if( $document->situation == 'Pendente de Envio' or $document->situation == 'Devolvida' ){
                            $linkEdit = route('documents.edit',['document' => $document->id]);
                            $linkDelete = route('documents.destroy',['document' => $document->id]);
                            $linkSend = url('/documents/send/'.$document->id);
                        }
                @endphp
                @if ($document->situation == 'Pendente de Envio' or $document->situation == 'Devolvida')
                {!! Button::success(Icon::create('envelope').' Enviar')->asLinkTo($linkSend)->small()->addAttributes(['class' => 'hidden-print']) !!}
                {!! Button::primary(Icon::pencil().' Editar')->asLinkTo($linkEdit)->small()->addAttributes(['class' => 'hidden-print']) !!}
                {!!
                Button::danger(Icon::remove().' Excluir')->asLinkTo($linkDelete)
                ->addAttributes([
                    'onclick' => "event.preventDefault();document.getElementById(\"form-delete\").submit();",
                    'class' => 'hidden-print'
                ])->small()
                !!}
                @endif
                @php
                    if( $document->situation == 'Pendente de Envio' or $document->situation == 'Devolvida' ){
                        $formDelete = FormBuilder::plain([
                            'id' => 'form-delete',
                            'url' => $linkDelete,
                            'method' => 'DELETE',
                            'style' => 'display:none'
                            ]);
                    }
                @endphp
                @if ($document->situation == 'Pendente de Envio' or $document->situation == 'Devolvida')
                {!! form($formDelete) !!}
                @endif
                <br><br>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th scope="row">Diário Oficial</th>
                        <td><a href="{{url('download/ordinance/'.$document->ordinance)}}" target="_blank">{{$document->ordinance}}</a></td>
                    </tr>
                    <tr>
                        <th scope="row">Declaração</th>
                        <td><a href="{{url('download/declaration/'.$document->declaration)}}" target="_blank">{{$document->declaration}}</a></td>
                    </tr>
                    <tr>
                        <th scope="row">Perfil Pretendido</th>
                        <td>{{$document->role}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Estado</th>
                        <td>@if ( isset($document->state))
                            {{  $document->state->initials. " - " . $document->state->name }}
                            @else
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Município</th>
                        <td>@if ( isset($document->county))
                            {{$document->county->name }}
                            @else
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Observação</th>
                        <td>{{$document->note}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Situação</th>
                        <td>{{$document->situation}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

    <!-- /.box -->
    {{--@include('adminlte::newuserform')--}}
    {{--@include('adminlte::mod.admin.deletemodal')--}}
@endsection