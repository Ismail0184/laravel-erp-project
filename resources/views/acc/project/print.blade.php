@extends('print')

@section('htmlheader_title', $langs['edit'] . ' Trandetail')

@section('contentheader_title', ' Project')
@section('main-content')

 <div class="container">
 <div class="box" >
    <div class="table-responsive">
        <div class="box-header">
        <table class="table borderless">
        <?php 
			// data collection filter method by session	
			$data=array('bname'=>'','btype'=>'','byear'=>'');
			
			Session::has('bname') ? 
			$data=array('bname'=>Session::get('bname'),'btype'=>Session::get('btype'),'byear'=>Session::get('byear')) : ''; 

			Session::has('com_id') ? 
			$com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; 
			isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h1 align="center" style="margin:0px">'.$com_name.'</h1></td></tr>
			<tr><td colspan="2"><h4 align="center" style="margin:0px">Project List</h4></td></tr>';
			
			$budget_name=array(''=>'Select ...','Revenue Budget'=>'Revenue Budget',
			'Capital Budget'=>'Capital Budget','Cash Flow Budget'=>'Cash Flow Budget','Special Budget'=>'Special Budget');
			$btypes=array(''=>'Select ...', 'Monthly'=>'Monthly', 'Yearly'=>'Yearly', 'Quarterly'=>'Quarterly');
			
		?>
        </table>
        </div><!-- /.box-header -->

            <table id="buyerinfo-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col-md-1 text-center">{{ $langs['sl'] }}			</th>
                        <th class="col-md-2">{{ $langs['name'] }}		</th>
                        <th class="col-md-3">{{ $langs['description'] }}		</th>
                        <th class="col-md-3 ">{{ $langs['location'] }}	</th>
                        <th class="col-md-1 ">{{ $langs['pdate'] }}	</th>
                        <th class="col-md-1 ">{{ $langs['sdate'] }}	</th>
                        <th class="col-md-1 ">{{ $langs['fdate'] }}	</th>
                    </tr>
                </thead>
                <tbody>
				{{-- */$x=0;/* --}}
				<?php 
					$budgets=DB::table('acc_projects')->where('com_id',$com_id)->get();
				?>
                        @foreach($budgets as $item)
                        {{-- */$x++;/* --}}
                        <tr>
                                <td width="50" class="text-center">{{ $x }}</td>
                                <td>{{ $item->name }}	</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->location }}	</td>
                                <td>{{ $item->pdate }}	</td>
                                <td>{{ $item->sdate }}	</td>
                                <td>{{ $item->fdate }}	</td>
                         </tr>
                        @endforeach    
                        <tr><td colspan="3" class="text-right"></td><td class="text-right"></td></tr>             

                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Project->Report</td><td class="text-right">Report generated by: </td></tr>
                </table>
            </div><!-- /.box-header -->
        </div>
     </div>
</div>
@endsection
