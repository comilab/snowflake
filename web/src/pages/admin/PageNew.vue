<template lang="pug">
  v-container
    v-breadcrumbs(:items="breadcrumbs")
    PageForm(ref="form", is-new, :save="save", @saved="onSaved")
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import PageForm from '@/components/PageForm'

export default {
  name: 'PageNew',
  components: { PageForm },
  data () {
    return {
      breadcrumbs: [
        {
          text: 'ページ管理',
          disabled: false,
          exact: true,
          href: '/pages'
        },
        {
          text: '新規ページ作成',
          disabled: true
        }
      ]
    }
  },
  computed: {
    ...mapState('pages', [
      'page'
    ])
  },
  methods: {
    ...mapMutations('pages', [
      'setPage'
    ]),
    ...mapMutations('leaveDialog', [
      'onLeave'
    ]),
    ...mapActions('pages', [
      'create'
    ]),
    async save (input) {
      await this.create(input)
    },
    onSaved () {
      this.$router.push(`/pages/${this.page.id}`)
    }
  },
  created () {
    this.setPage({
      title: '',
      body: '',
      slug: '',
      is_public: false,
      created_at: null
    })
  },
  beforeRouteLeave (to, from, next) {
    this.onLeave({to, from, next})
  }
}
</script>
