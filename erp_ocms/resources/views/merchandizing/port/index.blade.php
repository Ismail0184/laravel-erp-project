@extends('app')

@section('htmlheader_title', 'Ports')

@section('contentheader_title', 'Ports')

@section('main-content')

    <div class="box">
        <div class="box-header">
            @if (Entrust::can('create_port'))
            <a href="{{ URL::route('port.create') }}" class="btn btn-primary pull-right btn-sm">{{ $langs['add_new'] }} Port</a>
            @endif
        </div><!-- /.box-header -->
        <div class="box-body">
            <table id="port-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ $langs['sl'] }}</th>
                        <th>{{ $langs['name'] }}</th>
                        @if (Entrust::can('update_port'))
                        <th></th>
                        @endif
                        @if (Entrust::can('delete_port'))
                        <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                {{-- */$x=0;/* --}}
                @foreach($ports as $item)
                    {{-- */$x++;/* --}}
                    <tr>
                        <td width="50">{{ $x }}</td>
                        <td><a href="{{ url('/port', $item->id) }}">{{ $item->name }}</a></td>
                        @if (Entrust::can('update_port'))
                        <td width="80"><a class="btn btn-edit btn-block" title="{{ $langs['edit'] }}" href="{{ URL::route('port.edit', $item->id) }}"><i class="fa fa-edit"></i></a></td> 
                        @endif
                        @if (Entrust::can('delete_port'))
                        <td width="80">{!! Form::open(['route' => ['port.destroy', $item->id], 'method' => 'DELETE']) !!}
                            {!! Form::submit('&#xf1f8;', ['class' => 'btn btn-delete btn-block fa', 'title' => $langs['delete'], 'onclick' => 'return confirm("Are you sure?");']) !!}
                            {!!  Form::close() !!}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('custom-scripts')

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#port-table").dataTable({
    		"aoColumns": [ null, null<?php if (Entrust::can("update_port")): ?>, { "bSortable": false }<?php endif; if (Entrust::can("delete_port")): ?>, { "bSortable": false }<?php endif ?> ]
    	});
    } );
</script>

@endsection
