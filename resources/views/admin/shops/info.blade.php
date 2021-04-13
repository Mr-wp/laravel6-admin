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
                <form class="form-horizontal" role="form" method="POST" action="{{isset($info->id)?'edit':'add'}}" onsubmit="doSubmit();">
                    {{csrf_field()}}
                    @if(isset($info->id))
                        <input type="hidden" name="id" value="{{$info->id??''}}"/>
                    @endif
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <h3>基本信息:</h3> </label>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span style="color: red;">*</span>店铺名称 </label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{$info->name??''}}" {{(isset($info->id) && $info->id<2)?'disabled':''}} class="col-xs-8">
                        </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span style="color: red;">*</span>持有人 </label>
                        <div class="col-sm-9">
                            <input type="text" name="owner_name" value="{{$info->owner_name??''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span style="color: red;">*</span>拥有人电话 </label>
                        <div class="col-sm-9">
                            <input type="text" name="owner_phone" value="{{$info->owner_phone??''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 联系人 </label>
                        <div class="col-sm-9">
                            <input type="text" name="contact_name" value="{{$info->contact_name??''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 联系人电话 </label>
                        <div class="col-sm-9">
                            <input type="text" name="contact_phone" value="{{$info->contact_phone??''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span style="color: red;">*</span>地区 </label>
                        <div class="col-sm-4">
                            <input type="hidden" name="address" id="address" value="{{$info->address??''}}" class="col-xs-10 col-sm-8">
                            <select name="fang_province" id="fang_province" style="width: 100px;" onchange="selectCity(this,'fang_city')">
                                <option value="0">==请选择省==</option>
                                @foreach($provinces as $item)
                                    <option value="{{ $item->base_areaid }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <select name="fang_city" id="fang_city" style="width: 100px;" onchange="selectCity(this,'fang_region')">
                                <option value="0">==市==</option>
                                @if($citys)
                                    @foreach($citys as $item)
                                        <option value="{{ $item->base_areaid }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif;
                            </select>
                            <select name="fang_region" id="fang_region" style="width: 100px;">
                                <option value="0">==区/县==</option>
                                @if($regions)
                                    @foreach($regions as $item)
                                        <option value="{{ $item->base_areaid }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif;
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span style="color: red;">*</span>详细地址 </label>
                        <div class="col-sm-9">
                            <input type="text" name="address_detail" value="{{$info->address_detail??''}}" class="col-xs-10 col-sm-8">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> <span style="color: red;">*</span>店铺类别 </label>
                        <div class="col-sm-3">
                            {!! form_select(\App\Models\Shops::$types,$info->type??0,'class="form-control" name="type" class="col-xs-10 col-sm-8"','--请选择--') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> 是否启用 </label>
                        <div class="col-sm-9">
                            {!! form_radio([0=>'启用',1=>'停用'],$info->status??'','name="status"') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('auth_imgs') ? ' has-error' : '' }}">
                        <label class="col-sm-3 control-label no-padding-right"> <span style="color: red;">*</span>店铺资质 </label>
                        <div class="col-sm-6">
                            <input id="auth_imgs_file" name="auth_imgs_file" multiple data-overwrite-initial="false"  type="file" value="">
                            <input id="auth_imgs" name="auth_imgs" type="hidden" value="{{$info->auth_imgs??''}}">
                            <input id="auth_imgs_del" name="auth_imgs_del" type="hidden" value="">
                        </div>
                        @if ($errors->has('auth_imgs'))
                        <span class="help-block">
                            <strong>{{ $errors->first('auth_imgs','请上传您的店铺资质图片') }}</strong>
                        </span>
                        @endif
                    </div>

                    @if(isset($info->id))
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> <h3>其他信息:</h3> </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 审核状态 </label>
                            <div class="col-sm-3">
                                {!! form_select(\App\Models\Shops::$verifyStatus,$info->verify_status??0,'class="form-control" readonly name="type" class="col-xs-10 col-sm-8"','--请选择--') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 注册时间 </label>
                            <div class="col-sm-9">
                                <input type="text" name="created_at" readonly value="{{$info->created_at??''}}" class="col-xs-10 col-sm-8">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 是否已试用 </label>
                            <div class="col-sm-9">
                                {!! form_radio([0=>'否',1=>'是'],$info->ex_status??'','name="ex_status"') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 试用日期 </label>
                            <div class="col-sm-4">
                                <input type="text" name="ex_start_time" readonly value="{{$info->ex_start_time?date('Y-m-d H:i:s',$info->ex_start_time):''}}" class="col-xs-10 col-sm-4">
                                <span class="col-xs-10 col-sm-1" style="border: 1px solid gray;margin: 16px 5px;width: 5px;padding: 0 5px;"></span>
                                <input type="text" name="ex_end_time" readonly value="{{$info->ex_end_time?date('Y-m-d H:i:s',$info->ex_end_time):''}}" class="col-xs-10 col-sm-4">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> <h3>支付配置:</h3> </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 支付宝应用ID </label>
                            <div class="col-sm-9">
                                <input type="text" name="ali_appid" value="{{$info->ali_appid??''}}" class="col-xs-10 col-sm-4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 支付宝密钥 </label>
                            <div class="col-sm-9">
                                <input type="text" name="ali_private_key" value="{{$info->ali_private_key??''}}" class="col-xs-10 col-sm-4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 支付宝公钥 </label>
                            <div class="col-sm-9">
                                <input type="text" name="ali_public_key" value="{{$info->ali_public_key??''}}" class="col-xs-10 col-sm-4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 微信商户号 </label>
                            <div class="col-sm-9">
                                <input type="text" name="wx_mch_id" value="{{$info->wx_mch_id??''}}" class="col-xs-10 col-sm-4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 微信应用ID </label>
                            <div class="col-sm-9">
                                <input type="text" name="wx_appid" value="{{$info->wx_appid??''}}" class="col-xs-10 col-sm-4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"> 微信密钥 </label>
                            <div class="col-sm-9">
                                <input type="text" name="wx_secret" value="{{$info->wx_secret??''}}" class="col-xs-10 col-sm-4">
                            </div>
                        </div>
                    @endif

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
        function doSubmit(){
            var province = $('#fang_province').val();
            var city = $('#fang_city').val();
            var region = $('#fang_region').val();
            $('#address').val(province+','+city+','+region);
            return true;
        }
        function selectCity(obj, selectName) {
            let value = $(obj).val();
            // 以省份ID得到市 发起ajax请求
            $.get('{{ url('admin/areas/city') }}', {cid: value}).then(jsonArr => {
                let html = '<option value="0">==市==</option>';
                // 循环 map  for  for in    for of    $.each
                jsonArr.map(item => {
                    var {base_areaid, name} = item;
                    html += `<option value="${base_areaid}">${name}</option>`;
                });
                $('#' + selectName).html(html);
            });
        }
        $(function(){
            var areas = "{{ isset($info->id)?$info->address:'' }}";
            if(areas){
                var area_arr = areas.split(',');
                $('#fang_province').val(area_arr[0]);
                $('#fang_city').val(area_arr[1]);
                $('#fang_region').val(area_arr[2]);
            }
            var imgs_string = "{{ isset($info->id)?$info->auth_imgs:'' }}";
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
                $('#fang_province').val(area_arr[0]);
                $('#fang_city').val(area_arr[1]);
                $('#fang_region').val(area_arr[2]);
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#auth_imgs_file").fileinput({
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
                maxFileCount: 6, //表示允许同时上传的最大文件个数
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
                    var imgs = $("#auth_imgs").val();
                    if(imgs.length>0){
                        $("#auth_imgs").val(imgs+';'+picdir);
                    }else{
                        $("#auth_imgs").val(picdir);
                    }
                }
            }).on("fileerror",function(event,data){
                console.log(data,'fileerror');
            }).on('filepredelete', function(event, key) {
                var auth_imgs_del = $("#auth_imgs_del").val();
                if(auth_imgs_del){
                    $("#auth_imgs_del").val(auth_imgs_del + ";" + key);
                }else{
                    $("#auth_imgs_del").val(key);
                }
            });
        });
    </script>

@endsection
