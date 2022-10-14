@extends('app')

@section('htmlheader_title', 'Purchasemaster')

@section('contentheader_title', 'Purchase')

@section('main-content')
<style>
	#field{ width:150px; text-align:right}
	#unit, #currency { width:30px}
	#sl { width:150px;}
</style>
<?php 
		$clients = DB::table('acc_clients')->where('id',$purchasemaster->client_id)->first(); 
		$client_name=''; isset($clients) && $clients->id > 0 ? $client_name=$clients->name : $client_name='';
?>
<div class="container">
<div class="box">
        <div class="box-header">
            <a href="{{ url('purchasemaster/print') }}" title="{{ $langs['print'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-print"></i></a>
            <a href="{{ url('purchasemaster/pdf') }}" title="{{ $langs['download'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-download"></i></a>
            <a href="{{ url('purchasemaster/pdf') }}" title="{{ $langs['pdf'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-pdf-o"></i></a>
            <a href="{{ url('purchasemaster/excel') }}" title="{{ $langs['excel'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-excel-o"></i></a>
            <a href="{{ url('purchasemaster/csv') }}" title="{{ $langs['csv'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-excel-o"></i></a>
            <a href="{{ url('purchasemaster/word') }}" title="{{ $langs['word'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-word-o"></i></a>

        <table class="table borderless">
        <?php 
			Session::has('com_id') ? 
			$com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h2 align="center">'.$com_name.'</h2></td></tr>'; 
		?>
        <tr><td class="text-center"><h4>Purchase Details</h4></td></tr>
        </table>
        </div><!-- /.box-header -->
        <div class="box-body" align="center">
                <div class="table-responsive" >
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th id="field">{{ $langs['pdate'] }}</th>
                            <td>{{ $purchasemaster->pdate }}</td>
                            <th id="field">{{ $langs['client_id'] }}</th>
                            <td>{{ $client_name }}</td>
                        </tr>
                        <tr>
                            <th id="field">{{ $langs['invoice'] }}</th>
                            <td>{{ $purchasemaster->invoice }}</td>
                            <th id="field">{{ $langs['client_address'] }}</th>
                            <td>{{ $purchasemaster->client_address }}</td>
                        </tr>
                    </table>
                    
   			 <table class="table table-bordered">   
             	<tr>
                	<th class="text-center" id="sl">SL</th>
                    <th>Product</th>
                    <th class="text-right" colspan="2">Quantity</th>
                    <th class="text-right" colspan="2">Rate</th>
                    <th class="text-right" colspan="2">Amount</th>
                    
                </tr>             
                  <?php
						 $currency = DB::table('acc_currencies')->where('id',$purchasemaster->currency_id)->first();  
						 $currency_name=''; isset($currency) && $currency->id > 0 ? $currency_name=$currency->name : $currency_name='';
					
				  		$x=0; $ttl=0; $currency='';
				  		$record = DB::table('acc_purchasedetails')->where('pm_id', $purchasemaster->id)->get(); 
						
						foreach($record as $item):
							 
							$x++; $ttl+=$item->amount;

							$products = DB::table('acc_products')->where('id',$item->item_id)->first(); 
							$product=''; isset($products) && $products->id > 0 ? $product=$products->name : $product='';

							$units = DB::table('acc_units')->where('id',$item->unit_id)->first(); 
							$unit=''; isset($units) && $units->id > 0 ? $unit=$units->name : $unit='';
							
							echo "<tr><td class='text-center'>$x</td><td>$product</td>
							<td class='text-right'>$item->qty</td><td id='unit'>".$unit."</td>
							<td class='text-right'>".number_format($item->rate)."</td><td id='currency'>".$currency_name."</td>
							<td class='text-right'>".number_format($item->amount)."</td><td id='currency'>".$currency_name."</td></tr>";  
							
						endforeach;
						$ttl!='' ? $ttls=number_format($ttl) : '';
						echo "<tr><td colspan='6' class='text-right'>Total</td><td class='text-right'>".$ttls."</td>
						<td id='currency'>".$currency."</td></tr>"; 
						
				  ?>
                    
  			 </table>                 
                    <div class="box-header">
                        <table class="table borderless">
                        <tr><td class="text-left">Source: Purchase->Purchase details</td><td class="text-right">Report generated by: {{ $item->user_id }}</td></tr>
                        </table>
                    </div><!-- /.box-header -->

                  </div>
                 
                </div>
		</div>
</div>


@endsection
