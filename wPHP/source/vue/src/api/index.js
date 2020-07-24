import request from '@/utils/request'

export function index_frist(data) {
  return request({
    url: '/index/index_frist',
    method: 'get',
    data
  })
}



