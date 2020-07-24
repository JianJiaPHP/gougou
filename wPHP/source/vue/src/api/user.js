import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/login',
    method: 'post',
    data
  })
}

export function getInfo() {
  return request({
    url: '/me',
    method: 'get'
  })
}

export function logout() {
  return request({
    url: '/me/logout',
    method: 'get',
    data: {}
  })
}

// 修改密码
export function updatePassword(data) {
  return request({
    url: '/me/update_password',
    method: 'put',
    data
  })
}
