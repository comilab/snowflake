<template lang="pug">
  v-container
    v-card
      v-card-text
        v-form(ref="form", @submit.prevent="execSave")
          v-text-field(
            v-model="input.site_title",
            label="サイト名",
            data-vv-name="site_title",
            data-vv-as="サイト名",
            v-validate="'required'",
            :error-messages="errors.collect('site_title')"
          )
          v-text-field(
            v-model="input.self.name",
            label="管理者名",
            data-vv-name="name",
            data-vv-as="管理者名",
            v-validate="'required'",
            :error-messages="errors.collect('name')"
          )
          v-text-field(
            type="number",
            min="1",
            v-model="input.per_page",
            label="1ページあたりに表示するエントリ件数",
            suffix="件",
            data-vv-name="per_page",
            data-vv-as="件数",
            v-validate="'required|integer|min_value:1'",
            :error-messages="errors.collect('per_page')"
          )
          v-checkbox(v-model="input.comment_enabled", label="コメント機能を使用する")
          v-checkbox(v-model="input.like_enabled", label="いいね機能を使用する")
          v-text-field(
            v-model="input.self.email",
            label="メールアドレス",
            data-vv-name="email",
            data-vv-as="メールアドレス",
            v-validate="'required|email'",
            :error-messages="errors.collect('email')"
          )
          v-text-field(
            v-model="input.self.password",
            type="password",
            label="パスワード",
            data-vv-name="password",
            data-vv-as="パスワード",
            v-validate="'alpha_dash'",
            :error-messages="errors.collect('password')"
          )

          v-btn.mt-5(type="submit", :disabled="states.saving", color="primary") 保存

    LeaveDialog(:enable-cond="isInputUpdated")
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import _ from 'lodash'
import LeaveDialog from '@/components/LeaveDialog'

export default {
  name: 'Setting',
  components: {
    LeaveDialog
  },
  data () {
    return {
      input: {},
      states: {
        saving: false
      }
    }
  },
  computed: {
    ...mapState('settings', [
      'settings'
    ]),
    ...mapState('sessions', [
      'self'
    ])
  },
  methods: {
    ...mapMutations('snackbar', {
      showSnackbar: 'show',
      closeSnackbar: 'close'
    }),
    ...mapMutations('leaveDialog', [
      'onLeave'
    ]),
    ...mapActions('settings', {
      save: 'create'
    }),
    ...mapActions('sessions', {
      getSession: 'show'
    }),
    init () {
      this.input = _.cloneDeep(this.settings)
      this.input.self = _.cloneDeep(this.self)
    },
    isInputUpdated () {
      return !_.isEqual(this.input, _.assign({self: this.self}, this.settings))
    },
    async execSave () {
      const isValid = await this.$validator.validateAll()
      if (!isValid) {
        return
      }

      this.states.saving = true
      this.closeSnackbar()

      await this.save(this.input)
      await this.getSession()
      this.init()

      this.showSnackbar('保存しました')
      this.states.saving = false
    }
  },
  async created () {
    this.init()
  },
  beforeRouteLeave (to, from, next) {
    this.onLeave({to, from, next})
  }
}
</script>
