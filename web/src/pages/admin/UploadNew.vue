<template lang="pug">
  v-container
    v-breadcrumbs(:items="breadcrumbs")
    UploadForm(is-new, @saved="onSaved")
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import UploadForm from '@/components/UploadForm'

export default {
  name: 'UploadNew',
  components: { UploadForm },
  data () {
    return {
      breadcrumbs: [
        {
          text: 'ファイル管理',
          disabled: false,
          exact: true,
          href: '/uploads'
        },
        {
          text: '新規ファイル登録',
          disabled: true
        }
      ]
    }
  },
  computed: {
    ...mapState('uploads', [
      'upload'
    ])
  },
  methods: {
    ...mapMutations('uploads', [
      'setUpload'
    ]),
    ...mapMutations('leaveDialog', {
      onLeave: 'onLeave',
      disableLeaveDialog: 'disable'
    }),
    onSaved () {
      this.disableLeaveDialog()
      this.$router.push(`/uploads/${this.upload.id}`)
    }
  },
  created () {
    this.setUpload({
      name: ''
    })
  },
  beforeRouteLeave (to, from, next) {
    this.onLeave({to, from, next})
  }
}
</script>
