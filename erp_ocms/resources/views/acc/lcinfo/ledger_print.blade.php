@extends('print')

@section('htmlheader_title', $langs['edit'] . ' Trandetail')

@section('contentheader_title', 	  ' Ledger')
@section('main-content')

<style>
    table.borderless td,table.borderless th{
     border: none !important; margin:0px; padding:0px
	}

	h1{
		font-size: 1.6em;
	}
	h5{
		font-size: 1.2em; margin:0px; font-weight:bold; margin:opx; padding:0px;
	}
	#cur {width: 10px}
	body { padding:0px}
	#opn { margin:opx; padding:0px;}
	#dt { width:15%;} 
	#nt { width:30%; } 
</style>

        <table  width="100%>
        <?php 
			$user_name=''; Session::has('user_name') ? $user_name=Session::get('user_name') : $user_name='';

			$months=array(''=>'Select ...', 1=>'January', 2=>'February', 3=>'March', 4=>'April', 5=>'May', 6=>'June', 7=>'July', 8=>'August', 9=>'September', 10=>'October', 11=>'November', 12=>'December');
			Session::has('com_id') ? 
			$com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h1 align="center">'.$com_name.'</h1></td></tr>';

			// data collection filter method by session	
			$data=array('acc_id'=>'','lc_id'=>'');

			Session::has('lclc_id') ? 
			$data=array('acc_id'=>Session::get('lcacc_id'),'lc_id'=>Session::get('lclc_id')) : ''; 
			
			$lc=DB::table('acc_lcinfos')->where('id',$data['lc_id'])->first();
			$lc_number=''; isset($lc) && $lc->id >0 ? $lc_number=$lc->lcnumber : $lc_number='';
			
		
			if (isset($data['acc_id']) && $data['acc_id']>0):
				// for single account
				$coa=DB::table('acc_coas')->where('id',$data['acc_id'])->first();
				$coa_name=''; ($coa) && $coa->id>0 ? $coa_name=$coa->name : $coa_name='';
				echo '<tr><td ><h3 class="pull-left">General Ledger</h3></td>
				<td class="text-right" ><h3 aling="right">'.$coa_name.'</h3><h5 >LC  Number: '.$lc_number.'</h5></td></tr>';
			else:
				// for multiple account
				echo '<tr><td class="text-center" colspan="2"><h5>General Ledger</h5><h5 ></h5></td></tr>';
			endif;
		
		?>
        
        </table>

            <table id="buyerinfo-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="col-md-2" id="dt">{{ $langs['tdate'] }}</th>
                        <th class="col-md-4" id="nt">{{ $langs['note'] }}</th>
                        <th class="col-md-2 text-right" colspan="2">{{ $langs['debit'] }}</th>
                        <th class="col-md-2 text-right" colspan="2">{{ $langs['credit'] }}</th>
                        <th class="col-md-2 text-right" colspan="2">{{ $langs['balance'] }}</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
						//  filter wise acc_id based data collection 
						if (isset($data['acc_id']) && $data['acc_id'] > 0 ):
							$tran=DB::table('acc_coas')->where('com_id', $com_id)->where('id', $data['acc_id'])
							->groupBy('acc_coas.id')->get();
						else:
							$tran=DB::table('acc_coas')->where('acc_coas.com_id', $com_id)->select('acc_coas.id as id')
							->join('acc_trandetails', 'acc_coas.id', '=','acc_trandetails.acc_id')
							->where('acc_trandetails.lc_id', $data['lc_id'])->groupBy('acc_coas.id')->get();
						endif;
						$cur='Tk'; $debit=0; $credit=0; $balance=0; $ttl=0; //$balances='';
						
				?>
                  @foreach($tran as $item)
                  			<?php 
							
								$acchead= $data['acc_id']=='' ? "<h4>".$acccoa[$item->id]."</h4>" : '';
								// opening balance calculation
								$d_opbs=''; $c_opbs=''; $d_opb=''; $c_opb=''; $obcur=''; $opbs='';

								$opb=$tran=DB::table('acc_trandetails')
								->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
								->where('acc_trandetails.acc_id', $item->id)
								->where('acc_trandetails.com_id', $com_id)
								->sum('acc_trandetails.amount');

								$acchead= $data['acc_id']=='' ? "<h4>".$acccoa[$item->id]."</h4>" : '';
								?>
                                    <tr>
                                        <td colspan="2"><?php echo $acchead ?></td>
                                        <td id="cur"></td><td class=" text-right"></td>
                                        <td id="cur"></td><td class=" text-right"></td>
                                        <td id="cur"></td><td class=" text-right"></td>
                                    </tr>
								<?php
								
							// account-wise data 
							$details=DB::table('acc_trandetails')->where('acc_id', $item->id)->orderBy('acc_trandetails.id')->get() ;
							if (isset($data['lc_id'])):
								// account and date-wise data
								$details=DB::table('acc_trandetails')
								->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
								->where('acc_trandetails.acc_id', $item->id)
								->where('acc_trandetails.lc_id', $data['lc_id'])
								->orderBy('acc_tranmasters.id')
								->get();
							endif;
							 
							$balances='';$balance=0; $ttl_debit=''; $ttl_credit=''; $ttl_balances=''; $tdcur=''; $tccur='';
							//$opb!='' ? $balance=$opb : '';  $tdcur=''; $tccur='';
							//$opb> 0 ? $ttl_debit=$opb :  $ttl_credit=$opb;
							?>
                        
                        @foreach($details as $item)
                        <?php 
							$subhead=DB::table('acc_subheads')->where('id',$item->sh_id)->first();
							$item->sh_id>0 ? $subhead=$subhead->name.', ' : '';
							
							$ilc=DB::table('acc_lcimports')->where('id',$item->ilc_id)->first();
							isset($ilc) && $item->id>0 ? $ilc=' ,'.$ilc->lcnumber : $ilc='';

							$debit=''; $credit=''; $bc=''; $dcur=''; $ccur=''; 
							// to tal calculation
							$ttl+=$item->amount; $balance += $item->amount;
							// make different debit and credit
							$item->amount>0 ? $debit=number_format($item->amount, 2) :  '' ; 
							$item->amount<0 ? $credit= substr(number_format($item->amount, 2),1) : '' ;
							
							$debit!='' ? $dcur=$cur : $dcur=''; $credit!='' ? $ccur=$cur : $ccur='';
							// make total of debit and credit and thier currency
							$item->amount>0 ? $ttl_debit += $item->amount :  $ttl_credit += $item->amount ; 
							$ttl_debit!='' ? $tdcur=$cur : $tdcur=''; $ttl_credit!='' ? $tccur=$cur : $tccur='';
 							// to get tranmaster data	
							$master	   = DB::table('acc_tranmasters')->where('id', $item->tm_id)->first(); //echo $master->tdate;
							// to make sign Dr or Cr behind balance 
							$balance<0 ? $balances=substr(number_format($balance,2), 1).' '.$bc='Cr' : $balances= number_format($balance,2).' '. $bc='Dr';
							
							// create note
							$item->tranwiths_id>0 ?
							$coa = DB::table('acc_coas')->where('com_id',$com_id)->where('id',$item->tranwiths_id)->first() : ''; 
							isset($coa) && $coa->id>0  ? $acc_head=$coa->name : $acc_head='' ; 							

							$subhead='';
							$item->sh_id>0 ? 
							$subhead = DB::table('acc_subheads')->where('com_id',$com_id)->where('id',$item->sh_id)->first(): '';
							$subhead=='' ? '' : $acc_head = $acc_head.', '.$subhead->name; 
	
							$dep='';
							$item->dep_id>0 ? 
							$dep = DB::table('acc_departments')->where('com_id',$com_id)->where('id',$item->dep_id)->first(): '';
							$dep=='' ? '' : $acc_head = $acc_head.', Department of '.$dep->name; 
	
							$item->c_number!='' ? $acc_head = $acc_head.', Checque No:'.$item->c_number : ''; 
							$item->b_name!='' ? $acc_head = $acc_head.', Branch Name: '.$item->b_name : ''; 
							$item->c_date!='0000-00-00' ? $acc_head = $acc_head.', Checque Date: '.$item->c_date : '';	
							$master->note!='' ? $acc_head = $acc_head.', '.$master->note : '';	
							$master->person!='' ? $acc_head = $acc_head.', '.$master->person : '';		
	
							$item->m_id>0 ? $m_name=$months[$item->m_id] : $m_name='';
							$m_name!='' ? $acc_head = $acc_head.', Period for '.$m_name.'-'. $item->year : '';
							$master->person!='' ? $person=', ' .$master->person  : $person=''; //echo $ttl_credit;
							
						?>
                         <tr>
                            <td>{{ $master->tdate }} / vn-{{ $master->vnumber }}</td>
                            <td>{{ $acc_head }} </td>
                            <td id="cur">{{ $dcur }}</td><td class=" text-right">{{ $debit }}</td>
                            <td id="cur">{{ $ccur }}</td><td class=" text-right">{{ $credit }}</td>
                            <td id="cur">{{ $cur }}</td><td class=" text-right">{{ $balances }}</td>
                             </tr>
                        @endforeach  
                        <?php 
							$ttl_debit>0 ? $ttl_debit = number_format($ttl_debit, 2) : ''; 
							$ttl_credit<0 ? $ttl_credit=substr(number_format($ttl_credit,2),1) : ''; 
						?>
                        <tr>
                        	<td colspan="2" class="text-right">Total</td>
                        	<td id="cur">{{ $tdcur }}</td><td class=" text-right">{{ $ttl_debit }}</td>
                            <td id="cur">{{ $tccur }}</td><td class=" text-right">{{ $ttl_credit }}</td>
                            <td id="cur"></td><td class=" text-right">{{ $ttl_balances }}</td>
                        </tr> 

                   @endforeach 
                   		
                        <!--<tr><td colspan="6" class=" text-right">Total</td><td id="cur">{{ $cur }}<td class=" text-right">{{ $ttl }}</td></tr> -->

                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Transaction->Ledger</td><td class="text-right">Report generated by: {{ $user_name }}</td></tr>
                </table>
            </div><!-- /.box-header -->
