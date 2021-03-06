@extends("admin.main")
@section("content")
    <div class="page-content">
        <div class="page-header">
            <h1>
                站点设置
            </h1>
        </div><!-- /.page-header -->
        <div class="row">
            <div class="col-xs-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#web-title" aria-controls="web-title" role="tab" data-toggle="tab">基本信息</a>
                    </li>
                    <li role="presentation">
                        <a href="#queue" aria-controls="queue" role="tab" data-toggle="tab">队列设置</a>
                    </li>
                    <li role="presentation">
                        <a href="#other" aria-controls="other" role="tab" data-toggle="tab">其它设置</a>
                    </li>
                </ul>

                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id}}"/>
                    @endif
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="web-title">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 前台名称 </label>
                                <div class="col-sm-9">
                                    <input type="text" name="info[title]" value="{{$info->title??''}}"
                                           class="col-xs-10 col-sm-8">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 后台名称 </label>
                                <div class="col-sm-9">
                                    <input type="text" name="info[admin_title]" value="{{$info->admin_title??''}}"
                                           class="col-xs-10 col-sm-8">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 关键词 </label>
                                <div class="col-sm-9">
                                    <textarea name="info[keywords]" class="col-xs-10 col-sm-8" rows="2" cols="10"
                                              style="height:100px;">{{$info['keywords']??''}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 网站描述 </label>
                                <div class="col-sm-9">
                                    <textarea name="info[description]" class="col-xs-10 col-sm-8" rows="2" cols="10"
                                              style="height:100px;">{{$info['description']??''}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="queue">

                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right">
                                    <span class="red">*</span> 微信通知异步队列
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {!! form_radio(\App\Models\Site::$queueArr,$info['setting']['queue_weixin'],' name="info[setting][queue_weixin]" ','80','') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right">
                                    <span class="red">*</span> 邮件通知异步队列
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                    {!! form_radio(\App\Models\Site::$queueArr,$info['setting']['queue_email'],' name="info[setting][queue_email]" ','80','')!!}
                                </div>
                            </div>

                        </div>

                        <div role="tabpanel" class="tab-pane" id="other">
                            <div class="form-group">
                                <label class="col-sm-3 col-xs-3 control-label no-padding-right" for="form-field-2">
                                    <span class="red">*</span> 系统通知
                                </label>
                                <div class="col-sm-9 col-xs-9">
                                        {!! form_checkbox(\App\Models\Site::$alertTypeArr,$info['setting']['alert_type'],' name="info[setting][alert_type][]"','') !!}
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right"> 系统报警邮件 </label>
                                <div class="col-sm-9">
<textarea name="info[setting][mail]" class="col-xs-10 col-sm-8" rows="2" cols="10"
          style="height:100px;">{{$info['setting']['mail']??''}}</textarea>
                                </div>
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
                    </div>

                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@endsection


