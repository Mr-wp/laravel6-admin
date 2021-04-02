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
                <form class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id??''}}"/>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 用户名 </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{$info->name??''}}" {{isset($info->id)?'disabled':''}} class="col-xs-8">
                        </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 头像 </label>
                        <div class="col-sm-9">
                            <input type="file" name="head_img" id="head_img" value="" class="col-xs-10 col-sm-4">
                            @if(isset($info->head_img))
                                <img src="{{$info->head_img}}" class="col-xs-10 col-sm-4"/>
                            @endif
                        </div>
                    </div>

                    @if(empty($info->id))
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 密码 </label>
                            <div class="col-sm-9">
                                <input type="password" name="password" value="" class="col-xs-10 col-sm-8">
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 真实姓名 </label>
                        <div class="col-sm-9">
                            <input type="text" name="realname" value="{{$info->realname??''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 手机号 </label>
                        <div class="col-sm-9">
                            <input type="text" name="mobile" value="{{$info->mobile??''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 邮箱 </label>
                        <div class="col-sm-9">
                            <input type="text" name="email" value="{{$info->email??''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 角色 </label>
                        <div class="col-sm-9">
                            {!! form_checkbox($admin_group,$info->group_ids??'','name="group_id[]"') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 状态 </label>
                        <div class="col-sm-3">
                            {!! form_select(\App\Models\AdminUser::$statusArr,$info->status??1,'class="form-control" name="status" class="col-xs-10 col-sm-8"','--请选择--') !!}
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
