import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'
// import Administrators from '@/views/permission/administrators'
const Administrators = () => import('@/views/permission/administrators')
const Permissions = () => import('@/views/permission/permission')
const Config = () => import('@/views/system/config')
const OperatingLog = () => import('@/views/system/operatingLog')
const LoginLog = () => import('@/views/system/loginLog')

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
    affix: true                  if set true, the tag will affix in the tags-view
    noCache: true                if set true, the page will no be cached(default is false)
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        component: () => import('@/views/redirect/index')
      }
    ]
  },
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },

  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [{
      path: 'dashboard',
      name: 'Dashboard',
      component: () => import('@/views/dashboard/index'),
      meta: { title: '首页', icon: 'dashboard', affix: true }
    }]
  }

  // {
  //   path: '/permission',
  //   component: Layout,
  //   redirect: '/permission/administrators',
  //   name: 'permission',
  //   meta: {
  //     title: '权限管理',
  //     icon: 'nested'
  //   },
  //   children: [
  //     {
  //       path: '/administrators',
  //       // component: () => import('@/views/permission/administrators'), // Parent router-view
  //       component: Administrators,
  //       name: 'administrators',
  //       meta: { title: '管理员' }
  //     },
  //     {
  //       path: '/permissions',
  //       name: 'permissions',
  //       component: Permissions,
  //       meta: { title: '权限' }
  //     },
  //     {
  //       path: '/role',
  //       name: 'role',
  //       component: () => import('@/views/permission/role'), // Parent router-view
  //       meta: { title: '权限' }
  //     }
  //   ]
  // }
  // {
  //   path: '/system',
  //   component: Layout,
  //   redirect: '/system/config',
  //   name: 'system',
  //   meta: {
  //     title: '系统管理',
  //     icon: 'system'
  //   },
  //   children: [
  //     {
  //       path: '/config',
  //       name: 'config',
  //       component: Config, // Parent router-view
  //       meta: { title: '配置管理' }
  //     },
  //     {
  //       path: '/operating_log',
  //       name: 'operating_log',
  //       component: OperatingLog,
  //       meta: { title: '操作日志' }
  //     },
  //     {
  //       path: '/login_log',
  //       name: 'login_log',
  //       component: LoginLog,
  //       meta: { title: '登陆日志' }
  //     }
  //   ]
  // },

  // 404 page must be placed at the end !!!
  // { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
