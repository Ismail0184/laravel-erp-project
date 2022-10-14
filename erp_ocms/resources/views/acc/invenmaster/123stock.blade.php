@extends('app')

@section('htmlheader_title', $langs['edit'] . ' Trandetail')

@section('contentheader_title', 	  'Inventory')
@section('main-content')
<style>
	#hdr td, #hdr th{ padding:5px }
	.qty { padding-right:50px;}
</style>

 <div class="container">
 <div class="box" >
         <div class="box-header">
            <a href="{{ url('invenmaster/stock_print') }}" title="{{ $langs['print'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-print"></i></a>
<!--            <a href="{{ url('invenmaster/stock_pdf') }}" title="{{ $langs['download'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-download"></i></a>
            <a href="{{ url('invenmaster/stock_pdf') }}" title="{{ $langs['pdf'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-pdf-o"></i></a>
            <a href="{{ url('invenmaster/stock_excel') }}" title="{{ $langs['excel'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-excel-o"></i></a>
            <a href="{{ url('salemaster/stock_csv') }}" title="{{ $langs['csv'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-excel-o"></i></a>
            <a href="{{ url('salemaster/stock_word') }}" title="{{ $langs['word'] }}" class="btn btn-primary pull-right btn-sm trash-btn"><i class="fa fa-file-word-o"></i></a>
-->        </div><!-- /.box-header -->

    <div class="table-responsive">
        <table  width="100%  id="hdr">
        <?php 
			Session::put('lgdfrom', date('Y-m-01'));
			Session::put('lgdto', date('Y-m-d'));
			Session::put('lgwh_id', Session::get('sbwar_id'));
			//-- For stock ledger------
				Session::put('lgwh_id', '');
				Session::put('lgdfrom', date('Y-m-01'));
				Session::put('lgdto', date('Y-m-d'));
			//-----------------------------------------------
			//$x='';
			Session::has('com_id') ? $com_id=Session::get('com_id') : $com_id='' ;// echo $com_id.'osama';
			$com=DB::table('acc_companies')->where('id',$com_id)->first(); $com_name=''; 
			isset($com) && $com->id>0 ? $com_name=$com->name : $com_name=''; 
			echo '<tr><td colspan="2"><h3 align="center">'.$com_name.'</h3></td></tr>';

			// data collection filter method by session	
			$data=array('war_id'=>'','group_id'=>'','dto'=>'0000-00-00');
			
			Session::has('sbdto') ? 
			$data=array('war_id'=>Session::get('sbwar_id'),'group_id'=>Session::get('sbgroup_id'),'dto'=>Session::get('sbdto')) : 
			$data=array('war_id'=>'','group_id'=>'','dto'=>date('Y-m-d')); 

			$wh=DB::table('acc_warehouses')->where('com_id',$com_id)->where('id',$data['war_id'])->first(); //echo $item->war_id;						
			$wh_name=''; isset($wh) && $wh->id > 0 ? $wh_name=$wh->name : $wh_name=''; //echo $wh_name;
			
			$data['group_id']> 0 ? $group_name=$p_groups[$data['group_id']] : $group_name='';

			if (isset($data['group_id']) && $data['group_id']>0):
				// for single account
				
				echo '<tr><td ><h3 class="pull-left">Stock Balance</h3></td>
				<td class="text-right" ><h3 aling="right">Category : '.$group_name.'</h3><h5 ></h5></td></tr>';
			else:
				// for multiple account
				echo '<tr><td class="text-center" colspan="2">
				<h4>Stock Balance</h4>
				<h4>Warehouse :'.$wh_name.'</h4>
				<h5 >Dated on :'.$data['dto'].'</h5>
				</td></tr>';
			endif;
		?>
        
        </table>

            <table id="buyerinfo-table" class="table table-bordered">
                <thead>
                <tr><td colspan="8"><a href="{!! url('/invenmaster/stock?flag=filter') !!}"> Filter  </a>
					<?php 
                    	$flags=''; isset($_GET['flag']) ? $flags=$_GET['flag'] : ''; 
						 !isset($data['group_id']) ? $data['group_id']='' : '' ;
                   
				    // to get data by fileter
					?>
                    @if ($flags=='filter')
                           {!! Form::open(['url' => 'invenmaster/sbfilter', 'class' => 'form-horizontal']) !!}
            
                            <div class="form-group">
                                {!! Form::label('war_id', $langs['war_id'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::select('war_id', $warehouses, null, ['class' => 'form-control']) !!}
                                </div>    
                            </div>
                            <div class="form-group">
                                {!! Form::label('group_id', $langs['group_id'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::select('group_id', $p_groups, null, ['class' => 'form-control']) !!}
                                </div>    
                            </div>
                            <div class="form-group">
                                {!! Form::label('dto', $langs['dto'], ['class' => 'col-sm-3 control-label']) !!}
                                <div class="col-sm-6"> 
                                    {!! Form::text('dto', date('Y-m-d'), ['class' => 'form-control', 'id'=>'dto', 'required' ]) !!}
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
                        <th class="col-md-1">{{ $langs['sl'] }}  </th>
                        <th class="col-md-3">{{ $langs['name'] }}  </th>
                        <th class="col-md-2 text-right">{{ $langs['qty'] }} </th>
                    </tr>
                </thead>
                <tbody>
                <?php 
					if (isset($data['group_id']) && $data['group_id'] > 0): 
							$product=DB::table('acc_products')->where('com_id',$com_id)->where('id', $data['group_id'])->get();
					endif;
				?>
                @foreach($product as $item)
                <?php
					//$item->name;
					$flag='';
					if ($item->ptype=='Product'): 
						$tran=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$item->id)->sum('qty');
						isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
					elseif ($item->ptype=='Top Group' && $flag==''): 
						$find=DB::table('acc_products')->where('com_id',$com_id)->where('group_id',$item->id)->get();
						foreach( $find as $items): //echo  $items->name;
							if ($items->ptype=='Product'): 
								$tran=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$items->id)->sum('qty');
								isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
							elseif ($items->ptype=='Group'  && $flag==''): 
								$find=DB::table('acc_products')->where('com_id',$com_id)->where('group_id',$items->id)->get();
								foreach( $find as $items):
									if ($items->ptype=='Product'):
										$tran=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$items->id)->sum('qty');
										isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
									elseif ($items->ptype=='Group'  && $flag==''):
										$find=DB::table('acc_products')->where('com_id',$com_id)->where('group_id',$items->id)->get();
										foreach( $find as $items):
											$tran=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$items->id)->sum('qty');
											isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
										endforeach;	
									endif;					
								endforeach;
							endif;					
						endforeach;
					endif;
					//echo $flag.' 1<br>';
					//echo $item->name.'<br>';
				?>
                @if ($item->ptype=='Top Group'): 
                <?php $qty=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',  $item->id)->sum('qty');  ?>
                <tr>
                       	<td colspan="3">{{ $item->name }}</td>
                 </tr>
                 @endif
                 		
                    	<?php $record = DB::table('acc_products')->where('com_id',$com_id)->where('group_id', $item->id)->orderby('sl')->get(); ?>  
				{{-- */$x=0;/* --}}
                        @foreach($record as $item)
							<?php
								//echo $item->name.'<br>';
                                $flag='';
                                if ($item->ptype=='Product'): //echo 123;
                                    $tran=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$item->id)->sum('qty');;
                                    isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                elseif ($item->ptype=='Group' && $flag==''): //echo 456;
                                    $find=DB::table('acc_products')->where('com_id',$com_id)->where('group_id',$item->id)->get();
                                    foreach( $find as $items): //echo  '-----'.$items->name.'<br>';
                                        if ($items->ptype=='Product'): //echo $items->id;
                                            $tran=DB::table('acc_invendetails')->where('com_id',$com_id)
											->where('item_id',$items->id)->sum('qty');
                                            isset($tran) && $tran > 0 ? $flag='yes' : $flag=''; //echo $flag;
                                        elseif ($items->ptype=='Group'  && $flag==''): 
                                            $find=DB::table('acc_products')->where('com_id',$com_id)
											->where('group_id',$items->id)->get();
                                            foreach( $find as $items): 
                                                if ($items->ptype=='Product'): //echo  $items->name;
                                                    $tran=DB::table('acc_invendetails')->where('com_id',$com_id)
													->where('item_id',$items->id)->sum('qty');
                                                    isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                                elseif ($items->ptype=='Group'  && $flag==''): 
                                                    $find=DB::table('acc_products')->where('com_id',$com_id)
													->where('group_id',$items->id)->get();
                                                    foreach( $find as $items): //echo  $items->name;
                                                        $tran=DB::table('acc_invendetails')->where('com_id',$com_id)
														->where('item_id',$items->id)->sum('qty');
                                                        isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                                    endforeach;	
                                                endif;					
                                            endforeach;
                                        endif;					
                                    endforeach;
                                endif;
								
                            ?>
                            
                                @if( $flag=='yes')
								 <?php 
										if ($data['war_id']==''):
											$qty=DB::table('acc_invendetails')
											->join('acc_invenmasters','acc_invendetails.im_id','=','acc_invenmasters.id')
											->where('acc_invenmasters.com_id',$com_id)
											->where('idate','<=', $data['dto'])->where('item_id',  $item->id)->sum('qty');
										else:
											$qty=DB::table('acc_invendetails')
											->join('acc_invenmasters','acc_invendetails.im_id','=','acc_invenmasters.id')
											->where('acc_invenmasters.com_id',$com_id)
											->where('acc_invendetails.war_id',$data['war_id'])
											->where('idate','<=', $data['dto'])->where('item_id',  $item->id)->sum('qty');
										endif;
                                        $unit=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',  $item->id)->first();
                                        $unit_name='';
										if($qty !=null): 
											$unit=DB::table('acc_units')
											->where('id', $item->unit_id)->first() ; $unit_name='';
											isset( $unit) &&  $unit->id > 0 ? $unit_name= $unit->name :  $unit_name='';
										endif;
                                        $qtys=$qty !=null ? $qty.'  '.$unit_name : '';
										
										
                                     ?>
						               {{-- */$x++;/* --}}
                                        <tr>
                                        <td>{{ $x }}</td>
                                        <td style="padding-left:30px"><a href="{{ url('/invenmaster/ledger?item_id='. $item->id) }}">{{ $item->name }}</a></td>
                                        <td class="text-right qty">{{ $qtys }}</td>
                                        </tr>
                                @endif
									<?php $records = DB::table('acc_products')->where('com_id',$com_id)->where('group_id', $item->id)->orderby('sl')->get(); ?>  
                                    @foreach($records as $item)
												<?php
                                            //echo $item->ptype;
                                            $flag='';
                                            if ($item->ptype=='Product'): //echo 123;
                                                $tran=DB::table('acc_invendetails')->where('com_id',$com_id)
												->where('item_id',$item->id)->sum('qty');
                                                isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                            elseif ($item->ptype=='Group' && $flag==''): //echo 456;
                                                $find=DB::table('acc_products')->where('com_id',$com_id)
												->where('group_id',$item->id)->get();
                                                foreach( $find as $items): //echo  $items->name;
                                                    if ($items->ptype=='Product'): //echo 988;
                                                        $tran=DB::table('acc_invendetails')->where('com_id',$com_id)
														->where('item_id',$items->id)->sum('qty');
                                                        isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                                    elseif ($items->ptype=='Group'  && $flag==''): 
                                                        $find=DB::table('acc_products')->where('com_id',$com_id)
														->where('group_id',$items->id)->get();
                                                        foreach( $find as $items): 
                                                            if ($items->ptype=='Product'): //echo  $items->name;
                                                                $tran=DB::table('acc_invendetails')->where('com_id',$com_id)
																->where('item_id',$items->id)->sum('qty');
                                                                isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                                            elseif ($items->ptype=='Group'  && $flag==''): 
                                                                $find=DB::table('acc_products')->where('com_id',$com_id)
																->where('group_id',$items->id)->get();
                                                                foreach( $find as $items): //echo  $items->name;
                                                                    $tran=DB::table('acc_invendetails')->where('com_id',$com_id)
																	->where('item_id',$items->id)->sum('qty');
                                                                    isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                                                endforeach;	
                                                            endif;					
                                                        endforeach;
                                                    endif;					
                                                endforeach;
                                            endif;
                                        ?>
            
                                            @if( $flag=='yes')
											 <?php 
													$qty=DB::table('acc_invendetails')
													->join('acc_invenmasters','acc_invendetails.im_id','=','acc_invenmasters.id')
													->where('idate','<=', $data['dto'])->where('item_id',  $item->id)
													->where('acc_invendetails.war_id',$data['war_id'])->groupBy('item_id')->sum('qty'); //echo $qty.'-osama';
                                                    
													$unit=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',  $item->id)->first();
                                                    $qty !=null ? 
                                                    $unit=DB::table('acc_units')
                                                    ->where('id', $item->unit_id)->first() : '';
													isset( $unit) &&  $unit->id && $qty !=null> 0 ? $unit_name= $unit->name :  $unit_name='';
                                                    $qtys=$qty !=null ? $qty.'  '.$unit_name : '';
                                                 ?>
								                {{-- */$x++;/* --}}
                                                   <tr>
                                        			<td>{{ $x }}</td>
                                                    <td style="padding-left:50px"><a href="{{ url('/invenmaster/ledger?item_id='. $item->id) }}">{{ $item->name }}</a></td>
                                                    <td class="text-right qty">{{ $qtys }}</td>
                                                    </tr>
                                            @endif
                                     
												<?php $recordz = DB::table('acc_products')->where('com_id',$com_id)->where('group_id', $item->id)
												->orderby('sl')->get(); ?>  
                                                @foreach($recordz as $item)
                    										<?php
                                                            //echo $item->ptype;
                                                            $flag='';
                                                            if ($item->ptype=='Product'): //echo 123;
                                                                $tran=DB::table('acc_invendetails')->where('com_id',$com_id)
																->where('item_id',$item->id)->sum('qty');
                                                                 isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                                            elseif ($item->ptype=='Group' && $flag==''): //echo 456;
                                                                $find=DB::table('acc_products')->where('com_id',$com_id)
																->where('group_id',$item->id)->get();
                                                                foreach( $find as $items): //echo  $items->name;
                                                                    if ($items->ptype=='Product'): //echo 988;
                                                                        $tran=DB::table('acc_invendetails')->where('com_id',$com_id)
																		->where('item_id',$items->id)->sum('qty');
                                                                        isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                                                    elseif ($items->ptype=='Group'  && $flag==''): 
                                                                        $find=DB::table('acc_products')->where('com_id',$com_id)
																		->where('group_id',$items->id)->get();
                                                                        foreach( $find as $items): 
                                                                            if ($items->ptype=='Product'): //echo  $items->name;
                                                                                $tran=DB::table('acc_invendetails')
																				->where('com_id',$com_id)->where('item_id',$items->id)
																				->sum('qty');
                                                                                isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                                                            elseif ($items->ptype=='Group'  && $flag==''): 
                                                                                $find=DB::table('acc_products')->where('com_id',$com_id)
																				->where('group_id',$items->id)->get();
                                                                                foreach( $find as $items): //echo  $items->name;
                                                                                    $tran=DB::table('acc_invendetails')
																					->where('com_id',$com_id)->where('item_id',$items
																					->id)->sum('qty');
                                                                                    isset($tran) && $tran > 0 ? $flag='yes' : $flag='';
                                                                                endforeach;	
                                                                            endif;					
                                                                        endforeach;
                                                                    endif;					
                                                                endforeach;
                                                            endif;
                                                        ?>                                                
                                                        @if( $flag=='yes')
														 <?php 
																$qty=DB::table('acc_invendetails')
																->join('acc_invenmasters','acc_invendetails.im_id','=','acc_invenmasters.id')
																->where('idate','<=', $data['dto'])->where('item_id',  $item->id)
																->where('acc_invendetails.war_id',$data['war_id'])
																->sum('qty'); 
																$unit=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',  $item->id)->first();
																$qty !=null ? 
																$unit=DB::table('acc_units')
																->where('id', $item->unit_id)->first() : '';
																$qtys=$qty !=null ? $qty.'  '.$unit_name : '';
                                                             ?>
                                                                 <tr>
                                                                <td style="padding-left:70px"><a href="{{ url('/invenmaster/ledger?item_id='. $item->id) }}">{{ $item->name }}</a></td>
                                                                <td class="text-right qty">{{ $qtys }}</td>
                                                                </tr>
                                                        @endif
                                                 
														<?php $recordX = DB::table('acc_products')->where('com_id',$com_id)->where('group_id', $item->id)->orderby('sl')->get(); ?>  
                                                        @foreach($recordX as $item)
<!--                                                        {{-- */$x++;/* --}}
-->																	<?php
                                                                    //echo $item->ptype;
                                                                    $flag='';
                                                                    if ($item->ptype=='Product'): //echo 123;
                                                                        $tran=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$item->id)->first();
                                                                        isset($tran) && $tran->id > 0 ? $flag='yes' : $flag='';
                                                                    elseif ($item->ptype=='Group' && $flag==''): //echo 456;
                                                                        $find=DB::table('acc_products')->where('com_id',$com_id)->where('group_id',$item->id)->get();
                                                                        foreach( $find as $items): //echo  $items->name;
                                                                            if ($items->ptype=='Product'): //echo 988;
                                                                                $tran=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$items->id)->first();
                                                                                isset($tran) && $tran->id > 0 ? $flag='yes' : $flag='';
                                                                            elseif ($items->ptype=='Group'  && $flag==''): 
                                                                                $find=DB::table('acc_products')->where('com_id',$com_id)->where('group_id',$items->id)->get();
                                                                                foreach( $find as $items): 
                                                                                    if ($items->ptype=='Product'): //echo  $items->name;
                                                                                        $tran=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$items->id)->first();
                                                                                        isset($tran) && $tran->id > 0 ? $flag='yes' : $flag='';
                                                                                    elseif ($items->ptype=='Group'  && $flag==''): 
                                                                                        $find=DB::table('acc_products')->where('com_id',$com_id)->where('group_id',$items->id)->get();
                                                                                        foreach( $find as $items): //echo  $items->name;
                                                                                            $tran=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',$items->id)->first();
                                                                                            isset($tran) && $tran->id > 0 ? $flag='yes' : $flag='';
                                                                                        endforeach;	
                                                                                    endif;					
                                                                                endforeach;
                                                                            endif;					
                                                                        endforeach;
                                                                    endif;
																?>                                                
                                                                @if( $flag=='yes')
                                                                	 <?php 
																$qty=DB::table('acc_invendetails')->join('acc_invenmasters','acc_invendetails.im_id','=','acc_invenmasters.id')
																->where('idate','<=', $data['dto'])->where('item_id',  $item->id)
																->where('acc_invendetails.war_id',$data['war_id'])->sum('qty'); 
																		$unit=DB::table('acc_invendetails')->where('com_id',$com_id)->where('item_id',  $item->id)->first();
																		$qty !=null ? 
																		$unit=DB::table('acc_units')
																		->where('id', $item->unit_id)->first() : '';
																		$qtys=$qty !=null ? $qty.'  '.$unit_name : '';
																		 ?>
                                                                    <td style="padding-left:90px">{{ $item->name }}</td>
                                                                    <td class="text-right qty">{{ $qtys }}</td>
                                                                    </tr>
                                                                @endif                                                         
                                                        @endforeach      
                                                 
                                                @endforeach                                      
                                     
                                    @endforeach                         
                         
                        @endforeach

                @endforeach

                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Import->Product</td><td class="text-right">Report generated by: </td></tr>
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
