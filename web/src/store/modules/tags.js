import Vue from 'vue'

export default {
  namespaced: true,
  state: {
    tags: [],
    tag: null
  },
  mutations: {
    setTags (state, data) {
      state.tags = data
    },
    setTag (state, data) {
      state.tag = data
    }
  },
  actions: {
    async index (context, params = {}) {
      const res = await Vue.http.get('/tags', { params })
      this.commit('tags/setTags', res.data)
    },
    async create (context, params = {}) {
      const res = await Vue.http.post('/tags', params)
      this.commit('tags/setTag', res.data)
    },
    async show (context, params = {}) {
      this.commit('tags/setTag', null)
      const res = await Vue.http.get(`/tags/${params.id}`, params)
      this.commit('tags/setTag', res.data)
    },
    async update (context, params = {}) {
      const res = await Vue.http.put(`/tags/${params.id}`, params)
      this.commit('tags/setTag', res.data)
    },
    async delete (context, params = {}) {
      await Vue.http.delete(`/tags/${params.id}`)
      this.commit('tags/setTag', null)
    }
  }
}
