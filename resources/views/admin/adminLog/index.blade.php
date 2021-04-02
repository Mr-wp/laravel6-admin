@extends("admin.main")
@section("content")

    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$menu_info['name']}}
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
                            <div class="input-group-addon">管理员</div>
                            <input class="form-control" name="admin_name" type="text" value="{{request('admin_name')}}">
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">ip地址</div>
                            <input class="form-control" name="ip" type="text" value="{{request('ip')}}">
                        </div>

                        <div class="input-group">
                            <input type="submit" value="搜索" class="btn btn-danger btn-sm">
                            &nbsp;&nbsp;&nbsp;
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
                                <th class="center" width="50px">
                                    <label class="pos-rel">
                                        <input type="checkbox" class="ace">
                                        <span class="lbl"> </span>
                                    </label>
                                </th>
                                <th>日期</th>
                                <th>菜单名称</th>
                                <th>请求地址</th>
                                <th>ip地址</th>
                                <th>操作人</th>
                                <th>查看</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($lists as $info)
                                <tr>
                                    <td class="center">
                                        <label class="pos-rel">
                                            <input type="checkbox" class="ace " value="{{$info->id}}" name="logId">
                                            <span class="lbl"> </span>
                                        </label>
                                    </td>
                                    <td>{{$info->created_at}}</td>
                                    <td>{{$info->menu_name}}</td>
                                    <td>{{$info->c}}/{{$info->a}}{{!empty($info->querystring)?'?'.$info->querystring:''}}</td>
                                    <td>{{$info->ip}}</td>
                                    <td>{{$info->admin_name}}</td>

                                    <td>
                                        <div class="hidden-sm hidden-xs btn-group">
                                            <a href="info?id={{$info->id}}">查看</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="1" class="ui-pg-button ui-corner-all" title="删除选中" id="del_grid-table">
                                    <div class="ui-pg-div">
                                        <span class="ui-icon ace-icon fa fa-trash-o red"></span>
                                    </div>
                                </td>
                                <td colspan="6">总数：{{$lists->total()}}</td>
                            </tr>
                            </tfoot>
                            </tfoot>
                        </table>
                        <div id="page">{{$lists->appends(request()->all())->links()}}</div>
                    </div><!-- /.span -->

                </div><!-- /.row -->
                <!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div>
    </div>
    <script>
        $(function () {
            // 初始化 日期范围选择器
            var start = '{!! $start_time !!}';
            var end = '{!! $end_time !!}';
            initDateRangePicker(start, end, 'reportrange');


            var active_class = 'active';
            $('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function () {
                var th_checked = this.checked;//checkbox inside "TH" table header

                $(this).closest('table').find('tbody > tr').each(function () {
                    var row = this;
                    if (th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
                    else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
                });
            });


            $("#del_grid-table").on("click", function () {
                var logIds = "";
                $("#simple-table input:checkbox[name=logId]:checked").each(function (i) {
                    if (0 == i) {
                        logIds = $(this).val();
                    } else {
                        logIds += ("," + $(this).val());
                    }
                });
                if (logIds != "") {
                    //询问是否要删除先中
                    var r = confirm("确认将选中的数据 [" + logIds + "] 全部删除吗??");
                    if (r == true) {
                        $.ajax({
                            type: "get",
                            url: "del?ids="+logIds,
                            dataType: "json",
                            success: function (result) {
                                if (result.code == 1) {
                                    alert(result.msg);
                                    location.reload();
                                } else {
                                    alert(result.msg);
                                }
                            }
                        })
                    }
                }
            })
        })
    </script>
@endsection
