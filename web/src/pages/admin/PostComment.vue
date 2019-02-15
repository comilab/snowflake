<template lang="pug">
  div
    v-container
      v-breadcrumbs(:items="breadcrumbs")
      //v-breadcrumbs
        v-breadcrumbs-item(:exact="true", to="/posts") エントリ管理
        v-breadcrumbs-item(active-class="", :to="`/posts/${post.id}`") エントリ管理
        v-breadcrumbs-item(:disable="true", to="/posts") エントリ管理

      v-card.my-4(v-for="comment in comments", :key="comment.id")
        v-card-title.pb-1
          .headline {{ comment.name }} さん
        v-card-text.py-1 {{ comment.body }}
        v-card-actions
          span.ml-2 投稿日: {{ comment.created_at | moment('YYYY/MM/DD HH:mm') }}
          v-spacer
          v-tooltip(top)
            template(slot="activator")
              v-btn(icon, @click="openDeleteDialog(comment)")
                v-icon delete
            span 削除

      v-card(v-if="!comments.length")
        v-card-text コメントがありません

      .text-xs-center.pagination.mt-3
        v-pagination(v-model="query.page", :length="lastPage", @input="search")

    Dialog(
      v-model="states.deleteDialog",
      title="コメント削除",
      :executing-state="states.deleting",
      @exec="deleteComment",
      color="warning"
    )
      div 削除してよろしいですか？
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import _ from 'lodash'
import Dialog from '@/components/Dialog'
import TagInput from '@/components/TagInput'

export default {
  name: 'PostList',
  components: {
    TagInput,
    Dialog
  },
  data () {
    return {
      query: {
        page: 1
      },
      states: {
        deleteDialog: false,
        deleting: false
      },
      deleteItem: null,
      breadcrumbs: [
        {
          text: 'エントリ管理',
          disabled: false,
          to: '/posts',
          exact: true
        },
        {
          text: '',
          disabled: false,
          exact: true
        },
        {
          text: 'コメント',
          disabled: true
        }
      ]
    }
  },
  computed: {
    ...mapState('comments', [
      'comments',
      'lastPage'
    ]),
    ...mapState('posts', [
      'post'
    ])
  },
  watch: {
    '$route': 'init'
  },
  methods: {
    ...mapMutations('snackbar', {
      showSnackbar: 'show',
      closeSnackbar: 'close'
    }),
    ...mapActions('posts', {
      getPost: 'show'
    }),
    ...mapActions('comments', [
      'index',
      'delete'
    ]),
    search () {
      this.$router.push({
        query: this.query
      })
    },
    async init () {
      this.query = _.assign({
        page: 1,
        post_id: this.$route.params.post_id
      }, this.$route.query)

      await Promise.all([
        this.index(this.query),
        this.getPost({id: this.$route.params.post_id})
      ])
      this.breadcrumbs[1].text = this.post.title
      this.breadcrumbs[1].to = `/posts/${this.post.id}`
    },
    openDeleteDialog (comment) {
      this.deleteItem = comment
      this.states.deleteDialog = true
    },
    async deleteComment () {
      this.states.deleting = true
      this.closeSnackbar()

      await this.delete({
        post_id: this.$route.params.post_id,
        id: this.deleteItem.id
      })

      this.showSnackbar('削除しました')
      this.states.deleting = false
      this.states.deleteDialog = false

      this.init()
    }
  },
  created () {
    this.init()
  }
}
</script>

<style scoped>
  .grid-container .grid-body-text {
    max-height: 7.5rem;
    overflow: hidden;
  }
  .tag-link >>> .v-chip__content {
    cursor: pointer !important;
  }
  .pagination {
    margin-bottom: 6rem;
  }
</style>
