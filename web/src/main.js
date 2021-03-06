// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import http from './http'

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

import VeeValidate from 'vee-validate'
import jaValidationMessages from 'vee-validate/dist/locale/ja'

import VueMoment from 'vue-moment'

Vue.config.productionTip = false

Vue.use(http, { store })
Vue.use(Vuetify)
Vue.use(VeeValidate, {
  locale: 'ja',
  dictionary: {
    ja: jaValidationMessages
  }
})
Vue.use(VueMoment)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
