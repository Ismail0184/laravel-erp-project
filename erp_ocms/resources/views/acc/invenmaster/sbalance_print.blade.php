@extends('print')

@section('htmlheader_title', 'Stock Balance')

@section('contentheader_title', 	  ' Stock Balance')
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
	
	.tables { font-size:10px}
	.rpt { font-size:12px}
	.cname { font-size:16px}

</style>
<?php 
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
        <table  width="100%" class="tables">
        <?php 
			isset($_GET['item_id']) && $_GET['item_id']>0 ? Session::put('lgprod_id',$_GET['item_id']) : '';
			
			Session::has('com_id') ? $com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; 




			isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h1 align="center" class="cname">'.$com_name.'</h1></td></tr>';
			// data collection filter method by session	
			$data=array('dto'=>'0000-00-00');
			
			Session::has('sdto') ? 
			$data=array('dto'=>Session::get('sdto')) : array('dto'=>'0000-00-00'); 
		

				// for multiple account
				echo '<tr><td class="text-center rpt" colspan="2"><h5>Stock Balance</h5><h5 class="rpt">'.$data['dto'].'</h5></td></tr>';

		?>
        
        </table>

            <table id="buyerinfo-table" class="table table-bordered table-striped tables">
                <thead>
                    <tr>
                        <th class="text-center" width>{{ $langs['sl'] }}</th>
                        <th class="">{{ $langs['item_id'] }}</th>
                        @foreach($wh as $item)
                        	<th class="text-right">{{ $item->name }}</th>
                        @endforeach
                        <th class="text-right">{{ $langs['ttl'] }}</th>
                    </tr>
                </thead>
                <tbody>
				{{-- */$x=0;/* --}}
				@foreach($products as $item)
                <?php $has=has($item->id);?>
                @if(isset($has) && $has>0)
                {{-- */$x++;/* --}}
            	<tr>
                    <td width="50" class="text-center">{{ $x }}</td>
                	<td>{{ $item->name }}</td>
                    <?php  $ttl=''; ?>
                        @foreach($wh as $war)
                        <?php 
							$balance=fld_value($item->id, $data['dto'], $war->id);
							$balance>0 ? $balances=number_format($balance) : $balances='';
							$ttl +=$balance;
						?>
                        
                        	<td class="text-right">{{ $balances }} </td>
                        @endforeach
                        	<td class="text-right">{{ $ttl }} @if(isset($item->unit->name)){{ $item->unit->name }}@endif</td>
                    
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Export->Buyer</td><td class="text-right">Report generated by: </td></tr>
                </table>
            </div><!-- /.box-header -->
</div>
@endsection

