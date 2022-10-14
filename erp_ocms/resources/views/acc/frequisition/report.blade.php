<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
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
<?php 
//use BD;
?>

</head>   
<body>

 <div class="container">
 <div class="row center-block" >
    <div class="table-responsive">
        <div class="box-header">
        <table class="table borderless">
        <tr><td class="text-center"><h1>OCMD</h1></td></tr>
        <tr><td class="text-center"><h5>Fund Requisition List</h5></td></tr>
        </table>
        </div><!-- /.box-header -->

            <table id="buyerinfo-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="col-md-1">{{ $langs['sl'] }}</th>
                        <th class="col-md-2">{{ $langs['date'] }}</th>
                        <th class="col-md-2">{{ $langs['pr_id'] }}</th>
                        <th class="col-md-2 " >{{ $langs['description'] }}</th>
                        <th class="col-md-1 text-right" colspan="2">{{ $langs['ramount'] }}</th>
                        <th class="col-md-2 " >{{ $langs['check_id'] }}</th>
                        <th class="col-md-2 " >{{ $langs['appr_id'] }}</th>
                    </tr>
                </thead>
                <tbody>
				{{-- */$x=0;/* --}}
                <?php 
						$id=''; isset($_GET['id']) ? $id=$_GET['id'] : $id='';  
						$id !='' ?  $import=DB::table('acc_importmasters')->where('id', $id)->get() : '';
				?>
                        @foreach($requisition as $item)
                         {{-- */$x++;/* --}}
                         <tr>
                        	<td width="50">{{ $x }}</td>
                            <td >{{ $item->created_at }}</td>
                            <td >{{ $item->preq->name }}</td>
                            <td >{{ $item->preq->description }}</td>
                            <td>{{ $item->currency->name }}</td><td class=" text-right">{{ $item->ramount }}</td>
                            <td>{{ $item->check->name }}</td>
                            <td>{{ $item->approve->name }}</td>
                         </tr>
                         <?php $ttl=0; $ttl += $item->ramount; ?>
                        @endforeach   
                        <tr><td colspan="4" class=" text-right">Total</td><td></td><td class=" text-right">{{ $ttl }}</td><td></td><td></td></tr> 

                </tbody>
            </table>
			<div class="box-header">
                <table class="table borderless">
                <tr><td class="text-left">Source: Requisition->Purchase</td><td class="text-right">Report generated by: {{ $item->user->name }}</td></tr>
                </table>
            </div><!-- /.box-header -->
        </div>
     </div>
</div>
</body>
</html>