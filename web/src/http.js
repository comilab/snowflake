import axios from 'axios'

const API_URI = process.env.API_URI

const http = axios.create({
  baseURL: API_URI
})

export default (Vue, { store }) => {
  http.interceptors.request.use(config => {
    store.commit('progressbar/start')
    return config
  })
  http.interceptors.response.use(response => {
    store.commit('progressbar/end')
    return response
  }, error => {
    store.commit('progressbar/end')
    if (error.response.status === 401) {
      if (location.hash !== '#/login') {
        store.commit('snackbar/show', 'ログインしてください')
        store.commit('leaveDialog/disable')
        location.href = '#/login'
      }
    } else {
      store.commit('snackbar/show', 'エラーが発生しました')
    }
    return Promise.reject(error)
  })

  Vue.http = http
  Object.defineProperties(Vue.prototype, {
    $http: {
      get: () => http
    }
  })
}
