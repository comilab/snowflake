<template lang="pug">
  .login-form.ma-auto
    v-card
      v-card-title.secondary.white--text.title ログイン
      v-card-text
        v-form(@submit.prevent="login")
          v-text-field(v-model="input.email",
            label="メールアドレス",
            data-vv-name="email",
            data-vv-as="メールアドレス",
            v-validate="'required'",
            :error-messages="errors.collect('email')"
          )
          v-text-field(v-model="input.password",
            type="password",
            label="パスワード",
            data-vv-name="password",
            data-vv-as="パスワード",
            v-validate="'required'",
            :error-messages="errors.collect('password')"
          )
          v-btn(type="submit", :disabled="errors.any() || loggingIn", color="primary") ログイン
</template>

<script>
import { mapMutations, mapActions } from 'vuex'

export default {
  name: 'Login',
  data () {
    return {
      input: {
        email: '',
        password: '',
        remember: true
      },
      loggingIn: false
    }
  },
  methods: {
    ...mapMutations('snackbar', {
      showSnackbar: 'show'
    }),
    ...mapActions('sessions', [
      'create'
    ]),
    ...mapActions('settings', {
      getSettings: 'index'
    }),
    async login () {
      const isValid = await this.$validator.validateAll()
      if (!isValid) {
        return
      }

      let succeed = true

      this.loggingIn = true
      await this.create(this.input)
        .catch(() => {
          succeed = false
          this.showSnackbar('ログインできませんでした')
        })
      this.loggingIn = false

      if (succeed) {
        await this.getSettings()
        this.$router.push('posts')
      }
    }
  }
}
</script>

<style scoped>
  .login-form {
    width: 400px;
    max-width: 90%;
  }
</style>
