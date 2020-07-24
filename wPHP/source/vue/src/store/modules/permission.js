import { constantRoutes } from '@/router'

import { getNav } from '@/api/qiniu'

/**
 * Use meta.role to determine if the current info has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
  if (route.meta && route.meta.roles) {
    return roles.some(role => route.meta.roles.includes(role))
  } else {
    return true
  }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, roles) {
  const res = []

  routes.forEach(route => {
    const tmp = { ...route }
    if (hasPermission(roles, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, roles)
      }
      res.push(tmp)
    }
  })

  return res
}

const state = {
  routes: [],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

const actions = {
  generateRoutes({ commit }) {
    return new Promise((resolve, reject) => {
      getNav().then(async res => {
        // 数据正确后吧 accessedRoutes 改为 accessRoutes （commit 和 resolve里面的）
        const accessedRoutes = formatRoutes(res.data)
        const unfound = { path: '*', redirect: '/404', hidden: true }
        accessedRoutes.push(unfound)
        commit('SET_ROUTES', accessedRoutes)

        resolve(accessedRoutes)
      }).catch(err => {
        reject(err)
      })
    })
  }
}

export const formatRoutes = (aMenu) => {
  const aRouter = []
  if (!aMenu) {
    return
  }
  aMenu.forEach(oMenu => {
    const {
      path,
      component,
      name,
      icon,
      children,
      meta,
      hidden
    } = oMenu
    if (!validatenull(component)) {
      const oRouter = {
        path: path,
        component(resolve) {
          if (component === 'Layout') {
            return require([`../../layout`], resolve)
          } else {
            return require([`../../views/${component}.vue`], resolve)
          }
        },
        name: name,
        icon: icon,
        children: validatenull(children) ? '' : formatRoutes(children),
        meta: meta,
        hidden: hidden || false
      }
      aRouter.push(oRouter)
    }
  })
  return aRouter
}

export const validatenull = (obj) => {
  if (JSON.stringify(obj) === '{}' || JSON.stringify(obj) === '[]') {
    return true
  } else {
    return false
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
