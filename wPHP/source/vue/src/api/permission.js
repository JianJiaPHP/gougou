import request from '@/utils/request'

// 管理员列表
export function administrators(data) {
  return request({
    url: '/administrators',
    method: 'get',
    data
  })
}
// 管理员添加
export function administratorsStore(data) {
  return request({
    url: 'administrators',
    method: 'post',
    data
  })
}
// 管理员更新
export function administratorsUpdate(data) {
  return request({
    url: 'administrators/' + data.id,
    method: 'put',
    data
  })
}
// 管理员删除
export function administratorsDel(ids) {
  return request({
    url: 'administrators/' + ids,
    method: 'delete'
  })
}
export function getInfo() {
  return request({
    url: '/me',
    method: 'get',
    data: {}
  })
}

export function logout() {
  return request({
    url: '/me/logout',
    method: 'get',
    data: {}
  })
}
// 权限交集
export function permissionsIntersection() {
  return request({
    url: 'permissions/path/list',
    method: 'get'
  })
}
// 权限父级
export function permissionsFather() {
  return request({
    url: 'permissions/father',
    method: 'get'
  })
}
// 所有路由
export function permissionsAll() {
  return request({
    url: 'permissions/all',
    method: 'get'
  })
}
// 权限列表
export function permissionsIndex(data) {
  return request({
    url: 'permissions',
    method: 'get',
    data
  })
}
// 权限添加
export function permissionsStore(data) {
  return request({
    url: 'permissions',
    method: 'post',
    data
  })
}
// 权限修改
export function permissionsUpdate(data) {
  return request({
    url: 'permissions/' + data.id,
    method: 'put',
    data
  })
}

// 权限删除
export function permissionsDel(ids) {
  return request({
    url: 'permissions/' + ids,
    method: 'delete'
  })
}

// 权限树
export function permissionsTree() {
  return request({
    url: 'permissions/tree',
    method: 'get'
  })
}
// 角色列表
export function rolesIndex(data) {
  return request({
    url: 'roles',
    method: 'get',
    data
  })
}
// 角色添加
export function rolesStore(data) {
  return request({
    url: 'roles',
    method: 'post',
    data
  })
}
// 角色更新
export function rolesUpdate(data) {
  return request({
    url: 'roles/' + data.id,
    method: 'put',
    data
  })
}
// 角色删除
export function rolesDel(ids) {
  return request({
    url: 'roles/' + ids,
    method: 'delete'
  })
}
// 所有角色
export function rolesAll() {
  return request({
    url: 'roles_all',
    method: 'get'
  })
}

