export default {
  namespaced: true,
  state: {
    state: false
  },
  mutations: {
    start (state) {
      state.state = true
    },
    end (state) {
      state.state = false
    }
  }
}
