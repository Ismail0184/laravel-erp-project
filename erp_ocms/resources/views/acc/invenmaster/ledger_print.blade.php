@extends('print')

@section('htmlheader_title', ' Ledger')

@section('contentheader_title', 	  ' Ledger')
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

        <table  width="100% classs="tables">
        <?php 
			isset($_GET['item_id']) && $_GET['item_id']>0 ? Session::put('lgprod_id',$_GET['item_id']) : '';
			
			Session::has('com_id') ? $com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; 
			isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h1 align="center" class="cname">'.$com_name.'</h1></td></tr>';

			// data collection filter method by session	
			
			Session::has('lgdto') ? 
			$data=array('prod_id'=>Session::get('lgprod_id'),'wh_id'=>Session::get('lgwh_id'),'dfrom'=>Session::get('lgdfrom'),'dto'=>Session::get('lgdto')) : 
			$data=array('prod_id'=>'','wh_id'=>'','dfrom'=>'0000-00-00','dto'=>'0000-00-00');
		
			$wh=DB::table('acc_warehouses')->where('id',$data['wh_id'])->first(); //echo $item->war_id;						
			$wh_name=''; isset($wh) && $wh->id > 0 ? $wh_name=$wh->name : $wh_name=''; //echo $wh_name;

			if (isset($data['prod_id']) && $data['prod_id']>0):
				// for single account
				echo '<tr><td ><h3 class="pull-left rpt">Transaction</h3></td>
				<td class="text-right" >
					<h3 aling="right" class="rpt">'.$products[$data['prod_id']].'</h3>';
				//  '.isset($products[$data['prod_id']]) ? $products[$data['prod_id']] : ''.'
				echo $data['wh_id']>0 ? '<h5 aling="right" class="rpt">Warehouse: '.$wh_name.'</h5>' : '';
				echo '	<h5 class="rpt">'.$data['dfrom'].' to '.$data['dto'].'</h5>
				</td></tr>';
			else:
				// for multiple account
				echo '<tr><td class="text-center" colspan="2"><h5>Inventory Transaction</h5><h5 >'.$data['dfrom'].' to '.$data['dto'].'</h5></td></tr>';
			endif;

		?>
        
        </table>

            <table id="buyerinfo-table" class="table table-bordered table-striped tables">
                <thead>
                    <tr>
                        <th class="col-md-1 text-center">{{ $langs['sl'] }}</th>
                        <th class="col-md-2">{{ $langs['idate'] }}</th>
                        <th class="col-md-1">{{ $langs['itype'] }}</th>
                        <th class="col-md-3">{{ $langs['note'] }}</th>
                        <th class="col-md-1 text-right" colspan="2">{{ $langs['rcvd'] }}</th>
                        <th class="col-md-1 text-right" >{{ $langs['issue'] }}</th>
                        <th class="col-md-1 text-right" >{{ $langs['balance'] }}</th>
                    </tr>
                </thead>
                <tbody>
				{{-- */$x=0;/* --}}
                <?php 
						$data['wh_id']>0 ? $where=array('acc_invendetails.war_id'=>$data['wh_id']) : $where=array();
						if (isset($data['prod_id']) && $data['prod_id'] > 0): 
							$sale=DB::table('acc_invenmasters')
							->join('acc_invendetails','acc_invenmasters.id','=','acc_invendetails.im_id')
							->where($where)
							->where('acc_invendetails.com_id',$com_id)
							->where('acc_invendetails.item_id',$data['prod_id'])
							->whereBetween('idate', [$data['dfrom'], $data['dto']])->get();	
						endif;
						$balance=''; $ttl_rcvd=''; $ttl_issue='';
					
						$op_bal=DB::table('acc_invenmasters')->select(DB::raw('sum(qty) as qty'))
							->join('acc_invendetails','acc_invenmasters.id','=','acc_invendetails.im_id')
							->where($where)
							->where('acc_invendetails.com_id',$com_id)
							->where('acc_invendetails.item_id',$data['prod_id'])
							->where('idate','<', $data['dfrom'])->first();
							
							$balance=$op_bal->qty;
							$unit_name='';
							if ($data['prod_id']>0):
								$prod=DB::table('acc_products')->where('com_id',$com_id)->where('id',$data['prod_id'])->first();						
								if(isset($prod) && $prod->id > 0 ):
								$unit=DB::table('acc_units')->where('id',$prod->unit_id)->first();						
								$unit_name=''; isset($unit) && $unit->id > 0 ? $unit_name=$unit->name : $unit_name='';
								endif;
							endif;
				?>
                @if($op_bal->qty)
                 <tr>
                        	<td class="text-center"></td>
                            <td></td>
                            <td></td>
                            <td>Opening Balance </td>
                            <td id="unit" class="text-right" >{{ $unit_name }}</td><td class=" text-right">{{ $op_bal->qty }}</td>
                            <td class="text-right"></td>
                            <td class="text-right">{{ $balance }}</td>
                             </tr>
                @endif
                @foreach($sale as $item)
                 	<?php
					$vnumber=$item->vnumber;
					$idate=$item->idate;
					$itype=$item->itype;
					$note=$item->note; //echo $item->note.'-osama';
					
					//$user=$item->user->name;
					$ttl='';
					$details=DB::table('acc_invendetails')->where('war_id',$data['wh_id'])->where('im_id', $item->im_id)->get(); 
					$purch=DB::table('acc_invenmasters')->where('id', $item->id)->first(); //echo $purch->amount;
					
					$cur=DB::table('acc_currencies')->where('id',$item->currency_id)->first();						
					$cur_name=''; isset($cur) && $cur->id > 0 ? $cur_name=$cur->name : $cur_name='';
					
					?>
                        {{-- */$x++;/* --}}
                        <?php 
						
							$ttl+=$item->amount;
							$item->amount> 0 ? $item->amounts=number_format($item->amount, 2): $item->amounts='';
							$item->rate> 0 ? $item->rates=number_format($item->rate, 2): $item->rates='';

							$prod=DB::table('acc_products')->where('id',$item->item_id)->first();						
							$prod_name=''; isset($prod) && $prod->id > 0 ? $prod_name=$prod->name : $prod_name='';

							$cleint_name=''; //echo $item->for;
							if($item->client_id>0): 
								$client=DB::table('acc_clients')->where('id',$item->client_id)->first();						
								isset($client) && $client->id > 0 ? $cleint_name=' '.$client->name : $cleint_name=''; //echo $prod_name.'123';
							endif;

							$for_name=''; //echo $item->for;
							if($item->for>0): 
								$for=DB::table('acc_ittypes')->where('id',$item->for)->first();						
								isset($for) && $for->id > 0 ? $for_name=' , for '.$for->name : $for_name=''; //echo $prod_name.'123';
							endif;

							$rcvd=''; $issue=''; 
							$item->qty> 0 ? $rcvd=$item->qty : $issue=substr($item->qty,1);
							$balance =$balance+$item->qty;
							$ttl_rcvd  +=$rcvd; 
							$ttl_issue +=$issue; 
							$ttl_rcvd==0 ? $ttl_rcvd='' : '';
							$ttl_issue==0 ? $ttl_issue='' : '';
							?>
                         <tr>
                        	<td class="text-center">{{ $x }}</td>
                            <td>{{ $idate }}/VNo: {{ $item->vnumber }} </td>
                            <td>{{ $itype }} </td>
                            <td>{{ $note.$cleint_name.$for_name }} </td>
                            <td id="unit" class="text-right" >{{ $unit_name }}</td><td class=" text-right">{{ $rcvd }}</td>
                            <td class="text-right">{{ $issue}}</td>
                            <td class="text-right">{{ $balance }}</td>
                             </tr>
                @endforeach
                <tr><td colspan="5" class="text-right">Total</td><td class="text-right">{{ $ttl_rcvd }}</td><td class="text-right">{{ $ttl_issue }}</td><td class="text-right">{{ $balance }}</td></tr>

                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left rpt">Source: Export->Buyer</td><td class="text-right rpt">Report generated by: </td></tr>
                </table>
            </div><!-- /.box-header -->
</div>
@endsection

