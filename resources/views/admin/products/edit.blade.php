@extends('admin.app')

@section('other-css')
    <link rel="stylesheet" href="{{url('backend/AdminLTE/plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{url('backend/wangEditor-3.0.10/release/wangEditor.css')}}">
@stop
@section('content-header')
    <h1>
        Product
        <small>Product edit</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Index</a></li>
        <li><a href="{{ route('product.index') }}">Product</a></li>
        <li class="active">Edit</li>
    </ol>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit</h3>
            <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 10px">
                    <a href="{{ route('product.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;List</a>
                </div>
                <div class="btn-group pull-right" style="margin-right: 10px">
                    <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
                </div>
            </div>

            <form action="{{ route('product.update', $product->productID) }}" method="post" class="form-horizontal" pjax-container>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab-general" data-toggle="tab" >
                                    General <i class="fa fa-exclamation-circle text-red hide"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#tab-desc" data-toggle="tab" >
                                    Description <i class="fa fa-exclamation-circle text-red hide"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#tab-shipping" data-toggle="tab" >
                                    Shipping <i class="fa fa-exclamation-circle text-red hide"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#tab-photo" data-toggle="tab" >
                                    Photo <i class="fa fa-exclamation-circle text-red hide"></i>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="tab-general">
                                <div class="form-group">
                                    <label for="sku" class="col-sm-2 control-label">SKU</label>
                                    <div class="col-sm-8">
                                        <div class="box box-solid box-default no-margin">
                                            <div class="box-body">
                                                {{ $product->product_code }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('title') ? ' has-error':'' }}">
                                    <label for="name" class="col-sm-2 control-label">Product Title</label>
                                    <div class="col-sm-8">
                                        @if($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                            <input type="text" id="title" name="title" value="{{ $product->name }}" class="form-control" placeholder="Input Title">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('category') ? ' has-error':'' }}">
                                    <label for="category" class="col-sm-2 control-label">Main Category</label>
                                    <div class="col-sm-8">
                                        @if($errors->has('category'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('category') }}</strong>
                                        </span>
                                        @endif
                                        <select class="js-example-basic-single js-states form-control" style="width: 100%;" name="category" id="select-category">
                                            @foreach($tree as $id =>$category)
                                            <option value="{{ $id }}"
                                                @if($product->categoryID == $id)
                                                    selected
                                                @endif
                                            > {{ $category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('cost') ? ' has-error':'' }}">
                                    <label for="cost" class="col-sm-2 control-label">Cost</label>
                                    <div class="col-sm-8">
                                        @if($errors->has('cost'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('cost') }}</strong>
                                        </span>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-terminal"></i></span>
                                            <input type="text" id="cost" name="cost" value="{{ $product->productAdditional->cost }}" class="form-control" placeholder="Input Cost">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('price') ? ' has-error':'' }}">
                                    <label for="price" class="col-sm-2 control-label">Sell price</label>
                                    <div class="col-sm-8">
                                        @if($errors->has('price'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('price') }}</strong>
                                        </span>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-terminal"></i></span>
                                            <input type="text" id="price" name="price" value="{{ $product->Price }}" class="form-control" placeholder="Input Price">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="handling" class="col-sm-2 control-label">Handling fee</label>
                                    <div class="col-sm-8">
                                        <div class="box box-solid box-default no-margin">
                                            <div class="box-body">
                                                {{ $product->handling }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('brandID') ? ' has-error':'' }}">
                                    <label for="brandID" class="col-sm-2 control-label">Brand</label>
                                    <div class="col-sm-8">
                                        @if($errors->has('brandID'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('brandID') }}</strong>
                                        </span>
                                        @endif
                                        <select class="js-example-basic-single js-states form-control" style="width: 100%;" name="brandID" id="select-brandID">
                                            @foreach($brandList as $brand)
                                                <option value="{{ $brand->id }}"
                                                        @if($product->productBrand->id == $brand->id)
                                                        selected
                                                        @endif
                                                > {{ $brand->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('warehouseID') ? ' has-error':'' }}">
                                    <label for="warehouseID" class="col-sm-2 control-label">Warehouse</label>
                                    <div class="col-sm-8">
                                        @if($errors->has('warehouseID'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('warehouseID') }}</strong>
                                        </span>
                                        @endif
                                        <select class="js-example-basic-single js-states form-control" style="width: 100%;" name="warehouseID" id="select-warehouse">
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->warehouse_code }}"
                                                        @if($product->warehouseID == $warehouse->warehouse_code)
                                                        selected
                                                        @endif
                                                > {{ $warehouse->warehouse_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('purchase_agent') ? ' has-error':'' }}">
                                    <label for="purchase_agent" class="col-sm-2 control-label">Purchase agent</label>
                                    <div class="col-sm-8">
                                        @if($errors->has('purchase_agent'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('purchase_agent') }}</strong>
                                        </span>
                                        @endif
                                        <select class="js-example-basic-single js-states form-control" style="width: 100%;" name="purchase_agent" id="select-purchaseAgent">
                                            @foreach($admins as $admin)
                                                <option value="{{ $admin->adminID }}"
                                                        @if($product->productAdditional->purchase_agent == $admin->adminID)
                                                        selected
                                                        @endif
                                                > {{ $admin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('listing_agent') ? ' has-error':'' }}">
                                    <label for="listing_agent" class="col-sm-2 control-label">listing agent</label>
                                    <div class="col-sm-8">
                                        @if($errors->has('listing_agent'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('listing_agent') }}</strong>
                                        </span>
                                        @endif
                                        <select class="js-example-basic-single js-states form-control" style="width: 100%;" name="listing_agent" id="select-listingAgent">
                                            @foreach($admins as $admin)
                                                <option value="{{ $admin->adminID }}"
                                                        @if($product->productAdditional->listing_agent == $admin->adminID)
                                                        selected
                                                        @endif
                                                > {{ $admin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('review_agent') ? ' has-error':'' }}">
                                    <label for="review_agent" class="col-sm-2 control-label">Developer</label>
                                    <div class="col-sm-8">
                                        @if($errors->has('review_agent'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('review_agent') }}</strong>
                                        </span>
                                        @endif
                                        <select class="js-example-basic-single js-states form-control" style="width: 100%;" name="review_agent" id="select-reviewAgent">
                                            @foreach($admins as $admin)
                                                <option value="{{ $admin->adminID }}"
                                                        @if($product->productAdditional->review_agent == $admin->adminID)
                                                        selected
                                                        @endif
                                                > {{ $admin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-desc">
                                @foreach($desc as $single_desc)
                                <div class="form-group">
                                    <label for="desc_{{ $single_desc->title }}" class="col-sm-2 control-label">{{ $single_desc->title }}</label>
                                    <div class="col-sm-8">
                                        <textarea name="desc_{{ $single_desc->title }}" id="editor">{{ $single_desc->body }}</textarea>
                                        <script data-exec-on-popstate >
                                            $(function () {
                                                CKEDITOR.replace('desc_{{ $single_desc->title }}')
                                            })
                                        </script>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-shipping">3</div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-photo">4</div>

                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    {{ method_field('put') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="_previous_" value="{{ route('product.index') }}" class="_previous_">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="btn-group pull-right">
                            <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Submit">Submit</button>
                        </div>
                        <div class="btn-group pull-left">
                            <button type="reset" class="btn btn-warning">Reset</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script data-exec-on-popstate>
        $(function () {
            $('#select-category').select2();
            $('#select-brandID').select2();
            $('#select-warehouse').select2();
            $('#select-listingAgent').select2();
            $('#select-purchaseAgent').select2();
            $('#select-reviewAgent').select2();


        })


    </script>


@stop

@section('other-js')

@stop

