import request from '@/utils/request'

export function getToken() {
  return request({
    url: '/qiniu/upload/token', // 假地址 自行替换
    method: 'get'
  })
}

export function getNav() {
  return request({
    url: '/me/get_nav',
    method: 'get'
  })
}
