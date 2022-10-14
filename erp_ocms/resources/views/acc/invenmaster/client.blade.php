@extends('app')

@section('htmlheader_title', 'Client Ledger')

@section('contentheader_title', 	  ' Client Ledger')
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
</style>

 <div class="container">
 <div class="box" >
 <div class="box" >
         <div class="box-header">
        <a href="{{ url('invenmaster/client_print') }}" title="{{ $langs['print'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-print"></i></a>
<!--        <a href="{{ url('invenmaster/ledger_pdf') }}" title="{{ $langs['download'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-download"></i></a>
        <a href="{{ url('invenmaster/ledger_pdf') }}" title="{{ $langs['pdf'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-pdf-o"></i></a>
        <a href="{{ url('invenmaster/ledger_excel') }}" title="{{ $langs['excel'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-excel-o"></i></a>
        <a href="{{ url('salemaster/ledger_csv') }}" title="{{ $langs['csv'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-excel-o"></i></a>
        <a href="{{ url('salemaster/ledger_word') }}" title="{{ $langs['word'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-word-o"></i></a>
-->        </div><!-- /.box-header -->

    <div class="table-responsive">
        <table  width="100%>
        <?php 
			isset($_GET['item_id']) && $_GET['item_id']>0 ? Session::put('lgprod_id',$_GET['item_id']) : '';
			
			Session::has('com_id') ? $com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; 
			isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h1 align="center">'.$com_name.'</h1></td></tr>';

			// data collection filter method by session	
			$data=array('client_id'=>'','dfrom'=>'0000-00-00','dto'=>'0000-00-00');
			
			Session::has('cldto') ? 
			$data=array('client_id'=>Session::get('clclient_id'),'dfrom'=>Session::get('cldfrom'),'dto'=>Session::get('cldto')) : ''; 
		

			if (isset($data['client_id']) && $data['client_id']>0):
				// for single account
				echo '<tr><td ><h3 class="pull-left">Transaction</h3></td>
				<td class="text-right" >
					<h3 aling="right">'.$client[$data['client_id']].'</h3>';
				echo '	<h5 >'.$data['dfrom'].' to '.$data['dto'].'</h5>
				</td></tr>';
			else:
				// for multiple account
				echo '<tr><td class="text-center" colspan="2"><h5>Inventory Transaction</h5><h5 >'.$data['dfrom'].' to '.$data['dto'].'</h5></td></tr>';
			endif;

		?>
        
        </table>

            <table id="buyerinfo-table" class="table table-bordered table-striped">
                <thead>
                <tr><td colspan="7"><a href="{!! url('/invenmaster/client?flag=filter') !!}"> Filter  </a>
					<?php 
                    	$flags=''; isset($_GET['flag']) ? $flags=$_GET['flag'] : ''; 
						 !isset($data['prod_id']) ? $data['prod_id']='' : '' ;
                   
				    // to get data by fileter
					?>
                    @if ($flags=='filter')
                           {!! Form::open(['url' => 'invenmaster/clfilter', 'class' => 'form-horizontal']) !!}
            
                            <div class="form-group">
                                {!! Form::label('client_id', $langs['client_id'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::select('client_id', $client, null, ['class' => 'form-control select2','required']) !!}
                                </div>    
                            </div>
                            <div class="form-group">
                                {!! Form::label('dfrom', $langs['dfrom'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::text('dfrom',  date('Y-m-01'), ['class' => 'form-control', 'id'=>'dfrom','required']) !!}
                                </div>    
                            </div>
                            <div class="form-group">
                                {!! Form::label('dto', $langs['dto'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::text('dto', date('Y-m-d'), ['class' => 'form-control', 'id'=>'dto','required']) !!}
                                </div>    
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-3">
                                {!! Form::submit($langs['find'], ['class' => 'btn btn-primary form-control']) !!}
                                </div>    
                            </div>
                          {!! Form::close() !!}
                     @endif
               </td></tr>
                    <tr>
                        <th class="col-md-1 text-center">{{ $langs['sl'] }}</th>
                        <th class="col-md-2">{{ $langs['idate'] }}</th>
                        <th class="col-md-1">{{ $langs['itype'] }}</th>
                        <th class="col-md-3">{{ $langs['note'] }}</th>
                        <th class="col-md-1 text-right" >{{ $langs['rcvd'] }}</th>
                        <th class="col-md-1 text-right" >{{ $langs['issue'] }}</th>
                        <th class="col-md-1 text-right" >{{ $langs['balance'] }}</th>
                    </tr>
                </thead>
                <tbody>
				{{-- */$x=0;/* --}}
                <?php 
						if (isset($data['client_id']) && $data['client_id'] > 0): 
							$sale=DB::table('acc_invenmasters')
							->join('acc_invendetails','acc_invenmasters.id','=','acc_invendetails.im_id')
							->where('acc_invendetails.com_id',$com_id)
							->where('acc_invenmasters.client_id',$data['client_id'])
							->whereBetween('idate', [$data['dfrom'], $data['dto']])->get();	
						endif;
						$balance=''; $ttl_rcvd=''; $ttl_issue=''; $ttl=''; $balances='';
						
						$op_bal=DB::table('acc_invenmasters')->select(DB::raw('sum(qty) as qty'))
							->join('acc_invendetails','acc_invenmasters.id','=','acc_invendetails.im_id')
							->where('acc_invendetails.com_id',$com_id)
							->where('acc_invenmasters.client_id',$data['client_id'])
							->where('idate','<', $data['dfrom'])->first();
							
							$balance=$op_bal->qty;
							$unit_name=''; $ttl_rcvds='' ; $ttl_issues=''; 
/*								$prod=DB::table('acc_products')->where('com_id',$com_id)->where('id',$ietm->)->first();						
								$unit=DB::table('acc_units')->where('id',$prod->unit_id)->first();						
								$unit_name=''; isset($unit) && $unit->id > 0 ? $unit_name=$unit->name : $unit_name='';
*/				?>
                @if($op_bal->qty)
                 <tr>
                        	<td class="text-center"></td>
                            <td></td>
                            <td></td>
                            <td>Opening Balance </td>
                            <td class=" text-right">{{ $op_bal->qty }}</td>
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
					$details=DB::table('acc_invendetails')->where('im_id', $item->im_id)->get(); 
					$purch=DB::table('acc_invenmasters')->where('id', $item->id)->first(); //echo $purch->amount;
					
					$cur=DB::table('acc_currencies')->where('id',$item->currency_id)->first();						
					$cur_name=''; isset($cur) && $cur->id > 0 ? $cur_name=$cur->name : $cur_name='';
					
					?>
                        {{-- */$x++;/* --}}
                        <?php 
							$ttl+=$item->amount;
							$item->amount> 0 ? $item->amounts=number_format($item->amount, 2): $item->amounts='';
							$item->rate> 0 ? $item->rates=number_format($item->rate, 2): $item->rates='';

							$vnumber = DB::table('acc_invenmasters')->max('vnumber')+1; 
							$item->user_id > 0 ? $person=$users[$item->user_id] : $person='';

							$unit_name='';$prod_name='';
							if($item->item_id>0): 
								$prod=DB::table('acc_products')->where('id',$item->item_id)->first();						
								 isset($prod) && $prod->id > 0 ? $prod_name=$prod->name : $prod_name=''; //echo $prod_name.'123';
								 $unit=DB::table('acc_units')->where('id',$prod->unit_id)->first();						
								 isset($unit) && $unit->id > 0 ? $unit_name=$unit->name : $unit_name='';
							endif;
							$for_name=''; //echo $item->for;
							if($item->for>0): 
								$for=DB::table('acc_ittypes')->where('id',$item->for)->first();						
								isset($for) && $for->id > 0 ? $for_name=' , for '.$for->name : $for_name=''; //echo $prod_name.'123';
							endif;
							$rcvd=''; $issue=''; 
							$item->qty> 0 ? $rcvd=$item->qty : $issue=substr($item->qty,1);
							$balance =$balance+$item->qty;
							$ttl += $item->qty;  
							$ttl_rcvd  +=$rcvd; 
							$ttl_issue +=$issue; 
							$ttl_rcvd==0 ? $ttl_rcvd='' : '';
							$ttl_issue==0 ? $ttl_issue='' : '';
							 $note=strlen($item->note)> 0 ? ','.$item->note : '';
							 $note=$prod_name.$note;
							 $balance<0 ? $balances='('.substr($balance,1).')'.' '. $unit_name : $balances=$balance.' '. $unit_name;
							 $rcvd> 0 ? $rcvds=$rcvd.' '. $unit_name : $rcvds='';
							 $issue> 0 ? $issues=$issue.' '.$unit_name : $issues='';
							 
							 $ttl_rcvd !='' ? $ttl_rcvds=$ttl_rcvd .' '. $unit_name : $ttl_rcvds='';
							 $ttl_issue !='' ? $ttl_issues=$ttl_issue .' '. $unit_name : $ttl_issues='';
							 strlen($item->ref)==0 ? $ref='' : $ref=', '.$item->ref; 
							 
							  
							 
							?>
                         <tr>
                        	<td class="text-center">{{ $x }}</td>
                            <td>{{ $idate }}/VNo: {{ $item->vnumber }} </td>
                            <td>{{ $itype }} </td>
                            <td>{{ $note.$for_name.$ref }} </td>
                            <td class=" text-right">{{ $rcvds }}</td>
                            <td class="text-right">{{ $issues }}</td>
                            <td class="text-right">{{ $balances }} </td>
                             </tr>
                @endforeach
                <tr><td colspan="4" class="text-right">Total</td><td class="text-right">{{ $ttl_rcvds }}</td><td class="text-right">{{ $ttl_issues }}</td><td class="text-right">{{ $balances }}</td></tr>

                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Export->Buyer</td><td class="text-right">Report generated by: </td></tr>
                </table>
            </div><!-- /.box-header -->
        </div>
     </div>
</div>
@endsection

@section('custom-scripts')

<script type="text/javascript">
        
    jQuery(document).ready(function($) {        
        $(".trandetail").validate();
		$( "#dfrom" ).datepicker({ dateFormat: "yy-mm-dd" }).val();
        $( "#dto" ).datepicker({ dateFormat: "yy-mm-dd" }).val();
    });
        
</script>

@endsection
