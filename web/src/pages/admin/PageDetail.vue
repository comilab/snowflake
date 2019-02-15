<template lang="pug">
  v-container(v-if="page")
    v-breadcrumbs(:items="breadcrumbs")
    PageForm(:save="save")
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import PageForm from '@/components/PageForm'

export default {
  name: 'PageDetail',
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
          text: '',
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
  watch: {
    '$route': 'load'
  },
  methods: {
    ...mapMutations('leaveDialog', [
      'onLeave'
    ]),
    ...mapActions('pages', [
      'show',
      'update'
    ]),
    async load () {
      await this.show({id: this.$route.params.id})
      this.breadcrumbs[1].text = this.page.title
    },
    async save (input) {
      await this.update(input)
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
