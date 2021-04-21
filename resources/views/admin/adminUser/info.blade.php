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

                    <div class="form-group {{ $errors->has('head_img') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right"> <span style="color: red;">*</span>头像 </label>
                        <div class="col-sm-6">
                            <input id="upload_img" name="upload_img" multiple data-overwrite-initial="true"  type="file" value="">
                            <input id="head_img" name="head_img" type="hidden" value="{{$info->head_img??''}}">
                        </div>
                        @if ($errors->has('head_img'))
                            <span class="help-block">
                            <strong>{{ $errors->first('head_img','请上传头像') }}</strong>
                        </span>
                        @endif
                    </div>

                    @if(empty($info->id))
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 密码 </label>
                            <div class="col-sm-9">
                                <input type="password" name="password" value="" class="col-xs-10 col-sm-8">
                            </div>
                        </div>
                    @endif
                    @if(isset($info->id) && ($login_user['shop_id']==1))
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 所属店铺 </label>
                            <div class="col-sm-9">
                                <input type="text" name="shop_id" value="{{$info->btShop->name??''}}" readonly class="col-xs-10 col-sm-8">
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
    <script>
        $(function(){
            var imgs_string = "{{ isset($info->id)?$info->head_img:'' }}";
            var imgs = [];
            var configs = [];
            if(imgs_string){
                imgs = imgs_string.split(';');
                for(var i=0;i<imgs.length;i++){
                    configs.push({
                        caption: "",
                        width: "120px",
                        // url: imgs[i],
                        key: imgs[i],
                        extra: {id: "{{ isset($info->id)?$info->id:'' }}"}
                    })
                }
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#upload_img").fileinput({
                language: 'zh', //设置语言
                uploadUrl: "/admin/common/uploadFiles", //上传的地址
                deleteUrl: "/admin/common/deleteFiles",
                allowedFileExtensions: ['jpg', 'jpeg', 'gif', 'png'],//接收的文件后缀
                browseLabel: '选择文件',
                removeLabel: '删除文件',
                removeTitle: '删除选中文件',
                cancelLabel: '取消',
                cancelTitle: '取消上传',
                uploadLabel: '上传',
                uploadTitle: '上传选中文件',
                dropZoneTitle: "请通过拖拽图片文件放到这里",
                dropZoneClickTitle: "或者点击此区域添加图片",
                uploadAsync: true, //默认异步上传
                showUpload: true, //是否显示上传按钮
                showRemove: true, //显示移除按钮
                showPreview: true, //是否显示预览
                showCaption: false,//是否显示标题
                browseClass: "btn btn-primary", //按钮样式
                dropZoneEnabled: false,//是否显示拖拽区域
                //minImageWidth: 50, //图片的最小宽度
                //minImageHeight: 50,//图片的最小高度
                //maxImageWidth: 1000,//图片的最大宽度
                //maxImageHeight: 1000,//图片的最大高度
                maxFileSize: 2048,//单位为kb，如果为0表示不限制文件大小
                //minFileCount: 0,
                maxFileCount: 1, //表示允许同时上传的最大文件个数
                enctype: 'multipart/form-data',
                validateInitialCount: true,
                previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
                msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
                //allowedFileTypes: ['image', 'video', 'flash'],

                initialPreviewAsData: true,
                initialPreview: imgs,
                initialPreviewConfig: configs,
                slugCallback: function (filename) {
                    return filename.replace('(', '_').replace(']', '_');
                }
            }).on("filebatchselected", function (event, files) {
                $(this).fileinput("upload");
            }).on("fileuploaded", function (event, data) {
                if (data.response) {
                    var picdir = data.response;
                    console.log(picdir);
                    $("#head_img").val(picdir);
                }
            }).on("fileerror",function(event,data){
                console.log(data,'fileerror');
            });
        })
    </script>
@endsection
