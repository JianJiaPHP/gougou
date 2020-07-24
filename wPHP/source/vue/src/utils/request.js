import axios from 'axios'
import { Notification } from 'element-ui'
import store from '@/store'
import QS from 'qs'
import { getToken, removeToken } from '@/utils/auth'
import { CalcuMD5 } from '@/utils'
import da from 'element-ui/src/locale/lang/da'
import router, { resetRouter } from '@/router/index'

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 50000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent
    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      config.headers['Authorization'] = 'Bearer ' + getToken()
    }
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
  */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    return response.data
  },
  error => {
    const res = error.response.data
    const status = error.response.status

    switch (status) {
      case 500:
        if (res.message) {
          Notification.error({
            message: res.message
          })
        }
        return Promise.reject(new Error(res.message || 'Error'))
      case 401:
        store.commit('SET_TOKEN', '')
        store.commit('SET_NAME', '')
        removeToken()
        resetRouter()
        router.replace({
          path: `/redirect/login?redirect=${router.currentRoute.fullPath}`
        })
        return Promise.reject(error)
      case 422:
        if (res.message) {
          Notification.error({
            message: res.message
          })
        }
        return Promise.reject(new Error(res.message || 'Error'))
      default:
        return Promise.reject(error)
    }
  }
)

/**
 * 作者：TKE
 * 时间：2019/12/17 0017
 * 功能：对外接口
 * 描述：
 */
export default function request({ url, method, data = {}}) {
  // 数据处理
  const params = data
  method = method.toUpperCase()
  if (method === 'GET') {
    addSign(params)
    return service({ url, method, params })
  } else if (method === 'POST') {
    axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8'
    addSign(params)
    const data = QS.stringify(params)
    return service({ url, method, data })
  } else if (method === 'PUT' || method === 'PATCH') {
    axios.defaults.headers.post['Content-Type'] = 'application/json;charset=UTF-8'
    addSign(params)
    const data = QS.stringify(params)
    return service({ url, method, data })
  } else if (method === 'DELETE') {
    addSign(params)
    return service({ url, method, params })
  }
}

function addSign(params) {
  const signs = CalcuMD5(params)
  axios.defaults.headers['Yw-Sign'] = signs.sign
  axios.defaults.headers['Yw-Time'] = signs.time
}
