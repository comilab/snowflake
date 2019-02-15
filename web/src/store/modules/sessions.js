import Vue from 'vue'

export default {
  namespaced: true,
  state: {
    self: null
  },
  getters: {
    isLogin: state => !!state.self,
    isAdmin: state => state.self && state.self.role === 'admin'
  },
  mutations: {
    set (state, data) {
      state.self = data
    }
  },
  actions: {
    async create (context, params = {}) {
      const res = await Vue.http.post('/sessions', params)
      this.commit('sessions/set', res.data)
    },
    async show () {
      this.commit('sessions/set', null)

      const res = await Vue.http.get('/sessions')
      this.commit('sessions/set', res.data)
    },
    async delete () {
      await Vue.http.delete('/sessions')
      this.commit('sessions/set', null)
    }
  }
}
