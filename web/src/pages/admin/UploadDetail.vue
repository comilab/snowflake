<template lang="pug">
  v-container
    v-breadcrumbs(:items="breadcrumbs")
    UploadForm(v-if="upload")
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import UploadForm from '@/components/UploadForm'

export default {
  name: 'PostDetail',
  components: { UploadForm },
  data () {
    return {
      breadcrumbs: [
        {
          text: 'ファイル管理',
          disabled: false,
          exact: true,
          to: '/uploads'
        },
        {
          text: '',
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
  watch: {
    '$route': 'load'
  },
  methods: {
    ...mapMutations('leaveDialog', [
      'onLeave'
    ]),
    ...mapActions('uploads', [
      'show'
    ]),
    async load () {
      await this.show({id: this.$route.params.id})
      this.breadcrumbs[1].text = this.upload.name
    }
  },
  async created () {
    await this.load()
  },
  beforeRouteLeave (to, from, next) {
    this.onLeave({to, from, next})
  }
}
</script>
