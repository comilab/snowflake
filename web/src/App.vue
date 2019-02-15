<template lang="pug">
  div
    v-app(v-if="!isLogin || (isLogin && settings)")
      v-toolbar.toolbar(color="blue-grey", dark, fixed)
        v-toolbar-title snowflake
        v-spacer
        v-toolbar-items.hidden-sm-and-down(v-if="isLogin")
          v-btn(flat, to="/posts") エントリ管理
          v-btn(flat, to="/pages", v-if="isAdmin") ページ管理
          v-btn(flat, to="/uploads") ファイル管理
          v-btn(flat, to="/settings", v-if="isAdmin") 設定
          v-divider(vertical)
          v-btn(flat, @click="states.logoutDialog = true", :disabled="states.logout", color="danger") ログアウト
        v-toolbar-items.hidden-md-and-up(v-if="isLogin")
          v-spacer
          v-btn(flat, icon, @click="states.menu = !states.menu")
            v-icon menu
      v-progress-linear.progressbar(:indeterminate="true", color="accent", height="5px", v-show="progressbar")

      v-navigation-drawer(absolute, temporary, v-model="states.menu")
        v-list(dense)
          v-list-tile(to="/posts")
            v-list-tile-content
              v-list-tile-title エントリ管理
          v-list-tile(to="/pages")
            v-list-tile-content
              v-list-tile-title ページ管理
          v-list-tile(to="/uploads")
            v-list-tile-content
              v-list-tile-title ファイル管理
          v-list-tile(to="/settings")
            v-list-tile-content
              v-list-tile-title 設定
          v-divider
          v-list-tile(@click="states.logoutDialog = true")
            v-list-tile-content
              v-list-tile-title ログアウト

      router-view.main

      v-snackbar.snackbar(v-model="snackbarVisible", bottom, right)
        span {{ snackbarText }}
        v-btn(flat, dark, @click="closeSnackbar")
          v-icon(small) close

      Dialog(v-model="states.logoutDialog", title="ログアウト", :executingState="states.loggingOut", @exec="logout", color="warning")
        div ログアウトしてよろしいですか？
</template>

<script>
import { mapState, mapGetters, mapMutations, mapActions } from 'vuex'
import Dialog from '@/components/Dialog'

export default {
  name: 'App',
  components: {
    Dialog
  },
  data () {
    return {
      states: {
        menu: false,
        logoutDialog: false,
        loggingOut: false
      }
    }
  },
  computed: {
    ...mapState('progressbar', {
      progressbar: 'state'
    }),
    ...mapState('snackbar', {
      snackbarText: 'text'
    }),
    ...mapState('settings', [
      'settings'
    ]),
    ...mapGetters('sessions', [
      'isLogin',
      'isAdmin'
    ]),
    snackbarVisible: {
      get () {
        return !!this.snackbarText
      },
      set (value) {
        if (!value) {
          this.setSnackbarText('')
        }
      }
    }
  },
  methods: {
    ...mapMutations('progressbar', {
      progressStart: 'start',
      progressEnd: 'end'
    }),
    ...mapMutations('snackbar', {
      closeSnackbar: 'close',
      setSnackbarText: 'setText'
    }),
    ...mapActions('sessions', {
      checkAuth: 'show',
      sessionsDelete: 'delete'
    }),
    ...mapActions('settings', {
      getSettings: 'index'
    }),
    async logout () {
      this.states.loggingOut = true

      await this.sessionsDelete()

      this.states.loggingOut = false
      this.states.logoutDialog = false

      location.href = '/admin'
    }
  },
  created () {
    this.checkAuth()
    this.getSettings()
  }
}
</script>

<style scoped>
  .main {
    margin-top: 64px;
    position: relative;
  }
  .progressbar {
    position: fixed;
    top: 0;
    margin: 0;
    z-index: 500;
  }
</style>
