@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info['name']}}
                <span class="btn btn-sm btn-primary pull-right" onclick="javascript:window.location.href = 'info'">
		        添加
                </span>
            </h1>
        </div>
        <div class="operate panel panel-default">
            <div class="panel-body ">
                <form name="myform" id="myform" method="GET" class="form-inline">
                    <div class="form-group select-input">
                        <div class="input-group">
                            <div class="input-group-addon">名称</div>
                            <input class="form-control" name="name" type="text" value="{{request('name')??''}}">
                        </div>

                        <div class="input-group">
                            <div class="input-group-addon">店铺类型</div>
                            {!! form_select(\App\Models\Shops::$types,request('type'),'class="form-control" name="type"','--请选择--') !!}
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">审核状态</div>
                            {!! form_select(\App\Models\Shops::$verifyStatus,request('verify_status'),'class="form-control" name="verify_status"','--请选择--') !!}
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">使用状态</div>
                            {!! form_select(\App\Models\Shops::$status,request('status'),'class="form-control" name="status"','--请选择--') !!}
                        </div>
                        <div class="input-group">
                            <input type="submit" value="搜索" class="btn btn-danger btn-sm">
                            <span class="btn btn-info btn-sm" onclick="window.location.href = '?'">重置</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="row">
                    <div class="col-xs-12">
                        <table id="simple-table" class="table  table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>店铺名称</th>
                                <th>法人</th>
                                <th>法人电话</th>
                                <th>地区</th>
                                <th>详细地址</th>
                                <th>审核状态</th>
                                <th>使用状态</th>
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($lists as $info)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$info->name}}</td>
                                    <td>{{$info->owner_name}}</td>
                                    <td>{{$info->owner_phone}}</td>
                                    <td>{{$info->address}}</td>
                                    <td>{{$info->address_detail}}</td>
                                    <td>{{\App\Models\Shops::$verifyStatus[$info->type]}}</td>
                                    <td>{{$info->status?'停用':'启用'}}</td>
                                    <td>{{$info->created_at??''}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="verify?id={{$info->id}}">审核</a>
                                            <a href="info?id={{$info->id}}">编辑</a>
                                            <a href="del?id={{$info->id}}">删除</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="12">总数：{{$lists->total()}}</td>
                            </tr>
                            </tfoot>
                        </table>
                        <div id="page" style="height: 77px;">{{$lists->appends(request()->all())->links()}}
                            @if($lists->total()>$lists->perPage())
                            <select class=" input-sm" name="pageSize" id="perPage" style="float: right;margin-top: -56px;height: 32px;line-height: 32px;background-color: #337ab7;color: #fff;">
                                <option value="5" {{ $lists->perPage()==5?'selected':'' }}>5</option>
                                <option value="10" {{ $lists->perPage()==10?'selected':'' }}>10</option>
                                <option value="15" {{ $lists->perPage()==15?'selected':'' }}>15</option>
                                <option value="20" {{ $lists->perPage()==20?'selected':'' }}>20</option>
                            </select>
                            @endif
                        </div>
                    </div><!-- /.span -->

                </div><!-- /.row -->
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            // 每页显示条数
            $('#perPage').change(function(){
                var per_page = $(this).val();
                $('#myform').append("<input type='hidden' name='pageSize' value='"+per_page+"'/>");
                $('#myform').submit();
            });
            $.getJSON('json/word.json', function(word) {
                console.log(word);
                // var word = JSON.parse(JSON.stringify(word));
                // var x = word.word

                // for (var i = 0 ;i<x.length;i++){
                //     var zf = word.word[i]
                //     var t = st.indexOf(zf);
                //     if (t != -1){
                //         s = s + " " + zf
                //     }
                // }
                // console.log(word);
            });
        });
    </script>

@endsection
