import Vue from 'vue'
import _ from 'lodash'

export default {
  namespaced: true,
  state: {
    uploads: [],
    lastPage: 0,
    upload: null
  },
  mutations: {
    setUploads (state, data) {
      state.uploads = data
    },
    setLastPage (state, data) {
      state.lastPage = data
    },
    setUpload (state, data) {
      state.upload = data
    }
  },
  actions: {
    async index (context, params = {}) {
      const res = await Vue.http.get('/uploads', { params })
      if (_.has(params, 'page')) {
        this.commit('uploads/setLastPage', res.data.meta.last_page)
        this.commit('uploads/setUploads', res.data.data)
      } else {
        this.commit('uploads/setUploads', res.data)
      }
    },
    async create (context, params = {}) {
      const token = (`${document.cookie};`.match(/XSRF-TOKEN=(.+?);/) || [])[1]
      const res = await Vue.http.post('/uploads', params, {
        headers: {
          'Content-Type': 'multipart/form-data',
          'X-XSRF-TOKEN': decodeURIComponent(token)
        }
      })
      this.commit('uploads/setUpload', res.data)
    },
    async show (context, params = {}) {
      this.commit('uploads/setUpload', null)
      const res = await Vue.http.get(`/uploads/${params.id}`, params)
      this.commit('uploads/setUpload', res.data)
    },
    async update (context, params = {}) {
      const res = await Vue.http.put(`/uploads/${params.id}`, params)
      this.commit('uploads/setUpload', res.data)
    },
    async delete (context, params = {}) {
      await Vue.http.delete(`/uploads/${params.id}`)
      this.commit('uploads/setUpload', null)
    }
  }
}
