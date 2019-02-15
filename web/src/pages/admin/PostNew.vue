<template lang="pug">
  v-container
    v-breadcrumbs(:items="breadcrumbs")
    PostForm(is-new, :save="save", @saved="onSaved")
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import PostForm from '@/components/PostForm'

export default {
  name: 'PostNew',
  components: { PostForm },
  data () {
    return {
      breadcrumbs: [
        {
          text: 'エントリ管理',
          disabled: false,
          exact: true,
          href: '/posts'
        },
        {
          text: '新規エントリ作成',
          disabled: true
        }
      ]
    }
  },
  computed: {
    ...mapState('posts', [
      'post'
    ]),
    ...mapState('settings', [
      'settings'
    ])
  },
  methods: {
    ...mapMutations('posts', [
      'setPost'
    ]),
    ...mapMutations('leaveDialog', [
      'onLeave'
    ]),
    ...mapActions('posts', [
      'create'
    ]),
    async save (input) {
      await this.create(input)
    },
    onSaved () {
      this.$router.push(`/posts/${this.post.id}`)
    }
  },
  created () {
    this.setPost({
      title: '',
      body: '',
      comment_type: this.settings.comment_type,
      like_enabled: this.settings.like_enabled,
      is_public: false,
      tags: [],
      created_at: null,
      updated_at: null
    })
  },
  beforeRouteLeave (to, from, next) {
    this.onLeave({to, from, next})
  }
}
</script>
