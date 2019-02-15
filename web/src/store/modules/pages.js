import Vue from 'vue'
import _ from 'lodash'

export default {
  namespaced: true,
  state: {
    pages: [],
    lastPage: 0,
    page: null,
    parsedText: ''
  },
  mutations: {
    setPages (state, data) {
      state.pages = data
    },
    setLastPage (state, data) {
      state.lastPage = data
    },
    setPage (state, data) {
      state.page = data
    },
    setParsedText (state, data) {
      state.parsedText = data
    }
  },
  actions: {
    async index (context, params = {}) {
      const res = await Vue.http.get('/pages', { params })
      if (_.has(params, 'page')) {
        this.commit('pages/setLastPage', res.data.meta.last_page)
        this.commit('pages/setPages', res.data.data)
      } else {
        this.commit('pages/setPages', res.data)
      }
    },
    async create (context, params = {}) {
      const res = await Vue.http.post('/pages', params)
      this.commit('pages/setPage', res.data)
    },
    async show (context, params = {}) {
      this.commit('pages/setPage', null)
      const res = await Vue.http.get(`/pages/${params.id}`, params)
      this.commit('pages/setPage', res.data)
    },
    async update (context, params = {}) {
      const res = await Vue.http.put(`/pages/${params.id}`, params)
      this.commit('pages/setPage', res.data)
    },
    async delete (context, params = {}) {
      await Vue.http.delete(`/pages/${params.id}`)
      this.commit('pages/setPage', null)
    },
    async parse (context, params = '') {
      const res = await Vue.http.page('/pages/parse', {text: params})
      this.commit('pages/setParsedText', res.data)
    }
  }
}
