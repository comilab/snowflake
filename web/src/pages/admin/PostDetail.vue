<template lang="pug">
  v-container(v-if="post")
    v-breadcrumbs(:items="breadcrumbs")
    PostForm(:save="save")
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import PostForm from '@/components/PostForm'

export default {
  name: 'PostDetail',
  components: { PostForm },
  data () {
    return {
      breadcrumbs: [
        {
          text: 'エントリ管理',
          disabled: false,
          exact: true,
          to: '/posts'
        },
        {
          text: '',
          disabled: true
        }
      ]
    }
  },
  computed: {
    ...mapState('posts', [
      'post'
    ])
  },
  watch: {
    '$route': 'load'
  },
  methods: {
    ...mapMutations('leaveDialog', [
      'onLeave'
    ]),
    ...mapActions('posts', [
      'show',
      'update',
      'delete'
    ]),
    async load () {
      await this.show({id: this.$route.params.id})
    },
    async save (input) {
      await this.update(input)
    }
  },
  async created () {
    await this.load()
    this.breadcrumbs[1].text = this.post.title
  },
  beforeRouteLeave (to, from, next) {
    this.onLeave({to, from, next})
  }
}
</script>
