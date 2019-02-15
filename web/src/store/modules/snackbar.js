export default {
  namespaced: true,
  state: {
    text: ''
  },
  mutations: {
    show (state, text) {
      state.text = text
    },
    close (state) {
      state.text = ''
    },
    setText (state, data) {
      state.text = data
    }
  }
}
