import Vue from 'vue'
import Vuex from 'vuex'
import progressbar from './modules/progressbar'
import snackbar from './modules/snackbar'
import leaveDialog from './modules/leaveDialog'
import sessions from './modules/sessions'
import posts from './modules/posts'
import comments from './modules/comments'
import pages from './modules/pages'
import tags from './modules/tags'
import images from './modules/images'
import uploads from './modules/uploads'
import users from './modules/users'
import settings from './modules/settings'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    progressbar,
    snackbar,
    leaveDialog,
    sessions,
    posts,
    comments,
    pages,
    tags,
    images,
    uploads,
    users,
    settings
  },
  strict: true
})
