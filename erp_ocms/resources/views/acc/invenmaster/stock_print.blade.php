@extends('print')

@section('htmlheader_title', 'Stock Balance')

@section('contentheader_title', 	  ' Stock Balance 1')
@section('main-content')

<style>
    table.borderless td,table.borderless th{
     border: none !important;
	}

	h1{
		font-size: 1.6em;
	}
	h5{
		font-size: 1.2em; margin:0px
	}
	#unit {width: 10px} 
	#cur {width: 10px}
	.table { font-size:10px}
	.com {font-size:16px}
	.rpt { font-size:12px}
</style>
<?php 
		
			Session::has('sbdto') ? 
			$data=array('war_id'=>Session::get('sbwar_id'),'group_id'=>Session::get('sbgroup_id'),'dto'=>Session::get('sbdto')) : 
			$data=array('war_id'=>'','group_id'=>'','dto'=>date('Y-m-d')); 		
			$wh = DB::table('acc_warehouses')->where('com_id',$com_id)->where('id',$data['war_id'])->latest()->get();
			
		function fld_value($id, $idate, $war_id){
			Session::has('com_id') ? $com_id=Session::get('com_id') : $com_id='' ;
			$stock=DB::table('acc_invendetails')->join('acc_invenmasters','acc_invendetails.im_id','=','acc_invenmasters.id')
			->where('acc_invenmasters.com_id',$com_id)->where('item_id',$id)->where('idate','<=',$idate)->where('war_id', $war_id)->sum('qty');
			if (isset($stock) ):
				return $stock;
			else:
				return 0;
			endif;
		}
		function has($id){
			Session::has('com_id') ? $com_id=Session::get('com_id') : $com_id='' ;
			$stock=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$id)->sum('qty');
			if (isset($stock) ):
				return $stock;
			else:
				return 0;
			endif;
		}

 ?>
        <table  width="100%>
        <?php 

			isset($_GET['item_id']) && $_GET['item_id']>0 ? Session::put('lgprod_id',$_GET['item_id']) : '';
			
			Session::has('com_id') ? $com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; 

			$wh_name='';
			if ($data['war_id']):
				$whs=DB::table('acc_warehouses')->where('id',$data['war_id'])->first(); //DB::table('acc_warehouses')->where('id',$data['war_id'])->first(); $com_name=''; 
				isset($whs) && $whs->id>0 ? $wh_name='('.$whs->name.')' : $wh_name='';
			endif;

			isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h1 align="center" class="com">'.$com_name.'</h1></td></tr>';
			// data collection filter method by session	

				// for multiple account
				echo '<tr><td class="text-center" colspan="2"><h5 class="rpt">Stock Balance '.$wh_name.'</h5><h5 >'.$data['dto'].'</h5></td></tr>';

		?>
        
        </table>

            <table id="buyerinfo-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center" width>{{ $langs['sl'] }}</th>
                        <th class="">{{ $langs['item_id'] }}</th>
                        <th class="">{{ $langs['group_id'] }}</th>
                        @foreach($wh as $item)
                        	<th class="text-right">{{ $item->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
				{{-- */$x=0;/* --}}
				@foreach($products as $item)
                <?php $has=has($item->id); $balances='';
				$balance=fld_value($item->id, $data['dto'], $data['war_id']);
				?>
                {{-- */$x++;/* --}}
                @if ($balance!=0)
            	<tr>
                    <td width="50" class="text-center">{{ $x }}</td>
                	<td>{{ $item->name }}</td>
                     <td> @if(isset($item->group->name)){{ $item->group->name }}@endif</td>
                    <?php 
							$balance>0 ? $balances=number_format($balance) : $balances='';
							?>
                        	<td class="text-right">{{ $balance }} @if(isset($item->unit->name)){{ $item->unit->name }}@endif</td>
                    
                    </tr>
                    @endif
                   
                @endforeach
                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Inventory->Stock Balance</td><td class="text-right">Report generated by: </td></tr>
                </table>
            </div><!-- /.box-header -->
@endsection

