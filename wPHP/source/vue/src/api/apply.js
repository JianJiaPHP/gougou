import request from '@/utils/request'

export function index(data) {
  return request({
    url: '/index/index',
    method: 'get',
    data
  })
}

export function delete_remove(data) {
  //申请——移除
  return request({
    url: '/index/delete_remove',
    method: 'put',
    data
  })
}

export function updateStatus(id,status) {
  //申请——改变状态
  return request({
    url: '/index/updateStatus/'+id+'/'+status,
    method: 'patch',
  })
}
export function updateSubmit(data) {
  //申请——更新预约
  return request({
    url: '/index/updateSubmit/'+data.id,
    method: 'patch',
    data
  })
}
export function createSubmit(data) {
  //申请——更新预约
  return request({
    url: '/index/createSubmit',
    method: 'post',
    data
  })
}
