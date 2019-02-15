import Vue from 'vue'
import _ from 'lodash'

export default {
  namespaced: true,
  state: {
    images: [],
    total: 0,
    image: null
  },
  mutations: {
    setImages (state, data) {
      state.images = data
    },
    setTotal (state, data) {
      state.total = data
    },
    setImage (state, data) {
      state.image = data
    }
  },
  actions: {
    async index (context, params = {}) {
      const res = await Vue.http.get('/images', { params })
      if (_.has(params, 'page')) {
        this.commit('images/setTotal', res.data.total)
        this.commit('images/setImages', res.data.data)
      } else {
        this.commit('images/setImages', res.data)
      }
    },
    async create (context, params = {}) {
      const res = await Vue.http.post('/images', params, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      this.commit('images/setImage', res.data)
    },
    async show (context, params = {}) {
      this.commit('images/setImage', null)
      const res = await Vue.http.get(`/images/${params.id}`, params)
      this.commit('images/setImage', res.data)
    },
    async update (context, params = {}) {
      const res = await Vue.http.put(`/images/${params.id}`, params)
      this.commit('images/setImage', res.data)
    },
    async delete (context, params = {}) {
      await Vue.http.delete(`/images/${params.id}`)
      this.commit('images/setImage', null)
    }
  }
}
