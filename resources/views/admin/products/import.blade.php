@extends('admin.app')

@section('content-header')
    <h1>
        Product
        <small>Import Product</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Index</a></li>
        <li><a href="{{ route('product.index') }}">Product</a></li>
        <li class="active">Import</li>
    </ol>
@stop


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab-import" data-toggle="tab" aria-expanded="true" >
                            Import product
                        </a>
                    </li>
                    <li>
                        <a href="#tab-update" data-toggle="tab" >
                            Update product
                        </a>
                    </li>
                    <li>
                        <a href="#tab-category" data-toggle="tab" >
                            Find CategoryID
                        </a>
                    </li>
                    <li>
                        <a href="#tab-scClassID" data-toggle="tab" >
                            Find scClassID
                        </a>
                    </li>
                    <li>
                        <a href="#tab-supplier" data-toggle="tab" >
                            Find Supplier
                        </a>
                    </li>
                    <li>
                        <a href="#tab-agent" data-toggle="tab" >
                            Find AgentID
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="tab-import">
                        <div class="panel panel-default">
                            <form method="POST" action="{{ route('product.doImport') }}" class="form-horizontal" accept-charset="UTF-8" pjax-container="1">

                                <div class="panel-heading">Message</div>
                                <div class="panel-body">
                                    This section allows you to import products catalog from a CSV file (Comma Separated Values file).<br>

                                    Prepare your products catalog file in Microsoft Excel, save it as a CSV file and upload it using the following form.
                                    <a>Download sample file...</a>

                                    <blockquote>
                                        <ol style="font-size: 14px">
                                            <li>Find categoryID, scClassID and Supplier for import items</li>
                                            <li>Add new category and supplier if they don't exist.</li>
                                            <li>The import function is limited to 200</li>
                                            <li>Confirm the imported products by Click Me link</li>
                                            <li>desc_domain: 0 for crazysales,1 for ebay,2 for both crazysales and ebay, leave it blank means for crazysales</li>
                                            <li>Product Code Type must be 2 (SKU Product), or 3 (Part Product)</li>
                                        </ol>
                                    </blockquote>
                                        Please specify Excel CSV file:<input id="file" name="importProductCSV" type="file" >
                                </div>

                                <div class="box-footer">
                                    {{ method_field('put') }}
                                    {{ csrf_field() }}
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
                </div>
            </div>
        </div>
    </div>
    <script data-exec-on-popstate>
        $(function () {
            $("input#file").fileinput({
                "overwriteInitial": false,
                "initialPreviewAsData": true,
                "browseLabel": "Browse",
                "showRemove": false,
                "showUpload": false,
                "allowedFileExtensions": ['csv'],
                "initialCaption": "",
            });
        })
    </script>
@stop

