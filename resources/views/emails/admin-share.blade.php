<!DOCTYPE html>
<html>
 <head>
 	<style>
        .main-div {
            width: 600px;
            min-width: 600px;
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 10px;
            padding-bottom: 100px;
            margin-bottom: 100px;
        }

        table.bordered {
            border-top: 1px solid gray;
            border-right: 1px solid gray;
            border: none;
            background-color: lightgray !important;
        }

        table.bordered td.has-bordered {
            /*border-left: 1px solid gray;*/
             border-bottom: 1px solid gray;
            padding: 10px;
        }

        .bottom-row td.border-bottom {
            border-bottom: 1px solid gray;
        }
    </style>
</head>
<body>
  	<div style="width:100%">
            <table class="bordered" style="width:80%;margin:auto; border:1px solid gray">
                <thead>
                    <tr style="padding-right:10px;">
                    	<th class="has-bordered"></th>
                        <th>Products</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th >Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                     <?php $count = 1;?>

                    @foreach($list->list_products as $list_product)
                        @foreach($list_product->product->options as $key=>$option)
                        <?php 
                       
                        $bgcolor = 'background-color:#B0B0B0';
                        if ($count % 2 == 0) {
                        	$bgcolor = 'background-color:#E8E8E8';
                        }
                        $count++;
                        ?>


                            <tr style="padding:0px;text-align:center;border: 1px solid red; <?php echo $bgcolor;?>">
                                <td class="has-bordered">
                                    <img src="{{$option->image}}" alt="Product 1" class="img-circle img-size-32 mr-2" width="100px">
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
                    <tr style="text-align:center;"><th colspan="4">Grand Total</th><td>${{$list_product->grand_total}}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>