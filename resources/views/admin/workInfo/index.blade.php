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
                <form name="myform" method="GET" class="form-inline">

                    <div class="form-group select-input">
                        <div class="input-group" id="id_search_date">
                            <span>按时间查询：</span>
                            <span class="add-on input-group-addon">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar" style="font-size: 18px"></i>
                            </span>
                            <input type="text" readonly style="width:220px" name="reportrange" id="reportrange" class="form-control" value=""/>
                        </div>

                        <div class="input-group">
                            <div class="input-group-addon">是否需要提醒</div>
                            {!! form_select(\App\Models\WorkInfo::$reminderStatusArr,request('status'),'class="form-control" name="status"','--请选择--') !!}
                        </div>

                        <div class="input-group">
                            <div class="input-group-addon">状态</div>
                            {!! form_select(\App\Models\WorkInfo::$isReminderArr,request('is_reminder'),'class="form-control" name="is_reminder"','--请选择--') !!}
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
                                <th>提醒时间</th>
                                <th>是否需提醒</th>
                                <th>用户</th>
                                <th>内容</th>
                                <th>是否已提醒</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($lists as $info)
                                <tr>
                                    <td>{{date('Y-m-d H:i:s',$info->reminder_at)}}</td>
                                    <td>{{\App\Models\WorkInfo::$reminderStatusArr[$info->reminder_status]}}</td>
                                    <td>{{$info->btAdminUser->realname}}</td>
                                    <td>{{$info->content??''}}</td>
                                    <td>{{\App\Models\WorkInfo::$isReminderArr[$info->is_reminder]}}</td>
                                    <td>
                                        <a href="info?id={{$info->id}}">编辑</a>
                                        <a href="del?id={{$info->id}}">删除</a>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.span -->

                </div><!-- /.row -->
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div>

    </div>
    <script>
        // 初始化 日期范围选择器
        var start = '{!! $start_time !!}';
        var end = '{!! $end_time !!}';
        initDateRangePicker(start, end, 'reportrange');
    </script>

@endsection
