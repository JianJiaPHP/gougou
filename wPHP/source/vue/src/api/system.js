import request from '@/utils/request'

// 操作日志列表
export function operatingLog(data) {
  return request({
    url: '/operating_log',
    method: 'get',
    data
  })
}
// 登陆日志列表
export function loginLog(data) {
  return request({
    url: '/login_log',
    method: 'get',
    data
  })
}
// 获取所有配置
export function config(data) {
  return request({
    url: '/config',
    method: 'get',
    data
  })
}
// 添加配置
export function configStore(data) {
  return request({
    url: '/config',
    method: 'post',
    data
  })
}
// 更新配置
export function configUpdate(data) {
  return request({
    url: '/config/' + data.id,
    method: 'put',
    data
  })
}

// 获取配置
export function configGet(key) {
  return request({
    url: '/get_config/' + key,
    method: 'get'
  })
}

