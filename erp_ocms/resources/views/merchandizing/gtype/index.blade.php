@extends('app')

@section('htmlheader_title', 'Gtypes')

@section('contentheader_title', 'Gtypes')

@section('main-content')

    <div class="box">
        <div class="box-header">
            @if (Entrust::can('create_gtype'))
            <a href="{{ URL::route('gtype.create') }}" class="btn btn-primary pull-right btn-sm">{{ $langs['add_new'] }} Gtype</a>
            @endif
        </div><!-- /.box-header -->
        <div class="box-body">
            <table id="gtype-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ $langs['sl'] }}</th>
                        <th>{{ $langs['name'] }}</th>
                        @if (Entrust::can('update_gtype'))
                        <th></th>
                        @endif
                        @if (Entrust::can('delete_gtype'))
                        <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                {{-- */$x=0;/* --}}
                @foreach($gtypes as $item)
                    {{-- */$x++;/* --}}
                    <tr>
                        <td width="50">{{ $x }}</td>
                        <td><a href="{{ url('/gtype', $item->id) }}">{{ $item->name }}</a></td>
                        @if (Entrust::can('update_gtype'))
                        <td width="80"><a class="btn btn-edit btn-block" title="{{ $langs['edit'] }}" href="{{ URL::route('gtype.edit', $item->id) }}"><i class="fa fa-edit"></i></a></td> 
                        @endif
                        @if (Entrust::can('delete_gtype'))
                        <td width="80">{!! Form::open(['route' => ['gtype.destroy', $item->id], 'method' => 'DELETE']) !!}
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
        $("#gtype-table").dataTable({
    		"aoColumns": [ null, null<?php if (Entrust::can("update_gtype")): ?>, { "bSortable": false }<?php endif; if (Entrust::can("delete_gtype")): ?>, { "bSortable": false }<?php endif ?> ]
    	});
    } );
</script>

@endsection
