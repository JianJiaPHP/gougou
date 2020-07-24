import request from '@/utils/request'

export function index(data) {
  return request({
    url: '/boss/index',
    method: 'get',
    data
  })
}
export function submit(data) {
  return request({
    url: '/boss/submit/'+data.id,
    method: 'patch',
    data
  })
}


