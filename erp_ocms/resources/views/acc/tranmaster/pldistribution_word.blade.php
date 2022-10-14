@extends('word')

@section('htmlheader_title', 'Trandetail')

@section('contentheader_title', 'Profit and Loss Distribution Account')

<style>
    table.borderless td,table.borderless th{
     border: none !important; margin:0px; padding:0px
	}

	h1{
		font-size: 1.6em;
	}
	h5{
		font-size: 2em; margin:0px; font-weight:bold; margin:opx; padding:0px;
	}
	#cur {width: 10px}
	.container { width:auto;}
	body { padding:0px}
	#opn { margin:opx; padding:0px;}
	#dt { width:15%;} 
	#nt { width:30%; } 
	#cls { font-size:16px; font-weight:bold;}
	#top { border-top: thick double black;}
	#grp { font-size:14px; font-weight:bold;}
</style>

@section('main-content')
 <div class="container">
 <div class="box" >
    <div class="table-responsive">
    <div class="table-responsive">
        <table  width="100%>
        <?php 
			Session::has('com_id') ? 
			$com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h1 align="center">'.$com_name.'</h1></td></tr>';

			$data=array('acc_id'=>'','dfrom'=>'0000-00-00','dto'=>'0000-00-00');
			Session::has('pldto') ? 
			$data=array('acc_id'=>Session::get('placc_id'),'dfrom'=>Session::get('pldfrom'),'dto'=>Session::get('pldto')) : ''; 

			if (isset($data['acc_id']) && $data['acc_id']>0):
				
			// for single account
			echo '<tr><td ><h3 class="pull-left">Group Account</h3></td>
				<td class="text-right" ><h3 aling="right">'.$acccoa[$data['acc_id']].'</h3><h5 >'.$data['dto'].'</h5></td></tr>';
			else:
				// for multiple account
				echo '<tr><td class="text-center" colspan="2"><h3>Profit and Loss Distribution Account</h3><h5 >'.$data['dfrom'].' to '.$data['dto'].'</h5></td></tr>';
			endif;

		?>
		</table>
            <table id="buyerinfo-table" class="table table-bordered ">
                <thead>
                <tr><td colspan="8"><a href="{!! url('/tranmaster/pldistribution?flag=filter') !!}"> Filter  </a>
                <?php 
                    	$flags=''; isset($_GET['flag']) ? $flags=$_GET['flag'] : ''; 
						 !isset($data['acc_id']) ? $data['acc_id']='' : '' ;
				    // to get data by fileter
					?>
                    @if ($flags=='filter')
                           {!! Form::open(['url' => 'tranmaster/plfilter', 'class' => 'form-horizontal']) !!}
            
                            <div class="form-group">
                                {!! Form::label('acc_id', $langs['acc_id'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::select('acc_id', $acccoa, null, ['class' => 'form-control']) !!}
                                </div>    
                            </div>
                            <div class="form-group">
                                {!! Form::label('dfrom', $langs['dfrom'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::text('dfrom',  null, ['class' => 'form-control', 'id'=>'dfrom', 'required']) !!}
                                </div>    
                            </div>
                            <div class="form-group">
                                {!! Form::label('dto', $langs['dto'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::text('dto',  date('Y-m-d'), ['class' => 'form-control', 'id'=>'dto', 'required']) !!}
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
                        <th class="col-md-8" id="nt">{{ $langs['acc_id'] }}</th>
                        <th class="col-md-2 text-right" colspan="2"></th>
                        <th class="col-md-2 text-right" colspan="2">{{ $langs['amount'] }}</th>
                    </tr>
                </thead>
                <tbody>
				<?php 
                	$cur='Tk'; $debit=0; $credit=0; $balance=0;  //$balances='';
					$balances='';$balance=0; $ttl_debit=''; $ttl_credit=''; $ttl_balances='';
					$tdcur=''; $tccur=''; $ttl_debit=''; $ttl_credit=''; $ttl_balances='';   $ttl_debits=''; $ttl_credits='';  
					$atype='';  $asset=''; $liabi='';       
					?>
                   <?php 
				   // to create group account
				   isset($data['acc_id']) && $data['acc_id']>0 ?
				   $tran=DB::table('acc_coas')->where('id', $data['acc_id'])->groupBy('id')->get() : '';
				   ?> 
                  @foreach($tran as $item)
                  	<?php
						// find child account has or not?
						$flg='';
							$seek=DB::table('acc_coas')->where('group_id',$item->id)->get(); //echo $item->name.'<br>';
							foreach( $seek as $value):
									$acc_seek=$value->id; //echo $value->name.'<br>'; 
									
									$find=DB::table('acc_trandetails')
									->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
									->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
									->where('tdate','<=',$data['dto'])
									->where('acc_coas.id', $acc_seek)
									->first();
									isset($find->acc_id) && $find->acc_id >0 ? $flg='ok' : '';
										$seek2=DB::table('acc_coas')->where('group_id',$value->id)->get(); 
										foreach( $seek2 as $value):
												$acc_seek=$value->id; //echo $value->name.'<br>'; 
												$find=DB::table('acc_trandetails')
												->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
												->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
												->where('tdate','<=',$data['dto'])
												->where('acc_coas.id', $acc_seek)
												->first();
													isset($find->acc_id) && $find->acc_id >0 ? $flg='ok' : '';
													$seek3=DB::table('acc_coas')->where('group_id',$value->id)->get(); 
													foreach( $seek3 as $value):
															$acc_seek=$value->id; //echo $value->name.'<br>'; 
															$find=DB::table('acc_trandetails')
															->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
															->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
															->where('tdate','<=',$data['dto'])
															->where('acc_coas.id', $acc_seek)
															->first();
															isset($find->acc_id) >0  && $find->acc_id ? $flg='ok' : '';
													endforeach;
										endforeach;
							endforeach;
							 
						isset($find->acc_id) ? $flag=1 : $flag=0; //echo $flag;
								$pl=DB::table('acc_coas')->where('com_id',$com_id)->where('name','Profit and Loss Account')->first();
								$pl_id=''; isset($pl) && $pl->id > 0 ? $pl_id=$pl->id : $pl_id='';
								$man=DB::table('acc_coas')->where('com_id',$com_id)->where('name','Manufacturing Account')->first();
								$man_id=''; isset($man) && $man->id > 0 ? $man_id=$man->id : $man_id='';
								$tra=DB::table('acc_coas')->where('com_id',$com_id)->where('name','Trading Account')->first();
								$tra_id=''; isset($tra) && $tra->id > 0 ? $tra_id=$tra->id : $tra_id='';

								$pls=DB::table('acc_trandetails')
								->join('acc_tranmasters', 'acc_trandetails.tm_id','=', 'acc_tranmasters.id')
								->join('acc_coas', 'acc_trandetails.acc_id','=', 'acc_coas.id')
								->where('tdate','<=',$data['dto'])
								->whereIn('topGroup_id',[$pl_id, $man_id, $tra_id])
								->sum('acc_trandetails.amount') ; 
								$pls> 0 ? $pl_text="Loss" : $pl_text="Profit";
								$pls> 0 ? $pl=number_format($pls, 2) : $pl='('.substr(number_format($pls, 2), 1).')';
								$pls!='' ? $bcur=$cur : $bcur=''; 
					?>
                    	<!-- Group Head-->
                 		<tr>
                            <td id='grp'>{{  $pl_text }} Transfered from Profit and Loss Account</td>
                            <td id="cur">{{ $bcur }}</td><td class=" text-right">{{ $pl }}</td>
                            <td id="cur"></td><td class=" text-right"></td>

                          </tr>
                                   <!--------------first------------------>
						<?php 

						// find transaction of find group
						if (isset($item->atype) && $item->atype=='Account'): 
							$details=DB::table('acc_trandetails')->where('acc_id', $item->id)->groupBy('acc_id')->get() ;
						elseif (isset($item->atype) && $item->atype=='Group'):
							$details=DB::table('acc_coas')->where('group_id', $item->id)->groupBy('id')->get() ;
						endif;
						
						// to calculate sum of fixed asset group
						
						?>
                        @foreach($details as $item)
                        <?php 
							if (isset($item->atype) && $item->atype=='Account'):
							
								// find account-wise transaction sum
								$sum=DB::table('acc_trandetails')
								->join('acc_tranmasters', 'acc_trandetails.tm_id','=', 'acc_tranmasters.id')
								->where('tdate','<=',$data['dto'])
								->where('acc_trandetails.acc_id', $item->id)
								->groupBy('acc_trandetails.acc_id')
								->sum('acc_trandetails.amount') ; 
								
								$item->amount=$sum; 
								$debit=''; $credit=''; $bc=''; $dcur=''; $ccur=''; 
								// to tal calculation
								$ttl+=$item->amount; $balance += $item->amount; //echo $ttl;
								
								// to create amount and formate
								$item->amount <0  ? $debits= substr($item->amount,1) :  $debits= $item->amount; 
								$debit=number_format($debits, 2); 
								
								$debit!='' ? $dcur=$cur : $dcur=''; $credit!='' ? $ccur=$cur : $ccur='';
								// make total of debit and credit and thier currency
								$item->amount>0 ? $ttl_debit += $item->amount :  $ttl_credit += $item->amount ; //echo $ttl_credit;
								$ttl_debit!='' ? $tdcur=$cur : $tdcur=''; $ttl_credit!='' ? $tccur=$cur : $tccur='';
								
								// find transaction 
								$find=DB::table('acc_trandetails')
								->join('acc_tranmasters', 'acc_trandetails.tm_id','=', 'acc_tranmasters.id')
								->where('tdate','<=',$data['dto'])																		
								->where('acc_trandetails.acc_id', $item->id)
								->first(); 
								isset($find->acc_id) ? $flag=1 : $flag=0; //echo $flag;
								
								if ($flag==1):
							?>
                                  <tr>
                                    <td style="padding-left:30px">{{ $item->name }} </td>
                                    <td id="cur">{{ $dcur }}</td><td class=" text-right">{{ $debit }}</td>
                                    <td id="cur">{{ $ccur }}</td><td class=" text-right">{{ $credit }}</td>
        
                                  </tr>
							
							<?php 
								endif;
							
							endif;
							// find child account has or not?
							$flg=''; $ttl=''; $ttls=''; $dcur='';
							$seek=DB::table('acc_coas')->where('group_id',$item->id)->get(); //echo $item->sl.'<br>';
							$sl=$item->sl;
							foreach( $seek as $value):
									$acc_seek=$value->id; //echo $value->name.'<br>'; 
									
									$find=DB::table('acc_trandetails')
									->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
									->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
									->where('tdate','<=',$data['dto'])
									->where('acc_coas.id', $acc_seek)
									->first();
									isset($find->acc_id) && $find->acc_id >0 ? $flg='ok' : '';
										$seek2=DB::table('acc_coas')->where('group_id',$value->id)->get(); 
										foreach( $seek2 as $value):
												$acc_seek=$value->id; //echo $value->name.'<br>'; 
												$find=DB::table('acc_trandetails')
												->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
												->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
												->where('tdate','<=',$data['dto'])
												->where('acc_coas.id', $acc_seek)
												->first();
													isset($find->acc_id) && $find->acc_id >0 ? $flg='ok' : '';
													$seek3=DB::table('acc_coas')->where('group_id',$value->id)->get(); 
													foreach( $seek3 as $value):
															$acc_seek=$value->id; //echo $value->name.'<br>'; 
															$find=DB::table('acc_trandetails')
															->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
															->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
															->where('tdate','<=',$data['dto'])
															->where('acc_coas.id', $acc_seek)
															->first();
															isset($find->acc_id) && $find->acc_id>0 ? $flg='ok' : '';
													endforeach;
										endforeach;
							endforeach;
							 
							isset($find->acc_id) ? $flag=1 : $flag=0; //echo $flag;
							
							
							if (isset($item->atype) && $item->atype=='Group' && $flg=='ok'):
						?>
                        <tr>
                            <td style="padding-left:30px" id='grp'>{{ $item->name }}</td>
                            <td id="cur"></td><td class=" text-right"></td>
                            <td id="cur"></td><td class=" text-right"></td>

                          </tr>
                                   <!---------------second----------------->
                                    <?php 
							endif;		
										// find transaction of find group
										if (isset($item->atype) && $item->atype=='Account'): 
											$records=DB::table('acc_trandetails')->where('acc_id', $item->id)->groupBy('acc_id')->get() ;
										elseif (isset($item->atype) && $item->atype=='Group'): 
											$records=DB::table('acc_coas')->where('group_id', $item->id)->groupBy('id')->get() ;
										endif;                                    ?>
                                    @foreach($records as $item)
                                    <?php
									
									if (isset($item->atype) && $item->atype=='Account'):
									
										// find account-wise transaction sum
										$sum=DB::table('acc_trandetails')
										->join('acc_tranmasters', 'acc_trandetails.tm_id','=', 'acc_tranmasters.id')
										->where('tdate','<=',$data['dto'])
										->where('acc_trandetails.acc_id', $item->id)
										->groupBy('acc_trandetails.acc_id')
										->sum('acc_trandetails.amount') ; 
								
                                        $item->amount=$sum; 
                                        $debit=''; $credit=''; $bc=''; $dcur=''; $ccur=''; 
                                        // to tal calculation
                                        $ttl+=$item->amount; $balance += $item->amount;
                                        $sl<4 ? $asset +=$item->amount : $liabi += $item->amount ; //echo $asset.' -1<br>';
                                        
										// to create amount and formate
										$item->amount <0  ? $debits= substr($item->amount,1) :  $debits= $item->amount; 
										$debit=number_format($debits, 2); 
                                        
                                        $debit!='' ? $dcur=$cur : $dcur=''; $credit!='' ? $ccur=$cur : $ccur='';
                                        // make total of debit and credit and thier currency
                                        $item->amount>0 ? $ttl_debit += $item->amount :  $ttl_credit += $item->amount ; //echo $ttl_credit;
                                        $ttl_debit!='' ? $tdcur=$cur : $tdcur=''; $ttl_credit!='' ? $tccur=$cur : $tccur='';
										// find transaction 
										$find=DB::table('acc_trandetails')
										->join('acc_tranmasters', 'acc_trandetails.tm_id','=', 'acc_tranmasters.id')
										->where('tdate','<=',$data['dto'])																		
										->where('acc_trandetails.acc_id', $item->id)
										->first(); 
										isset($find->acc_id) ? $flag=1 : $flag=0; //echo $flag;
										
										if ($flag==1):
                                    ?>
                                          <tr>
                                            <td style="padding-left:60px">{{ $item->name }}</td>
                                            <td id="cur">{{ $dcur }}</td><td class=" text-right">{{ $debit }}</td>
                                            <td id="cur">{{ $ccur }}</td><td class=" text-right">{{ $credit }}</td>
                
                                          </tr>
                                      <?php
									  	endif;
									  endif;
									
									  // find child account has or not?
										$flg='';
										$seek=DB::table('acc_coas')->where('group_id',$item->id)->get(); //echo $item->id.'<br>';
										foreach( $seek as $value):
												$acc_seek=$value->id; //echo $value->group_id.'<br>'; 
												
												$find=DB::table('acc_trandetails')
												->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
												->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
												->where('tdate','<=',$data['dto'])
												->where('acc_coas.id', $acc_seek)
												->first(); //echo $find->acc_id;
												//if (isset($find->acc_id)): echo $find->acc_id; endif;
												isset($find->acc_id) && $find->acc_id >0 ? $flg='ok' : ''; //echo $flg;
													if ($flg==''):
													$seek2=DB::table('acc_coas')->where('group_id',$value->id)->get(); 
													foreach( $seek2 as $value):
															$acc_seek=$value->id; //echo $value->name.'<br>'; 
															$find=DB::table('acc_trandetails')
															->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
															->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
															->where('tdate','<=',$data['dto'])
															->where('acc_coas.id', $acc_seek)
															->first();
																if ($flg==''):
																isset($find->acc_id) && $find->acc_id >0 ? $flg='ok' : ''; //echo $flg.'0sama';
																$seek3=DB::table('acc_coas')->where('group_id',$value->id)->get(); 
																foreach( $seek3 as $value):
																		$acc_seek=$value->id; //echo $value->name.'<br>'; 
																		$find=DB::table('acc_trandetails')
																		->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
																		->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
																		->where('tdate','<=',$data['dto'])
																		->where('acc_coas.id', $acc_seek)
																		->first();
																		isset($find->acc_id) && $find->acc_id >0 ? $flg='ok' : ''; //echo $flg;
																endforeach;
																endif;
													endforeach;
													endif;
										endforeach;
										if (isset($item->atype) && $item->atype=='Group' && $flg=='ok'):
									  ?>
                                      <tr>
                                        <td style="padding-left:60px">{{ $item->name }}</td>
                                        <td id="cur"></td><td class=" text-right"></td>
                                        <td id="cur"></td><td class=" text-right"></td>
            
                                      </tr>
               										<!----------------third---------------->
                                                    <?php 
									endif;
													if (isset($item->atype) && $item->atype=='Account'):
														$recordx=DB::table('acc_trandetails')->where('acc_id', $item->id)->groupBy('acc_id')->get() ;
													elseif (isset($item->atype) && $item->atype=='Group'):
														$recordx=DB::table('acc_coas')->where('group_id', $item->id)->groupBy('id')->get() ;
													endif;                                                     
													?>
                                                    @foreach($recordx as $item)
                                                    <?php 
													if (isset($item->atype) && $item->atype=='Account'):
														
														// find account-wise transaction sum
														$sum=DB::table('acc_trandetails')
														->join('acc_tranmasters', 'acc_trandetails.tm_id','=', 'acc_tranmasters.id')
														->where('tdate','<=',$data['dto'])
														->where('acc_trandetails.acc_id', $item->id)
														->groupBy('acc_trandetails.acc_id')
														->sum('acc_trandetails.amount') ; 
								
                                                        $item->amount=$sum; 
                                                        $debit=''; $credit=''; $bc=''; $dcur=''; $ccur=''; 
                                                        // to tal calculation
                                                        $ttl+=$item->amount; $balance += $item->amount;
                                                        $sl<4 ? $asset +=$item->amount : $liabi += $item->amount ; //echo $asset.' -2<br>';
														
														// to create amount and formate
														$item->amount <0  ? $debits= substr($item->amount,1) :  $debits= $item->amount; 
														$debit=number_format($debits, 2); 
                                                        
                                                        $debit!='' ? $dcur=$cur : $dcur=''; $credit!='' ? $ccur=$cur : $ccur='';
                                                        // make total of debit and credit and thier currency
                                                        $item->amount>0 ? $ttl_debit += $item->amount :  $ttl_credit += $item->amount ; //echo $ttl_credit;
                                                        $ttl_debit!='' ? $tdcur=$cur : $tdcur=''; $ttl_credit!='' ? $tccur=$cur : $tccur='';
														// find transaction 
														$find=DB::table('acc_trandetails')
														->join('acc_tranmasters', 'acc_trandetails.tm_id','=', 'acc_tranmasters.id')
														->where('tdate','<=',$data['dto'])																		
														->where('acc_trandetails.acc_id', $item->id)
														->first(); 
														isset($find->acc_id) ? $flag=1 : $flag=0; //echo $flag;
														
														if ($flag==1):
                                                        
                                                    ?>
                                                      <tr>
                                                        <td style="padding-left:90px">{{ $item->name }}</td>
                                                        <td id="cur">{{ $dcur }}</td><td class=" text-right">{{ $debit }}</td>
                                                        <td id="cur">{{ $ccur }}</td><td class=" text-right">{{ $credit }}</td>
                            
                                                      </tr>
                                                      <?php 
													  	endif;
													  endif;
													  
													  // find child account has or not?
														$find=DB::table('acc_trandetails')
														->join('acc_coas', 'acc_trandetails.acc_id', '=', 'acc_coas.id')
														->join('acc_tranmasters', 'acc_trandetails.tm_id', '=', 'acc_tranmasters.id')
														->where('tdate','<=',$data['dto'])
														->where('acc_coas.group_id', $item->id)
														->first(); 
														isset($find->acc_id) ? $flag=1 : $flag=0; //echo $flag;
														
														if (isset($item->atype) && $item->atype=='Group' && $flag==1):
														 ?>
														 <tr>
															<td style="padding-left:90px">{{ $item->name }}</td>
															<td id="cur"></td><td class=" text-right"></td>
															<td id="cur"></td><td class=" text-right"></td>
								
														  </tr>

                                                                   
                                                                    <!--------------forth------------------>
                                                                    <?php 
																	
													endif;				
																		$recordz=array();
																		if (isset($item->atype) && $item->atype=='Account'):
																			$recordz=DB::table('acc_trandetails')->where('acc_id', $item->id)->groupBy('acc_id')->get() ;
																		elseif (isset($item->atype) && $item->atype=='Group'):
																			$recordz=DB::table('acc_coas')->where('group_id', $item->id)->groupBy('id')->get() ;
																		endif;                                                     
																		?>
                                                                    
                                                                    @foreach($recordz as $item)
                                                                    <?php 
																	if (isset($item->atype) && $item->atype=='Account'):
																		
																		// find account-wise transaction sum
																		$sum=DB::table('acc_trandetails')
																		->join('acc_tranmasters', 'acc_trandetails.tm_id','=', 'acc_tranmasters.id')
																		->where('tdate','<=',$data['dto'])	 
																		->where('acc_trandetails.acc_id', $item->id)
																		->groupBy('acc_trandetails.acc_id')
																		->sum('acc_trandetails.amount') ; 
								
                                                                        $item->amount=$sum; 
                                                                        $debit=''; $credit=''; $bc=''; $dcur=''; $ccur=''; 
                                                                        // to tal calculation
                                                                        $ttl+=$item->amount; $balance += $item->amount;
                                                                        $sl<4 ? $asset +=$item->amount : $liabi += $item->amount ; //echo $asset.' -3<br>';
																		
																		// to create amount and formate
																		$item->amount <0  ? $debits= substr($item->amount,1) :  $debits= $item->amount; 
																		$debit=number_format($debits, 2); 
                                                                        
                                                                        $debit!='' ? $dcur=$cur : $dcur=''; $credit!='' ? $ccur=$cur : $ccur='';
                                                                        // make total of debit and credit and thier currency
                                                                        $item->amount>0 ? $ttl_debit += $item->amount :  $ttl_credit += $item->amount ; //echo $ttl_credit;
                                                                        $ttl_debit!='' ? $tdcur=$cur : $tdcur=''; $ttl_credit!='' ? $tccur=$cur : $tccur='';
																		
																		// find transaction 
																		$find=DB::table('acc_trandetails')
																		->join('acc_tranmasters', 'acc_trandetails.tm_id','=', 'acc_tranmasters.id')
																		->where('tdate','<=',$data['dto'])																		
																		->where('acc_trandetails.acc_id', $item->id)
																		->first(); 
																		isset($find->acc_id) ? $flag=1 : $flag=0; //echo $flag;
																		
																		if ($flag==1):
                                                                        
                                                                    ?>
                                                                      <tr>
                                                                        <td style="padding-left:120px">{{ $item->name }}</td>
                                                                        <td id="cur">{{ $dcur }}</td><td class=" text-right">{{ $debit }}</td>
                                                                        <td id="cur">{{ $ccur }}</td><td class=" text-right">{{ $credit }}</td>
                                            
                                                                      </tr>
                                                                      <?php 
																	  	endif;
																	  endif; ?>
                                                                    @endforeach 
                                                                    <!---------------------forth-------------------------->                                                     
                                                    @endforeach 
                                                    <!-----------------------third------------------------>   
                                   
                                    @endforeach 
                                    <!-------------------second---------------------------->
                                 
                                 
                                    <?php 
									$ttl!='' ? $ttl +=$pls : '';
									$ttl >0 ? $pl_text="Loss" : $pl_text="Profit" ; 
									$ttl <0 ? $ttl=substr($ttl,1) : '';
									$ttl!='' ? $ttls=number_format($ttl, 2) : ''; 
									$ttls !='' ?  $dcur=$cur : '';
									if($ttl!=''):
									?>
                                     <tr>
                                        <td class="text-right">{{ $pl_text }} Transfered to Balance Sheet</td>
                                        <td id="cur"></td><td class=" text-right"></td>
                                        <td id="cur">{{ $dcur }}</td><td class=" text-right" id="top">{{ $ttls }}</td>
                                      </tr>
									<?php endif;?>
                        @endforeach 

                   @endforeach 

                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Transaction->Balance Sheet</td><td class="text-right">Report generated by: {{ $users[$item->user_id] }}</td></tr>
                </table>
            </div><!-- /.box-header -->
        </div>
     </div>
</div>
@endsection
@section('custom-scripts')

<script type="text/javascript">
        
    jQuery(document).ready(function($) {        
        $(".tranmaster").validate();
		$( "#dfrom" ).datepicker({ dateFormat: "yy-mm-dd" }).val();
		$( "#dto" ).datepicker({ dateFormat: "yy-mm-dd" }).val();
    });
        
</script>

@endsection
