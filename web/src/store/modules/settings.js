import Vue from 'vue'
import _ from 'lodash'

export default {
  namespaced: true,
  state: {
    settings: null
  },
  mutations: {
    set (state, data) {
      state.settings = data
    },
    assign (state, data) {
      state.settings = _.assign({}, state.settings, data)
    }
  },
  actions: {
    async index (context, params = {}) {
      this.commit('settings/set', null)
      const res = await Vue.http.get('/settings', { params })
      this.commit('settings/set', res.data)
    },
    async create (context, params = {}) {
      const res = await Vue.http.post('/settings', params)
      this.commit('settings/set', res.data)
    }
  }
}
