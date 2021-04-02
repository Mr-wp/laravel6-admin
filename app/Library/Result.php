<?php
/**
 * 返回结果通用实体类
 *
 */
namespace App\Library;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Result implements Arrayable,Jsonable
{
    /**
     * 状态码
     * @var int|0
     */
    const CODE_ERROR   = -1; // 失败
    const CODE_SUCCESS = 1;  // 成功
    const CODE_LAYUI   = 0;  // layui默认状态码
    const CODE_MQ_SUCCESS = 200;  // 成功
    const CODE_PARAMS_ERROR = 301; //参数错误

    /**
     * 默认返回结构体
     * @var array
     */
    protected $resultArr = [
        'code'  => self::CODE_LAYUI,
        'msg'   => '',
        'data'  => '',
        'count' => 0
    ];

    public function __construct()
    {
        //$this->_code = - 1;
    }

    /**
     * 设置状态码
     *
     * @param int|0 $code
     * @return Result
     */
    public function setCode($code)
    {
        $this->resultArr['code'] = $code;
        return $this;
    }

    /**
     * 获取状态码
     *
     * @return int|0
     */
    public function getCode()
    {
        return $this->resultArr['code'];
    }

    /**
     * 设置返回消息
     *
     * @param string $msg
     * @return Result
     */
    public function setMsg($msg)
    {
        $this->resultArr['msg'] = $msg;
        return $this;
    }

    /**
     * 获取消息
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->resultArr['msg'] ;
    }

    /**
     * 设置返回数据
     *
     * @param array|string $data
     * @return Result
     */
    public function setData($data)
    {
        $this->resultArr['data'] = $data;
        return $this;
    }

    /**
     * 获取返回的数据
     *
     * @return mixed
     */
    public function getData()
    {
        return  $this->resultArr['data'];
    }

    /**
     * 设置count
     *
     * @param int|0 $count
     * @return Result
     */
    public function setCount($count)
    {
        $this->resultArr['count'] = $count;
        return $this;
    }

    /**
     * 获取count
     *
     * @return int|0
     */
    public function getCount()
    {
        return $this->resultArr['count'];
    }

    /**
     * 返回数组
     *
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Support\Arrayable::toArray()
     */
    public function toArray()
    {
        return  $this->resultArr;
    }

    /**
     * 返回json
     *
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Support\Jsonable::toJson()
     */
    public function toJson($option = 0)
    {
        return json_encode($this->resultArr,$option);
    }

    /**
     * 判断结果是否成功
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getCode() == self::CODE_SUCCESS;
    }

}
