import request from '@/utils/request'

// 用户列表
export function user(data) {
  return request({
    url: '/user',
    method: 'get',
    data
  })
}
// 健康管家列表
export function housekeeper() {
  return request({
    url: '/housekeeper',
    method: 'get'
  })
}
// 分配管家
export function allocationHousekeeper(data) {
  return request({
    url: '/allocationHousekeeper/'+data.id,
    method: 'put',
    data
  })
}

