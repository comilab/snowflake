export default {
  namespaced: true,
  state: {
    enabled: false,
    from: null,
    to: null,
    next: null
  },
  mutations: {
    onLeave (state, {from, to, next}) {
      state.from = from
      state.to = to
      state.next = next
    },
    enable (state) {
      state.enabled = true
    },
    disable (state) {
      state.enabled = false
    }
  }
}
