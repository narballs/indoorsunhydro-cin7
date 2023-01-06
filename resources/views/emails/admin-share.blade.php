<!DOCTYPE html>
<html>
 <head>
 	<style>
        .main-div {
            width: 100%;
            min-width: 600px;
            max-width: 100%;
            margin: auto;
            background: #fff;
            padding: 10px;
            padding-bottom: 100px;
            margin-bottom: 100px;
            background-color: #dee2e6;
        }

        table.bordered {
            border-top: 1px solid gray;
            border-right: 1px solid gray;
            border: none;
            background-color: #FFF;
            background-radius: 5px;
            height: 100%;

        }

        table.bordered td.has-bordered {
            /*border-left: 1px solid gray;*/
             //border-bottom: 1px solid gray;
             border-top: 1px solid gray;
            padding: 10px;
        }

        .bottom-row td.border-bottom {
            border-bottom: 1px solid gray;
        }
        .buy-now {
            background: #7CC633;
            float: right;
            justify-content: center;
            border: none;
            width: 138px;
        }
    </style>
</head>
<body>
  	<div class="main-div" style="width:100%">
        <div style="text-align:center"><h1>{{$list->title}}</h1></div>
            <table class="bordered" style="width:80%;margin:auto; border:1px solid #edf1f7;">
                <thead>
                    <tr><td>&nbsp;</td></tr>
                    <tr style="padding-right:10px;">
                    	<th class="has-bordered"></th>
                        <th><h3>Products</h3></th>
                        <th><h3>Price</h3></th>
                        <th><h3>Quantity</h3></th>
                        <th ><h3>Subtotal</h3></th>
                    </tr>
                </thead>
                <tbody>
                     <?php $count = 1;?>

                    @foreach($list->list_products as $list_product)
                        @foreach($list_product->product->options as $key=>$option)
                        <?php 
                       
                        $bgcolor = 'background-color:#edf1f7';
                        if ($count % 2 == 0) {
                        	$bgcolor = 'background-color:#FFFFFF';

                        }
                        $count++;
                        ?>
                            <tr style="padding:0px;text-align:center;border: 1px solid red; <?php echo $bgcolor;?>; font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Arial,'Roboto';font-size: 15px;">
                                <td class="has-bordered">
                                    <img src="{{$option->image}}" alt="Product 1" class="img-circle img-size-32 mr-2" width="100px" style="max-height:150px">
                                </td>
                                <td class="has-bordered" style =<?php echo $bgcolor;?>>
                                    {{$list_product->product->name}}
                                </td>
                                <td class="has-bordered">${{$list_product->product->retail_price}}</td>
                                <td class="has-bordered">
                                  <!--   <small class="text-success mr-1">
                                        <i class="fas fa-arrow-up"></i>
                                    12%
                                    </small> -->
                                        {{$list_product->quantity}}
                                </td>
                                <td class="has-bordered">
                                   ${{$list_product->sub_total}}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr class="has-bordered"><td colspan="4" style="text-align:left;margin-left: 20px"><h3><span style="margin-left:40px">Grand Total</span></h3></td><td><span style="margin-left:75px;text-align:center"><h3>${{$list_product->grand_total}}</h3></span></td></tr>
                    <tr><td colspan="4" style="padding-bottom:20px;"><a href="{{$link}}"><center><button style="background:#7CC633;border: none;width: 138px;color:white;height:44px;font-size:18px;margin:auto;text-align:center;" type="button" value="Buy Now">Buy Now</button></center></a></td></tr>
                </tbody>
            </table>
        
        </div>
    </body>
</html>