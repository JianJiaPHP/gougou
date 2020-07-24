import request from '@/utils/request'

export function index(data) {
  return request({
    url: '/data/index',
    method: 'get',
    data
  })
}
export function optimization(data) {
  return request({
    url: '/data/optimize',
    method: 'get',
    data
  })
}
export function repair(data) {
  return request({
    url: '/data/repair',
    method: 'get',
    data
  })
}
export function backupssql(data) {
  return backupssql({
    url: '/data/backups',
    method: 'get',
    data
  })
}


