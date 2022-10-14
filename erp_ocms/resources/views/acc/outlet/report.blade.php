@extends('app')

@section('htmlheader_title', 'Lcimports')

@section('contentheader_title', 'Lc for import')

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
		?>
        <tr><td class="text-center"><h4>Outlet List</h4></td></tr>
        </table>
        </div><!-- /.box-header -->

            <table id="buyerinfo-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="col-md-1">{{ $langs['sl'] }}</th>
                        <th class="col-md-3">{{ $langs['name'] }}</th>
                        <th class="col-md-2">{{ $langs['emp_id'] }}</th>
                        <th class="col-md-2">{{ $langs['address'] }}</th>
                        <th class="col-md-2">{{ $langs['email'] }}</th>
                    </tr>
                </thead>
                <tbody>
				{{-- */$x=0;/* --}}
                <?php $employee=array(''=>'Select ...', '1'=>'Hasan Habib'); ?>
                @foreach($outlets as $item)
                {{-- */$x++;/* --}}
                <tr>
                        <td width="50">{{ $x }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $employee[$item->emp_id] }}</td>
                        <td>{{ $item->address }}</td>
                        <td>{{ $item->email }}</td>
                 </tr>
                @endforeach

                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Sales->Outlet</td><td class="text-right">Report generated by: {{ $item->user->name }}</td></tr>
                </table>
            </div><!-- /.box-header -->
        </div>
     </div>
</div>
@endsection