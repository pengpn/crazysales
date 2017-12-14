@extends('admin.app')
@section('content-header')
    <h1>
        Product
        <small>Product lists</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Index</a></li>
        <li><a href="{{ route('product.index') }}">Product</a></li>
        <li class="active">Product Lists</li>
    </ol>
@stop

@section('content')
    <a href="{{url('admin/article/create')}}" class="btn btn-primary margin-bottom"><i class="fa fa-paint-brush" style="margin-right: 6px"></i>Import New Product</a>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Product Lists</h3>
            <div class="box-tools">
                {{--<form action="" method="get">--}}
                    {{--<div class="input-group">--}}
                        {{--<input type="text" class="form-control input-sm pull-right" name="s_title"--}}
                               {{--style="width: 150px;" placeholder="搜索文章标题">--}}
                        {{--<div class="input-group-btn">--}}
                            {{--<button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</form>--}}
                <div class="pull-right">
                    <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filter-modal"><i class="fa fa-filter"></i>Filter</a>
                </div>
                <div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Filter</h4>
                                <form action="{{ route('product.search') }}" method="post" >
                                    {{ csrf_field() }}
                                    <div class="modal-body">
                                        <div class="form">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Product ID</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-pencil"></i>
                                                        </div>
                                                        <input type="text" class="form-control productID" placeholder="ProductID" name="productID" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>SKU</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-pencil"></i>
                                                        </div>
                                                        <input type="text" class="form-control product_code" placeholder="SKU" name="product_code" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary submit">Submit</button>
                                        <button type="reset" class="btn btn-warning pull-left">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-hover table-bordered">
                <tbody>
                <!--tr-th start-->
                <thead>
                <tr>
                    <th>productID</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>In stock</th>
                    <th>ebay stock</th>
                    <th>Amazon stock</th>
                    <th>Walmart stock</th>
                    <th>Operation</th>
                </tr>
                </thead>
                <!--tr-th end-->

                <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="text-muted">{{ $product->productID }}</td>
                    <td class="text-green">{{ $product->product_code }}</td>
                    <td class="text-navy">{{ $product->Price }}</td>
                    <td class="text-navy">{{ $product->in_stock }}</td>
                    <td class="text-navy">{{ $product->ebay_stock }}</td>
                    <td class="text-navy">{{ $product->amazon_stock }}</td>
                    <td class="text-navy">{{ $product->walmart_stock }}</td>
                    <td>
                        <a style="font-size: 16px" href="{{ route('product.edit',$product->productID) }}"><i class="fa fa-fw fa-pencil" title="修改"></i></a>
                        <a style="font-size: 16px;color: #dd4b39;" href="#"><i class="fa fa-fw fa-trash-o" title="删除"></i></a>
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>
            <div class="pull-right">
            {!! $products->links() !!}
            </div>
        </div>
    </div>
@stop