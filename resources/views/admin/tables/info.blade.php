@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{isset($info->id)?'详情':'添加'}}
                <button class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'index'">
                    返回列表
                </button>
            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id??''}}"/>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 餐桌名称 </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{$info->name??''}}" {{(isset($info->id) && $info->id<10)?'disabled':''}} class="col-xs-8">
                        </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 餐桌编号 </label>
                        <div class="col-sm-9">
                            <input type="text" name="num_id" value="{{$info->num_id??''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 餐桌类型 </label>
                        <div class="col-sm-3">
                            {!! form_select(\App\Models\Tables::$typeArr,$info->type??0,'class="form-control" name="type" class="col-xs-10 col-sm-8"','--请选择--') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 是否显示 </label>
                        <div class="col-sm-9">
                            {!! form_radio([0=>'隐藏',1=>'显示'],$info->is_show??'','name="is_show"') !!}
                        </div>
                    </div>

                    @if (count($errors) > 0)
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                提交
                            </button>
                            <button class="btn" type="reset">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>


@endsection
