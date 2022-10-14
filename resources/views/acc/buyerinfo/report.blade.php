@extends('app')

@section('htmlheader_title', 'Dashboard')

@section('contentheader_title', 'Buyer Report')

@section('main-content')

 <div class="container">
 <div class="box" >
    <div class="table-responsive">
        <div class="box-header">
        <table class="table borderless">
        <?php 
			Session::has('com_id') ? 
			$com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h1 align="center">'.$com_name.'</h1></td></tr>';
			
			Session::put('brdfrom', date('Y-01-01'));
			Session::put('brdto', date('Y-m-d'));
			
		?>
        <tr><td class="text-center"><h5>Buyer List</h5></td></tr>
        </table>
        </div><!-- /.box-header -->

            <table id="buyerinfo-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col-md-1">{{ $langs['sl'] }}</th>
                        <th class="col-md-3">{{ $langs['name'] }}</th>
                        <th class="col-md-2">{{ $langs['contact'] }}</th>
                        <th class="col-md-2">{{ $langs['address'] }}</th>
                        <th class="col-md-2">{{ $langs['country_id'] }}</th>
                        <th class="col-md-2">{{ $langs['email'] }}</th>
                    </tr>
                </thead>
                <tbody>
				{{-- */$x=0;/* --}}
                @foreach($buyer as $item)
                {{-- */$x++;/* --}}
                <tr>
                        <td width="50">{{ $x }}</td>
                        <td><a href="{{ url('/lcinfo/report?buyer_id='. $item->id) }}">{{ $item->name }}</a></td>
                        <td>{{ $item->contact }}</td>
                        <td>{{ $item->address }}</td>
                        <td><a href="{{ url('/lcinfo/report?country_id='. $item->country_id) }}">{{ $item->country->name }}</a></td>
                        <td>{{ $item->email }}</td>
                 </tr>
                @endforeach

                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Export->Buyer</td><td class="text-right">Report generated by: {{ $item->user->name }}</td></tr>
                </table>
            </div><!-- /.box-header -->
        </div>
     </div>
</div>
@endsection

@section('custom-scripts')

<script type="text/javascript">
    jQuery(document).ready(function($) {

    } );
</script>

@endsection