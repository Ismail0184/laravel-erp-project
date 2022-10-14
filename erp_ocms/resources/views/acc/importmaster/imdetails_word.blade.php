@extends('word')

@section('htmlheader_title', 'Purchasemaster')

@section('contentheader_title', 'Import')

@section('main-content')
<style>
	#field{ width:150px; text-align:right}
	#unit { width:30px}  
	#rate { width:30px}  
	#amt  { width:30px}
</style>
<div class="box">
        <div class="box-header">
        <table class="table borderless">
        <?php 
			Session::has('com_id') ? 
			$com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h2 align="center">'.$com_name.'</h2></td></tr>';
		?>
        <tr><td class="text-center"><h4>Import LC Information</h4></td></tr>
        </table>
        </div><!-- /.box-header -->
        <div class="box-body" align="center">
                <div class="table-responsive" >
                	<?php 
						Session::has('im_id') ? 
						$im_id=Session::get('im_id') : $im_id='' ;// echo $com_id.'osama';
						
							$lcimport_value='' ; $currency_name='';
							$importmaster=DB::table('acc_importmasters')->where('id',$im_id)->first();
							if (isset($importmaster) && $importmaster->id > 0):
								$lcimport = DB::table('acc_lcimports')->where('id',$importmaster->lcimport_id)->first();  
								 isset($lcimport) && $lcimport->id > 0 ? $lcimport_value=$lcimport->lcvalue : $lcimport_value='';
								 
								 $currency = DB::table('acc_currencies')->where('id',$importmaster->currency_id)->first();  
								 isset($currency) && $currency->id > 0 ? $currency_name=$currency->name : $currency_name='';
								$currencys=array('1'=>'USD', '2'=>'EURO');
								$currency=''; //$currencys[$lcimport->currency_id];
							endif;
					?>
                    
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th id="field">{{ $langs['idate'] }}</th>
                            <td>{{ $importmaster->idate }}</td>
                             <th id="field">{{ $langs['lcimport_id'] }}</th>
                            <td>{{ $lcimport->lcnumber }}</td>
                        </tr>
                        <tr>
                            <th id="field">{{ $langs['invoice'] }}</th>
                            <td>{{ $importmaster->invoice }}</td>
                             <th id="field">{{ $langs['lcvalue'] }}</th>
                            <td>{{ $lcimport_value .'('.$currency_name.')' }}</td>
                        </tr>
                        
                    </table>
                    
   			 <table class="table table-bordered">   
             	<tr>
                	<th class="text-center">SL</th>
                    <th>Product</th>
                    <th class="text-right" colspan="2">Quantity</th>
                    <th class="text-right" colspan="2">Rate</th>
                    <th class="text-right" colspan="2">Amount</th>
                    
                    <th class="text-right"></th>
                    
                </tr>             
                  <?php   

				  		$x=0; $ttl=0;
				  		$record = DB::table('acc_importdetails')->where('im_id', $im_id)->get(); 
						
						foreach($record as $item):
						$x++; $ttl+=$item->amount;
						$product = DB::table('acc_products')->where('id',$item->item_id)->first(); 
						$product_name=''; isset($product) && $product->id > 0 ? $product_name=$product->name : $product_name=''; 
							$unit=''; //$units[$item->unit_id]; 
							echo "<tr><td class='text-center'>$x</td>
							<td>$product_name</td>
							<td class='text-right'>$item->qty</td><td id='unit'>$unit</td>
							<td class='text-right'>".number_format($item->rate)."</td><td id='rate'>$currency_name</td>
							<td class='text-right'>".number_format($item->amount)."</td><td id='amt'>$currency_name</td><td width='150px'>
							</td></tr>"; 
						endforeach;
						echo "<tr><td colspan='6' class='text-right'>Total</td><td class='text-right'>".number_format($ttl)."</td><td id='amt'>$currency_name</td><td width='150px'></td></tr>";

				  ?>
                    
  			 </table>                 
                    <div class="box-header">
                        <table class="table borderless">
                        <tr><td class="text-left">Source: Import->Supplier</td><td class="text-right">Report generated by: {{ $item->user_id }}</td></tr>
                        </table>
                    </div><!-- /.box-header -->
                  </div>
                </div>
		</div>
</div>


@endsection
