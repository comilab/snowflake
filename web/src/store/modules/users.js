import Vue from 'vue'
import _ from 'lodash'

export default {
  namespaced: true,
  state: {
    users: [],
    lastPage: 0,
    user: null
  },
  mutations: {
    setUsers (state, data) {
      state.users = data
    },
    setLastPage (state, data) {
      state.lastPage = data
    },
    setUser (state, data) {
      state.user = data
    }
  },
  actions: {
    async index (context, params = {}) {
      this.commit('users/setUsers', [])
      this.commit('users/setLastPage', 0)

      const res = await Vue.http.get('/users', { params })
      if (_.has(params, 'page')) {
        this.commit('users/setLastPage', res.data.meta.last_page)
        this.commit('users/setUsers', res.data.data)
      } else {
        this.commit('users/setUsers', res.data)
      }
    },
    async create (context, params = {}) {
      const res = await Vue.http.post('/users', params)
      this.commit('users/setUser', res.data)
    },
    async show (context, params = {}) {
      this.commit('users/setUser', null)

      const res = await Vue.http.get(`/users/${params.id}`, params)
      this.commit('users/setUser', res.data)
    },
    async update (context, params = {}) {
      const res = await Vue.http.put(`/users/${params.id}`, params)
      this.commit('users/setUser', res.data)
    },
    async delete (context, params = {}) {
      await Vue.http.delete(`/users/${params.id}`)
      this.commit('users/setUser', null)
    }
  }
}
