<?php


namespace App\Services;


use App\Models\Config;

class ConfigService
{
    protected $config;
    protected $data;

    public function __construct()
    {
        $this->config = new Config();
        $this->data = $this->config->getAll();
    }

    /**
     * 获取所有
     * @return mixed
     * @author Aii
     * @date 2020/1/17 上午10:21
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * 获取一条
     * @param $key
     * @return |null
     * @author Aii
     * @date 2020/1/17 上午10:20
     */
    public function getOne($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}
