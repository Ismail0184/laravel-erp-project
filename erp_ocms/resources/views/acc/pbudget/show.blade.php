@extends('app')

@section('htmlheader_title', 'Pbudget')

@section('contentheader_title', 'Pbudget')

@section('main-content')

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>{{ $langs['id'] }}</th>
                <th>{{ $langs['name'] }}</th>
            </tr>
            <tr>
                <td>{{ $pbudget->id }}</td>
                <td>{{ $pbudget->name }}</td>
            </tr>
        </table>
    </div>

@endsection
