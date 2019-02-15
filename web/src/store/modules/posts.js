import Vue from 'vue'
import _ from 'lodash'

export default {
  namespaced: true,
  state: {
    posts: [],
    lastPage: 0,
    post: null,
    parsedText: ''
  },
  mutations: {
    setPosts (state, data) {
      state.posts = data
    },
    setLastPage (state, data) {
      state.lastPage = data
    },
    setPost (state, data) {
      state.post = data
    },
    setParsedText (state, data) {
      state.parsedText = data
    }
  },
  actions: {
    async index (context, params = {}) {
      const res = await Vue.http.get('/posts', { params })
      if (_.has(params, 'page')) {
        this.commit('posts/setLastPage', res.data.meta.last_page)
        this.commit('posts/setPosts', res.data.data)
      } else {
        this.commit('posts/setPosts', res.data)
      }
    },
    async create (context, params = {}) {
      const res = await Vue.http.post('/posts', params)
      this.commit('posts/setPost', res.data)
    },
    async show (context, params = {}) {
      this.commit('posts/setPost', null)
      const res = await Vue.http.get(`/posts/${params.id}`, params)
      this.commit('posts/setPost', res.data)
    },
    async update (context, params = {}) {
      const res = await Vue.http.put(`/posts/${params.id}`, params)
      this.commit('posts/setPost', res.data)
    },
    async delete (context, params = {}) {
      await Vue.http.delete(`/posts/${params.id}`)
      this.commit('posts/setPost', null)
    },
    async parse (context, params = '') {
      const res = await Vue.http.post('/posts/parse', {text: params})
      this.commit('posts/setParsedText', res.data)
    }
  }
}
