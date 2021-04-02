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
                            <div class="input-group-addon">状态</div>
                            {!! form_select(\App\Models\Tables::$typeArr,request('type'),'class="form-control" name="type"','--请选择--') !!}
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
                                <th>id</th>
                                <th>餐桌名</th>
                                <th>餐桌编号</th>
                                <th>类型</th>
                                <th>状态</th>
                                <th>创建用户</th>
                                <th>创建时间</th>
                                <th>修改时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($lists as $info)
                                <tr>
                                    <td>{{$info->id}}</td>
                                    <td>{{$info->name}}</td>
                                    <td>{{$info->num_id}}</td>
                                    <td>{{\App\Models\Tables::$typeArr[$info->type]}}</td>
                                    <td>{{$info->is_show?'显示':'隐藏'}}</td>
                                    <td>{{$info->btAdminUser->realname}}</td>
                                    <td>{{$info->c_time}}</td>
                                    <td>{{$info->u_time}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="info?id={{$info->id}}">编辑</a>
                                            @if($info->id>=10)<a href="del?id={{$info->id}}">删除</a>@endif
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
            })
        });
    </script>

@endsection
