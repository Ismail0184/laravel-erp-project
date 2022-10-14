@extends('app')

@section('htmlheader_title', ' Delivery Challan')

@section('contentheader_title', 	  ' Delivery Challan')
@section('main-content')

<style>
    table.borderless td,table.borderless th, table.borderless tr, table.borderless{
	border:none;
	 padding:10px;
	}
	#col {  min-height:100px; padding-top:50px; width:23%; float:left; padding:20px ; background-color:; margin:5px}
	#inner { border:1px solid #300; min-height:60px; padding:10px; margin:3px}

	#unit {width: 10px} 
	#cur {width: 10px}
	#logo, #td { width:25%}
	#buyerinfo-table tr,#buyerinfo-table td, #buyerinfo-table th { border:1px solid black; }
	.box-body { min-height:400px}
	#in-body { height:350px}
	#foo {width:100%;}

</style>

 <div class="container">
 <div class="box" >
        <div class="box-header">
            <a href="{{ url('invenmaster/challan_print') }}" title="{{ $langs['print'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-print"></i></a>
        </div><!-- /.box-header -->

    <div class="table-responsive">
		<div class="box-body" >

        <table  width="100%" border="1" class="borderless">
        <tr><td id="logo"></td><td>
        <?php 
			Session::has('com_id') ? $com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';

			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; 
			isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			isset($_GET['flag']) && $_GET['flag']>0 ? Session::put('siacc_id',$_GET['flag']) : '';
		
		?>
        	<h2 align="center">{{ $com->name }}</h2>
            <h5 align="center">{{ $com->oaddress }}</h5>
            <h5 align="center">{{ strlen($com->phone) > 0  ? $com->phone : '' }} {{ strlen($com->fax)> 0 ? ', '.  $com->fax : '' }}</h5>
            <h5 align="center">{{ strlen($com->email)> 0 ? $com->email : '' }} {{ strlen($com->web)> 0 ?  ', '. $com->web : ''}}</h5>
        </td><td></td></tr>
        <?php 

			// data collection filter method by session	
			$data=array('acc_id'=>'');
			
			Session::has('siacc_id') ? 
			$data=array('acc_id'=>Session::get('siacc_id')) : ''; 
			$client_name=''; $client_address='';
					
			if (isset($data['acc_id']) && $data['acc_id']>0):
				// for single account
				$sale=DB::table('acc_invenmasters')->where('id', $data['acc_id'])->get();
				$sales=DB::table('acc_invenmasters')->where('id', $data['acc_id'])->first();

				$client = DB::table('acc_clients')->where('com_id',$com_id)->where('id',$sales->client_id)->first();  
				if (isset($client) && $client->id > 0):
					$client_name=$client->name;
					$client_address=$client->address1;
				else:
					$client_name=$sales->client;
					$client_address=$sales->client_address;
				
				endif;
				echo '<tr><td ><h2 class="pull-left">Challan</h2></td><td></td>
				<td class="text-right"  id="td"><h4 aling="right">Challan No: '.$invoices[$data['acc_id']].'/ch</h4><h4>Date: '.$sales->idate.'</h4></td></tr>';
			else:
				// for multiple account
				echo '<tr><td class="text-center" colspan="2"><h4>Invoice</h4><h5 ></h5></td></tr>';
			endif;
		?>
        
        </table>
			
            <table id="buyerinfo-table" class="table">
                <thead>
                 <tr><td colspan="6"><a href="{!! url('/invenmaster/challan?flag=filter') !!}"> Filter  </a>
					<?php 
                    	$flags=''; isset($_GET['flag']) ? $flags=$_GET['flag'] : ''; 
						 !isset($data['acc_id']) ? $data['acc_id']='' : '' ;
                   
				    // to get data by fileter
					?>
                    @if ($flags=='filter')
                           {!! Form::open(['url' => 'invenmaster/chfilter', 'class' => 'form-horizontal']) !!}
            
                            <div class="form-group">
                                {!! Form::label('acc_id', $langs['invoice'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::select('acc_id', $invoices, null, ['class' => 'form-control']) !!}
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

                 <tr><td colspan="4"> Name: {{ $client_name }} </td></tr>
                 <tr><td colspan="4"> Address: {{ $client_address }}</td></tr> 
                    <tr>
                        <th class="col-md-1 text-center">{{ $langs['sl'] }}</th>
                        <th class="col-md-3">Description of Materials</th>
                        <th class="col-md-2 text-right">{{ $langs['qty'] }}</th>
<!--                        <th class="col-md-2 text-right" >{{ $langs['rate'] }}</th>
                        <th class="col-md-2 text-right" >{{ $langs['amount'] }}</th>
-->                    </tr>
                </thead>
                <tbody>
				{{-- */$x=0;/* --}}
                <?php 
						$username='';$checkname=''; $apprname='';
				?>
                @foreach($sale as $item)
                
                 	<?php
					$username=$item->user_id >0 ? $users[$item->user_id] : '';
					$checkname=$item->check_action==1 ? $users[$item->check_id] : 'waiting';
					$apprname=$item->apr_id >0 ? $users[$item->apr_id] : '...';
					//$user=$item->user->name;
					$ttl='';
					$details=DB::table('acc_invendetails')->where('im_id', $item->id)->get(); 
					$purch=DB::table('acc_invenmasters')->where('id', $item->id)->first(); //echo $purch->amount;

					 $currency = DB::table('acc_currencies')->where('id',$item->currency_id)->first();  
					 $currency_name=''; isset($currency) && $currency->id > 0 ? $currency_name=$currency->name : $currency_name='';
					
					?>
                    
                        @foreach($details as $item)
                        {{-- */$x++;/* --}}
                        <?php 
						
							$ttl+=$item->amount;
							$item->amount> 0 ? $item->amounts=number_format($item->amount, 2): $item->amounts='';
							$item->rate> 0 ? $item->rates=number_format($item->rate, 2): $item->rates='';

							$vnumber = DB::table('acc_invenmasters')->max('vnumber')+1; 
							$item->user_id > 0 ? $person=$users[$item->user_id] : $person='';

							$wh=DB::table('acc_warehouses')->where('id',$item->war_id)->first(); //echo $item->war_id;						
							$wh_name=''; isset($wh) && $wh->id > 0 ? $wh_name='/'.$wh->name : $wh_name=''; //echo $wh_name;

							$products = DB::table('acc_products')->where('id',$item->item_id)->first(); 
							$product_name=''; isset($products) && $products->id > 0 ? $product_name=$products->name : $product_name='';
                                    $unit_id=''; isset($products) && $products->id > 0 ? $unit_id=$products->unit_id : $unit_id='';
                                    $units = DB::table('acc_units')->where('id',$unit_id)->first(); 
                                    $unit_name=''; isset($units) && $units->id > 0 ? $unit_name=$units->name : $unit_name='';
									$item->qty < 0 ? $item->qty=substr($item->qty,1) : '';
						?>
                         <tr id="in-body">
                        	<td class="text-center">{{ $x }}</td>
                            <td>{{ $product_name }} {{ $wh_name }}</td>
                            <td class=" text-right">{{ $item->qty }} {{ $unit_name }}</td>
<!--                            <td class=" text-right">{{ $item->rates}} {{ $currency_name }}</td>
                            <td class=" text-right">{{ $item->amounts }} {{ $currency_name }}</td>
-->                          </tr>
                        @endforeach  
                       
                        <?php 
							$ttl> 0 ? $ttls=number_format($ttl, 2) : $ttls=''; 
							// check entry 
							$check_entry = DB::table('acc_invenmasters')->where('pm_id', $data['acc_id'] )->first();
							isset($check_entry) && $check_entry->pm_id>0 ? $entry_has='yes' : $entry_has='no' ; //echo $entry_has;
						?> 
                        <tr><td colspan="2" class=" text-right">Total</td><td class=" text-right">{{ $ttls }} {{ $currency_name }}</td></tr> 
<!--                        <tr><td colspan="4" class=" text-left">Inwords : </td></tr> 
-->
                @endforeach
				
                </tbody>
            </table>
            </div>
         	<div class="foo" style="padding:5px">
                <div class="text-center" id="col">{{ $username }}
                    <div id="inner">Delivered By</div>
                </div>
            	<div class="text-center" id="col">{{ $checkname }}
            		<div id="inner">Checked By</div>
                </div>
            	<div class="text-center" id="col">{{ $apprname }}
            		<div id="inner">Approved By</div>
                </div>
            	<div class="text-center" id="col">...
            		<div id="inner">Received By</div>
                </div>
            </div>     
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
