import Vue from 'vue'
import _ from 'lodash'

export default {
  namespaced: true,
  state: {
    comments: [],
    lastPage: 0,
    comment: null
  },
  mutations: {
    setComments (state, data) {
      state.comments = data
    },
    setLastPage (state, data) {
      state.lastPage = data
    },
    setComment (state, data) {
      state.comment = data
    },
    setParsedText (state, data) {
      state.parsedText = data
    }
  },
  actions: {
    async index (context, params = {}) {
      const res = await Vue.http.get(`/posts/${params.post_id}/comments`, { params })
      if (_.has(params, 'page')) {
        this.commit('comments/setLastPage', res.data.meta.last_page)
        this.commit('comments/setComments', res.data.data)
      } else {
        this.commit('comments/setComments', res.data)
      }
    },
    async delete (context, params = {}) {
      await Vue.http.delete(`/posts/${params.post_id}/comments/${params.id}`)
      this.commit('comments/setComment', null)
    }
  }
}
