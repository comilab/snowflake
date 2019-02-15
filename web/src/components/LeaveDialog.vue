<template lang="pug">
  Dialog(v-model="dialogState", title="編集中のデータがあります", @exec="changeRoute", color="warning")
    div ページを移動すると編集中のデータが失われます。移動しますか？
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import Dialog from '@/components/Dialog'

export default {
  name: 'LeaveDialog',
  components: {
    Dialog
  },
  props: {
    enableCond: {
      type: Function,
      default: () => true,
      required: false
    }
  },
  data () {
    return {
      dialogState: false,
      toRoute: null
    }
  },
  computed: {
    ...mapState('leaveDialog', [
      'enabled',
      'from',
      'to',
      'next'
    ])
  },
  watch: {
    next (newValue, oldValue) {
      if (newValue) {
        this.beforeRouteLeave()
      }
    }
  },
  methods: {
    ...mapMutations('leaveDialog', [
      'enable',
      'disable'
    ]),
    beforeRouteLeave () {
      if (this.enabled && this.enableCond()) {
        this.dialogState = true
        return this.next(false)
      }
      window.removeEventListener('beforeunload', this.onLeave)
      this.next()
    },
    onLeave () {
      if (this.enabled && this.enableCond()) {
        return true
      }
    },
    changeRoute () {
      window.removeEventListener('beforeunload', this.onLeave)
      this.disable()
      this.$router.push(this.to.path)
    }
  },
  created () {
    window.removeEventListener('beforeunload', this.onLeave)
    window.addEventListener('beforeunload', this.onLeave)
    this.enable()
  }
}
</script>
